<?php
/**
 * controllers/ReportController.php – Report Controller
 * 
 * Handles report generation and printable views
 */

require_once __DIR__ . '/../models/Item.php';
require_once __DIR__ . '/../models/Category.php';

class ReportController {
    private $itemModel;
    private $categoryModel;
    private $conn;

    public function __construct($db_connection) {
        $this->conn = $db_connection;
        $this->itemModel = new Item($db_connection);
        $this->categoryModel = new Category($db_connection);
    }

    private function requireAdmin() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        if ($_SESSION['role'] !== 'admin') {
            header("Location: index.php?action=dashboard");
            exit();
        }
    }

    public function index() {
        $this->requireAdmin();
        $status = isset($_GET['status']) ? $_GET['status'] : '';
        $category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

        $categories = $this->categoryModel->getAll('', 100, 0);
        $items = $this->itemModel->getForReport($status, $category_id, $start_date, $end_date);

        include __DIR__ . '/../views/reports/index.php';
    }

    public function printReport() {
        $this->requireAdmin();
        $status = isset($_GET['status']) ? $_GET['status'] : '';
        $category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

        $category_name = 'All Categories';
        if ($category_id > 0) {
            $cat = $this->categoryModel->getById($category_id);
            if ($cat) $category_name = $cat['name'];
        }

        $items = $this->itemModel->getForReport($status, $category_id, $start_date, $end_date);

        include __DIR__ . '/../views/reports/print.php';
    }
}
?>
