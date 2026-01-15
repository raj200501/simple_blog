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

$errors = [];
$title = $post['title'] ?? '';
$content = $post['content'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = (string) ($_POST['title'] ?? '');
    $content = (string) ($_POST['content'] ?? '');

    $result = $postService->update($id, $title, $content);
    if ($result['success']) {
        header('Location: view.php?id=' . $id);
        exit;
    }

    $errors = $result['errors'];
}

include __DIR__ . '/../templates/header.php';
?>

<h1>Edit Post</h1>
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
        <button type="submit" class="button">Update</button>
        <a href="view.php?id=<?= $id; ?>" class="button secondary">Cancel</a>
    </div>
</form>

<?php include __DIR__ . '/../templates/footer.php'; ?>
