<?php
require_once '../config/config.php';
require_once '../src/Controller/PostController.php';

$controller = new PostController($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->create($_POST['title'], $_POST['content']);
    header('Location: index.php');
}

include '../templates/header.php';
?>

<h1>Create New Post</h1>
<form method="post">
    <label for="title">Title</label>
    <input type="text" id="title" name="title" required>
    <br>
    <label for="content">Content</label>
    <textarea id="content" name="content" required></textarea>
    <br>
    <button type="submit">Create</button>
</form>

<?php include '../templates/footer.php'; ?>
