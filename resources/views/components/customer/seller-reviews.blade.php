<style>
    .kibo-text { color: #009866 !important; }
</style>
<!-- Seller Reviews Section -->
<section class="bg-white py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                Join thousands of happy sellers
            </h2>
            
            <!-- Trustpilot Rating Summary -->
            <div class="flex flex-col items-center gap-4">
                <div class="flex items-center gap-2">
                    <span class="text-gray-700 font-medium">Kibo Auto Trustpilot rating:</span>
                    <span class="kibo-text font-bold">Excellent</span>
                </div>
                
                <!-- Trustpilot Logo Placeholder -->
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1">
                        <!-- 5 Stars -->
                        @for($i = 0; $i < 5; $i++)
                        <svg class="w-6 h-6 kibo-text fill-current" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        @endfor
                    </div>
                    <span class="text-gray-400 text-sm">Trustpilot</span>
                </div>
                
                <div class="flex items-center gap-2">
                    <span class="text-gray-700">Kibo Auto has a Trustpilot score of</span>
                    <span class="text-4xl font-bold text-gray-900">4.6/5</span>
                </div>
                
                <p class="text-gray-600">Score is based on 104,714 reviews</p>
            </div>
        </div>

        <!-- Reviews Grid -->
        <div class="grid md:grid-cols-3 gap-6 max-w-6xl mx-auto">
            <!-- Review 1 -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-shadow">
                <!-- Stars -->
                <div class="flex items-center gap-1 mb-4">
                    @for($i = 0; $i < 5; $i++)
                    <svg class="w-5 h-5 kibo-text fill-current" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    @endfor
                </div>
                
                <!-- Review Text -->
                <h3 class="text-lg font-semibold text-gray-900 mb-3">
                    Simple way to advertise and post the photos
                </h3>
                
                <!-- Score Badge -->
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-sm text-gray-600">Kibo Auto has a Trustpilot score of</span>
                    <span class="font-bold text-gray-900">5/5</span>
                </div>
                
                <!-- Reviewer Info -->
                <div class="border-t border-gray-200 pt-4">
                    <p class="font-medium text-gray-900">Iain Rowley</p>
                    <p class="text-sm text-gray-500">Published 9 hours ago</p>
                </div>
            </div>

            <!-- Review 2 -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-shadow">
                <!-- Stars -->
                <div class="flex items-center gap-1 mb-4">
                    @for($i = 0; $i < 5; $i++)
                    <svg class="w-5 h-5 kibo-text fill-current" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    @endfor
                </div>
                
                <!-- Review Text -->
                <h3 class="text-lg font-semibold text-gray-900 mb-3">
                    A few tyre kickers but first viewer bought
                </h3>
                
                <!-- Score Badge -->
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-sm text-gray-600">Kibo Auto has a Trustpilot score of</span>
                    <span class="font-bold text-gray-900">5/5</span>
                </div>
                
                <!-- Reviewer Info -->
                <div class="border-t border-gray-200 pt-4">
                    <p class="font-medium text-gray-900">Anders Rumbold</p>
                    <p class="text-sm text-gray-500">Published 10 hours ago</p>
                </div>
            </div>

            <!-- Review 3 -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-shadow">
                <!-- Stars -->
                <div class="flex items-center gap-1 mb-4">
                    @for($i = 0; $i < 4; $i++)
                    <svg class="w-5 h-5 kibo-text fill-current" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    @endfor
                    <!-- Empty star -->
                    <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                
                <!-- Review Text -->
                <h3 class="text-lg font-semibold text-gray-900 mb-3">
                    Little expensive to advertise, but got the job done
                </h3>
                
                <!-- Score Badge -->
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-sm text-gray-600">Kibo Auto has a Trustpilot score of</span>
                    <span class="font-bold text-gray-900">4/5</span>
                </div>
                
                <!-- Reviewer Info -->
                <div class="border-t border-gray-200 pt-4">
                    <p class="font-medium text-gray-900">SuhNcl</p>
                    <p class="text-sm text-gray-500">Published 14 hours ago</p>
                </div>
            </div>
        </div>
    </div>
</section>

