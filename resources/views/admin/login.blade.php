<!DOCTYPE html>
<html lang="en" data-theme="forest">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - Window Tender</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/iconoir-icons/iconoir@main/css/iconoir.css" />
</head>
<body class="bg-base-200">
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="card w-full max-w-md shadow-xl bg-base-100">
        <div class="card-body">
            <!-- Header -->
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-primary mb-2">Window Tender</h1>
                <h2 class="text-xl font-semibold mb-1">Admin Portal</h2>
                <p class="text-base-content/60">Sign in to access the admin dashboard</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="alert alert-error mb-4">
                    <i class="iconoir-warning-triangle"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Success Messages -->
            @if (session('success'))
                <div class="alert alert-success mb-4">
                    <i class="iconoir-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
                @csrf
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Email Address</span>
                    </label>
                    <div class="relative">
                        <input type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="admin@example.com" 
                               class="input input-bordered w-full pr-10 @error('email') input-error @enderror" 
                               required 
                               autofocus>
                        <i class="iconoir-mail absolute right-3 top-1/2 transform -translate-y-1/2 text-base-content/40"></i>
                    </div>
                    @error('email')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Password</span>
                    </label>
                    <div class="relative">
                        <input type="password" 
                               name="password" 
                               placeholder="Your password" 
                               class="input input-bordered w-full pr-10 @error('password') input-error @enderror" 
                               required>
                        <i class="iconoir-lock absolute right-3 top-1/2 transform -translate-y-1/2 text-base-content/40"></i>
                    </div>
                    @error('password')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>
                
                <div class="form-control">
                    <label class="label cursor-pointer justify-start gap-3">
                        <input type="checkbox" name="remember" value="1" class="checkbox checkbox-sm">
                        <span class="label-text">Remember me</span>
                    </label>
                </div>
                
                <div class="form-control mt-6">
                    <button type="submit" class="btn btn-primary w-full">
                        <i class="iconoir-log-in"></i>
                        Sign In
                    </button>
                </div>
            </form>

            <!-- Footer -->
            <div class="text-center mt-6 pt-4 border-t border-base-300">
                <p class="text-sm text-base-content/60">
                    Access restricted to authorized administrators only
                </p>
            </div>
        </div>
    </div>
</div>
</body>
</html>