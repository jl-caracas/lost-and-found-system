<?php
/**
 * views/claims/index.php – Claims Management (Admin only)
 * 
 * Displays all claims with:
 * - Status filter (pending, approved, rejected, claimed)
 * - Search by claimant name, ID, or item name
 * - Update status button
 * - Delete button
 */
include __DIR__ . '/../../includes/header.php';
?>

?>

<div class="max-w-7xl mx-auto px-margin-mobile md:px-6 py-12">
    <!-- Header Section -->
    <div class="relative glass-card rounded-3xl p-8 mb-10 overflow-hidden shadow-lg border border-outline-variant/20">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="font-display text-3xl md:text-5xl font-extrabold text-primary mb-3 tracking-tight">Claim Requests</h1>
                <p class="text-on-surface-variant font-medium text-lg max-w-xl font-body">Review and manage claims submitted by users for found items on the platform.</p>
            </div>
            
            <div class="bg-surface-container-highest rounded-2xl p-4 flex items-center gap-4 text-on-surface border border-outline-variant/30 shadow-sm">
                <div class="p-3 bg-primary/10 rounded-full flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-3xl">fact_check</span>
                </div>
                <div>
                    <div class="text-3xl font-extrabold text-primary"><?php echo mysqli_num_rows($claims); ?></div>
                    <div class="text-sm font-medium text-on-surface-variant">Visible Claims</div>
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
            <span class="font-bold text-on-surface">Filter Claims</span>
        </div>
        
        <form method="GET" action="index.php" class="flex flex-wrap gap-4 items-center w-full md:w-auto flex-1 md:flex-none justify-end">
            <input type="hidden" name="action" value="claims">
            
            <div class="relative min-w-[160px]">
                <select name="status" class="w-full bg-surface-container text-on-surface font-medium px-5 py-3 rounded-xl border border-outline-variant/30 focus:border-primary focus:ring-2 focus:ring-primary/20 cursor-pointer outline-none transition-all" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="pending" <?php echo ($status == 'pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="rejected" <?php echo ($status == 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                    <option value="claimed" <?php echo ($status == 'claimed') ? 'selected' : ''; ?>>Claimed</option>
                </select>
            </div>

            <div class="relative flex-1 md:flex-none min-w-[240px]">
                <input type="text" name="search" placeholder="Search claimant or ID..." 
                       value="<?php echo htmlspecialchars($search ?? ''); ?>" 
                       class="w-full bg-surface-container text-on-surface font-medium pl-10 pr-4 py-3 rounded-xl border border-outline-variant/30 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                <span class="material-symbols-outlined absolute left-3 top-1/2 transform -translate-y-1/2 text-on-surface-variant">search</span>
            </div>
            
            <?php if(!empty($status) || !empty($search)): ?>
                <a href="index.php?action=claims" class="px-4 py-2.5 text-error hover:bg-error-container hover:text-on-error-container rounded-xl text-sm font-bold transition-colors flex items-center gap-1.5 shrink-0">
                    <span class="material-symbols-outlined text-[18px]">close</span> Clear
                </a>
            <?php else: ?>
                <button type="submit" class="px-5 py-2.5 bg-primary text-on-primary rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all shrink-0">Search</button>
            <?php endif; ?>
        </form>
    </div>

    <!-- Flash Messages -->
    <?php if(isset($_SESSION['success'])): ?>
        <div class="bg-success-container border border-success/20 text-on-success-container px-6 py-4 rounded-2xl mb-6 shadow-sm flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span>
            <span class="font-medium"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></span>
        </div>
    <?php endif; ?>
    <?php if(isset($_SESSION['error'])): ?>
        <div class="bg-error-container border border-error/20 text-on-error-container px-6 py-4 rounded-2xl mb-6 shadow-sm flex items-center gap-3">
            <span class="material-symbols-outlined">error</span>
            <span class="font-medium"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
        </div>
    <?php endif; ?>

    <!-- Claims List -->
    <div class="glass-card rounded-3xl p-1 shadow-sm border border-outline-variant/30 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="bg-surface-container-lowest border-b border-outline-variant/30">
                        <th class="py-4 px-6 text-xs font-extrabold text-on-surface-variant uppercase tracking-wider rounded-tl-3xl">Item</th>
                        <th class="py-4 px-6 text-xs font-extrabold text-on-surface-variant uppercase tracking-wider">Claimant Info</th>
                        <th class="py-4 px-6 text-xs font-extrabold text-on-surface-variant uppercase tracking-wider">Claim Date</th>
                        <th class="py-4 px-6 text-xs font-extrabold text-on-surface-variant uppercase tracking-wider text-center">Status</th>
                        <th class="py-4 px-6 text-xs font-extrabold text-on-surface-variant uppercase tracking-wider text-center">Proof</th>
                        <th class="py-4 px-6 text-xs font-extrabold text-on-surface-variant uppercase tracking-wider text-right rounded-tr-3xl">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    <?php if (mysqli_num_rows($claims) > 0): ?>
                        <?php while ($claim = mysqli_fetch_assoc($claims)): ?>
                            <tr class="group hover:bg-surface-container-highest/50 transition-colors duration-200">
                                <td class="py-5 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-secondary-container text-on-secondary-container flex items-center justify-center font-bold shadow-sm">
                                            <span class="material-symbols-outlined">category</span>
                                        </div>
                                        <span class="font-bold text-on-surface text-sm max-w-[150px] truncate" title="<?php echo htmlspecialchars($claim['item_name']); ?>">
                                            <?php echo htmlspecialchars($claim['item_name']); ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="py-5 px-6">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-on-surface text-sm"><?php echo htmlspecialchars($claim['claimant_name']); ?></span>
                                        <span class="text-xs text-on-surface-variant mt-0.5 uppercase tracking-wider"><?php echo htmlspecialchars($claim['claimant_id_number']); ?></span>
                                    </div>
                                </td>
                                <td class="py-5 px-6">
                                    <span class="text-sm text-on-surface-variant font-medium">
                                        <?php echo date('M d, Y', strtotime($claim['claim_date'])); ?>
                                    </span>
                                </td>
                                <td class="py-5 px-6 text-center">
                                    <?php if ($claim['status'] == 'pending'): ?>
                                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-warning-container text-on-warning-container border border-warning/20">
                                            <span class="relative flex h-2 w-2">
                                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-warning opacity-75"></span>
                                              <span class="relative inline-flex rounded-full h-2 w-2 bg-warning"></span>
                                            </span>
                                            PENDING
                                        </div>
                                    <?php elseif ($claim['status'] == 'claimed'): ?>
                                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-primary-container text-on-primary-container border border-primary/20">
                                            <span class="material-symbols-outlined text-[14px]">check_circle</span> CLAIMED
                                        </div>
                                    <?php else: ?>
                                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-error-container text-on-error-container border border-error/20">
                                            <span class="material-symbols-outlined text-[14px]">cancel</span> REJECTED
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="py-5 px-6 text-center">
                                    <?php if(!empty($claim['proof_document'])): ?>
                                        <a href="/LF-web2/<?php echo $claim['proof_document']; ?>" target="_blank" 
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-primary/10 text-primary hover:bg-primary/20 transition-colors" title="View Document">
                                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-on-surface-variant/50 text-xs font-medium">None</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-5 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="index.php?action=claims_edit&id=<?php echo $claim['id']; ?>" 
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-surface-container text-on-surface hover:bg-surface-variant rounded-xl text-xs font-bold transition-colors">
                                            <span class="material-symbols-outlined text-[16px]">edit</span> Update
                                        </a>
                                        <a href="index.php?action=claims_delete&id=<?php echo $claim['id']; ?>" 
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-error-container/50 text-error hover:bg-error-container hover:text-on-error-container rounded-xl text-xs font-bold transition-colors delete-confirm"
                                           onclick="return confirm('Are you sure you want to delete this claim?')">
                                            <span class="material-symbols-outlined text-[16px]">delete</span> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="py-24 text-center">
                                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-surface-container mb-5 border-4 border-surface-container-lowest shadow-sm">
                                    <span class="material-symbols-outlined text-5xl text-on-surface-variant">inventory_2</span>
                                </div>
                                <h3 class="text-xl font-extrabold text-on-surface mb-2">No claims found</h3>
                                <p class="text-on-surface-variant font-medium max-w-md mx-auto">There are currently no claims matching your filter criteria.</p>
                                <?php if(!empty($status) || !empty($search)): ?>
                                    <a href="index.php?action=claims" class="inline-block mt-6 px-6 py-3 bg-surface-variant hover:bg-surface-container-highest text-on-surface rounded-xl font-bold transition-colors">
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
                    <a href="index.php?action=claims&page=<?php echo $i; ?>&status=<?php echo urlencode($status); ?>&search=<?php echo urlencode($search); ?>" 
                       class="w-10 h-10 flex items-center justify-center rounded-xl text-sm font-bold transition-all duration-200 <?php echo $i == $page ? 'bg-primary text-on-primary shadow-md transform scale-105' : 'text-on-surface-variant hover:bg-surface-container'; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>

