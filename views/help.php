<?php
/**
 * views/help.php – Help, FAQ & Support
 */
include __DIR__ . '/../includes/header.php';
?>

<div class="max-w-5xl mx-auto px-margin-mobile md:px-0 py-8">
    <div class="mb-8">
        <h1 class="font-display text-3xl md:text-4xl font-extrabold text-primary mb-2">Help &amp; Support</h1>
        <p class="text-on-surface-variant font-body">Find answers to common questions, learn how to use Foundly, or report an issue to our support team.</p>
    </div>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="mb-stack-md p-4 bg-success-container text-on-success-container rounded-xl text-sm text-center flex items-center justify-center gap-2 animate-fade-in alert-auto-dismiss">
            <span class="material-symbols-outlined text-success">check_circle</span>
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="mb-stack-md p-4 bg-error-container text-on-error-container rounded-xl text-sm text-center flex items-center justify-center gap-2 alert-auto-dismiss">
            <span class="material-symbols-outlined text-error">error</span>
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- Section: How to Claim a Lost Item -->
    <section class="mb-stack-xl bg-surface-container-lowest rounded-3xl p-8 md:p-12 shadow-sm border border-outline-variant/20 relative overflow-hidden">
        <!-- Decorative background blur -->
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 bg-primary/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex items-center gap-4 mb-10 relative z-10">
            <div class="w-14 h-14 bg-primary-container text-on-primary-container rounded-2xl flex items-center justify-center shadow-sm">
                <span class="material-symbols-outlined text-3xl">verified_user</span>
            </div>
            <div>
                <h2 class="font-headline-md text-headline-md">How to Claim a Lost Item</h2>
                <p class="text-on-surface-variant text-sm">Follow these simple steps to safely retrieve your belongings.</p>
            </div>
        </div>
        
        <!-- Vertical Stepper -->
        <div class="space-y-8 relative before:absolute before:inset-0 before:ml-[1.75rem] before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-1 before:bg-gradient-to-b before:from-primary/20 before:via-secondary/20 before:to-tertiary/20 z-10">
            
            <!-- Step 1 -->
            <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                <div class="flex items-center justify-center w-14 h-14 rounded-full border-[6px] border-surface-container-lowest bg-primary text-on-primary font-bold shadow-md shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10 text-xl transition-transform group-hover:scale-110">
                    1
                </div>
                <div class="w-[calc(100%-4.5rem)] md:w-[calc(50%-3rem)] p-6 rounded-2xl bg-surface-container-low border border-outline-variant/20 group-hover:border-primary/40 group-hover:shadow-md transition-all">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="material-symbols-outlined text-primary">search</span>
                        <h3 class="font-headline-sm text-headline-sm">Search &amp; Verify</h3>
                    </div>
                    <p class="text-on-surface-variant text-sm leading-relaxed">Browse the 'Found Items' listings or use the search bar to look for your lost property. Once you find a match, check the photos and description carefully.</p>
                </div>
            </div>
            
            <!-- Step 2 -->
            <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                <div class="flex items-center justify-center w-14 h-14 rounded-full border-[6px] border-surface-container-lowest bg-secondary text-on-secondary font-bold shadow-md shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10 text-xl transition-transform group-hover:scale-110">
                    2
                </div>
                <div class="w-[calc(100%-4.5rem)] md:w-[calc(50%-3rem)] p-6 rounded-2xl bg-surface-container-low border border-outline-variant/20 group-hover:border-secondary/40 group-hover:shadow-md transition-all">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="material-symbols-outlined text-secondary">assignment_turned_in</span>
                        <h3 class="font-headline-sm text-headline-sm">File a Claim</h3>
                    </div>
                    <p class="text-on-surface-variant text-sm leading-relaxed">If you found someone else's post about your item, click <strong>"Claim Item"</strong> and provide proof of ownership. <br><br><em>(Note: If you are the original poster and already resolved it yourself, skip this and just click <strong>"Mark Claimed"</strong> to instantly close your post!)</em></p>
                </div>
            </div>
            
            <!-- Step 3 -->
            <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                <div class="flex items-center justify-center w-14 h-14 rounded-full border-[6px] border-surface-container-lowest bg-tertiary text-on-tertiary font-bold shadow-md shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10 text-xl transition-transform group-hover:scale-110">
                    3
                </div>
                <div class="w-[calc(100%-4.5rem)] md:w-[calc(50%-3rem)] p-6 rounded-2xl bg-surface-container-low border border-outline-variant/20 group-hover:border-tertiary/40 group-hover:shadow-md transition-all">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="material-symbols-outlined text-tertiary">handshake</span>
                        <h3 class="font-headline-sm text-headline-sm">Approval &amp; Meetup</h3>
                    </div>
                    <p class="text-on-surface-variant text-sm leading-relaxed">Wait for the original poster (or an administrator) to review your proof and Approve your claim. Once approved, use the built-in messaging system to safely coordinate returning the item!</p>
                </div>
            </div>
            
        </div>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-stack-lg">
        
        <!-- Section: FAQ -->
        <section class="lg:col-span-3 bg-surface-container-lowest rounded-3xl p-6 md:p-8 shadow-sm border border-outline-variant/20">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-12 h-12 bg-secondary-container text-on-secondary-container rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-2xl">help_center</span>
                </div>
                <h2 class="font-headline-md text-headline-md">Frequently Asked Questions</h2>
            </div>

            <div class="space-y-4">
                <!-- FAQ 1 -->
                <details class="group bg-surface-container p-2 rounded-2xl [&_summary::-webkit-details-marker]:hidden border border-outline-variant/10 shadow-sm" open>
                    <summary class="flex cursor-pointer items-center justify-between p-4 font-headline-sm text-on-surface hover:text-primary transition-colors">
                        <span class="font-medium text-[15px]">Is Foundly free to use?</span>
                        <span class="material-symbols-outlined text-outline-variant transition duration-300 group-open:-rotate-180 group-open:text-primary">expand_more</span>
                    </summary>
                    <div class="px-4 pb-4 pt-1 text-on-surface-variant text-sm leading-relaxed border-t border-outline-variant/10 mt-2">
                        <p class="mt-2">Yes, Foundly is completely free for all students and staff members to report and claim items on the PUP Taguig campus. The platform is also designed to scale into a region-wide lost and found system in the future.</p>
                    </div>
                </details>
                <!-- FAQ 2 -->
                <details class="group bg-surface-container p-2 rounded-2xl [&_summary::-webkit-details-marker]:hidden border border-outline-variant/10 shadow-sm">
                    <summary class="flex cursor-pointer items-center justify-between p-4 font-headline-sm text-on-surface hover:text-primary transition-colors">
                        <span class="font-medium text-[15px]">What if I found an ID card?</span>
                        <span class="material-symbols-outlined text-outline-variant transition duration-300 group-open:-rotate-180 group-open:text-primary">expand_more</span>
                    </summary>
                    <div class="px-4 pb-4 pt-1 text-on-surface-variant text-sm leading-relaxed border-t border-outline-variant/10 mt-2">
                        <p class="mt-2">If you found a student ID or an official document, please turn it over directly to the campus security office or administration. You can still post it on Foundly as "Surrendered to Security" so the owner knows where to find it.</p>
                    </div>
                </details>
                <!-- FAQ 3 -->
                <details class="group bg-surface-container p-2 rounded-2xl [&_summary::-webkit-details-marker]:hidden border border-outline-variant/10 shadow-sm">
                    <summary class="flex cursor-pointer items-center justify-between p-4 font-headline-sm text-on-surface hover:text-primary transition-colors">
                        <span class="font-medium text-[15px]">How do I know my data is safe?</span>
                        <span class="material-symbols-outlined text-outline-variant transition duration-300 group-open:-rotate-180 group-open:text-primary">expand_more</span>
                    </summary>
                    <div class="px-4 pb-4 pt-1 text-on-surface-variant text-sm leading-relaxed border-t border-outline-variant/10 mt-2">
                        <p class="mt-2">Messages and claims are kept private between the finder and the claimant. Only administrators can view claim details for mediation purposes. Your passwords and sensitive information are encrypted.</p>
                    </div>
                </details>
                <!-- FAQ 4 -->
                <details class="group bg-surface-container p-2 rounded-2xl [&_summary::-webkit-details-marker]:hidden border border-outline-variant/10 shadow-sm">
                    <summary class="flex cursor-pointer items-center justify-between p-4 font-headline-sm text-on-surface hover:text-primary transition-colors">
                        <span class="font-medium text-[15px]">I can't reach the person who found my item. What do I do?</span>
                        <span class="material-symbols-outlined text-outline-variant transition duration-300 group-open:-rotate-180 group-open:text-primary">expand_more</span>
                    </summary>
                    <div class="px-4 pb-4 pt-1 text-on-surface-variant text-sm leading-relaxed border-t border-outline-variant/10 mt-2">
                        <p class="mt-2">If 48 hours have passed without a response in the messaging system, please contact an administrator via the report bug feature or speak to campus security to intervene.</p>
                    </div>
                </details>
            </div>
        </section>

        <!-- Section: Report a Bug -->
        <section class="lg:col-span-2 bg-surface-container-lowest rounded-3xl p-6 md:p-8 shadow-sm border border-outline-variant/20 flex flex-col">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-error-container text-on-error-container rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-2xl">bug_report</span>
                </div>
                <h2 class="font-headline-md text-headline-md">Report an Issue</h2>
            </div>
            
            <p class="text-on-surface-variant text-sm mb-6 leading-relaxed">Encountered a bug or have a suggestion? Let us know so we can improve the platform.</p>

            <form action="index.php?action=issue_reports_store" method="POST" class="space-y-5 flex-1 flex flex-col justify-end">
                <div>
                    <label class="block font-label-caps text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Issue Type</label>
                    <div class="relative">
                        <select name="issue_type" class="w-full bg-surface-container px-4 py-3 rounded-xl border border-outline-variant/20 focus:border-primary focus:ring-2 focus:ring-primary/20 text-sm outline-none transition-shadow cursor-pointer" required>
                            <option value="Technical Bug">Technical Bug</option>
                            <option value="Display/UI Issue">Display/UI Issue</option>
                            <option value="Feature Request">Feature Request</option>
                            <option value="Report a User/Listing">Report a User/Listing</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block font-label-caps text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Description</label>
                    <textarea name="description" rows="5" class="w-full bg-surface-container p-4 rounded-xl border border-outline-variant/20 focus:border-primary focus:ring-1 focus:ring-primary text-sm resize-none outline-none transition-shadow" placeholder="Please describe the issue in detail..." required></textarea>
                </div>
                <button type="submit" class="w-full bg-primary text-on-primary py-4 rounded-xl font-body-md font-semibold active:scale-95 transition-all hover:bg-primary/90 hover:shadow-lg flex justify-center items-center gap-2 mt-auto">
                    <span class="material-symbols-outlined text-lg">send</span> Submit Report
                </button>
            </form>
            
            <!-- Success Message (Hidden by default) -->
            <div id="bugSuccessMsg" class="hidden mt-4 p-4 bg-success-container text-on-success-container rounded-xl text-sm text-center flex items-center justify-center gap-2 animate-fade-in">
                <span class="material-symbols-outlined text-success">check_circle</span>
                Thank you! Your report has been submitted.
            </div>
        </section>

    </div>

    <!-- Section: Contact Us -->
    <section class="mt-stack-lg bg-primary rounded-3xl p-8 md:p-12 shadow-lg relative overflow-hidden text-on-primary">
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-black/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-10">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center shadow-inner">
                        <span class="material-symbols-outlined text-3xl">support_agent</span>
                    </div>
                    <h2 class="font-display text-3xl md:text-4xl font-bold">Need More Help?</h2>
                </div>
                <p class="text-white/80 text-sm md:text-base leading-relaxed mb-8 max-w-xl">
                    If you couldn't find the answer in our FAQ or need immediate assistance regarding a lost item or account issue, our dedicated support team is ready to help you.
                </p>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 md:gap-4">
                    <div class="flex items-center gap-3 bg-black/20 p-3 md:p-4 rounded-2xl backdrop-blur-md border border-white/10 hover:bg-black/30 transition-colors">
                        <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center shrink-0 text-secondary">
                            <span class="material-symbols-outlined">mail</span>
                        </div>
                        <div class="min-w-0">
                            <div class="text-[10px] text-white/60 uppercase tracking-widest font-bold mb-0.5 whitespace-nowrap">Email Support</div>
                            <a href="mailto:support@foundly.edu.ph" class="font-medium text-sm hover:text-secondary transition-colors truncate block">support@foundly.ph</a>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 bg-black/20 p-3 md:p-4 rounded-2xl backdrop-blur-md border border-white/10 hover:bg-black/30 transition-colors">
                        <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center shrink-0 text-secondary">
                            <span class="material-symbols-outlined">location_on</span>
                        </div>
                        <div class="min-w-0">
                            <div class="text-[10px] text-white/60 uppercase tracking-widest font-bold mb-0.5 whitespace-nowrap">Visit Office</div>
                            <div class="font-medium text-sm whitespace-nowrap">PUP Taguig</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="hidden lg:flex w-64 h-64 shrink-0 relative items-center justify-center">
                <!-- Abstract graphic for contact us -->
                <div class="absolute inset-0 bg-secondary/20 rounded-full animate-pulse blur-xl"></div>
                <div class="relative w-48 h-48 bg-white/10 backdrop-blur-lg border border-white/20 rounded-[2rem] rotate-12 flex items-center justify-center shadow-2xl">
                    <span class="material-symbols-outlined text-7xl text-white drop-shadow-lg">forum</span>
                </div>
                <div class="absolute -bottom-4 -left-4 w-24 h-24 bg-white/10 backdrop-blur-lg border border-white/20 rounded-full -rotate-12 flex items-center justify-center shadow-xl">
                    <span class="material-symbols-outlined text-4xl text-secondary drop-shadow-md">alternate_email</span>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Script removed as form now submits natively -->

<?php include __DIR__ . '/../includes/footer.php'; ?>
