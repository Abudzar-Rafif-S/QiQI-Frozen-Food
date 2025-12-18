@extends('auth.index')

@section('title', 'Login')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="mb-5 text-center col-md-6">
            <h2 class="heading-section">Welcome Back to Qiqi Frozen Food</h2>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-10">
            <div class="wrap d-md-flex">

                {{-- Bagian Kanan (Welcome) --}}
                <div class="p-4 text-center text-wrap p-lg-5 d-flex align-items-center order-md-last">
                    <div class="text w-100">
                        <h2>Welcome to Our Website</h2>
                        <p>Don't have an account?</p>

                        {{-- Jika punya route register --}}
                        <a href="{{ route('register') }}" class="btn btn-white btn-outline-white">Sign Up</a>
                    </div>
                </div>

                {{-- Bagian Kiri (Form Login) --}}
                <div class="p-4 login-wrap p-lg-5">

                    <div class="d-flex">
                        <div class="w-100">
                            <h3 class="mb-4">Sign In</h3>
                        </div>
                        <div class="w-100">
                        </div>
                    </div>

                    {{-- FORM LOGIN LARAVEL --}}
                    <form method="POST" action="{{ route('login') }}" class="signin-form">
                        @csrf

                        {{-- Email or Username --}}
                        <div class="mb-3 form-group">
                            <label class="label" for="email">Email</label>
                            <input
                                type="email"
                                name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="Email"
                                required
                                autofocus
                                value="{{ old('email') }}"
                            >
                            @error('email')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-3 form-group">
                            <label class="label" for="password">Password</label>
                            <input
                                type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Password"
                                required
                            >
                            @error('password')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="form-group">
                            <button type="submit" class="px-3 form-control btn btn-primary submit">
                                Sign In
                            </button>
                        </div>

                        {{-- Remember + Forgot --}}
                        <div class="form-group d-md-flex">
                            <div class="text-left w-50">
                                <label class="mb-0 checkbox-wrap checkbox-primary">
                                    Remember Me
                                    <input type="checkbox" name="remember">
                                    <span class="checkmark"></span>
                                </label>
                            </div>

                            <div class="w-50 text-md-right">
                                {{-- Jika ada route lupa password --}}
                                <a href="{{ route('password.request') }}">Forgot Password</a>
                            </div>
                        </div>
                    </form>

                </div> {{-- end login-wrap --}}

            </div> {{-- end wrap --}}
        </div>
    </div>
</div>
@endsection
