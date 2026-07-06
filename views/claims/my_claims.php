<?php
/**
 * views/claims/my_claims.php – Finder's Claims View
 * 
 * Displays claims on items reported by the current user (finder).
 * - Same design as admin claims but with limited actions
 * - Only Update status (no delete)
 */
include __DIR__ . '/../../includes/header.php';
?>

<div class="max-w-container mx-auto px-margin-mobile md:px-margin-desktop">
    <div class="glass-card rounded-2xl p-6 md:p-8 card-shadow">
        <h2 class="font-display text-headline-md mb-2">Claims on My Found Items</h2>
        <p class="text-on-surface-variant text-sm mb-6">Review and manage claims submitted for items you reported as found.</p>

        <!-- Status Filter -->
        <form method="GET" action="index.php" class="flex flex-wrap gap-3 mb-6">
            <input type="hidden" name="action" value="my_claims">
            <select name="status" class="px-4 py-2 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none">
                <option value="">All Status</option>
                <option value="pending" <?php echo ($status == 'pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="rejected" <?php echo ($status == 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                <option value="claimed" <?php echo ($status == 'claimed') ? 'selected' : ''; ?>>Claimed</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-primary text-on-primary rounded-xl text-sm font-medium hover:bg-primary/90 transition-all">Filter</button>
            <a href="index.php?action=my_claims" class="px-4 py-2 bg-surface-variant text-on-surface-variant rounded-xl text-sm font-medium hover:bg-outline-variant transition-all">Reset</a>
        </form>

        <!-- Flash Messages -->
        <?php if(isset($_SESSION['success'])): ?>
            <div class="bg-success/10 text-success p-4 rounded-xl mb-4">✅ <?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if(isset($_SESSION['error'])): ?>
            <div class="bg-error-container text-on-error-container p-4 rounded-xl mb-4">❌ <?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- Claims Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-outline-variant/30">
                        <th class="text-left py-3 text-sm font-medium text-on-surface-variant">Item</th>
                        <th class="text-left py-3 text-sm font-medium text-on-surface-variant">Claimant</th>
                        <th class="text-left py-3 text-sm font-medium text-on-surface-variant">ID Number</th>
                        <th class="text-left py-3 text-sm font-medium text-on-surface-variant">Claim Date</th>
                        <th class="text-left py-3 text-sm font-medium text-on-surface-variant">Status</th>
                        <th class="text-left py-3 text-sm font-medium text-on-surface-variant">Proof</th>
                        <th class="text-left py-3 text-sm font-medium text-on-surface-variant">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($claims) == 0): ?>
                        <tr>
                            <td colspan="7" class="text-center py-8 text-on-surface-variant">No claims found on your items.</td>
                        </tr>
                    <?php else: ?>
                        <?php while($claim = mysqli_fetch_assoc($claims)): ?>
                            <tr class="border-b border-outline-variant/10 hover:bg-surface/20 transition-colors">
                                <td class="py-3 text-sm font-medium"><?php echo htmlspecialchars($claim['item_name']); ?></td>
                                <td class="py-3 text-sm"><?php echo htmlspecialchars($claim['claimant_name']); ?></td>
                                <td class="py-3 text-sm text-on-surface-variant"><?php echo htmlspecialchars($claim['claimant_id_number']); ?></td>
                                <td class="py-3 text-sm text-on-surface-variant"><?php echo date('M d, Y, g:i A', strtotime($claim['claim_date'])); ?></td>
                                <td class="py-3 text-sm">
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium <?php 
                                        echo ($claim['status'] == 'pending') ? 'bg-warning/20 text-warning' : 
                                            (($claim['status'] == 'claimed') ? 'bg-primary/20 text-primary' : 'bg-error-container/20 text-on-error-container'); 
                                    ?>">
                                        <?php echo ucfirst($claim['status']); ?>
                                    </span>
                                </td>
                                <td class="py-3 text-sm">
                                    <?php if(!empty($claim['proof_document'])): ?>
                                        <a href="/LF-web2/<?php echo $claim['proof_document']; ?>" target="_blank" 
                                           class="text-primary hover:underline text-sm">View</a>
                                    <?php else: ?>
                                        <span class="text-on-surface-variant/50">No proof</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 text-sm">
                                    <?php if($claim['status'] === 'pending'): ?>
                                        <a href="index.php?action=claims_edit&id=<?php echo $claim['id']; ?>" 
                                           class="px-3 py-1 bg-warning/20 text-warning hover:bg-warning/30 rounded-full text-xs font-medium transition-colors">Update Status</a>
                                    <?php else: ?>
                                        <span class="text-on-surface-variant/50 text-xs">Locked</span>
                                    <?php endif; ?>
                                </td>
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
                <a href="index.php?action=my_claims&page=<?php echo $i; ?>&status=<?php echo urlencode($status); ?>" 
                   class="px-4 py-2 rounded-lg <?php echo $i==$page ? 'bg-primary text-on-primary' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-highest'; ?> transition-colors">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>

