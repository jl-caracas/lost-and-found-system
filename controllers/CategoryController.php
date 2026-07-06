<?php
/**
 * controllers/CategoryController.php – Category Management Controller
 * 
 * Handles admin-only category CRUD operations:
 * - List categories (search, pagination)
 * - Create category
 * - Edit category
 * - Delete category (with item existence check)
 * - Professional UI with glass-morphism design
 */

require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/AuditLog.php';

class CategoryController {
    private $categoryModel;
    private $auditLog;
    private $conn;

    public function __construct($db_connection) {
        $this->conn = $db_connection;
        $this->categoryModel = new Category($db_connection);
        $this->auditLog = new AuditLog($db_connection);
    }

    /**
     * Helper: ensure user is admin
     */
    private function checkAdmin() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
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
     * List categories with search and pagination
     */
    public function index() {
        $this->checkAdmin();
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $categories = $this->categoryModel->getAll($search, $limit, $offset);
        $total = $this->categoryModel->countAll($search);
        $totalPages = ceil($total / $limit);

        // Get stats for dashboard cards
        $stats_sql = "SELECT COUNT(*) as total FROM categories";
        $stats_result = mysqli_query($this->conn, $stats_sql);
        $stats_row = mysqli_fetch_assoc($stats_result);
        $total_categories = $stats_row['total'] ?? 0;

        // Count total items
        $item_count_sql = "SELECT COUNT(*) as total FROM items";
        $item_result = mysqli_query($this->conn, $item_count_sql);
        $item_row = mysqli_fetch_assoc($item_result);
        $total_items = $item_row['total'] ?? 0;

        // Get item count per category (for display in table)
        $item_counts = [];
        $count_sql = "SELECT category_id, COUNT(*) as count FROM items GROUP BY category_id";
        $count_result = mysqli_query($this->conn, $count_sql);
        while($row = mysqli_fetch_assoc($count_result)) {
            $item_counts[$row['category_id']] = $row['count'];
        }

        include __DIR__ . '/../views/categories/index.php';
    }

    /**
     * Show form to create a new category
     */
    public function create() {
        $this->checkAdmin();
        include __DIR__ . '/../views/categories/create.php';
    }

    /**
     * Store new category
     */
    public function store() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            $errors = [];

            if (empty($name)) {
                $errors[] = "Category name is required.";
            }

            // Check for duplicate category name
            $check_sql = "SELECT id FROM categories WHERE name = ?";
            $check_stmt = mysqli_prepare($this->conn, $check_sql);
            mysqli_stmt_bind_param($check_stmt, "s", $name);
            mysqli_stmt_execute($check_stmt);
            $check_result = mysqli_stmt_get_result($check_stmt);
            if (mysqli_num_rows($check_result) > 0) {
                $errors[] = "Category with this name already exists.";
            }

            if (empty($errors)) {
                if ($this->categoryModel->create($name, $description)) {
                    $this->logAction("Added category: $name", "categories");
                    $_SESSION['success'] = "Category created successfully.";
                    header("Location: index.php?action=categories");
                    exit();
                } else {
                    $errors[] = "Database error. Could not create category.";
                }
            }
            include __DIR__ . '/../views/categories/create.php';
        } else {
            $this->create();
        }
    }

    /**
     * Show edit form
     */
    public function edit() {
        $this->checkAdmin();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $category = $this->categoryModel->getById($id);
        if (!$category) {
            $_SESSION['error'] = "Category not found.";
            header("Location: index.php?action=categories");
            exit();
        }
        include __DIR__ . '/../views/categories/edit.php';
    }

    /**
     * Update category
     */
    public function update() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['id'];
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            $errors = [];

            if (empty($name)) {
                $errors[] = "Category name is required.";
            }

            // Check for duplicate category name (excluding current)
            $check_sql = "SELECT id FROM categories WHERE name = ? AND id != ?";
            $check_stmt = mysqli_prepare($this->conn, $check_sql);
            mysqli_stmt_bind_param($check_stmt, "si", $name, $id);
            mysqli_stmt_execute($check_stmt);
            $check_result = mysqli_stmt_get_result($check_stmt);
            if (mysqli_num_rows($check_result) > 0) {
                $errors[] = "Category with this name already exists.";
            }

            if (empty($errors)) {
                if ($this->categoryModel->update($id, $name, $description)) {
                    $this->logAction("Updated category: $name", "categories");
                    $_SESSION['success'] = "Category updated successfully.";
                } else {
                    $_SESSION['error'] = "Update failed.";
                }
            } else {
                $_SESSION['error'] = implode(", ", $errors);
            }
            header("Location: index.php?action=categories");
            exit();
        }
        $this->edit();
    }

    /**
     * Delete category (with item existence check)
     */
    public function delete() {
        $this->checkAdmin();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $category = $this->categoryModel->getById($id);
        
        if (!$category) {
            $_SESSION['error'] = "Category not found.";
            header("Location: index.php?action=categories");
            exit();
        }

        // Check if items exist in this category
        $check_sql = "SELECT id FROM items WHERE category_id = ? LIMIT 1";
        $check_stmt = mysqli_prepare($this->conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "i", $id);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);
        
        if (mysqli_num_rows($check_result) > 0) {
            $_SESSION['error'] = "Cannot delete category because it has items linked to it.";
        } else {
            if ($this->categoryModel->delete($id)) {
                $this->logAction("Deleted category: {$category['name']}", "categories");
                $_SESSION['success'] = "Category deleted successfully.";
            } else {
                $_SESSION['error'] = "Delete failed.";
            }
        }
        header("Location: index.php?action=categories");
        exit();
    }
}
?>