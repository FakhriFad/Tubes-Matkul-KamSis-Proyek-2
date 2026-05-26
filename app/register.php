<?php
require 'config.php';

$message = '';
$toastClass = '';

function isPasswordStrong($password) {
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);
    $special   = preg_match('@[^\w]@', $password);

    return ($uppercase && $lowercase && $number && $special && strlen($password) >= 8);
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        die("CSRF token validation failed.");
    }

    $username = trim($_POST['username'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if(empty($username) || !$email || empty($password)){
        $message = "Please provide a valid username, email, and password.";
        $toastClass = "#007bff";
    }elseif(!isPasswordStrong($password)){
        $message = "Password must be 8+ chars with uppercase, lowercase, number, and symbol.";
        $toastClass = "#007bff";
    }elseif(strlen($username) > 50 || strlen($email) > 100 || strlen($password) > 255){
        $message = "Input too long.";
    }else{
        $hashedPwd = password_hash($password, PASSWORD_DEFAULT);

        try{
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
            $stmt->execute([
                'username' => $username,
                'email' => $email
            ]);

            if($stmt->fetch()){
                $message = "Username or email already exists.";
                $toastClass = "#007bff";
            }else{
                $sql = "INSERT INTO users (username, email, password, mfa_enabled) VALUES (:username, :email, :password, 0)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'username' => $username,
                    'email' => $email,
                    'password' => $hashedPwd
                ]);
                $message = "Registration Success, Plese log in.";
                $toastClass = "#28a745";
            }
        }catch(PDOException $e){
            $message = "Something went wrong. Please try again.";
            error_log("Registration Error: " . $e->getMessage());
            $toastClass = "#dc3545";
        }   
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href=
"https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href=
"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="shortcut icon" href=
"https://cdn-icons-png.flaticon.com/512/295/295128.png">
    <script src=
"https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>Registration</title>
</head>

<body class="bg-light">
    <div class="container p-5 d-flex flex-column align-items-center">
        <?php if ($message): ?>
            <div class="toast align-items-center text-white border-0" 
          role="alert" aria-live="assertive" aria-atomic="true"
                style="background-color: <?php echo $toastClass; ?>;">
                <div class="d-flex">
                    <div class="toast-body">
                        <?php echo $message; ?>
                    </div>
                    <button type="button" class="btn-close
                    btn-close-white me-2 m-auto" 
                          data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>
        <form method="post" class="form-control mt-5 p-4"
            style="height:auto; width:380px;
            box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px,
            rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
            <div class="row text-center">
                <i class="fa fa-user-circle-o fa-3x mt-1 mb-2" style="color: green;"></i>
                <h5 class="p-4" style="font-weight: 700;">Create Your Account</h5>
            </div>
            <div class="mb-2">
                <label for="username"><i 
                  class="fa fa-user"></i> User Name</label>
                <input type="text" name="username" id="username"
                  class="form-control" required>
            </div>
            <div class="mb-2 mt-2">
                <label for="email"><i 
                  class="fa fa-envelope"></i> Email</label>
                <input type="text" name="email" id="email"
                  class="form-control" required>
            </div>
            <div class="mb-2 mt-2">
                <label for="password"><i 
                  class="fa fa-lock"></i> Password</label>
                <input type="password" name="password" id="password"
                  class="form-control" required>
            </div>
            <div class="mb-2 mt-3">
                <button type="submit" 
                  class="btn btn-success
                bg-success" style="font-weight: 600;">Create
                    Account</button>
            </div>
            <div class="mb-2 mt-4">
                <p class="text-center" style="font-weight: 600; 
                color: navy;">I have an Account <a href="./login.php"
                        style="text-decoration: none;">Login</a></p>
            </div>
        </form>
    </div>
    <script>
        let toastElList = [].slice.call(document.querySelectorAll('.toast'))
        let toastList = toastElList.map(function (toastEl) {
            return new bootstrap.Toast(toastEl, { delay: 3000 });
        });
        toastList.forEach(toast => toast.show());
    </script>
</body>

</html>
