<?php
/**
 * includes/header.php – Main layout header with glass-morphism navbar
 */

// Calculate unread message count and fetch profile picture if user is logged in AND $conn is available
$unread_count = 0;
if (isset($_SESSION['user_id']) && isset($conn) && $conn) {
    if (!isset($_SESSION['profile_picture']) && file_exists(__DIR__ . '/../models/User.php')) {
        require_once __DIR__ . '/../models/User.php';
        try {
            $userModel = new User($conn);
            $user = $userModel->getById($_SESSION['user_id']);
            $_SESSION['profile_picture'] = $user['profile_picture'] ?? null;
        } catch (Exception $e) {}
    }

    if (file_exists(__DIR__ . '/../models/Message.php')) {
        require_once __DIR__ . '/../models/Message.php';
        try {
            $msgModel = new Message($conn);
            $unread_count = $msgModel->getTotalUnread($_SESSION['user_id']);
        } catch (Exception $e) {
            $unread_count = 0;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foundly | Lost & Found System</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        .glass {
            background: rgba(248, 250, 252, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 10px 40px -10px rgba(15, 23, 42, 0.08);
            border-radius: 1.5rem;
        }
        .card-shadow {
            box-shadow: 0 10px 40px -10px rgba(15, 23, 42, 0.08);
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
        }
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f6faff;
        }
        ::-webkit-scrollbar-thumb {
            background: #c5c6cd;
            border-radius: 10px;
        }
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        :root {
            --primary: #4338ca;
            --on-primary: #ffffff;
            --secondary: #ea580c;
            --secondary-container: #f97316;
            --on-secondary-container: #ffffff;
            --surface: #f8fafc;
            --on-surface: #0f172a;
            --surface-variant: #e2e8f0;
            --on-surface-variant: #334155;
            --outline: #64748b;
            --outline-variant: #cbd5e1;
            --error: #dc2626;
            --error-container: #fef2f2;
            --on-error-container: #7f1d1d;
            --warning: #f59e0b;
            --success: #10b981;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--surface);
            color: var(--on-surface);
        }
        .font-display {
            font-family: 'Manrope', sans-serif;
        }
    </style>

    <style type="text/tailwindcss">
        @layer components {
            select {
                @apply w-full bg-surface px-4 py-3 rounded-xl border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all outline-none text-sm text-on-surface cursor-pointer appearance-none;
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2344474d' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e") !important;
                background-position: right 1rem center !important;
                background-repeat: no-repeat !important;
                background-size: 1.25em 1.25em !important;
                padding-right: 2.5rem !important;
            }
        }
    </style>

    <script id="tailwind-config">
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4338ca',
                        'on-primary': '#ffffff',
                        secondary: '#ea580c',
                        'secondary-container': '#f97316',
                        'on-secondary-container': '#ffffff',
                        surface: '#f8fafc',
                        'surface-container-lowest': '#ffffff',
                        'surface-container-low': '#f1f5f9',
                        'on-surface': '#0f172a',
                        'surface-variant': '#e2e8f0',
                        'on-surface-variant': '#334155',
                        outline: '#64748b',
                        'outline-variant': '#cbd5e1',
                        error: '#dc2626',
                        'error-container': '#fef2f2',
                        'on-error-container': '#7f1d1d',
                        warning: '#f59e0b',
                        success: '#10b981',
                    },
                    fontFamily: {
                        display: ['Manrope', 'sans-serif'],
                        body: ['Inter', 'sans-serif'],
                    },
                    maxWidth: {
                        container: '1280px',
                    },
                    spacing: {
                        'margin-mobile': '16px',
                        'margin-desktop': '40px',
                        gutter: '24px',
                    },
                },
            },
        }
    </script>
</head>
<body class="bg-surface text-on-surface font-body antialiased">

