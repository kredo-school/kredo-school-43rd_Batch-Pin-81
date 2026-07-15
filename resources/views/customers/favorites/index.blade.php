@extends('layouts.app')

@section('title', 'Favorites')

@section('content')
<div class="container bg-light py-4" style="font-family: inter; color: #0a2540;">

    <div class="mb-3 filter-bar d-none d-md-block bg-white">

        <div class="d-flex justify-content-between align-items-center mb-2">

            <a href="{{ url()->previous() }}"><i class="fa-solid fa-chevron-left text-dark fs-1"></i></a>
            
            <h5 class="h4 fw-bold" style="color: #0a2540;">
                {{ $favorites->count() }} Favorite Restaurants Found
            </h5>

            <button class="btn filter-btn ms-3 flex-shrink-0" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="fa-solid fa-sliders"></i> Filters
            </button>
        </div>

        <div id="activeFilters" class="d-flex gap-2 flex-wrap justify-content-end">

            @foreach (request('cuisines', []) as $cuisine)
                <span class="badge bg-light border text-secondary fs-6 px-2 pt-2" style="height: 2rem">
                    {{ $cuisine }}
                </span>
            @endforeach

            @foreach (request('features', []) as $feature)
                <span class="badge bg-light border text-secondary fs-6 px-2 pt-2" style="height: 2rem">
                    {{ $feature }}
                </span>
            @endforeach

            @if (request('rating'))
                <span class="badge bg-light border text-secondary fs-6 px-2 pt-2" style="height: 2rem">
                    At least {{ request('rating') }} stars
                </span>
            @endif

            @if (request('distance'))
                <span class="badge bg-light border text-secondary fs-6 px-2 pt-2" style="height: 2rem">
                    {{ request('distance') }}
                </span>
            @endif

        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3 d-md-none">
        <div>
            <h5 class="mb-0">{{ $favorites->count() }} Favorite Restaurants Found</h5>
        </div>

        <button class="btn filter-btn rounded-pill" data-bs-toggle="modal" data-bs-target="#filterModal">
            <i class="bi bi-sliders"></i>
        </button>
    </div>

    <div class="row g-4">
        @foreach ($favorites as $restaurant)
            @php
                $restaurantName = $restaurant->restaurant_name ?? $restaurant->name;
                $averageRating = $restaurant->posts_avg_rating ?? $restaurant->rating ?? 0;
                $filledStars = (int) floor($averageRating);
                $hasHalfStar = ($averageRating - $filledStars) >= 0.5;
                $favoritePhoto = $restaurant->photos->first();
                $availableTimes = $restaurant->available_times ?? [];

                if (empty($availableTimes)) {
                    $now = \Carbon\Carbon::now();
                    $endTime = $now->copy()->addHour();

                    $minutes = ceil($now->minute / 15) * 15;
                    $slot = $now->copy()->minute(0)->second(0)->addMinutes($minutes);

                    $availableTimes = [];

                    while ($slot <= $endTime) {
                        $availableTimes[] = $slot->format('H:i');
                        $slot->addMinutes(15);
                    }
                }
            @endphp

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card restaurant-card border-0 shadow-sm h-100 rounded-4 overflow-hidden position-relative" style="background: #fff; font-family: inter;">
                    <a href="{{ route('restaurant.show', $restaurant) }}" class="stretched-link"></a>

                    <div class="favorite-form position-absolute top-0 end-0 m-2">
                        <button
                            type="button"
                            class="favorite-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#unfavoriteModal-{{ $restaurant->id }}"
                            aria-label="Remove {{ $restaurantName }} from favorites">
                            <i class="fa-solid fa-heart text-warning"></i>
                        </button>
                    </div>

                    <div>
                        <div class="row g-0">
                            <div class="col-4 col-md-12">
                                @if ($favoritePhoto)
                                    <img src="{{ asset('storage/' . $favoritePhoto->photo_path) }}"
                                        alt="{{ $restaurantName }}" class="restaurant-photos">
                                @else
                                    <div class="d-flex justify-content-center align-items-center restaurant-placeholder"
                                        style="background: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1200') center/cover;">
                                        <div class="text-center text-white px-3 py-2 rounded"
                                            style="background: rgba(0, 0, 0, 0.5);">
                                            <i class="bi bi-image fa-2x mb-2"></i>
                                            <p class="mb-0 small">No photos available (Showing default view)</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="col-8 col-md-12">
                                <div class="card-body">

                                    <div class="d-flex justify-content-between align-items-center mb-0 restaurant-name">
                                        <h4 class="fw-bold mb-0">{{ $restaurantName }}</h4>

                                        <div class="star-rating d-none d-md-flex align-items-center">
                                            <div class="star-rating-stars" aria-label="{{ number_format($averageRating, 1) }} out of 5 stars">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <span class="star-rating-star {{ $i <= $filledStars ? 'filled' : ($i === $filledStars + 1 && $hasHalfStar ? 'half' : 'empty') }}">
                                                        ★
                                                    </span>
                                                @endfor
                                            </div>
                                            <span class="fs-6">{{ number_format($averageRating, 1) }}</span>
                                        </div>

                                        <div class="d-md-none">
                                            <i class="fa-solid fa-star text-warning"></i><span class="small text-nowrap">
                                                {{ number_format($averageRating, 1) }}</span>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-start category">
                                        @if ($restaurant->categories && $restaurant->categories->isNotEmpty())
                                            @foreach ($restaurant->categories as $category)
                                                <span>{{ $category->category_name }}</span>
                                            @endforeach
                                        @elseif (!empty($restaurant->category) && $restaurant->category !== '-')
                                            <span>{{ $restaurant->category }}</span>
                                        @endif
                                    </div>

                                    <div class="d-flex justify-content-start location">
                                        <p class="mb-1">
                                            <i class="fa-solid fa-location-dot location-icon"></i>
                                            {{ $restaurant->city ?? '' }}
                                        </p>
                                    </div>

                                    <div class="mb-2">
                                        <p class="mb-1 avalable-time">
                                            <i class="fa-regular fa-clock time-icon"></i>
                                            Available Now
                                        </p>

                                        <div class="d-md-none">
                                            <div class="time-slider">
                                                @foreach ($availableTimes as $time)
                                                    @auth
                                                        <a href="{{ route('booking.create', [
                                                            'restaurant' => $restaurant->id,
                                                            'time' => $time,
                                                        ]) }}" class="time-btn" data-time="{{ $time }}" data-restaurant-id="{{ $restaurant->id }}">
                                                            {{ $time }}
                                                        </a>
                                                    @endauth

                                                    @guest
                                                        <button
                                                            type="button"
                                                            class="time-btn"
                                                            data-time="{{ $time }}"
                                                            data-restaurant-id="{{ $restaurant->id }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#bookingOptionsModal">
                                                            {{ $time }}
                                                        </button>
                                                    @endguest
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="d-none d-md-block">
                                            @auth
                                                @foreach ($availableTimes as $time)
                                                    <a href="{{ route('booking.create', [
                                                        'restaurant' => $restaurant->id,
                                                        'time' => $time,
                                                    ]) }}"
                                                        class="time-btn" data-time="{{ $time }}"
                                                        data-restaurant-id="{{ $restaurant->id }}">
                                                        {{ $time }}
                                                    </a>
                                                @endforeach
                                            @endauth

                                            @guest
                                                @foreach ($availableTimes as $time)
                                                    <button type="button" class="time-btn" data-restaurant-id="{{ $restaurant->id }}"
                                                        data-bs-toggle="modal" data-bs-target="#bookingOptionsModal"
                                                        data-time="{{ $time }}">
                                                        {{ $time }}
                                                    </button>
                                                @endforeach
                                            @endguest
                                        </div>
                                    </div>

                                    <div class="d-flex flex-wrap gap-1">
                                        @if ($restaurant->features && $restaurant->features->isNotEmpty())
                                            @foreach ($restaurant->features as $feature)
                                                <span class="badge rounded-pill fw-normal px-2 py-1 text-muted"
                                                    style="background-color: #e8ebf1; font-size: 10px;">
                                                    {{ $feature->feature_name }}
                                                </span>
                                            @endforeach
                                        @elseif (!empty($restaurant->feature_labels))
                                            @foreach ($restaurant->feature_labels as $feature)
                                                <span class="badge rounded-pill fw-normal px-2 py-1 text-muted"
                                                    style="background-color: #e8ebf1; font-size: 10px;">
                                                    {{ $feature }}
                                                </span>
                                            @endforeach
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        @foreach ($favorites as $restaurant)
            @php
                $restaurantName = $restaurant->restaurant_name ?? $restaurant->name;
            @endphp
            <div class="modal fade" id="unfavoriteModal-{{ $restaurant->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Remove from favorites?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-muted">
                            Are you sure you want to unfavorite <strong>{{ $restaurantName }}</strong>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                                No
                            </button>
                            <form action="{{ route('favorites.destroy', $restaurant->id) }}" method="POST" class="m-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">
                                    Yes
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@include('customers.restaurants.partials.modals.filter', ['filterAction' => route('favorites.index')])
@include('customers.restaurants.partials.modals.booking_options')

