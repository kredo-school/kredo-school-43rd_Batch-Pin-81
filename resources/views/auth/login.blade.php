@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100" style="background-color: #fffefc;">
    
    <div class="col-12 col-md-10 col-lg-5">

        <!-- Login Card -->
        <div class="card shadow-sm mx-auto">
            <div class="card-body p-4">
                <h3 class="card-title fw-bold mb-1 text-center mb-2" style="color: #0a2540;">{{ __('Login') }}</h3>
                <p class="text-muted small mb-4 text-center">Access your account to make reservations or  manage your restaurant</p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Field -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold" style="color: #0a2540;">{{ __('Email') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="your@email.com" required autocomplete="email" autofocus>
                            
                        @error('email')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold" style="color: #0a2540;">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="••••••••" required autocomplete="current-password">
                        @error('password')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Remember and Forgot Password -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input custom-checkbox" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                {{ __('Remember me') }}
                            </label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">
                                {{ __('Forgot password?') }}
                            </a>
                        @endif
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="btn w-100 fw-semibold mb-3 custom-btn-a">
                        {{ __('Login') }}
                    </button>

                    <!-- Guest Login Button -->
                    <button type="button" class="btn w-100 fw-semibold mb-3 custom-btn-b">
                        {{ __('Login as a Guest') }}
                    </button>

                    <!-- Sign Up Link -->
                    <p class="text-center text-muted small mb-3">
                        {{ __("Don't have an account?") }} <a href="{{ route('register') }}">{{ __('Sign up') }}</a>
                    </p>

                    <!-- Partner Link -->
                    <p class="text-center">
                        <a href="#">{{ __('Partner with us') }}</a>
                    </p>
                </form>
            </div>
        </div>

        <!-- Back to Home -->
        {{-- <div class="text-center mt-4">
            <a href="/" class="text-decoration-none text-muted small">
                ← {{ __('Back to home') }}
            </a>
        </div> --}}
    </div>
</div>

<style>
    .min-vh-100 {
        min-height: 100vh;
    }

    .card{
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
        border: 1px solid #FCE7F3;
        border-radius: 10px;
    }
    
    .form-control:focus {
        border-color: #cfb2c4;
        box-shadow: 0 0 0 0.2rem rgba(233, 192, 228, 0.25);
    }
    
    .custom-checkbox:checked {
        color: #0a2540;
        background-color: #8d4b75;
        border-color: #ca9cb9;
    }

    .custom-checkbox:focus {
        box-shadow: 0 0 0 0.2rem rgba(233, 192, 228, 0.25);
        border-color: #ca9cb9
    }

    /* Login button */
    .custom-btn-a {
        background-color: #FCE7F3;
        color: #0a2540; /* text color */
        cursor: pointer;
        transition: 0.3s;
    }

    /* mouse hover effect */
    .custom-btn-a:hover {
        background-color: #fdd6eb;
        color: #0a2a5e;
    }

    /* login as a guest button */
    .custom-btn-b {
        background-color: transparent;
        color: #0a2540; /* text color */
        border: 1px solid #0a2540;
        cursor: pointer;
        transition: 0.3s;
    }

    /* mouse hover effect */
    .custom-btn-b:hover {
        background-color: #0a2540;
        color: white;
        border-color: #0a2540;
    }

    /* Link decorations */
    a{
        color: #0a2540;
        font-weight: 600;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }
</style>
@endsection
