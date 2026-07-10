@extends('layouts.app')

@section('title', 'Book restaurant')

@section('content')

<div class="container py-5 bg-light" style="font-family: inter">
    <div class="text-center mb-4">
        <div class="d-flex align-items-center justify-content-center fs-1 success-icon-circle">
            <i class="bi bi-check-lg"></i>
        </div>
        <h1 class="fw-bold h2 mb-1" style="color: #0a2540;">Reservation Confirmed!</h1>
        <p class="text-muted">Your booking has been confirmed successfully</p>
    </div>

    <div class="card-custom shadow-sm bg-white" >
        <div class="card-custom-header" style="color: #fff;">
            <h4 class="mb-1 fw-bold" >{{ $reservation->restaurant->restaurant_name ?? 'Restaurant' }}</h4>
            <div class="confirmation-label">
                Reservation Code: <span class="confirmation-code" style="color: #fff;">{{ $reservationCode }}</span>
            </div>
        </div>

        <div class="p-4 p-md-5">
            <h5 class="fw-bold mb-3 text-primary-dark" style="color: #0a2540;">Reservation Details</h5>
            <div class="row g-3 pb-3 mb-4 border-bottom" style="color: #0a2540;">
                <div class="col-4 d-flex align-items-start">
                    <i class="bi bi-calendar3 me-2 detail-icon fs-5"></i>
                    <div>
                        <div class="detail-label text-secondary">Date</div>
                        <div class="fw-semibold">{{ $reservation->reservation_date }}</div>
                    </div>
                </div>
                <div class="col-4 d-flex align-items-start">
                    <i class="bi bi-clock me-2 fs-5 detail-icon"></i>
                    <div>
                        <div class="detail-label text-secondary">Time</div>
                        <div class="fw-semibold">{{ $reservation->reservation_time }}</div>
                    </div>
                </div>
                <div class="col-4 d-flex align-items-start">
                    <i class="bi bi-people me-2 detail-icon fs-5"></i>
                    <div>
                        <div class="detail-label text-secondary">Guests</div>
                        <div class="fw-semibold">{{ $reservation->num_of_people }}</div>
                    </div>
                </div>
            </div>
            
            <div class="email-alert mb-4 d-flex align-items-center">
                <i class="bi bi-check2 me-2"></i>
                <span>A confirmation email has been sent to {{ $reservation->user->email }}</span>
            </div>

            <div class="whats-next-box mb-4"  style="color: #0a2540;">
                <h6 class="fw-bold mb-3">What's Next?</h6>
                <ul>
                    <li>If you will be late, please notify the restaurant via My Reservations.</li>
                    <li>Show your reservation code at the restaurant</li>
                    <li>If you need to cancel, please do so via My Reservations.</li>
                </ul>
            </div>

            <div class="row g-3">
                <div class="col-sm-6">
                    <a href="#" class="btn btn-home w-100 fw-semibold custom-btn-b">
                        <i class="fa-solid fa-chevron-left"></i>
                        Back to Home
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('my_reservations') }}" class="btn custom-btn-a w-100 fw-semibold" style="color: #0a2540;">My Reservations</a>
                </div>
            </div>

        </div>
    </div>

    <div class="text-center mt-4 footer-support">
        <p>Contact us at <a href="mailto:support@pin81.com" style="color: #0a2540;">support@pin81.com</a></p>
    </div>
</div>

<style>
    .success-icon-circle {
        width: 50px;
        height: 50px;
        background-color: #d9f8ea;
        color: #8ce4c6;
        border-radius: 50%;
        font-size: 1.5rem;
        margin: 0 auto 1.5rem;
    }

    .card-custom {
        background: #ffffff;
        border: none;
        max-width: 650px;
        margin: 0 auto;
        overflow: hidden;
        border-radius: 10px;
    }

    .card-custom-header {
        background: #0a2540;
        padding: 1.25rem 1.75rem;
    }

    .confirmation-label {
        font-size: 0.85rem;
        opacity: 0.9;
    }

    .confirmation-code {
        font-weight: 700;
        letter-spacing: 0.5px;
        background-color: rgba(252, 252, 255, 0.15);
        padding: 2px 6px;
        border-radius: 4px;
    }

    .detail-label {
        font-size: 0.85rem;
        margin-bottom: 2px;
    }
    
    .detail-icon{
        color: #fdd6eb
    }

    .email-alert {
        color: #2c5892;
        border-radius: 8px;
        padding: 0.75rem 1.25rem;
        font-size: 0.95rem;
    }
    .whats-next-box {
        background-color: #f8fafc;
        border-radius: 10px;
        padding: 1.5rem;
    }
    .whats-next-box ul {
        padding-left: 1.25rem;
        margin-bottom: 0;
    }
    .whats-next-box li {
        margin-bottom: 0.5rem;
        color: #334155;
        font-size: 0.95rem;
    }
    .whats-next-box li:last-child {
        margin-bottom: 0;
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

    .custom-btn-b {
        background-color: transparent;
        color: #0a2540; /* text color */
        cursor: pointer;
        transition: 0.3s;
    }
    /* mouse hover effect */
    .custom-btn-b:hover {
        background-color: #0a2540;
        color: white;
        border-color: #0a2540;
    }

    .footer-support {
        font-size: 0.9rem;
        color: #64748b;
    }
    .footer-support a {
        color: #0b2545;
        text-decoration: none;
        font-weight: 500;
    }
    .footer-support a:hover {
        text-decoration: underline;
    }
</style>

@endsection