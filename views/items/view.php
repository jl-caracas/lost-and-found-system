<?php
/**
 * views/items/view.php – Item Details Page with avatars
 */
include __DIR__ . '/../../includes/header.php';

if (!isset($item) || !$item) {
    header("Location: index.php?action=items");
    exit();
}
?>

<div class="max-w-container mx-auto px-margin-mobile md:px-margin-desktop">
    <button type="button" onclick="history.back()" class="flex items-center gap-2 text-on-surface-variant hover:text-primary transition-colors font-medium text-sm mb-6 group w-fit">
        <span class="material-symbols-outlined text-xl group-hover:-translate-x-1 transition-transform">arrow_back</span>
        Back
    </button>
    <!-- Breadcrumb & Status -->
    <div class="flex items-center justify-between mb-stack-lg">
        <div class="flex items-center gap-2 text-on-surface-variant">
            <a href="index.php?action=items" class="font-label-caps text-label-caps hover:text-primary transition-colors">
                DISCOVERY FEED
            </a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <span class="font-label-caps text-label-caps text-primary">ITEM DETAILS</span>
        </div>
        
        <div class="<?php echo ($item['status'] == 'lost') ? 'bg-error-container text-on-error-container' : 'bg-secondary-container text-on-secondary-container'; ?> px-4 py-1 rounded-full font-label-caps text-label-caps flex items-center gap-1">
            <span class="w-1.5 h-1.5 <?php echo ($item['status'] == 'lost') ? 'bg-error' : 'bg-secondary'; ?> rounded-full animate-pulse"></span>
            <?php echo strtoupper($item['status']); ?>
            <?php if($item['status_label'] == 'claimed'): ?>
                <span class="ml-1 bg-success/90 text-white px-2 py-0.5 rounded-full text-[10px]">✓ CLAIMED</span>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        
        <!-- LEFT COLUMN: Gallery -->
        <div class="lg:col-span-7 space-y-stack-md">
            <div class="aspect-square w-full rounded-xl overflow-hidden bg-surface-container shadow-sm group relative">
                <?php if(!empty($item['photo'])): ?>
                    <img id="mainImage" 
                         src="/LF-web2/<?php echo htmlspecialchars($item['photo']); ?>" 
                         alt="<?php echo htmlspecialchars($item['item_name']); ?>" 
                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                         onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22><rect fill=%22%23f0f0f0%22 width=%22200%22 height=%22200%22/><text x=%2250%%22 y=%2250%%22 font-size=%2216%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22%23999%22>No Image</text></svg>'">
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center bg-surface-variant text-outline">
                        <span class="material-symbols-outlined text-8xl">image_not_supported</span>
                    </div>
                <?php endif; ?>
                
                <button onclick="openImageModal()" 
                        class="absolute bottom-6 right-6 bg-surface/90 backdrop-blur-sm p-3 rounded-full shadow-lg active:scale-90 transition-transform">
                    <span class="material-symbols-outlined">zoom_in</span>
                </button>
            </div>

            <div class="flex gap-4 overflow-x-auto hide-scrollbar pb-2 mt-6">
                <?php if(!empty($item['photo'])): ?>
                    <button class="flex-shrink-0 w-24 h-24 rounded-lg overflow-hidden border-2 border-primary" 
                            onclick="document.getElementById('mainImage').src = this.querySelector('img').src">
                        <img src="/LF-web2/<?php echo htmlspecialchars($item['photo']); ?>" 
                             alt="Main" 
                             class="w-full h-full object-cover">
                    </button>
                <?php endif; ?>
                <?php if(!empty($additionalPhotos)): ?>
                    <?php foreach($additionalPhotos as $photo): ?>
                        <button class="flex-shrink-0 w-24 h-24 rounded-lg overflow-hidden opacity-60 hover:opacity-100 transition-opacity"
                                onclick="document.getElementById('mainImage').src = this.querySelector('img').src">
                            <img src="/LF-web2/<?php echo htmlspecialchars($photo['photo_path']); ?>" 
                                 alt="Thumbnail" 
                                 class="w-full h-full object-cover">
                        </button>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- RIGHT COLUMN: Item Information -->
        <div class="lg:col-span-5 space-y-stack-lg">
            <header class="space-y-stack-sm">
                <h1 class="font-display text-3xl md:text-5xl font-extrabold text-primary leading-tight">
                    <?php echo htmlspecialchars($item['item_name']); ?>
                </h1>
                <p class="text-on-surface-variant flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary">location_on</span>
                    <?php 
                    echo htmlspecialchars($item['location']); 
                    if (!empty($item['specific_location'])) {
                        echo ' - ' . htmlspecialchars($item['specific_location']);
                    }
                    ?>
                </p>
                <!-- Reporter with avatar -->
                <div class="flex items-center gap-2 text-sm text-on-surface-variant">
                    <?php if(!empty($item['reported_by'])): ?>
                        <a href="index.php?action=public_profile&id=<?php echo htmlspecialchars($item['reported_by']); ?>" class="flex items-center gap-2 group transition-colors">
                            <?php if(!empty($item['reporter_picture'])): ?>
                                <img src="/LF-web2/uploads/profiles/<?php echo htmlspecialchars($item['reporter_picture']); ?>" alt="Avatar" class="w-8 h-8 rounded-full object-cover border border-outline-variant group-hover:border-primary transition-colors">
                            <?php else: ?>
                                <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-sm flex-shrink-0 group-hover:bg-primary/20 transition-colors">
                                    <?php echo strtoupper(substr($item['reporter_name'] ?? '?', 0, 1)); ?>
                                </div>
                            <?php endif; ?>
                            <span class="group-hover:text-on-surface transition-colors">Reported by <strong class="group-hover:text-primary group-hover:underline transition-colors text-on-surface"><?php echo htmlspecialchars($item['reporter_name']); ?></strong></span>
                        </a>
                    <?php else: ?>
                        <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-sm flex-shrink-0">
                            <?php echo strtoupper(substr($item['reporter_name'] ?? '?', 0, 1)); ?>
                        </div>
                        <span>Reported by <strong>Anonymous</strong></span>
                    <?php endif; ?>
                    
                    <span class="w-1 h-1 rounded-full bg-outline-variant"></span>
                    <span><?php echo date('F j, Y, g:i A', strtotime($item['date_reported'])); ?></span>
                </div>

                <?php if ($item['status'] == 'lost' && !empty($item['reward'])): ?>
                    <div class="mt-4 bg-secondary/10 border border-secondary/20 rounded-xl p-4 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-secondary/20 flex items-center justify-center text-secondary flex-shrink-0">
                            <span class="material-symbols-outlined text-2xl">stars</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-secondary uppercase tracking-wider mb-1">Reward Offered</h3>
                            <p class="text-lg font-bold text-on-surface"><?php echo htmlspecialchars($item['reward']); ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </header>

            <div class="grid grid-cols-2 gap-4">
                <div class="bg-surface-container-low p-4 rounded-xl">
                    <p class="font-label-caps text-label-caps text-on-surface-variant mb-1">CATEGORY</p>
                    <p class="font-headline-md text-body-lg font-semibold"><?php echo htmlspecialchars($item['category_name']); ?></p>
                </div>
                <div class="bg-surface-container-low p-4 rounded-xl">
                    <p class="font-label-caps text-label-caps text-on-surface-variant mb-1">STATUS</p>
                    <p class="font-headline-md text-body-lg font-semibold <?php echo ($item['status'] == 'lost') ? 'text-error' : 'text-secondary'; ?>">
                        <?php echo ucfirst($item['status']); ?>
                    </p>
                </div>
            </div>

            <article class="bg-surface-container-low p-4 rounded-xl">
                <p class="font-label-caps text-label-caps text-on-surface-variant mb-1 uppercase tracking-wider">DESCRIPTION</p>
                <div class="font-headline-md text-body-lg font-semibold leading-relaxed">
                    <?php echo nl2br(htmlspecialchars($item['description'] ?? 'No description provided.')); ?>
                </div>
            </article>

            <div class="space-y-2">
                <h3 class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider">LAST SEEN LOCATION</h3>
                <div id="map" class="w-full h-48 rounded-xl overflow-hidden bg-surface-container-highest relative border border-outline-variant/20 shadow-inner z-0">
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-outline-variant/30 flex flex-col gap-4">
                <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != $item['reported_by'] && $item['status_label'] != 'claimed'): ?>
                    <a href="index.php?action=chat&item_id=<?php echo $item['id']; ?>" 
                       class="w-full bg-primary text-on-primary py-4 rounded-full font-headline-md text-body-lg hover:opacity-90 transition-all shadow-md active:scale-95 flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">mail</span>
                        Contact Owner
                    </a>

                    <!-- Quick Replies -->
                    <?php if($item['status'] == 'lost'): ?>
                        <div class="flex gap-2 justify-center flex-wrap mt-1 mb-2">
                            <a href="index.php?action=chat&item_id=<?php echo $item['id']; ?>&msg=I%20might%20have%20the%20item%20you%20lost" class="bg-primary/10 text-primary px-4 py-2 rounded-full text-sm font-medium hover:bg-primary/20 transition-colors border border-primary/20">I might have this</a>
                            <a href="index.php?action=chat&item_id=<?php echo $item['id']; ?>&msg=Is%20this%20still%20missing?" class="bg-primary/10 text-primary px-4 py-2 rounded-full text-sm font-medium hover:bg-primary/20 transition-colors border border-primary/20">Is this still missing?</a>
                        </div>
                    <?php elseif($item['status'] == 'found'): ?>
                        <div class="flex gap-2 justify-center flex-wrap mt-1 mb-2">
                            <a href="index.php?action=chat&item_id=<?php echo $item['id']; ?>&msg=I%20think%20this%20is%20mine!" class="bg-primary/10 text-primary px-4 py-2 rounded-full text-sm font-medium hover:bg-primary/20 transition-colors border border-primary/20">I think this is mine!</a>
                            <a href="index.php?action=chat&item_id=<?php echo $item['id']; ?>&msg=Where%20was%20this%20found?" class="bg-primary/10 text-primary px-4 py-2 rounded-full text-sm font-medium hover:bg-primary/20 transition-colors border border-primary/20">Where was this found?</a>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($item['status'] == 'found'): ?>
                        <a href="index.php?action=claims_create&item_id=<?php echo $item['id']; ?>" 
                           class="w-full border-2 border-secondary text-secondary py-4 rounded-full font-headline-md text-body-lg hover:bg-secondary hover:text-on-secondary transition-all active:scale-95 flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">handshake</span>
                            Claim This Item
                        </a>
                    <?php endif; ?>
                <?php elseif($item['status_label'] == 'claimed'): ?>
                    <div class="w-full bg-success/10 text-success py-4 rounded-full font-headline-md text-body-lg flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">check_circle</span>
                        This item has been claimed
                    </div>
                <?php endif; ?>

                <button onclick="shareItem()" 
                        class="w-full border-2 border-primary text-primary py-4 rounded-full font-headline-md text-body-lg hover:bg-primary hover:text-on-secondary transition-all active:scale-95 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">share</span>
                    Share Listing
                </button>

                <?php if((isset($_SESSION['role']) && $_SESSION['role'] == 'admin') || (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $item['reported_by'])): ?>
                    <div class="flex gap-3 pt-2">
                        <?php if($item['status_label'] !== 'claimed'): ?>
                            <a href="index.php?action=items_edit&id=<?php echo $item['id']; ?>" 
                               class="flex-1 bg-warning/20 text-warning hover:bg-warning/30 py-3 rounded-xl text-sm font-medium text-center transition-colors">
                                Edit
                            </a>
                            <a href="index.php?action=items_mark_claimed&id=<?php echo $item['id']; ?>" 
                               class="flex-1 bg-success/20 text-success hover:bg-success/30 py-3 rounded-xl text-sm font-medium text-center transition-colors">
                                Mark Claimed
                            </a>
                        <?php endif; ?>
                        <a href="index.php?action=items_delete&id=<?php echo $item['id']; ?>" 
                           onclick="return confirm('Delete this item? This action cannot be undone.')"
                           class="flex-1 bg-error-container text-on-error-container hover:bg-error-container/70 py-3 rounded-xl text-sm font-medium text-center transition-colors delete-confirm">
                            Delete
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if(isset($similar_items) && mysqli_num_rows($similar_items) > 0): ?>
    <section class="mt-24 space-y-stack-lg">
        <div class="flex items-center justify-between border-b border-outline-variant/30 pb-4">
            <h2 class="font-display text-2xl md:text-4xl font-bold text-primary">Similar Items</h2>
            <a href="index.php?action=items&category_id=<?php echo $item['category_id']; ?>" 
               class="text-primary font-label-caps text-label-caps hover:underline decoration-2 underline-offset-4">
                VIEW ALL
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php while($sim = mysqli_fetch_assoc($similar_items)): ?>
                <a href="index.php?action=items_view&id=<?php echo $sim['id']; ?>" 
                   class="group bg-surface rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 clickable-card">
                    <div class="aspect-square overflow-hidden relative bg-surface-variant">
                        <?php if(!empty($sim['photo'])): ?>
                            <img src="/LF-web2/<?php echo htmlspecialchars($sim['photo']); ?>" 
                                 alt="<?php echo htmlspecialchars($sim['item_name']); ?>"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                 onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22><rect fill=%22%23f0f0f0%22 width=%22200%22 height=%22200%22/><text x=%2250%%22 y=%2250%%22 font-size=%2216%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22%23999%22>No Image</text></svg>'">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-outline">
                                <span class="material-symbols-outlined text-4xl">image_not_supported</span>
                            </div>
                        <?php endif; ?>
                        <div class="absolute top-3 left-3">
                            <span class="font-label-caps text-[10px] px-2 py-1 rounded-full <?php echo ($sim['status'] == 'lost') ? 'bg-error-container text-on-error-container' : 'bg-secondary-container text-on-secondary-container'; ?>">
                                <?php echo strtoupper($sim['status']); ?>
                            </span>
                        </div>
                    </div>
                    <div class="p-3">
                        <h4 class="font-headline-md text-sm text-primary group-hover:text-secondary transition-colors truncate">
                            <?php echo htmlspecialchars($sim['item_name']); ?>
                        </h4>
                        <p class="font-label-caps text-[10px] text-on-surface-variant flex items-center gap-1 truncate">
                            <span class="material-symbols-outlined text-[12px]">location_on</span>
                            <?php echo htmlspecialchars($sim['location']); ?>
                        </p>
                    </div>
                </a>
            <?php endwhile; ?>
        </div>
    </section>
    <?php endif; ?>
