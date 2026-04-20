<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link rel="icon" type="image/png" href="{{ asset('logo/smalldeviceLogo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo/smalldeviceLogo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4" data-navigate-once></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" data-navigate-once></script>
    <style>
        /* Sidebar expanded state */
        .sidebar-expanded {
            width: 16rem; /* 256px - w-64 */
        }
        
        /* Sidebar collapsed state */
        .sidebar-collapsed {
            width: 5rem; /* 80px - w-20 */
        }
        
        /* Hide text when collapsed */
        .sidebar-collapsed .menu-text,
        .sidebar-collapsed .section-title,
        .sidebar-collapsed .user-info,
        .sidebar-collapsed .submenu {
            opacity: 0;
            width: 0;
            height: 0;
            overflow: hidden;
            white-space: nowrap;
            display: none;
        }
        
        /* Center icons when collapsed */
        .sidebar-collapsed nav a,
        .sidebar-collapsed nav button {
            justify-content: center;
        }
        
        /* Adjust main content margin */
        .main-content {
            transition: margin-left 0.3s ease;
        }
        
        @media (min-width: 1024px) {
            .main-content.expanded {
                margin-left: 16rem; /* 256px */
            }
            .main-content.collapsed {
                margin-left: 5rem; /* 80px */
            }
        }
        
        /* Rotate toggle button when collapsed */
        .sidebar-collapsed #toggleSidebar svg {
            transform: rotate(180deg);
        }
        
        /* Small screens: sidebar as overlay drawer using LEFT (not transform) so it's not overridden by Tailwind */
        @media (max-width: 1023px) {
            .main-content {
                margin-left: 0 !important;
            }
            #sidebar {
                left: -16rem !important;
                transform: none !important;
                width: 16rem !important;
                transition: left 0.3s ease;
                box-shadow: 0 0 0 1px rgba(0,0,0,0.05), 4px 0 24px rgba(0,0,0,0.15);
                z-index: 9999;
                background-color: #fff !important;
            }
            #sidebar.sidebar-mobile-open {
                left: 0 !important;
            }
            #sidebar .sidebar-collapse-btn {
                display: none;
            }
        }
        @media (min-width: 1024px) {
            #sidebar {
                z-index: 40;
                left: 0;
            }
            #sidebar.sidebar-mobile-open {
                left: 0;
            }
        }
        
        /* Tooltip for collapsed state */
        .sidebar-collapsed [data-tooltip] {
            position: relative;
        }
        
        .sidebar-collapsed [data-tooltip]:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            margin-left: 0.5rem;
            padding: 0.5rem 0.75rem;
            background-color: #1f2937;
            color: white;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            white-space: nowrap;
            z-index: 50;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50">
    
    <!-- Sidebar Component -->
    <x-admin.sidebar />

    <!-- Mobile sidebar overlay (below sidebar, above content) -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 hidden lg:hidden" style="z-index: 9998;" aria-hidden="true"></div>

    <!-- Main Content Area -->
    <div class="main-content expanded transition-[margin] duration-300 min-h-screen flex flex-col">
        <!-- Navbar Component -->
        <x-admin.navbar />

        <!-- Page Content -->
        <main class="flex-1 p-3 sm:p-6 lg:p-8 min-w-0 overflow-x-auto">
            @yield('content')
            {{ $slot ?? '' }}
        </main>

        <!-- Footer Component -->
        <x-admin.footer />
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var sidebar = document.getElementById('sidebar');
            var mainContent = document.querySelector('.main-content');
            var overlay = document.getElementById('sidebar-overlay');
            var toggleBtn = document.getElementById('toggleSidebar');
            var mobileToggleBtn = document.getElementById('toggleMobileSidebar');
            var sidebarCloseBtn = document.getElementById('sidebarClose');

            function closeMobileSidebar() {
                if (sidebar) sidebar.classList.remove('sidebar-mobile-open');
                if (overlay) overlay.classList.add('hidden');
                document.body.style.overflow = '';
            }
            function openMobileSidebar() {
                if (sidebar) sidebar.classList.add('sidebar-mobile-open');
                if (overlay) overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
            function isMobile() { return window.innerWidth < 1024; }

            // Desktop: collapse/expand
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    if (!isMobile()) {
                        sidebar.classList.toggle('sidebar-expanded');
                        sidebar.classList.toggle('sidebar-collapsed');
                        mainContent.classList.toggle('expanded');
                        mainContent.classList.toggle('collapsed');
                        localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('sidebar-collapsed'));
                    } else {
                        closeMobileSidebar();
                    }
                });
            }
            // Mobile: hamburger opens sidebar
            if (mobileToggleBtn) {
                mobileToggleBtn.addEventListener('click', function() {
                    if (isMobile()) {
                        if (sidebar.classList.contains('sidebar-mobile-open')) closeMobileSidebar();
                        else openMobileSidebar();
                    }
                });
            }
            if (sidebarCloseBtn) sidebarCloseBtn.addEventListener('click', closeMobileSidebar);
            if (overlay) overlay.addEventListener('click', closeMobileSidebar);

            window.addEventListener('resize', function() {
                if (!isMobile()) closeMobileSidebar();
            });

            // Restore desktop sidebar state
            if (!isMobile() && localStorage.getItem('sidebarCollapsed') === 'true' && toggleBtn) {
                toggleBtn.click();
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>

