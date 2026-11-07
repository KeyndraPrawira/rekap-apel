<header class="h-16 bg-white border-b border-gray-200 sticky top-0 z-30 flex items-center justify-between px-4 sm:px-6 shadow-sm">
            
            <!-- Navbar Kiri: Hamburger Toggle & Search Bar -->
            <div class="flex items-center gap-4">
                <!-- Burger Icon Toggle Button yang Terpisah Antara Desktop dan Mobile -->
                <button 
                    @click="window.innerWidth >= 1024 ? (sidebarOpen = !sidebarOpen) : (mobileSidebarOpen = !mobileSidebarOpen)" 
                    class="p-2 rounded-lg text-gray-600 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand-500"
                >
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>

                
            </div>

            <!-- Navbar Kanan: Quick Actions & Profile Dropdown -->
            <div class="flex items-center gap-2 sm:gap-4">
                

                <!-- User Profile Dropdown Menu (Standard Laravel Breeze Style) -->
                <div class="relative ml-2" x-data="{ userMenuOpen: false }">
                    <button 
                        @click="userMenuOpen = !userMenuOpen" 
                        class="flex items-center gap-3 p-1.5 rounded-lg hover:bg-gray-100 focus:outline-none"
                    >
                        <img 
                            src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&q=80&w=80" 
                            alt="Avatar Shawn L." 
                            class="w-8 h-8 rounded-full object-cover border border-gray-200"
                        >
                        <span class="hidden md:inline-block font-semibold text-sm text-gray-700">{{ Auth::user()->name }}</span>
                        <i class="fa-solid fa-chevron-down text-xs text-gray-400 hidden md:inline-block"></i>
                    </button>

                    <!-- Dropdown List User Profile -->
                    <div 
                        x-show="userMenuOpen" 
                        @click.outside="userMenuOpen = false"
                        x-transition
                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 border border-gray-100 z-50"
                        style="display: none;"
                    >
                    
                        <div class="border-t border-gray-100 my-1"></div>
                        <!-- Authentication -->
                          
                <form method="POST" action="{{ route('logout') }}" >
                   
                    @csrf
                    
                    <x-responsive-nav-link class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50" :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> 
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
                    </div>
                </div>

            </div>
        </header>





