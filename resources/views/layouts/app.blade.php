<!DOCTYPE html>
<html lang="id" x-data="{ sidebarOpen: true, mobileSidebarOpen: false }" :class="{ 'overflow-hidden': mobileSidebarOpen }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rekap apel - Admin Lapas</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js (Standar Laravel Breeze untuk Interaktivitas UI) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- FontAwesome CDN untuk Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- SweetAlert2 -->
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!-- Custom Tailwind Configuration untuk Menyesuaikan Warna Theme -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f4f5fa',
                            100: '#e9ebf5',
                            500: '#5156be', // Warna aksen ungu Minia
                            600: '#484cb0',
                            700: '#3c3f93',
                        },
                        sidebar: '#fafbfe', // Warna latar sidebar sesuai gambar
                        bodybg: '#f8f8fb'
                    }
                }
            }
        }
    </script>
    
    <style>
        /* Smooth transitions untuk collapse sidebar */
        .transition-width {
            transition: width 0.3s ease-in-out, margin-left 0.3s ease-in-out;
        }
        /* Custom scrollbar untuk sidebar */
        ::-webkit-scrollbar {
            width: 5px;
        }
        ::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }

         [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-bodybg font-sans text-gray-700 antialiased flex min-h-screen">

    <!-- ========================================== -->
    <!-- OVERLAY UNTUK TAMPILAN MOBILE             -->
    <!-- ========================================== -->
    <div 
        x-show="mobileSidebarOpen" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="mobileSidebarOpen = false"
        class="fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-sm lg:hidden"
        style="display: none;">
    </div>

    <!-- ========================================== -->
    <!-- SIDEBAR UTAMA                             -->
    <!-- ========================================== -->
    <!-- 
      - Pada Layar Desktop (lg): Width 64px (w-20) jika ditutup (sidebarOpen = false), 256px (w-64) jika dibuka (sidebarOpen = true).
      - Pada Layar Mobile: Fixed width 64 (w-64) saat drawer mobile di-toggle (mobileSidebarOpen = true).
    -->
   @include('layouts.sidebar')

    <!-- ========================================== -->
    <!-- MAIN CONTENT AREA                          -->
    <!-- ========================================== -->
    <div 
        class="flex-1 flex flex-col min-w-0 transition-width duration-300"
        :class="{
            'lg:ml-64': sidebarOpen,
            'lg:ml-20': !sidebarOpen
        }"
    >
    
        
        <!-- // NAVBAR TOP BAR -->
        @include('layouts.navigation')

        <!-- // MAIN CONTENT CONTAINER (Halaman Isi Utama) -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            @include('sweetalert2::index')
            
            @yield('content')

            @stack('scripts')
            

        </main>
    </div>

   

</body>
</html>