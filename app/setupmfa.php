<?php
require 'config.php';
require 'vendor/autoload.php';

use RobThree\Auth\TwoFactorAuth;

// Protect the page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$tfa = new TwoFactorAuth('App');
$user_id = $_SESSION['user_id'];
$message = "";
$toastClass = "";

// Check current MFA status
$stmt = $pdo->prepare("SELECT mfa_enabled, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($user['mfa_enabled']) {
    $message = "MFA is already enabled on your account.";
    $toastClass = "bg-info";
}

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['verify_mfa'])) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        die("CSRF token validation failed.");
    }

    $secret = $_POST['temp_secret'];
    $code = trim($_POST['mfa_code']);

    if ($tfa->verifyCode($secret, $code)) {
        $update = $pdo->prepare("UPDATE users SET mfa_secret = ?, mfa_enabled = 1 WHERE id = ?");
        $update->execute([$secret, $user_id]);
        header("Location: dashboard.php?mfa_enabled=1");
        exit();
    } else {
        $message = "Invalid code. Please try again.";
        $toastClass = "bg-danger";
    }
}

// Prepare fresh secret for the QR code
$temp_secret = $tfa->createSecret();
$qrCodeUrl = $tfa->getQRCodeGoogleUrl($user['email'], $temp_secret);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <title>Setup MFA</title>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Setup Two-Factor Authentication</h5>
                    </div>
                    <div class="card-body p-4">
                        <?php if ($message): ?>
                            <div class="alert <?php echo $toastClass; ?> text-white"><?php echo $message; ?></div>
                        <?php endif; ?>

                        <?php if (!$user['mfa_enabled']): ?>
                            <ol>
                                <li>Install <strong>Google Authenticator</strong> or <strong>Authy</strong> on your phone.</li>
                                <li>Scan the QR code below:</li>
                            </ol>
                            
                            <div class="text-center my-4">
                                <img src="<?php echo $qrCodeUrl; ?>" class="img-fluid border p-3 bg-white">
                                <p class="mt-2 mb-0 small">Manual Entry: <code><?php echo $temp_secret; ?></code></p>
                            </div>

                            <form method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <input type="hidden" name="temp_secret" value="<?php echo $temp_secret; ?>">
                                
                                <div class="mb-3">
                                    <label class="form-label">Enter 6-digit code to verify:</label>
                                    <input type="text" name="mfa_code" class="form-control text-center" placeholder="000 000" maxlength="6" required>
                                </div>
                                <button type="submit" name="verify_mfa" class="btn btn-success w-100">Enable MFA</button>
                            </form>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fa fa-check-circle fa-4x text-success"></i>
                                <h4 class="mt-3">MFA is Active</h4>
                                <p>Your account is protected by an extra layer of security.</p>
                                <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>