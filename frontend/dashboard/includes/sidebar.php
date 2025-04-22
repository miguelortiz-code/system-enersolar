<aside id="sidebar" class="bg-gray-800 text-white fixed md:relative h-full z-30 w-64 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
    <div class="flex flex-col h-full">
        <!-- Logo/Brand -->
        <div class="p-4 border-b border-gray-700">
            <h2 class="text-xl font-bold flex gap-3 items-center">
                <img src="public/images/logo.png" alt="User" class="w-12 h-12 rounded-full">
                <span>Enersolar</span>
            </h2>
        </div>

        <!-- ============================================-->
        <!-- NAVBAR-->
        <!-- ============================================-->
        <nav class="flex-1 overflow-y-auto">
            <ul class="space-y-1 p-2">
                <!-- ============================================-->
                <!-- DASHBOARD-->
                <!-- ============================================-->
                <li>
                    <a href="#" class="flex items-center p-3 rounded-lg hover:bg-gray-700">
                        <i class="fas fa-home mr-3"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- ============================================-->
                <!-- USUARIOS-->
                <!-- ============================================-->
                <li x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex items-center p-3 rounded-lg hover:bg-gray-700">
                        <i class="fas fa-users mr-3"></i>
                        <span class="flex-1 text-left">Usuarios</span>
                        <i class="fas fa-chevron-down text-xs transition-transform" :class="{ 'transform rotate-180': open }"></i>
                    </button>

                    <!-- Submenu -->
                    <ul x-show="open" class="ml-8 mt-1 space-y-1">
                        <li>
                            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-700 text-sm">
                                <i class="fas fa-circle-notch mr-2 text-xs"></i>
                                <span>Lista de usuarios</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-700 text-sm">
                                <i class="fas fa-circle-notch mr-2 text-xs"></i>
                                <span>Agregar usuario</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- ============================================-->
                <!-- PROUCTOS-->
                <!-- ============================================-->
                <li x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex items-center p-3 rounded-lg hover:bg-gray-700">
                        <i class="fas fa-inbox mr-3"></i>
                        <span class="flex-1 text-left">Configurar productos</span>
                        <i class="fas fa-chevron-down text-xs transition-transform" :class="{ 'transform rotate-180': open }"></i>
                    </button>

                    <!-- Submenu -->
                    <ul x-show="open" class="ml-8 mt-1 space-y-1">
                        <li>
                            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-700 text-sm">
                                <i class="fas fa-circle-notch mr-2 text-xs"></i>
                                <span>Agregar categoria</span>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-700 text-sm">
                                <i class="fas fa-circle-notch mr-2 text-xs"></i>
                                <span>Agregar producto</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-700 text-sm">
                                <i class="fas fa-circle-notch mr-2 text-xs"></i>
                                <span>Lista de productos</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- ============================================-->
                <!-- PROUCTOS-->
                <!-- ============================================-->
                <li x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex items-center p-3 rounded-lg hover:bg-gray-700">
                        <i class="fas fa-inbox mr-3"></i>
                        <span class="flex-1 text-left">Configurar productos</span>
                        <i class="fas fa-chevron-down text-xs transition-transform" :class="{ 'transform rotate-180': open }"></i>
                    </button>

                    <!-- Submenu -->
                    <ul x-show="open" class="ml-8 mt-1 space-y-1">
                        <li>
                            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-700 text-sm">
                                <i class="fas fa-circle-notch mr-2 text-xs"></i>
                                <span>Agregar categoria</span>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-700 text-sm">
                                <i class="fas fa-circle-notch mr-2 text-xs"></i>
                                <span>Agregar producto</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-700 text-sm">
                                <i class="fas fa-circle-notch mr-2 text-xs"></i>
                                <span>Lista de productos</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- ============================================-->
                <!-- DASHBOARD-->
                <!-- ============================================-->
                <li>
                    <a href="#" class="flex items-center p-3 rounded-lg hover:bg-gray-700">
                        <i class="fas fa-gear mr-3"></i>
                        <span>Configuración</span>
                    </a>
                </li>

            </ul>
        </nav>

        <!-- Sidebar footer -->
        <div class="p-4 border-t border-gray-700">
            <div class="text-xs text-gray-400">
                Versión 1.0.0
            </div>
        </div>
    </div>
</aside>

<!-- Script para el sidebar -->
<script>

</script>