@extends('auth.index')

@section('title', 'Register')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="mb-5 text-center col-md-6">
            <h2 class="heading-section">Join Qiqi Frozen Food</h2>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-10">
            <div class="wrap d-md-flex">

                {{-- Bagian Kanan --}}
                <div class="p-4 text-center text-wrap p-lg-5 d-flex align-items-center order-md-last">
                    <div class="text w-100">
                        <h2>Welcome to Our Website</h2>
                        <p>Already have an account?</p>

                        <a href="{{ route('login') }}" class="btn btn-white btn-outline-white">Sign In</a>
                    </div>
                </div>

                {{-- Bagian Kiri (Form Register) --}}
                <div class="p-4 login-wrap p-lg-5">

                    <div class="d-flex">
                        <div class="w-100">
                            <h3 class="mb-4">Sign Up</h3>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="signin-form">
                        @csrf

                        {{-- Name --}}
                        <div class="mb-3 form-group">
                            <label class="label" for="name">Full Name</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Your full name"
                                required
                                value="{{ old('name') }}"
                            >
                            @error('name')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3 form-group">
                            <label class="label" for="email">Email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="Email"
                                required
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
                                id="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Password"
                                required
                            >
                            @error('password')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div class="mb-3 form-group">
                            <label class="label" for="password_confirmation">Confirm Password</label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="form-control"
                                placeholder="Confirm password"
                                required
                            >
                        </div>

                        {{-- Address --}}
                        <div class="mb-3 form-group">
                            <label class="label" for="address">Address</label>
                            <input
                                type="text"
                                id="address"
                                name="address"
                                class="form-control @error('address') is-invalid @enderror"
                                placeholder="Your full address"
                                required
                                value="{{ old('address') }}"
                            >
                            @error('address')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="mb-4 form-group">
                            <label class="label" for="phone">Phone Number</label>
                            <input
                                type="text"
                                id="phone"
                                name="phone"
                                class="form-control @error('phone') is-invalid @enderror"
                                placeholder="Your phone number"
                                required
                                value="{{ old('phone') }}"
                            >
                            @error('phone')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Submit --}}
                        <div class="form-group">
                            <button type="submit" class="px-3 form-control btn btn-primary submit">
                                Register
                            </button>
                        </div>

                    </form>

                </div>{{-- end register-wrap --}}

            </div>{{-- end wrap --}}
        </div>
    </div>
</div>
@endsection
