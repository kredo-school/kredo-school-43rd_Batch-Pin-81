@extends('layouts.app')

@section('title', 'Sign up')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100 bg-light">
    
        <div class="col-12 col-md-10 col-lg-5">

            {{-- Sign up Card --}}
            <div class="card p-4 shadow-sm border-0 signup-card bg-white" style="max-width: 600px; width: 100%; border-radius: 16px;">
                <div class="card-body p-4">
                    <h3 class="card-title fw-bold text-center mb-4" style="color: #0a2540;">{{ __('Create an Account') }}</h3>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        {{-- Name --}}
                        <div class="row mb-3">
                            <div class="col">
                                <label for="first_name" class="form-label fw-semibold">{{ __('First Name') }}</label>

                                <div class="col">
                                    <input 
                                    id="first_name" 
                                    type="text" 
                                    class="form-control @error('first_name') is-invalid @enderror" name="first_name" 
                                    value="{{ old('first_name') }}" 
                                    placeholder="First Name" 
                                    required autocomplete="first_name" 
                                    autofocus>

                                    @error('first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col">
                                <label for="last_name" class="form-label fw-semibold">{{ __('Last Name') }}</label>

                                <div class="col">
                                    <input 
                                    id="last_name" 
                                    type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                    name="last_name" 
                                    value="{{ old('last_name') }}" 
                                    placeholder="Last Name" 
                                    required 
                                    autocomplete="last_name">

                                    @error('last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Email --}}
                        <label for="email" class="form-label fw-semibold">{{ __('Email') }}</label>

                        <input 
                        id="email" 
                        type="email" 
                        class="form-control mb-3 @error('email') is-invalid @enderror" 
                        name="email" 
                        value="{{ old('email') }}" 
                        placeholder="your@email.com" 
                        required 
                        autocomplete="email">

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                            
                        {{-- Passward --}}
                        <label for="password" class="form-label fw-semibold">{{ __('Password') }}</label>

                        <input 
                        id="password" 
                        type="password" 
                        class="form-control mb-3 @error('password') is-invalid @enderror" 
                        name="password" 
                        placeholder="••••••••" 
                        required 
                        autocomplete="new-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        
                        <label for="password-confirm" class="form-label fw-semibold">{{ __('Confirm Password') }}</label>

                        <input 
                        id="password-confirm" 
                        type="password" 
                        class="form-control mb-3" 
                        name="password_confirmation" 
                        placeholder="••••••••" 
                        required 
                        autocomplete="new-password">
                        
                        {{-- Agreement check box --}}
                        <div class="form-check mb-4">
                            <input 
                            class="form-check-input custom-checkbox" 
                            type="checkbox" 
                            name="agreement" 
                            id="agreement" 
                            {{ old('agreement') ? 'checked' : '' }}>

                            {{-- <label class="form-check-label" for="agreement">
                                I agree to the
                                <a href="{{ route('terms') }}" target="_blank">
                                    Terms of Service
                                </a>
                                and
                                <a href="{{ route('privacy') }}" target="_blank">
                                    Privacy Policy
                                </a>
                            </label> --}}
                            {{-- No route version ↓ --}}
                                <label class="form-check-label" for="agreement">
                                    I agree to the
                                    <a href="/terms" target="_blank">Terms of Service</a>
                                    and
                                    <a href="/privacy" target="_blank">Privacy Policy</a>
                                </label> 
                            
                        </div>
                         
                        {{-- Sign in Button --}}
                            <button type="submit" class="btn w-100 fw-semibold custom-btn-a mb-3">
                                {{ __('Create Account') }}
                            </button>
                       
                        {{-- Already have an account --}}
                        <p class="text-center text-muted small mb-3">
                            {{ __("Already have an account?") }} <a href="{{ route('login') }}" >{{ __('Login') }}</a>
                        </p>

                    </form>
                </div>
            </div>
        
        </div>
</div>

<style>
    .min-vh-100 {
        min-height: 100vh;
    }

    
    
    .form-control:focus {
        border-color: #cfb2c4;
        box-shadow: 0 0 0 0.2rem rgba(233, 192, 228, 0.25);
    }

    /* Label decoration */
    label{
        color: #0a2540;
    }


    /* Check box */
    .custom-checkbox:checked {
        color: #0a2540;
        background-color: #8d4b75;
        border-color: #ca9cb9;
    }

    .custom-checkbox:focus {
        box-shadow: 0 0 0 0.2rem rgba(233, 192, 228, 0.25);
        border-color: #ca9cb9;
    }

    a{
        color: #0a2540;
        font-weight: 600;
        text-decoration: none;
    }
    a:hover {
        text-decoration: underline;
    }

    /* Create an Account button */
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
</style>
@endsection
