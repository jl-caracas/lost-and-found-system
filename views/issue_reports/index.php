<?php
/**
 * views/issue_reports/index.php – Issue Reports Dashboard
 */
include __DIR__ . '/../../includes/header.php';
?>

<div class="max-w-7xl mx-auto px-margin-mobile md:px-6 py-12">
    <!-- Header Section -->
    <div class="relative glass-card rounded-3xl p-8 mb-10 overflow-hidden shadow-lg border border-outline-variant/20">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="font-display text-3xl md:text-5xl font-extrabold text-primary mb-3 tracking-tight">Issue Reports</h1>
                <p class="text-on-surface-variant font-medium text-lg max-w-xl font-body">Manage and resolve bug reports or user suggestions submitted from the platform.</p>
            </div>
            
            <div class="bg-surface-container-highest rounded-2xl p-4 flex items-center gap-4 text-on-surface border border-outline-variant/30 shadow-sm">
                <div class="p-3 bg-primary/10 rounded-full flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-3xl">bug_report</span>
                </div>
                <div>
                    <div class="text-3xl font-extrabold text-primary"><?php echo mysqli_num_rows($reports); ?></div>
                    <div class="text-sm font-medium text-on-surface-variant">Visible Reports</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass-card rounded-2xl p-5 mb-8 shadow-sm border border-outline-variant/30 flex flex-wrap gap-4 items-center justify-between hover:shadow-md transition-shadow">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                <span class="material-symbols-outlined">filter_list</span>
            </div>
            <span class="font-bold text-on-surface">Filter Issues</span>
        </div>
        
        <form method="GET" action="index.php" class="flex flex-wrap gap-4 items-center w-full md:w-auto">
            <input type="hidden" name="action" value="issue_reports">
            
            <div class="relative min-w-[200px]">
                <select name="status" class="w-full bg-surface-container text-on-surface font-medium px-5 py-3.5 rounded-xl border border-outline-variant/30 focus:border-primary focus:ring-2 focus:ring-primary/20 cursor-pointer outline-none transition-all" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="open" <?php echo ($status == 'open') ? 'selected' : ''; ?>>Open Issues</option>
                    <option value="fixed" <?php echo ($status == 'fixed') ? 'selected' : ''; ?>>Resolved Issues</option>
                </select>
            </div>
            
            <?php if(!empty($status)): ?>
                <a href="index.php?action=issue_reports" class="px-4 py-2.5 text-error hover:bg-error-container hover:text-on-error-container rounded-xl text-sm font-bold transition-colors flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px]">close</span> Clear
                </a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Reports List -->
    <div class="glass-card rounded-3xl p-1 shadow-sm border border-outline-variant/30 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="bg-surface-container-lowest border-b border-outline-variant/30">
                        <th class="py-4 px-6 text-xs font-extrabold text-on-surface-variant uppercase tracking-wider rounded-tl-3xl">ID</th>
                        <th class="py-4 px-6 text-xs font-extrabold text-on-surface-variant uppercase tracking-wider">Reporter</th>
                        <th class="py-4 px-6 text-xs font-extrabold text-on-surface-variant uppercase tracking-wider">Issue Type</th>
                        <th class="py-4 px-6 text-xs font-extrabold text-on-surface-variant uppercase tracking-wider w-[35%]">Description</th>
                        <th class="py-4 px-6 text-xs font-extrabold text-on-surface-variant uppercase tracking-wider text-center">Status</th>
                        <th class="py-4 px-6 text-xs font-extrabold text-on-surface-variant uppercase tracking-wider text-right rounded-tr-3xl">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    <?php if (mysqli_num_rows($reports) > 0): ?>
                        <?php while ($report = mysqli_fetch_assoc($reports)): ?>
                            <tr class="group hover:bg-surface-container-highest/50 transition-colors duration-200">
                                <td class="py-5 px-6">
                                    <span class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 rounded-full bg-surface-container text-on-surface-variant font-bold text-xs group-hover:bg-surface-container-highest transition-all">
                                        #<?php echo $report['id']; ?>
                                    </span>
                                </td>
                                <td class="py-5 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center text-primary font-bold shadow-sm">
                                            <?php echo $report['reporter_username'] ? strtoupper(substr($report['reporter_username'], 0, 1)) : '?'; ?>
                                        </div>
                                        <div>
                                            <p class="font-bold text-on-surface text-sm"><?php echo $report['reporter_username'] ? htmlspecialchars($report['reporter_username']) : 'Guest User'; ?></p>
                                            <p class="text-[11px] text-on-surface-variant font-medium uppercase tracking-wider mt-0.5"><?php echo date('M d, Y', strtotime($report['created_at'])); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-5 px-6">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-secondary-container text-on-secondary-container">
                                        <span class="material-symbols-outlined text-[16px]">category</span>
                                        <?php echo htmlspecialchars($report['issue_type']); ?>
                                    </span>
                                </td>
                                <td class="py-5 px-6">
                                    <div class="text-sm text-on-surface-variant line-clamp-2 leading-relaxed" title="<?php echo htmlspecialchars($report['description']); ?>">
                                        <?php echo htmlspecialchars($report['description']); ?>
                                    </div>
                                </td>
                                <td class="py-5 px-6 text-center">
                                    <?php if ($report['status'] == 'open'): ?>
                                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-error-container text-on-error-container border border-error/20">
                                            <span class="relative flex h-2 w-2">
                                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-error opacity-75"></span>
                                              <span class="relative inline-flex rounded-full h-2 w-2 bg-error"></span>
                                            </span>
                                            OPEN
                                        </div>
                                    <?php else: ?>
                                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-success-container text-on-success-container border border-success/20" title="Fixed by: <?php echo htmlspecialchars($report['resolver_username'] ?? 'Unknown'); ?>">
                                            <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                            FIXED
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="py-5 px-6 text-right">
                                    <?php if ($report['status'] == 'open'): ?>
                                        <form action="index.php?action=issue_reports_fix" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to mark this issue as fixed?');">
                                            <input type="hidden" name="id" value="<?php echo $report['id']; ?>">
                                            <button type="submit" class="group/btn relative inline-flex items-center justify-center gap-2 px-4 py-2 bg-primary text-on-primary rounded-xl font-semibold text-sm shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 overflow-hidden">
                                                <span class="absolute inset-0 w-full h-full bg-white/20 translate-y-full group-hover/btn:translate-y-0 transition-transform duration-300 ease-in-out"></span>
                                                <span class="relative flex items-center gap-1.5">
                                                    <span class="material-symbols-outlined text-[18px]">check</span> Resolve
                                                </span>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 text-xs font-bold text-on-surface-variant bg-surface-container px-4 py-2 rounded-xl border border-outline-variant/30">
                                            <span class="material-symbols-outlined text-[14px]">lock</span> Resolved
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="py-24 text-center">
                                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-surface-container mb-5 border-4 border-surface-container-lowest shadow-sm">
                                    <span class="material-symbols-outlined text-5xl text-on-surface-variant">task_alt</span>
                                </div>
                                <h3 class="text-xl font-extrabold text-on-surface mb-2">All clear!</h3>
                                <p class="text-on-surface-variant font-medium max-w-md mx-auto">No issue reports found matching your criteria. Everything is running smoothly.</p>
                                <?php if($status): ?>
                                    <a href="index.php?action=issue_reports" class="inline-block mt-6 px-6 py-3 bg-surface-variant hover:bg-surface-container-highest text-on-surface rounded-xl font-bold transition-colors">
                                        Clear Filters
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="mt-8 flex justify-center">
            <div class="inline-flex items-center gap-1.5 glass-card border border-outline-variant/30 rounded-2xl p-1.5 shadow-sm">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="index.php?action=issue_reports&page=<?php echo $i; ?>&status=<?php echo urlencode($status); ?>" 
                       class="w-10 h-10 flex items-center justify-center rounded-xl text-sm font-bold transition-all duration-200 <?php echo $i == $page ? 'bg-primary text-on-primary shadow-md transform scale-105' : 'text-on-surface-variant hover:bg-surface-container'; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
