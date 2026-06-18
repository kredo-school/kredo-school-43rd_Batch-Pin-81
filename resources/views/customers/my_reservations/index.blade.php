@extends('layouts.app')

@section('title', 'My Reservations')

@section('content')

<div class="container py-4" style="font-family: inter; color:#0a2540;">
    
    <div class="mb-5">

         @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>
            </div>
        @endif

        <h2 class="section-title mb-3">Upcoming</h2>

        @forelse($upcomingReservations ?? [] as $booking)
            <div class="reservation-card p-3 mb-3 shadow-sm">
                <div class="row g-3 align-items-center">
                    
                    <div class="col-12 col-md-auto text-center">
                        <a href="{{ route('restaurant.show') }}">
                            <img src="{{ $booking->restaurant_image ?? 'https://via.placeholder.com/80' }}" alt="Restaurant image" class="restaurant-img">
                        </a>
                    </div>
                    
                    <div class="col mt-0">
                        <div class="reservation-card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center">
                                    <a href="{{ route('restaurant.show') }}" class="text-decoration-none">
                                        <h3 class="fw-bold mb-0" style="color: #002855">{{ $booking->restaurant_name }}</h3>
                                    </a>
                                    <div class="meta-code mt-1 mt-md-0 px-2 text-secondary reservation-code">Code: {{ $booking->reservation_code }}</div>
                                </div>
                                <span class="badge badge-confirmed fw-bold text-white mb-auto">confirmed</span>
                            </div>

                            <div class="row mt-2">
                                <div class="meta-text mb-1 text-secondary text-decoration-none fw-6">
                                    <i class="bi bi-geo-alt-fill me-1 location-icon"></i>{{ $booking->location }}
                                </div>
                            </div>
                            
                            <div class="reservation-footer d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mt-1">
                                <div class="reservation-details d-flex gap-4 gap-md-4">
                                    <div class="detail-text">
                                        <i class="fa-solid fa-calendar date-icon"></i>{{ $booking->date }}
                                    </div>
                                    <div class="detail-text">
                                        <i class="fa-regular fa-clock time-icon"></i>{{ $booking->time }}
                                    </div>
                                    <div class="detail-text">
                                        <i class="bi bi-people me-2 guest-icon"></i>{{ $booking->guests }}
                                    </div>
                                </div>

                                <div class="reservation-actions d-flex gap-2 flex-wrap">
                                        <button
                                            type="button"
                                            class="btn btn-action-outline"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal">
                                            Edit
                                        </button>
                                    
                                        <button
                                            type="button"
                                            class="btn btn-action-outline"
                                            data-bs-toggle="modal"
                                            data-bs-target="#NotifyLateModal">
                                            Notify I"ll be Late
                                        </button>
                                    
                                        <button type="submit" 
                                                class="btn btn-danger btn-action-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#CancelModal">
                                                Cancel
                                        </button>
                                    </form>  
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        @empty
            <div class="reservation-card p-3 mb-3">
                <div class="row align-items-center g-3">
                    <div class="col-auto">
                        <div class="bg-secondary rounded text-white d-flex align-items-center justify-content-center" style="width:80px; height:80px;"><i class="bi bi-egg-fried fs-3"></i></div>
                    </div>
                    <div class="col text-muted py-3">
                        No upcoming reservations found. <a href="{{ route('home') }}" class="text-primary text-decoration-none">Book a table now.</a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <div>
        <h2 class="section-title mb-4">Past Visits</h2>

        @forelse($pastReservations ?? [] as $past)
            <div class="reservation-card p-3 mb-3 shadow-sm">
                <div class="row align-items-center g-3">
                    
                    <div class="col-12 col-md-auto text-center">
                        <a href="{{ route('restaurant.show') }}">
                          <img src="{{ $past->restaurant_image ?? 'https://via.placeholder.com/80' }}" alt="Restaurant image" class="restaurant-img filter-grayscale">
                        </a>
                    </div>
                    
                    <div class="col mt-0">
                        <div class="reservation-card-body">
                            <div class="d-flex align-items-center">
                                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center">
                                    <a href="{{ route('restaurant.show')}}" class="text-decoration-none me-3" style="color: #0f2d59;">
                                        <h3 class="fw-bold">{{ $past->restaurant_name }}</h3>
                                    </a>
                                    <div class="meta-code mb-2 px-2 py-0 text-secondary d-inline">Code: {{ $booking->reservation_code }}</div>
                                </div>
                            </div>
                            
                            <div class="meta-text mb-2 text-secondary text-decoration-none fs-6">
                                    <i class="bi bi-geo-alt-fill me-1 location-icon"></i>{{ $past->location }}
                            </div>
                            
                            <div class="reservation-footer d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mt-3">
                                <div class="reservation-details d-flex gap-4 gap-md-4">
                                    <div class="detail-text">
                                        <i class="fa-solid fa-calendar date-icon"></i>{{ $past->date }}
                                    </div>
                                    <div class="detail-text">
                                        <i class="fa-regular fa-clock time-icon"></i>{{ $past->time }}
                                    </div>
                                    <div class="detail-text">
                                        <i class="bi bi-people me-2 guest-icon"></i>{{ $past->guests }}
                                    </div>
                                </div>

                                <div class="reservation-actions">
                                    <a href="{{ route('restaurant.book', ['restaurant' => $past->restaurant_id]) }}" class="btn btn-action-outline">Book Again</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-muted ps-2">No history of past visits.</div>
        @endforelse
    </div>

