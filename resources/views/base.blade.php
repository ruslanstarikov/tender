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
    <div class="drawer lg:drawer-open">
        <input id="drawer-toggle" type="checkbox" class="drawer-toggle" />
        
        <!-- Main Content -->
        <div class="drawer-content flex flex-col">
            <!-- Header -->
            <header class="navbar bg-base-200 px-6 shadow">
                <div class="flex-none lg:hidden">
                    <label for="drawer-toggle" class="btn btn-square btn-ghost">
                        <i class="iconoir-menu text-xl"></i>
                    </label>
                </div>
                <div class="flex-1">
                    <a class="text-lg font-bold" href="{{ route('admin.dashboard') }}">Window Tender</a>
                </div>
                <div class="flex-none">
                    @auth('admin')
                        <div class="dropdown dropdown-end">
                            <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                                <div class="w-10 rounded-full bg-primary text-primary-content flex items-center justify-center">
                                    <span class="text-sm font-semibold">{{ strtoupper(substr(auth('admin')->user()->name, 0, 1)) }}</span>
                                </div>
                            </label>
                            <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                                <li class="menu-title">
                                    <span>{{ auth('admin')->user()->name }}</span>
                                </li>
                                <li><a href="#"><i class="iconoir-user"></i> Profile</a></li>
                                <li><a href="#"><i class="iconoir-settings"></i> Settings</a></li>
                                <li class="border-t border-base-300 mt-2 pt-2">
                                    <form method="POST" action="{{ route('admin.logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left text-error">
                                            <i class="iconoir-log-out"></i>
                                            Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endauth
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 p-6">
                @yield('content')
            </main>
            
            <!-- Footer -->
            <footer class="footer items-center p-4 bg-base-200 text-base-content">
                <div class="items-center grid-flow-col">
                    <p>&copy; {{ date('Y') }} Window Tender</p>
                </div>
            </footer>
        </div>
        
        <!-- Sidebar -->
        <div class="drawer-side">
            <label for="drawer-toggle" class="drawer-overlay"></label>
            <aside class="min-h-full w-64 bg-base-300">
                <!-- Sidebar Header -->
                <div class="p-4 border-b border-base-content/10">
                    <h2 class="text-lg font-semibold text-base-content">Navigation</h2>
                </div>
                
                <!-- Menu -->
                <ul class="menu p-4 space-y-2 text-base-content">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="iconoir-dashboard-dots text-lg"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-3 {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                            <i class="iconoir-user text-lg"></i>
                            <span>Customers</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.contacts.index') }}" class="flex items-center gap-3 {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                            <i class="iconoir-user-circle text-lg"></i>
                            <span>Contacts</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.suppliers.index') }}" class="flex items-center gap-3 {{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}">
                            <i class="iconoir-truck text-lg"></i>
                            <span>Suppliers</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.tenders.index') }}" class="flex items-center gap-3 {{ request()->routeIs('admin.tenders.*') ? 'active' : '' }}">
                            <i class="iconoir-page-edit text-lg"></i>
                            <span>Tenders</span>
                        </a>
                    </li>
                </ul>
            </aside>
        </div>
    </div>
    @yield('scripts')
</body>
</html>