<?php
require_once '../config/config.php';
require_once '../src/Controller/PostController.php';

$controller = new PostController($conn);
$controller->delete($_GET['id']);

header('Location: index.php');
?>
