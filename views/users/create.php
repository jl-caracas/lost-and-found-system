<?php
/**
 * views/users/create.php – Add User Form (Admin only)
 * 
 * Form to create a new user with:
 * - First Name, Last Name, Birthdate, Username, ID Type, ID Number, Email, Password
 * - Role selection (admin, staff, user)
 * - Status selection (active, disabled)
 * - Sticky form values on validation errors
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
            <h2 class="font-display text-headline-md text-primary">Add New User</h2>
        </div>

        <?php if(!empty($errors)): ?>
            <div class="bg-error-container text-on-error-container p-4 rounded-xl mb-6 text-sm space-y-1">
                <?php foreach($errors as $err): ?>
                    <p>❌ <?php echo $err; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?action=users_store" class="space-y-5">
            <!-- First Name -->
            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">First Name *</label>
                <input type="text" name="first_name" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none" 
                       value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>" required>
            </div>

            <!-- Last Name -->
            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">Last Name *</label>
                <input type="text" name="last_name" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none" 
                       value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>" required>
            </div>

            <!-- Birthdate -->
            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">Birthdate *</label>
                <input type="date" name="birthdate" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none" 
                       value="<?php echo htmlspecialchars($_POST['birthdate'] ?? ''); ?>" required>
            </div>

            <!-- Username -->
            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">Username *</label>
                <input type="text" name="username" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none" 
                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
            </div>

            <!-- ID Type -->
            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">ID Type *</label>
                <select name="id_type" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none" required>
                    <option value="pup_id" <?php echo (($_POST['id_type'] ?? '') == 'pup_id') ? 'selected' : ''; ?>>PUP ID</option>
                    <option value="national_id" <?php echo (($_POST['id_type'] ?? '') == 'national_id') ? 'selected' : ''; ?>>National ID</option>
                    <option value="faculty_id" <?php echo (($_POST['id_type'] ?? '') == 'faculty_id') ? 'selected' : ''; ?>>Faculty ID</option>
                    <option value="other" <?php echo (($_POST['id_type'] ?? '') == 'other') ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>

            <!-- ID Number -->
            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">ID Number *</label>
                <input type="text" name="id_number" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none" 
                       value="<?php echo htmlspecialchars($_POST['id_number'] ?? ''); ?>" required>
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">Email *</label>
                <input type="email" name="email" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none" 
                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">Password * (min 6 characters)</label>
                <input type="password" name="password" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none" required minlength="6">
            </div>

            <!-- Role -->
            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">Role</label>
                <select name="role" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none">
                    <option value="user" <?php echo (($_POST['role'] ?? '') == 'user') ? 'selected' : ''; ?>>User</option>
                    <option value="staff" <?php echo (($_POST['role'] ?? '') == 'staff') ? 'selected' : ''; ?>>Staff</option>
                    <option value="admin" <?php echo (($_POST['role'] ?? '') == 'admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">Status</label>
                <select name="status" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none">
                    <option value="active" <?php echo (($_POST['status'] ?? '') == 'active') ? 'selected' : ''; ?>>Active</option>
                    <option value="disabled" <?php echo (($_POST['status'] ?? '') == 'disabled') ? 'selected' : ''; ?>>Disabled</option>
                </select>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-3 bg-primary text-on-primary rounded-xl font-medium hover:bg-primary/90 transition-all active:scale-[0.98]">
                    Create User
                </button>
                <a href="index.php?action=users" class="px-6 py-3 bg-surface-variant text-on-surface-variant rounded-xl font-medium hover:bg-outline-variant transition-all">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
