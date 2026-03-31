<?php
require_once __DIR__ . '/../classes/Database.php';

$db = Database::getInstance();

$students = $db->fetchAll("SELECT * FROM students");
$courses = $db->fetchAll("SELECT * FROM courses");

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = (int)($_POST['student_id'] ?? 0);
    $course_id = (int)($_POST['course_id'] ?? 0);

    if ($student_id <= 0 || $course_id <= 0) {
        $error = "Please select both student and course.";
    } else {
        $exists = $db->fetch(
            "SELECT id FROM enrollments WHERE student_id = ? AND course_id = ?",
            [$student_id, $course_id]
        );

        if ($exists) {
            $error = "This student is already enrolled in this course.";
        } else {
            $db->insert('enrollments', [
                'student_id' => $student_id,
                'course_id' => $course_id
            ]);

            header("Location: index.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Enrollment</title>
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
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h1 class="h3 mb-3">Add Enrollment</h1>
                    <p class="text-muted">Assign a student to a course.</p>

                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Student</label>
                            <select name="student_id" class="form-select">
                                <option value="0">-- Select Student --</option>
                                <?php foreach ($students as $s): ?>
                                    <option value="<?= $s['id'] ?>">
                                        <?= htmlspecialchars($s['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Course</label>
                            <select name="course_id" class="form-select">
                                <option value="0">-- Select Course --</option>
                                <?php foreach ($courses as $c): ?>
                                    <option value="<?= $c['id'] ?>">
                                        <?= htmlspecialchars($c['title']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Enroll</button>
                            <a href="index.php" class="btn btn-outline-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>