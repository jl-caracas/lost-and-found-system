<?php
/**
 * views/users/edit.php – Edit User Form (Admin only)
 * 
 * Form to edit an existing user:
 * - Username is disabled (cannot be changed)
 * - Email is disabled (cannot be changed)
 * - Role selection (admin, staff, user)
 * - Status selection (active, disabled)
 * - Professional glass-morphism design
 */
include __DIR__ . '/../../includes/header.php';
?>

<div class="max-w-2xl mx-auto px-margin-mobile md:px-margin-desktop py-8">
    <button type="button" onclick="history.back()" class="flex items-center gap-2 text-on-surface-variant hover:text-primary transition-colors font-medium text-sm mb-6 group w-fit">
        <span class="material-symbols-outlined text-xl group-hover:-translate-x-1 transition-transform">arrow_back</span>
        Back
    </button>
    <div class="bg-surface-container-lowest rounded-2xl p-6 md:p-8 shadow-sm border border-outline-variant/10">
        <div class="flex items-center gap-3 mb-6">
            <a href="index.php?action=users" class="text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <h2 class="font-display text-headline-md text-primary">Edit User</h2>
        </div>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="bg-error-container text-on-error-container p-4 rounded-xl mb-4">❌ <?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?action=users_update" class="space-y-5">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
            
            <!-- Username (disabled) -->
            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">Username</label>
                <input type="text" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 cursor-not-allowed opacity-75" 
                       value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
            </div>

            <!-- Email (disabled) -->
            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">Email</label>
                <input type="text" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 cursor-not-allowed opacity-75" 
                       value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
            </div>

            <!-- Role -->
            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">Role</label>
                <select name="role" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none">
                    <option value="user" <?php echo ($user['role'] == 'user') ? 'selected' : ''; ?>>User</option>
                    <option value="staff" <?php echo ($user['role'] == 'staff') ? 'selected' : ''; ?>>Staff</option>
                    <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">Status</label>
                <select name="status" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none">
                    <option value="active" <?php echo ($user['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                    <option value="disabled" <?php echo ($user['status'] == 'disabled') ? 'selected' : ''; ?>>Disabled</option>
                </select>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-3 bg-primary text-on-primary rounded-xl font-medium hover:bg-primary/90 transition-all active:scale-[0.98]">
                    Update User
                </button>
                <a href="index.php?action=users" class="px-6 py-3 bg-surface-variant text-on-surface-variant rounded-xl font-medium hover:bg-outline-variant transition-all">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
