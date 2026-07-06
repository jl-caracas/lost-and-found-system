<?php
$page_title = "Profile Management";
include 'includes/header.php';
?>

<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="bg-surface rounded-2xl shadow-sm border border-outline-variant p-6 md:p-8">
        <h1 class="text-3xl font-display font-bold text-primary mb-6">Profile Management</h1>

        <?php if(isset($_SESSION['success'])): ?>
            <div class="bg-green-50 text-green-800 p-4 rounded-lg mb-6 flex items-center gap-3">
                <span class="material-symbols-outlined">check_circle</span>
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="bg-red-50 text-red-800 p-4 rounded-lg mb-6 flex items-center gap-3">
                <span class="material-symbols-outlined">error</span>
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="index.php?action=profile_update" method="POST" enctype="multipart/form-data" class="space-y-6">
            <div class="flex flex-col md:flex-row gap-8 items-start">
                
                <!-- Profile Picture Section -->
                <div class="flex-shrink-0 flex flex-col items-center space-y-4">
                    <div class="w-40 h-40 rounded-full overflow-hidden border-4 border-primary/20 bg-surface-variant flex items-center justify-center relative group">
                        <?php if(!empty($user['profile_picture'])): ?>
                            <img src="uploads/profiles/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" class="w-full h-full object-cover">
                        <?php else: ?>
                            <span class="material-symbols-outlined text-[80px] text-on-surface-variant">account_circle</span>
                        <?php endif; ?>
                        
                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer" onclick="document.getElementById('profile_picture').click()">
                            <span class="material-symbols-outlined text-white">photo_camera</span>
                        </div>
                    </div>
                    
                    <div class="text-center w-full">
                        <label for="profile_picture" class="cursor-pointer text-sm font-medium text-primary hover:text-primary/80 transition-colors">
                            Change Picture
                        </label>
                        <input type="file" name="profile_picture" id="profile_picture" accept="image/*" class="hidden" onchange="previewImage(this)">
                        <p class="text-xs text-on-surface-variant mt-1">JPG, PNG or GIF (Max. 2MB)</p>
                    </div>
                </div>

                <!-- Profile Details Section -->
                <div class="flex-grow space-y-4 w-full">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-on-surface mb-1">First Name *</label>
                            <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" class="w-full bg-surface border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:ring-2 focus:ring-primary focus:border-primary transition-all" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-on-surface mb-1">Last Name *</label>
                            <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" class="w-full bg-surface border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:ring-2 focus:ring-primary focus:border-primary transition-all" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-on-surface mb-1">Middle Initial</label>
                            <input type="text" name="middle_initial" maxlength="10" value="<?php echo htmlspecialchars($user['middle_initial'] ?? ''); ?>" class="w-full bg-surface border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-on-surface mb-1">Birthdate *</label>
                            <input type="date" name="birthdate" value="<?php echo htmlspecialchars($user['birthdate'] ?? ''); ?>" class="w-full bg-surface border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:ring-2 focus:ring-primary focus:border-primary transition-all" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-on-surface mb-1">Age</label>
                            <input type="text" value="<?php echo htmlspecialchars($user['age'] ?? ''); ?>" disabled class="w-full bg-surface-variant border border-outline-variant rounded-lg px-4 py-2 text-on-surface-variant opacity-70 cursor-not-allowed">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-on-surface mb-1">Username</label>
                        <input type="text" value="<?php echo htmlspecialchars($user['username']); ?>" disabled class="w-full bg-surface-variant border border-outline-variant rounded-lg px-4 py-2 text-on-surface-variant opacity-70 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-on-surface mb-1">Email</label>
                        <input type="text" value="<?php echo htmlspecialchars($user['email']); ?>" disabled class="w-full bg-surface-variant border border-outline-variant rounded-lg px-4 py-2 text-on-surface-variant opacity-70 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-on-surface mb-1">ID Number</label>
                        <input type="text" value="<?php echo htmlspecialchars($user['id_number']); ?>" disabled class="w-full bg-surface-variant border border-outline-variant rounded-lg px-4 py-2 text-on-surface-variant opacity-70 cursor-not-allowed">
                    </div>

                    <div>
                        <label for="bio" class="block text-sm font-medium text-on-surface mb-1">Bio</label>
                        <textarea name="bio" id="bio" rows="4" class="w-full bg-surface border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:ring-2 focus:ring-primary focus:border-primary transition-all resize-y" placeholder="Tell us something about yourself..."><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-outline-variant flex justify-end gap-3">
                <a href="index.php?action=dashboard" class="px-6 py-2 rounded-lg font-medium text-on-surface-variant hover:bg-surface-variant transition-colors border border-outline-variant">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg font-medium hover:bg-primary/90 transition-colors shadow-sm">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            const container = input.parentElement.previousElementSibling;
            container.innerHTML = `
                <img src="${e.target.result}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer" onclick="document.getElementById('profile_picture').click()">
                    <span class="material-symbols-outlined text-white">photo_camera</span>
                </div>
            `;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php include 'includes/footer.php'; ?>
