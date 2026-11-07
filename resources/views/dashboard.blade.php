 
 
 @section('content')
 
 
 <!-- // Page Title Header & Breadcrumbs -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <h1 class="text-xl font-bold text-gray-800">Dashboard</h1>
                
            </div>

            <!-- // Top 4 Stat Cards Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
                
                <!-- Stat Card 1: My Wallet -->
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                    <p class="text-sm font-medium text-gray-500">My Wallet</p>
                    <div class="flex items-baseline justify-between mt-2">
                        <h2 class="text-2xl font-bold text-gray-800">$865.2 k</h2>
                        <span class="inline-flex items-center text-xs font-semibold px-2 py-0.5 rounded bg-emerald-50 text-emerald-600">
                            + $20.9k
                        </span>
                    </div>
                    <div class="mt-4 flex items-center justify-between text-xs text-gray-400">
                        <span>Since last week</span>
                        <!-- Mock Wave Line SVG Chart -->
                        <svg class="w-24 h-8 text-brand-500 stroke-current fill-none" viewBox="0 0 100 30" stroke-width="2">
                            <path d="M0 20 Q 25 5, 50 20 T 100 10" />
                        </svg>
                    </div>
                </div>

                <!-- Stat Card 2: Number of Trades -->
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                    <p class="text-sm font-medium text-gray-500">Number of Trades</p>
                    <div class="flex items-baseline justify-between mt-2">
                        <h2 class="text-2xl font-bold text-gray-800">6258</h2>
                        <span class="inline-flex items-center text-xs font-semibold px-2 py-0.5 rounded bg-rose-50 text-rose-600">
                            - 29 Trades
                        </span>
                    </div>
                    <div class="mt-4 flex items-center justify-between text-xs text-gray-400">
                        <span>Since last week</span>
                        <!-- Mock Wave Line SVG Chart -->
                        <svg class="w-24 h-8 text-brand-500 stroke-current fill-none" viewBox="0 0 100 30" stroke-width="2">
                            <path d="M0 15 Q 30 25, 60 5 T 100 20" />
                        </svg>
                    </div>
                </div>

                <!-- Stat Card 3: Invested Amount -->
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                    <p class="text-sm font-medium text-gray-500">Invested Amount</p>
                    <div class="flex items-baseline justify-between mt-2">
                        <h2 class="text-2xl font-bold text-gray-800">$4.32M</h2>
                        <span class="inline-flex items-center text-xs font-semibold px-2 py-0.5 rounded bg-emerald-50 text-emerald-600">
                            + $2.8k
                        </span>
                    </div>
                    <div class="mt-4 flex items-center justify-between text-xs text-gray-400">
                        <span>Since last week</span>
                        <!-- Mock Wave Line SVG Chart -->
                        <svg class="w-24 h-8 text-brand-500 stroke-current fill-none" viewBox="0 0 100 30" stroke-width="2">
                            <path d="M0 25 Q 25 10, 50 15 T 100 5" />
                        </svg>
                    </div>
                </div>

                <!-- Stat Card 4: Profit Ration -->
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                    <p class="text-sm font-medium text-gray-500">Profit Ration</p>
                    <div class="flex items-baseline justify-between mt-2">
                        <h2 class="text-2xl font-bold text-gray-800">12.57%</h2>
                        <span class="inline-flex items-center text-xs font-semibold px-2 py-0.5 rounded bg-emerald-50 text-emerald-600">
                            + 2.95%
                        </span>
                    </div>
                    <div class="mt-4 flex items-center justify-between text-xs text-gray-400">
                        <span>Since last week</span>
                        <!-- Mock Wave Line SVG Chart -->
                        <svg class="w-24 h-8 text-brand-500 stroke-current fill-none" viewBox="0 0 100 30" stroke-width="2">
                            <path d="M0 10 Q 30 30, 70 10 T 100 25" />
                        </svg>
                    </div>
                </div>

            </div>

            <!-- // Section Dashboard Utama: Wallet Balance & Chart Placeholder -->
            <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <h3 class="text-base font-bold text-gray-800">Wallet Balance</h3>
                    <div class="flex items-center gap-1 bg-gray-100 p-1 rounded-lg text-xs font-medium text-gray-600 self-start sm:self-auto">
                        <button class="px-3 py-1 rounded-md hover:bg-white">ALL</button>
                        <button class="px-3 py-1 bg-white text-brand-500 font-bold rounded-md shadow-sm">1M</button>
                        <button class="px-3 py-1 rounded-md hover:bg-white">6M</button>
                        <button class="px-3 py-1 rounded-md hover:bg-white">1Y</button>
                    </div>
                </div>

                <!-- Pie Chart & Stats Mockup -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                    <!-- SVG Donut Chart Mockup -->
                    <div class="flex justify-center">
                        <div class="relative w-56 h-56 flex items-center justify-center">
                            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                                <path stroke-dasharray="70, 100" stroke="currentColor" class="text-brand-500" stroke-width="3.8" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                <path stroke-dasharray="30, 100" stroke-dashoffset="-70" stroke="currentColor" class="text-indigo-300" stroke-width="3.8" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            </svg>
                            <div class="absolute flex flex-col items-center">
                                <span class="text-xs text-gray-400">Total Balance</span>
                                <span class="text-lg font-bold text-gray-800">$5,149.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Details Stats Legend -->
                    <div class="space-y-4">
                        <div class="p-4 rounded-lg bg-gray-50 flex items-center justify-between border border-gray-100">
                            <div class="flex items-center gap-3">
                                <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                                <div>
                                    <p class="font-bold text-sm text-gray-800">Bitcoin</p>
                                    <p class="text-xs text-gray-500">0.4412 BTC</p>
                                </div>
                            </div>
                            <span class="font-bold text-sm text-gray-800">$4025.32</span>
                        </div>

                        <div class="p-4 rounded-lg bg-gray-50 flex items-center justify-between border border-gray-100">
                            <div class="flex items-center gap-3">
                                <span class="w-3 h-3 rounded-full bg-brand-500"></span>
                                <div>
                                    <p class="font-bold text-sm text-gray-800">Ethereum</p>
                                    <p class="text-xs text-gray-500">4.5701 ETH</p>
                                </div>
                            </div>
                            <span class="font-bold text-sm text-gray-800">$1123.64</span>
                        </div>
                    </div>
                </div>
            </div>
@endsection