<script>
    document.querySelectorAll('.time-btn').forEach(button => {
        button.addEventListener('click', function() {
            const modal = document.getElementById('bookingOptionsModal');

            modal.dataset.restaurantId = this.dataset.restaurantId;
            modal.dataset.time = this.dataset.time;
        });
    });
</script>

<style>
    .filter-bar {
        position: sticky;
        top: 50px;
        z-index: 100;
        padding: 12px 0;
        --bs-bg-opacity: .9;
        border-radius: 10px;
    }

    .filter-btn {
        background-color: transparent;
        color: #0a2540;
        border: 2px solid #0a2540;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
    }

    .filter-btn:hover {
        background-color: #0a2540;
        color: #fff;
    }

    .restaurant-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        cursor: pointer;
    }

    .restaurant-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 15px rgba(3, 3, 3, 0.1) !important;
    }

    .favorite-form {
        z-index: 3;
    }

    .favorite-btn {
        border: none;
        background: transparent;
        background-color: transparent;
        box-shadow: none;
        width: auto;
        height: auto;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        position: relative;
        z-index: 4;
        padding: 0.1rem 0.25rem;
        appearance: none;
    }

    .favorite-btn i {
        font-size: 1.9rem;
        margin: 4px;
    }

    .restaurant-photos {
        width: 100%;
        height: 220px;
        object-fit: cover;
    }

    .restaurant-placeholder {
        width: 100%;
        height: 220px;
    }

    .star-rating {
        position: relative;
        display: inline-block;
        font-size: 1.5rem;
    }

    .star-rating-stars {
        display: inline-flex;
        align-items: center;
        gap: 1px;
        margin-right: 6px;
    }

    .star-rating-star {
        display: inline-block;
        line-height: 1;
    }

    .star-rating-star.filled {
        color: #ffc107;
    }

    .star-rating-star.empty {
        color: #ddd;
    }

    .star-rating-star.half {
        background: linear-gradient(90deg, #ffc107 50%, #ddd 50%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .restaurant-name,
    .category,
    .location,
    .avalable-time {
        color: #0a2540;
    }

    @media (max-width: 767.98px) {
        .restaurant-photos {
            height: 100%;
            min-height: 140px;
        }

        .restaurant-placeholder {
            height: 140px;
        }

        .restaurant-card h4 {
            font-size: 1rem;
        }
    }

    .time-slider {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        white-space: nowrap;
        padding-bottom: 4px;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .location-icon {
        color: #fdd6eb !important;
    }

    .time-icon {
        color: #fdd6eb;
    }

    .time-btn {
        position: relative;
        z-index: 5;
        color: #0a2540 !important;
        background-color: transparent;
        border: 1px solid #FCE7F3;
        font-size: 0.75rem;
        padding: 2px 9px;
        border-radius: 10px;
        text-decoration: none;
        display: inline-block;
        cursor: pointer;
        transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease;
    }

    .time-btn:hover {
        background-color: #FCE7F3 !important;
        color: #0a2540 !important;
        border-color: #FCE7F3 !important;
        text-decoration: none;
    }
</style>
@endsection