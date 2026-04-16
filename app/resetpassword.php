<?php
$message = "Please contact administrator to reset your password.";
$toastClass = "bg-warning";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="shortcut icon" href="https://cdn-icons-png.flaticon.com/512/295/295128.png">
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <title>Reset Password</title>
</head>

<body>
    <div class="container p-5 d-flex flex-column align-items-center">

        <!-- Toast Message -->
        <div class="toast align-items-center text-white border-0 show"
            role="alert" aria-live="assertive" aria-atomic="true"
            style="background-color: #ffc107;">
            
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fa fa-exclamation-triangle"></i>
                    <?php echo $message; ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast"></button>
            </div>
        </div>

        <!-- Form (Disabled) -->
        <form class="form-control mt-5 p-4"
            style="height:auto; width:380px; box-shadow: rgba(60, 64, 67, 0.3) 
            0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;">
            
            <div class="row">
                <i class="fa fa-lock fa-3x mt-1 mb-2" 
                   style="text-align: center; color: orange;"></i>

                <h5 class="text-center p-4" style="font-weight: 700;">
                    Password Reset Not Implemented
                </h5>
            </div>

            <div class="alert alert-warning text-center">
                For simplicity, password reset is not implemented.<br>
                <strong>Please contact administrator.</strong>
            </div>

            <div class="col-mb-3">
                <label><i class="fa fa-envelope"></i> Email</label>
                <input type="text" class="form-control" disabled placeholder="Nope">
            </div>

            <div class="col mb-3 mt-3">
                <label><i class="fa fa-lock"></i> Password</label>
                <input type="password" class="form-control" disabled>
            </div>

            <div class="col mb-3 mt-3">
                <label><i class="fa fa-lock"></i> Confirm Password</label>
                <input type="password" class="form-control" disabled>
            </div>

            <div class="col mb-3 mt-3">
                <button type="button" class="btn btn-secondary w-100" disabled>
                    Reset Disabled
                </button>
            </div>

            <div class="col mb-2 mt-4">
                <p class="text-center" style="font-weight: 600; color: navy;">
                    <a href="./register.php" style="text-decoration: none;">
                        Create Account
                    </a> OR 
                    <a href="./login.php" style="text-decoration: none;">
                        Login
                    </a>
                </p>
            </div>

        </form>
    </div>

    <script>
        let toastElList = [].slice.call(document.querySelectorAll('.toast'))
        let toastList = toastElList.map(function (toastEl) {
            return new bootstrap.Toast(toastEl, { delay: 4000 });
        });
        toastList.forEach(toast => toast.show());
    </script>
</body>
</html>