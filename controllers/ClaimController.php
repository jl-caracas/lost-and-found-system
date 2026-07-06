<?php
/**
 * controllers/ClaimController.php – Claim Management Controller
 * 
 * Handles:
 * - Claim submission (users claiming found items)
 * - Admin/finder claim list with search, filter, pagination
 * - Claim status update (pending → approved → claimed / rejected)
 * - Claim deletion (admin only)
 * - Finder's claims view (my_claims)
 */

require_once __DIR__ . '/../models/Claim.php';
require_once __DIR__ . '/../models/Item.php';
require_once __DIR__ . '/../models/AuditLog.php';

class ClaimController {
    private $claimModel;
    private $itemModel;
    private $auditLog;
    private $conn;

    public function __construct($db_connection) {
        $this->conn = $db_connection;
        $this->claimModel = new Claim($db_connection);
        $this->itemModel = new Item($db_connection);
        $this->auditLog = new AuditLog($db_connection);
    }

    /**
     * Helper: ensure user is logged in
     */
    private function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Please login to continue.";
            header("Location: index.php?action=login");
            exit();
        }
    }

    /**
     * Helper: ensure user is admin
     */
    private function requireAdmin() {
        $this->requireLogin();
        if ($_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = "Access denied. Admin only.";
            header("Location: index.php?action=dashboard");
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
     * Show form to claim a specific item (only for found items)
     */
    public function create() {
        $this->requireLogin();
        $item_id = isset($_GET['item_id']) ? (int)$_GET['item_id'] : 0;
        $item = $this->itemModel->getById($item_id);
        
        if (!$item) {
            $_SESSION['error'] = "Item not found.";
            header("Location: index.php?action=items");
            exit();
        }
        
        if ($item['status'] != 'found') {
            $_SESSION['error'] = "You can only claim found items.";
            header("Location: index.php?action=items");
            exit();
        }
        
        if ($item['status_label'] == 'claimed') {
            $_SESSION['error'] = "This item has already been claimed.";
            header("Location: index.php?action=items");
            exit();
        }
        
        include __DIR__ . '/../views/claims/create.php';
    }

    /**
     * Store claim submission
     */
    public function store() {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $item_id = (int)$_POST['item_id'];
            $claimant_name = trim($_POST['claimant_name']);
            $claimant_id_type = $_POST['claimant_id_type'];
            $claimant_id_number = trim($_POST['claimant_id_number']);
            $claimant_contact = trim($_POST['claimant_contact']);
            $claim_date = $_POST['claim_date'];

            $errors = [];

            // Validation
            if (empty($claimant_name)) $errors[] = "Full name is required.";
            if (empty($claimant_id_number)) $errors[] = "ID number is required.";
            if (empty($claim_date)) $errors[] = "Claim date is required.";

            // Proof upload
            $proof_path = null;
            if (isset($_FILES['proof']) && $_FILES['proof']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
                $ext = strtolower(pathinfo($_FILES['proof']['name'], PATHINFO_EXTENSION));
                if (!in_array($ext, $allowed)) {
                    $errors[] = "Proof must be JPG, PNG, GIF, or PDF.";
                }
                if ($_FILES['proof']['size'] > 2 * 1024 * 1024) {
                    $errors[] = "Proof file must be less than 2MB.";
                }
                if (empty($errors)) {
                    $new_name = uniqid() . '.' . $ext;
                    $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/LF-web2/assets/uploads/proofs/';
                    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                    if (move_uploaded_file($_FILES['proof']['tmp_name'], $upload_dir . $new_name)) {
                        $proof_path = 'assets/uploads/proofs/' . $new_name;
                    } else {
                        $errors[] = "Failed to upload proof.";
                    }
                }
            } else {
                $errors[] = "Proof document is required.";
            }

            if (empty($errors)) {
                if ($this->claimModel->create($item_id, $claimant_name, $claimant_id_type, 
                                              $claimant_id_number, $claimant_contact, $proof_path, $claim_date)) {
                    $this->logAction("Claim submitted for item ID: $item_id by $claimant_name", "claims");
                    $_SESSION['success'] = "Claim submitted successfully. The finder will review it.";
                    header("Location: index.php?action=items");
                    exit();
                } else {
                    $errors[] = "Database error. Could not submit claim.";
                }
            }
            
            $item = $this->itemModel->getById($item_id);
            include __DIR__ . '/../views/claims/create.php';
        } else {
            $this->create();
        }
    }

    /**
     * Admin: list all claims with filters
     */
    public function index() {
        $this->requireAdmin();
        $status = isset($_GET['status']) ? $_GET['status'] : '';
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $claims = $this->claimModel->getAll($status, $search, $limit, $offset);
        $total = $this->claimModel->countAll($status, $search);
        $totalPages = ceil($total / $limit);

        include __DIR__ . '/../views/claims/index.php';
    }

    /**
     * Show edit/update form for claim status (admin or finder)
     */
    public function edit() {
        $this->requireLogin();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $claim = $this->claimModel->getById($id);
        
        if (!$claim) {
            $_SESSION['error'] = "Claim not found.";
            if ($_SESSION['role'] === 'admin') {
                header("Location: index.php?action=claims");
            } else {
                header("Location: index.php?action=my_claims");
            }
            exit();
        }
        
        // Check permission: admin OR finder (reporter of the item)
        $item = $this->itemModel->getById($claim['item_id']);
        $is_finder = ($item && $item['reported_by'] == $_SESSION['user_id']);
        
        if ($_SESSION['role'] !== 'admin' && !$is_finder) {
            $_SESSION['error'] = "You can only manage claims on your own found items.";
            header("Location: index.php?action=dashboard");
            exit();
        }
        
        if ($_SESSION['role'] !== 'admin' && $claim['status'] !== 'pending') {
            $_SESSION['error'] = "You cannot modify a claim that has already been processed or finalized.";
            header("Location: index.php?action=my_claims");
            exit();
        }
        
        include __DIR__ . '/../views/claims/edit.php';
    }

    /**
     * Update claim status (admin or finder)
     */
    public function update() {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['id'];
            $status = $_POST['status'];
            $admin_remarks = trim($_POST['admin_remarks']);
            $processed_by = $_SESSION['user_id'];

            // Verify permission
            $claim = $this->claimModel->getById($id);
            if (!$claim) {
                $_SESSION['error'] = "Claim not found.";
                header("Location: index.php?action=claims");
                exit();
            }
            
            $item = $this->itemModel->getById($claim['item_id']);
            $is_finder = ($item && $item['reported_by'] == $_SESSION['user_id']);
            
            if ($_SESSION['role'] !== 'admin' && !$is_finder) {
                $_SESSION['error'] = "Permission denied.";
                header("Location: index.php?action=dashboard");
                exit();
            }

            if ($_SESSION['role'] !== 'admin' && $claim['status'] !== 'pending') {
                $_SESSION['error'] = "You cannot modify a claim that has already been processed or finalized.";
                header("Location: index.php?action=my_claims");
                exit();
            }

            if ($this->claimModel->updateStatus($id, $status, $admin_remarks, $processed_by)) {
                $this->logAction("Updated claim status to '$status' for claim ID: $id", "claims");
                $_SESSION['success'] = "Claim status updated.";
                
                // If status is 'claimed', update item status_label
                if ($status == 'claimed') {
                    $this->itemModel->updateStatusLabel($claim['item_id'], 'claimed');
                }
            } else {
                $_SESSION['error'] = "Update failed.";
            }
            if ($_SESSION['role'] === 'admin') {
                header("Location: index.php?action=claims");
            } else {
                header("Location: index.php?action=my_claims");
            }
            exit();
        }
        $this->edit();
    }

    /**
     * Admin: delete claim
     */
    public function delete() {
        $this->requireAdmin();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $claim = $this->claimModel->getById($id);
        
        if ($this->claimModel->delete($id)) {
            $this->logAction("Deleted claim ID: $id", "claims");
            $_SESSION['success'] = "Claim deleted.";
        } else {
            $_SESSION['error'] = "Delete failed.";
        }
        header("Location: index.php?action=claims");
        exit();
    }

    /**
     * Finder: show claims on their own items
     */
    public function myClaims() {
        $this->requireLogin();
        $finder_id = $_SESSION['user_id'];
        $status = isset($_GET['status']) ? $_GET['status'] : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $claims = $this->claimModel->getClaimsByFinder($finder_id, $status, $limit, $offset);
        $total = $this->claimModel->countClaimsByFinder($finder_id, $status);
        $totalPages = ceil($total / $limit);

        include __DIR__ . '/../views/claims/my_claims.php';
    }
}
?>