</div>

<!-- Image Zoom Modal -->
<div id="imageModal" class="fixed inset-0 bg-black/90 z-[999] hidden flex items-center justify-center cursor-zoom-out"
     onclick="closeImageModal()">
    <img id="modalImage" src="" alt="Zoomed Item" class="max-w-[95%] max-h-[95%] object-contain">
</div>

<script>
    function openImageModal() {
        const mainImg = document.getElementById('mainImage');
        const modalImg = document.getElementById('modalImage');
        modalImg.src = mainImg.src;
        document.getElementById('imageModal').classList.remove('hidden');
        document.getElementById('imageModal').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.getElementById('imageModal').classList.remove('flex');
        document.body.style.overflow = '';
    }

    function shareItem() {
        const url = window.location.href;
        const title = "<?php echo addslashes(htmlspecialchars($item['item_name'])); ?>";
        
        // Windows Share UI often rejects localhost or HTTP URLs, causing the "Try that again" error.
        if (navigator.share && window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1') {
            navigator.share({
                title: title,
                text: "Check out this lost/found item: " + title,
                url: url
            }).catch(() => {
                fallbackCopy(url);
            });
        } else {
            fallbackCopy(url);
        }
    }

    function fallbackCopy(url) {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(url).then(() => {
                alert('Link copied to clipboard!');
            }).catch(() => {
                prompt("Copy this link to share:", url);
            });
        } else {
            // Fallback for older browsers or non-secure contexts
            const textArea = document.createElement("textarea");
            textArea.value = url;
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
                alert('Link copied to clipboard!');
            } catch (err) {
                prompt("Copy this link to share:", url);
            }
            document.body.removeChild(textArea);
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    });

    function initViewMap() {
        const address = "<?php echo addslashes($item['location']); ?>";
        const defaultLocation = [14.4795, 121.0494]; // Fallback to PUP Taguig
        
        // Metro Manila Bounds
        const southWest = L.latLng(14.3344, 120.8950);
        const northEast = L.latLng(14.7766, 121.1354);
        const bounds = L.latLngBounds(southWest, northEast);

        const map = L.map('map', {
            maxBounds: bounds,
            maxBoundsViscosity: 1.0,
            minZoom: 11
        }).setView(defaultLocation, 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        fetch(`https://nominatim.openstreetmap.org/search?format=jsonv2&countrycodes=ph&viewbox=120.8950,14.7766,121.1354,14.3344&bounded=1&q=${encodeURIComponent(address)}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    const newLatLng = new L.LatLng(data[0].lat, data[0].lon);
                    map.setView(newLatLng, 16);
                    L.marker(newLatLng).addTo(map);
                } else {
                    L.marker(defaultLocation).addTo(map);
                }
            })
            .catch(() => {
                L.marker(defaultLocation).addTo(map);
            });
    }

    document.addEventListener("DOMContentLoaded", function() {
        initViewMap();
    });
</script>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>

