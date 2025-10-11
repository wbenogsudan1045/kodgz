<!-- app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Add inline styles as fallback if Tailwind isn't compiling -->
    <style>
        .nav-bg-green {
            background-color: #86efac !important;
        }

        .search-bg-yellow {
            background-color: #fef3c7 !important;
        }

        .logo-bg-yellow {
            background-color: #fde68a !important;
        }

        .main-bg-pink {
            background-color: #fdf2f8 !important;
        }
    </style>
</head>

<body class="font-sans antialiased">
    <!-- Whole background -->
    <div class="min-h-screen flex flex-col">

        <!-- Navbar -->
        @include('layouts.navigation')

        <!-- Page Content -->
        <main class="flex-1 main-bg-pink" style="background-color: #fdf2f8;">
            {{ $slot }}
        </main>
    </div>
</body>

</html>