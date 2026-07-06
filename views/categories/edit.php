<?php
/**
 * views/categories/edit.php – Edit Category Form (Admin only)
 * 
 * Form to edit an existing category with:
 * - Category name (required)
 * - Description (optional)
 * - Pre-filled with current values
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
            <a href="index.php?action=categories" class="text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <h2 class="font-display text-headline-md text-primary">Edit Category</h2>
        </div>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="bg-error-container text-on-error-container p-4 rounded-xl mb-4">❌ <?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?action=categories_update" class="space-y-5">
            <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
            
            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">Category Name *</label>
                <input type="text" name="name" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none" 
                       value="<?php echo htmlspecialchars($category['name']); ?>" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">Description</label>
                <textarea name="description" rows="4" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none"><?php echo htmlspecialchars($category['description']); ?></textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-3 bg-primary text-on-primary rounded-xl font-medium hover:bg-primary/90 transition-all active:scale-[0.98]">
                    Update Category
                </button>
                <a href="index.php?action=categories" class="px-6 py-3 bg-surface-variant text-on-surface-variant rounded-xl font-medium hover:bg-outline-variant transition-all">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
