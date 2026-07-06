<?php
/**
 * views/users/index.php – User Management Page (Admin only)
 * 
 * Displays all users with:
 * - Statistics cards (total users, active, banned, etc.)
 * - Search functionality
 * - User table with avatar, name, email, role, status
 * - Role and status management (Edit, Delete, Reset Password)
 * - Pagination
 * - Professional glass-morphism design
 */
include __DIR__ . '/../../includes/header.php';
?>

<style>
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
.user-row:hover {
    background-color: rgba(0, 0, 0, 0.02);
}
.status-badge {
    padding: 4px 12px;
    border-radius: 9999px;
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.status-active {
    background: #d1fae5;
    color: #065f46;
}
.status-disabled {
    background: #fee2e2;
    color: #991b1b;
}
.role-badge {
    padding: 2px 10px;
    border-radius: 9999px;
    font-size: 0.6rem;
    font-weight: 600;
    text-transform: uppercase;
}
.role-admin {
    background: #dbeafe;
    color: #1e40af;
}
.role-staff {
    background: #fef3c7;
    color: #92400e;
}
.role-user {
    background: #e5e7eb;
    color: #374151;
}
</style>

<div class="max-w-container mx-auto px-margin-mobile md:px-margin-desktop">
    <!-- Header Section -->
    <header class="mb-stack-lg flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-3xl md:text-5xl font-extrabold text-primary mb-2">User Management</h1>
            <p class="text-on-surface-variant font-body-lg">Oversee the Foundly community and maintain platform trust.</p>
        </div>
        <div class="flex gap-3">
            <a href="index.php?action=users_create" class="bg-primary text-on-primary px-6 py-3 rounded-xl font-body-md font-semibold hover:opacity-90 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined">person_add</span>
                Add User
            </a>
        </div>
    </header>

    <!-- Stats Grid (Bento Style) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-gutter mb-stack-lg">
        <div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border border-outline-variant/10">
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-1 uppercase">Total Users</p>
            <p class="text-[32px] font-bold text-primary"><?php echo $userStats['total'] ?? 0; ?></p>
        </div>
        <div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border border-outline-variant/10">
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-1 uppercase">Active Users</p>
            <p class="text-[32px] font-bold text-success"><?php echo $userStats['active'] ?? 0; ?></p>
        </div>
        <div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border border-outline-variant/10">
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-1 uppercase">Disabled Users</p>
            <p class="text-[32px] font-bold text-error"><?php echo $userStats['disabled'] ?? 0; ?></p>
        </div>
        <div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border border-outline-variant/10">
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-1 uppercase">Administrators</p>
            <p class="text-[32px] font-bold text-primary"><?php echo $userStats['admin'] ?? 0; ?></p>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant/10 p-4 mb-stack-lg">
        <form method="GET" action="index.php" class="flex flex-col md:flex-row gap-3">
            <input type="hidden" name="action" value="users">
            <div class="flex-1 relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
                <input type="text" 
                       name="search" 
                       placeholder="Search by username, email, or ID number..." 
                       value="<?php echo htmlspecialchars($search ?? ''); ?>"
                       class="w-full pl-10 pr-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none">
            </div>
            <button type="submit" class="px-6 py-3 bg-primary text-on-primary rounded-xl font-body-md font-semibold hover:opacity-90 transition-all">
                Search
            </button>
            <?php if(!empty($search)): ?>
                <a href="index.php?action=users" class="px-6 py-3 bg-surface-variant text-on-surface-variant rounded-xl font-body-md font-semibold hover:bg-outline-variant transition-all">
                    Clear
                </a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Flash Messages -->
    <?php if(isset($_SESSION['success'])): ?>
        <div class="bg-success/10 text-success p-4 rounded-xl mb-4">✅ <?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if(isset($_SESSION['error'])): ?>
        <div class="bg-error-container text-on-error-container p-4 rounded-xl mb-4">❌ <?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <!-- Main User Table -->
    <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant/10 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-outline-variant/20 bg-surface-container-low">
                        <th class="px-6 py-4 font-label-caps text-label-caps text-on-surface-variant uppercase">User Details</th>
                        <th class="px-6 py-4 font-label-caps text-label-caps text-on-surface-variant uppercase">ID Type</th>
                        <th class="px-6 py-4 font-label-caps text-label-caps text-on-surface-variant uppercase">Role</th>
                        <th class="px-6 py-4 font-label-caps text-label-caps text-on-surface-variant uppercase">Status</th>
                        <th class="px-6 py-4 font-label-caps text-label-caps text-on-surface-variant uppercase text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    <?php if(mysqli_num_rows($users) == 0): ?>
                        <tr>
                            <td colspan="5" class="text-center py-8 text-on-surface-variant">
                                No users found. <?php if(!empty($search)): ?>Try a different search.<?php endif; ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php while($user = mysqli_fetch_assoc($users)): ?>
                            <tr class="user-row transition-colors group cursor-pointer" data-user-id="<?php echo $user['id']; ?>">
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <!-- Avatar (first letter) -->
                                        <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-sm flex-shrink-0">
                                            <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <p class="font-headline-md text-[16px] text-primary"><?php echo htmlspecialchars($user['username']); ?></p>
                                            <p class="text-on-surface-variant text-sm"><?php echo htmlspecialchars($user['email']); ?></p>
                                            <p class="text-on-surface-variant text-xs">ID: <?php echo htmlspecialchars($user['id_number']); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="text-sm text-on-surface-variant"><?php echo str_replace('_', ' ', $user['id_type']); ?></span>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="role-badge role-<?php echo $user['role']; ?>">
                                        <?php echo ucfirst($user['role']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="status-badge status-<?php echo $user['status']; ?>">
                                        <?php echo ucfirst($user['status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <?php if($user['id'] != $_SESSION['user_id']): ?>
                                            <a href="index.php?action=users_edit&id=<?php echo $user['id']; ?>" 
                                               class="text-primary hover:bg-surface-variant p-2 rounded-lg transition-colors" 
                                               title="Edit User">
                                                <span class="material-symbols-outlined text-[20px]">edit</span>
                                            </a>
                                            <a href="index.php?action=users_delete&id=<?php echo $user['id']; ?>" 
                                               class="text-error hover:bg-error-container/20 p-2 rounded-lg transition-colors delete-confirm" 
                                               title="Delete User"
                                               onclick="return confirm('Are you sure you want to delete this user?')">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </a>
                                            <button onclick="openResetModal(<?php echo $user['id']; ?>)" 
                                                    class="text-secondary hover:bg-secondary-container/20 p-2 rounded-lg transition-colors" 
                                                    title="Reset Password">
                                                <span class="material-symbols-outlined text-[20px]">key</span>
                                            </button>
                                        <?php else: ?>
                                            <span class="text-on-surface-variant/50 text-xs">(You)</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if($totalPages > 1): ?>
        <div class="px-6 py-4 flex flex-col md:flex-row items-center justify-between border-t border-outline-variant/20 bg-surface-container-low/30 gap-4">
            <p class="text-sm text-on-surface-variant">
                Showing page <?php echo $page; ?> of <?php echo $totalPages; ?> (<?php echo $total; ?> users)
            </p>
            <div class="flex gap-2">
                <a href="index.php?action=users&page=<?php echo max(1, $page-1); ?>&search=<?php echo urlencode($search); ?>" 
                   class="w-10 h-10 flex items-center justify-center rounded-lg border border-outline-variant/50 hover:bg-surface-variant transition-colors <?php echo $page <= 1 ? 'opacity-30 pointer-events-none' : ''; ?>">
                    <span class="material-symbols-outlined">chevron_left</span>
                </a>
                <?php for($i = max(1, $page-2); $i <= min($totalPages, $page+2); $i++): ?>
                    <a href="index.php?action=users&page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>" 
                       class="w-10 h-10 flex items-center justify-center rounded-lg border border-outline-variant/50 hover:bg-surface-variant transition-colors <?php echo $i == $page ? 'bg-primary text-on-primary border-primary' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                <a href="index.php?action=users&page=<?php echo min($totalPages, $page+1); ?>&search=<?php echo urlencode($search); ?>" 
                   class="w-10 h-10 flex items-center justify-center rounded-lg border border-outline-variant/50 hover:bg-surface-variant transition-colors <?php echo $page >= $totalPages ? 'opacity-30 pointer-events-none' : ''; ?>">
                    <span class="material-symbols-outlined">chevron_right</span>
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- ===================================================== -->
<!-- PASSWORD RESET MODAL                                    -->
<!-- ===================================================== -->
<div id="resetModal" class="fixed inset-0 bg-black/50 z-[100] hidden items-center justify-center" style="display: none;">
    <div class="bg-surface-container-lowest rounded-2xl p-6 max-w-md w-full mx-4 shadow-2xl">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-display text-headline-md text-primary">Reset Password</h3>
            <button onclick="closeResetModal()" class="p-2 hover:bg-surface-variant rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="index.php?action=users_reset_password">
            <input type="hidden" name="id" id="reset_user_id">
            <div class="form-group mb-4">
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">New Password</label>
                <input type="password" name="new_password" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none" required minlength="6">
                <p class="text-xs text-on-surface-variant mt-1">Minimum 6 characters</p>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-primary text-on-primary py-3 rounded-xl font-medium hover:bg-primary/90 transition-all">Reset Password</button>
                <button type="button" onclick="closeResetModal()" class="flex-1 bg-surface-variant text-on-surface-variant py-3 rounded-xl font-medium hover:bg-outline-variant transition-all">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
/**
 * Open password reset modal
 */
function openResetModal(userId) {
    document.getElementById('reset_user_id').value = userId;
    const modal = document.getElementById('resetModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

/**
 * Close password reset modal
 */
function closeResetModal() {
    const modal = document.getElementById('resetModal');
    modal.style.display = 'none';
    document.body.style.overflow = '';
}

/**
 * Close modal when clicking outside
 */
document.getElementById('resetModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeResetModal();
    }
});

/**
 * Keyboard shortcut: ESC to close modal
 */
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeResetModal();
    }
});

/**
 * Confirm delete with custom message
 */
document.querySelectorAll('.delete-confirm').forEach(link => {
    link.addEventListener('click', function(e) {
        if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
            e.preventDefault();
        }
    });
});
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
