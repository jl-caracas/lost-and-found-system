<?php
/**
 * views/items/index.php – Discovery Feed with avatars
 */
include __DIR__ . '/../../includes/header.php';
?>

<!-- Simplified Discovery Feed Header -->
<div class="relative pt-4 md:pt-8 pb-4 border-b border-outline-variant/20 bg-surface">
    <section class="max-w-container mx-auto px-margin-mobile md:px-margin-desktop relative z-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-3xl font-extrabold text-primary">Discovery Feed</h1>
            <p class="text-on-surface-variant font-body text-sm mt-1">Browse, search, and help reconnect items with their owners.</p>
        </div>
        
        <!-- Main Search Bar -->
        <div class="w-full md:w-80 flex flex-col items-end">
            <form method="GET" action="index.php" class="w-full relative">
                <input type="hidden" name="action" value="items">
                
                <div class="relative flex items-center shadow-sm rounded-full">
                    <span class="material-symbols-outlined absolute left-4 text-on-surface-variant text-[20px]">search</span>
                    <input type="text" 
                           name="search" 
                           placeholder="Search items..." 
                           class="w-full bg-surface-container backdrop-blur-md border border-outline-variant/50 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-full pl-11 pr-4 py-2 font-body transition-all text-sm"
                           value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                </div>
            </form>
            <?php if(!empty($_GET['search'])): ?>
            <div class="flex items-center gap-2 mt-2">
                <span class="text-on-surface-variant text-xs">Search results for: <span class="font-bold text-primary">"<?php echo htmlspecialchars($_GET['search']); ?>"</span></span>
                <a href="index.php?action=items<?php echo isset($_GET['category_id']) ? '&category_id='.$_GET['category_id'] : ''; ?><?php echo isset($_GET['status']) ? '&status='.urlencode($_GET['status']) : ''; ?>" class="text-[10px] bg-outline-variant/30 hover:bg-outline-variant/50 px-2 py-0.5 rounded text-on-surface-variant transition-colors">Clear</a>
            </div>
            <?php endif; ?>
        </div>
    </section>
</div>

