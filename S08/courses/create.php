<?php
require_once __DIR__ . '/../classes/Database.php';

$db = Database::getInstance();
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
        $db->insert('courses', [
            'title' => $title,
            'description' => $description
        ]);

        header("Location: index.php");
        exit;
    }
}
?>

<h2>Add Course</h2>

<?php foreach ($errors as $e): ?>
    <p style="color:red"><?= htmlspecialchars($e) ?></p>
<?php endforeach; ?>

<form method="POST">
    Title: <input name="title"><br><br>
    Description: <textarea name="description"></textarea><br><br>
    <button type="submit">Save</button>
</form>