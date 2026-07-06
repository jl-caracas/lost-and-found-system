<?php
/**
 * views/auth/register.php – Registration page with glass-morphism design
 * 
 * Users can create a new account with:
 * - Username, ID Type, ID Number, Email, Password (with confirmation)
 * - Sticky form values (retains input on validation errors)
 * - ID format validation hints
 */
include __DIR__ . '/../../includes/header.php';
?>

<!-- Register Container -->
<div class="min-h-[80vh] flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-md">
        <!-- Glass card -->
        <div class="glass-card rounded-2xl p-8 card-shadow">
            
            <!-- Page header -->
            <div class="text-center mb-8 flex flex-col items-center">
                <img src="/LF-web2/assets/logo.png" alt="Foundly Logo" class="h-28 w-auto object-contain scale-125 mb-1">
                <h1 class="font-display text-3xl font-bold text-primary mt-2">Create Account</h1>
                <p class="text-on-surface-variant mt-2">Join the Foundly community</p>
            </div>

            <!-- Error messages (sticky) -->
            <?php if(!empty($errors)): ?>
                <div class="bg-error-container text-on-error-container p-4 rounded-xl mb-4 text-sm space-y-1">
                    <?php foreach($errors as $err): ?>
                        <p>❌ <?php echo $err; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Registration Form -->
            <form id="registerForm" method="POST" action="index.php?action=register" class="space-y-4" onsubmit="return handleFormSubmit(event)">
                <!-- Name fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-on-surface-variant mb-1.5">First Name *</label>
                        <input type="text" id="first_name" name="first_name" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none" placeholder="First Name" value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>" required>
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-on-surface-variant mb-1.5">Last Name *</label>
                        <input type="text" id="last_name" name="last_name" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none" placeholder="Last Name" value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="middle_initial" class="block text-sm font-medium text-on-surface-variant mb-1.5">Middle Initial (Optional)</label>
                        <input type="text" id="middle_initial" name="middle_initial" maxlength="10" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none" placeholder="M.I." value="<?php echo htmlspecialchars($_POST['middle_initial'] ?? ''); ?>">
                    </div>
                    <div>
                        <label for="birthdate" class="block text-sm font-medium text-on-surface-variant mb-1.5">Birthdate *</label>
                        <input type="date" id="birthdate" name="birthdate" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none" value="<?php echo htmlspecialchars($_POST['birthdate'] ?? ''); ?>" required>
                    </div>
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-medium text-on-surface-variant mb-1.5">
                        Username *
                    </label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none"
                           placeholder="Choose a username"
                           value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                           required>
                </div>

                <!-- ID Type (dropdown) -->
                <div>
                    <label for="id_type" class="block text-sm font-medium text-on-surface-variant mb-1.5">
                        ID Type *
                    </label>
                    <select id="id_type" 
                            name="id_type" 
                            class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none">
                        <option value="pup_id" <?php echo (($_POST['id_type'] ?? '') == 'pup_id') ? 'selected' : ''; ?>>PUP Student ID (Format: 2024-12345-TG-0)</option>
                        <option value="national_id" <?php echo (($_POST['id_type'] ?? '') == 'national_id') ? 'selected' : ''; ?>>National ID (12 digits)</option>
                        <option value="faculty_id" <?php echo (($_POST['id_type'] ?? '') == 'faculty_id') ? 'selected' : ''; ?>>Faculty ID</option>
                        <option value="other" <?php echo (($_POST['id_type'] ?? '') == 'other') ? 'selected' : ''; ?>>Other ID</option>
                    </select>
                </div>

                <!-- Custom ID Name (Hidden by default) -->
                <div id="custom_id_wrapper" class="hidden">
                    <label for="custom_id_name" class="block text-sm font-medium text-on-surface-variant mb-1.5">
                        Specify ID Name *
                    </label>
                    <input type="text" 
                           id="custom_id_name" 
                           name="custom_id_name" 
                           class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none"
                           placeholder="e.g., Driver's License"
                           value="<?php echo htmlspecialchars($_POST['custom_id_name'] ?? ''); ?>">
                </div>

                <!-- ID Number -->
                <div>
                    <label for="id_number" class="block text-sm font-medium text-on-surface-variant mb-1.5">
                        ID Number *
                    </label>
                    <input type="text" 
                           id="id_number" 
                           name="id_number" 
                           class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none"
                           placeholder="Enter your ID number"
                           value="<?php echo htmlspecialchars($_POST['id_number'] ?? ''); ?>"
                           required>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-on-surface-variant mb-1.5">
                        Email *
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none"
                           placeholder="Enter your email"
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                           required>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-on-surface-variant mb-1.5">
                        Password * (min 6 characters)
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none pr-12"
                               placeholder="Create a password"
                               required>
                        <button type="button" class="absolute inset-y-0 right-0 pr-4 flex items-center text-on-surface-variant hover:text-primary transition-colors focus:outline-none" onclick="togglePassword('password', 'eye_pwd')">
                            <span class="material-symbols-outlined text-xl" id="eye_pwd">visibility_off</span>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-on-surface-variant mb-1.5">
                        Confirm Password *
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="confirm_password" 
                               name="confirm_password" 
                               class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none pr-12"
                               placeholder="Confirm your password"
                               required>
                        <button type="button" class="absolute inset-y-0 right-0 pr-4 flex items-center text-on-surface-variant hover:text-primary transition-colors focus:outline-none" onclick="togglePassword('confirm_password', 'eye_cpwd')">
                            <span class="material-symbols-outlined text-xl" id="eye_cpwd">visibility_off</span>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full py-3 bg-primary text-on-primary rounded-xl font-medium hover:bg-primary/90 transition-all active:scale-[0.98]">
                    Create Account
                </button>
            </form>

            <!-- Login Link -->
            <p class="text-center text-on-surface-variant text-sm mt-6">
                Already have an account? 
                <a href="index.php?action=login" class="text-primary hover:underline font-medium">Sign in</a>
            </p>
        </div>
    </div>
