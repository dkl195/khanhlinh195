<?php
require_once '../config/database.php';
require_once '../models/User.php';

class UserController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    // Create user
    public function create() {
        try {
            $data = json_decode(file_get_contents("php://input"));
            
            $result = $this->user->create($data);
            
            http_response_code(201);
            echo json_encode([
                "success" => true,
                "message" => "User created successfully",
                "user_id" => $result
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    // Get all users
    public function readAll() {
        $users = $this->user->readAll();
        echo json_encode($users);
    }

    // Get single user
    public function readOne($user_id) {
        $user = $this->user->readOne($user_id);
        if ($user) {
            echo json_encode($user);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "User not found"]);
        }
    }

    // Update user
    public function update($user_id) {
        try {
            $data = json_decode(file_get_contents("php://input"));
            
            $result = $this->user->update($user_id, $data);
            
            echo json_encode([
                "success" => true,
                "message" => "User updated successfully"
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    // Delete user
    public function delete($user_id) {
        try {
            $result = $this->user->delete($user_id);
            
            echo json_encode([
                "success" => true,
                "message" => "User deleted successfully"
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    // Get roles
    public function getRoles() {
        $roles = $this->user->getRoles();
        echo json_encode($roles);
    }
}
?>
