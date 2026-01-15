<?php

declare(strict_types=1);

use SimpleBlog\Support\Html;

$container = require __DIR__ . '/bootstrap.php';
$postService = $container['postService'];
$posts = $postService->all();

include __DIR__ . '/../templates/header.php';
?>

<h1>Simple Blog</h1>
<p class="intro">A lightweight PHP blog with SQLite/MySQL storage.</p>
<a class="button" href="create.php">Create New Post</a>

<section class="posts">
    <?php if ($posts === []): ?>
        <p class="empty">No posts yet. Start by creating your first entry.</p>
    <?php endif; ?>
    <?php foreach ($posts as $post): ?>
        <article class="post">
            <h2><?= Html::escape($post['title']); ?></h2>
            <p><?= nl2br(Html::escape($post['content'])); ?></p>
            <div class="actions">
                <a href="view.php?id=<?= (int) $post['id']; ?>">View</a>
                <a href="edit.php?id=<?= (int) $post['id']; ?>">Edit</a>
                <a href="delete.php?id=<?= (int) $post['id']; ?>" data-confirm="Are you sure?">Delete</a>
            </div>
        </article>
    <?php endforeach; ?>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>
