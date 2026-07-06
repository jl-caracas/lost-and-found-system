<?php
/**
 * models/AuditLog.php – Audit Log model
 * 
 * Handles logging and retrieval of audit trail entries:
 * - Log actions (add, edit, delete, login, logout)
 * - Retrieve logs with search and pagination
 * - Admin-only viewing
 */

class AuditLog {
    private $conn;

    public function __construct($db_connection) {
        $this->conn = $db_connection;
    }

    /**
     * Log an action
     * @param int|null $user_id
     * @param string $action
     * @param string $module
     * @param string|null $ip_address
     * @return bool
     */
    public function log($user_id, $action, $module, $ip_address = null) {
        if (!$ip_address) {
            $ip_address = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        }
        $sql = "INSERT INTO audit_logs (user_id, action, module, ip_address) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "isss", $user_id, $action, $module, $ip_address);
        return mysqli_stmt_execute($stmt);
    }

    /**
     * Get all logs with optional search and pagination
     * @param string $search
     * @param int $limit
     * @param int $offset
     * @return mysqli_result
     */
    public function getAll($search = '', $limit = 10, $offset = 0, $module_filter = '') {
        $conditions = [];
        $params = [];
        $types = '';

        if (!empty($search)) {
            $conditions[] = "(u.username LIKE ? OR l.action LIKE ? OR l.module LIKE ?)";
            $like = "%$search%";
            $params[] = $like; $params[] = $like; $params[] = $like;
            $types .= 'sss';
        }
        
        if (!empty($module_filter)) {
            $conditions[] = "l.module = ?";
            $params[] = $module_filter;
            $types .= 's';
        }

        $sql = "SELECT l.*, u.username 
                FROM audit_logs l
                LEFT JOIN users u ON l.user_id = u.id";
                
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
        
        $sql .= " ORDER BY l.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit; $params[] = $offset;
        $types .= 'ii';

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!empty($params)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }

    /**
     * Count total logs (for pagination)
     * @param string $search
     * @return int
     */
    public function countAll($search = '', $module_filter = '') {
        $conditions = [];
        $params = [];
        $types = '';

        if (!empty($search)) {
            $conditions[] = "(u.username LIKE ? OR l.action LIKE ? OR l.module LIKE ?)";
            $like = "%$search%";
            $params[] = $like; $params[] = $like; $params[] = $like;
            $types .= 'sss';
        }
        
        if (!empty($module_filter)) {
            $conditions[] = "l.module = ?";
            $params[] = $module_filter;
            $types .= 's';
        }

        $sql = "SELECT COUNT(*) as total 
                FROM audit_logs l
                LEFT JOIN users u ON l.user_id = u.id";
                
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!empty($params)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }

    public function getDistinctModules() {
        $sql = "SELECT DISTINCT module FROM audit_logs ORDER BY module ASC";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }
}
?>