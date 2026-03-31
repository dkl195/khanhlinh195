<?php
require_once __DIR__ . '/../classes/Database.php';

$db = Database::getInstance();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];

    if (empty($name)) {
        $errors[] = "Name required";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email";
    }

    // check duplicate
    $existing = $db->fetch("SELECT id FROM students WHERE email = ?", [$email]);
    if ($existing) {
        $errors[] = "Email already exists";
    }

    if (empty($errors)) {
        $db->insert('students', [
            'name' => $name,
            'email' => $email
        ]);

        header("Location: index.php");
        exit;
    }
}
?>

<h2>Add Student</h2>

<?php foreach ($errors as $e): ?>
    <p style="color:red"><?= $e ?></p>
<?php endforeach; ?>

<form method="POST">
    Name: <input name="name"><br><br>
    Email: <input name="email"><br><br>
    <button type="submit">Save</button>
</form>