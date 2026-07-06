<?php
/**
 * views/landing.php – Public Landing Page
 * 
 * The first page visitors see. Features:
 * - New Discovery Feed Hero section with live stats
 * - How Foundly Works guide
 * - Proceed to Discovery Feed button
 */
include __DIR__ . '/../includes/header.php';
?>

<!-- Hero & Filter Section with Premium Background -->
<div class="relative overflow-hidden bg-gradient-to-b from-primary/5 via-primary/10 to-transparent pt-12 pb-8 mb-stack-lg border-b border-outline-variant/20">
    <!-- Decorative background glow -->
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-primary/20 rounded-full blur-3xl -translate-y-1/2 pointer-events-none"></div>
    <div class="absolute top-0 right-1/4 w-96 h-96 bg-secondary/20 rounded-full blur-3xl -translate-y-1/2 pointer-events-none"></div>
    
    <section class="max-w-container mx-auto px-margin-mobile md:px-margin-desktop relative z-10">
        <div class="flex flex-col lg:flex-row items-center lg:items-start justify-between gap-12 mb-12">
            <!-- Left Column: Intro -->
            <div class="w-full lg:w-1/2 flex flex-col items-center lg:items-start text-center lg:text-left lg:pt-8">
                <h1 class="font-display text-4xl md:text-6xl font-extrabold mb-4 text-on-surface leading-tight">
                    Welcome to <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">Foundly</span>
                </h1>
                <p class="text-on-surface-variant font-body text-lg md:text-xl mb-8 max-w-lg">
                    Reconnecting lost items with their owners through a trusted community of finders. Your lost item might just be a search away.
                </p>
                
                <!-- Main Search Bar -->
                <div class="w-full max-w-md relative group">
                    <form method="GET" action="index.php">
                        <input type="hidden" name="action" value="items">
                        
                        <div class="relative flex items-center shadow-lg rounded-full">
                            <span class="material-symbols-outlined absolute left-5 text-primary text-[24px]">search</span>
                            <input type="text" 
                                   name="search" 
                                   placeholder="Search for an item or location..." 
                                   class="w-full bg-white/90 backdrop-blur-md border border-outline-variant/50 focus:ring-4 focus:ring-primary/20 focus:border-primary rounded-full pl-14 pr-32 py-4 font-body transition-all text-lg">
                            <button type="submit" class="absolute right-2 bg-primary text-white px-6 py-2 rounded-full font-bold hover:shadow-lg hover:-translate-y-0.5 transition-all">Search</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Right Column: Stats -->
            <div class="w-full lg:w-1/2 flex justify-center lg:justify-end">
                <div class="grid grid-cols-2 gap-4 w-full max-w-md">
                    <div class="col-span-2 bg-gradient-to-br from-surface-container to-surface-container-high p-6 rounded-3xl shadow-xl border border-white/50 backdrop-blur-md relative overflow-hidden group hover:-translate-y-1 transition-transform">
                        <div class="absolute -right-4 -top-4 w-24 h-24 bg-success/10 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                        <div class="flex items-center gap-4 relative z-10">
                            <div class="w-14 h-14 bg-success/20 text-success rounded-2xl flex items-center justify-center shadow-inner">
                                <span class="material-symbols-outlined text-3xl">task_alt</span>
                            </div>
                            <div>
                                <p class="text-on-surface-variant font-label-caps text-sm mb-1">Successfully Claimed</p>
                                <h3 class="font-display text-4xl font-extrabold text-on-surface"><?php echo number_format($totalClaimedStats ?? 0); ?></h3>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-surface-container to-surface-container-high p-5 rounded-3xl shadow-lg border border-white/50 backdrop-blur-md relative overflow-hidden group hover:-translate-y-1 transition-transform">
                        <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-error/10 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                        <div class="flex flex-col gap-2 relative z-10">
                            <div class="w-10 h-10 bg-error/10 text-error rounded-xl flex items-center justify-center">
                                <span class="material-symbols-outlined">search_off</span>
                            </div>
                            <h3 class="font-display text-3xl font-extrabold text-on-surface"><?php echo number_format($totalLostStats ?? 0); ?></h3>
                            <p class="text-on-surface-variant font-label-caps text-xs">Items Lost</p>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-surface-container to-surface-container-high p-5 rounded-3xl shadow-lg border border-white/50 backdrop-blur-md relative overflow-hidden group hover:-translate-y-1 transition-transform">
                        <div class="absolute -left-4 -bottom-4 w-20 h-20 bg-secondary/10 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                        <div class="flex flex-col gap-2 relative z-10 items-end text-right">
                            <div class="w-10 h-10 bg-secondary/10 text-secondary rounded-xl flex items-center justify-center self-end">
                                <span class="material-symbols-outlined">manage_search</span>
                            </div>
                            <h3 class="font-display text-3xl font-extrabold text-on-surface"><?php echo number_format($totalFoundStats ?? 0); ?></h3>
                            <p class="text-on-surface-variant font-label-caps text-xs">Items Found</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- How It Works Section -->
        <div class="py-8 mb-8">
            <h3 class="text-center font-display font-bold text-on-surface mb-8 text-xl">How Foundly Works</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="flex flex-col items-center text-center px-4">
                    <div class="w-16 h-16 bg-primary/10 text-primary rounded-full flex items-center justify-center mb-4 shadow-sm">
                        <span class="material-symbols-outlined text-3xl">add_a_photo</span>
                    </div>
                    <h4 class="font-bold text-on-surface mb-2">1. Post an Item</h4>
                    <p class="text-sm text-on-surface-variant">Lost or found something? Snap a photo and post it to our community feed.</p>
                </div>
                <div class="flex flex-col items-center text-center px-4 relative">
                    <!-- Connector Line -->
                    <div class="hidden md:block absolute top-8 -left-[20%] w-[40%] h-[2px] bg-outline-variant/30"></div>
                    <div class="w-16 h-16 bg-secondary/10 text-secondary rounded-full flex items-center justify-center mb-4 shadow-sm">
                        <span class="material-symbols-outlined text-3xl">travel_explore</span>
                    </div>
                    <h4 class="font-bold text-on-surface mb-2">2. Search & Match</h4>
                    <p class="text-sm text-on-surface-variant">Browse the discovery feed or use our search to find matching items.</p>
                </div>
                <div class="flex flex-col items-center text-center px-4 relative">
                    <!-- Connector Line -->
                    <div class="hidden md:block absolute top-8 -left-[20%] w-[40%] h-[2px] bg-outline-variant/30"></div>
                    <div class="w-16 h-16 bg-success/10 text-success rounded-full flex items-center justify-center mb-4 shadow-sm">
                        <span class="material-symbols-outlined text-3xl">handshake</span>
                    </div>
                    <h4 class="font-bold text-on-surface mb-2">3. Reunite</h4>
                    <p class="text-sm text-on-surface-variant">Connect safely through our platform and return the item to its owner.</p>
                </div>
            </div>
        </div>
        
        <!-- Proceed to Discovery Feed Button -->
        <div class="flex justify-center mt-12 pb-8">
            <a href="index.php?action=items" class="group relative inline-flex items-center justify-center px-8 py-4 font-bold text-white transition-all duration-200 bg-primary font-display rounded-full hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary shadow-xl hover:shadow-primary/30 hover:-translate-y-1">
                <span class="mr-3">Proceed to Discovery Feed</span>
                <span class="material-symbols-outlined transition-transform group-hover:translate-x-1">arrow_forward</span>
            </a>
        </div>
    </section>
