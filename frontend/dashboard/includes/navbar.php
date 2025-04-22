<header class="bg-white shadow-sm sticky top-0 z-40">
    <div class="flex items-center justify-between px-10 py-4">
        <!-- Left section - Hamburger -->
        <div class="flex items-center">
            <!-- Hamburger button -->
            <button id="sidebarToggle" class="text-gray-600 hover:text-gray-900 focus:outline-none mr-4">
                <i class="fas fa-bars text-xl"></i>
            </button>
            
            <!-- Mobile search button (solo icono) -->
            <button id="mobileSearchToggle" class="md:hidden text-gray-600 hover:text-gray-900">
                <i class="fas fa-search text-xl"></i>
            </button>
        </div>

        <!-- Center section - Search (desktop) -->
        <div class="hidden md:flex flex-1 max-w-2xl mx-4">
            <div class="relative w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" id="desktopSearch" class="bg-gray-200 pl-10 pr-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white w-full" placeholder="Buscar...">
            </div>
        </div>

        <!-- Mobile search (hidden by default) -->
        <div id="mobileSearch" class="absolute left-0 right-0 top-16 bg-white p-2 shadow-md hidden md:hidden transition-all duration-300">
            <div class="relative">
                <input type="text" class="bg-gray-100 pl-10 pr-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 w-full" placeholder="Buscar...">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-500"></i>
                </div>
            </div>
        </div>

        <!-- Right section - User dropdown -->
        <div class="flex items-center space-x-4">
            <!-- User dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                    <img src="public/images/user.png" alt="User" class="w-8 h-8 rounded-full">
                    <span class="hidden md:inline text-gray-700">Admin User</span>
                    <i class="fas fa-chevron-up text-xs transition-transform" :class="{ 'transform rotate-180': open }"></i>
                </button>
                
                <!-- Dropdown menu -->
                <div x-show="open" @click.away="open = false" 
                     class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                    <div class="px-4 py-2 border-b">
                        <p class="text-sm font-medium text-gray-800">Admin User</p>
                        <p class="text-xs text-gray-500">admin@enersolar.com</p>
                    </div>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-user mr-2"></i> Ver perfil
                    </a>

                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 border-t">
                        <i class="fas fa-sign-out-alt mr-2"></i> Cerrar sesión
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    // Toggle sidebar (arreglado)
    document.getElementById('sidebarToggle').addEventListener('click', function(e) {
        e.stopPropagation();
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('-translate-x-full');
    });

    // Toggle mobile search
    document.getElementById('mobileSearchToggle').addEventListener('click', function(e) {
        e.stopPropagation();
        const mobileSearch = document.getElementById('mobileSearch');
        mobileSearch.classList.toggle('hidden');
        mobileSearch.classList.toggle('flex');
    });

    // Cerrar search mobile al hacer clic fuera
    document.addEventListener('click', function(event) {
        const mobileSearch = document.getElementById('mobileSearch');
        const mobileSearchToggle = document.getElementById('mobileSearchToggle');
        
        if (!mobileSearch.contains(event.target) && 
            event.target !== mobileSearchToggle && 
            !mobileSearchToggle.contains(event.target)) {
            mobileSearch.classList.add('hidden');
            mobileSearch.classList.remove('flex');
        }
    });
</script>