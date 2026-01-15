<?php

declare(strict_types=1);

use SimpleBlog\Support\Html;

$container = require __DIR__ . '/bootstrap.php';
$postService = $container['postService'];

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$post = $postService->find($id);

include __DIR__ . '/../templates/header.php';
?>

<article class="post">
    <h1><?= Html::escape($post['title']); ?></h1>
    <p><?= nl2br(Html::escape($post['content'])); ?></p>
    <div class="actions">
        <a href="edit.php?id=<?= $id; ?>" class="button">Edit</a>
        <a href="delete.php?id=<?= $id; ?>" class="button secondary">Delete</a>
        <a href="index.php" class="button secondary">Back</a>
    </div>
</article>

<?php include __DIR__ . '/../templates/footer.php'; ?>