</div>

<!-- ===== FEATURES SECTION ===== -->
<section class="py-16 md:py-20 bg-surface">
    <div class="max-w-container mx-auto px-margin-mobile md:px-margin-desktop">
        <div class="text-center mb-12">
            <span class="inline-block px-4 py-1 bg-secondary-container/30 text-secondary-container rounded-full text-sm font-medium mb-3">
                Why Choose Us?
            </span>
            <h2 class="font-display text-3xl md:text-4xl font-bold text-primary">Key Features</h2>
            <p class="text-on-surface-variant mt-4 max-w-2xl mx-auto">Everything you need to successfully return or claim a lost item.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Feature 1 -->
            <div class="glass-card p-6 hover:-translate-y-1 transition-all duration-300">
                <div class="w-12 h-12 bg-secondary-container/20 rounded-xl flex items-center justify-center mb-4 text-secondary">
                    <span class="material-symbols-outlined text-2xl">chat_bubble</span>
                </div>
                <h3 class="font-display text-lg font-bold text-on-surface mb-2">Secure Messaging</h3>
                <p class="text-on-surface-variant text-sm">Communicate privately to verify details before meeting up or returning items.</p>
            </div>
            
            <!-- Feature 2 -->
            <div class="glass-card p-6 hover:-translate-y-1 transition-all duration-300">
                <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center mb-4 text-primary">
                    <span class="material-symbols-outlined text-2xl">search_insights</span>
                </div>
                <h3 class="font-display text-lg font-bold text-on-surface mb-2">Smart Search</h3>
                <p class="text-on-surface-variant text-sm">Easily filter and search through categories to find exactly what you are looking for.</p>
            </div>
            
            <!-- Feature 3 -->
            <div class="glass-card p-6 hover:-translate-y-1 transition-all duration-300">
                <div class="w-12 h-12 bg-success/10 rounded-xl flex items-center justify-center mb-4 text-success">
                    <span class="material-symbols-outlined text-2xl">group</span>
                </div>
                <h3 class="font-display text-lg font-bold text-on-surface mb-2">Community Driven</h3>
                <p class="text-on-surface-variant text-sm">Powered by honest people helping each other get their lost belongings back.</p>
            </div>
        </div>
    </div>
