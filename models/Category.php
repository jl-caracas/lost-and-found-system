<?php
/**
 * models/Category.php – Category model
 * 
 * Handles all database operations for item categories:
 * - CRUD operations
 * - Search and pagination
 * - Duplicate checking
 */

class Category {
    private $conn;

    public function __construct($db_connection) {
        $this->conn = $db_connection;
    }

    /**
     * Get all categories with optional search and pagination
     * @param string $search
     * @param int $limit
     * @param int $offset
     * @return mysqli_result
     */
    public function getAll($search = '', $limit = 10, $offset = 0) {
        if (!empty($search)) {
            $sql = "SELECT * FROM categories WHERE name LIKE ? OR description LIKE ? ORDER BY name LIMIT ? OFFSET ?";
            $stmt = mysqli_prepare($this->conn, $sql);
            $like = "%$search%";
            mysqli_stmt_bind_param($stmt, "ssii", $like, $like, $limit, $offset);
        } else {
            $sql = "SELECT * FROM categories ORDER BY name LIMIT ? OFFSET ?";
            $stmt = mysqli_prepare($this->conn, $sql);
            mysqli_stmt_bind_param($stmt, "ii", $limit, $offset);
        }
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }

    /**
     * Count total categories (for pagination)
     * @param string $search
     * @return int
     */
    public function countAll($search = '') {
        if (!empty($search)) {
            $sql = "SELECT COUNT(*) as total FROM categories WHERE name LIKE ? OR description LIKE ?";
            $stmt = mysqli_prepare($this->conn, $sql);
            $like = "%$search%";
            mysqli_stmt_bind_param($stmt, "ss", $like, $like);
        } else {
            $sql = "SELECT COUNT(*) as total FROM categories";
            $stmt = mysqli_prepare($this->conn, $sql);
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }

    /**
     * Get single category by ID
     * @param int $id
     * @return array|null
     */
    public function getById($id) {
        $sql = "SELECT * FROM categories WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    /**
     * Create a new category
     * @param string $name
     * @param string $description
     * @return bool
     */
    public function create($name, $description) {
        $sql = "INSERT INTO categories (name, description) VALUES (?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $name, $description);
        return mysqli_stmt_execute($stmt);
    }

    /**
     * Update a category
     * @param int $id
     * @param string $name
     * @param string $description
     * @return bool
     */
    public function update($id, $name, $description) {
        $sql = "UPDATE categories SET name = ?, description = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $name, $description, $id);
        return mysqli_stmt_execute($stmt);
    }

    /**
     * Delete a category (only if no items are linked)
     * @param int $id
     * @return bool False if items exist, true if deleted
     */
    public function delete($id) {
        // Check if any items use this category
        $check = "SELECT id FROM items WHERE category_id = ? LIMIT 1";
        $stmt = mysqli_prepare($this->conn, $check);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) > 0) {
            return false; // Cannot delete – items exist
        }
        $sql = "DELETE FROM categories WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        return mysqli_stmt_execute($stmt);
    }
}
?>