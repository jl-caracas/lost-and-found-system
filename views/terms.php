<?php
/**
 * views/terms.php – Terms and Conditions Page
 * 
 * Displays the terms and conditions for using the Foundly platform.
 * Includes: Acceptance of terms, user accounts, data accuracy, termination, privacy.
 */
include __DIR__ . '/../includes/header.php';
?>

<div class="max-w-4xl mx-auto px-margin-mobile md:px-margin-desktop py-8">
    <div class="glass-card rounded-2xl p-6 md:p-8 card-shadow">
        <?php include __DIR__ . '/terms_content.php'; ?>

        <!-- Acknowledgment -->
        <div class="mt-8 p-4 bg-primary/5 rounded-xl border border-outline-variant/30">
            <p class="text-sm text-on-surface-variant">
                By using Foundly, you acknowledge that you have read, understood, and agree to be bound by these terms and conditions.
            </p>
        </div>

        <div class="mt-6 flex gap-3">
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="index.php?action=items" class="px-4 py-2 bg-primary text-on-primary rounded-xl text-sm font-medium hover:bg-primary/90 transition-all">Back to Home</a>
            <?php else: ?>
                <a href="index.php?action=landing" class="px-4 py-2 bg-primary text-on-primary rounded-xl text-sm font-medium hover:bg-primary/90 transition-all">Back to Home</a>
                <a href="index.php?action=register" class="px-4 py-2 bg-secondary-container text-on-secondary-container rounded-xl text-sm font-medium hover:bg-secondary-container/90 transition-all">Create Account</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

