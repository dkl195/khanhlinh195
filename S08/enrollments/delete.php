<?php
require_once __DIR__ . '/../classes/Database.php';

$db = Database::getInstance();

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $db->delete('enrollments', "id = ?", [$id]);
}

header("Location: index.php");
exit;