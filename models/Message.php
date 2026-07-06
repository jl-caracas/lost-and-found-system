<?php
/**
 * models/Message.php – Message model
 * 
 * Handles all database operations for messaging:
 * - Send messages
 * - Retrieve conversations
 * - Mark messages as read
 * - Get unread counts
 * - Message with photo support
 */

class Message {
    private $conn;

    public function __construct($db_connection) {
        $this->conn = $db_connection;
    }

    /**
     * Send a message
     * @param int $from_user_id
     * @param int $to_user_id
     * @param int $item_id
     * @param string $message
     * @param string|null $photo
     * @return bool
     */
    public function send($from_user_id, $to_user_id, $item_id, $message, $photo = null) {
        $sql = "INSERT INTO messages (from_user_id, to_user_id, item_id, message, photo) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "iiiss", $from_user_id, $to_user_id, $item_id, $message, $photo);
        return mysqli_stmt_execute($stmt);
    }

    public function getConversations($user_id) {
        $sql = "SELECT 
                    i.id as item_id, i.item_name, i.status, i.photo as item_photo,
                    other_user.id as other_user_id,
                    other_user.username as other_username,
                    MAX(m.created_at) as last_message_time,
                    SUM(CASE WHEN m.to_user_id = ? AND m.is_read = 0 THEN 1 ELSE 0 END) as unread_count,
                    (SELECT message FROM messages 
                     WHERE item_id = i.id 
                       AND ((from_user_id = ? AND to_user_id = other_user.id) OR (from_user_id = other_user.id AND to_user_id = ?))
                     ORDER BY created_at DESC LIMIT 1) as last_message
                FROM items i
                INNER JOIN messages m ON i.id = m.item_id
                INNER JOIN users other_user ON other_user.id = CASE WHEN m.from_user_id = ? THEN m.to_user_id ELSE m.from_user_id END
                WHERE m.from_user_id = ? OR m.to_user_id = ?
                GROUP BY i.id, other_user.id
                ORDER BY last_message_time DESC";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "iiiiii", $user_id, $user_id, $user_id, $user_id, $user_id, $user_id);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }

    /**
     * Get all messages for a specific item (between two users)
     * @param int $item_id
     * @param int $user1
     * @param int $user2
     * @return mysqli_result
     */
    public function getMessagesForItem($item_id, $user1, $user2) {
        $sql = "SELECT m.*, u.username as from_username 
                FROM messages m
                JOIN users u ON m.from_user_id = u.id
                WHERE m.item_id = ? AND ((m.from_user_id = ? AND m.to_user_id = ?) OR (m.from_user_id = ? AND m.to_user_id = ?))
                ORDER BY m.created_at ASC";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "iiiii", $item_id, $user1, $user2, $user2, $user1);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }

    /**
     * Mark messages as read for a given item and receiver
     * @param int $item_id
     * @param int $to_user_id
     * @return bool
     */
    public function markAsRead($item_id, $to_user_id) {
        $sql = "UPDATE messages SET is_read = 1 WHERE item_id = ? AND to_user_id = ? AND is_read = 0";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $item_id, $to_user_id);
        return mysqli_stmt_execute($stmt);
    }

    /**
     * Get unread count for a user (overall)
     * @param int $user_id
     * @return int
     */
    public function getTotalUnread($user_id) {
        // If connection is null, return 0
        if (!$this->conn) {
            return 0;
        }
        $sql = "SELECT COUNT(*) as total FROM messages WHERE to_user_id = ? AND is_read = 0";
        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            return 0;
        }
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        return $row['total'] ?? 0;
    }
}
?>