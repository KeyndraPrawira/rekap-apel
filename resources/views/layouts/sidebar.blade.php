<aside
    class="fixed top-0 left-0 z-50 h-screen bg-sidebar border-r border-gray-200 flex flex-col transition-width duration-300 shadow-sm"
    :class="{
        'w-64': sidebarOpen && !mobileSidebarOpen,
        'w-20': !sidebarOpen && !mobileSidebarOpen,
        '-translate-x-full lg:translate-x-0': !mobileSidebarOpen,
        'translate-x-0 w-64': mobileSidebarOpen
    }"
>
    <!-- Header Logo Sidebar -->
    <div class="h-16 flex items-center justify-between px-5 border-b border-gray-100 shrink-0">
        <a href="#" class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-brand-500 flex items-center justify-center text-white font-bold text-lg shadow-md shadow-brand-500/30 shrink-0">
                <i class="fa-solid fa-cube"></i>
            </div>
            <span
                x-show="(sidebarOpen && !mobileSidebarOpen) || mobileSidebarOpen"
                x-transition
                class="font-bold text-xl text-gray-800 tracking-wide truncate"
            >
                Rekap Apel
            </span>
        </a>
        <!-- Tombol tutup sidebar untuk mobile -->
        <button @click="mobileSidebarOpen = false" class="lg:hidden text-gray-500 hover:text-gray-700">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
    </div>

    <!-- Menu Navigation Scrollable -->
    <div class="flex-1 overflow-y-auto px-3 py-4 space-y-6 overflow-x-hidden">

        <!-- ========================================== -->
        <!-- MENU GROUP 1: MENU UTAMA                  -->
        <!-- ========================================== -->
        <div>
            <!-- Subtitle Menu Group -->
            <div
                x-show="sidebarOpen || mobileSidebarOpen"
                class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"
            >
                Menu
            </div>

            <nav class="space-y-1">
                <!-- Link Dashboard, aktif saat berada di halaman dashboard -->
                <a href="{{ route('dashboard') }}"
                   class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'text-brand-500 bg-brand-50' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    <i class="fa-solid fa-house w-6 text-center text-base shrink-0"></i>
                    <span x-show="sidebarOpen || mobileSidebarOpen" class="ml-3 truncate">Dashboard</span>
                </a>
            </nav>
        </div>

        <!-- ========================================== -->
        <!-- MENU GROUP 2: MASTER DATA                 -->
        <!-- ========================================== -->
        <div>
            <!-- Subtitle Menu Group -->
            <div
                x-show="sidebarOpen || mobileSidebarOpen"
                class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"
            >
                Master Data
            </div>

            <nav class="space-y-1">
                <!-- Link Pegawai, aktif saat berada di halaman pegawai -->
                <a href="{{ route('pegawai.index') }}"
                   class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('pegawai.*') ? 'bg-indigo-50 text-indigo-600 font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span class="w-6 flex justify-center shrink-0">
                        <svg class="w-5 h-5 {{ request()->routeIs('pegawai.*') ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </span>
                    <span x-show="sidebarOpen || mobileSidebarOpen" class="ml-3 truncate">Pegawai</span>
                </a>
            </nav>
        </div>

        <!-- ========================================== -->
        <!-- MENU GROUP 3: ABSEN                       -->
        <!-- ========================================== -->
        <div>
            <!-- Subtitle Menu Group -->
            <div
                x-show="sidebarOpen || mobileSidebarOpen"
                class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"
            >
                Absen
            </div>

            <nav class="space-y-1">
                <!-- Link Absen, aktif saat berada di halaman absen -->
                <a href="{{ route('absen.index') }}"
                   class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('absen.*') ? 'bg-indigo-50 text-indigo-600 font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span class="w-6 flex justify-center shrink-0">
                        <svg class="w-5 h-5 {{ request()->routeIs('absen.*') ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </span>
                    <span x-show="sidebarOpen || mobileSidebarOpen" class="ml-3 truncate">Absen</span>
                </a>
            </nav>
        </div>

    </div>
</aside>