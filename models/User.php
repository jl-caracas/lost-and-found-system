<?php
/**
 * models/User.php – User model
 * 
 * Handles all database operations related to users:
 * - Registration
 * - Login
 * - Duplicate checking
 * - User retrieval
 */

class User {
    private $conn;

    public function __construct($db_connection) {
        $this->conn = $db_connection;
    }

    /**
     * Check if username, email, or ID already exists
     * @param string $username
     * @param string $email
     * @param string $id_type
     * @param string $id_number
     * @return bool True if duplicate exists
     */
    public function isDuplicate($username, $email, $id_type, $id_number) {
        $sql = "SELECT id FROM users WHERE username = ? OR email = ? OR (id_type = ? AND id_number = ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $id_type, $id_number);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_num_rows($result) > 0;
    }

    /**
     * Register a new user
     * @param string $username
     * @param string $first_name
     * @param string $middle_initial
     * @param string $last_name
     * @param string $birthdate
     * @param int $age
     * @param string $id_type
     * @param string $id_number
     * @param string $email
     * @param string $password
     * @param string $role (default: 'user')
     * @return bool True on success
     */
    public function register($username, $first_name, $middle_initial, $last_name, $birthdate, $age, $id_type, $id_number, $email, $password, $role = 'user') {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, first_name, middle_initial, last_name, birthdate, age, id_type, id_number, email, password, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssisssss", $username, $first_name, $middle_initial, $last_name, $birthdate, $age, $id_type, $id_number, $email, $hashed, $role);
        return mysqli_stmt_execute($stmt);
    }

    /**
     * Login user
     * @param string $identifier (username or email)
     * @param string $password
     * @return array|false User data array or false on failure
     */
    public function login($identifier, $password) {
        $sql = "SELECT * FROM users WHERE (username = ? OR email = ?) AND status = 'active'";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $identifier, $identifier);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    /**
     * Get user by ID
     * @param int $id
     * @return array|null
     */
    public function getById($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    /**
     * Get all users with optional search and pagination
     * @param string $search
     * @param int $limit
     * @param int $offset
     * @return mysqli_result
     */
    public function getAllUsers($search = '', $limit = 10, $offset = 0) {
        if (!empty($search)) {
            $sql = "SELECT * FROM users WHERE username LIKE ? OR email LIKE ? OR id_number LIKE ? ORDER BY created_at DESC LIMIT ? OFFSET ?";
            $stmt = mysqli_prepare($this->conn, $sql);
            $like = "%$search%";
            mysqli_stmt_bind_param($stmt, "sssii", $like, $like, $like, $limit, $offset);
        } else {
            $sql = "SELECT * FROM users ORDER BY created_at DESC LIMIT ? OFFSET ?";
            $stmt = mysqli_prepare($this->conn, $sql);
            mysqli_stmt_bind_param($stmt, "ii", $limit, $offset);
        }
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }

    /**
     * Count total users (for pagination)
     * @param string $search
     * @return int
     */
    public function countUsers($search = '') {
        if (!empty($search)) {
            $sql = "SELECT COUNT(*) as total FROM users WHERE username LIKE ? OR email LIKE ? OR id_number LIKE ?";
            $stmt = mysqli_prepare($this->conn, $sql);
            $like = "%$search%";
            mysqli_stmt_bind_param($stmt, "sss", $like, $like, $like);
        } else {
            $sql = "SELECT COUNT(*) as total FROM users";
            $stmt = mysqli_prepare($this->conn, $sql);
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }

    /**
     * Update user role and status (admin only)
     * @param int $id
     * @param string $role
     * @param string $status
     * @return bool
     */
    public function updateUser($id, $role, $status) {
        $sql = "UPDATE users SET role = ?, status = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $role, $status, $id);
        return mysqli_stmt_execute($stmt);
    }

    /**
     * Delete user (hard delete)
     * @param int $id
     * @return bool
     */
    public function deleteUser($id) {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        return mysqli_stmt_execute($stmt);
    }

    /**
     * Reset user password (admin only)
     * @param int $id
     * @param string $newPassword
     * @return bool
     */
    public function resetPassword($id, $newPassword) {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $hashed, $id);
        return mysqli_stmt_execute($stmt);
    }

    /**
     * Get total number of users (for dashboard)
     * @return int
     */
    public function getTotalUsers() {
        $sql = "SELECT COUNT(*) as total FROM users";
        $result = mysqli_query($this->conn, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }

    /**
     * Get recently registered users (for dashboard)
     * @param int $limit
     * @return mysqli_result
     */
    public function getRecentUsers($limit = 5) {
        $sql = "SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC LIMIT ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $limit);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }
    /**
     * Update user profile
     * @param int $id
     * @param string $first_name
     * @param string $middle_initial
     * @param string $last_name
     * @param string $birthdate
     * @param int $age
     * @param string $bio
     * @param string $profilePicture
     * @return bool
     */
    public function updateProfile($id, $first_name, $middle_initial, $last_name, $birthdate, $age, $bio, $profilePicture = null) {
        if ($profilePicture) {
            $sql = "UPDATE users SET first_name = ?, middle_initial = ?, last_name = ?, birthdate = ?, age = ?, bio = ?, profile_picture = ? WHERE id = ?";
            $stmt = mysqli_prepare($this->conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssssissi", $first_name, $middle_initial, $last_name, $birthdate, $age, $bio, $profilePicture, $id);
        } else {
            $sql = "UPDATE users SET first_name = ?, middle_initial = ?, last_name = ?, birthdate = ?, age = ?, bio = ? WHERE id = ?";
            $stmt = mysqli_prepare($this->conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssssisi", $first_name, $middle_initial, $last_name, $birthdate, $age, $bio, $id);
        }
        return mysqli_stmt_execute($stmt);
    }
}
?>