</div>
<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = 'visibility';
    } else {
        input.type = 'password';
        icon.textContent = 'visibility_off';
    }
}

// Handle Custom ID Type
const idTypeSelect = document.getElementById('id_type');
const customIdWrapper = document.getElementById('custom_id_wrapper');
const customIdInput = document.getElementById('custom_id_name');

function toggleCustomIdField() {
    if (idTypeSelect.value === 'other') {
        customIdWrapper.classList.remove('hidden');
        customIdInput.required = true;
    } else {
        customIdWrapper.classList.add('hidden');
        customIdInput.required = false;
    }
}

idTypeSelect.addEventListener('change', toggleCustomIdField);
// Initialize on page load
toggleCustomIdField();

// Handle Terms Modal
let termsAccepted = false;
function handleFormSubmit(e) {
    if (!termsAccepted) {
        e.preventDefault(); // Prevent immediate submission
        document.getElementById('termsModal').classList.remove('hidden');
        return false;
    }
    return true;
}

function acceptTerms() {
    termsAccepted = true;
    document.getElementById('termsModal').classList.add('hidden');
    document.getElementById('registerForm').submit();
}

function closeTerms() {
    document.getElementById('termsModal').classList.add('hidden');
}
</script>

<!-- Terms Modal -->
<div id="termsModal" class="fixed inset-0 z-[100] hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center px-4">
    <div class="bg-white rounded-2xl p-6 max-w-3xl w-full max-h-[80vh] overflow-y-auto card-shadow">
        <div class="mb-6">
            <?php include __DIR__ . '/../terms_content.php'; ?>
        </div>
        <div class="flex gap-4 justify-end">
            <button type="button" onclick="closeTerms()" class="px-4 py-2 rounded-lg font-medium text-on-surface-variant hover:bg-surface-variant transition-colors">Cancel</button>
            <button type="button" onclick="acceptTerms()" class="px-4 py-2 bg-primary text-on-primary rounded-lg font-medium hover:bg-primary/90 transition-colors">I Agree</button>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
