<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();

use \App\Controller\AuthController;

if (!isset($_SESSION['email'])) {
    header("Location: /Blog/src/views/login.php");
    exit();
}

$auth = new AuthController();
$auth->changePassword();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa, #e0eafc);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
</head>
<body>
<div class="container my-5">
    <div class="d-flex justify-content-between">
        <a href="/Blog/src/views/post_list.php" class="btn btn-primary mb-4 p-2">Home</a>

        <span class="me-3 fw-bold text-dark">Welcome, <?php echo $_SESSION['username']; ?>!</span>
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                Menu
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item" href="./view_profile.php">My Profile </a></li>
                <li><a class="dropdown-item" href="./change_password.php">Change Password</a></li>
                <li><a class="dropdown-item" href="./logout.php">Logout</a></li>
            </ul>
        </div>

    </div>

    <div class="card mt-4 p-4 shadow-sm">
        <h3 class="mb-4">🔒 Change Password</h3>
        <form action="" method="post">
            <div class="mb-3">
                <label for="current_password" class="form-label">Current Password</label>
                <input type="password" class="form-control" id="current_password" name="current_password" required>
            </div>

            <div class="mb-3">
                <label for="new_password" class="form-label">New Password</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>

            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm New Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn btn-success mb-3">🔄 Update Password</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
