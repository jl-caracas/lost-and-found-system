<?php
/**
 * views/auth/login.php – Login page with glass-morphism design
 * 
 * Users enter their username/email and password to authenticate.
 * Includes sticky error messages and registration link.
 */
include __DIR__ . '/../../includes/header.php';
?>

<!-- Login Container: Centered glass card -->
<div class="min-h-[80vh] flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <!-- Glass card with subtle shadow -->
        <div class="glass-card rounded-2xl p-8 card-shadow">
            
            <!-- Page header -->
            <div class="text-center mb-8 flex flex-col items-center">
                <img src="/LF-web2/assets/logo.png" alt="Foundly Logo" class="h-28 w-auto object-contain scale-125 mb-1">
                <h1 class="font-display text-3xl font-bold text-primary mt-2">Welcome Back</h1>
                <p class="text-on-surface-variant mt-2">Sign in to your Foundly account</p>
            </div>

            <!-- Success message after registration (from URL param) -->
            <?php if(isset($_GET['registered'])): ?>
                <div class="bg-secondary-container/30 text-on-secondary-container p-4 rounded-xl mb-4 text-sm">
                    ✅ Registration successful! Please login.
                </div>
            <?php endif; ?>

            <!-- Error message (set by AuthController) -->
            <?php if(isset($error)): ?>
                <div class="bg-error-container text-on-error-container p-4 rounded-xl mb-4 text-sm">
                    ❌ <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form method="POST" action="index.php?action=login" class="space-y-5">
                <!-- Username/Email Field -->
                <div>
                    <label for="identifier" class="block text-sm font-medium text-on-surface-variant mb-1.5">
                        Username or Email
                    </label>
                    <input type="text" 
                           id="identifier" 
                           name="identifier" 
                           class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none"
                           placeholder="Enter your username or email"
                           required>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-on-surface-variant mb-1.5">
                        Password
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none"
                           placeholder="Enter your password"
                           required>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full py-3 bg-primary text-on-primary rounded-xl font-medium hover:bg-primary/90 transition-all active:scale-[0.98]">
                    Sign In
                </button>
            </form>

            <!-- Register Link -->
            <p class="text-center text-on-surface-variant text-sm mt-6">
                Don't have an account? 
                <a href="index.php?action=register" class="text-primary hover:underline font-medium">Create Account</a>
            </p>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
