<?php
/**
 * models/IssueReport.php – Issue Report Model
 * 
 * Handles database operations for bug and issue reports submitted by users.
 */

class IssueReport {
    private $conn;

    public function __construct($db_connection) {
        $this->conn = $db_connection;
    }

    /**
     * Create a new issue report
     */
    public function create($user_id, $issue_type, $description) {
        $sql = "INSERT INTO issue_reports (user_id, issue_type, description) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "iss", $user_id, $issue_type, $description);
        return mysqli_stmt_execute($stmt);
    }

    /**
     * Get all reports with optional status filter and pagination
     */
    public function getAll($status = '', $limit = 20, $offset = 0) {
        $query = "SELECT r.*, u.username as reporter_username, r_u.username as resolver_username 
                  FROM issue_reports r
                  LEFT JOIN users u ON r.user_id = u.id
                  LEFT JOIN users r_u ON r.resolved_by = r_u.id";
        
        if (!empty($status)) {
            $query .= " WHERE r.status = ?";
            $query .= " ORDER BY r.created_at DESC LIMIT ? OFFSET ?";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, "sii", $status, $limit, $offset);
        } else {
            $query .= " ORDER BY r.created_at DESC LIMIT ? OFFSET ?";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, "ii", $limit, $offset);
        }
        
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }

    /**
     * Count total reports for pagination
     */
    public function countAll($status = '') {
        $query = "SELECT COUNT(*) as total FROM issue_reports";
        if (!empty($status)) {
            $query .= " WHERE status = ?";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, "s", $status);
        } else {
            $stmt = mysqli_prepare($this->conn, $query);
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }

    /**
     * Get report by ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM issue_reports WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    /**
     * Mark an issue as fixed
     */
    public function markAsFixed($id, $resolved_by) {
        $sql = "UPDATE issue_reports SET status = 'fixed', resolved_by = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $resolved_by, $id);
        return mysqli_stmt_execute($stmt);
    }
}
?>
