<?php
/**
 * User Model
 * Handles all database operations for users and user profiles
 */

class User {
    private $conn;
    private $table_name = "User";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create new user with profile
    public function create($data) {
        try {
            $this->conn->beginTransaction();

            // Check if email already exists (Business Rule)
            if ($this->emailExists($data['email'])) {
                throw new Exception("Email already exists");
            }

            // Check if username already exists (Business Rule)
            if ($this->usernameExists($data['user_name'])) {
                throw new Exception("Username already exists");
            }

            // Validate role exists (Business Rule)
            if (!$this->roleExists($data['role_id'])) {
                throw new Exception("Invalid role selected");
            }

            // Insert into User table
            $query = "INSERT INTO " . $this->table_name . " 
                     (role_id, user_name, email, password, status) 
                     VALUES (:role_id, :user_name, :email, :password, :status)";

            $stmt = $this->conn->prepare($query);

            // Sanitize and bind
            $data['user_name'] = htmlspecialchars(strip_tags($data['user_name']));
            $data['email'] = htmlspecialchars(strip_tags($data['email']));
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

            $stmt->bindParam(':role_id', $data['role_id']);
            $stmt->bindParam(':user_name', $data['user_name']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':password', $data['password']);
            $stmt->bindParam(':status', $data['status']);

            $stmt->execute();
            $user_id = $this->conn->lastInsertId();

            // Insert into User_profiles table
            $profile_query = "INSERT INTO User_profiles 
                            (user_id, full_name, student_code, phone, department, address) 
                            VALUES (:user_id, :full_name, :student_code, :phone, :department, :address)";

            $profile_stmt = $this->conn->prepare($profile_query);

            $data['full_name'] = htmlspecialchars(strip_tags($data['full_name']));
            $data['student_code'] = htmlspecialchars(strip_tags($data['student_code']));
            $data['phone'] = htmlspecialchars(strip_tags($data['phone']));
            $data['department'] = htmlspecialchars(strip_tags($data['department']));
            $data['address'] = htmlspecialchars(strip_tags($data['address']));

            $profile_stmt->bindParam(':user_id', $user_id);
            $profile_stmt->bindParam(':full_name', $data['full_name']);
            $profile_stmt->bindParam(':student_code', $data['student_code']);
            $profile_stmt->bindParam(':phone', $data['phone']);
            $profile_stmt->bindParam(':department', $data['department']);
            $profile_stmt->bindParam(':address', $data['address']);

            $profile_stmt->execute();

            $this->conn->commit();
            return $user_id;

        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }

    // Read all users with profiles and roles
    public function readAll() {
        $query = "SELECT u.*, up.full_name, up.student_code, up.phone, up.department, 
                 up.address, r.role_name 
                 FROM " . $this->table_name . " u
                 LEFT JOIN User_profiles up ON u.user_id = up.user_id
                 LEFT JOIN Roles r ON u.role_id = r.roleID
                 ORDER BY u.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Read single user with profile
    public function readOne($user_id) {
        $query = "SELECT u.*, up.full_name, up.student_code, up.phone, up.department, 
                 up.address, r.role_name 
                 FROM " . $this->table_name . " u
                 LEFT JOIN User_profiles up ON u.user_id = up.user_id
                 LEFT JOIN Roles r ON u.role_id = r.roleID
                 WHERE u.user_id = :user_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update user and profile
    public function update($user_id, $data) {
        try {
            $this->conn->beginTransaction();

            // Check if email exists for another user (Business Rule)
            if (isset($data['email']) && $this->emailExistsForOther($data['email'], $user_id)) {
                throw new Exception("Email already exists");
            }

            // Check if username exists for another user (Business Rule)
            if (isset($data['user_name']) && $this->usernameExistsForOther($data['user_name'], $user_id)) {
                throw new Exception("Username already exists");
            }

            // Update User table
            $user_fields = [];
            $params = [];

            if (isset($data['role_id'])) {
                if (!$this->roleExists($data['role_id'])) {
                    throw new Exception("Invalid role selected");
                }
                $user_fields[] = "role_id = :role_id";
                $params[':role_id'] = $data['role_id'];
            }

            if (isset($data['user_name'])) {
                $user_fields[] = "user_name = :user_name";
                $params[':user_name'] = htmlspecialchars(strip_tags($data['user_name']));
            }

            if (isset($data['email'])) {
                $user_fields[] = "email = :email";
                $params[':email'] = htmlspecialchars(strip_tags($data['email']));
            }

            if (isset($data['status'])) {
                $user_fields[] = "status = :status";
                $params[':status'] = $data['status'];
            }

            if (isset($data['password']) && !empty($data['password'])) {
                $user_fields[] = "password = :password";
                $params[':password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }

            if (!empty($user_fields)) {
                $query = "UPDATE " . $this->table_name . " SET " . implode(', ', $user_fields) . " 
                         WHERE user_id = :user_id";
                $params[':user_id'] = $user_id;

                $stmt = $this->conn->prepare($query);
                foreach ($params as $key => $value) {
                    $stmt->bindValue($key, $value);
                }
                $stmt->execute();
            }

            // Update User_profiles table
            $profile_fields = [];
            $profile_params = [];

            if (isset($data['full_name'])) {
                $profile_fields[] = "full_name = :full_name";
                $profile_params[':full_name'] = htmlspecialchars(strip_tags($data['full_name']));
            }

            if (isset($data['student_code'])) {
                $profile_fields[] = "student_code = :student_code";
                $profile_params[':student_code'] = htmlspecialchars(strip_tags($data['student_code']));
            }

            if (isset($data['phone'])) {
                $profile_fields[] = "phone = :phone";
                $profile_params[':phone'] = htmlspecialchars(strip_tags($data['phone']));
            }

            if (isset($data['department'])) {
                $profile_fields[] = "department = :department";
                $profile_params[':department'] = htmlspecialchars(strip_tags($data['department']));
            }

            if (isset($data['address'])) {
                $profile_fields[] = "address = :address";
                $profile_params[':address'] = htmlspecialchars(strip_tags($data['address']));
            }

            if (!empty($profile_fields)) {
                $query = "UPDATE User_profiles SET " . implode(', ', $profile_fields) . " 
                         WHERE user_id = :user_id";
                $profile_params[':user_id'] = $user_id;

                $stmt = $this->conn->prepare($query);
                foreach ($profile_params as $key => $value) {
                    $stmt->bindValue($key, $value);
                }
                $stmt->execute();
            }

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }

    // Delete user (Business Rule: Check if user has bookings)
    public function delete($user_id) {
        try {
            // Check if user has existing bookings (Business Rule)
            if ($this->hasBookings($user_id)) {
                throw new Exception("Cannot delete user with existing bookings");
            }

            $this->conn->beginTransaction();

            // Delete from User_profiles first (foreign key)
            $query = "DELETE FROM User_profiles WHERE user_id = :user_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            // Delete from User
            $query = "DELETE FROM " . $this->table_name . " WHERE user_id = :user_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }

    // Business Rule: Check if email exists
    private function emailExists($email) {
        $query = "SELECT user_id FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Business Rule: Check if email exists for another user
    private function emailExistsForOther($email, $user_id) {
        $query = "SELECT user_id FROM " . $this->table_name . " WHERE email = :email AND user_id != :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Business Rule: Check if username exists
    private function usernameExists($user_name) {
        $query = "SELECT user_id FROM " . $this->table_name . " WHERE user_name = :user_name";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_name', $user_name);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Business Rule: Check if username exists for another user
    private function usernameExistsForOther($user_name, $user_id) {
        $query = "SELECT user_id FROM " . $this->table_name . " WHERE user_name = :user_name AND user_id != :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_name', $user_name);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Business Rule: Check if role exists
    private function roleExists($role_id) {
        $query = "SELECT roleID FROM Roles WHERE roleID = :role_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role_id', $role_id);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Business Rule: Check if user has bookings
    private function hasBookings($user_id) {
        $query = "SELECT booking_id FROM Bookings WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Get all roles for dropdown
    public function getRoles() {
        $query = "SELECT roleID, role_name FROM Roles ORDER BY role_name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
