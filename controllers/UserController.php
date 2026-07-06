<?php
/**
 * controllers/UserController.php – User Management Controller
 * 
 * Handles admin-only user management:
 * - List users (search, pagination)
 * - Create user
 * - Edit user (role, status)
 * - Delete user
 * - Reset password
 * - Statistics for dashboard
 */

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/AuditLog.php';

class UserController {
    private $userModel;
    private $auditLog;
    private $conn;

    public function __construct($db_connection) {
        $this->conn = $db_connection;
        $this->userModel = new User($db_connection);
        $this->auditLog = new AuditLog($db_connection);
    }

    /**
     * Helper: ensure user is admin
     */
    private function checkAdmin() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = "Access denied. Admin only.";
            header("Location: index.php?action=dashboard");
            exit();
        }
    }

    /**
     * Helper: log an action
     */
    private function logAction($action, $module) {
        $user_id = $_SESSION['user_id'] ?? null;
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $this->auditLog->log($user_id, $action, $module, $ip);
    }

    /**
     * Get user statistics for dashboard cards
     */
    private function getUserStats() {
        $stats = [
            'total' => 0,
            'active' => 0,
            'disabled' => 0,
            'admin' => 0,
            'staff' => 0,
            'user' => 0
        ];
        
        $sql = "SELECT role, status FROM users";
        $result = mysqli_query($this->conn, $sql);
        while($row = mysqli_fetch_assoc($result)) {
            $stats['total']++;
            if($row['status'] == 'active') $stats['active']++;
            if($row['status'] == 'disabled') $stats['disabled']++;
            if($row['role'] == 'admin') $stats['admin']++;
            if($row['role'] == 'staff') $stats['staff']++;
            if($row['role'] == 'user') $stats['user']++;
        }
        return $stats;
    }

    /**
     * List users with search and pagination
     */
    public function index() {
        $this->checkAdmin();
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $users = $this->userModel->getAllUsers($search, $limit, $offset);
        $total = $this->userModel->countUsers($search);
        $totalPages = ceil($total / $limit);

        // Get user statistics
        $userStats = $this->getUserStats();

        include __DIR__ . '/../views/users/index.php';
    }

    /**
     * Show form to create a new user
     */
    public function create() {
        $this->checkAdmin();
        include __DIR__ . '/../views/users/create.php';
    }

    public function store() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $first_name = trim($_POST['first_name'] ?? '');
            $last_name = trim($_POST['last_name'] ?? '');
            $birthdate = trim($_POST['birthdate'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $id_type = $_POST['id_type'] ?? '';
            $id_number = trim($_POST['id_number'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? 'user';
            $status = $_POST['status'] ?? 'active';

            $errors = [];

            if (empty($first_name)) $errors[] = "First name required.";
            if (empty($last_name)) $errors[] = "Last name required.";
            if (empty($birthdate)) $errors[] = "Birthdate required.";
            if (empty($username)) $errors[] = "Username required.";
            if (empty($id_number)) $errors[] = "ID number required.";
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email required.";
            if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";

            $age = 0;
            if (!empty($birthdate)) {
                $dob = new DateTime($birthdate);
                $now = new DateTime();
                $age = $now->diff($dob)->y;
            }

            // Check duplicates using existing method
            if ($this->userModel->isDuplicate($username, $email, $id_type, $id_number)) {
                $errors[] = "Username, email, or ID already exists.";
            }

            if (empty($errors)) {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (username, id_type, id_number, email, first_name, last_name, birthdate, age, password, role, status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($this->conn, $sql);
                mysqli_stmt_bind_param($stmt, "sssssssisss", $username, $id_type, $id_number, $email, $first_name, $last_name, $birthdate, $age, $hashed, $role, $status);
                if (mysqli_stmt_execute($stmt)) {
                    $this->logAction("Added user: $username", "users");
                    $_SESSION['success'] = "User created successfully.";
                    header("Location: index.php?action=users");
                    exit();
                } else {
                    $errors[] = "Database error. Could not create user.";
                }
            }
            include __DIR__ . '/../views/users/create.php';
        } else {
            $this->create();
        }
    }

    /**
     * Show edit form
     */
    public function edit() {
        $this->checkAdmin();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $user = $this->userModel->getById($id);
        if (!$user) {
            $_SESSION['error'] = "User not found.";
            header("Location: index.php?action=users");
            exit();
        }
        include __DIR__ . '/../views/users/edit.php';
    }

    /**
     * Update user
     */
    public function update() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['id'];
            $role = $_POST['role'];
            $status = $_POST['status'];
            if ($this->userModel->updateUser($id, $role, $status)) {
                $this->logAction("Updated user ID: $id", "users");
                $_SESSION['success'] = "User updated successfully.";
            } else {
                $_SESSION['error'] = "Update failed.";
            }
            header("Location: index.php?action=users");
            exit();
        }
        $this->edit();
    }

    /**
     * Delete user
     */
    public function delete() {
        $this->checkAdmin();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id == $_SESSION['user_id']) {
            $_SESSION['error'] = "You cannot delete your own account.";
        } else {
            if ($this->userModel->deleteUser($id)) {
                $this->logAction("Deleted user ID: $id", "users");
                $_SESSION['success'] = "User deleted successfully.";
            } else {
                $_SESSION['error'] = "Delete failed.";
            }
        }
        header("Location: index.php?action=users");
        exit();
    }

    /**
     * Reset user password
     */
    public function resetPassword() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['id'];
            $newPassword = $_POST['new_password'];
            if (strlen($newPassword) < 6) {
                $_SESSION['error'] = "Password must be at least 6 characters.";
            } else {
                if ($this->userModel->resetPassword($id, $newPassword)) {
                    $this->logAction("Reset password for user ID: $id", "users");
                    $_SESSION['success'] = "Password reset successfully.";
                } else {
                    $_SESSION['error'] = "Password reset failed.";
                }
            }
            header("Location: index.php?action=users");
            exit();
        }
    }
}
?>