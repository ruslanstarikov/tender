@extends('customer.layout')

@section('title', 'Login')

@section('content')
<div class="max-w-md mx-auto bg-base-100 p-6 rounded-lg shadow">
    <h2 class="text-2xl font-semibold mb-4">Customer Login</h2>
    <p class="text-base-content/60 mb-6">Sign in to manage your tender requests</p>

    @if ($errors->any())
        <div class="alert alert-error mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('customer.login') }}">
        @csrf

        <div class="grid grid-cols-1 gap-4">
            <div>
                <label class="label"><span class="label-text">Email Address</span></label>
                <input type="email" name="email" value="{{ old('email') }}" class="input input-bordered w-full" required autofocus>
            </div>

            <div>
                <label class="label"><span class="label-text">Password</span></label>
                <input type="password" name="password" class="input input-bordered w-full" required>
            </div>

            <label class="label cursor-pointer justify-start gap-2">
                <input type="checkbox" name="remember" class="checkbox">
                <span class="label-text">Remember me</span>
            </label>
        </div>

        <div class="mt-6 flex gap-4">
            <button type="submit" class="btn btn-primary flex-1">Sign In</button>
            <a href="{{ route('customer.register') }}" class="btn btn-outline">Register</a>
        </div>
    </form>
</div>
@endsection