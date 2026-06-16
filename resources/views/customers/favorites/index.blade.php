@extends('layouts.app')

@section('title', 'Favorites')

@section('content')
<div class="container py-4" style="font-family: inter; color: #0a2540; background: #fff;" >

    <h4 class="mb-4 fw-bold">
        {{ $favorites->count() }} favorite restaurants
    </h4>

    <div class="row g-4">

        @foreach($favorites as $restaurant)
        <div class="col-12 col-md-6 col-lg-4">

            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden position-relative">

                <a href="{{ route('restaurant.show' /* $restaurant->id */) }}"
                class="stretched-link"></a>

                <div class="row g-0 h-100">

                    {{-- Image --}}
                    <div class="col-4 col-md-12 position-relative restaurant-card">

                        <img
                            src="{{ asset($restaurant->image) }}"
                            alt="{{ $restaurant->name }}">

                        {{-- Favorite Button --}}
                        <form action="{{ route('favorites.destroy', $restaurant->id) }}"
                            method="POST"
                            class="position-absolute top-0 end-0 m-2 d-none d-md-block">
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="favorite-btn">
                                <i class="fa-solid fa-heart text-danger"></i>
                            </button>
                        </form>

                    </div>

                    {{-- Content --}}
                    <div class="col-8 col-md-12">

                        <div class="card-body">

                            {{-- Name & Rating --}}
                            <div class="d-flex justify-content-between align-items-start mb-0">
                                {{-- Left side --}}
                                <div>
                                    <h4 class="fw-semibold mb-1">
                                        {{ $restaurant->name }}
                                    </h4>

                                    <p class="mb-1">
                                        {{ $restaurant->category }}
                                    </p>
                                </div>

                                {{-- Right side --}}
                                <div class="text-end d-md-none">

                                    <form
                                        action="{{ route('favorites.destroy', $restaurant->id) }}"
                                        method="POST"
                                        class="mb-1">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="mobile-favorite-btn">
                                            <i class="fa-solid fa-heart text-danger"></i>
                                        </button>
                                    </form>

                                    <small class="d-block">
                                        <i class="fa-solid fa-star text-warning"></i>
                                        {{ $restaurant->rating }}
                                        ({{ $restaurant->review_count }})
                                    </small>

                                </div>

                                {{-- Desktop Rating --}}
                                <span class="small text-nowrap d-none d-md-inline">
                                    <i class="fa-solid fa-star text-warning"></i>
                                    {{ $restaurant->rating }}
                                    ({{ $restaurant->review_count }})
                                </span>
                            </div>

                            {{-- Category --}}
                            <p class="mb-1">
                                {{ $restaurant->category }}
                            </p>

                            {{-- Location --}}
                            <p class="mb-1">
                                <i class="fa-solid fa-location-dot location-icon"></i>
                                {{ $restaurant->location }}
                            </p>

                            {{-- Available Time --}}
                            <div class="mb-2">

                                <p class="mb-1">
                                    <i class="fa-regular fa-clock time-icon"></i>
                                    Available Now
                                </p>

                                @php
                                    $mobileTimes = array_slice($restaurant->available_times, 0, 3);
                                    $remainingCount = count($restaurant->available_times) - 3;
                                @endphp

                                {{-- Mobile --}}
                                <div class="d-md-none">
                                    <div class="time-slider">
                                        @foreach($restaurant->available_times as $time)
                                            <button
                                                type="button"
                                                class="time-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#bookingOptionsModal">
                                                {{ $time }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Desktop --}}
                                <div class="d-none d-md-block">
                                    @foreach($restaurant->available_times as $time)

                                        @auth
                                            <a href="{{ route('booking.create', [
                                                'restaurant' => $restaurant->id,
                                                'time' => $time
                                            ]) }}"
                                            class="time-btn">
                                                {{ $time }}
                                            </a>
                                        @endauth

                                        @guest
                                            <button
                                                type="button"
                                                class="time-btn"
                                                data-time="{{ $time }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#bookingOptionsModal">
                                                {{ $time }}
                                            </button>
                                        @endguest

                                    @endforeach
                                </div>

                            </div>

                            {{-- Features --}}
                            <div class="d-flex flex-wrap gap-1">
                                @foreach ($restaurant->features as $feature)
                                    <span
                                        class="badge rounded-pill fw-normal px-2 py-1 text-muted"
                                        style="background-color:#e8ebf1;font-size:10px;">
                                        {{ $feature }}
                                    </span>
                                @endforeach
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
        @endforeach

    </div>

</div>

{{-- include  the modal here --}}
@include('customers.restaurants.partials.modals.booking_options')

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.more-times-btn').forEach(btn => {

        const target = document.querySelector(
            btn.getAttribute('data-bs-target')
        );

        target.addEventListener('shown.bs.collapse', function () {
            btn.textContent = '-';
        });

        target.addEventListener('hidden.bs.collapse', function () {
            btn.textContent = btn.dataset.more;
        });

    });

});
</script>

<style>
.favorite-btn,
.time-btn{
    position: relative;
    z-index: 2;
}

.favorite-btn{
    background: transparent;
    border: none;
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.favorite-btn i{
    font-size: 2rem;
}

.restaurant-card img{
    width:100%;
    height:220px;
    object-fit:cover;
}

.feature-badge{
    background:#f6e3f1;
    color:#0b3155;
    font-weight:500;
    padding:.45rem .8rem;
}

.card{
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    cursor: pointer;
}

.card:hover{
    transform: translateY(-4px);
    box-shadow: 0 8px 15px rgba(3, 3, 3, 0.1) !important;
}

@media (max-width: 767.98px) {
    .restaurant-card img{
        height:100%;
        min-height:180px;
    }

    .card-body{
        padding:0.75rem;
    }

    .card-body h4{
        font-size:1rem;
    }

    .card-body p,
    .card-body span{
        font-size:0.85rem;
    }

    .mobile-favorite-btn{
        background: transparent;
        border: none;
        padding: 0;
        line-height: 1;
    }

    .mobile-favorite-btn i{
        font-size: 1.25rem;
    }
} 

  .time-slider {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    white-space: nowrap;
    padding-bottom: 4px;

    /* Hide scrollbar */
    scrollbar-width: none;
    -ms-overflow-style: none;
  }

  /* Design for both*/
  .location-icon{
    color: #fdd6eb !important;
  }

  .time-icon{
    color: #fdd6eb;
  }

  .time-btn{
    color: #0a2540;
    background-color: transparent;
    border: 1px solid #FCE7F3;
    font-size: 0.75rem;
    padding: 2px 9px;
    border-radius: 10px;
  }

  .time-btn:hover{
    background-color: #FCE7F3 !important;
  }
</style>
@endsection