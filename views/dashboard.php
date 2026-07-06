<?php
/**
 * views/dashboard.php – User Dashboard
 * 
 * Shows summary statistics and quick action cards:
 * - Total users (admin only)
 * - Total lost/found items
 * - Total pending claims
 * - Recent user registrations
 * - Quick action buttons for admin
 */
include __DIR__ . '/../includes/header.php';
?>

<div class="max-w-container mx-auto px-margin-mobile md:px-margin-desktop py-8">
    <!-- Dashboard Header -->
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-4xl md:text-5xl font-extrabold text-primary mb-2 tracking-tight">Dashboard</h1>
            <p class="text-on-surface-variant font-body text-lg">
                Welcome back, <strong class="text-primary"><?php echo htmlspecialchars($_SESSION['username']); ?></strong>! 
                Here's what's happening today.
            </p>
        </div>
        <div class="hidden md:flex items-center gap-2 text-sm text-on-surface-variant font-medium bg-surface-variant/30 px-4 py-2 rounded-full border border-outline-variant/30">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <?php echo date('l, F j, Y'); ?>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 <?php echo ($_SESSION['role'] == 'admin') ? 'lg:grid-cols-6' : 'lg:grid-cols-5'; ?> gap-4 md:gap-6 mb-12">
        <!-- Total Users (Admin only) -->
        <?php if($_SESSION['role'] == 'admin'): ?>
            <div class="glass-card rounded-3xl p-5 card-shadow relative overflow-hidden group hover:-translate-y-1 hover:shadow-xl transition-all duration-300 border border-primary/20 bg-gradient-to-br from-primary/5 to-transparent">
                <div class="absolute -right-6 -top-6 w-28 h-28 bg-primary/10 rounded-full blur-2xl group-hover:bg-primary/20 transition-all duration-500"></div>
                <div class="flex items-center justify-between mb-3">
                    <div class="text-xs md:text-sm font-semibold text-on-surface-variant uppercase tracking-wider">Users</div>
                    <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary relative z-10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                </div>
                <div class="text-3xl md:text-5xl font-display font-extrabold text-primary relative z-10"><?php echo $totalUsers ?? 0; ?></div>
            </div>
        <?php endif; ?>

        <!-- Lost Items -->
        <div class="glass-card rounded-3xl p-5 card-shadow relative overflow-hidden group hover:-translate-y-1 hover:shadow-xl transition-all duration-300 border border-error/20 bg-gradient-to-br from-error/5 to-transparent">
            <div class="absolute -right-6 -top-6 w-28 h-28 bg-error/10 rounded-full blur-2xl group-hover:bg-error/20 transition-all duration-500"></div>
            <div class="flex items-center justify-between mb-3">
                <div class="text-xs md:text-sm font-semibold text-on-surface-variant uppercase tracking-wider">All Lost</div>
                <div class="w-8 h-8 rounded-full bg-error/10 flex items-center justify-center text-error relative z-10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v11m0 5l4-4m-4 4l-4-4"></path></svg>
                </div>
            </div>
            <div class="text-3xl md:text-5xl font-display font-extrabold text-error relative z-10"><?php echo $totalLost ?? 0; ?></div>
        </div>

        <!-- Found Items -->
        <div class="glass-card rounded-3xl p-5 card-shadow relative overflow-hidden group hover:-translate-y-1 hover:shadow-xl transition-all duration-300 border border-secondary/20 bg-gradient-to-br from-secondary/5 to-transparent">
            <div class="absolute -right-6 -top-6 w-28 h-28 bg-secondary/10 rounded-full blur-2xl group-hover:bg-secondary/20 transition-all duration-500"></div>
            <div class="flex items-center justify-between mb-3">
                <div class="text-xs md:text-sm font-semibold text-on-surface-variant uppercase tracking-wider">All Found</div>
                <div class="w-8 h-8 rounded-full bg-secondary/10 flex items-center justify-center text-secondary relative z-10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
            </div>
            <div class="text-3xl md:text-5xl font-display font-extrabold text-secondary relative z-10"><?php echo $totalFound ?? 0; ?></div>
        </div>

        <!-- My Reported Lost Items -->
        <div class="glass-card rounded-3xl p-5 card-shadow relative overflow-hidden group hover:-translate-y-1 hover:shadow-xl transition-all duration-300 border border-error/20 bg-gradient-to-br from-error/5 to-transparent">
            <div class="absolute -right-6 -top-6 w-28 h-28 bg-error/10 rounded-full blur-2xl group-hover:bg-error/20 transition-all duration-500"></div>
            <div class="flex items-center justify-between mb-3">
                <div class="text-xs md:text-sm font-semibold text-on-surface-variant uppercase tracking-wider">My Lost</div>
                <div class="w-8 h-8 rounded-full bg-error/10 flex items-center justify-center text-error relative z-10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
            </div>
            <div class="text-3xl md:text-5xl font-display font-extrabold text-error relative z-10"><?php echo $myLost ?? 0; ?></div>
        </div>

        <!-- My Reported Found Items -->
        <div class="glass-card rounded-3xl p-5 card-shadow relative overflow-hidden group hover:-translate-y-1 hover:shadow-xl transition-all duration-300 border border-secondary/20 bg-gradient-to-br from-secondary/5 to-transparent">
            <div class="absolute -right-6 -top-6 w-28 h-28 bg-secondary/10 rounded-full blur-2xl group-hover:bg-secondary/20 transition-all duration-500"></div>
            <div class="flex items-center justify-between mb-3">
                <div class="text-xs md:text-sm font-semibold text-on-surface-variant uppercase tracking-wider">My Found</div>
                <div class="w-8 h-8 rounded-full bg-secondary/10 flex items-center justify-center text-secondary relative z-10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
            </div>
            <div class="text-3xl md:text-5xl font-display font-extrabold text-secondary relative z-10"><?php echo $myFound ?? 0; ?></div>
        </div>

        <!-- Pending Claims -->
        <div class="glass-card rounded-3xl p-5 card-shadow relative overflow-hidden group hover:-translate-y-1 hover:shadow-xl transition-all duration-300 border border-warning/20 bg-gradient-to-br from-warning/5 to-transparent">
            <div class="absolute -right-6 -top-6 w-28 h-28 bg-warning/10 rounded-full blur-2xl group-hover:bg-warning/20 transition-all duration-500"></div>
            <div class="flex items-center justify-between mb-3">
                <?php if($_SESSION['role'] == 'admin'): ?>
                    <div class="text-xs md:text-sm font-semibold text-on-surface-variant uppercase tracking-wider">All Claims</div>
                <?php else: ?>
                    <div class="text-xs md:text-sm font-semibold text-on-surface-variant uppercase tracking-wider">Review Claims</div>
                <?php endif; ?>
                <div class="w-8 h-8 rounded-full bg-warning/10 flex items-center justify-center text-warning relative z-10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </div>
            </div>
            <?php if($_SESSION['role'] == 'admin'): ?>
                <div class="text-3xl md:text-5xl font-display font-extrabold text-warning relative z-10"><?php echo $totalPendingClaims ?? 0; ?></div>
            <?php else: ?>
                <div class="text-3xl md:text-5xl font-display font-extrabold text-warning relative z-10"><?php echo $myPendingClaims ?? 0; ?></div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Quick Actions Redesigned -->
    <div class="mb-12">
        <h2 class="font-display text-2xl font-bold mb-6 text-on-surface flex items-center gap-2">
            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            Quick Shortcuts
        </h2>
        
        <?php if($_SESSION['role'] == 'admin'): ?>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <a href="index.php?action=users" class="group flex flex-col items-center justify-center p-6 glass-card rounded-3xl hover:-translate-y-1 hover:shadow-xl hover:border-primary/50 transition-all duration-300">
                    <div class="w-14 h-14 bg-surface-variant/50 text-primary rounded-full flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-primary/10 transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <span class="font-semibold text-sm text-center text-on-surface">Manage Users</span>
                </a>
                <a href="index.php?action=categories" class="group flex flex-col items-center justify-center p-6 glass-card rounded-3xl hover:-translate-y-1 hover:shadow-xl hover:border-primary/50 transition-all duration-300">
                    <div class="w-14 h-14 bg-surface-variant/50 text-secondary rounded-full flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-secondary/10 transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    </div>
                    <span class="font-semibold text-sm text-center text-on-surface">Categories</span>
                </a>
                <a href="index.php?action=items" class="group flex flex-col items-center justify-center p-6 glass-card rounded-3xl hover:-translate-y-1 hover:shadow-xl hover:border-primary/50 transition-all duration-300">
                    <div class="w-14 h-14 bg-surface-variant/50 text-primary rounded-full flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-primary/10 transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <span class="font-semibold text-sm text-center text-on-surface">All Items</span>
                </a>
                <a href="index.php?action=claims" class="group flex flex-col items-center justify-center p-6 glass-card rounded-3xl hover:-translate-y-1 hover:shadow-xl hover:border-primary/50 transition-all duration-300">
                    <div class="w-14 h-14 bg-surface-variant/50 text-warning rounded-full flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-warning/10 transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="font-semibold text-sm text-center text-on-surface">All Claims</span>
                </a>
                <a href="index.php?action=reports" class="group flex flex-col items-center justify-center p-6 glass-card rounded-3xl hover:-translate-y-1 hover:shadow-xl hover:border-primary/50 transition-all duration-300">
                    <div class="w-14 h-14 bg-surface-variant/50 text-secondary rounded-full flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-secondary/10 transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <span class="font-semibold text-sm text-center text-on-surface">Reports</span>
                </a>
                <a href="index.php?action=audit_logs" class="group flex flex-col items-center justify-center p-6 glass-card rounded-3xl hover:-translate-y-1 hover:shadow-xl hover:border-primary/50 transition-all duration-300">
                    <div class="w-14 h-14 bg-surface-variant/50 text-error rounded-full flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-error/10 transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="font-semibold text-sm text-center text-on-surface">Audit Logs</span>
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <a href="index.php?action=items_create" class="group flex flex-col items-center justify-center p-6 glass-card rounded-3xl hover:-translate-y-1 hover:shadow-xl hover:border-primary/50 transition-all duration-300">
                    <div class="w-14 h-14 bg-surface-variant/50 text-primary rounded-full flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-primary/10 transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <span class="font-semibold text-sm text-center text-on-surface">Post Item</span>
                </a>
                <a href="index.php?action=items" class="group flex flex-col items-center justify-center p-6 glass-card rounded-3xl hover:-translate-y-1 hover:shadow-xl hover:border-primary/50 transition-all duration-300">
                    <div class="w-14 h-14 bg-surface-variant/50 text-secondary rounded-full flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-secondary/10 transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <span class="font-semibold text-sm text-center text-on-surface">Browse Items</span>
                </a>
                <a href="index.php?action=my_claims" class="group flex flex-col items-center justify-center p-6 glass-card rounded-3xl hover:-translate-y-1 hover:shadow-xl hover:border-primary/50 transition-all duration-300">
                    <div class="w-14 h-14 bg-surface-variant/50 text-warning rounded-full flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-warning/10 transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="font-semibold text-sm text-center text-on-surface">Review Claims</span>
                </a>
                <a href="index.php?action=inbox" class="group flex flex-col items-center justify-center p-6 glass-card rounded-3xl hover:-translate-y-1 hover:shadow-xl hover:border-primary/50 transition-all duration-300">
                    <div class="w-14 h-14 bg-surface-variant/50 text-error rounded-full flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-error/10 transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    </div>
                    <span class="font-semibold text-sm text-center text-on-surface">Inbox</span>
                </a>
                <a href="index.php?action=profile" class="group flex flex-col items-center justify-center p-6 glass-card rounded-3xl hover:-translate-y-1 hover:shadow-xl hover:border-primary/50 transition-all duration-300">
                    <div class="w-14 h-14 bg-surface-variant/50 text-primary rounded-full flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-primary/10 transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <span class="font-semibold text-sm text-center text-on-surface">Profile</span>
                </a>
                <a href="index.php?action=help" class="group flex flex-col items-center justify-center p-6 glass-card rounded-3xl hover:-translate-y-1 hover:shadow-xl hover:border-primary/50 transition-all duration-300">
                    <div class="w-14 h-14 bg-surface-variant/50 text-secondary rounded-full flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-secondary/10 transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="font-semibold text-sm text-center text-on-surface">Help & Support</span>
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Recent Activity (Admin only) -->
    <?php if($_SESSION['role'] == 'admin'): ?>
    <div class="glass-card rounded-3xl p-6 md:p-8 card-shadow mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="font-display text-2xl font-bold flex items-center gap-2">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Recent Registrations
            </h2>
            <a href="index.php?action=users" class="text-primary font-medium hover:underline text-sm">View All</a>
        </div>
        
        <?php if(isset($recentUsers) && mysqli_num_rows($recentUsers) > 0): ?>
            <div class="overflow-hidden rounded-2xl border border-outline-variant/30">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-surface-variant/30 text-on-surface-variant text-sm uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Username</th>
                                <th class="px-6 py-4 font-semibold">Email</th>
                                <th class="px-6 py-4 font-semibold">Role</th>
                                <th class="px-6 py-4 font-semibold">Registered</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/20">
                            <?php while($user = mysqli_fetch_assoc($recentUsers)): ?>
                                <tr class="hover:bg-surface-variant/20 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs uppercase">
                                                <?php echo substr(htmlspecialchars($user['username']), 0, 1); ?>
                                            </div>
                                            <span class="font-medium text-on-surface"><?php echo htmlspecialchars($user['username']); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-on-surface-variant"><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold tracking-wide <?php echo ($user['role'] == 'admin') ? 'bg-primary/10 text-primary border border-primary/20' : 'bg-surface-variant text-on-surface-variant'; ?>">
                                            <?php echo ucfirst($user['role']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-on-surface-variant">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <?php echo date('M j, Y g:i A', strtotime($user['created_at'])); ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="p-8 text-center bg-surface-variant/20 rounded-2xl border border-outline-variant/30">
                <svg class="w-12 h-12 text-on-surface-variant mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <p class="text-on-surface-variant text-sm font-medium">No recent user registrations.</p>
            </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
