<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Mobile Safari specific -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="format-detection" content="telephone=no">
    
    <!-- Browser compatibility -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#3b82f6">
    
    <title>@yield('title', 'Form Vaksin')</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        
        /* Fix untuk iOS Safari */
        @supports (-webkit-touch-callout: none) {
            input[type="file"] {
                font-size: 16px !important; /* Mencegah zoom di iOS */
            }
        }
        
        /* Fix untuk touch events di mobile */
        * {
            -webkit-tap-highlight-color: transparent;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        @yield('content')
    </div>
    
    @stack('scripts')
</body>
</html>
