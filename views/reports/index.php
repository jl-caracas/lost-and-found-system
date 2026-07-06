<?php
/**
 * views/reports/index.php – Filterable Reports View
 */
include __DIR__ . '/../../includes/header.php';
?>

<div class="max-w-container mx-auto px-margin-mobile md:px-margin-desktop py-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="font-display text-3xl md:text-4xl font-extrabold text-primary mb-2">Item Reports</h1>
            <p class="text-on-surface-variant font-body">Generate filterable reports for lost and found items.</p>
        </div>
        <div>
            <a href="index.php?action=reports_print&status=<?php echo urlencode($status); ?>&category_id=<?php echo $category_id; ?>&start_date=<?php echo urlencode($start_date); ?>&end_date=<?php echo urlencode($end_date); ?>" 
               target="_blank"
               class="px-5 py-2.5 bg-primary text-on-primary rounded-xl font-bold flex items-center gap-2 hover:bg-primary/90 transition-all">
                <span class="material-symbols-outlined">print</span> Print Report
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass-card rounded-2xl p-6 mb-8 card-shadow">
        <form method="GET" action="index.php" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <input type="hidden" name="action" value="reports">
            
            <div class="flex flex-col gap-1.5">
                <label class="font-label text-on-surface">Status</label>
                <select name="status" class="w-full bg-surface-container rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-primary border border-outline-variant/30 text-on-surface">
                    <option value="">All Statuses</option>
                    <option value="lost" <?php echo $status == 'lost' ? 'selected' : ''; ?>>Lost</option>
                    <option value="found" <?php echo $status == 'found' ? 'selected' : ''; ?>>Found</option>
                    <option value="claimed" <?php echo $status == 'claimed' ? 'selected' : ''; ?>>Claimed</option>
                </select>
            </div>
            
            <div class="flex flex-col gap-1.5">
                <label class="font-label text-on-surface">Category</label>
                <select name="category_id" class="w-full bg-surface-container rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-primary border border-outline-variant/30 text-on-surface">
                    <option value="0">All Categories</option>
                    <?php 
                    if(isset($categories)) {
                        mysqli_data_seek($categories, 0);
                        while($cat = mysqli_fetch_assoc($categories)) {
                            $selected = ($category_id == $cat['id']) ? 'selected' : '';
                            echo "<option value=\"{$cat['id']}\" $selected>" . htmlspecialchars($cat['name']) . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            
            <div class="flex flex-col gap-1.5">
                <label class="font-label text-on-surface">Start Date</label>
                <input type="date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>" 
                       class="w-full bg-surface-container rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-primary border border-outline-variant/30 text-on-surface">
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="font-label text-on-surface">End Date</label>
                <input type="date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>" 
                       class="w-full bg-surface-container rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-primary border border-outline-variant/30 text-on-surface">
            </div>
            
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-primary text-on-primary rounded-xl px-4 py-3 font-bold hover:bg-primary/90 transition-all">
                    Apply
                </button>
                <a href="index.php?action=reports" class="bg-surface-variant text-on-surface-variant rounded-xl px-4 py-3 font-bold hover:bg-outline-variant transition-all flex items-center justify-center">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Results Table -->
    <div class="glass-card rounded-2xl p-6 card-shadow overflow-x-auto">
        <?php if(mysqli_num_rows($items) > 0): ?>
            <table class="w-full border-collapse">
                <thead>
                    <tr class="border-b border-outline-variant/30">
                        <th class="text-left py-3 px-4 font-label-caps text-on-surface-variant">Item ID</th>
                        <th class="text-left py-3 px-4 font-label-caps text-on-surface-variant">Name</th>
                        <th class="text-left py-3 px-4 font-label-caps text-on-surface-variant">Category</th>
                        <th class="text-left py-3 px-4 font-label-caps text-on-surface-variant">Status</th>
                        <th class="text-left py-3 px-4 font-label-caps text-on-surface-variant">Label</th>
                        <th class="text-left py-3 px-4 font-label-caps text-on-surface-variant">Location</th>
                        <th class="text-left py-3 px-4 font-label-caps text-on-surface-variant">Date Reported</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($items)): ?>
                        <tr class="border-b border-outline-variant/10 hover:bg-surface-container-lowest transition-colors">
                            <td class="py-3 px-4 text-sm">#<?php echo $row['id']; ?></td>
                            <td class="py-3 px-4 font-medium"><?php echo htmlspecialchars($row['item_name']); ?></td>
                            <td class="py-3 px-4 text-sm text-on-surface-variant"><?php echo htmlspecialchars($row['category_name']); ?></td>
                            <td class="py-3 px-4">
                                <?php
                                    if ($row['status'] == 'lost') {
                                        $badgeClass = 'bg-red-100 text-red-700';
                                    } elseif ($row['status'] == 'found') {
                                        $badgeClass = 'bg-orange-100 text-orange-700';
                                    } else { // claimed
                                        $badgeClass = 'bg-green-100 text-green-700';
                                    }
                                ?>
                                <span class="px-2 py-1 rounded-full text-xs font-bold <?php echo $badgeClass; ?>">
                                    <?php echo strtoupper($row['status']); ?>
                                </span>
                            </td>
                            <td class="py-3 px-4 text-sm text-on-surface-variant"><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $row['status_label']))); ?></td>
                            <td class="py-3 px-4 text-sm text-on-surface-variant"><?php echo htmlspecialchars($row['location']); ?></td>
                            <td class="py-3 px-4 text-sm text-on-surface-variant"><?php echo date('M d, Y, g:i A', strtotime($row['date_reported'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="text-center py-12">
                <span class="material-symbols-outlined text-4xl text-outline mb-2">analytics</span>
                <p class="text-on-surface-variant">No items found matching the selected criteria.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>

