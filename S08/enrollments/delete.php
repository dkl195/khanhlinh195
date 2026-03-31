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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="../index.php">School App</a>
        <div class="navbar-nav">
            <a class="nav-link" href="../students/index.php">Students</a>
            <a class="nav-link" href="../courses/index.php">Courses</a>
            <a class="nav-link active" href="index.php">Enrollments</a>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Enrollments</h1>
            <p class="text-muted mb-0">Manage student course registrations</p>
        </div>
        <a href="create.php" class="btn btn-primary">+ Add Enrollment</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <?php if (empty($enrollments)): ?>
                <div class="alert alert-info mb-0">
                    No enrollments found.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Student</th>
                                <th>Course</th>
                                <th>Time</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($enrollments as $e): ?>
                                <tr>
                                    <td><?= $e['id'] ?></td>
                                    <td><?= htmlspecialchars($e['student_name']) ?></td>
                                    <td><?= htmlspecialchars($e['course_title']) ?></td>
                                    <td><?= htmlspecialchars($e['enrolled_at']) ?></td>
                                    <td class="text-center">
                                        <a href="delete.php?id=<?= $e['id'] ?>"
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Delete this enrollment?')">
                                           Delete
                                        </a>
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