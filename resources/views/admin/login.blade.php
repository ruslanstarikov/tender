<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>
    @vite('resources/css/app.css')
    <script src="https://unpkg.com/htmx.org@1.8.4"></script>
</head>
<body class="bg-base-200">
<div class="min-h-screen flex items-center justify-center">
    <div class="card w-96 shadow-lg bg-base-100">
        <div class="card-body">
            <h2 class="card-title text-2xl mb-4">Admin Login</h2>
            <form hx-post="{{ route('admin.login.submit') }}"
                  hx-target="#login-feedback"
                  hx-swap="outerHTML"
                  method="POST">
                @csrf
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Email</span>
                    </label>
                    <input type="email" name="email" placeholder="admin@example.com" class="input input-bordered" required>
                </div>
                <div class="form-control mt-4">
                    <label class="label">
                        <span class="label-text">Password</span>
                    </label>
                    <input type="password" name="password" placeholder="Your Password" class="input input-bordered" required>
                </div>
                <div id="login-feedback"></div>
                <div class="form-control mt-6">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
