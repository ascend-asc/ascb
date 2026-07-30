<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    lucide.createIcons();

    const sidebar     = document.getElementById('sidebar');
    const topbar      = document.getElementById('topbar');
    const mainContent = document.getElementById('mainContent');
    const toggleIcon  = document.getElementById('toggleIcon');

    // Restore state from localStorage
    if (sidebar && topbar && mainContent && toggleIcon) {
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            sidebar.classList.add('collapsed');
            topbar.classList.add('collapsed');
            mainContent.classList.add('collapsed');
            toggleIcon.setAttribute('data-lucide', 'panel-left-open');
            lucide.createIcons();
        }

        document.getElementById('sidebarToggle').addEventListener('click', function () {
            const isCollapsed = sidebar.classList.toggle('collapsed');
            topbar.classList.toggle('collapsed', isCollapsed);
            mainContent.classList.toggle('collapsed', isCollapsed);

            toggleIcon.setAttribute('data-lucide', isCollapsed ? 'panel-left-open' : 'panel-left-close');
            lucide.createIcons();

            localStorage.setItem('sidebarCollapsed', isCollapsed);
        });
    }
</script>
</body>
</html>
