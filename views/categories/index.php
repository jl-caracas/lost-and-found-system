<?php
/**
 * views/categories/index.php – Categories Management Page (Admin only)
 * 
 * Displays all categories with:
 * - Statistics cards (total categories, total items)
 * - Search functionality
 * - Category table with name, description, item count
 * - Edit and Delete actions
 * - Pagination
 * - Professional glass-morphism design
 */
include __DIR__ . '/../../includes/header.php';
?>

<style>
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
.category-row:hover {
    background-color: rgba(0, 0, 0, 0.02);
}
.item-count-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 28px;
    padding: 2px 10px;
    border-radius: 9999px;
    font-size: 0.7rem;
    font-weight: 600;
}
.count-low {
    background: #d1fae5;
    color: #065f46;
}
.count-medium {
    background: #fef3c7;
    color: #92400e;
}
.count-high {
    background: #fee2e2;
    color: #991b1b;
}
</style>

<div class="max-w-container mx-auto px-margin-mobile md:px-margin-desktop">
    <!-- Header Section -->
    <header class="mb-stack-lg flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-3xl md:text-5xl font-extrabold text-primary mb-2">Category Management</h1>
            <p class="text-on-surface-variant font-body-lg">Organize items by categories for better discovery and filtering.</p>
        </div>
        <div class="flex gap-3">
            <a href="index.php?action=categories_create" class="bg-primary text-on-primary px-6 py-3 rounded-xl font-body-md font-semibold hover:opacity-90 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined">add</span>
                Add Category
            </a>
        </div>
    </header>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-gutter mb-stack-lg">
        <div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border border-outline-variant/10">
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-1 uppercase">Total Categories</p>
            <p class="text-[32px] font-bold text-primary"><?php echo $total_categories ?? 0; ?></p>
        </div>
        <div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border border-outline-variant/10">
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-1 uppercase">Total Items</p>
            <p class="text-[32px] font-bold text-secondary"><?php echo $total_items ?? 0; ?></p>
        </div>
        <div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border border-outline-variant/10">
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-1 uppercase">Avg. Items per Category</p>
            <p class="text-[32px] font-bold text-primary">
                <?php 
                $avg = ($total_categories ?? 0) > 0 ? round(($total_items ?? 0) / ($total_categories ?? 1), 1) : 0;
                echo $avg;
                ?>
            </p>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant/10 p-4 mb-stack-lg">
        <form method="GET" action="index.php" class="flex flex-col md:flex-row gap-3">
            <input type="hidden" name="action" value="categories">
            <div class="flex-1 relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
                <input type="text" 
                       name="search" 
                       placeholder="Search categories by name or description..." 
                       value="<?php echo htmlspecialchars($search ?? ''); ?>"
                       class="w-full pl-10 pr-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none">
            </div>
            <button type="submit" class="px-6 py-3 bg-primary text-on-primary rounded-xl font-body-md font-semibold hover:opacity-90 transition-all">
                Search
            </button>
            <?php if(!empty($search)): ?>
                <a href="index.php?action=categories" class="px-6 py-3 bg-surface-variant text-on-surface-variant rounded-xl font-body-md font-semibold hover:bg-outline-variant transition-all">
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

    <!-- Categories Table -->
    <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant/10 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-outline-variant/20 bg-surface-container-low">
                        <th class="px-6 py-4 font-label-caps text-label-caps text-on-surface-variant uppercase">ID</th>
                        <th class="px-6 py-4 font-label-caps text-label-caps text-on-surface-variant uppercase">Category Name</th>
                        <th class="px-6 py-4 font-label-caps text-label-caps text-on-surface-variant uppercase">Description</th>
                        <th class="px-6 py-4 font-label-caps text-label-caps text-on-surface-variant uppercase text-center">Items</th>
                        <th class="px-6 py-4 font-label-caps text-label-caps text-on-surface-variant uppercase text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    <?php if(mysqli_num_rows($categories) == 0): ?>
                        <tr>
                            <td colspan="5" class="text-center py-8 text-on-surface-variant">
                                No categories found. <?php if(!empty($search)): ?>Try a different search.<?php endif; ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php while($cat = mysqli_fetch_assoc($categories)): 
                            // Get item count from pre-fetched array
                            $item_count = $item_counts[$cat['id']] ?? 0;
                            
                            // Determine badge color based on count
                            $badge_class = 'count-low';
                            if($item_count > 5) $badge_class = 'count-medium';
                            if($item_count > 15) $badge_class = 'count-high';
                        ?>
                            <tr class="category-row transition-colors group">
                                <td class="px-6 py-5 text-sm text-on-surface-variant"><?php echo $cat['id']; ?></td>
                                <td class="px-6 py-5">
                                    <span class="font-medium text-primary"><?php echo htmlspecialchars($cat['name']); ?></span>
                                </td>
                                <td class="px-6 py-5 text-sm text-on-surface-variant">
                                    <?php echo htmlspecialchars($cat['description'] ?? '—'); ?>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="item-count-badge <?php echo $badge_class; ?>">
                                        <?php echo $item_count; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="index.php?action=categories_edit&id=<?php echo $cat['id']; ?>" 
                                           class="text-primary hover:bg-surface-variant p-2 rounded-lg transition-colors" 
                                           title="Edit Category">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                        <a href="index.php?action=categories_delete&id=<?php echo $cat['id']; ?>" 
                                           class="text-error hover:bg-error-container/20 p-2 rounded-lg transition-colors delete-confirm" 
                                           title="Delete Category"
                                           onclick="return confirm('Delete this category? It will only be removed if no items are linked to it.')">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </a>
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
                Showing page <?php echo $page; ?> of <?php echo $totalPages; ?> (<?php echo $total; ?> categories)
            </p>
            <div class="flex gap-2">
                <a href="index.php?action=categories&page=<?php echo max(1, $page-1); ?>&search=<?php echo urlencode($search); ?>" 
                   class="w-10 h-10 flex items-center justify-center rounded-lg border border-outline-variant/50 hover:bg-surface-variant transition-colors <?php echo $page <= 1 ? 'opacity-30 pointer-events-none' : ''; ?>">
                    <span class="material-symbols-outlined">chevron_left</span>
                </a>
                <?php for($i = max(1, $page-2); $i <= min($totalPages, $page+2); $i++): ?>
                    <a href="index.php?action=categories&page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>" 
                       class="w-10 h-10 flex items-center justify-center rounded-lg border border-outline-variant/50 hover:bg-surface-variant transition-colors <?php echo $i == $page ? 'bg-primary text-on-primary border-primary' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                <a href="index.php?action=categories&page=<?php echo min($totalPages, $page+1); ?>&search=<?php echo urlencode($search); ?>" 
                   class="w-10 h-10 flex items-center justify-center rounded-lg border border-outline-variant/50 hover:bg-surface-variant transition-colors <?php echo $page >= $totalPages ? 'opacity-30 pointer-events-none' : ''; ?>">
                    <span class="material-symbols-outlined">chevron_right</span>
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
/**
 * Confirm delete with custom message
 */
document.querySelectorAll('.delete-confirm').forEach(link => {
    link.addEventListener('click', function(e) {
        if (!confirm('Delete this category? It will only be removed if no items are linked to it.')) {
            e.preventDefault();
        }
    });
});
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
