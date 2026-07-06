<?php
/**
 * controllers/ItemController.php – Item Controller
 * 
 * Handles item CRUD operations with absolute image paths
 */

require_once __DIR__ . '/../models/Item.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/AuditLog.php';

class ItemController {
    private $itemModel;
    private $categoryModel;
    private $auditLog;
    private $conn;

    public function __construct($db_connection) {
        $this->conn = $db_connection;
        $this->itemModel = new Item($db_connection);
        $this->categoryModel = new Category($db_connection);
        $this->auditLog = new AuditLog($db_connection);
    }

    private function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Please login to continue.";
            header("Location: index.php?action=login");
            exit();
        }
    }

    private function requireAdmin() {
        $this->requireLogin();
        if ($_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = "Access denied. Admin only.";
            header("Location: index.php?action=dashboard");
            exit();
        }
    }

    private function logAction($action, $module) {
        $user_id = $_SESSION['user_id'] ?? null;
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $this->auditLog->log($user_id, $action, $module, $ip);
    }

    public function index() {
        $this->requireLogin();
        $status = isset($_GET['status']) ? $_GET['status'] : '';
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'newest';
        $limit = 12;
        $offset = ($page - 1) * $limit;
        $current_user_id = $_SESSION['user_id'] ?? null;

        $items = $this->itemModel->getAll($status, $search, $category_id, $limit, $offset, $sort_by, $current_user_id);
        $total = $this->itemModel->countAll($status, $search, $category_id);
        $totalPages = ceil($total / $limit);

        $categories = $this->categoryModel->getAll('', 100, 0);

        include __DIR__ . '/../views/items/index.php';
    }

    public function view() {
        $this->requireLogin();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        $item = $this->itemModel->getById($id);
        if (!$item) {
            $_SESSION['error'] = "Item not found.";
            header("Location: index.php?action=items");
            exit();
        }
        
        $similar_items = $this->itemModel->getSimilarItems($id, $item['category_id']);
        $additionalPhotos = $this->itemModel->getAdditionalPhotos($id);
        
        include __DIR__ . '/../views/items/view.php';
    }

    public function create() {
        $this->requireLogin();
        $categories = $this->categoryModel->getAll('', 100, 0);
        include __DIR__ . '/../views/items/create.php';
    }

    public function store() {
        $this->requireLogin();
        
        // Check if form was submitted
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=items_create");
            exit();
        }
        
        // Get form data
        $category_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;
        $item_name = isset($_POST['item_name']) ? trim($_POST['item_name']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $reward = isset($_POST['reward']) ? trim($_POST['reward']) : null;
        if (empty($reward)) $reward = null;
        $status = isset($_POST['status']) ? $_POST['status'] : '';
        $location = isset($_POST['location']) ? trim($_POST['location']) : '';
        $specific_location = isset($_POST['specific_location']) ? trim($_POST['specific_location']) : null;
        $date_reported = isset($_POST['date_reported']) ? $_POST['date_reported'] : '';
        $date_reported = str_replace('T', ' ', $date_reported); // Ensure MySQL format
        $latitude = !empty($_POST['latitude']) ? (float)$_POST['latitude'] : null;
        $longitude = !empty($_POST['longitude']) ? (float)$_POST['longitude'] : null;
        $reported_by = $_SESSION['user_id'];

        $errors = [];

        // Validation
        if ($category_id <= 0) $errors[] = "Please select a category.";
        if (empty($item_name)) $errors[] = "Item name is required.";
        if (empty($location)) $errors[] = "Location is required.";
        if (empty($date_reported)) $errors[] = "Date reported is required.";
        if (!in_array($status, ['lost', 'found'])) $errors[] = "Invalid status.";

        // Photo upload
        $photo_path = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['photo']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                $errors[] = "Only JPG, PNG, GIF files are allowed.";
            }
            if ($_FILES['photo']['size'] > 2 * 1024 * 1024) {
                $errors[] = "File size must be less than 2MB.";
            }
            if (empty($errors)) {
                $new_name = uniqid() . '.' . $ext;
                $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/LF-web2/assets/uploads/items/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $new_name)) {
                    $photo_path = 'assets/uploads/items/' . $new_name;
                } else {
                    $errors[] = "Failed to upload image.";
                }
            }
        }

        // If no errors, save to database
        if (empty($errors)) {
            $item_id = $this->itemModel->create($category_id, $item_name, $description, $reward, $status, $location, $specific_location, $date_reported, $photo_path, $reported_by, $latitude, $longitude);
            
            if ($item_id) {
                if (isset($_FILES['additional_photos'])) {
                    $total = count($_FILES['additional_photos']['name']);
                    for ($i = 0; $i < min($total, 4); $i++) {
                        if ($_FILES['additional_photos']['error'][$i] == 0) {
                            $ext2 = strtolower(pathinfo($_FILES['additional_photos']['name'][$i], PATHINFO_EXTENSION));
                            if (in_array($ext2, ['jpg', 'jpeg', 'png', 'gif']) && $_FILES['additional_photos']['size'][$i] <= 2*1024*1024) {
                                $new_name2 = uniqid('add_') . '.' . $ext2;
                                $upload_dir2 = $_SERVER['DOCUMENT_ROOT'] . '/LF-web2/assets/uploads/items/';
                                if (move_uploaded_file($_FILES['additional_photos']['tmp_name'][$i], $upload_dir2 . $new_name2)) {
                                    $this->itemModel->addAdditionalPhoto($item_id, 'assets/uploads/items/' . $new_name2);
                                }
                            }
                        }
                    }
                }
                $this->logAction("Added item: $item_name", "items");

                // Auto-notify users who lost an item in the same category
                if ($status === 'found') {
                    $finder_id = $_SESSION['user_id'];
                    $sql_match = "SELECT DISTINCT reported_by, item_name FROM items WHERE category_id = ? AND status = 'lost' AND status_label != 'claimed' AND reported_by != ?";
                    $stmt_match = mysqli_prepare($this->conn, $sql_match);
                    mysqli_stmt_bind_param($stmt_match, "ii", $category_id, $finder_id);
                    mysqli_stmt_execute($stmt_match);
                    $result_match = mysqli_stmt_get_result($stmt_match);
                    
                    if (mysqli_num_rows($result_match) > 0) {
                        require_once __DIR__ . '/../models/Message.php';
                        $msgModel = new Message($this->conn);
                        
                        while ($lost_item = mysqli_fetch_assoc($result_match)) {
                            $to_user = $lost_item['reported_by'];
                            
                            // Send the automated message from the Finder directly,
                            // so the user who lost the item can reply immediately to them.
                            $notification = "🔔 Automated Match Alert: I just posted a 'Found' item ($item_name) in a category where you recently lost something (" . $lost_item['item_name'] . "). Please check my post to see if it belongs to you!";
                            $msgModel->send($finder_id, $to_user, $item_id, $notification);
                        }
                    }
                }

                $_SESSION['success'] = "Item reported successfully.";
                header("Location: index.php?action=items");
                exit();
            } else {
                $_SESSION['error'] = "Failed to report item.";
                include __DIR__ . '/../views/items/create.php';
            }
        } else {
            $_SESSION['error'] = implode('<br>', $errors);
            include __DIR__ . '/../views/items/create.php';
        }
    }

    public function edit() {
        $this->requireLogin();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $item = $this->itemModel->getById($id);
        if (!$item) {
            $_SESSION['error'] = "Item not found.";
            header("Location: index.php?action=items");
            exit();
        }
        
        // Ownership check
        if ($_SESSION['role'] !== 'admin' && $item['reported_by'] !== $_SESSION['user_id']) {
            $_SESSION['error'] = "Access denied.";
            header("Location: index.php?action=items");
            exit();
        }

        if ($item['status_label'] === 'claimed') {
            $_SESSION['error'] = "You cannot edit an item that has already been claimed.";
            header("Location: index.php?action=items_view&id=" . $id);
            exit();
        }

        $categories = $this->categoryModel->getAll('', 100, 0);
        $additionalPhotos = $this->itemModel->getAdditionalPhotos($id);
        include __DIR__ . '/../views/items/edit.php';
    }

    public function update() {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['id'];
            $item = $this->itemModel->getById($id);
            if (!$item || ($_SESSION['role'] !== 'admin' && $item['reported_by'] !== $_SESSION['user_id'])) {
                $_SESSION['error'] = "Access denied.";
                header("Location: index.php?action=items");
                exit();
            }

            if ($item['status_label'] === 'claimed') {
                $_SESSION['error'] = "You cannot update an item that has already been claimed.";
                header("Location: index.php?action=items_view&id=" . $id);
                exit();
            }
            $category_id = (int)$_POST['category_id'];
            $item_name = trim($_POST['item_name']);
            $description = trim($_POST['description']);
            $reward = isset($_POST['reward']) ? trim($_POST['reward']) : null;
            if (empty($reward)) $reward = null;
            $status = $_POST['status'];
            $location = trim($_POST['location']);
            $specific_location = isset($_POST['specific_location']) ? trim($_POST['specific_location']) : null;
            $latitude = !empty($_POST['latitude']) ? (float)$_POST['latitude'] : null;
            $longitude = !empty($_POST['longitude']) ? (float)$_POST['longitude'] : null;
            $date_reported = str_replace('T', ' ', $_POST['date_reported']);

            $errors = [];

            if ($category_id <= 0) $errors[] = "Select category.";
            if (empty($item_name)) $errors[] = "Item name required.";
            if (empty($location)) $errors[] = "Location required.";
            if (empty($date_reported)) $errors[] = "Date required.";

            if (isset($_POST['delete_additional']) && is_array($_POST['delete_additional'])) {
                foreach ($_POST['delete_additional'] as $del_id) {
                    $this->itemModel->deleteAdditionalPhoto((int)$del_id);
                }
            }

            $photo_path = null;
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
                if (!in_array($ext, $allowed)) {
                    $errors[] = "Only JPG, PNG, GIF allowed.";
                }
                if ($_FILES['photo']['size'] > 2*1024*1024) {
                    $errors[] = "File max 2MB.";
                }
                if (empty($errors)) {
                    $new_name = uniqid() . '.' . $ext;
                    $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/LF-web2/assets/uploads/items/';
                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $new_name)) {
                        $old_item = $this->itemModel->getById($id);
                        if ($old_item && !empty($old_item['photo'])) {
                            $old_file = $_SERVER['DOCUMENT_ROOT'] . '/LF-web2/' . $old_item['photo'];
                            if (file_exists($old_file)) unlink($old_file);
                        }
                        $photo_path = 'assets/uploads/items/' . $new_name;
                    }
                }
            }

            if (empty($errors)) {
                $result = $this->itemModel->update($id, $category_id, $item_name, $description, $reward, $status, $location, $specific_location, $date_reported, $photo_path, $latitude, $longitude);
                if ($result) {
                    if (isset($_FILES['additional_photos'])) {
                        $total = count($_FILES['additional_photos']['name']);
                        for ($i = 0; $i < min($total, 4); $i++) {
                            if ($_FILES['additional_photos']['error'][$i] == 0) {
                                $ext2 = strtolower(pathinfo($_FILES['additional_photos']['name'][$i], PATHINFO_EXTENSION));
                                if (in_array($ext2, ['jpg', 'jpeg', 'png', 'gif']) && $_FILES['additional_photos']['size'][$i] <= 2*1024*1024) {
                                    $new_name2 = uniqid('add_') . '.' . $ext2;
                                    $upload_dir2 = $_SERVER['DOCUMENT_ROOT'] . '/LF-web2/assets/uploads/items/';
                                    if (move_uploaded_file($_FILES['additional_photos']['tmp_name'][$i], $upload_dir2 . $new_name2)) {
                                        $this->itemModel->addAdditionalPhoto($id, 'assets/uploads/items/' . $new_name2);
                                    }
                                }
                            }
                        }
                    }
                    $this->logAction("Updated item: $item_name", "items");
                    $_SESSION['success'] = "Item updated successfully.";
                    header("Location: index.php?action=items");
                    exit();
                } else {
                    $_SESSION['error'] = "Failed to update item.";
                    include __DIR__ . '/../views/items/edit.php';
                }
            } else {
                $_SESSION['error'] = implode('<br>', $errors);
                include __DIR__ . '/../views/items/edit.php';
            }
        }
    }

    public function delete() {
        $this->requireLogin();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $item = $this->itemModel->getById($id);
        if (!$item || ($_SESSION['role'] !== 'admin' && $item['reported_by'] !== $_SESSION['user_id'])) {
            $_SESSION['error'] = "Access denied.";
            header("Location: index.php?action=items");
            exit();
        }
        if ($this->itemModel->delete($id)) {
            $this->logAction("Deleted item: {$item['item_name']}", "items");
            $_SESSION['success'] = "Item deleted.";
        } else {
            $_SESSION['error'] = "Delete failed.";
        }
        header("Location: index.php?action=items");
        exit();
    }

    public function markClaimed() {
        $this->requireLogin();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $item = $this->itemModel->getById($id);
        if (!$item || ($_SESSION['role'] !== 'admin' && $item['reported_by'] !== $_SESSION['user_id'])) {
            $_SESSION['error'] = "Access denied.";
            header("Location: index.php?action=items");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proof_document = '';
            if (isset($_FILES['proof']) && $_FILES['proof']['error'] == 0) {
                $allowed = ['jpg','jpeg','png','gif','pdf'];
                $ext = strtolower(pathinfo($_FILES['proof']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, $allowed) && $_FILES['proof']['size'] <= 2*1024*1024) {
                    $new_name = uniqid('proof_') . '.' . $ext;
                    $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/LF-web2/assets/uploads/claims/';
                    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                    if (move_uploaded_file($_FILES['proof']['tmp_name'], $upload_dir . $new_name)) {
                        $proof_document = 'assets/uploads/claims/' . $new_name;
                    }
                } else {
                    $_SESSION['error'] = "Invalid file or file too large (Max 2MB).";
                    header("Location: index.php?action=items_mark_claimed&id=" . $id);
                    exit();
                }
            } else {
                $_SESSION['error'] = "Proof document is required.";
                header("Location: index.php?action=items_mark_claimed&id=" . $id);
                exit();
            }

            if ($this->itemModel->updateStatusLabel($id, 'claimed')) {
                $this->logAction("Marked item as claimed: {$item['item_name']}", "items");
                
                require_once __DIR__ . '/../models/Claim.php';
                require_once __DIR__ . '/../models/User.php';
                $claimModel = new Claim($this->conn);
                $userModel = new User($this->conn);
                $poster = $userModel->getById($item['reported_by']);
                
                $claimant_name = $poster ? ($poster['first_name'] . ' ' . $poster['last_name']) : 'Original Poster';
                $claimant_id_type = 'other';
                $claimant_id_number = 'SELF-CLAIMED / SYSTEM BYPASS';
                $claimant_contact = 'N/A';
                $claim_date = date('Y-m-d H:i:s');
                
                $claimModel->create($id, $claimant_name, $claimant_id_type, $claimant_id_number, $claimant_contact, $proof_document, $claim_date);
                
                $_SESSION['success'] = "Item successfully marked as claimed. A paper trail with proof has been submitted to the Admin.";
            } else {
                $_SESSION['error'] = "Failed to mark item as claimed.";
            }
            header("Location: index.php?action=items_view&id=" . $id);
            exit();
        }

        // GET request - Show form
        include __DIR__ . '/../views/items/mark_claimed.php';
    }

    public function api() {
        header('Content-Type: application/json');
        $status = isset($_GET['status']) ? $_GET['status'] : '';
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
        
        $items_result = $this->itemModel->getAll($status, $search, $category_id, 500, 0);
        $items = [];
        
        if ($items_result) {
            while ($row = mysqli_fetch_assoc($items_result)) {
                if (!empty($row['latitude']) && !empty($row['longitude'])) {
                    $items[] = [
                        'id' => $row['id'],
                        'name' => $row['item_name'],
                        'status' => $row['status'],
                        'latitude' => $row['latitude'],
                        'longitude' => $row['longitude']
                    ];
                }
            }
        }
        echo json_encode($items);
        exit();
    }
}
?>