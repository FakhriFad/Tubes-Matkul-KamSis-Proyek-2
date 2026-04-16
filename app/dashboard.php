<?php
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
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
    <link rel="stylesheet" href="../css/dashboard.css">
    <title>Dashboard</title>
</head>

<body>
    <nav class="navbar navbar-expand-sm navbar-light bg-success shadow">
        <div class="container">
            <a class="navbar-brand" href="#" style="font-weight:bold; color:white;">
                <i class="fa fa-th-large"></i> Dashboard
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavId">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavId">
                <ul class="navbar-nav me-auto"></ul>
                <div class="d-flex align-items-center">
                    <span class="text-white me-3">
                        <i class="fa fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['email']); ?>
                    </span>
                    <a href="logout.php" class="btn btn-light btn-sm" style="font-weight:bolder; color:green;">
                        <i class="fa fa-sign-out"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm p-4">
                    <h2>Welcome to your Dashboard</h2>
                    <p class="text-muted">You have successfully bypassed all security checks.</p>
                    <hr>
                    <p>Your User ID is: <strong><?php echo $_SESSION['user_id']; ?></strong></p>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm p-4">
                    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['email']); ?></h2>
                    <p>You are logged into your secure area.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-warning">
                    <div class="card-body text-center">
                        <i class="fa fa-shield fa-3x text-warning mb-3"></i>
                        <h5>Account Security</h5>
                        <p class="small text-muted">Protect your account with Two-Factor Authentication (MFA).</p>
                        <a href="setupmfa.php" class="btn btn-outline-dark w-100">
                            <i class="fa fa-cog"></i> Setup MFA
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>