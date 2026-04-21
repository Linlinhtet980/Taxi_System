<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taxi | Admin Dashboard</title>
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('css')
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
</head>
<body data-theme="dark">

    <!-- Floating Expandable Sidebar -->
    <aside class="sidebar glass" id="sidebar">
        <div class="logo-container" id="sidebar-toggle">
            <i class="fa-solid fa-taxi"></i>
            <span class="logo-text">TaxiAdmin</span>
        </div>
        
        <ul class="nav-links">
            <li>
                <a href="#" class="nav-link">
                    <i class="fa-solid fa-gauge"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('drivers.index') }}" class="nav-link {{ request()->routeIs('drivers.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i>
                    <span class="nav-text">Drivers List</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-link">
                    <i class="fa-solid fa-car"></i>
                    <span class="nav-text">Fleet Management</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-link">
                    <i class="fa-solid fa-gears"></i>
                    <span class="nav-text">Settings</span>
                </a>
            </li>
        </ul>

        <div class="logout-link" style="margin-top: auto; width: 100%;">
            <a href="#" class="nav-link">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span class="nav-text">Logout</span>
            </a>
        </div>
    </aside>
    
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <!-- Main Content Wrapper -->
    <div class="main-wrapper" id="main-wrapper">
        <header>
            <div class="mobile-menu-btn" id="mobile-menu-btn">
                <i class="fa-solid fa-bars"></i>
            </div>

            <div class="search-bar">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="global-search" placeholder="Explore drivers, analytics...">
            </div>

            <div class="user-area">
                <button class="theme-toggle" id="theme-toggle" title="Toggle Theme">
                    <i class="fa-solid fa-moon" id="theme-icon"></i>
                </button>
                
                <div class="notifications" style="cursor: pointer; font-size: 1.25rem;">
                    <i class="fa-regular fa-bell"></i>
                </div>
                
                <div class="user-card">
                    <p class="user-name">James Radcliffe</p>
                    <p class="user-role">Administrator</p>
                </div>
                
                <div class="user-avatar-box">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
            </div>
        </header>

        @if(session('success'))
            <div class="glass animate-fade" style="padding: 1.25rem; border-left: 4px solid var(--accent-purple); margin-bottom: 2rem;">
                <p style="color: var(--accent-purple); font-weight: 700;">✨ {{ session('success') }}</p>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Interactivity Script -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const mainWrapper = document.getElementById('main-wrapper');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const themeToggle = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');
        const body = document.body;

        function setTheme(theme) {
            body.setAttribute('data-theme', theme);
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
            if (theme === 'light') {
                themeIcon.classList.replace('fa-moon', 'fa-sun');
            } else {
                themeIcon.classList.replace('fa-sun', 'fa-moon');
            }
        }

        themeToggle.addEventListener('click', () => {
            const currentTheme = body.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            setTheme(newTheme);
        });

        const initialTheme = localStorage.getItem('theme') || 'dark';
        setTheme(initialTheme);

        // Sidebar Toggle (Desktop & Mobile)
        function toggleSidebar() {
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('mobile-active');
                sidebarOverlay.classList.toggle('active');
            } else {
                sidebar.classList.toggle('expanded');
                mainWrapper.classList.toggle('expanded');
            }
        }

        sidebarToggle.addEventListener('click', toggleSidebar);
        mobileMenuBtn.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', toggleSidebar);

        // Close sidebar on link click (Mobile)
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768 && sidebar.classList.contains('mobile-active')) {
                    toggleSidebar();
                }
            });
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                sidebar.classList.remove('mobile-active');
                sidebarOverlay.classList.remove('active');
            }
        });
    </script>
</body>
</html>