<!-- TOP NAVBAR -->
<header class="fixed top-4 left-0 right-0 w-full z-50 px-4 flex justify-center pointer-events-none">
    <div class="bg-white/90 backdrop-blur-xl shadow-[0_8px_30px_rgb(0,0,0,0.08)] border border-gray-100/50 rounded-full flex items-center justify-between px-4 h-16 w-full max-w-6xl pointer-events-auto transition-all">
        
        <!-- Logo -->
        <a href="<?php echo isset($_SESSION['user_id']) ? 'index.php?action=items' : 'index.php?action=landing'; ?>" class="flex items-center pl-2 pr-10 hover:opacity-80 transition-opacity">
            <img src="/LF-web2/assets/logo.png" alt="Foundly" class="h-14 md:h-16 w-auto object-contain scale-125 md:scale-150 origin-left">
        </a>
        
        <!-- Nav Links -->
        <nav class="hidden lg:flex items-center gap-1">
            <?php $current_action = $_GET['action'] ?? 'landing'; ?>
            
            <?php if(isset($_SESSION['user_id'])): ?>
                <?php
                $links = [
                    'dashboard' => ['icon' => 'home', 'label' => 'Dashboard'],
                    'items_create' => ['icon' => 'add_circle', 'label' => 'Post'],
                    // messages handled separately
                    'help' => ['icon' => 'help', 'label' => 'Help']
                ];
                
                foreach($links as $act => $data):
                    $isActive = ($current_action == $act);
                    $cls = $isActive ? 'text-primary bg-primary/10 font-bold' : 'text-gray-500 hover:text-primary hover:bg-gray-50 font-medium';
                ?>
                    <a href="index.php?action=<?php echo $act; ?>" class="flex items-center gap-1.5 px-4 py-2 rounded-full text-sm transition-all <?php echo $cls; ?>">
                        <span class="material-symbols-outlined text-[18px]"><?php echo $data['icon']; ?></span>
                        <?php echo $data['label']; ?>
                    </a>
                <?php endforeach; ?>
                
                <!-- Messages -->
                <?php 
                $isMsgActive = ($current_action == 'inbox' || $current_action == 'chat');
                $msgCls = $isMsgActive ? 'text-primary bg-primary/10 font-bold' : 'text-gray-500 hover:text-primary hover:bg-gray-50 font-medium';
                ?>
                <a href="index.php?action=inbox" class="flex items-center gap-1.5 px-4 py-2 rounded-full text-sm transition-all <?php echo $msgCls; ?>">
                    <span class="material-symbols-outlined text-[18px]">chat</span>
                    Messages
                    <?php if($unread_count > 0): ?>
                        <span class="bg-error text-white text-[10px] font-bold rounded-full px-1.5 py-0.5 ml-0.5"><?php echo $unread_count; ?></span>
                    <?php endif; ?>
                </a>

                <?php if(isset($_SESSION['role']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'staff')): ?>
                    <?php 
                    $isIssueActive = ($current_action == 'issue_reports');
                    $issueCls = $isIssueActive ? 'text-primary bg-primary/10 font-bold' : 'text-gray-500 hover:text-primary hover:bg-gray-50 font-medium';
                    ?>
                    <a href="index.php?action=issue_reports" class="flex items-center gap-1.5 px-4 py-2 rounded-full text-sm transition-all <?php echo $issueCls; ?>">
                        <span class="material-symbols-outlined text-[18px]">report</span>
                        Issues
                    </a>
                <?php endif; ?>
                
                <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                    <?php 
                    $isRepActive = ($current_action == 'reports' || $current_action == 'reports_print');
                    $repCls = $isRepActive ? 'text-primary bg-primary/10 font-bold' : 'text-gray-500 hover:text-primary hover:bg-gray-50 font-medium';
                    ?>
                    <a href="index.php?action=reports" class="flex items-center gap-1.5 px-4 py-2 rounded-full text-sm transition-all <?php echo $repCls; ?>">
                        <span class="material-symbols-outlined text-[18px]">bar_chart</span>
                        Reports
                    </a>
                    <?php 
                    $isAudActive = ($current_action == 'audit_logs');
                    $audCls = $isAudActive ? 'text-primary bg-primary/10 font-bold' : 'text-gray-500 hover:text-primary hover:bg-gray-50 font-medium';
                    ?>
                    <a href="index.php?action=audit_logs" class="flex items-center gap-1.5 px-4 py-2 rounded-full text-sm transition-all <?php echo $audCls; ?>">
                        <span class="material-symbols-outlined text-[18px]">history</span>
                        Audit
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        </nav>

        <!-- Right Side Icons -->
        <div class="flex items-center gap-1 pr-1">
            <?php if(isset($_SESSION['user_id'])): ?>
                
                <a href="index.php?action=landing" class="w-10 h-10 rounded-full flex items-center justify-center text-gray-500 hover:bg-gray-100 hover:text-primary transition-colors" title="Home">
                    <span class="material-symbols-outlined text-[22px]">explore</span>
                </a>

                <div class="relative group ml-1">
                    <button class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-full border border-gray-200 hover:border-primary/30 hover:bg-gray-50 transition-colors cursor-pointer">
                        <?php if(!empty($_SESSION['profile_picture'])): ?>
                            <img src="/LF-web2/uploads/profiles/<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>" alt="Profile" class="w-7 h-7 rounded-full object-cover">
                        <?php else: ?>
                            <span class="material-symbols-outlined text-[24px] text-gray-500">account_circle</span>
                        <?php endif; ?>
                        <span class="text-sm font-bold hidden md:block text-gray-700"><?php echo htmlspecialchars(substr($_SESSION['username'] ?? 'User', 0, 10)); ?></span>
                        <span class="material-symbols-outlined text-sm text-gray-400 hidden md:block">expand_more</span>
                    </button>
                    
                    <div class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.15)] border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 overflow-hidden py-2">
                        <a href="index.php?action=items" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[20px] text-gray-400">search</span>
                            Discovery Feed
                        </a>
                        <a href="index.php?action=dashboard" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[20px] text-gray-400">dashboard</span>
                            Dashboard
                        </a>
                        <a href="index.php?action=profile" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[20px] text-gray-400">manage_accounts</span>
                            Profile Management
                        </a>
                        <div class="h-px bg-gray-100 my-1"></div>
                        <a href="index.php?action=logout" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 transition-colors">
                            <span class="material-symbols-outlined text-[20px]">logout</span>
                            Logout
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <a href="index.php?action=login" 
                   class="px-5 py-2 bg-primary text-white rounded-full hover:bg-primary/90 hover:shadow-md hover:-translate-y-0.5 transition-all text-sm font-bold shadow-sm shadow-primary/20">
                    Login / Register
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>

<main class="pt-28 pb-24 md:pb-8 min-h-screen">