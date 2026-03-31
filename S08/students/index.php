<?php
require_once __DIR__ . '/../classes/Database.php';

$db = Database::getInstance();
$students = $db->fetchAll("SELECT * FROM students ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="../index.php">School App</a>
        <div class="navbar-nav">
            <a class="nav-link active" href="index.php">Students</a>
            <a class="nav-link" href="../courses/index.php">Courses</a>
            <a class="nav-link" href="../enrollments/index.php">Enrollments</a>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Students</h1>
            <p class="text-muted mb-0">Manage student information</p>
        </div>
        <a href="create.php" class="btn btn-primary">+ Add Student</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <?php if (empty($students)): ?>
                <div class="alert alert-info mb-0">No students found.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $s): ?>
                                <tr>
                                    <td><?= $s['id'] ?></td>
                                    <td><?= htmlspecialchars($s['name']) ?></td>
                                    <td><?= htmlspecialchars($s['email']) ?></td>
                                    <td class="text-center">
                                        <a href="edit.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <a href="delete.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this student?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>