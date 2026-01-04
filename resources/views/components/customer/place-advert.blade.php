<div class="max-w-7xl mx-auto px-4 py-12">
    <!-- Header -->
    <h1 class="text-3xl md:text-4xl font-bold text-slate-900 text-center mb-12">
        Place an advert on Kibo Auto
    </h1>

    <!-- Main Content Grid -->
    <div class="grid lg:grid-cols-2 gap-0 items-stretch">
        <!-- Left Panel - Dark Blue Card -->
        <div class="bg-slate-900 rounded-t-lg lg:rounded-l-lg lg:rounded-tr-none p-6 md:p-8 min-h-[190px] relative flex flex-col justify-between">
            <!-- Tab Buttons -->
            <div class="flex gap-3 mb-4">
                <button 
                    onclick="switchTab(0)" 
                    id="tab-0" 
                    class="tab-button text-white text-sm pb-1.5 border-b-2 border-white font-medium transition-colors">
                    Advertise on Kibo Auto
                </button>
                <button 
                    onclick="switchTab(1)" 
                    id="tab-1" 
                    class="tab-button text-white text-sm pb-1.5 border-b-2 border-transparent hover:border-white transition-colors">
                    Sell fast for free
                </button>
            </div>

            <!-- Content Tabs -->
            <div class="text-white flex-grow flex items-center">
                <!-- Tab Content 0 -->
                <div id="tab-content-0" class="tab-content">
                    <p class="text-sm leading-relaxed">
                        Reach millions of potential buyers across the UK. Create your listing with detailed photos and descriptions, set your price, and manage enquiries directly. You're in control of your sale from start to finish.
                    </p>
                </div>

                <!-- Tab Content 1 -->
                <div id="tab-content-1" class="tab-content hidden">
                    <p class="text-sm leading-relaxed">
                        Once you've answered a few questions and uploaded your images, our partner Dealer Auction will take it from there! Once your listing is live, you could receive your highest offer within 48 hours.
                    </p>
                </div>
            </div>

            <!-- Pagination Dots -->
            <div class="flex gap-2 mt-4">
                <button onclick="switchTab(0)" id="dot-0" class="w-2 h-2 rounded-full bg-white"></button>
                <button onclick="switchTab(1)" id="dot-1" class="w-2 h-2 rounded-full bg-white/40"></button>
            </div>
        </div>

        <!-- Right Panel - Car Image with Stats -->
        <div class="relative bg-gray-100 rounded-b-lg lg:rounded-r-lg lg:rounded-bl-none min-h-[190px] flex items-center justify-center overflow-hidden">
            <!-- 48 Hours Badge -->
            <div class="absolute top-4 right-4 text-right z-10">
                <div class="text-4xl md:text-5xl font-bold text-gray-200/50">48</div>
                <div class="text-base md:text-lg font-bold text-gray-400/70 -mt-2">hours</div>
                <p class="text-gray-500 text-[10px] mt-2 max-w-[150px] leading-tight">
                    Average time to sell to a dealer
                </p>
            </div>

            <!-- Car Image -->
            <div class="w-full h-full flex items-center justify-center">
                <img src="https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?w=800&auto=format&fit=crop" alt="Red car" class="w-full h-full object-cover">
            </div>
        </div>
    </div>
</div>

<script>
    function switchTab(index) {
        // Update tab buttons
        const tabs = document.querySelectorAll('.tab-button');
        tabs.forEach((tab, i) => {
            if (i === index) {
                tab.classList.remove('border-transparent');
                tab.classList.add('border-white', 'font-medium');
            } else {
                tab.classList.remove('border-white', 'font-medium');
                tab.classList.add('border-transparent');
            }
        });

        // Update tab content
        const contents = document.querySelectorAll('.tab-content');
        contents.forEach((content, i) => {
            if (i === index) {
                content.classList.remove('hidden');
            } else {
                content.classList.add('hidden');
            }
        });

        // Update pagination dots
        const dots = document.querySelectorAll('[id^="dot-"]');
        dots.forEach((dot, i) => {
            if (i === index) {
                dot.classList.remove('bg-white/40');
                dot.classList.add('bg-white');
            } else {
                dot.classList.remove('bg-white');
                dot.classList.add('bg-white/40');
            }
        });
    }
</script>

