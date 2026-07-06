<?php
/**
 * models/Claim.php – Claim model
 * 
 * Handles all database operations for claims:
 * - Create, read, update, delete
 * - Status workflow (pending → approved → claimed / rejected)
 * - Finder claims retrieval
 * - Proof document handling
 */

class Claim {
    private $conn;

    public function __construct($db_connection) {
        $this->conn = $db_connection;
    }

    /**
     * Create a new claim
     * @param int $item_id
     * @param string $claimant_name
     * @param string $claimant_id_type
     * @param string $claimant_id_number
     * @param string $claimant_contact
     * @param string $proof_document
     * @param string $claim_date
     * @return bool
     */
    public function create($item_id, $claimant_name, $claimant_id_type, $claimant_id_number, 
                           $claimant_contact, $proof_document, $claim_date) {
        $sql = "INSERT INTO claims (item_id, claimant_name, claimant_id_type, claimant_id_number, 
                claimant_contact, proof_document, claim_date, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "issssss", $item_id, $claimant_name, $claimant_id_type, 
                              $claimant_id_number, $claimant_contact, $proof_document, $claim_date);
        return mysqli_stmt_execute($stmt);
    }

    /**
     * Get all claims with item and user info (for admin)
     * @param string $status
     * @param string $search
     * @param int $limit
     * @param int $offset
     * @return mysqli_result
     */
    public function getAll($status = '', $search = '', $limit = 10, $offset = 0) {
        $conditions = [];
        $params = [];
        $types = '';

        if (!empty($status) && in_array($status, ['pending','approved','rejected','claimed'])) {
            $conditions[] = "c.status = ?";
            $params[] = $status;
            $types .= 's';
        }
        if (!empty($search)) {
            $conditions[] = "(c.claimant_name LIKE ? OR c.claimant_id_number LIKE ? OR i.item_name LIKE ?)";
            $like = "%$search%";
            $params[] = $like; $params[] = $like; $params[] = $like;
            $types .= 'sss';
        }

        $sql = "SELECT c.*, i.item_name, i.status as item_status, u.username as processed_by_name
                FROM claims c
                LEFT JOIN items i ON c.item_id = i.id
                LEFT JOIN users u ON c.processed_by = u.id";
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
        $sql .= " ORDER BY c.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit; $params[] = $offset;
        $types .= 'ii';

        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }

    /**
     * Count claims (for pagination)
     * @param string $status
     * @param string $search
     * @return int
     */
    public function countAll($status = '', $search = '') {
        $conditions = [];
        $params = [];
        $types = '';

        if (!empty($status)) {
            $conditions[] = "c.status = ?";
            $params[] = $status;
            $types .= 's';
        }
        if (!empty($search)) {
            $conditions[] = "(c.claimant_name LIKE ? OR c.claimant_id_number LIKE ? OR i.item_name LIKE ?)";
            $like = "%$search%";
            $params[] = $like; $params[] = $like; $params[] = $like;
            $types .= 'sss';
        }

        $sql = "SELECT COUNT(*) as total FROM claims c LEFT JOIN items i ON c.item_id = i.id";
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

    /**
     * Get a single claim by ID
     * @param int $id
     * @return array|null
     */
    public function getById($id) {
        $sql = "SELECT c.*, i.item_name, i.status as item_status, i.location, i.photo, u.username as processed_by_name
                FROM claims c
                LEFT JOIN items i ON c.item_id = i.id
                LEFT JOIN users u ON c.processed_by = u.id
                WHERE c.id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    /**
     * Update claim status (admin/finder)
     * @param int $id
     * @param string $status
     * @param string $admin_remarks
     * @param int $processed_by
     * @return bool
     */
    public function updateStatus($id, $status, $admin_remarks, $processed_by) {
        $sql = "UPDATE claims SET status = ?, admin_remarks = ?, processed_by = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssii", $status, $admin_remarks, $processed_by, $id);
        return mysqli_stmt_execute($stmt);
    }

    /**
     * Delete a claim (also removes proof file)
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        $claim = $this->getById($id);
        if ($claim && !empty($claim['proof_document'])) {
            $file_path = $_SERVER['DOCUMENT_ROOT'] . '/LF-web/' . $claim['proof_document'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        $sql = "DELETE FROM claims WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        return mysqli_stmt_execute($stmt);
    }

    /**
     * Get claims for items reported by a specific user (finder)
     * @param int $finder_id
     * @param string $status
     * @param int $limit
     * @param int $offset
     * @return mysqli_result
     */
    public function getClaimsByFinder($finder_id, $status = '', $limit, $offset) {
        $sql = "SELECT c.*, i.item_name, i.status as item_status
                FROM claims c
                JOIN items i ON c.item_id = i.id
                WHERE i.reported_by = ?";
        $params = [$finder_id];
        $types = "i";
        if (!empty($status)) {
            $sql .= " AND c.status = ?";
            $params[] = $status;
            $types .= "s";
        }
        $sql .= " ORDER BY c.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit; $params[] = $offset;
        $types .= "ii";

        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }

    /**
     * Count claims for items reported by a specific user (finder)
     * @param int $finder_id
     * @param string $status
     * @return int
     */
    public function countClaimsByFinder($finder_id, $status = '') {
        $sql = "SELECT COUNT(*) as total
                FROM claims c
                JOIN items i ON c.item_id = i.id
                WHERE i.reported_by = ?";
        $params = [$finder_id];
        $types = "i";
        if (!empty($status)) {
            $sql .= " AND c.status = ?";
            $params[] = $status;
            $types .= "s";
        }
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }
}
?>