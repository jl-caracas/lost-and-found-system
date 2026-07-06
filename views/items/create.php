<?php
/**
 * views/items/create.php – Multi-step Create Post Form
 * 
 * Steps:
 * 1. Upload Photos (with preview)
 * 2. Item Details (name, category, description)
 * 3. Location & Date
 * 
 * Uses JavaScript for step navigation with progress bar.
 * Sticky form values on validation errors.
 */
include __DIR__ . '/../../includes/header.php';
?>

<!-- Progress Indicator -->
<div class="max-w-3xl mx-auto px-margin-mobile md:px-0">
    <button type="button" onclick="history.back()" class="flex items-center gap-2 text-on-surface-variant hover:text-primary transition-colors font-medium text-sm mb-6 group w-fit">
        <span class="material-symbols-outlined text-xl group-hover:-translate-x-1 transition-transform">arrow_back</span>
        Cancel Post
    </button>
    <div class="mb-8">
        <h1 class="font-display text-3xl md:text-4xl font-extrabold text-primary mb-2">Create Post</h1>
        <p class="text-on-surface-variant font-body">Fill out the details to report a lost or found item.</p>
    </div>
    <div class="mb-stack-lg">
        <div class="flex justify-between items-center mb-4">
            <span class="font-label-caps text-label-caps text-secondary uppercase tracking-widest" id="step-label">
                Step 1 of 4: Type
            </span>
            <span class="font-headline-md text-sm font-bold text-primary uppercase tracking-wider" id="step-percentage">25%</span>
        </div>
        <div class="h-1 w-full bg-surface-container-highest rounded-full overflow-hidden">
            <div class="h-full bg-secondary w-1/4 transition-all duration-500 ease-out" id="progress-bar"></div>
        </div>
    </div>

    <form method="POST" action="index.php?action=items_store" enctype="multipart/form-data" id="mainForm">
        <div class="bg-surface-container-lowest rounded-xl p-6 md:p-8 shadow-sm border border-outline-variant/10">
            
            <?php if(!empty($errors)): ?>
                <div class="bg-error-container text-on-error-container p-4 rounded-xl mb-4 text-sm space-y-1">
                    <?php foreach($errors as $err): ?>
                        <p>❌ <?php echo $err; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <!-- ===== STEP 1: Type ===== -->
            <section class="step-transition" id="step-1">
                <div class="mb-stack-lg">
                    <h2 class="text-2xl font-extrabold text-primary mb-2">What are you posting?</h2>
                    <p class="text-on-surface-variant font-body-md mb-8">Are you reporting a lost item or have you found something?</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-stack-lg">
                    <label class="cursor-pointer group">
                        <input type="radio" name="status" value="lost" class="peer hidden" <?php echo (($_POST['status'] ?? 'lost') == 'lost') ? 'checked' : ''; ?>>
                        <div class="h-full p-8 rounded-2xl border-2 border-outline-variant/30 bg-surface-container-low peer-checked:border-primary peer-checked:bg-primary-container/20 hover:bg-surface-container-high transition-all">
                            <span class="material-symbols-outlined text-5xl mb-4 text-primary group-hover:scale-110 transition-transform">search</span>
                            <h3 class="font-headline-sm text-headline-sm mb-2 text-on-surface">I lost an item</h3>
                            <p class="text-on-surface-variant font-body-md mb-8">Create a report for an item you have lost so others can help you find it.</p>
                        </div>
                    </label>

                    <label class="cursor-pointer group">
                        <input type="radio" name="status" value="found" class="peer hidden" <?php echo (($_POST['status'] ?? '') == 'found') ? 'checked' : ''; ?>>
                        <div class="h-full p-8 rounded-2xl border-2 border-outline-variant/30 bg-surface-container-low peer-checked:border-primary peer-checked:bg-primary-container/20 hover:bg-surface-container-high transition-all">
                            <span class="material-symbols-outlined text-5xl mb-4 text-secondary group-hover:scale-110 transition-transform">inventory_2</span>
                            <h3 class="font-headline-sm text-headline-sm mb-2 text-on-surface">I found an item</h3>
                            <p class="text-on-surface-variant font-body-md mb-8">Report an item you found so the owner can claim it back.</p>
                        </div>
                    </label>
                </div>
            </section>

            <!-- ===== STEP 2: Upload Photos ===== -->
            <section class="step-transition hidden opacity-0 translate-y-4" id="step-2">
                <div class="mb-stack-lg">
                    <h2 class="text-2xl font-extrabold text-primary mb-2">Upload Photos</h2>
                    <p class="text-on-surface-variant font-body-md mb-8">Clear photos help others identify the item more quickly. Add up to 5 images.</p>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-stack-lg">
                    <!-- Main Photo Upload Area -->
                    <div class="col-span-1">
                        <label id="mainPhotoLabel" class="aspect-square w-full rounded-xl border-2 border-dashed border-outline-variant bg-surface-container-low flex flex-col items-center justify-center cursor-pointer hover:bg-surface-container-high transition-colors group">
                            <input type="file" id="photoInput" name="photo" accept="image/*" class="hidden" onchange="previewPhotos(this)">
                            <span class="material-symbols-outlined text-4xl text-on-surface-variant group-hover:scale-110 transition-transform">add_a_photo</span>
                            <span class="font-label-caps text-label-caps mt-2 text-on-surface-variant text-center">Main Photo<br>(Required)</span>
                        </label>
                        <div id="photoPreviewContainer" class="grid gap-4 w-full"></div>
                    </div>

                    <!-- Additional Photos Upload Area -->
                    <div class="col-span-2 md:col-span-4">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <label id="extraPhotosLabel" class="aspect-square w-full rounded-xl border-2 border-dashed border-outline-variant bg-surface-container-low flex flex-col items-center justify-center cursor-pointer hover:bg-surface-container-high transition-colors group">
                                <input type="file" id="additionalPhotoInput" name="additional_photos[]" multiple accept="image/*" class="hidden" onchange="previewAdditionalPhotos(this)">
                                <span class="material-symbols-outlined text-4xl text-on-surface-variant group-hover:scale-110 transition-transform">library_add</span>
                                <span class="font-label-caps text-label-caps mt-2 text-on-surface-variant text-center">Extra Photos<br>(Up to 4)</span>
                            </label>
                            
                            <!-- Additional Photo Previews Container -->
                            <div class="col-span-1 md:col-span-3 contents" id="additionalPhotoPreviewContainer"></div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center p-4 bg-surface-container-high/50 rounded-lg">
                    <span class="material-symbols-outlined text-secondary mr-3">lightbulb</span>
                    <p class="text-sm text-on-surface-variant">Pro tip: Avoid blurry shots and include distinguishing marks like scratches or engravings.</p>
                </div>
            </section>

            <!-- ===== STEP 3: Item Details ===== -->
            <section class="step-transition hidden opacity-0 translate-y-4" id="step-3">
                <div class="mb-stack-lg">
                    <h2 class="text-2xl font-extrabold text-primary mb-2">Item Details</h2>
                    <p class="text-on-surface-variant font-body-md mb-8">Tell us what was lost or found. Be as descriptive as possible.</p>
                </div>

                <div class="space-y-6">
                    <!-- Item Name -->
                    <div>
                        <label class="font-headline-md text-sm font-bold text-primary uppercase tracking-wider mb-2 block">Item Name *</label>
                        <input type="text" 
                               name="item_name" 
                               id="itemNameInput"
                               class="w-full bg-surface/50 py-3 px-4 rounded-xl border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all outline-none font-body-md font-body-md" 
                               placeholder="e.g. Blue Velvet Wallet" 
                               value="<?php echo htmlspecialchars($_POST['item_name'] ?? ''); ?>">
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="font-headline-md text-sm font-bold text-primary uppercase tracking-wider mb-2 block">Category *</label>
                        <select name="category_id" id="categorySelect" class="w-full bg-surface/50 py-3 px-4 rounded-xl border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all outline-none font-body-md font-body-md appearance-none">
                            <option value="">-- Select Category --</option>
                            <?php while($cat = mysqli_fetch_assoc($categories)): ?>
                                <option value="<?php echo $cat['id']; ?>" 
                                    <?php echo (($_POST['category_id'] ?? 0) == $cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <!-- Reward (Optional) -->
                    <div id="rewardContainer" class="hidden">
                        <label class="font-headline-md text-sm font-bold text-primary uppercase tracking-wider mb-2 flex items-center gap-2">
                            Reward (Optional)
                            <span class="material-symbols-outlined text-[16px] text-secondary">stars</span>
                        </label>
                        <input type="text" 
                               name="reward" 
                               id="rewardInput"
                               class="w-full bg-surface p-4 rounded-lg border border-secondary/30 focus:border-secondary focus:ring-2 focus:ring-secondary/20 font-body-md" 
                               placeholder="e.g., ₱500, Free Lunch, etc." 
                               value="<?php echo htmlspecialchars($_POST['reward'] ?? ''); ?>">
                        <p class="text-xs text-on-surface-variant mt-2">Offer an incentive to encourage others to return your item.</p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="font-headline-md text-sm font-bold text-primary uppercase tracking-wider mb-2 block">Description</label>
                        <textarea name="description" 
                                  id="descriptionInput"
                                  class="w-full bg-surface/50 py-3 px-4 rounded-xl border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all outline-none font-body-md font-body-md resize-none" 
                                  placeholder="Mention color, brand, size, or any unique features..." 
                                  rows="4"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                    </div>
                </div>
            </section>

            <!-- ===== STEP 4: Location & Date ===== -->
            <section class="step-transition hidden opacity-0 translate-y-4" id="step-4">
                <div class="mb-stack-lg">
                    <h2 class="text-2xl font-extrabold text-primary mb-2">Location &amp; Date</h2>
                    <p class="text-on-surface-variant font-body-md mb-8">Where and when was the item last seen or discovered?</p>
                </div>

                <div class="space-y-6">
                    <!-- Location -->
                    <div>
                        <label class="font-headline-md text-sm font-bold text-primary uppercase tracking-wider mb-2 block">Location *</label>
                        <div class="relative">
                            <input type="text" 
                                   name="location" 
                                   id="locationInput"
                                   class="w-full bg-surface/50 py-3 px-4 pl-12 rounded-xl border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all outline-none font-body-md font-body-md" 
                                   placeholder="e.g. PUP Taguig" 
                                   value="<?php echo htmlspecialchars($_POST['location'] ?? ''); ?>">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">location_on</span>
                            <input type="hidden" name="latitude" id="latInput" value="<?php echo htmlspecialchars($_POST['latitude'] ?? ''); ?>">
                            <input type="hidden" name="longitude" id="lngInput" value="<?php echo htmlspecialchars($_POST['longitude'] ?? ''); ?>">
                        </div>
                    </div>

                    <!-- Specific Location -->
                    <div>
                        <label class="font-headline-md text-sm font-bold text-primary uppercase tracking-wider mb-2 block">Specific Location</label>
                        <div class="relative">
                            <input type="text" 
                                   name="specific_location" 
                                   id="specificLocationInput"
                                   class="w-full bg-surface/50 py-3 px-4 pl-12 rounded-xl border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all outline-none font-body-md font-body-md" 
                                   placeholder="e.g. Canteen, Library 2nd Floor" 
                                   value="<?php echo htmlspecialchars($_POST['specific_location'] ?? ''); ?>">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">push_pin</span>
                        </div>
                    </div>

                    <!-- Map Container -->
                    <div id="map" class="h-64 w-full rounded-xl overflow-hidden relative bg-surface-container shadow-inner border border-outline-variant/20 z-0">
                    </div>

                    <!-- Date -->
                    <div>
                        <label class="font-headline-md text-sm font-bold text-primary uppercase tracking-wider mb-2 block">Date of Incident *</label>
                        <input type="datetime-local" 
                               name="date_reported" 
                               id="dateInput"
                               class="w-full bg-surface/50 py-3 px-4 rounded-xl border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all outline-none font-body-md font-body-md" 
                               value="<?php echo htmlspecialchars($_POST['date_reported'] ?? date('Y-m-d\TH:i')); ?>">
                    </div>
                </div>
            </section>

            <!-- ===== Navigation Buttons ===== -->
            <div class="mt-12 pt-8 border-t border-outline-variant/20 flex justify-between items-center">
                <button type="button" class="hidden font-body-md font-medium text-primary border border-primary px-6 py-3 rounded-full hover:bg-primary/10 transition-colors active:scale-95" 
                        id="prev-btn">
                    Previous Step
                </button>
                <div class="flex-1"></div>
                <button type="button" class="bg-primary text-on-primary font-body-md font-semibold px-8 py-3 rounded-full active:scale-95 transition-transform hover:opacity-90" 
                        id="next-btn">
                    Next Step
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    /**
     * Photo Preview: Show selected images before upload
     */
    function previewPhotos(input) {
        const container = document.getElementById('photoPreviewContainer');
        const label = document.getElementById('mainPhotoLabel');
        
        // Clear existing previews
        const existingPreviews = container.querySelectorAll('.preview-item');
        existingPreviews.forEach(el => el.remove());
        
        const files = input.files;
        
        if (files.length > 0) {
            label.style.display = 'none';
        } else {
            label.style.display = 'flex';
        }

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'preview-item relative aspect-square rounded-xl overflow-hidden group';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">
                    <button type="button" onclick="this.parentElement.remove(); document.getElementById('mainPhotoLabel').style.display='flex'; document.getElementById('photoInput').value='';" 
                            class="absolute top-2 right-2 bg-black/50 text-white p-1 rounded-full backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="material-symbols-outlined text-sm">close</span>
                    </button>
                `;
                container.appendChild(div);
            };
            reader.readAsDataURL(file);
        }
    }
    let additionalPhotosArray = [];

    function previewAdditionalPhotos(input) {
        const files = input.files;
        
        for(let i=0; i<files.length; i++) {
            if (additionalPhotosArray.length < 4) {
                additionalPhotosArray.push(files[i]);
            } else {
                alert("You can only upload up to 4 additional photos.");
                break;
            }
        }
        
        updateAdditionalPhotosInput();
        renderAdditionalPhotos();
    }

    function updateAdditionalPhotosInput() {
        const input = document.getElementById('additionalPhotoInput');
        const dt = new DataTransfer();
        for(let i=0; i<additionalPhotosArray.length; i++){
            dt.items.add(additionalPhotosArray[i]);
        }
        input.files = dt.files;
    }

    window.removeAdditionalPhoto = function(index) {
        additionalPhotosArray.splice(index, 1);
        updateAdditionalPhotosInput();
        renderAdditionalPhotos();
    }

    function renderAdditionalPhotos() {
        const container = document.getElementById('additionalPhotoPreviewContainer');
        container.innerHTML = '';
        
        for (let i = 0; i < additionalPhotosArray.length; i++) {
            const file = additionalPhotosArray[i];
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'preview-item relative aspect-square rounded-xl overflow-hidden group';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">
                    <button type="button" onclick="removeAdditionalPhoto(${i})" 
                            class="absolute top-2 right-2 bg-black/50 text-white p-1 rounded-full backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="material-symbols-outlined text-sm">close</span>
                    </button>
                `;
                container.appendChild(div);
            };
            reader.readAsDataURL(file);
        }
        
        const label = document.getElementById('extraPhotosLabel');
        if (label) {
            if (additionalPhotosArray.length >= 4) {
                label.style.display = 'none';
            } else {
                label.style.display = 'flex';
            }
        }
    }

    /**
     * Multi-step form navigation
     */
    let currentStep = 1;
    const totalSteps = 4;

    const nextBtn = document.getElementById('next-btn');
    const prevBtn = document.getElementById('prev-btn');
    const progressBar = document.getElementById('progress-bar');
    const stepLabel = document.getElementById('step-label');
    const stepPercentage = document.getElementById('step-percentage');

    const stepLabels = [
        "Step 1 of 4: Type",
        "Step 2 of 4: Media",
        "Step 3 of 4: Item Details",
        "Step 4 of 4: Location & Date"
    ];

    function updateUI() {
        // Hide all steps
        for (let i = 1; i <= totalSteps; i++) {
            const el = document.getElementById(`step-${i}`);
            el.classList.add('hidden', 'opacity-0', 'translate-y-4');
        }

        // Show current step with animation
        const currentEl = document.getElementById(`step-${currentStep}`);
        currentEl.classList.remove('hidden');
        setTimeout(() => {
            currentEl.classList.remove('opacity-0', 'translate-y-4');
        }, 50);

        // Update progress
        const percentage = (currentStep / totalSteps) * 100;
        progressBar.style.width = `${percentage}%`;
        stepLabel.innerText = stepLabels[currentStep - 1];
        stepPercentage.innerText = `${Math.round(percentage)}%`;

        // Button visibility
        if (currentStep === 1) {
            prevBtn.classList.add('hidden');
        } else {
            prevBtn.classList.remove('hidden');
        }

        if (currentStep === totalSteps) {
            nextBtn.innerText = "Publish Post";
            nextBtn.classList.remove('bg-primary');
            nextBtn.classList.add('bg-secondary-container', 'text-on-secondary-container');
        } else {
            nextBtn.innerText = "Next Step";
            nextBtn.classList.add('bg-primary');
            nextBtn.classList.remove('bg-secondary-container', 'text-on-secondary-container');
        }
    }

    /**
     * Navigate to next step
     */
    nextBtn.addEventListener('click', () => {
        if (currentStep < totalSteps) {
            currentStep++;
            updateUI();
        } else {
            // Confirm submission
            if (confirm("Are you sure you want to publish this post?")) {
                formChanged = false;
                localStorage.removeItem('lf_post_draft');
                document.getElementById('mainForm').submit();
            }
        }
    });

    /**
     * Navigate to previous step
     */
    prevBtn.addEventListener('click', () => {
        if (currentStep > 1) {
            currentStep--;
            updateUI();
        }
    });

    // Initialize UI
    updateUI();

    /**
     * Leaflet.js Map Integration
     */
    let map;
    let marker;

    function initMap() {
        const defaultLocation = [14.4795, 121.0494]; // PUP Taguig approximate
        
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

        // Reverse Geocode when marker is dragged
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

        // Allow user to click on map to set location
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

        // Geocode when input changes (on blur)
        input.addEventListener('change', function() {
            if (this.value) {
                // Bias search to Philippines and specific viewbox
                fetch(`https://nominatim.openstreetmap.org/search?format=jsonv2&countrycodes=ph&viewbox=120.8950,14.7766,121.1354,14.3344&bounded=1&q=${encodeURIComponent(this.value)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            const newLatLng = new L.LatLng(data[0].lat, data[0].lon);
                            map.setView(newLatLng, 16);
                            marker.setLatLng(newLatLng);
                            latInput.value = newLatLng.lat;
                            lngInput.value = newLatLng.lng;
                        }
                    });
            }
        });
    }

    // Initialize map on load
    document.addEventListener("DOMContentLoaded", function() {
        initMap();
    });

    // Since we hide/show steps, we need to resize map when step 3 becomes visible
    nextBtn.addEventListener('click', () => {
        if (currentStep === 4 && map) {
            setTimeout(() => {
                map.invalidateSize();
                map.setView(marker.getLatLng(), 15);
            }, 300);
        }
    });

    /**
     * Prevent accidental navigation & Auto-save draft
     */
    let formChanged = false;
    const DRAFT_KEY = 'lf_post_draft';
    const formInputs = document.querySelectorAll('#mainForm input:not([type="file"]), #mainForm textarea, #mainForm select');

    function saveDraft() {
        const draft = {
            status: document.querySelector('input[name="status"]:checked') ? document.querySelector('input[name="status"]:checked').value : 'lost',
            item_name: document.getElementById('itemNameInput').value,
            category_id: document.getElementById('categorySelect').value,
            description: document.getElementById('descriptionInput').value,
            reward: document.getElementById('rewardInput').value,
            location: document.getElementById('locationInput').value,
            specific_location: document.getElementById('specificLocationInput').value,
            date_reported: document.getElementById('dateInput').value
        };
        localStorage.setItem(DRAFT_KEY, JSON.stringify(draft));
        formChanged = true;
    }

    function loadDraft() {
        const draftStr = localStorage.getItem(DRAFT_KEY);
        if (draftStr) {
            try {
                const draft = JSON.parse(draftStr);
                if (draft.status) {
                    const statusRadio = document.querySelector(`input[name="status"][value="${draft.status}"]`);
                    if(statusRadio) statusRadio.checked = true;
                }
                if (draft.item_name) document.getElementById('itemNameInput').value = draft.item_name;
                if (draft.category_id) document.getElementById('categorySelect').value = draft.category_id;
                if (draft.description) document.getElementById('descriptionInput').value = draft.description;
                if (draft.reward) document.getElementById('rewardInput').value = draft.reward;
                if (draft.location) document.getElementById('locationInput').value = draft.location;
                if (draft.specific_location) document.getElementById('specificLocationInput').value = draft.specific_location;
                if (draft.date_reported) document.getElementById('dateInput').value = draft.date_reported;
                
                updateRewardVisibility();
                
                // Show a small toast/notice if draft loaded
                const notice = document.createElement('div');
                notice.className = 'bg-secondary-container text-on-secondary-container p-3 rounded-lg text-sm mb-4 animate-fade-in';
                notice.innerHTML = '📝 Restored your draft in the next steps! (Note: Photos cannot be auto-saved and must be re-uploaded)';
                document.querySelector('.bg-surface-container-lowest').prepend(notice);
                setTimeout(() => notice.remove(), 6000);
            } catch (e) {}
        }
    }

    function updateRewardVisibility() {
        const status = document.querySelector('input[name="status"]:checked');
        const rewardContainer = document.getElementById('rewardContainer');
        if (status && status.value === 'lost') {
            rewardContainer.classList.remove('hidden');
        } else {
            rewardContainer.classList.add('hidden');
            document.getElementById('rewardInput').value = '';
            saveDraft();
        }
    }

    document.querySelectorAll('input[name="status"]').forEach(radio => {
        radio.addEventListener('change', updateRewardVisibility);
    });

    formInputs.forEach(input => {
        input.addEventListener('change', saveDraft);
        input.addEventListener('keyup', saveDraft);
    });

    window.addEventListener('beforeunload', (e) => {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    document.getElementById('mainForm').addEventListener('submit', () => {
        formChanged = false;
        localStorage.removeItem(DRAFT_KEY);
    });

    <?php if(empty($errors) && empty($_POST)): ?>
    document.addEventListener('DOMContentLoaded', loadDraft);
    <?php endif; ?>
</script>

<!-- Leaflet JS & CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>







