<?php
/**
 * views/claims/edit.php – Claim Status Update (Admin/Finder)
 * 
 * Admin or finder can update:
 * - Claim status (pending, rejected, claimed)
 * - Admin remarks
 * 
 * When status is set to 'claimed', the item is automatically marked as claimed.
 */
include __DIR__ . '/../../includes/header.php';
?>

<div class="max-w-2xl mx-auto px-margin-mobile md:px-margin-desktop py-8">
    <button type="button" onclick="history.back()" class="flex items-center gap-2 text-on-surface-variant hover:text-primary transition-colors font-medium text-sm mb-6 group w-fit">
        <span class="material-symbols-outlined text-xl group-hover:-translate-x-1 transition-transform">arrow_back</span>
        Back
    </button>
    <div class="glass-card rounded-2xl p-6 md:p-8 card-shadow">
        <h2 class="font-display text-headline-md mb-2">Update Claim Status</h2>
        
        <div class="bg-surface-container-low p-4 rounded-xl mb-6 space-y-1">
            <p class="text-sm"><strong>Item:</strong> <?php echo htmlspecialchars($claim['item_name']); ?></p>
            <p class="text-sm"><strong>Claimant:</strong> <?php echo htmlspecialchars($claim['claimant_name']); ?> (<?php echo htmlspecialchars($claim['claimant_id_number']); ?>)</p>
            <p class="text-sm text-on-surface-variant"><strong>Claim Date:</strong> <?php echo date('F j, Y, g:i A', strtotime($claim['claim_date'])); ?></p>
            <?php if(!empty($claim['proof_document'])): ?>
                <p class="text-sm"><strong>Proof:</strong> <a href="/LF-web2/<?php echo $claim['proof_document']; ?>" target="_blank" class="text-primary hover:underline">View Document</a></p>
            <?php endif; ?>
        </div>

        <?php if(isset($error)): ?>
            <div class="bg-error-container text-on-error-container p-4 rounded-xl mb-6 text-sm">❌ <?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?action=claims_update" class="space-y-5">
            <input type="hidden" name="id" value="<?php echo $claim['id']; ?>">
            
            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">Status</label>
                <select name="status" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none" required>
                    <option value="pending" <?php echo ($claim['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="rejected" <?php echo ($claim['status'] == 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                    <option value="claimed" <?php echo ($claim['status'] == 'claimed') ? 'selected' : ''; ?>>Claimed</option>
                </select>
                <p class="text-xs text-on-surface-variant mt-1.5">Setting to "Claimed" will mark the item as claimed and hide it from the feed.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">Admin Remarks</label>
                <textarea name="admin_remarks" rows="3" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none"><?php echo htmlspecialchars($claim['admin_remarks']); ?></textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-3 bg-primary text-on-primary rounded-xl font-medium hover:bg-primary/90 transition-all active:scale-[0.98]">
                    Update Claim
                </button>
                <a href="index.php?action=<?php echo ($_SESSION['role'] === 'admin') ? 'claims' : 'my_claims'; ?>" class="px-6 py-3 bg-surface-variant text-on-surface-variant rounded-xl font-medium hover:bg-outline-variant transition-all">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
