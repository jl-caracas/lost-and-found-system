<?php
/**
 * views/profile_public.php – Public Profile View
 */
$page_title = htmlspecialchars($user['username']) . "'s Profile";
include 'includes/header.php';
?>

<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="flex items-center gap-3 mb-6">
        <a href="javascript:history.back()" class="text-on-surface-variant hover:text-primary transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <h2 class="font-display text-headline-md text-primary">Public Profile</h2>
    </div>

    <div class="bg-surface rounded-2xl shadow-sm border border-outline-variant p-6 md:p-8">
        <div class="flex flex-col md:flex-row gap-8 items-center md:items-start text-center md:text-left">
            
            <!-- Profile Picture Section -->
            <div class="flex-shrink-0">
                <div class="w-40 h-40 rounded-full overflow-hidden border-4 border-primary/20 bg-surface-variant flex items-center justify-center relative">
                    <?php if(!empty($user['profile_picture'])): ?>
                        <img src="uploads/profiles/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" class="w-full h-full object-cover">
                    <?php else: ?>
                        <span class="material-symbols-outlined text-[80px] text-on-surface-variant">account_circle</span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Profile Details Section -->
            <div class="flex-grow space-y-4 w-full">
                <div>
                    <h1 class="text-3xl font-display font-bold text-primary">
                        <?php 
                        $fullName = trim(($user['first_name'] ?? '') . ' ' . ($user['middle_initial'] ?? '') . ' ' . ($user['last_name'] ?? ''));
                        echo htmlspecialchars($fullName ?: 'Anonymous'); 
                        ?>
                    </h1>
                    <p class="text-on-surface-variant font-medium text-lg mt-1">@<?php echo htmlspecialchars($user['username']); ?></p>
                </div>

                <div class="pt-4 border-t border-outline-variant/30">
                    <h3 class="font-headline-md text-sm text-on-surface-variant mb-2 uppercase tracking-wider">Bio</h3>
                    <p class="text-on-surface leading-relaxed whitespace-pre-wrap"><?php echo !empty($user['bio']) ? nl2br(htmlspecialchars($user['bio'])) : '<i>This user hasn\'t written a bio yet.</i>'; ?></p>
                </div>

                <div class="pt-4 flex gap-4">
                    <span class="inline-block bg-primary/10 text-primary px-3 py-1 rounded-full text-sm font-medium">
                        Role: <?php echo ucfirst(htmlspecialchars($user['role'])); ?>
                    </span>
                    <span class="inline-block bg-secondary/10 text-secondary px-3 py-1 rounded-full text-sm font-medium">
                        Joined: <?php echo date('F Y', strtotime($user['created_at'])); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
