<?php
require_once __DIR__ . '/../classes/Database.php';

$db = Database::getInstance();

$sql = "
SELECT e.id, s.name AS student_name, c.title AS course_title, e.enrolled_at
FROM enrollments e
JOIN students s ON e.student_id = s.id
JOIN courses c ON e.course_id = c.id
ORDER BY e.enrolled_at DESC
";

$enrollments = $db->fetchAll($sql);
?>

<h2>Enrollments</h2>

<a href="create.php">+ Add Enrollment</a>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Student</th>
        <th>Course</th>
        <th>Time</th>
        <th>Action</th>
    </tr>

    <?php foreach ($enrollments as $e): ?>
        <tr>
            <td><?= $e['id'] ?></td>
            <td><?= htmlspecialchars($e['student_name']) ?></td>
            <td><?= htmlspecialchars($e['course_title']) ?></td>
            <td><?= $e['enrolled_at'] ?></td>
            <td>
                <a href="delete.php?id=<?= $e['id'] ?>" onclick="return confirm('Delete?')">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>