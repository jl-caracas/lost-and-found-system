<?php
/**
 * models/Item.php – Item model
 * 
 * Handles all database operations for items (lost/found):
 * - CRUD operations
 * - Search, filter, sort, pagination
 * - Photo upload handling
 * - Similar items retrieval
 */

class Item {
    private $conn;

    public function __construct($db_connection) {
        $this->conn = $db_connection;
    }

    public function getAll($status = '', $search = '', $category_id = 0, $limit = 10, $offset = 0, $sort_by = 'newest', $current_user_id = null) {
        $conditions = [];
        $params = [];
        $types = '';

        if (!empty($status)) {
            if (in_array($status, ['lost', 'found'])) {
                $conditions[] = "i.status = ?";
                $params[] = $status;
                $types .= 's';
            } elseif ($status === 'claimed') {
                $conditions[] = "i.status_label = ?";
                $params[] = $status;
                $types .= 's';
            }
        }
        if (!empty($search)) {
            $conditions[] = "(i.item_name LIKE ? OR i.item_name LIKE ? OR i.description LIKE ? OR i.description LIKE ? OR i.location LIKE ? OR i.location LIKE ?)";
            $like_start = "$search%";
            $like_space = "% $search%";
            $params[] = $like_start; $params[] = $like_space;
            $params[] = $like_start; $params[] = $like_space;
            $params[] = $like_start; $params[] = $like_space;
            $types .= 'ssssss';
        }
        if ($category_id > 0) {
            $conditions[] = "i.category_id = ?";
            $params[] = $category_id;
            $types .= 'i';
        }

        $sql = "SELECT i.*, c.name as category_name, u.username as reporter_name, u.profile_picture as reporter_picture
                FROM items i
                LEFT JOIN categories c ON i.category_id = c.id
                LEFT JOIN users u ON i.reported_by = u.id";
        
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
        
        $sql .= " ORDER BY ";
        if ($current_user_id) {
            $sql .= "CASE WHEN i.reported_by = ? THEN 0 ELSE 1 END ASC, ";
            $params[] = $current_user_id;
            $types .= 'i';
        }
        
        switch($sort_by) {
            case 'oldest':
                $sql .= "i.date_reported ASC, i.created_at ASC";
                break;
            case 'name_asc':
                $sql .= "i.item_name ASC";
                break;
            case 'name_desc':
                $sql .= "i.item_name DESC";
                break;
            case 'newest':
            default:
                $sql .= "i.date_reported DESC, i.created_at DESC";
                break;
        }
        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $limit; $params[] = $offset;
        $types .= 'ii';

        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }

