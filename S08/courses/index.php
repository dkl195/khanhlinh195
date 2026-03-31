<?php
require_once __DIR__ . '/../classes/Database.php';

$db = Database::getInstance();
$courses = $db->fetchAll("SELECT * FROM courses");
?>

<h2>Courses</h2>

<a href="create.php">+ Add Course</a>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Description</th>
        <th>Action</th>
    </tr>

    <?php foreach ($courses as $c): ?>
        <tr>
            <td><?= $c['id'] ?></td>
            <td><?= htmlspecialchars($c['title']) ?></td>
            <td><?= htmlspecialchars($c['description']) ?></td>
            <td>
                <a href="edit.php?id=<?= $c['id'] ?>">Edit</a> |
                <a href="delete.php?id=<?= $c['id'] ?>" onclick="return confirm('Delete?')">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>