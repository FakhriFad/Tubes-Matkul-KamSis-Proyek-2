<?php
require_once("config.php");

// Fetch users
$stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <title>User Table</title>
</head>

<body>

<nav class="navbar navbar-expand-sm navbar-light bg-success shadow">
    <div class="container">
        <a class="navbar-brand text-white fw-bold" href="#">
            <i class="fa fa-database"></i> User Database
        </a>

        <div class="d-flex align-items-center">
            <span class="text-white me-3">
                <i class="fa fa-user-circle"></i> 
                <?php echo htmlspecialchars($_SESSION['email']); ?>
            </span>
            <a href="logout.php" class="btn btn-light btn-sm fw-bold text-success">
                <i class="fa fa-sign-out"></i> Logout
            </a>
        </div>
    </div>
</nav>

<div class="container mt-5">

    <div class="card shadow-sm p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Users Table</h4>
            <!-- <a href="add.php" class="btn btn-success btn-sm">
                <i class="fa fa-plus"></i> Add User
            </a> -->
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-success text-center">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Password (Hash)</th>
                        <th>Failed Attempts</th>
                        <th>Last Failed Login</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="text-center"><?php echo $user['id']; ?></td>

                            <td><?php echo htmlspecialchars($user['username']); ?></td>

                            <td><?php echo htmlspecialchars($user['email']); ?></td>

                            <td style="font-size: 12px; max-width:200px; word-break: break-all;">
                                <?php echo htmlspecialchars($user['password']); ?>
                            </td>

                            <td class="text-center">
                                <?php echo $user['failed_attempts']; ?>
                            </td>

                            <td>
                                <?php echo $user['last_failed_login'] ?? '-'; ?>
                            </td>

                            <td class="text-center">
                                <?php if ($user['account_locked_until'] && strtotime($user['account_locked_until']) > time()): ?>
                                    <span class="badge bg-danger">Locked</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Active</span>
                                <?php endif; ?>
                            </td>

                            <!-- <td class="text-center">
                                <a href="edit.php?id=<?php echo $user['id']; ?>" 
                                   class="btn btn-warning btn-sm">
                                    <i class="fa fa-pencil"></i>
                                </a>

                                <a href="delete.php?id=<?php echo $user['id']; ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Delete this user?')">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td> -->
                        </tr>
                    <?php endforeach; ?>

                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                No users found
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>