<!-- Footer -->
<footer class="bg-white border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Column - Logo & Links -->
            <div class="lg:col-span-3 space-y-3">
                <div class="mb-6">
                    <img src="{{ asset('logo/green.png') }}" alt="Kibo Auto Logo" class="h-10 w-auto">
                </div>
                <a href="#" class="block text-gray-700 hover:text-gray-900 text-sm">Security advice</a>
                <a href="#" class="block text-gray-700 hover:text-gray-900 text-sm">Contact us</a>
                <a href="#" class="block text-gray-700 hover:text-gray-900 text-sm">About Kibo Auto</a>
                <a href="#" class="block text-gray-700 hover:text-gray-900 text-sm italic">Careers</a>
                <a href="#" class="block text-gray-700 hover:text-gray-900 text-sm">Investor information</a>
                <a href="#" class="block text-gray-700 hover:text-gray-900 text-sm">Privacy policies and terms</a>
                <a href="#" class="block text-gray-700 hover:text-gray-900 text-sm">Terms & conditions</a>
                <a href="#" class="block text-gray-700 hover:text-gray-900 text-sm">External wellbeing support</a>
                <button class="text-gray-700 hover:text-gray-900 text-sm mt-6">Manage cookies</button>
            </div>

            <!-- Middle Column - Expandable Sections -->
            <div class="lg:col-span-5 space-y-2">
                <!-- Products & Services -->
                <div class="border-b border-gray-200">
                    <button onclick="toggleSection('products')" class="w-full flex items-center justify-between py-4 text-left">
                        <span class="text-gray-900 font-medium">Products & services</span>
                        <svg id="products-icon" class="w-5 h-5 text-gray-600 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="products-content" class="hidden pb-4 space-y-2">
                        <a href="#" class="block text-gray-700 hover:text-gray-900 text-sm">Vehicle financing</a>
                        <a href="#" class="block text-gray-700 hover:text-gray-900 text-sm">Vehicle insurance</a>
                        <a href="#" class="block text-gray-700 hover:text-gray-900 text-sm">Extended warranties</a>
                        <a href="#" class="block text-gray-700 hover:text-gray-900 text-sm">Vehicle inspection</a>
                    </div>
                </div>

                <!-- Buying Advice -->
                <div class="border-b border-gray-200">
                    <button onclick="toggleSection('buying')" class="w-full flex items-center justify-between py-4 text-left">
                        <span class="text-gray-900 font-medium">Buying advice</span>
                        <svg id="buying-icon" class="w-5 h-5 text-gray-600 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="buying-content" class="hidden pb-4 space-y-2">
                        <a href="#" class="block text-gray-700 hover:text-gray-900 text-sm">Buying guide</a>
                        <a href="#" class="block text-gray-700 hover:text-gray-900 text-sm">How to buy a car</a>
                        <a href="#" class="block text-gray-700 hover:text-gray-900 text-sm">Vehicle checklist</a>
                        <a href="#" class="block text-gray-700 hover:text-gray-900 text-sm">Finance options</a>
                    </div>
                </div>

                <!-- Quick Search -->
                <div class="border-b border-gray-200">
                    <button onclick="toggleSection('quickSearch')" class="w-full flex items-center justify-between py-4 text-left">
                        <span class="text-gray-900 font-medium">Quick search</span>
                        <svg id="quickSearch-icon-close" class="w-5 h-5 text-gray-600 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <svg id="quickSearch-icon" class="w-5 h-5 text-gray-600 transition-transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="quickSearch-content" class="pb-4 space-y-2">
                        <a href="#" class="block text-gray-700 hover:text-gray-900 text-sm">Car brands</a>
                        <a href="#" class="block text-gray-700 hover:text-gray-900 text-sm">All locations</a>
                        <a href="#" class="block text-gray-700 hover:text-gray-900 text-sm">Find a dealer</a>
                        <a href="#" class="block text-gray-700 hover:text-gray-900 text-sm">Electric car leasing</a>
                        <a href="#" class="block text-gray-700 hover:text-gray-900 text-sm">Classic cars</a>
                        <a href="#" class="block text-gray-700 hover:text-gray-900 text-sm">Budget cars</a>
                        <a href="#" class="block text-gray-700 hover:text-gray-900 text-sm">New car deals</a>
                    </div>
                </div>

                <!-- Kibo Auto for Dealers -->
                <div class="border-b border-gray-200">
                    <button onclick="toggleSection('dealers')" class="w-full flex items-center justify-between py-4 text-left">
                        <span class="text-gray-900 font-medium">Kibo Auto for dealers</span>
                        <svg id="dealers-icon" class="w-5 h-5 text-gray-600 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="dealers-content" class="hidden pb-4 space-y-2">
                        <a href="#" class="block text-gray-700 hover:text-gray-900 text-sm">List your vehicle</a>
                        <a href="#" class="block text-gray-700 hover:text-gray-900 text-sm">Dealer portal</a>
                        <a href="#" class="block text-gray-700 hover:text-gray-900 text-sm">Pricing plans</a>
                    </div>
                </div>
            </div>

            <!-- Right Column - Feedback & Social -->
            <div class="lg:col-span-4">
                <div class="text-sm text-gray-700 mb-4">Help us improve our website</div>
                <button class="border-2 border-green-700 text-green-700 px-6 py-2 rounded-full hover:bg-green-50 transition-colors font-medium mb-6">
                    Send feedback
                </button>

                <!-- Social Icons -->
                <div class="flex gap-4 mb-6">
                    <a href="#" class="text-gray-700 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-700 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-700 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-700 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.53.02C13.84 0 15.14.01 16.44 0c.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-700 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-700 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0C5.373 0 0 5.372 0 12c0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24 12 24c6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z"/>
                        </svg>
                    </a>
                </div>

                <!-- App Store Buttons -->
                <div class="flex gap-3 mb-6">
                    <a href="#" class="inline-block">
                        <div class="bg-black text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-gray-800 transition-colors">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                            </svg>
                            <div class="text-xs">
                                <div>Download on the</div>
                                <div class="font-semibold">App Store</div>
                            </div>
                        </div>
                    </a>
                    <a href="#" class="inline-block">
                        <div class="bg-black text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-gray-800 transition-colors">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3,20.5V3.5C3,2.91 3.34,2.39 3.84,2.15L13.69,12L3.84,21.85C3.34,21.6 3,21.09 3,20.5M16.81,15.12L6.05,21.34L14.54,12.85L16.81,15.12M20.16,10.81C20.5,11.08 20.75,11.5 20.75,12C20.75,12.5 20.5,12.92 20.16,13.19L17.89,14.5L15.39,12L17.89,9.5L20.16,10.81M6.05,2.66L16.81,8.88L14.54,11.15L6.05,2.66Z"/>
                            </svg>
                            <div class="text-xs">
                                <div>GET IT ON</div>
                                <div class="font-semibold">Google Play</div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Copyright & Company Info -->
                <div class="text-xs text-gray-600 space-y-2">
                    <p>Copyright © Kibo Auto Limited {{ date('Y') }}.</p>
                    <p>
                        Kibo Auto Limited is authorised and regulated by the Financial Conduct Authority. 
                        Our FCA authorisation includes credit broking and insurance introductions. 
                        We are not a lender.{' '}
                        <a href="#" class="text-green-700 hover:underline">
                            Read more about our role and about fees and commissions
                        </a>
                    </p>
                    <div class="mt-4 space-y-1">
                        <p class="font-medium text-gray-700">Registered office and headquarters</p>
                        <p>Kibo Auto Limited</p>
                        <p>123 Business Street</p>
                        <p>City Center</p>
                        <p>Postal Code</p>
                        <p>Country</p>
                        <p>Registered number: 12345678</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<script>
    function toggleSection(section) {
        const content = document.getElementById(section + '-content');
        const icon = document.getElementById(section + '-icon');
        const closeIcon = document.getElementById(section + '-icon-close');
        
        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            if (icon) icon.classList.add('rotate-180');
            if (closeIcon) {
                closeIcon.classList.remove('hidden');
                icon.classList.add('hidden');
            }
        } else {
            content.classList.add('hidden');
            if (icon) icon.classList.remove('rotate-180');
            if (closeIcon) {
                closeIcon.classList.add('hidden');
                icon.classList.remove('hidden');
            }
        }
    }
</script>

