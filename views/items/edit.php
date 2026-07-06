<?php
/**
 * views/items/edit.php – Edit Item Form with absolute image paths
 */
include __DIR__ . '/../../includes/header.php';
?>

<div class="max-w-2xl mx-auto px-margin-mobile md:px-margin-desktop py-8">
    <button type="button" onclick="history.back()" class="flex items-center gap-2 text-on-surface-variant hover:text-primary transition-colors font-medium text-sm mb-6 group w-fit">
        <span class="material-symbols-outlined text-xl group-hover:-translate-x-1 transition-transform">arrow_back</span>
        Cancel Edit
    </button>
    <div class="glass-card rounded-2xl p-6 md:p-8 card-shadow">
        <h2 class="font-display text-headline-md mb-6">Edit Item</h2>

        <?php if(isset($error)): ?>
            <div class="bg-error-container text-on-error-container p-4 rounded-xl mb-4 text-sm">❌ <?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?action=items_update" enctype="multipart/form-data" class="space-y-5">
            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
            
            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">Status *</label>
                <select name="status" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none">
                    <option value="lost" <?php echo ($item['status'] == 'lost') ? 'selected' : ''; ?>>🔍 Lost</option>
                    <option value="found" <?php echo ($item['status'] == 'found') ? 'selected' : ''; ?>>📦 Found</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">Category *</label>
                <select name="category_id" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none" required>
                    <option value="">-- Select Category --</option>
                    <?php while($cat = mysqli_fetch_assoc($categories)): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo ($item['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">Item Name *</label>
                <input type="text" name="item_name" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none" 
                       value="<?php echo htmlspecialchars($item['item_name']); ?>" required>
            </div>

            <div id="rewardContainer" class="<?php echo ($item['status'] == 'found') ? 'hidden' : ''; ?>">
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5 flex items-center gap-2">
                    Reward (Optional) <span class="material-symbols-outlined text-[16px] text-secondary">stars</span>
                </label>
                <input type="text" name="reward" id="rewardInput" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none" 
                       value="<?php echo htmlspecialchars($item['reward'] ?? ''); ?>" placeholder="e.g., ₱500, Free Lunch, etc.">
            </div>

            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">Description</label>
                <textarea name="description" rows="4" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none"><?php echo htmlspecialchars($item['description']); ?></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">Location *</label>
                <input type="text" name="location" id="locationInput" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none" 
                       value="<?php echo htmlspecialchars($item['location']); ?>" required>
                <input type="hidden" name="latitude" id="latInput" value="<?php echo htmlspecialchars($item['latitude'] ?? ''); ?>">
                <input type="hidden" name="longitude" id="lngInput" value="<?php echo htmlspecialchars($item['longitude'] ?? ''); ?>">
            </div>

            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">Specific Location</label>
                <input type="text" name="specific_location" id="specificLocationInput" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none" 
                       value="<?php echo htmlspecialchars($item['specific_location'] ?? ''); ?>">
            </div>

            <div id="map" class="h-64 w-full rounded-xl overflow-hidden relative bg-surface-container shadow-inner border border-outline-variant/20 mb-5 z-0"></div>

            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">Date of Incident *</label>
                <input type="datetime-local" name="date_reported" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none" 
                       value="<?php echo date('Y-m-d\TH:i', strtotime($item['date_reported'])); ?>" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">Current Photo</label>
                <?php if(!empty($item['photo'])): ?>
                    <div class="mb-3">
                        <img src="/LF-web2/<?php echo htmlspecialchars($item['photo']); ?>" alt="Current photo" class="w-32 h-32 object-cover rounded-lg">
                    </div>
                <?php else: ?>
                    <p class="text-sm text-on-surface-variant mb-3">No photo currently uploaded.</p>
                <?php endif; ?>
                
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">Replace Photo (optional)</label>
                <input type="file" name="photo" accept="image/*" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-primary file:text-on-primary file:text-sm file:font-medium hover:file:bg-primary/90 transition-all outline-none">
                <p class="text-xs text-on-surface-variant mt-1.5">Allowed: JPG, PNG, GIF. Max 2MB.</p>
            </div>

            <!-- Additional Reference Photos Area -->
            <div class="bg-surface-container-low rounded-2xl p-6 border border-outline-variant/30 mt-6">
                <h3 class="text-lg font-bold text-on-surface mb-4">Additional Reference Photos (Optional, up to 4)</h3>
                
                <?php if (isset($additionalPhotos) && !empty($additionalPhotos)): ?>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                        <?php foreach ($additionalPhotos as $photo): ?>
                            <div class="relative aspect-square rounded-xl overflow-hidden group">
                                <img src="<?= htmlspecialchars($photo['photo_path']) ?>" alt="Reference Photo" class="w-full h-full object-cover">
                                <label class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center cursor-pointer">
                                    <input type="checkbox" name="delete_additional[]" value="<?= $photo['id'] ?>" class="w-5 h-5 mb-2 text-error focus:ring-error border-gray-300 rounded">
                                    <span class="text-white text-xs font-bold">Delete</span>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">Add More Reference Photos</label>
                <input type="file" name="additional_photos[]" multiple accept="image/*" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-primary file:text-on-primary file:text-sm file:font-medium hover:file:bg-primary/90 transition-all outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-1.5">Status Label</label>
                <select name="status_label" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none">
                    <option value="open" <?php echo ($item['status_label'] == 'open') ? 'selected' : ''; ?>>Open</option>
                    <option value="found_owner" <?php echo ($item['status_label'] == 'found_owner') ? 'selected' : ''; ?>>Found Owner</option>
                    <option value="claimed" <?php echo ($item['status_label'] == 'claimed') ? 'selected' : ''; ?>>Claimed</option>
                </select>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-3 bg-primary text-on-primary rounded-xl font-medium hover:bg-primary/90 transition-all active:scale-[0.98]">
                    Update Item
                </button>
                <a href="index.php?action=items" class="px-6 py-3 bg-surface-variant text-on-surface-variant rounded-xl font-medium hover:bg-outline-variant transition-all">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.querySelector('select[name="status"]').addEventListener('change', function() {
        const rewardContainer = document.getElementById('rewardContainer');
        if (this.value === 'lost') {
            rewardContainer.classList.remove('hidden');
        } else {
            rewardContainer.classList.add('hidden');
            document.getElementById('rewardInput').value = '';
        }
    });

    let map;
    let marker;

    function initMap() {
        const defaultLat = <?php echo !empty($item['latitude']) ? $item['latitude'] : '14.4795'; ?>;
        const defaultLng = <?php echo !empty($item['longitude']) ? $item['longitude'] : '121.0494'; ?>;
        const defaultLocation = [defaultLat, defaultLng]; // Coordinates

        
        // Metro Manila Bounds
        const southWest = L.latLng(14.3344, 120.8950);
        const northEast = L.latLng(14.7766, 121.1354);
        const bounds = L.latLngBounds(southWest, northEast);

        map = L.map('map', {
            maxBounds: bounds,
            maxBoundsViscosity: 1.0,
            minZoom: 11
        }).setView(defaultLocation, 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        marker = L.marker(defaultLocation, {draggable: true}).addTo(map);

        const input = document.getElementById("locationInput");
        const latInput = document.getElementById("latInput");
        const lngInput = document.getElementById("lngInput");

        // If there is no existing latitude string but there's a location, geocode it to place the marker
        if (input.value && !latInput.value) {
            fetch(`https://nominatim.openstreetmap.org/search?format=jsonv2&countrycodes=ph&viewbox=120.8950,14.7766,121.1354,14.3344&bounded=1&q=${encodeURIComponent(input.value)}`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.length > 0) {
                        const newLatLng = new L.LatLng(data[0].lat, data[0].lon);
                        map.setView(newLatLng, 16);
                        marker.setLatLng(newLatLng);
                        latInput.value = data[0].lat;
                        lngInput.value = data[0].lon;
                    }
                });
        }

        marker.on('dragend', function (e) {
            const latlng = marker.getLatLng();
            latInput.value = latlng.lat;
            lngInput.value = latlng.lng;
            fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${latlng.lat}&lon=${latlng.lng}`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.display_name) {
                        input.value = data.display_name;
                    }
                });
        });

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            latInput.value = e.latlng.lat;
            lngInput.value = e.latlng.lng;
            fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${e.latlng.lat}&lon=${e.latlng.lng}`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.display_name) {
                        input.value = data.display_name;
                    }
                });
        });
        
        // Prevent form submission on Enter in location input
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                e.preventDefault();
                this.dispatchEvent(new Event('change'));
            }
        });

        input.addEventListener('change', function() {
            if (this.value) {
                fetch(`https://nominatim.openstreetmap.org/search?format=jsonv2&countrycodes=ph&viewbox=120.8950,14.7766,121.1354,14.3344&bounded=1&q=${encodeURIComponent(this.value)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            const newLatLng = new L.LatLng(data[0].lat, data[0].lon);
                            map.setView(newLatLng, 16);
                            marker.setLatLng(newLatLng);
                            latInput.value = data[0].lat;
                            lngInput.value = data[0].lon;
                        }
                    });
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        initMap();
    });
</script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
