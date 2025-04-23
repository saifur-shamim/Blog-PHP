<?php
require __DIR__ . '/../../vendor/autoload.php';

use \App\Controller\PostController;

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $postModel = new PostController();
    $postModel->createPost();
    header("Location: /Blog/src/views/post_list.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <div class="d-flex justify-content-between">

        <a href="/Blog/src/views/post_list.php" class="btn btn-success mb-4 p-2">Home</a>

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

    <div >
        <h2>Create a New Blog Post</h2>
        <form action="" method="POST">
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Content</label>
                <textarea name="content" class="form-control" rows="5" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Category</label>
                <input type="text" name="category" class="form-control" required>
            </div>



            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>


</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
