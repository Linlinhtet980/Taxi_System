/**
 * Admin Layout Interactivity
 * Handles sidebar, theme toggling, and notifications
 */

document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const mainWrapper = document.getElementById('main-wrapper');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const sidebarOverlay = document.getElementById('sidebar-overlay');
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');
    const body = document.body;

    // Theme Management
    function setTheme(theme) {
        body.setAttribute('data-theme', theme);
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        if (theme === 'light') {
            if (themeIcon) themeIcon.classList.replace('fa-moon', 'fa-sun');
        } else {
            if (themeIcon) themeIcon.classList.replace('fa-sun', 'fa-moon');
        }
    }

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const currentTheme = body.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            setTheme(newTheme);
        });
    }

    // Sidebar Toggle (Desktop & Mobile)
    function toggleSidebar() {
        if (window.innerWidth <= 768) {
            if (sidebar) sidebar.classList.toggle('mobile-active');
            if (sidebarOverlay) sidebarOverlay.classList.toggle('active');
        } else {
            if (sidebar) sidebar.classList.toggle('expanded');
            if (mainWrapper) mainWrapper.classList.toggle('expanded');
            
            // Save state
            const isCollapsed = sidebar && !sidebar.classList.contains('expanded');
            localStorage.setItem('sidebar-collapsed', isCollapsed);
        }
    }

    // Initialize Sidebar State (Desktop)
    if (window.innerWidth > 768) {
        const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
        if (isCollapsed) {
            if (sidebar) sidebar.classList.remove('expanded');
            if (mainWrapper) mainWrapper.classList.remove('expanded');
        } else {
            if (sidebar) sidebar.classList.add('expanded');
            if (mainWrapper) mainWrapper.classList.add('expanded');
        }
    }

    if (sidebarToggle) sidebarToggle.addEventListener('click', toggleSidebar);
    if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', toggleSidebar);
    if (sidebarOverlay) sidebarOverlay.addEventListener('click', toggleSidebar);

    // Close sidebar on link click (Mobile)
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 768 && sidebar && sidebar.classList.contains('mobile-active')) {
                toggleSidebar();
            }
        });
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 768) {
            if (sidebar) sidebar.classList.remove('mobile-active');
            if (sidebarOverlay) sidebarOverlay.classList.remove('active');
        }
    });

    // Notification Dropdown Toggle
    const notifBtn = document.getElementById('notif-btn');
    const notifDropdown = document.getElementById('notif-dropdown');

    if (notifBtn && notifDropdown) {
        notifBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            notifDropdown.style.display = notifDropdown.style.display === 'none' ? 'block' : 'none';
        });

        document.addEventListener('click', function(e) {
            if (!notifDropdown.contains(e.target) && e.target !== notifBtn) {
                notifDropdown.style.display = 'none';
            }
        });
    }
});
