<footer class="bg-base-200 border-t">
    <div class="max-w-7xl mx-auto px-6 py-16">
        <div class="footer grid grid-cols-2 md:grid-cols-4 gap-10">
            
            <!-- Brand -->
            <div>
                <div class="flex items-center gap-2 mb-6">
                    <span class="text-3xl font-bold text-primary">Campus</span>
                    <span class="text-3xl font-bold">Connect</span>
                </div>
                <p class="text-base-content/70 leading-relaxed max-w-xs">
                    Connecting students and staff through a trusted campus marketplace for skills and services.
                </p>
            </div>

            <!-- Marketplace -->
            <div>
                <h4 class="footer-title text-base-content font-semibold mb-4">Marketplace</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('skills.index') }}" class="link link-hover">Browse Skills</a></li>
                    <li><a href="{{ route('jobs.index') }}" class="link link-hover">Browse Jobs</a></li>
                    <li><a href="#" class="link link-hover">Post a Gig</a></li>
                    <li><a href="#" class="link link-hover">Post a Job</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h4 class="footer-title text-base-content font-semibold mb-4">Support</h4>
                <ul class="space-y-3">
                    <li><a href="#" class="link link-hover">Help Center</a></li>
                    <li><a href="#" class="link link-hover">Contact Us</a></li>
                    <li><a href="#" class="link link-hover">FAQs</a></li>
                    <li><a href="#" class="link link-hover">Safety & Trust</a></li>
                </ul>
            </div>

            <!-- Legal -->
            <div>
                <h4 class="footer-title text-base-content font-semibold mb-4">Legal</h4>
                <ul class="space-y-3">
                    <li><a href="#" class="link link-hover">Terms of Service</a></li>
                    <li><a href="#" class="link link-hover">Privacy Policy</a></li>
                    <li><a href="#" class="link link-hover">Community Guidelines</a></li>
                    <li><a href="#" class="link link-hover">University Policy</a></li>
                </ul>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t mt-16 pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-base-content/60">
            <div>
                © {{ date('Y') }} Campus Connect. All rights reserved.
            </div>
            <div class="flex gap-6 mt-4 md:mt-0">
                <a href="#" class="hover:text-base-content transition-colors">Made for EDSU</a>
                <a href="#" class="hover:text-base-content transition-colors">Accessibility</a>
            </div>
        </div>
    </div>
</footer>