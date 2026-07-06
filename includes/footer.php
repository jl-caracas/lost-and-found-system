<?php
/**
 * includes/footer.php – Main layout footer
 * 
 * This file is included at the bottom of every page.
 * It contains:
 * - Closing main tag
 * - Mobile bottom navigation bar
 * - Floating Action Button (FAB) for creating new items
 * - JavaScript for micro-interactions
 */

// Only show FAB and bottom nav if user is logged in
$is_logged_in = isset($_SESSION['user_id']);
// Note: $unread_count is already calculated in header.php
// We use the same variable here for consistency
?>
</main> <!-- end main content -->

<!-- ===================================================== -->
<!-- BOTTOM NAVIGATION (Mobile only)                       -->
<!-- ===================================================== -->
<nav class="fixed bottom-0 left-0 w-full flex justify-around items-center py-3 px-4 md:hidden glass shadow-[0_-4px_20px_rgba(0,0,0,0.05)] z-50 rounded-t-2xl">
    
    <?php if($is_logged_in): ?>
        <!-- Logged-in user navigation -->
        <a href="index.php?action=items" 
           class="flex flex-col items-center justify-center text-secondary active:scale-95 transition-transform">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">home</span>
            <span class="text-[10px] font-medium">Home</span>
        </a>
        <a href="index.php?action=items_create" 
           class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary transition-colors active:scale-95 transition-transform">
            <span class="material-symbols-outlined">add_circle</span>
            <span class="text-[10px] font-medium">Post</span>
        </a>
        <a href="index.php?action=inbox" 
           class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary transition-colors active:scale-95 transition-transform relative">
            <span class="material-symbols-outlined">notifications</span>
            <?php if(isset($unread_count) && $unread_count > 0): ?>
                <span class="absolute -top-1 -right-1 bg-error text-white text-[9px] font-bold rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1">
                    <?php echo $unread_count; ?>
                </span>
            <?php endif; ?>
            <span class="text-[10px] font-medium">Alerts</span>
        </a>
        <a href="index.php?action=dashboard" 
           class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary transition-colors active:scale-95 transition-transform">
            <span class="material-symbols-outlined">person</span>
            <span class="text-[10px] font-medium">Profile</span>
        </a>
    <?php else: ?>
        <!-- Guest navigation -->
        <a href="index.php?action=login" 
           class="flex flex-col items-center justify-center text-secondary active:scale-95 transition-transform">
            <span class="material-symbols-outlined">login</span>
            <span class="text-[10px] font-medium">Login</span>
        </a>
    <?php endif; ?>
</nav>

<!-- ===================================================== -->
<!-- FLOATING ACTION BUTTON (FAB)                          -->
<!-- ===================================================== -->
<?php if($is_logged_in): ?>
    <button onclick="window.location.href='index.php?action=items_create'" 
            class="fixed bottom-24 right-6 md:bottom-10 md:right-10 w-16 h-16 bg-primary text-on-primary rounded-full shadow-lg flex items-center justify-center group active:scale-90 transition-all duration-150 z-40 hover:bg-primary/90 hover:shadow-xl">
        <span class="material-symbols-outlined text-[32px] group-hover:rotate-90 transition-transform duration-300">add</span>
    </button>
<?php endif; ?>

<!-- ===================================================== -->
<!-- GLOBAL FOOTER                                         -->
<!-- ===================================================== -->
<footer class="w-full py-8 mt-8 mb-20 md:mb-0 text-center border-t-[0.5px] border-outline-variant/20 text-on-surface-variant bg-surface/30">
    <div class="container mx-auto px-4 flex flex-col items-center gap-3">
        <div class="flex gap-4">
            <a href="index.php?action=terms" class="text-sm font-medium hover:text-primary transition-colors hover:underline">Terms and Conditions</a>
            <span class="text-outline-variant/50">&bull;</span>
            <a href="index.php?action=help" class="text-sm font-medium hover:text-primary transition-colors hover:underline">Help</a>
        </div>
        <p class="text-xs opacity-75 max-w-2xl text-balance leading-relaxed">
            &copy; <?php echo date('Y'); ?> Created by Group 16 BSIT 2-1:<br>
            Cervales, Yuann Czedriehck D., Marondo, John Immanuel C., Talosig, Jhun Francis M.
        </p>
    </div>
</footer>

<!-- ===================================================== -->
<!-- JAVASCRIPT – Micro-interactions                       -->
<!-- ===================================================== -->
<script>
    /**
     * Card press effect – adds a subtle "click" feeling to cards
     */
    document.querySelectorAll('article, .clickable-card').forEach(card => {
        card.addEventListener('mousedown', () => {
            card.style.transform = 'scale(0.98)';
        });
        card.addEventListener('mouseup', () => {
            card.style.transform = '';
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = '';
        });
    });

    /**
     * Auto-dismiss flash messages after 5 seconds
     */
    document.querySelectorAll('.alert-auto-dismiss').forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => { alert.remove(); }, 500);
        }, 5000);
    });

    /**
     * Confirm dialog for delete actions
     */
    document.querySelectorAll('.delete-confirm').forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirm(this.dataset.message || 'Are you sure you want to delete this item?')) {
                e.preventDefault();
            }
        });
    });
</script>

</body>
</html>