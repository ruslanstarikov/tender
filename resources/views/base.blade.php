<!DOCTYPE html>
<html lang="en" data-theme="forest">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tender')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/iconoir-icons/iconoir@main/css/iconoir.css" />
</head>
<body class="bg-base-100 text-base-content">
<div class="min-h-screen flex flex-col">
    <header class="navbar bg-base-200 px-6 shadow">
        <div class="flex-1">
            <a class="text-lg font-bold" href="/">Window Tender</a>
        </div>
    </header>

    <main class="flex-1 p-6">
        @yield('content')
    </main>
    <footer class="footer items-center p-4 bg-base-200 text-base-content">
        <div class="items-center grid-flow-col">
            <p>&copy; {{ date('Y') }} Window Tender</p>
        </div>
    </footer>
</div>
@yield('scripts')
</body>
</html>
