<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Authentication - Taxi Platform')</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Global Theme CSS -->
    <link rel="stylesheet" href="{{ asset('css/root/theme.css') }}">
    
    <!-- Theme Script -->
    <script>
        const savedTheme = localStorage.getItem('taxi_theme') || 'dark';
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>
    
    <!-- Page Specific Styles -->
    @yield('styles')
</head>
<body>

    @yield('content')

    <!-- Page Specific Scripts -->
    @yield('scripts')
</body>
</html>