<section class="max-w-container mx-auto px-margin-mobile md:px-margin-desktop relative z-10">
        
        <!-- Filters Section -->
        <div class="flex flex-col gap-4 mt-8">
            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-6 bg-surface-container/50 p-4 rounded-2xl border border-outline-variant/30 backdrop-blur-sm">
                <!-- Category Chips -->
                <div class="flex flex-wrap items-center gap-3">
                    <a href="index.php?action=items<?php echo isset($_GET['status']) ? '&status='.urlencode($_GET['status']) : ''; ?><?php echo isset($_GET['search']) ? '&search='.urlencode($_GET['search']) : ''; ?>" 
                       class="px-5 py-2.5 rounded-full <?php echo empty($_GET['category_id']) ? 'bg-primary text-on-primary shadow-md shadow-primary/20' : 'bg-surface text-on-surface-variant hover:bg-surface-container-highest border border-outline-variant/30'; ?> font-label-caps whitespace-nowrap active:scale-95 transition-all category-chip">
                        All Categories
                    </a>
                    <?php 
                    if(isset($categories) && mysqli_num_rows($categories) > 0):
                        mysqli_data_seek($categories, 0);
                        while($cat = mysqli_fetch_assoc($categories)):
                            $active = (isset($_GET['category_id']) && $_GET['category_id'] == $cat['id']);
                    ?>
                        <a href="index.php?action=items&category_id=<?php echo $cat['id']; ?><?php echo isset($_GET['status']) ? '&status='.urlencode($_GET['status']) : ''; ?><?php echo isset($_GET['search']) ? '&search='.urlencode($_GET['search']) : ''; ?>" 
                           class="px-5 py-2.5 rounded-full <?php echo $active ? 'bg-primary text-on-primary shadow-md shadow-primary/20' : 'bg-surface text-on-surface-variant hover:bg-surface-container-highest border border-outline-variant/30'; ?> font-label-caps whitespace-nowrap active:scale-95 transition-all category-chip">
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </a>
                    <?php 
                        endwhile; 
                    endif; 
                    ?>
                </div>
                
                <div class="w-full h-px bg-outline-variant/30 xl:hidden"></div>
                
                <!-- Status Filter & Sort -->
                <div class="flex flex-wrap items-center gap-3 w-full xl:w-auto">
                    <span class="text-sm font-medium text-on-surface-variant font-label-caps mr-2 hidden sm:inline">Status:</span>
                    <a href="index.php?action=items<?php echo isset($_GET['category_id']) ? '&category_id='.$_GET['category_id'] : ''; ?><?php echo isset($_GET['search']) ? '&search='.urlencode($_GET['search']) : ''; ?>" 
                       class="px-4 py-2 rounded-full text-sm <?php echo empty($_GET['status']) ? 'bg-on-surface text-surface shadow-md' : 'bg-surface text-on-surface-variant hover:bg-surface-container-highest border border-outline-variant/30'; ?> font-label-caps whitespace-nowrap active:scale-95 transition-all">
                        All
                    </a>
                    <a href="index.php?action=items&status=lost<?php echo isset($_GET['category_id']) ? '&category_id='.$_GET['category_id'] : ''; ?><?php echo isset($_GET['search']) ? '&search='.urlencode($_GET['search']) : ''; ?>" 
                       class="px-4 py-2 rounded-full text-sm <?php echo (isset($_GET['status']) && $_GET['status'] == 'lost') ? 'bg-error text-white shadow-md shadow-error/20' : 'bg-surface text-on-surface-variant hover:bg-surface-container-highest border border-outline-variant/30'; ?> font-label-caps whitespace-nowrap active:scale-95 transition-all">
                        Lost
                    </a>
                    <a href="index.php?action=items&status=found<?php echo isset($_GET['category_id']) ? '&category_id='.$_GET['category_id'] : ''; ?><?php echo isset($_GET['search']) ? '&search='.urlencode($_GET['search']) : ''; ?>" 
                       class="px-4 py-2 rounded-full text-sm <?php echo (isset($_GET['status']) && $_GET['status'] == 'found') ? 'bg-secondary text-white shadow-md shadow-secondary/20' : 'bg-surface text-on-surface-variant hover:bg-surface-container-highest border border-outline-variant/30'; ?> font-label-caps whitespace-nowrap active:scale-95 transition-all">
                        Found
                    </a>
                    <a href="index.php?action=items&status=claimed<?php echo isset($_GET['category_id']) ? '&category_id='.$_GET['category_id'] : ''; ?><?php echo isset($_GET['search']) ? '&search='.urlencode($_GET['search']) : ''; ?>" 
                       class="px-4 py-2 rounded-full text-sm <?php echo (isset($_GET['status']) && $_GET['status'] == 'claimed') ? 'bg-success text-white shadow-md shadow-success/20' : 'bg-surface text-on-surface-variant hover:bg-surface-container-highest border border-outline-variant/30'; ?> font-label-caps whitespace-nowrap active:scale-95 transition-all">
                        Claimed
                    </a>
                    
                    <!-- Sort Dropdown -->
                    <div class="ml-auto flex items-center pl-4 border-l border-outline-variant/30">
                        <form action="index.php" method="GET" class="flex items-center gap-2 m-0">
                            <input type="hidden" name="action" value="items">
                            <?php if(isset($_GET['category_id'])) echo '<input type="hidden" name="category_id" value="'.(int)$_GET['category_id'].'">'; ?>
                            <?php if(isset($_GET['status'])) echo '<input type="hidden" name="status" value="'.htmlspecialchars($_GET['status']).'">'; ?>
                            <?php if(isset($_GET['search'])) echo '<input type="hidden" name="search" value="'.htmlspecialchars($_GET['search']).'">'; ?>
                            
                            <div class="relative flex items-center group">
                                <span class="material-symbols-outlined absolute left-3 text-on-surface-variant text-[18px] group-hover:text-primary transition-colors pointer-events-none">sort</span>
                                <select name="sort_by" onchange="this.form.submit()" class="pl-9 pr-8 py-2 rounded-full bg-white text-sm font-medium text-on-surface-variant border border-outline-variant/30 hover:border-primary/50 focus:ring-2 focus:ring-primary/20 focus:border-primary appearance-none cursor-pointer transition-all shadow-sm">
                                    <option value="newest" <?php echo (!isset($_GET['sort_by']) || $_GET['sort_by'] == 'newest') ? 'selected' : ''; ?>>Newest First</option>
                                    <option value="oldest" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'oldest') ? 'selected' : ''; ?>>Oldest First</option>
                                    <option value="name_asc" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'name_asc') ? 'selected' : ''; ?>>Name (A-Z)</option>
                                    <option value="name_desc" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'name_desc') ? 'selected' : ''; ?>>Name (Z-A)</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Map View Section -->
