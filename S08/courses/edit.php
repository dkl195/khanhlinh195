<?php
require_once __DIR__ . '/../classes/Database.php';

$db = Database::getInstance();

$id = (int)($_GET['id'] ?? 0);
$course = $db->fetch("SELECT * FROM courses WHERE id = ?", [$id]);

if (!$course) {
    die("Course not found");
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    if (empty($title)) {
        $errors[] = "Title is required";
    } elseif (strlen($title) < 3) {
        $errors[] = "Title must be at least 3 characters";
    }

    if (empty($errors)) {
        $db->update('courses', [
            'title' => $title,
            'description' => $description
        ], "id = ?", [$id]);

        header("Location: index.php");
        exit;
    }
}
?>

<h2>Edit Course</h2>

<?php foreach ($errors as $e): ?>
    <p style="color:red"><?= htmlspecialchars($e) ?></p>
<?php endforeach; ?>

<form method="POST">
    Title: <input name="title" value="<?= htmlspecialchars($course['title']) ?>"><br><br>
    Description: <textarea name="description"><?= htmlspecialchars($course['description']) ?></textarea><br><br>
    <button type="submit">Update</button>
</form>