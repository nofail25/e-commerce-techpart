<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - TechPart')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: { 
                        primary: {
                            50: '#f0f7ff',
                            100: '#e0effe',
                            200: '#bae6fd',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-700 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Backdrop Mobile -->
        <div id="sidebar-backdrop" class="fixed inset-0 bg-black/50 z-20 hidden lg:hidden transition-opacity" onclick="toggleSidebar()"></div>

        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-30 w-72 bg-white border-r border-slate-200 transform -translate-x-full lg:translate-x-0 lg:static lg:inset-auto transition-transform duration-300 ease-in-out flex flex-col shadow-lg lg:shadow-none">
            
            <!-- Logo Section -->
            <div class="flex items-center justify-between h-20 border-b border-slate-200/50 px-6">
                <a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-2.5">
                    <div class="bg-gradient-to-br from-primary-600 to-primary-700 text-white p-2 rounded-xl shadow-md">
                        <i data-lucide="shield-check" class="w-5 h-5"></i>
                    </div>
                    <div class="hidden xs:block">
                        <div class="text-sm font-bold tracking-tight text-slate-800">TechPart</div>
                        <div class="text-xs font-semibold text-primary-600">ADMIN</div>
                    </div>
                </a>
                <button onclick="toggleSidebar()" class="lg:hidden text-slate-400 hover:text-slate-600 p-1.5 hover:bg-slate-100 rounded-lg transition">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-1">
                
                <div class="mb-1">
                    <p class="px-3 text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Menu Utama</p>
                    
                    <a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-all duration-200 {{ Request::is('admin/dashboard') ? 'bg-primary-50 text-primary-700 shadow-sm' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50' }}">
                        <i data-lucide="layout-dashboard" class="w-5 h-5 {{ Request::is('admin/dashboard') ? 'text-primary-600' : 'text-slate-400' }}"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ url('/admin/pesanan') }}" class="flex items-center justify-between px-4 py-3 rounded-lg font-medium transition-all duration-200 {{ Request::is('admin/pesanan*') ? 'bg-primary-50 text-primary-700 shadow-sm' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50' }}">
                        <div class="flex items-center gap-3">
                            <i data-lucide="shopping-bag" class="w-5 h-5 {{ Request::is('admin/pesanan*') ? 'text-primary-600' : 'text-slate-400' }}"></i>
                            <span>Pesanan</span>
                        </div>
                    </a>

                    <a href="{{ url('/admin/produk') }}" class="flex items-center justify-between px-4 py-3 rounded-lg font-medium transition-all duration-200 {{ Request::is('admin/produk*') ? 'bg-primary-50 text-primary-700 shadow-sm' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50' }}">
                        <div class="flex items-center gap-3">
                            <i data-lucide="package-2" class="w-5 h-5 {{ Request::is('admin/produk*') ? 'text-primary-600' : 'text-slate-400' }}"></i>
                            <span>Produk</span>
                        </div>
                    </a>

                    <a href="{{ url('/admin/retur') }}" class="flex items-center justify-between px-4 py-3 rounded-lg font-medium transition-all duration-200 {{ Request::is('admin/retur*') ? 'bg-primary-50 text-primary-700 shadow-sm' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50' }}">
                        <div class="flex items-center gap-3">
                            <i data-lucide="undo-2" class="w-5 h-5 {{ Request::is('admin/retur*') ? 'text-primary-600' : 'text-slate-400' }}"></i>
                            <span>Retur</span>
                        </div>
                    </a>

                    <a href="{{ url('/admin/pengajuan-mitra') }}" class="flex items-center justify-between px-4 py-3 rounded-lg font-medium transition-all duration-200 {{ Request::is('admin/pengajuan-mitra*') ? 'bg-primary-50 text-primary-700 shadow-sm' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50' }}">
                        <div class="flex items-center gap-3">
                            <i data-lucide="users-2" class="w-5 h-5 {{ Request::is('admin/pengajuan-mitra*') ? 'text-primary-600' : 'text-slate-400' }}"></i>
                            <span>Verifikasi Mitra</span>
                        </div>
                    </a>
                </div>

                <div class="mb-1">
                    <p class="px-3 text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Laporan</p>
                    
                    <a href="{{ url('/admin/laporan') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-all duration-200 {{ Request::is('admin/laporan*') ? 'bg-primary-50 text-primary-700 shadow-sm' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50' }}">
                        <i data-lucide="bar-chart-3" class="w-5 h-5 {{ Request::is('admin/laporan*') ? 'text-primary-600' : 'text-slate-400' }}"></i>
                        <span>Laporan Penjualan</span>
                    </a>
                </div>
            </nav>

            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-slate-200/50 bg-gradient-to-b from-transparent to-slate-50/50">
                <form action="{{ route('logout') ?? '#' }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 w-full px-4 py-2.5 rounded-lg text-slate-600 hover:text-rose-600 hover:bg-rose-50 transition-all duration-200 font-medium">
                        <i data-lucide="log-out" class="w-5 h-5"></i>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- Top Header -->
            <header class="h-20 bg-white border-b border-slate-200/50 flex items-center justify-between px-4 sm:px-6 lg:px-8 z-10">
                
                <!-- Left: Hamburger Mobile + Breadcrumb -->
                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()" class="text-slate-500 hover:text-slate-700 lg:hidden p-2 rounded-lg hover:bg-slate-100 transition">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                </div>

                <!-- Right: Actions & Profile -->
                <div class="flex items-center gap-6">
                    
                    <a href="{{ url('/katalog') }}" target="_blank" title="Buka toko di tab baru" class="hidden sm:flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-primary-600 transition-colors bg-white border border-slate-200 hover:border-primary-300 px-4 py-2.5 rounded-lg hover:bg-slate-50">
                        <i data-lucide="external-link" class="w-4 h-4"></i>
                        <span>Lihat Toko</span>
                    </a>

                    <!-- User Profile Dropdown -->
                    <div class="flex items-center gap-3 pl-6 border-l border-slate-200/50">
                        <div class="text-right hidden sm:block">
                            <div class="text-sm font-semibold text-slate-800">{{ Auth::user()->name ?? 'Admin' }}</div>
                            <div class="text-xs font-medium text-slate-500">Administrator</div>
                        </div>
                        <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-primary-100 to-primary-200 text-primary-700 flex items-center justify-center font-bold text-sm border border-primary-300 shadow-sm">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Scrollable -->
            <main class="flex-1 overflow-y-auto">
                <div class="p-4 sm:p-6 lg:p-8">
                    <div class="max-w-7xl mx-auto">
                        @yield('content')
                    </div>
                </div>
            </main>
            
            <!-- Footer -->
            <footer class="bg-white border-t border-slate-200 py-4 text-center text-xs text-slate-500">
                <p>Sistem Manajemen TechPart &copy; 2026. All rights reserved.</p>
            </footer>
        </div>
    </div>

    <script>
        // Render Icons
        lucide.createIcons();

        // Script untuk Toggle Sidebar di versi Mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }
    </script>
</body>
</html>
