<?php
require_once '../config/config.php';
require_once '../src/Controller/PostController.php';

$controller = new PostController($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->update($_POST['id'], $_POST['title'], $_POST['content']);
    header('Location: index.php');
}

$post = $controller->edit($_GET['id']);

include '../templates/header.php';
?>

<h1>Edit Post</h1>
<form method="post">
    <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
    <label for="title">Title</label>
    <input type="text" id="title" name="title" value="<?php echo $post['title']; ?>" required>
    <br>
    <label for="content">Content</label>
    <textarea id="content" name="content" required><?php echo $post['content']; ?></textarea>
    <br>
    <button type="submit">Update</button>
</form>

<?php include '../templates/footer.php'; ?>
