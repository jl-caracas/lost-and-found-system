<?php
/**
 * index.php – Front Controller
 * 
 * This is the entry point of the application. All requests go through this file.
 * It:
 * 1. Starts the session
 * 2. Includes the database configuration
 * 3. Routes to the appropriate controller based on the 'action' parameter
 */

session_start();

// Include database connection
require_once __DIR__ . '/config/database.php';

// ===== Get the action from URL (default: landing) =====
$action = $_GET['action'] ?? 'landing';

// ===== Route to the appropriate controller =====
switch($action) {
    
    // ----- Authentication -----
    case 'login':
        require_once 'controllers/AuthController.php';
        $auth = new AuthController($conn);
        $auth->login();
        break;
        
    case 'register':
        require_once 'controllers/AuthController.php';
        $auth = new AuthController($conn);
        $auth->register();
        break;
        
    case 'logout':
        require_once 'controllers/AuthController.php';
        $auth = new AuthController($conn);
        $auth->logout();
        break;
    
    // ----- Dashboard -----
    case 'dashboard':
        require_once 'controllers/DashboardController.php';
        $dashboard = new DashboardController($conn);
        $dashboard->index();
        break;
        
    // ----- Profile Management -----
    case 'profile':
        require_once 'controllers/ProfileController.php';
        $profileCtrl = new ProfileController($conn);
        $profileCtrl->index();
        break;
        
    case 'profile_update':
        require_once 'controllers/ProfileController.php';
        $profileCtrl = new ProfileController($conn);
        $profileCtrl->update();
        break;
        
    case 'public_profile':
        require_once 'controllers/ProfileController.php';
        $profileCtrl = new ProfileController($conn);
        $profileCtrl->publicProfile();
        break;
    
    // ----- User Management (Admin) -----
    case 'users':
        require_once 'controllers/UserController.php';
        $userCtrl = new UserController($conn);
        $userCtrl->index();
        break;
        
    case 'users_create':
        require_once 'controllers/UserController.php';
        $userCtrl = new UserController($conn);
        $userCtrl->create();
        break;
        
    case 'users_store':
        require_once 'controllers/UserController.php';
        $userCtrl = new UserController($conn);
        $userCtrl->store();
        break;
        
    case 'users_edit':
        require_once 'controllers/UserController.php';
        $userCtrl = new UserController($conn);
        $userCtrl->edit();
        break;
        
    case 'users_update':
        require_once 'controllers/UserController.php';
        $userCtrl = new UserController($conn);
        $userCtrl->update();
        break;
        
    case 'users_delete':
        require_once 'controllers/UserController.php';
        $userCtrl = new UserController($conn);
        $userCtrl->delete();
        break;
        
    case 'users_reset_password':
        require_once 'controllers/UserController.php';
        $userCtrl = new UserController($conn);
        $userCtrl->resetPassword();
        break;
    
    // ----- Categories (Admin) -----
    case 'categories':
        require_once 'controllers/CategoryController.php';
        $catCtrl = new CategoryController($conn);
        $catCtrl->index();
        break;
        
    case 'categories_create':
        require_once 'controllers/CategoryController.php';
        $catCtrl = new CategoryController($conn);
        $catCtrl->create();
        break;
        
    case 'categories_store':
        require_once 'controllers/CategoryController.php';
        $catCtrl = new CategoryController($conn);
        $catCtrl->store();
        break;
        
    case 'categories_edit':
        require_once 'controllers/CategoryController.php';
        $catCtrl = new CategoryController($conn);
        $catCtrl->edit();
        break;
        
    case 'categories_update':
        require_once 'controllers/CategoryController.php';
        $catCtrl = new CategoryController($conn);
        $catCtrl->update();
        break;
        
    case 'categories_delete':
        require_once 'controllers/CategoryController.php';
        $catCtrl = new CategoryController($conn);
        $catCtrl->delete();
        break;
    
    // ----- Items -----
    case 'items':
        require_once 'controllers/ItemController.php';
        $itemCtrl = new ItemController($conn);
        $itemCtrl->index();
        break;
        
    case 'items_view':
        require_once 'controllers/ItemController.php';
        $itemCtrl = new ItemController($conn);
        $itemCtrl->view();
        break;
        
    case 'items_create':
        require_once 'controllers/ItemController.php';
        $itemCtrl = new ItemController($conn);
        $itemCtrl->create();
        break;
        
    case 'items_store':
        require_once 'controllers/ItemController.php';
        $itemCtrl = new ItemController($conn);
        $itemCtrl->store();
        break;
        
    case 'items_edit':
        require_once 'controllers/ItemController.php';
        $itemCtrl = new ItemController($conn);
        $itemCtrl->edit();
        break;
        
    case 'items_update':
        require_once 'controllers/ItemController.php';
        $itemCtrl = new ItemController($conn);
        $itemCtrl->update();
        break;
        
    case 'items_delete':
        require_once 'controllers/ItemController.php';
        $itemCtrl = new ItemController($conn);
        $itemCtrl->delete();
        break;

    case 'items_mark_claimed':
        require_once 'controllers/ItemController.php';
        $itemCtrl = new ItemController($conn);
        $itemCtrl->markClaimed();
        break;
        
    case 'items_api':
        require_once 'controllers/ItemController.php';
        $itemCtrl = new ItemController($conn);
        $itemCtrl->api();
        break;
    
    // ----- Claims -----
    case 'claims':
        require_once 'controllers/ClaimController.php';
        $claimCtrl = new ClaimController($conn);
        $claimCtrl->index();
        break;
        
    case 'my_claims':
        require_once 'controllers/ClaimController.php';
        $claimCtrl = new ClaimController($conn);
        $claimCtrl->myClaims();
        break;
        
    case 'claims_create':
        require_once 'controllers/ClaimController.php';
        $claimCtrl = new ClaimController($conn);
        $claimCtrl->create();
        break;
        
    case 'claims_store':
        require_once 'controllers/ClaimController.php';
        $claimCtrl = new ClaimController($conn);
        $claimCtrl->store();
        break;
        
    case 'claims_edit':
        require_once 'controllers/ClaimController.php';
        $claimCtrl = new ClaimController($conn);
        $claimCtrl->edit();
        break;
        
    case 'claims_update':
        require_once 'controllers/ClaimController.php';
        $claimCtrl = new ClaimController($conn);
        $claimCtrl->update();
        break;
        
    case 'claims_delete':
        require_once 'controllers/ClaimController.php';
        $claimCtrl = new ClaimController($conn);
        $claimCtrl->delete();
        break;
    
    // ----- Messages -----
    case 'inbox':
        require_once 'controllers/MessageController.php';
        $msgCtrl = new MessageController($conn);
        $msgCtrl->inbox();
        break;
        
    case 'chat':
        require_once 'controllers/MessageController.php';
        $msgCtrl = new MessageController($conn);
        $msgCtrl->chat();
        break;
        
    case 'send_message':
        require_once 'controllers/MessageController.php';
        $msgCtrl = new MessageController($conn);
        $msgCtrl->send();
        break;
        
    case 'fetch_messages':
        require_once 'controllers/MessageController.php';
        $msgCtrl = new MessageController($conn);
        $msgCtrl->fetch();
        break;
        
    case 'unread_count':
        require_once 'controllers/MessageController.php';
        $msgCtrl = new MessageController($conn);
        $msgCtrl->unreadCount();
        break;
    
    // ----- Audit Logs (Admin) -----
    case 'audit_logs':
        require_once 'controllers/AuditController.php';
        $auditCtrl = new AuditController($conn);
        $auditCtrl->index();
        break;
        
    // ----- Reports (Admin) -----
    case 'reports':
        require_once 'controllers/ReportController.php';
        $reportCtrl = new ReportController($conn);
        $reportCtrl->index();
        break;
        
    case 'reports_print':
        require_once 'controllers/ReportController.php';
        $reportCtrl = new ReportController($conn);
        $reportCtrl->printReport();
        break;
        
    // ----- Support / Issue Reports (Staff & Admin) -----
    case 'issue_reports':
        require_once 'controllers/IssueReportController.php';
        $issueCtrl = new IssueReportController($conn);
        $issueCtrl->index();
        break;
        
    case 'issue_reports_store':
        require_once 'controllers/IssueReportController.php';
        $issueCtrl = new IssueReportController($conn);
        $issueCtrl->store();
        break;
        
    case 'issue_reports_fix':
        require_once 'controllers/IssueReportController.php';
        $issueCtrl = new IssueReportController($conn);
        $issueCtrl->fix();
        break;
    
    // ----- Public Pages -----
    case 'landing':
        require_once 'models/Item.php';
        $itemModel = new Item($conn);
        $totalLostStats = $itemModel->countAll('lost', '', 0);
        $totalFoundStats = $itemModel->countAll('found', '', 0);
        $totalClaimedStats = $itemModel->countAll('claimed', '', 0);
        include 'views/landing.php';
        break;
        
    case 'terms':
        include 'views/terms.php';
        break;
        
    case 'help':
        include 'views/help.php';
        break;
    
    // ----- Default: Landing page -----
    default:
        require_once 'models/Item.php';
        $itemModel = new Item($conn);
        $totalLostStats = $itemModel->countAll('lost', '', 0);
        $totalFoundStats = $itemModel->countAll('found', '', 0);
        $totalClaimedStats = $itemModel->countAll('claimed', '', 0);
        include 'views/landing.php';
        break;
}
?>