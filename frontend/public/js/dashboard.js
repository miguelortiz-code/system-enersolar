    // Toggle sidebar
    document.getElementById('sidebarToggle').addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('-translate-x-full');
    });

    // Cerrar sidebar al hacer clic fuera en móviles
    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');

        if (window.innerWidth < 768 &&
            !sidebar.contains(event.target) &&
            event.target !== sidebarToggle &&
            !sidebarToggle.contains(event.target)) {
            sidebar.classList.add('-translate-x-full');
        }
    });

    document.getElementById('sidebarToggle').addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('hidden');
    });