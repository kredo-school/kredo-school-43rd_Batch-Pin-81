@extends('layouts.app')

@section('title', 'Book restaurant')

@section('content')

<div class="container my-5 d-flex justify-content-center" style="font-family: inter">
    <div class="card p-4 shadow-sm border-0 signup-card bg-white" style="max-width: 600px; width: 100%; border-radius: 16px;">
        <div class="card-body">
            <h3 class="fw-bold text-navy mb-2">
                Complete Your Reservation{{ auth()->guest() ? ' (Guest)' : '' }}
            </h3>
            @guest
                <p class="text-muted small mb-4">
                    Booking as a guest. Want to save your favorites and view your booking history?
                    <a class="text-navy fw-semibold text-decoration-none hover-underline" href="{{ route('register') }}">Create an account</a>
                </p>
            @endguest

            <form action="{{ route('booking.store') }}" method="POST">
                @csrf
                <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">

                @if ($errors->any())
                    <div class="alert alert-danger py-2 small mb-3">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="reservation_date" class="form-label fw-semibold text-navy small">Date</label>
                        <input type="date"
                            class="form-control custom-input @error('reservation_date') is-invalid @enderror"
                            id="reservation_date"
                            name="reservation_date"
                            value="{{ old('reservation_date', $selectedDate) }}"
                            min="{{ date('Y-m-d') }}"
                            required>
                        @error('reservation_date')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="reservation_time" class="form-label fw-semibold text-navy small">Time</label>
                        <select class="form-select custom-input @error('reservation_time') is-invalid @enderror" id="reservation_time" name="reservation_time" required>
                            @if($selectedTime)
                                <option value="{{ $selectedTime }}" selected>
                                    {{ $selectedTime }}
                                </option>
                            @else
                                <option value="" selected disabled>
                                    Select time
                                </option>
                            @endif

                            @foreach($availableSlots ?? [] as $time)
                                @if($time != $selectedTime)
                                    <option value="{{ $time }}" {{ $time == $selectedTime ? 'selected' : '' }}>
                                        {{ $time }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        @error('reservation_time')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="num_of_people" class="form-label fw-semibold text-navy small">Number of Guests</label>
                    <select class="form-select custom-input @error('num_of_people') is-invalid @enderror" id="num_of_people" name="num_of_people" required>
                        <option value="" selected disabled>Select guests</option>
                        <option value="1">1 Person</option>
                        <option value="2">2 People</option>
                        <option value="3">3 People</option>
                        <option value="4">4 People</option>
                        <option value="5">5+ People</option>
                    </select>
                    @error('num_of_people')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="text-muted my-4 opacity-25">

                <h5 class="fw-bold text-navy mb-3">Your Information</h5>

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold text-navy small">Full Name</label>
                    <input type="text" class="form-control custom-input" id="name" name="name" placeholder="John Doe" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold text-navy small">Email</label>
                    <input type="email" class="form-control custom-input" id="email" name="email" placeholder="name@example.com" required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label fw-semibold text-navy small">Phone <span class="text-muted fw-normal">(Optional)</span></label>
                    <input type="tel" class="form-control custom-input" id="phone" name="phone">
                </div>

                <div class="mb-4">
                    <label for="requests" class="form-label fw-semibold text-navy small">Special Requests <span class="text-muted fw-normal">(Optional)</span></label>
                    <textarea class="form-control custom-input" id="requests" name="requests" rows="3" placeholder="Dietary restrictions, allergies, special occasions..."></textarea>
                </div>

                <button type="submit" class="btn w-100 fw-semibold custom-btn-a">Confirm Reservation</button>
            </form>
        </div>
    </div>
</div>

<style>
:root {
    --navy-base: #0a2540;
    --input-bg: #f8f9fa;
}

.text-navy {
    color: var(--navy-base);
}

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

.hover-underline:hover {
    text-decoration: underline !important;
}

.custom-input:focus {
    border-color: #cfb2c4;
    box-shadow: 0 0 0 0.2rem rgba(233, 192, 228, 0.25);
}

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