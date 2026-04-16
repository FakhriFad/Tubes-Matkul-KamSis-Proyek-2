<?php
require 'config.php';
require 'vendor/autoload.php'; 

use RobThree\Auth\TwoFactorAuth;

$tfa = new TwoFactorAuth('YourAppName');
$message = "";
$toastClass = "";

if (!isset($_SESSION['auth_pending_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        die("CSRF token validation failed.");
    }

    $code = trim($_POST['mfa_code'] ?? '');

    if (empty($code)) {
        $message = "Please enter the 6-digit code.";
        $toastClass = "bg-warning";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, email, mfa_secret FROM users WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $_SESSION['auth_pending_id']]);
            $user = $stmt->fetch();

            if ($user && $tfa->verifyCode($user['mfa_secret'], $code)) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                
                unset($_SESSION['auth_pending_id']);

                session_regenerate_id(true);
                header("Location: dashboard.php");
                exit();
            } else {
                $message = "Invalid verification code.";
                $toastClass = "bg-danger";
            }
        } catch (Exception $e) {
            $message = "Verification failed. Please try again.";
            error_log("MFA Error: " . $e->getMessage());
            $toastClass = "bg-danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="shortcut icon" href="https://cdn-icons-png.flaticon.com/512/295/295128.png">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../css/login.css">
    <title>Verify MFA</title>
</head>

<body class="bg-light">
    <div class="container p-5 d-flex flex-column align-items-center">
        <?php if ($message): ?>
            <div class="toast align-items-center text-white <?php echo $toastClass; ?> border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>

        <form action="" method="post" class="form-control mt-5 p-4"
            style="height:auto; width:380px; box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
            
            <div class="row">
                <i class="fa fa-shield fa-3x mt-1 mb-2" style="text-align: center; color: green;"></i>
                <h5 class="text-center p-4" style="font-weight: 700;">Two-Factor Authentication</h5>
                <p class="text-center small text-muted">Enter the 6-digit code from your authenticator app.</p>
            </div>

            <div class="col-mb-3 mt-2">
                <label for="mfa_code"><i class="fa fa-key"></i> Authenticator Code</label>
                <input type="text" name="mfa_code" id="mfa_code" 
                       class="form-control text-center" 
                       placeholder="000000" 
                       maxlength="6" 
                       inputmode="numeric" 
                       pattern="[0-9]*" 
                       required autofocus>
            </div>

            <div class="col mb-3 mt-4">
                <button type="submit" class="btn btn-success w-100" style="font-weight: 600;">Verify Code</button>
            </div>
            
            <div class="col mb-2 mt-3">
                <p class="text-center" style="font-weight: 600;"><a href="./logout.php" style="text-decoration: none; color: navy;">Cancel Login</a></p>
            </div>
        </form>
    </div>

    <script>
        var toastElList = [].slice.call(document.querySelectorAll('.toast'))
        var toastList = toastElList.map(function (toastEl) {
            return new bootstrap.Toast(toastEl, { delay: 3000 });
        });
        toastList.forEach(toast => toast.show());
    </script>
</body>
</html>