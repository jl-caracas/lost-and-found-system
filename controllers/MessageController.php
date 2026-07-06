<?php
/**
 * controllers/MessageController.php – Message Controller
 * 
 * Handles messaging between users:
 * - Inbox (list conversations)
 * - Chat (per item conversation)
 * - Send message (AJAX)
 * - Fetch messages (AJAX polling)
 * - Unread count (AJAX)
 * - Mark messages as read
 * - Avatar support (first letter of username)
 */

require_once __DIR__ . '/../models/Message.php';
require_once __DIR__ . '/../models/Item.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/AuditLog.php';

class MessageController {
    private $messageModel;
    private $itemModel;
    private $userModel;
    private $auditLog;
    private $conn;

    public function __construct($db_connection) {
        $this->conn = $db_connection;
        $this->messageModel = new Message($db_connection);
        $this->itemModel = new Item($db_connection);
        $this->userModel = new User($db_connection);
        $this->auditLog = new AuditLog($db_connection);
    }

    /**
     * Helper: ensure user is logged in
     */
    private function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            if ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '' == 'XMLHttpRequest') {
                http_response_code(401);
                echo json_encode(['error' => 'Please login']);
                exit();
            }
            $_SESSION['error'] = "Please login to continue.";
            header("Location: index.php?action=login");
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
     * Generate avatar HTML from username
     * @param string $username
     * @return string
     */
    private function getAvatar($username) {
        $initial = strtoupper(substr($username, 0, 1));
        return '<div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-sm flex-shrink-0">' . $initial . '</div>';
    }

    /**
     * Show inbox (list of conversations)
     */
    public function inbox() {
        $this->requireLogin();
        $conversations = $this->messageModel->getConversations($_SESSION['user_id']);
        include __DIR__ . '/../views/messages/inbox.php';
    }

    /**
     * Show chat window for a specific item
     */
    public function chat() {
        $this->requireLogin();
        $item_id = isset($_GET['item_id']) ? (int)$_GET['item_id'] : 0;
        
        $item = $this->itemModel->getById($item_id);
        if (!$item) {
            $_SESSION['error'] = "Item not found.";
            header("Location: index.php?action=inbox");
            exit();
        }
        
        $current_user = $_SESSION['user_id'];
        $other_user_id = isset($_GET['other_user_id']) ? (int)$_GET['other_user_id'] : null;
        
        // Determine the other participant
        if (!$other_user_id) {
            if ($item['reported_by'] == $current_user) {
                $_SESSION['error'] = "Please select a conversation from your inbox.";
                header("Location: index.php?action=inbox");
                exit();
            } else {
                // Current user is not the reporter – other participant is the reporter
                $other_user_id = $item['reported_by'];
            }
        }
        
        $other_user = $this->userModel->getById($other_user_id);
        // Get initial for avatar
        $other_initial = strtoupper(substr($other_user['username'], 0, 1));
        
        include __DIR__ . '/../views/messages/chat.php';
    }

    /**
     * Send message via AJAX
     */
    public function send() {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $from_user_id = $_SESSION['user_id'];
            $to_user_id = (int)$_POST['to_user_id'];
            $item_id = (int)$_POST['item_id'];
            $message = trim($_POST['message']);
            $photo_path = null;

            // Basic validation
            if (empty($message) && !isset($_FILES['photo'])) {
                echo json_encode(['success' => false, 'error' => 'Message or photo required']);
                exit();
            }

            // Handle photo upload
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
                $allowed = ['jpg','jpeg','png','gif'];
                $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
                if (!in_array($ext, $allowed)) {
                    echo json_encode(['success' => false, 'error' => 'Only JPG, PNG, GIF']);
                    exit();
                }
                if ($_FILES['photo']['size'] > 2*1024*1024) {
                    echo json_encode(['success' => false, 'error' => 'Max 2MB']);
                    exit();
                }
                $new_name = uniqid() . '.' . $ext;
                $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/LF-web2/assets/uploads/messages/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $new_name)) {
                    $photo_path = 'assets/uploads/messages/' . $new_name;
                } else {
                    echo json_encode(['success' => false, 'error' => 'Upload failed']);
                    exit();
                }
            }

            if ($this->messageModel->send($from_user_id, $to_user_id, $item_id, $message, $photo_path)) {
                $this->logAction("Sent message on item ID: $item_id", "messages");
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Database error']);
            }
            exit();
        }
    }

    /**
     * Fetch new messages via AJAX polling
     */
    public function fetch() {
        $this->requireLogin();
        $item_id = (int)$_GET['item_id'];
        $other_user_id = (int)$_GET['other_user_id'];
        $last_id = isset($_GET['last_id']) ? (int)$_GET['last_id'] : 0;

        $messages = $this->messageModel->getMessagesForItem($item_id, $_SESSION['user_id'], $other_user_id);
        
        // Mark received messages as read
        $this->messageModel->markAsRead($item_id, $_SESSION['user_id']);

        $output = [];
        while ($msg = mysqli_fetch_assoc($messages)) {
            if ($msg['id'] > $last_id) {
                $initial = strtoupper(substr($msg['from_username'], 0, 1));
                $output[] = [
                    'id' => $msg['id'],
                    'from_username' => $msg['from_username'],
                    'from_user_id' => $msg['from_user_id'],
                    'message' => nl2br(htmlspecialchars($msg['message'])),
                    'photo' => $msg['photo'] ? '/LF-web2/' . $msg['photo'] : null,
                    'avatar' => $initial,
                    'created_at' => date('g:i A', strtotime($msg['created_at'])),
                    'is_mine' => ($msg['from_user_id'] == $_SESSION['user_id'])
                ];
            }
        }
        echo json_encode($output);
        exit();
    }

    /**
     * Get unread count for navbar badge (AJAX)
     */
    public function unreadCount() {
        $this->requireLogin();
        $count = $this->messageModel->getTotalUnread($_SESSION['user_id']);
        echo json_encode(['unread' => $count]);
        exit();
    }
}
?>