<section class="max-w-container mx-auto px-margin-mobile md:px-margin-desktop mb-12">
    <div class="glass-card rounded-2xl p-4 md:p-6 card-shadow border border-outline-variant/30">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-display text-headline-md font-bold text-on-surface">Map View</h2>
            <span class="text-sm text-on-surface-variant flex items-center gap-1"><span class="material-symbols-outlined text-[18px]">info</span> Showing recent items</span>
        </div>
        <div id="discoveryMap" class="h-80 w-full rounded-xl overflow-hidden relative bg-surface-container shadow-inner border border-outline-variant/20 z-0"></div>
    </div>
</section>

<!-- Items Grid -->
<section id="items-feed" class="max-w-container mx-auto px-margin-mobile md:px-margin-desktop">
    <?php if(isset($_SESSION['success'])): ?>
        <div class="bg-success/10 text-success p-4 rounded-xl mb-4 alert-auto-dismiss">✅ <?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if(isset($_SESSION['error'])): ?>
        <div class="bg-error-container text-on-error-container p-4 rounded-xl mb-4 alert-auto-dismiss">❌ <?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <?php if(mysqli_num_rows($items) == 0): ?>
        <div class="text-center py-20 px-6 bg-gradient-to-br from-surface-container to-surface-container-high rounded-3xl border border-outline-variant/30 shadow-inner max-w-2xl mx-auto">
            <div class="w-24 h-24 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6">
                <span class="material-symbols-outlined text-5xl text-primary">manage_search</span>
            </div>
            <h3 class="font-display text-headline-lg font-bold text-on-surface mb-3">No items found</h3>
            <p class="text-on-surface-variant text-lg mb-8 max-w-md mx-auto">We couldn't find any items matching your current filters. Be the first to report something!</p>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="index.php?action=items_create" class="inline-flex items-center gap-2 bg-primary text-on-primary px-8 py-4 rounded-full font-bold hover:shadow-lg hover:shadow-primary/30 hover:-translate-y-1 transition-all">
                    <span class="material-symbols-outlined">add_circle</span> Post a New Item
                </a>
            <?php else: ?>
                <a href="index.php?action=login" class="inline-flex items-center gap-2 bg-primary text-on-primary px-8 py-4 rounded-full font-bold hover:shadow-lg hover:shadow-primary/30 hover:-translate-y-1 transition-all">
                    Login to Post
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-gutter">
            <?php while($item = mysqli_fetch_assoc($items)): ?>
                <div class="relative block group bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm border border-outline-variant/30 transition-all duration-500 hover:shadow-2xl hover:shadow-primary/20 hover:-translate-y-2 hover:border-primary/30">
                    
                    <!-- Clickable overlay for the entire card -->
                    <a href="index.php?action=items_view&id=<?php echo $item['id']; ?>" class="absolute inset-0 z-10"></a>
                    
                    <div class="aspect-square relative overflow-hidden bg-surface-variant">
                        <?php if(!empty($item['photo'])): ?>
                            <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
                                 src="/LF-web2/<?php echo htmlspecialchars($item['photo']); ?>" 
                                 alt="<?php echo htmlspecialchars($item['item_name']); ?>"
                                 onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22><rect fill=%22%23f0f0f0%22 width=%22200%22 height=%22200%22/><text x=%2250%%22 y=%2250%%22 font-size=%2216%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22%23999%22>No Image</text></svg>'">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-outline bg-surface-variant">
                                <span class="material-symbols-outlined text-6xl">image_not_supported</span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="absolute top-4 right-4 z-10">
                            <span class="font-label-caps px-4 py-2 rounded-full shadow-lg backdrop-blur-md border border-white/20 <?php echo ($item['status'] == 'lost') ? 'bg-error text-white' : 'bg-gradient-to-r from-secondary to-primary text-white'; ?>">
                                <?php echo strtoupper($item['status']); ?>
                            </span>
                        </div>
                        
                        <?php if($item['status_label'] == 'claimed'): ?>
                            <div class="absolute bottom-4 left-4 bg-success/90 text-white px-3 py-1 rounded-full text-xs font-bold z-10">
                                ✓ Claimed
                            </div>
                        <?php endif; ?>
                        
                        <?php if($item['status'] == 'lost' && !empty($item['reward'])): ?>
                            <div class="absolute bottom-4 right-4 bg-secondary/90 text-on-secondary px-3 py-1 rounded-full text-xs font-bold z-10 shadow-md backdrop-blur-sm flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">stars</span>
                                Reward
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="p-5 relative pointer-events-none">
                        <p class="font-label-caps text-on-surface-variant mb-1"><?php echo htmlspecialchars($item['category_name']); ?></p>
                        <h3 class="font-display font-semibold text-on-surface mb-2 text-lg"><?php echo htmlspecialchars($item['item_name']); ?></h3>
                        
                        <div class="flex items-center gap-1.5 text-outline group-hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[18px]">location_on</span>
                            <span class="font-label-caps truncate">
                                <?php 
                                echo htmlspecialchars($item['location']); 
                                if (!empty($item['specific_location'])) {
                                    echo ' - ' . htmlspecialchars($item['specific_location']);
                                }
                                ?>
                            </span>
                        </div>
                        
                        <!-- Reporter with avatar -->
                        <div class="flex items-center gap-2 mt-3 text-xs text-on-surface-variant">
                            <?php if (!empty($item['reporter_picture'])): ?>
                                <img src="/LF-web2/uploads/profiles/<?php echo htmlspecialchars($item['reporter_picture']); ?>" alt="Avatar" class="w-6 h-6 rounded-full object-cover flex-shrink-0">
                            <?php else: ?>
                                <div class="w-6 h-6 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-[10px] flex-shrink-0">
                                    <?php echo strtoupper(substr($item['reporter_name'] ?? '?', 0, 1)); ?>
                                </div>
                            <?php endif; ?>
                            <span>By <?php echo htmlspecialchars($item['reporter_name'] ?? 'Anonymous'); ?></span>
                            <span class="w-1 h-1 rounded-full bg-outline-variant flex-shrink-0"></span>
                            <span class="whitespace-nowrap flex-shrink-0"><?php echo date('M d, Y, g:i A', strtotime($item['date_reported'])); ?></span>
                        </div>
                        
                        <div class="pointer-events-auto">
                            <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != $item['reported_by'] && $item['status_label'] != 'claimed'): ?>
                                <div class="mt-3 flex gap-2 relative z-20">
                                    <a href="index.php?action=chat&item_id=<?php echo $item['id']; ?>" 
                                       class="text-xs bg-surface-variant hover:bg-outline-variant px-3 py-1.5 rounded-full transition-colors flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px]">chat</span> Contact
                                    </a>
                                    <?php if($item['status'] == 'found'): ?>
                                        <a href="index.php?action=claims_create&item_id=<?php echo $item['id']; ?>" 
                                           class="text-xs bg-primary text-on-primary hover:bg-primary/90 px-3 py-1.5 rounded-full transition-colors flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[16px]">handshake</span> Claim
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php elseif($item['status_label'] == 'claimed'): ?>
                                <div class="mt-3 text-xs text-on-surface-variant/70 italic">This item has been claimed</div>
                            <?php endif; ?>
                            
                            <?php if((isset($_SESSION['role']) && $_SESSION['role'] == 'admin') || (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $item['reported_by'])): ?>
                                <div class="mt-3 flex gap-2 border-t border-outline-variant pt-3 relative z-20">
                                    <a href="index.php?action=items_edit&id=<?php echo $item['id']; ?>" 
                                       class="text-xs bg-warning/20 text-warning hover:bg-warning/30 px-3 py-1 rounded-full transition-colors">Edit</a>
                                    <a href="index.php?action=items_delete&id=<?php echo $item['id']; ?>" 
                                       onclick="return confirm('Delete this item?')"
                                       class="text-xs bg-error-container text-on-error-container hover:bg-error-container/70 px-3 py-1 rounded-full transition-colors delete-confirm">Delete</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <?php if($totalPages > 1): ?>
        <div class="flex justify-center gap-2 mt-8">
            <?php for($i=1; $i<=$totalPages; $i++): ?>
                <a href="index.php?action=items&page=<?php echo $i; ?>&status=<?php echo urlencode($status); ?>&search=<?php echo urlencode($search); ?>&category_id=<?php echo $category_id; ?>&sort_by=<?php echo urlencode($_GET['sort_by'] ?? 'newest'); ?>" 
                   class="px-4 py-2 rounded-lg <?php echo $i==$page ? 'bg-primary text-on-primary' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-highest'; ?> transition-colors">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</section>

