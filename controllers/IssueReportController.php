<?php
/**
 * controllers/IssueReportController.php – Issue Report Controller
 * 
 * Handles submission of new issues and admin/staff management of issues.
 */

require_once __DIR__ . '/../models/IssueReport.php';
require_once __DIR__ . '/../models/AuditLog.php';

class IssueReportController {
    private $issueReportModel;
    private $auditLog;
    private $conn;

    public function __construct($db_connection) {
        $this->conn = $db_connection;
        $this->issueReportModel = new IssueReport($db_connection);
        $this->auditLog = new AuditLog($db_connection);
    }

    private function requireStaffOrAdmin() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff') {
            $_SESSION['error'] = "Access denied. Staff or Admin only.";
            header("Location: index.php?action=dashboard");
            exit();
        }
    }

    private function logAction($action, $module) {
        $user_id = $_SESSION['user_id'] ?? null;
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $this->auditLog->log($user_id, $action, $module, $ip);
    }

    /**
     * Store a new issue report (accessible by anyone, even guests)
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
            $issue_type = trim($_POST['issue_type'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if (empty($issue_type) || empty($description)) {
                $_SESSION['error'] = "Please fill in all required fields.";
                header("Location: index.php?action=help");
                exit();
            }

            if ($this->issueReportModel->create($user_id, $issue_type, $description)) {
                $_SESSION['success'] = "Thank you! Your report has been submitted successfully.";
            } else {
                $_SESSION['error'] = "Something went wrong while submitting your report. Please try again.";
            }
            header("Location: index.php?action=help");
            exit();
        }
        header("Location: index.php?action=help");
    }

    /**
     * List all issue reports (Staff/Admin only)
     */
    public function index() {
        $this->requireStaffOrAdmin();
        
        $status = isset($_GET['status']) ? $_GET['status'] : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 15;
        $offset = ($page - 1) * $limit;

        $reports = $this->issueReportModel->getAll($status, $limit, $offset);
        $total = $this->issueReportModel->countAll($status);
        $totalPages = ceil($total / $limit);

        include __DIR__ . '/../views/issue_reports/index.php';
    }

    /**
     * Mark an issue report as fixed (Staff/Admin only)
     */
    public function fix() {
        $this->requireStaffOrAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            $report = $this->issueReportModel->getById($id);
            
            if ($report) {
                if ($this->issueReportModel->markAsFixed($id, $_SESSION['user_id'])) {
                    $this->logAction("Marked issue report #$id as fixed", "issue_reports");
                    $_SESSION['success'] = "Report marked as fixed successfully.";
                } else {
                    $_SESSION['error'] = "Failed to update report status.";
                }
            } else {
                $_SESSION['error'] = "Report not found.";
            }
        }
        header("Location: index.php?action=issue_reports");
        exit();
    }
}
?>
