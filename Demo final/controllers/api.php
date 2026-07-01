<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'UserController.php';

$controller = new UserController();
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? null;

switch ($action) {
    case 'create':
        $controller->create();
        break;
    case 'readAll':
        $controller->readAll();
        break;
    case 'readOne':
        if ($id) {
            $controller->readOne($id);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "User ID required"]);
        }
        break;
    case 'update':
        if ($id) {
            $controller->update($id);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "User ID required"]);
        }
        break;
    case 'delete':
        if ($id) {
            $controller->delete($id);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "User ID required"]);
        }
        break;
    case 'getRoles':
        $controller->getRoles();
        break;
    default:
        http_response_code(404);
        echo json_encode(["message" => "Action not found"]);
        break;
}
?>