<!-- Leaflet JS & CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const defaultLocation = [14.4795, 121.0494]; // PUP Taguig
    
    // Metro Manila Bounds
    const southWest = L.latLng(14.3344, 120.8950);
    const northEast = L.latLng(14.7766, 121.1354);
    const bounds = L.latLngBounds(southWest, northEast);

    const map = L.map('discoveryMap', {
        maxBounds: bounds,
        maxBoundsViscosity: 1.0,
        minZoom: 11
    }).setView(defaultLocation, 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Fetch items from API
    const categoryId = "<?php echo isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0; ?>";
    const statusStr = "<?php echo isset($_GET['status']) ? htmlspecialchars($_GET['status']) : ''; ?>";
    const searchStr = "<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>";
    
    fetch(`index.php?action=items_api&category_id=${categoryId}&status=${statusStr}&search=${encodeURIComponent(searchStr)}`)
        .then(res => res.json())
        .then(items => {
            if (items.length > 0) {
                const markers = L.featureGroup();
                items.forEach(item => {
                    if (item.latitude && item.longitude) {
                        const iconHtml = item.status === 'lost' ? '🔴' : '🟢';
                        const popupContent = `
                            <div class="text-center w-48">
                                ${item.photo ? `<img src="/LF-web2/${item.photo}" class="w-full h-24 object-cover rounded-lg mb-2">` : ''}
                                <h3 class="font-bold mb-1">${item.item_name}</h3>
                                <p class="text-xs text-gray-600 mb-2">${item.location}</p>
                                <a href="${item.url}" class="bg-primary text-white text-xs px-3 py-1.5 rounded-full inline-block">View Details</a>
                            </div>
                        `;
                        const marker = L.marker([item.latitude, item.longitude], {
                            title: item.item_name
                        }).bindPopup(popupContent);
                        
                        markers.addLayer(marker);
                    }
                });
                
                map.addLayer(markers);
                
                // Adjust map bounds to show all markers if there are any
                if (markers.getLayers().length > 0) {
                    map.fitBounds(markers.getBounds(), {padding: [50, 50]});
                }
            }
        })
        .catch(err => console.error("Error loading map points:", err));
});
</script>

<?php if(!empty($_GET['search'])): ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const feed = document.getElementById('items-feed');
    if(feed) {
        // Wait a tiny bit for the page layout and map to stabilize
        setTimeout(() => {
            const offset = 80;
            const bodyRect = document.body.getBoundingClientRect().top;
            const elementRect = feed.getBoundingClientRect().top;
            const elementPosition = elementRect - bodyRect;
            const offsetPosition = elementPosition - offset;

            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });
        }, 150);
    }
});
</script>
<?php endif; ?>

<?php include __DIR__ . '/../../includes/footer.php'; ?>

