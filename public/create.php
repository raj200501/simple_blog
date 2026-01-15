<?php

declare(strict_types=1);

use SimpleBlog\Support\Html;

$container = require __DIR__ . '/bootstrap.php';
$postService = $container['postService'];

$errors = [];
$title = '';
$content = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = (string) ($_POST['title'] ?? '');
    $content = (string) ($_POST['content'] ?? '');

    $result = $postService->create($title, $content);
    if ($result['success']) {
        header('Location: index.php');
        exit;
    }

    $errors = $result['errors'];
}

include __DIR__ . '/../templates/header.php';
?>

<h1>Create New Post</h1>
<form method="post" class="form">
    <label for="title">Title</label>
    <input type="text" id="title" name="title" value="<?= Html::escape($title); ?>" required>
    <?php if (isset($errors['title'])): ?>
        <p class="error"><?= Html::escape($errors['title']); ?></p>
    <?php endif; ?>

    <label for="content">Content</label>
    <textarea id="content" name="content" rows="8" required><?= Html::escape($content); ?></textarea>
    <?php if (isset($errors['content'])): ?>
        <p class="error"><?= Html::escape($errors['content']); ?></p>
    <?php endif; ?>

    <div class="actions">
        <button type="submit" class="button">Create</button>
        <a href="index.php" class="button secondary">Cancel</a>
    </div>
</form>

<?php include __DIR__ . '/../templates/footer.php'; ?>