    public function countAll($status = '', $search = '', $category_id = 0) {
        $conditions = [];
        $params = [];
        $types = '';

        if (!empty($status)) {
            if (in_array($status, ['lost', 'found'])) {
                $conditions[] = "status = ?";
                $params[] = $status;
                $types .= 's';
            } elseif ($status === 'claimed') {
                $conditions[] = "status_label = ?";
                $params[] = $status;
                $types .= 's';
            }
        }
        if (!empty($search)) {
            $conditions[] = "(item_name LIKE ? OR item_name LIKE ? OR description LIKE ? OR description LIKE ? OR location LIKE ? OR location LIKE ?)";
            $like_start = "$search%";
            $like_space = "% $search%";
            $params[] = $like_start; $params[] = $like_space;
            $params[] = $like_start; $params[] = $like_space;
            $params[] = $like_start; $params[] = $like_space;
            $types .= 'ssssss';
        }
        if ($category_id > 0) {
            $conditions[] = "category_id = ?";
            $params[] = $category_id;
            $types .= 'i';
        }

        $sql = "SELECT COUNT(*) as total FROM items";
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

    public function countByUser($user_id, $status = '') {
        $conditions = ["reported_by = ?"];
        $params = [$user_id];
        $types = 'i';

        if (!empty($status)) {
            if (in_array($status, ['lost', 'found'])) {
                $conditions[] = "status = ?";
                $params[] = $status;
                $types .= 's';
            } elseif ($status === 'claimed') {
                $conditions[] = "status_label = ?";
                $params[] = $status;
                $types .= 's';
            }
        }

        $sql = "SELECT COUNT(*) as total FROM items WHERE " . implode(" AND ", $conditions);

        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }

    public function getById($id) {
        $sql = "SELECT i.*, c.name as category_name, u.username as reporter_name, u.profile_picture as reporter_picture
                FROM items i
                LEFT JOIN categories c ON i.category_id = c.id
                LEFT JOIN users u ON i.reported_by = u.id
                WHERE i.id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    public function getSimilarItems($item_id, $category_id, $limit = 4) {
        $sql = "SELECT i.*, c.name as category_name, u.username as reporter_name, u.profile_picture as reporter_picture
                FROM items i
                LEFT JOIN categories c ON i.category_id = c.id
                LEFT JOIN users u ON i.reported_by = u.id
                WHERE i.category_id = ? AND i.id != ?
                ORDER BY i.created_at DESC
                LIMIT ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "iii", $category_id, $item_id, $limit);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }

    public function create($category_id, $item_name, $description, $reward, $status, $location, $specific_location, $date_reported, $photo, $reported_by, $latitude = null, $longitude = null) {
        $sql = "INSERT INTO items (category_id, item_name, description, reward, status, location, specific_location, date_reported, photo, reported_by, status_label, latitude, longitude) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'open', ?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "issssssssidd", $category_id, $item_name, $description, $reward, $status, $location, $specific_location, $date_reported, $photo, $reported_by, $latitude, $longitude);
        if(mysqli_stmt_execute($stmt)) {
            return mysqli_insert_id($this->conn);
        }
        return false;
    }

    public function update($id, $category_id, $item_name, $description, $reward, $status, $location, $specific_location, $date_reported, $photo = null, $latitude = null, $longitude = null) {
        if ($photo !== null) {
            $sql = "UPDATE items SET category_id=?, item_name=?, description=?, reward=?, status=?, location=?, specific_location=?, date_reported=?, photo=?, latitude=?, longitude=? WHERE id=?";
            $stmt = mysqli_prepare($this->conn, $sql);
            mysqli_stmt_bind_param($stmt, "issssssssddi", $category_id, $item_name, $description, $reward, $status, $location, $specific_location, $date_reported, $photo, $latitude, $longitude, $id);
        } else {
            $sql = "UPDATE items SET category_id=?, item_name=?, description=?, reward=?, status=?, location=?, specific_location=?, date_reported=?, latitude=?, longitude=? WHERE id=?";
            $stmt = mysqli_prepare($this->conn, $sql);
            mysqli_stmt_bind_param($stmt, "isssssssddi", $category_id, $item_name, $description, $reward, $status, $location, $specific_location, $date_reported, $latitude, $longitude, $id);
        }
        return mysqli_stmt_execute($stmt);
    }

    public function getAdditionalPhotos($item_id) {
        $sql = "SELECT * FROM item_images WHERE item_id = ? ORDER BY created_at ASC";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $item_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $photos = [];
        while($row = mysqli_fetch_assoc($result)) {
            $photos[] = $row;
        }
        return $photos;
    }

    public function addAdditionalPhoto($item_id, $photo_path) {
        $sql = "INSERT INTO item_images (item_id, photo_path) VALUES (?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "is", $item_id, $photo_path);
        return mysqli_stmt_execute($stmt);
    }

    public function deleteAdditionalPhoto($id) {
        $sql = "SELECT photo_path FROM item_images WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if($row = mysqli_fetch_assoc($result)) {
            $file_path = $_SERVER['DOCUMENT_ROOT'] . '/LF-web2/' . $row['photo_path'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            $del_sql = "DELETE FROM item_images WHERE id = ?";
            $del_stmt = mysqli_prepare($this->conn, $del_sql);
            mysqli_stmt_bind_param($del_stmt, "i", $id);
            return mysqli_stmt_execute($del_stmt);
        }
        return false;
    }

    public function delete($id) {
        $additional = $this->getAdditionalPhotos($id);
        foreach($additional as $img) {
            $this->deleteAdditionalPhoto($img['id']);
        }
        
        $item = $this->getById($id);
        if ($item && !empty($item['photo'])) {
            $file_path = $_SERVER['DOCUMENT_ROOT'] . '/LF-web2/' . $item['photo'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        $sql = "DELETE FROM items WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        return mysqli_stmt_execute($stmt);
    }

    public function getForReport($status = '', $category_id = 0, $start_date = '', $end_date = '') {
        $conditions = [];
        $params = [];
        $types = '';

        if (!empty($status)) {
            if (in_array($status, ['lost', 'found'])) {
                $conditions[] = "i.status = ?";
                $params[] = $status;
                $types .= 's';
            } elseif ($status === 'claimed') {
                $conditions[] = "i.status_label = ?";
                $params[] = $status;
                $types .= 's';
            }
        }
        if ($category_id > 0) {
            $conditions[] = "i.category_id = ?";
            $params[] = $category_id;
            $types .= 'i';
        }
        if (!empty($start_date)) {
            $conditions[] = "i.date_reported >= ?";
            $params[] = $start_date;
            $types .= 's';
        }
        if (!empty($end_date)) {
            $conditions[] = "i.date_reported <= ?";
            $params[] = $end_date;
            $types .= 's';
        }

        $sql = "SELECT i.*, c.name as category_name, u.username as reporter_name, u.profile_picture as reporter_picture
                FROM items i
                LEFT JOIN categories c ON i.category_id = c.id
                LEFT JOIN users u ON i.reported_by = u.id";
        
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
        
        $sql .= " ORDER BY i.date_reported DESC, i.created_at DESC";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!empty($params)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }

    public function updateStatusLabel($item_id, $label) {
        $allowed = ['open', 'found_owner', 'claimed'];
        if (!in_array($label, $allowed)) return false;
        $sql = "UPDATE items SET status_label = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $label, $item_id);
        return mysqli_stmt_execute($stmt);
    }
}
?>