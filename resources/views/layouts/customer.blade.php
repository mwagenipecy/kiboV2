<!DOCTYPE html>
<html lang="en">
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
        {{ $slot ?? '' }}
    </main>

    <!-- Footer Component -->
    <x-customer.footer />

    @stack('scripts')
    @livewireScripts
</body>
</html>
