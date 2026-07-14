@extends('layouts.app')

@section('title', 'Book restaurant')

@section('content')

<div class="container my-5 d-flex justify-content-center" style="font-family: inter">
    <div class="card p-4 shadow-sm border-0 signup-card bg-white" style="max-width: 600px; width: 100%; border-radius: 16px;">
        <div class="card-body">
            <a href="{{ route('restaurant.show', $restaurant) }}"
                class="btn btn-link text-decoration-none text-secondary p-0 mb-3">
                <i class="fa-solid fa-chevron-left me-1"></i> Back
            </a>
            
                
            <h3 class="fw-bold text-navy mb-2">
                Complete Your Reservation{{ auth()->guest() ? ' (Guest)' : '' }}
            </h3>
            @guest
                <p class="text-muted small mb-4">
                    Booking as a guest. Want to save your favorites and view your booking history?
                    <a class="text-navy fw-semibold text-decoration-none hover-underline" href="{{ route('register') }}">Create an account</a>
                </p>
            @endguest

            <p class="text-muted small mb-4">{{ $restaurant->restaurant_name }}</p>

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

                    @if ($maxPartySize < 1)
                        <div class="alert alert-warning small">
                            This restaurant does not currently have an active table available for online booking.
                        </div>
                    @endif

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="reservation_date" class="form-label fw-semibold text-navy small">Date</label>
                            <input type="date"
                                class="form-control custom-input @error('reservation_date') is-invalid @enderror"
                                id="reservation_date" name="reservation_date"
                                value="{{ old('reservation_date', $selectedDate) }}" min="{{ now()->format('Y-m-d') }}"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label for="num_of_people" class="form-label fw-semibold text-navy small">Guests</label>
                            <select class="form-select custom-input @error('num_of_people') is-invalid @enderror"
                                id="num_of_people" name="num_of_people" required {{ $maxPartySize < 1 ? 'disabled' : '' }}>
                                @for ($i = 1; $i <= $maxPartySize; $i++)
                                    <option value="{{ $i }}"
                                        {{ (int) old('num_of_people', $partySize) === $i ? 'selected' : '' }}>
                                        {{ $i }} {{ $i === 1 ? 'Person' : 'People' }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="reservation_time" class="form-label fw-semibold text-navy small">Available Time</label>
                        <select class="form-select custom-input @error('reservation_time') is-invalid @enderror"
                            id="reservation_time" name="reservation_time" required
                            {{ $maxPartySize < 1 ? 'disabled' : '' }}>
                            <option value="">Select time</option>
                            @foreach ($availableSlots as $time)
                                <option value="{{ $time }}"
                                    {{ old('reservation_time', $selectedTime) === $time ? 'selected' : '' }}>
                                    {{ $time }}
                                </option>
                            @endforeach
                        </select>
                        <div id="availabilityMessage" class="form-text">
                            @if (empty($availableSlots))
                                No available time slots for the selected date and party size.
                            @endif
                        </div>
                    </div>

                    <hr class="text-muted my-4 opacity-25">
                    <h5 class="fw-bold text-navy mb-3">Your Information</h5>

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold text-navy small">Full Name</label>
                        <input type="text" class="form-control custom-input" id="name" name="name"
                            value="{{ old('name', auth()->check() ? trim(auth()->user()->first_name . ' ' . auth()->user()->last_name) : '') }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold text-navy small">Email</label>
                        <input type="email" class="form-control custom-input" id="email" name="email"
                            value="{{ old('email', auth()->user()->email ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label fw-semibold text-navy small">Phone <span
                                class="text-muted fw-normal">(Optional)</span></label>
                        <input type="tel" class="form-control custom-input" id="phone" name="phone"
                            value="{{ old('phone') }}">
                    </div>

                    <div class="mb-4">
                        <label for="requests" class="form-label fw-semibold text-navy small">Special Requests <span
                                class="text-muted fw-normal">(Optional)</span></label>
                        <textarea class="form-control custom-input" id="requests" name="requests" rows="3">{{ old('requests') }}</textarea>
                    </div>

                    <button type="submit" id="submitBooking" class="btn w-100 fw-semibold custom-btn-a"
                        {{ $maxPartySize < 1 || empty($availableSlots) ? 'disabled' : '' }}>
                        Confirm Reservation
                    </button>
                </form>
            </div>
        </div>
    </div>

    <style>
        :root {
            --navy-base: #0a2540;
        }

        .text-navy {
            color: var(--navy-base);
        }

        .custom-input:focus {
            border-color: #cfb2c4;
            box-shadow: 0 0 0 .2rem rgba(233, 192, 228, .25);
        }

        .custom-btn-a {
            background-color: #FCE7F3;
            color: #0a2540;
            transition: .3s;
        }

        .custom-btn-a:hover:not(:disabled) {
            background-color: #fdd6eb;
            color: #0a2a5e;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dateInput = document.getElementById('reservation_date');
            const guestsInput = document.getElementById('num_of_people');
            const timeInput = document.getElementById('reservation_time');
            const message = document.getElementById('availabilityMessage');
            const submitButton = document.getElementById('submitBooking');
            const endpoint = @json(route('booking.availability', $restaurant));

            async function refreshAvailability() {
                if (!dateInput.value || !guestsInput.value) return;

                timeInput.disabled = true;
                submitButton.disabled = true;
                message.textContent = 'Checking availability...';

                try {
                    const url = new URL(endpoint, window.location.origin);
                    url.searchParams.set('date', dateInput.value);
                    url.searchParams.set('guests', guestsInput.value);

                    const response = await fetch(url, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    if (!response.ok) throw new Error('Unable to load availability.');

                    const data = await response.json();
                    const previousValue = timeInput.value;
                    timeInput.innerHTML = '<option value="">Select time</option>';

                    data.slots.forEach(slot => {
                        const option = document.createElement('option');
                        option.value = slot;
                        option.textContent = slot;
                        option.selected = slot === previousValue;
                        timeInput.appendChild(option);
                    });

                    timeInput.disabled = false;
                    submitButton.disabled = data.slots.length === 0;
                    message.textContent = data.slots.length ?
                        `${data.slots.length} available time slot(s).` :
                        'No available time slots for the selected date and party size.';
                } catch (error) {
                    timeInput.innerHTML = '<option value="">Select time</option>';
                    message.textContent = error.message;
                }
            }

            dateInput.addEventListener('change', refreshAvailability);
            guestsInput.addEventListener('change', refreshAvailability);
        });
    </script>
@endsection
