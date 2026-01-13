<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Find Your Perfect Vehicle')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        body {
            font-family: ATVFabriga, -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif !important;
        }
    </style>
    @stack('styles')
    @livewireStyles
</head>
<body class="bg-white" style="font-family: ATVFabriga, -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif !important;">
    
    <!-- Header Component with Dynamic Vehicle Type -->
    <x-customer.header :vehicleType="$vehicleType ?? 'cars'" />

    <!-- Main Content -->
    <main>
        @yield('content')
        @isset($slot)
            {{ $slot }}
        @endisset
    </main>

    <!-- Footer Component -->
    <x-customer.footer />

    <!-- Chatbot Widget -->
    <x-customer.chatbot />

    @stack('scripts')
    @livewireScripts
    
    <script>
        // Close user menu dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = event.target.closest('.user-menu-dropdown');
            if (!userMenu) {
                // Clicked outside, find and close the dropdown
                Livewire.all().forEach(component => {
                    if (component.showDropdown !== undefined && component.showDropdown) {
                        component.closeDropdown();
                    }
                });
            }
        });
    </script>
</body>
</html>
