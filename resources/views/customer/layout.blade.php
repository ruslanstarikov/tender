<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="forest">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Customer Portal') - Window Tender Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/iconoir-icons/iconoir@main/css/iconoir.css">
</head>
<body class="min-h-screen bg-base-200">
    <!-- Navigation -->
    <nav class="navbar bg-base-100 shadow-lg">
        <div class="container mx-auto">
            <div class="flex-1">
                <a href="{{ route('customer.dashboard') }}" class="btn btn-ghost text-xl">
                    <i class="iconoir-home"></i>
                    Window Tenders
                </a>
            </div>
            
            @auth('customer')
            <div class="flex-none">
                <ul class="menu menu-horizontal px-1">
                    <li><a href="{{ route('customer.dashboard') }}" class="{{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">Dashboard</a></li>
                    <li><a href="{{ route('customer.tender-requests.index') }}" class="{{ request()->routeIs('customer.tender-requests.*') ? 'active' : '' }}">My Requests</a></li>
                    <li><a href="{{ route('customer.tender-requests.create') }}" class="btn btn-primary btn-sm">New Request</a></li>
                </ul>
                
                <div class="dropdown dropdown-end">
                    <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                        <div class="w-10 rounded-full bg-primary text-primary-content flex items-center justify-center">
                            <span class="text-sm font-semibold">{{ substr(auth('customer')->user()->first_name, 0, 1) }}{{ substr(auth('customer')->user()->last_name, 0, 1) }}</span>
                        </div>
                    </div>
                    <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
                        <li class="menu-title">{{ auth('customer')->user()->first_name }} {{ auth('customer')->user()->last_name }}</li>
                        <li><a href="#">Profile Settings</a></li>
                        <li>
                            <form method="POST" action="{{ route('customer.logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
            @else
            <div class="flex-none">
                <ul class="menu menu-horizontal px-1">
                    <li><a href="{{ route('customer.login') }}">Login</a></li>
                    <li><a href="{{ route('customer.register') }}" class="btn btn-primary btn-sm">Register</a></li>
                </ul>
            </div>
            @endauth
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success mx-4 mt-4">
            <i class="iconoir-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error mx-4 mt-4">
            <i class="iconoir-warning-triangle"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer footer-center p-10 bg-base-300 text-base-content mt-20">
        <div>
            <p>Copyright © {{ date('Y') }} - Window Tender Management System</p>
        </div>
    </footer>
</body>
</html>