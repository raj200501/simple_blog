<?php
require_once '../config/config.php';
require_once '../src/Controller/PostController.php';

$controller = new PostController($conn);
$posts = $controller->index();

include '../templates/header.php';
?>

<h1>Simple Blog</h1>
<a href="create.php">Create New Post</a>

<?php foreach ($posts as $post): ?>
    <h2><?php echo $post['title']; ?></h2>
    <p><?php echo $post['content']; ?></p>
    <a href="view.php?id=<?php echo $post['id']; ?>">View</a>
    <a href="edit.php?id=<?php echo $post['id']; ?>">Edit</a>
    <a href="delete.php?id=<?php echo $post['id']; ?>">Delete</a>
<?php endforeach; ?>

<?php include '../templates/footer.php'; ?>
