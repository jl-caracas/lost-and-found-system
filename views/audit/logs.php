<?php
/**
 * views/audit/logs.php – Audit Logs Viewer (Admin only)
 * 
 * Displays all logged actions with:
 * - Search by username, action, or module
 * - Pagination (20 logs per page)
 * - User, action, module, IP, timestamp columns
 * - Clean, table-based layout
 */
include __DIR__ . '/../../includes/header.php';
?>

<div class="max-w-container mx-auto px-margin-mobile md:px-margin-desktop">
    <div class="glass-card rounded-2xl p-6 md:p-8 card-shadow">
        <h1 class="font-display text-3xl md:text-4xl font-extrabold text-primary mb-2">Audit Logs</h1>
        <p class="text-on-surface-variant font-body mb-6">Track all user actions across the system.</p>

        <!-- Search Form -->
        <form method="GET" action="index.php" class="flex flex-wrap gap-3 mb-6">
            <input type="hidden" name="action" value="audit_logs">
            
            <input type="text" name="search" placeholder="Search by username, action..." 
                   value="<?php echo htmlspecialchars($search ?? ''); ?>" 
                   class="flex-1 min-w-[200px] px-4 py-2 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none">
                   
            <select name="module_filter" onchange="this.form.submit()" class="px-4 py-2 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none cursor-pointer">
                <option value="">All Modules</option>
                <?php 
                if(isset($modules_result) && mysqli_num_rows($modules_result) > 0):
                    mysqli_data_seek($modules_result, 0);
                    while($mod = mysqli_fetch_assoc($modules_result)):
                ?>
                    <option value="<?php echo htmlspecialchars($mod['module']); ?>" <?php echo (isset($module_filter) && $module_filter === $mod['module']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars(ucfirst($mod['module'])); ?>
                    </option>
                <?php 
                    endwhile; 
                endif; 
                ?>
            </select>
            
            <button type="submit" class="px-4 py-2 bg-primary text-on-primary rounded-xl text-sm font-medium hover:bg-primary/90 transition-all">Search</button>
            <?php if(!empty($search) || !empty($module_filter)): ?>
                <a href="index.php?action=audit_logs" class="px-4 py-2 bg-surface-variant text-on-surface-variant rounded-xl text-sm font-medium hover:bg-outline-variant transition-all">Clear</a>
            <?php endif; ?>
        </form>

        <!-- Error messages -->
        <?php if(isset($_SESSION['error'])): ?>
            <div class="bg-error-container text-on-error-container p-4 rounded-xl mb-4">❌ <?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- Logs Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-outline-variant/30">
                        <th class="text-left py-3 text-sm font-medium text-on-surface-variant">ID</th>
                        <th class="text-left py-3 text-sm font-medium text-on-surface-variant">User</th>
                        <th class="text-left py-3 text-sm font-medium text-on-surface-variant">Action</th>
                        <th class="text-left py-3 text-sm font-medium text-on-surface-variant">Module</th>
                        <th class="text-left py-3 text-sm font-medium text-on-surface-variant">IP Address</th>
                        <th class="text-left py-3 text-sm font-medium text-on-surface-variant">Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($logs) == 0): ?>
                        <tr>
                            <td colspan="6" class="text-center py-8 text-on-surface-variant">
                                No logs found. <?php if(!empty($search)): ?>Try a different search.<?php endif; ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php while($log = mysqli_fetch_assoc($logs)): ?>
                            <tr class="border-b border-outline-variant/10 hover:bg-surface/20 transition-colors">
                                <td class="py-3 text-sm"><?php echo $log['id']; ?></td>
                                <td class="py-3 text-sm font-medium">
                                    <?php if($log['username']): ?>
                                        <?php echo htmlspecialchars($log['username']); ?>
                                    <?php else: ?>
                                        <span class="text-on-surface-variant/50">System/Deleted</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 text-sm"><?php echo htmlspecialchars($log['action']); ?></td>
                                <td class="py-3 text-sm">
                                    <span class="px-2 py-0.5 rounded-full text-xs bg-surface-variant text-on-surface-variant">
                                        <?php echo htmlspecialchars($log['module']); ?>
                                    </span>
                                </td>
                                <td class="py-3 text-sm text-on-surface-variant"><?php echo htmlspecialchars($log['ip_address']); ?></td>
                                <td class="py-3 text-sm text-on-surface-variant"><?php echo date('Y-m-d H:i:s', strtotime($log['created_at'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if($totalPages > 1): ?>
        <div class="flex justify-center gap-2 mt-6">
            <?php for($i=1; $i<=$totalPages; $i++): ?>
                <a href="index.php?action=audit_logs&page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&module_filter=<?php echo urlencode($module_filter ?? ''); ?>" 
                   class="px-4 py-2 rounded-lg <?php echo $i==$page ? 'bg-primary text-on-primary' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-highest'; ?> transition-colors">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
