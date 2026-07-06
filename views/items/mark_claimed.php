<?php include __DIR__ . '/../../includes/header.php'; ?>

<div class="max-w-xl mx-auto px-margin-mobile md:px-margin-desktop py-8">
    <button type="button" onclick="history.back()" class="flex items-center gap-2 text-on-surface-variant hover:text-primary transition-colors font-medium text-sm mb-6 group w-fit">
        <span class="material-symbols-outlined text-xl group-hover:-translate-x-1 transition-transform">arrow_back</span>
        Back
    </button>
    
    <?php if(isset($_SESSION['error'])): ?>
        <div class="bg-error-container text-on-error-container p-4 rounded-xl mb-6 text-sm">
            ❌ <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="glass-card rounded-2xl p-6 md:p-8 card-shadow">
        <h2 class="font-display text-headline-md mb-2">Mark as Claimed</h2>
        <p class="text-on-surface-variant text-sm mb-6">Please upload a proof photo or document (e.g., photo of the item returned) for our records before closing this report.</p>

        <form method="POST" action="index.php?action=items_mark_claimed&id=<?php echo $item['id']; ?>" enctype="multipart/form-data" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">Proof of Return *</label>
                <input type="file" name="proof" accept="image/*,application/pdf" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-primary file:text-on-primary file:text-sm file:font-medium hover:file:bg-primary/90 transition-all outline-none" required>
                <p class="text-xs text-on-surface-variant mt-1.5">Max 2MB. Allowed: JPG, PNG, GIF, PDF</p>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="px-6 py-3 bg-primary text-on-primary rounded-xl font-medium hover:bg-primary/90 transition-all active:scale-[0.98]">
                    Submit Proof & Close Report
                </button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
