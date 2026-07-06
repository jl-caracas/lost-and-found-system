<?php
/**
 * views/claims/create.php – Claim Submission Form
 * 
 * Users can claim a found item by filling:
 * - Claimant name, ID type, ID number, contact
 * - Claim date
 * - Proof document (image or PDF)
 * 
 * Sticky form values on validation errors.
 */
include __DIR__ . '/../../includes/header.php';
?>

<div class="max-w-2xl mx-auto px-margin-mobile md:px-margin-desktop py-8">
    <button type="button" onclick="history.back()" class="flex items-center gap-2 text-on-surface-variant hover:text-primary transition-colors font-medium text-sm mb-6 group w-fit">
        <span class="material-symbols-outlined text-xl group-hover:-translate-x-1 transition-transform">arrow_back</span>
        Back
    </button>
    <div class="glass-card rounded-2xl p-6 md:p-8 card-shadow">
        <h2 class="font-display text-headline-md mb-2">Claim Item</h2>
        <div class="bg-surface-container-low p-4 rounded-xl mb-6">
            <p class="text-sm"><strong>Item:</strong> <?php echo htmlspecialchars($item['item_name']); ?></p>
            <p class="text-sm text-on-surface-variant"><strong>Found at:</strong> <?php echo htmlspecialchars($item['location']); ?></p>
            <p class="text-sm text-on-surface-variant"><strong>Found on:</strong> <?php echo date('F j, Y, g:i A', strtotime($item['date_reported'])); ?></p>
        </div>

        <?php if(!empty($errors)): ?>
            <div class="bg-error-container text-on-error-container p-4 rounded-xl mb-6 text-sm space-y-1">
                <?php foreach($errors as $err): ?>
                    <p>❌ <?php echo $err; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?action=claims_store" enctype="multipart/form-data" class="space-y-5">
            <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
            
            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">Your Full Name *</label>
                <input type="text" name="claimant_name" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none" 
                       value="<?php echo htmlspecialchars($_POST['claimant_name'] ?? ''); ?>" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">ID Type *</label>
                <select name="claimant_id_type" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none" required>
                    <option value="pup_id" <?php echo (($_POST['claimant_id_type'] ?? '') == 'pup_id') ? 'selected' : ''; ?>>PUP ID</option>
                    <option value="national_id" <?php echo (($_POST['claimant_id_type'] ?? '') == 'national_id') ? 'selected' : ''; ?>>National ID</option>
                    <option value="faculty_id" <?php echo (($_POST['claimant_id_type'] ?? '') == 'faculty_id') ? 'selected' : ''; ?>>Faculty ID</option>
                    <option value="other" <?php echo (($_POST['claimant_id_type'] ?? '') == 'other') ? 'selected' : ''; ?>>Other ID</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">ID Number *</label>
                <input type="text" name="claimant_id_number" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none" 
                       value="<?php echo htmlspecialchars($_POST['claimant_id_number'] ?? ''); ?>" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">Contact Number</label>
                <input type="text" name="claimant_contact" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none" 
                       value="<?php echo htmlspecialchars($_POST['claimant_contact'] ?? ''); ?>">
            </div>

            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">Date of Claim *</label>
                <input type="date" name="claim_date" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none" 
                       value="<?php echo htmlspecialchars($_POST['claim_date'] ?? date('Y-m-d')); ?>" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">Proof Document (receipt, ID, etc.) *</label>
                <input type="file" name="proof" accept="image/*,application/pdf" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-primary file:text-on-primary file:text-sm file:font-medium hover:file:bg-primary/90 transition-all outline-none" required>
                <p class="text-xs text-on-surface-variant mt-1.5">Max 2MB. Allowed: JPG, PNG, GIF, PDF</p>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-3 bg-primary text-on-primary rounded-xl font-medium hover:bg-primary/90 transition-all active:scale-[0.98]">
                    Submit Claim
                </button>
                <a href="index.php?action=items" class="px-6 py-3 bg-surface-variant text-on-surface-variant rounded-xl font-medium hover:bg-outline-variant transition-all">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>

