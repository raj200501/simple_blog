<?php

declare(strict_types=1);

$container = require __DIR__ . '/bootstrap.php';
$postService = $container['postService'];

$id = (int) ($_GET['id'] ?? 0);
if ($id > 0) {
    $postService->delete($id);
}

header('Location: index.php');
exit;
