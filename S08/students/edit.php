<?php
require_once __DIR__ . '/../classes/Database.php';

$db = Database::getInstance();

$id = $_GET['id'];
$student = $db->fetch("SELECT * FROM students WHERE id = ?", [$id]);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];

    // duplicate check (EXCLUDE SELF)
    $existing = $db->fetch(
        "SELECT id FROM students WHERE email = ? AND id <> ?",
        [$email, $id]
    );

    if ($existing) {
        $errors[] = "Email already used by another";
    }

    if (empty($errors)) {
        $db->update('students', [
            'name' => $name,
            'email' => $email
        ], "id = ?", [$id]);

        header("Location: index.php");
        exit;
    }
}
?>

<h2>Edit Student</h2>

<?php foreach ($errors as $e): ?>
    <p style="color:red"><?= $e ?></p>
<?php endforeach; ?>

<form method="POST">
    Name: <input name="name" value="<?= htmlspecialchars($student['name']) ?>"><br><br>
    Email: <input name="email" value="<?= htmlspecialchars($student['email']) ?>"><br><br>
    <button type="submit">Update</button>
</form>