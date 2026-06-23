@extends('layouts.app')

@section('title', 'Register Restaurant')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100" style="font-family: inter;">
    
        <div class="col-12 col-md-10 col-lg-5">

            {{-- Application Card --}}
            <div class="card shadow-sm bg-white border-0" style="max-width: 600px; width: 100%; border-radius: 16px;">
                <div class="card-body p-4">
                    <h3 
                      class="card-title fw-bold text-center mb-4" 
                      style="color: #0a2540;">{{ __('Register Your Restaurant on Pin+81') }}
                    </h3>

                    <form method="POST" action="{{ route('register.restaurant') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Restarant Name --}}
                        <label for="restaurant_name" class="form-label fw-semibold">Restaurant Name *</label>

                        <input 
                        id="restaurant_name" 
                        type="text" 
                        class="form-control mb-3 @error('restaurant_name') is-invalid @enderror" 
                        name="restaurant_name" 
                        value="{{ old('restaurant_name') }}" 
                        placeholder="Restaurant Name" 
                        required 
                        autocomplete="restaurant_name" 
                        autofocus>

                        @error('restaurant_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                     
                        
                        {{-- Phone Number --}}
                        <label for="phone" class="form-label fw-semibold">Phone Number *</label>

                        <input  
                        id="phone_number" 
                        type="tel" 
                        class="form-control mb-3 @error('phone_number') is-invalid @enderror" name="phone_number" 
                        value="{{ old('phone_number') }}" 
                        placeholder="090-1234-5678" 
                        autocomplete="tel">

                        @error('phone_number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        
                        {{-- Address --}}
                        <div class="row">
                            <div class="col">
                                <label for="postal_code" class="form-label fw-semibold">
                                    Postal Code *
                                </label>

                                <input
                                    id="postal_code"
                                    type="text"
                                    class="form-control mb-3 @error('postal_code') is-invalid @enderror"
                                    name="postal_code"
                                    value="{{ old('postal_code') }}"
                                    placeholder="123-4567"
                                    required
                                    autocomplete="postal-code">

                                @error('postal_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col">
                                <label for="prefecture" class="form-label fw-semibold">
                                    Prefecture *
                                </label>

                                <input
                                    id="prefecture"
                                    type="text"
                                    class="form-control mb-3 @error('prefecture') is-invalid @enderror"
                                    name="prefecture"
                                    value="{{ old('prefecture') }}"
                                    placeholder="Tokyo"
                                    autocomplete="address-level1"
                                    required>

                                @error('prefecture')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <label for="address" class="form-label fw-semibold">
                            City & Street Address *
                        </label>

                        <input
                            id="address"
                            type="text"
                            class="form-control mb-3 @error('address') is-invalid @enderror"
                            name="address"
                            value="{{ old('address') }}"
                            placeholder="Shibuya-ku, 1-2-3 Shibuya"
                            autocomplete="street-address"
                            required>

                        @error('address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <label for="building" class="form-label fw-semibold">
                            Building
                        </label>

                        <input
                            id="building"
                            type="text"
                            class="form-control mb-3 @error('building') is-invalid @enderror"
                            name="building"
                            value="{{ old('building') }}"
                            placeholder="Sunshine Building 5F"
                            autocomplete="address-line2">

                        @error('building')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        {{-- Bussiness lisence --}}
                        <label for="business_license" class="form-label fw-semibold">
                            Business License (PDF) *
                        </label>

                        <input
                            id="business_license"
                            type="file"
                            class="form-control @error('business_license') is-invalid @enderror"
                            name="business_license"
                            accept=".pdf,application/pdf"
                            required>

                        @error('business_license')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <small class="text-muted mb-3 d-block">
                            Please upload your business license as a PDF file (max 5 MB).
                        </small>
                                                
                        {{-- Agreement check box --}}
                        <div class="form-check mb-4">
                            <input 
                            class="form-check-input custom-checkbox @error('agreement') is-invalid @enderror" 
                            type="checkbox" 
                            name="agreement" 
                            id="agreement" 
                            required
                            {{ old('agreement') ? 'checked' : '' }}>

                            @error('agreement')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
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

    .card{
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
        border-radius: 10px;
    }
    
    .form-control:focus {
        border-color: #cfb2c4;
        box-shadow: 0 0 0 0.2rem rgba(233, 192, 228, 0.25);
    }



    /* Check box */
    .custom-checkbox:checked {
        color: #0a2540;
        background-color: #8d4b75;
        border-color: #ca9cb9;
    }

    .custom-checkbox:focus {
        box-shadow: 0 0 0 0.2rem rgba(233, 192, 228, 0.25);
        border-color: #ca9cb9
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