</section>


<!-- ===== FAQ SECTION ===== -->
<section class="py-16 md:py-20 bg-surface">
    <div class="max-w-container mx-auto px-margin-mobile md:px-margin-desktop max-w-3xl">
        <div class="text-center mb-12">
            <h2 class="font-display text-3xl md:text-4xl font-bold text-primary">Frequently Asked Questions</h2>
        </div>
        
        <div class="space-y-4">
            <!-- FAQ 1 -->
            <div class="glass-card p-6">
                <h3 class="font-display text-lg font-bold text-primary mb-2 flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary">help</span>
                    Is Foundly free to use?
                </h3>
                <p class="text-on-surface-variant text-sm">Yes! Foundly is completely free to use. Our goal is to simply connect people who have found items with those who have lost them.</p>
            </div>
            
            <!-- FAQ 2 -->
            <div class="glass-card p-6">
                <h3 class="font-display text-lg font-bold text-primary mb-2 flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary">help</span>
                    How do I claim a lost item?
                </h3>
                <p class="text-on-surface-variant text-sm">If you see an item that belongs to you, log in to your account and send a secure message to the finder to verify the details. Once verified, you can arrange a safe meetup.</p>
            </div>
            
            <!-- FAQ 3 -->
            <div class="glass-card p-6">
                <h3 class="font-display text-lg font-bold text-primary mb-2 flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary">help</span>
                    What if I don't see my lost item?
                </h3>
                <p class="text-on-surface-variant text-sm">You can post a "Lost" report! Upload details and a description so if someone finds it, they can reach out to you.</p>
            </div>
        </div>
    </div>
</section>

<!-- ===== CTA SECTION ===== -->
<section class="py-16 bg-primary text-on-primary">
    <div class="max-w-container mx-auto px-margin-mobile md:px-margin-desktop text-center">
        <?php if(isset($_SESSION['user_id'])): ?>
            <h2 class="font-display text-3xl md:text-4xl font-bold mb-4">Ready to Help Someone Today?</h2>
            <p class="text-white/80 max-w-2xl mx-auto mb-8">
                Report a found item or check the discovery feed to help reunite belongings with their owners.
            </p>
            <a href="index.php?action=items_create" 
               class="inline-block px-8 py-3 bg-secondary-container text-on-secondary-container rounded-full font-medium hover:bg-secondary-container/90 transition-all shadow-lg hover:shadow-xl active:scale-95">
                Post an Item
            </a>
        <?php else: ?>
            <h2 class="font-display text-3xl md:text-4xl font-bold mb-4">Ready to Help?</h2>
            <p class="text-white/80 max-w-2xl mx-auto mb-8">
                Join the Foundly community today and help reunite lost items with their owners.
            </p>
            <a href="index.php?action=register" 
               class="inline-block px-8 py-3 bg-secondary-container text-on-secondary-container rounded-full font-medium hover:bg-secondary-container/90 transition-all shadow-lg hover:shadow-xl active:scale-95">
                Sign Up
            </a>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
