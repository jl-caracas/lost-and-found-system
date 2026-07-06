<?php
/**
 * controllers/AuditController.php – Audit Log Controller
 * 
 * Handles admin-only audit log viewing:
 * - List logs with search and pagination
 * - Shows user, action, module, IP, timestamp
 */

require_once __DIR__ . '/../models/AuditLog.php';

class AuditController {
    private $auditLog;
    private $conn;

    public function __construct($db_connection) {
        $this->conn = $db_connection;
        $this->auditLog = new AuditLog($db_connection);
    }

    /**
     * Helper: ensure user is admin
     */
    private function requireAdmin() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = "Access denied. Admin only.";
            header("Location: index.php?action=dashboard");
            exit();
        }
    }

    /**
     * List audit logs with search and pagination
     */
    public function index() {
        $this->requireAdmin();
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $module_filter = isset($_GET['module_filter']) ? trim($_GET['module_filter']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 20; // Show 20 logs per page
        $offset = ($page - 1) * $limit;

        $logs = $this->auditLog->getAll($search, $limit, $offset, $module_filter);
        $total = $this->auditLog->countAll($search, $module_filter);
        $totalPages = ceil($total / $limit);
        
        $modules_result = $this->auditLog->getDistinctModules();

        include __DIR__ . '/../views/audit/logs.php';
    }
}
?>