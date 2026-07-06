<?php
/**
 * controllers/DashboardController.php – Dashboard Controller
 * 
 * Handles dashboard display with summary statistics:
 * - Total users
 * - Total items (lost/found)
 * - Total claims (pending)
 * - Recent user registrations
 * - Quick action links
 */

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Item.php';
require_once __DIR__ . '/../models/Claim.php';

class DashboardController {
    private $conn;
    private $userModel;
    private $itemModel;
    private $claimModel;

    public function __construct($db_connection) {
        $this->conn = $db_connection;
        $this->userModel = new User($db_connection);
        $this->itemModel = new Item($db_connection);
        $this->claimModel = new Claim($db_connection);
    }

    /**
     * Show dashboard with statistics
     */
    public function index() {
        // Ensure user is logged in
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        // Get counts
        $totalUsers = $this->userModel->getTotalUsers();
        
        // Item counts (using existing methods with empty filters)
        $totalLost = $this->itemModel->countAll('lost', '', 0);
        $totalFound = $this->itemModel->countAll('found', '', 0);
        $totalItems = $totalLost + $totalFound;
        
        // Claim counts
        $totalPendingClaims = $this->claimModel->countAll('pending', '');
        $totalClaims = $this->claimModel->countAll('', '');

        // Pending claims on items reported by this user (relevant for non-admins)
        $myPendingClaims = $this->claimModel->countClaimsByFinder($_SESSION['user_id'], 'pending');
        
        // Items reported by this user
        $myLost = $this->itemModel->countByUser($_SESSION['user_id'], 'lost');
        $myFound = $this->itemModel->countByUser($_SESSION['user_id'], 'found');
        
        // Recent users (for admin activity feed only)
        $recentUsers = null;
        if ($_SESSION['role'] == 'admin') {
            $recentUsers = $this->userModel->getRecentUsers(5);
        }

        // Include view with all data
        include __DIR__ . '/../views/dashboard.php';
    }
}
?>