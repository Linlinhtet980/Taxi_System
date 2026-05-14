<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Taxi Luxury Fleet')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Core Theme CSS -->
    <link rel="stylesheet" href="{{ asset('css/root/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/root/ui-components.css') }}">
    
    @stack('css')

    <script>
        // Universal Theme Initialization
        (function() {
            const savedTheme = localStorage.getItem('taxi_theme') || 'dark';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
</head>
<body style="background: var(--bg-main); background-image: var(--bg-gradient); background-attachment: fixed; min-height: 100vh; color: var(--text-main); transition: var(--transition);">

    @yield('master_content')

    <!-- Common Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('theme-toggle');
            if (themeToggle) {
                themeToggle.addEventListener('click', () => {
                    const currentTheme = document.documentElement.getAttribute('data-theme');
                    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                    
                    document.documentElement.setAttribute('data-theme', newTheme);
                    localStorage.setItem('taxi_theme', newTheme);
                    
                    // Update Icons if exists
                    const themeIcon = document.getElementById('theme-icon');
                    if (themeIcon) {
                        themeIcon.classList.toggle('fa-moon');
                        themeIcon.classList.toggle('fa-sun');
                    }
                });
            }
        });
    </script>

    @stack('js')
</body>
</html>
