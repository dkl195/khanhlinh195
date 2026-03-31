<?php
require_once __DIR__ . '/classes/Database.php';

$db = Database::getInstance();
$students = $db->fetchAll("SELECT * FROM students");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>School App</title>
</head>
<body>
    <h1>School Management App</h1>
    <p><a href="students/index.php">Manage Students</a></p>
    <p><a href="courses/index.php">Manage Courses</a></p>
    <p><a href="enrollments/index.php">Manage Enrollments</a></p>

    <h2>Quick Test: Students</h2>

    <?php if (empty($students)): ?>
        <p>No students found.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($students as $s): ?>
                <li>
                    <?= htmlspecialchars($s['name']) ?> -
                    <?= htmlspecialchars($s['email']) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>