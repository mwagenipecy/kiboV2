<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
        
        .main-content.expanded {
            margin-left: 16rem; /* 256px */
        }
        
        .main-content.collapsed {
            margin-left: 5rem; /* 80px */
        }
        
        /* Rotate toggle button when collapsed */
        .sidebar-collapsed #toggleSidebar svg {
            transform: rotate(180deg);
        }
        
        /* Mobile: always full width or hidden */
        @media (max-width: 1024px) {
            .sidebar-expanded,
            .sidebar-collapsed {
                width: 16rem;
            }
            .main-content.expanded,
            .main-content.collapsed {
                margin-left: 0;
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

    <!-- Main Content Area -->
    <div class="lg:ml-64 main-content main-content-expanded">
        <!-- Navbar Component -->
        <x-admin.navbar />

        <!-- Page Content -->
        <main class="p-4 sm:p-6 lg:p-8">
            @yield('content')
            {{ $slot ?? '' }}
        </main>

        <!-- Footer Component -->
        <x-admin.footer />
    </div>

    <script>
        // Sidebar state
        let sidebarCollapsed = false;
        
        // Mobile Sidebar Toggle
        document.getElementById('openSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.remove('-translate-x-full');
            document.getElementById('sidebarOverlay').classList.remove('hidden');
        });

        document.getElementById('closeSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.add('-translate-x-full');
            document.getElementById('sidebarOverlay').classList.add('hidden');
        });

        document.getElementById('sidebarOverlay').addEventListener('click', function() {
            document.getElementById('sidebar').classList.add('-translate-x-full');
            document.getElementById('sidebarOverlay').classList.add('hidden');
        });
        
        // Desktop Sidebar Collapse/Expand Toggle
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            
            sidebarCollapsed = !sidebarCollapsed;
            
            if (sidebarCollapsed) {
                sidebar.classList.remove('sidebar-expanded');
                sidebar.classList.add('sidebar-collapsed');
                mainContent.classList.remove('expanded');
                mainContent.classList.add('collapsed');
                localStorage.setItem('sidebarCollapsed', 'true');
            } else {
                sidebar.classList.remove('sidebar-collapsed');
                sidebar.classList.add('sidebar-expanded');
                mainContent.classList.remove('collapsed');
                mainContent.classList.add('expanded');
                localStorage.setItem('sidebarCollapsed', 'false');
            }
        });
        
        // Restore sidebar state from localStorage
        window.addEventListener('DOMContentLoaded', function() {
            const savedState = localStorage.getItem('sidebarCollapsed');
            if (savedState === 'true') {
                document.getElementById('toggleSidebar').click();
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>