</div>

{{-- include  the modal here --}}
@include('customers.my_reservations.modals.cancel')
@include('customers.my_reservations.modals.notify_late')
@include('customers.my_reservations.modals.edit')

<div class="chat-widget-btn">
    <i class="bi bi-chat-left-text-fill fs-4"></i>
</div>

<style>
    .reservation-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
    }

    .reservation-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 7px 12px rgba(0, 4, 17, 0.05) !important;
    }

    .reservation-card-body{
        margin-top: 15px;
    }

    .reservation-code{
        margin-left: 20px;
    }

    .restaurant-img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 8px;
    }

    .badge-confirmed {
        background-color: #0a2540;
        font-size: 0.9rem;
        padding: 5px 10px;
        border-radius: 6px;
        letter-spacing: 0.3px;
    }

    .meta-text {
        font-size: 0.85rem;
    }

    .meta-code {
        font-size: 0.85rem;
        border-radius: 7px;
        background-color: #f2f4fc
    }

    .reservation-details {
        gap: 2rem;
    }

    .detail-text {
        font-size: 0.95rem;
        color: #0a2540;
    }
    /* Button Customizations */
    .btn-action-outline {
        background-color: #f8fafc;
        border: 1px solid #cbd5e1;
        color: #334155;
        font-weight: 500;
        font-size: 0.875rem;
        padding: 0.4rem 1rem;
        border-radius: 6px;
    }
    .btn-action-outline:hover {
        background-color: #f1f5f9;
        border-color: #94a3b8;
        color: #0f172a;
    }
    
    /* Floating chat icon simulation */
    .chat-widget-btn {
        position: fixed;
        bottom: 24px;
        right: 24px;
        width: 56px;
        height: 56px;
        background-color: #002855;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        cursor: pointer;
        z-index: 1000;
    }

    .location-icon, .date-icon, .time-icon, .guest-icon{
      color: #ffd0e9 !important;
      text-shadow: 0 0 0.9px currentColor !important;
    }

    @media (max-width: 768px) {
        .restaurant-img {
            width: 100%;
            height: 220px;
            max-width: none;
            object-fit: cover;
            border-radius: 0;
            display: block;
        }

        .reservation-card {
            padding: 0 !important;
            overflow: hidden;
        }

        .reservation-card-body {
            padding: 1rem;
            margin-top: 0;
        }

       .reservation-code{
            margin-left: 0;
       }

        .reservation-footer {
            flex-direction: column;
            align-items: stretch !important;
        }

        .reservation-details {
            display: flex;
            justify-content: space-between;
            width: 100%;
            gap: 0 !important;
        }

        .reservation-details .detail-text {
            white-space: nowrap;
        }

        .reservation-actions {
            width: 100%;
            margin-top: 0;
            flex-direction: column;
        }

        .reservation-actions .btn,
        .reservation-actions form {
            width: 100%;
        }

        .reservation-actions .btn {
            width: 100%;
        }
    }
</style>

@endsection