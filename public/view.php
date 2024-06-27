<?php
require_once '../config/config.php';
require_once '../src/Controller/PostController.php';

$controller = new PostController($conn);
$post = $controller->view($_GET['id']);

include '../templates/header.php';
?>

<h1><?php echo $post['title']; ?></h1>
<p><?php echo $post['content']; ?></p>
<a href="index.php">Back</a>

<?php include '../templates/footer.php'; ?>
