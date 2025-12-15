<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Mobile Safari specific -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="format-detection" content="telephone=no">
    
    <!-- Browser compatibility -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#7c3aed">
    
    <title>{{ config('app.name', 'Sistem Vaksin RSI') }} - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Smooth transitions */
        .sidebar-transition {
            transition: all 0.3s ease-in-out;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        /* Active menu item */
        .menu-item-active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 4px 6px -1px rgba(102, 126, 234, 0.3);
        }
        /* Hover effect */
        .menu-item:hover {
            background: rgba(102, 126, 234, 0.1);
            transform: translateX(5px);
        }
        /* Submenu animation */
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out;
        }
        .submenu.open {
            max-height: 500px;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden w-full">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar-transition fixed lg:relative z-30 w-64 bg-gradient-to-b from-indigo-900 via-purple-900 to-indigo-800 text-white shrink-0 overflow-y-auto shadow-2xl h-full transform -translate-x-full lg:translate-x-0">
            <!-- Logo -->
            <div class="p-6 border-b border-indigo-700">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="font-bold text-lg">RSI Vaksin</h1>
                        <p class="text-xs text-indigo-300">Admin Panel</p>
                    </div>
                </div>
            </div>

            <!-- User Info -->
            <div class="p-4 border-b border-indigo-700 bg-indigo-800 bg-opacity-50">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-pink-500 to-yellow-500 rounded-full flex items-center justify-center font-bold text-white shadow-lg">
                        {{ strtoupper(substr(Auth::user()->nama, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-sm truncate">{{ Auth::user()->nama }}</p>
                        <p class="text-xs text-indigo-300 truncate">{{ Auth::user()->email }}</p>
                        <span class="inline-block mt-1 px-2 py-0.5 bg-green-500 text-white text-xs rounded-full">Admin</span>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="p-4 space-y-2">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'menu-item-active' : '' }} sidebar-transition flex items-center space-x-3 px-4 py-3 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>

                <!-- Manajemen Permohonan -->
                <div class="space-y-1">
                    <button onclick="toggleSubmenu('permohonan')" class="menu-item sidebar-transition w-full flex items-center justify-between px-4 py-3 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium">Permohonan</span>
                        </div>
                        <svg id="permohonan-icon" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="permohonan-submenu" class="submenu pl-4 space-y-1">
                        <a href="{{ route('admin.permohonan.index') }}" class="menu-item {{ request()->routeIs('admin.permohonan.index') || request()->routeIs('admin.permohonan.show') ? 'menu-item-active' : '' }} sidebar-transition flex items-center space-x-3 px-4 py-2 rounded-lg text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <span>Daftar Permohonan</span>
                        </a>
                        <a href="{{ route('admin.permohonan.terverifikasi') }}" class="menu-item {{ request()->routeIs('admin.permohonan.terverifikasi') ? 'menu-item-active' : '' }} sidebar-transition flex items-center space-x-3 px-4 py-2 rounded-lg text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Permohonan Terverifikasi</span>
                        </a>
                    </div>
                </div>

                <!-- Manajemen Pasien -->
                <a href="{{ route('admin.pasien.index') }}" class="menu-item {{ request()->routeIs('admin.pasien.*') ? 'menu-item-active' : '' }} sidebar-transition flex items-center space-x-3 px-4 py-3 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="font-medium">Data Pasien</span>
                </a>

                <!-- Manajemen Screening -->
                <div class="space-y-1">
                    <button onclick="toggleSubmenu('screening')" class="menu-item sidebar-transition w-full flex items-center justify-between px-4 py-3 rounded-lg {{ request()->routeIs('admin.screening.categories.*') || request()->routeIs('admin.screening.questions.*') ? 'menu-item-active' : '' }}">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            <span class="font-medium">Screening</span>
                        </div>
                        <svg id="screening-icon" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="screening-submenu" class="submenu pl-4 space-y-1">
                        <a href="{{ route('admin.screening.categories.index') }}" class="menu-item {{ request()->routeIs('admin.screening.categories.*') ? 'menu-item-active' : '' }} sidebar-transition flex items-center space-x-3 px-4 py-2 rounded-lg text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <span>Kategori Pertanyaan</span>
                        </a>
                        <a href="{{ route('admin.screening.questions.index') }}" class="menu-item {{ request()->routeIs('admin.screening.questions.*') ? 'menu-item-active' : '' }} sidebar-transition flex items-center space-x-3 px-4 py-2 rounded-lg text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Bank Pertanyaan</span>
                        </a>
                    </div>
                </div>

                <!-- Manajemen Jenis Vaksin -->
                <a href="{{ route('admin.vaksin.index') }}" class="menu-item {{ request()->routeIs('admin.vaksin.*') ? 'menu-item-active' : '' }} sidebar-transition flex items-center space-x-3 px-4 py-3 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                    <span class="font-medium">Jenis Vaksin</span>
                </a>

                <!-- Manajemen User -->
                <a href="{{ route('admin.users.index') }}" class="menu-item {{ request()->routeIs('admin.users.*') ? 'menu-item-active' : '' }} sidebar-transition flex items-center space-x-3 px-4 py-3 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span class="font-medium">Kelola User</span>
                </a>
            </nav>

            <!-- Logout -->
            <div class="p-4 border-t border-indigo-700 mt-auto">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="menu-item sidebar-transition w-full flex items-center space-x-3 px-4 py-3 rounded-lg bg-red-500 bg-opacity-20 hover:bg-red-500 hover:bg-opacity-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span class="font-medium">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Overlay for mobile -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden lg:hidden" onclick="closeSidebar()"></div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden w-full">
            <!-- Top Navbar -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-4 lg:px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <button onclick="toggleSidebar()" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-lg p-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <div>
                            <h2 class="text-lg lg:text-xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                            <p class="text-xs lg:text-sm text-gray-500 hidden sm:block">@yield('page-subtitle', 'Selamat datang di panel admin')</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button class="relative p-2 text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>

                        <!-- Current Time -->
                        <div class="hidden md:flex items-center space-x-2 text-sm text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span id="current-time"></span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-4 lg:p-6">
                @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm animate-fade-in">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm animate-fade-in">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-red-700 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 px-4 lg:px-6 py-3 lg:py-4">
                <div class="flex flex-col sm:flex-row items-center justify-between text-xs lg:text-sm text-gray-600 space-y-2 sm:space-y-0">
                    <p>&copy; {{ date('Y') }} RSI Vaksin System. All rights reserved.</p>
                    <p class="text-gray-500">Version 1.0.0</p>
                </div>
            </footer>
        </div>
    </div>

    <script>
        // Toggle Sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const isDesktop = window.innerWidth >= 1024;
            
            // Toggle sidebar visibility
            sidebar.classList.toggle('-translate-x-full');
            
            // Show/hide overlay only for mobile
            if (!isDesktop) {
                overlay.classList.toggle('hidden');
            }
        }

        // Close Sidebar (for mobile overlay click)
        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        // Toggle Submenu
        function toggleSubmenu(name) {
            const submenu = document.getElementById(name + '-submenu');
            const icon = document.getElementById(name + '-icon');
            submenu.classList.toggle('open');
            icon.classList.toggle('rotate-180');
        }

        // Handle window resize - reset sidebar state
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const isDesktop = window.innerWidth >= 1024;
            
            if (isDesktop) {
                // Desktop: hide overlay, ensure sidebar visible
                overlay.classList.add('hidden');
                sidebar.classList.remove('-translate-x-full');
            } else {
                // Mobile: hide sidebar by default
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        });

        // Update current time
        function updateTime() {
            const now = new Date();
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            document.getElementById('current-time').textContent = now.toLocaleDateString('id-ID', options);
        }
        
        setInterval(updateTime, 1000);
        updateTime();

        // Auto-open active submenu
        document.addEventListener('DOMContentLoaded', function() {
            const activeItem = document.querySelector('.submenu .menu-item-active');
            if (activeItem) {
                const submenu = activeItem.closest('.submenu');
                if (submenu) {
                    submenu.classList.add('open');
                    const submenuId = submenu.id.replace('-submenu', '');
                    const icon = document.getElementById(submenuId + '-icon');
                    if (icon) icon.classList.add('rotate-180');
                }
            }
        });
    </script>
</body>
</html>
