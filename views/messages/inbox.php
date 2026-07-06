<?php
/**
 * views/messages/inbox.php – Message Inbox with avatars
 * 
 * Displays all conversations with item avatars and unread counts
 */
include __DIR__ . '/../../includes/header.php';
?>

<div class="max-w-container mx-auto px-margin-mobile md:px-margin-desktop relative">
    <div class="mb-8">
        <h1 class="font-display text-3xl md:text-4xl font-extrabold text-primary mb-2">Messages</h1>
        <p class="text-on-surface-variant font-body">Manage your item inquiries and conversations.</p>
    </div>

    <div class="glass-card rounded-3xl p-6 md:p-10 card-shadow border border-white/20 relative overflow-hidden animate-fade-in-up">
        <!-- Background decorative elements -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-primary/10 rounded-full blur-3xl pointer-events-none -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-secondary/10 rounded-full blur-3xl pointer-events-none translate-y-1/3 -translate-x-1/3"></div>

        <?php if(isset($_SESSION['success'])): ?>
            <div class="bg-success/10 text-success border border-success/20 p-4 rounded-xl mb-6 alert-auto-dismiss flex items-center gap-3 relative z-10 shadow-sm">
                <span class="material-symbols-outlined">check_circle</span>
                <span class="font-medium"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></span>
            </div>
        <?php endif; ?>
        <?php if(isset($_SESSION['error'])): ?>
            <div class="bg-error-container/50 text-on-error-container border border-error/20 p-4 rounded-xl mb-6 alert-auto-dismiss flex items-center gap-3 relative z-10 shadow-sm">
                <span class="material-symbols-outlined">error</span>
                <span class="font-medium"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
            </div>
        <?php endif; ?>

        <div class="relative z-10">
            <?php if(mysqli_num_rows($conversations) == 0): ?>
                <div class="flex flex-col items-center justify-center py-16 px-4 animate-fade-in">
                    <div class="relative mb-8 group cursor-default">
                        <div class="absolute inset-0 bg-primary/20 blur-2xl rounded-full scale-150 group-hover:bg-primary/30 transition-all duration-700 animate-pulse"></div>
                        <div class="w-28 h-28 bg-gradient-to-br from-surface to-surface-variant rounded-[2rem] flex items-center justify-center relative border border-white/40 shadow-xl shadow-primary/10 transform rotate-3 hover:rotate-0 transition-transform duration-500">
                            <span class="material-symbols-outlined text-6xl bg-gradient-to-tr from-primary to-secondary bg-clip-text text-transparent">mark_chat_unread</span>
                        </div>
                    </div>
                    <h3 class="font-display text-2xl font-bold mt-2 text-center text-on-surface">No conversations yet</h3>
                    <p class="text-on-surface-variant mt-3 text-center max-w-md leading-relaxed text-sm">Your inbox is quiet for now. When someone contacts you about a lost or found item, the magic will happen right here.</p>
                    <a href="index.php?action=items" class="inline-flex items-center gap-2 mt-8 px-8 py-3.5 bg-primary text-on-primary rounded-full text-sm font-bold shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:bg-primary/90 hover:-translate-y-1 transition-all duration-300 group">
                        <span class="material-symbols-outlined text-xl group-hover:rotate-12 transition-transform">search</span>
                        Browse Items
                    </a>
                </div>
            <?php else: ?>
                <!-- Search and Filter Bar -->
                <div class="flex flex-col sm:flex-row gap-4 mb-6">
                    <div class="relative flex-1">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                        <input type="text" id="inboxSearch" placeholder="Search by item name or username..." class="w-full pl-12 pr-4 py-3 rounded-xl bg-surface border border-outline/20 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                    </div>
                    <select id="inboxFilter" class="px-4 py-3 rounded-xl bg-surface border border-outline/20 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all sm:w-48">
                        <option value="all">All Messages</option>
                        <option value="unread">Unread Only</option>
                    </select>
                </div>

                <div class="grid gap-4" id="conversationsList">
                    <?php while($conv = mysqli_fetch_assoc($conversations)): ?>
                        <?php 
                        $search_text = strtolower(htmlspecialchars($conv['item_name'] . ' ' . $conv['other_username']));
                        $is_unread = $conv['unread_count'] > 0 ? 'true' : 'false';
                        ?>
                        <a href="index.php?action=chat&item_id=<?php echo $conv['item_id']; ?>&other_user_id=<?php echo $conv['other_user_id']; ?>" 
                           data-search="<?php echo $search_text; ?>" data-unread="<?php echo $is_unread; ?>"
                           class="conversation-item flex flex-col sm:flex-row sm:items-center gap-4 p-5 rounded-2xl bg-surface/40 border border-outline/10 hover:border-primary/30 hover:bg-surface/80 hover:shadow-lg hover:shadow-primary/5 hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden">
                            
                            <!-- Card Hover Gradient -->
                            <div class="absolute inset-0 bg-gradient-to-r from-primary/0 via-primary/5 to-primary/0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 translate-x-[-100%] group-hover:translate-x-[100%]"></div>

                            <!-- Item Avatar with first letter -->
                            <div class="relative flex-shrink-0 w-16 h-16 sm:w-14 sm:h-14">
                                <div class="w-full h-full rounded-2xl overflow-hidden bg-gradient-to-br from-primary/10 to-secondary/10 border border-primary/10 text-primary flex items-center justify-center font-display font-bold text-2xl shadow-inner group-hover:scale-105 transition-transform duration-300">
                                    <?php echo strtoupper(substr($conv['item_name'], 0, 1)); ?>
                                </div>
                                <?php if($conv['unread_count'] > 0): ?>
                                    <span class="absolute -top-1.5 -right-1.5 flex h-5 w-5 items-center justify-center">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-error opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-4 w-4 bg-error border-2 border-surface"></span>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Conversation Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center justify-between mb-1.5 gap-2">
                                    <h4 class="font-bold text-on-surface text-lg sm:text-base truncate group-hover:text-primary transition-colors">
                                        <?php echo htmlspecialchars($conv['item_name']); ?>
                                        <span class="font-normal text-sm text-on-surface-variant ml-2 hidden sm:inline-block">with <?php echo htmlspecialchars($conv['other_username']); ?></span>
                                    </h4>
                                    <span class="text-xs font-semibold text-on-surface-variant flex-shrink-0 bg-surface border border-outline/10 px-2.5 py-1 rounded-lg shadow-sm group-hover:bg-primary/5 group-hover:border-primary/20 transition-colors">
                                        <?php echo date('M j, g:i A', strtotime($conv['last_message_time'])); ?>
                                    </span>
                                </div>
                                <div class="flex items-center justify-between gap-4">
                                    <p class="text-sm text-on-surface-variant truncate <?php echo $conv['unread_count'] > 0 ? 'text-on-surface font-bold' : 'font-medium'; ?>">
                                        <?php echo htmlspecialchars(substr($conv['last_message'], 0, 60)); ?>
                                    </p>
                                    <?php if($conv['unread_count'] > 0): ?>
                                        <span class="bg-gradient-to-r from-error to-error/90 text-white text-[10px] uppercase tracking-wider font-bold rounded-full px-3 py-1 flex-shrink-0 shadow-sm shadow-error/20">
                                            <?php echo $conv['unread_count']; ?> new
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Arrow Indicator -->
                            <div class="hidden sm:flex w-10 h-10 rounded-full bg-surface border border-outline/10 items-center justify-center group-hover:bg-primary group-hover:border-primary shadow-sm transition-all duration-300 shrink-0">
                                <span class="material-symbols-outlined text-on-surface-variant group-hover:text-white transition-colors">arrow_forward</span>
                            </div>
                        </a>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('inboxSearch');
    const filterSelect = document.getElementById('inboxFilter');
    const items = document.querySelectorAll('.conversation-item');

    function filterConversations() {
        if (!searchInput || !filterSelect) return;
        
        const searchTerm = searchInput.value.toLowerCase();
        const filterVal = filterSelect.value;

        items.forEach(item => {
            const text = item.getAttribute('data-search') || '';
            const unread = item.getAttribute('data-unread') || 'false';
            
            const matchesSearch = text.includes(searchTerm);
            const matchesFilter = filterVal === 'all' || (filterVal === 'unread' && unread === 'true');

            if (matchesSearch && matchesFilter) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }

    if(searchInput) searchInput.addEventListener('input', filterConversations);
    if(filterSelect) filterSelect.addEventListener('change', filterConversations);
});
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
