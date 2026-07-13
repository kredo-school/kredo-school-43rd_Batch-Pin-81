<div class="card restaurant-card border-0 shadow-sm h-100 rounded-4 overflow-hidden position-relative"
    style="background: #fff; font-family: inter;">
    <a href="{{ route('restaurant.show', $restaurant) }}" class="stretched-link"></a>
    <form action="{{ route('favorites.store', $restaurant->id) }}" method="POST" class="favorite-form position-absolute top-0 end-0 m-2">
        @csrf
        <button
            type="submit"
            class="favorite-btn"
            aria-label="{{ $restaurant->is_favorited ? 'Remove from favorites' : 'Add to favorites' }}">
            <i class="fa-{{ $restaurant->is_favorited ? 'solid' : 'regular' }} fa-heart {{ $restaurant->is_favorited ? 'text-warning' : 'text-dark' }}"></i>
        </button>
    </form>

    <div>
        <div class="row g-0">

            <!-- Image -->
            <div class="col-4 col-md-12">
                @if ($restaurant->photos->isNotEmpty())
                    <img src="{{ asset('storage/' . $restaurant->photos->first()->photo_path) }}"
                        alt="{{ $restaurant->restaurant_name }}" class="restaurant-photos">
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

            <!-- Content -->
            <div class="col-8 col-md-12">
                <div class="card-body">

                    {{-- Name & Rating --}}
                    <div class="d-flex justify-content-between align-items-center mb-0 restaurant-name">
                        <h4 class="fw-bold mb-0">{{ $restaurant->restaurant_name }}</h4>

                        @php
                            $averageRating = $restaurant->posts_avg_rating ?? 0;
                            $percentage = ($averageRating / 5) * 100;
                        @endphp
                        {{-- Desktop --}}
                        <div class="star-rating d-none d-md-flex align-items-center">
                            <div class="star-rating-top" style="width: {{ $percentage }}%">
                                ★★★★★
                            </div>
                            <div class="star-rating-bottom">
                                ★★★★★
                            </div>
                            <span class="fs-6">{{ number_format($averageRating, 1) }}</span>
                        </div>


                        {{-- Mobile --}}
                        <div class="d-md-none">
                            <i class="fa-solid fa-star text-warning"></i><span class="small text-nowrap">
                                {{ number_format($restaurant->posts_avg_rating ?? 0, 1) }}</span>
                        </div>
                    </div>

                    {{-- Category --}}
                    <div class="d-flex justify-content-start category">
                        @foreach ($restaurant->categories as $category)
                            <span>{{ $category->category_name }}</span>
                        @endforeach
                    </div>

                    {{-- Location --}}
                    <div class="d-flex justify-content-start location">
                        <p class="mb-1">
                            <i class="fa-solid fa-location-dot location-icon"></i>
                            {{ $restaurant->city }}
                        </p>
                    </div>

                    {{-- Avalable time --}}
                    <div class="mb-2">
                        @php
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

                        <p class="mb-1 avalable-time">
                            <i class="fa-regular fa-clock time-icon"></i>
                            Available Now
                        </p>

                        {{-- Mobile --}}
                        <div class="d-md-none">
                            <div class="time-slider">
                                @foreach ($availableTimes as $time)

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

                        {{-- Desktop --}}
                        <div class="d-none d-md-block">

                            @auth
                                @foreach ($availableTimes as $time)
                                    <a href="{{ route('booking.create', $restaurant->id) }}?time={{ urlencode($time) }}"
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

                    {{-- Features --}}
                    <div class="d-flex flex-wrap gap-1 ">
                        @if($restaurant->features && $restaurant->features->isNotEmpty())
                            @foreach($restaurant->features as $feature)
                                <span class="badge rounded-pill fw-normal px-2 py-1 text-muted"
                                    style="background-color: #e8ebf1; font-size: 10px;">
                                    {{ $feature->feature_name }}
                                </span>
                            @endforeach
                        @endif
                        
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- include  the modal here --}}
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
    /* Desktop */
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
        background: transparent;
        border: none;
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

    .star-rating-top {
        color: #ffc107;
        position: absolute;
        overflow: hidden;
        white-space: nowrap;
    }

    .star-rating-bottom {
        color: #ddd;
    }

    .restaurant-name,
    .category,
    .location,
    .avalable-time {
        color: #0a2540;
    }

    /* Mobile */
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

        /* Hide scrollbar */
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    /* Design for both*/
    .location-icon {
        color: #fdd6eb !important;
    }

    .time-icon {
        color: #fdd6eb;
    }

    .time-btn {
        color: #0a2540 !important;
        background-color: transparent;
        border: 1px solid #FCE7F3;
        font-size: 0.75rem;
        padding: 2px 9px;
        border-radius: 10px;

        text-decoration: none;
        /* Remove underline */
        display: inline-block;
    }

    .time-btn:hover {
        background-color: #FCE7F3 !important;
        color: #0a2540 !important;
        text-decoration: none;
    }
</style>
