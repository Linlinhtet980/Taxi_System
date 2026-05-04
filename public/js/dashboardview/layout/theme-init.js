/**
 * Theme and Sidebar State Initialization
 * Prevents theme/layout flicker on page load
 */
(function() {
    const savedTheme = localStorage.getItem('theme') || 'dark';
    document.documentElement.setAttribute('data-theme', savedTheme);
    
    const sidebarState = localStorage.getItem('sidebar-collapsed');
    if (sidebarState === 'true') {
        document.documentElement.classList.add('sidebar-is-collapsed');
    }
})();
