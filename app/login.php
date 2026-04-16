<?php
require 'config.php';

$message = "";
$toastClass = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        die("CSRF token validation failed.");
    }

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if(empty($email) || empty($password)){
        $message = "Please enter a valid email and password";
        $toastClass = "bg-warning";
    }else{
        try{
            $stmt = $pdo->prepare("SELECT id, password, failed_attempts, account_locked_until, mfa_secret, mfa_enabled FROM users WHERE email = :email LIMIT 1");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if($user){
                if($user['account_locked_until'] && strtotime($user['account_locked_until']) > time()){
                    $message = "Account temp lock, try again later.";
                    $toastClass = "bg-danger";
                }elseif(password_verify($password, $user['password'])){
                    $stmt = $pdo->prepare("UPDATE users SET failed_attempts = 0, account_locked_until = NULL WHERE id = :id");
                    $stmt->execute(['id' => $user['id']]);

                    $_SESSION['auth_pending_id'] = $user['id'];

                    if ($user['mfa_enabled'] && !empty($user['mfa_secret'])) {
                        header("Location: verifymfa.php");
                        exit();
                    } else {
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['email'] = $email;
                        unset($_SESSION['auth_pending_id']);
                        
                        session_regenerate_id(true);
                        header("Location: dashboard.php");
                        exit();
                    }
                }else{
                    $new_attempts = $user['failed_attempts'] + 1;
                    $lock_until = null;

                    if($new_attempts >= 5){
                        $lock_until = date("Y-m-d H:i:s", strtotime("+1 minutes"));
                        $message = "Too many failed attempts. Account locked for 1 minutes.";
                    }else{
                        $message = "Incorrect email or password";
                    }
                    
                    $stmt = $pdo->prepare("UPDATE users SET failed_attempts = :attempts, account_locked_until = :lock WHERE id = :id");
                    $stmt->execute([
                        'attempts'=> $new_attempts,
                        'lock' => $lock_until,
                        'id' => $user['id']
                    ]);
                    $toastClass = "bg-danger";
                }
            }else{
                $message = "Invalid email or password.";
                $toastClass = "bg-danger";
            }
        }catch(PDOException $e){
            $message = "Something went wrong. Please try again.";
            error_log("Login Error: " . $e->getMessage());
            $toastClass = "bg-danger";
        } 
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" 
          content="width=device-width, initial-scale=1.0">
    <link href=
"https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href=
"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="shortcut icon" href=
"https://cdn-icons-png.flaticon.com/512/295/295128.png">
    <script src=
"https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../css/login.css">
    <title>Login Page</title>
</head>

<body class="bg-light">
    <div class="container p-5 d-flex flex-column align-items-center">
        <?php if ($message): ?>
            <div class="toast align-items-center text-white 
            <?php echo $toastClass; ?> border-0" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                    <button type="button" class="btn-close
                    btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>
        <form action="" method="post" class="form-control mt-5 p-4"
            style="height:auto; width:380px; box-shadow: rgba(60, 64, 67, 0.3) 
            0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
            <div class="row">
                <i class="fa fa-user-circle-o fa-3x mt-1 mb-2"
          style="text-align: center; color: green;"></i>
                <h5 class="text-center p-4" 
          style="font-weight: 700;">Login Into Your Account</h5>
            </div>
            <div class="col-mb-3">
                <label for="email"><i 
                  class="fa fa-envelope"></i> Email</label>
                <input type="text" name="email" id="email"
                  class="form-control" required>
            </div>
            <div class="col mb-3 mt-3">
                <label for="password"><i
                  class="fa fa-lock"></i> Password</label>
                <input type="password" name="password" id="password" 
                  class="form-control" required>
            </div>
            <div class="col mb-3 mt-3">
                <button type="submit" 
                  class="btn btn-success bg-success" style="font-weight: 600;">Login</button>
            </div>
            <div class="col mb-2 mt-4">
                <p class="text-center" 
                  style="font-weight: 600; color: navy;"
                  ><a href="./register.php"
                        style="text-decoration: none;">Create Account</a> OR <a href="./resetpassword.php"
                        style="text-decoration: none;">Forgot Password</a></p>
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