<?php
/**
 * controllers/AuthController.php – Authentication Controller
 * 
 * Handles:
 * - Login (show form, process)
 * - Registration (show form, process)
 * - Logout
 * - Session management
 */

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/AuditLog.php';

class AuthController {
    private $userModel;
    private $auditLog;
    private $conn;

    public function __construct($db_connection) {
        $this->conn = $db_connection;
        $this->userModel = new User($db_connection);
        $this->auditLog = new AuditLog($db_connection);
    }

    /**
     * Show login form
     */
    public function showLogin() {
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php?action=dashboard");
            exit();
        }
        include __DIR__ . '/../views/auth/login.php';
    }

    /**
     * Process login
     */
    public function login() {
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php?action=dashboard");
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $identifier = trim($_POST['identifier']);
            $password = $_POST['password'];

            $user = $this->userModel->login($identifier, $password);
            if ($user) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['profile_picture'] = $user['profile_picture'] ?? null;

                // Log login action
                $this->auditLog->log($user['id'], "User logged in", "auth");

                header("Location: index.php?action=dashboard");
                exit();
            } else {
                $error = "Invalid credentials or account disabled.";
                include __DIR__ . '/../views/auth/login.php';
            }
        } else {
            $this->showLogin();
        }
    }

    /**
     * Show registration form
     */
    public function showRegister() {
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php?action=dashboard");
            exit();
        }
        include __DIR__ . '/../views/auth/register.php';
    }

    /**
     * Process registration
     */
    public function register() {
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php?action=dashboard");
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $first_name = trim($_POST['first_name']);
            $middle_initial = trim($_POST['middle_initial'] ?? '');
            $last_name = trim($_POST['last_name']);
            $birthdate = $_POST['birthdate'];
            $username = trim($_POST['username']);
            $id_type = $_POST['id_type'];
            $id_number = trim($_POST['id_number']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $confirm = $_POST['confirm_password'];

            $errors = [];

            // Calculate age
            $age = 0;
            if (!empty($birthdate)) {
                $dob = new DateTime($birthdate);
                $now = new DateTime();
                $age = $now->diff($dob)->y;
            } else {
                $errors[] = "Birthdate is required.";
            }

            // Validation
            if (empty($first_name)) $errors[] = "First name is required.";
            if (empty($last_name)) $errors[] = "Last name is required.";
            if (empty($username)) $errors[] = "Username is required.";
            if (empty($id_number)) $errors[] = "ID number is required.";
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email required.";
            if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";
            if ($password !== $confirm) $errors[] = "Passwords do not match.";

            // Handle custom ID type
            if ($id_type === 'other') {
                if (empty($_POST['custom_id_name'])) {
                    $errors[] = "Please specify the ID Name for 'Other ID'.";
                } else {
                    $id_type = "Other: " . trim($_POST['custom_id_name']);
                }
            }

            // ID format validation
            if ($id_type == 'pup_id' && !preg_match('/^[A-Za-z0-9]{4}-\d{5}-[A-Za-z]+-\d+$/', $id_number)) {
                $errors[] = "PUP Student ID must be format: YYYY-XXXXX-TG-0";
            }
            if ($id_type == 'national_id' && !preg_match('/^\d{12}$/', $id_number)) {
                $errors[] = "National ID must be 12 digits.";
            }

            // Check duplicates
            if ($this->userModel->isDuplicate($username, $email, $id_type, $id_number)) {
                $errors[] = "Username, email, or ID already exists.";
            }

            if (empty($errors)) {
                if ($this->userModel->register($username, $first_name, $middle_initial, $last_name, $birthdate, $age, $id_type, $id_number, $email, $password)) {
                    $this->auditLog->log(null, "New user registered: $username", "auth");
                    header("Location: index.php?action=login&registered=1");
                    exit();
                } else {
                    $errors[] = "Registration failed. Please try again.";
                }
            }
            include __DIR__ . '/../views/auth/register.php';
        } else {
            $this->showRegister();
        }
    }

    /**
     * Logout – destroy session
     */
    public function logout() {
        session_start();
        $user_id = $_SESSION['user_id'] ?? null;
        $username = $_SESSION['username'] ?? 'Unknown';
        $this->auditLog->log($user_id, "User logged out: $username", "auth");
        session_destroy();
        header("Location: index.php?action=login");
        exit();
    }
}
?>