<a href="{{ route('restaurant.show', $restaurant) }}"
    class="text-decoration-none">

    <div class="card restaurant-card border-0 shadow-sm h-100 rounded-4 overflow-hidden" style="background: #fff; font-family: inter;">
        <div class="row g-0">

            <!-- Image -->
            <div class="col-4 col-md-12">
                @if ($restaurant->photos->isNotEmpty())
                    <img
                        src="{{ asset('storage/' . $restaurant->photos->first()->photo_path) }}"
                        alt="{{ $restaurant->restaurant_name }}"
                        class="restaurant-photos"
                    >
                @else
                    <div class="restaurant-photos">
                        <i class="fa-regular fa-image no-photo"></i>
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
                            <i class="fa-solid fa-star text-warning"></i><span class="small text-nowrap"> {{ number_format($restaurant->posts_avg_rating ?? 0, 1) }}</span>
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

                        <p class="mb-1 avalable-time">
                            <i class="fa-regular fa-clock time-icon"></i>
                            Available time
                        </p>

                        {{-- This is for test --}}
                        @php
                        $now = now();
                        $endTime = $now->copy()->addHour();

                        $minutes = ceil($now->minute / 15) * 15;

                        $slot = $now->copy()->minute(0)->second(0)->addMinutes($minutes);

                        $availableSlots = [];

                        while ($slot <= $endTime) {
                            $availableSlots[] = $slot->format('H:i');
                            $slot->addMinutes(15);
                        }
                        @endphp

                        {{-- Mobile --}}
                        <div class="d-md-none">

                            @auth
                                @foreach($availableSlots as $time)
                                    <a href="{{ route('booking.create', $restaurant->id) }}?time={{ urlencode($time) }}"
                                        class="time-btn"
                                        data-time="{{ $time }}"
                                        data-restaurant-id="{{ $restaurant->id }}">
                                        {{ $time }}
                                    </a>
                                @endforeach
                            @endauth

                            @guest
                                @foreach($availableSlots as $time)
                                    <button
                                        type="button"
                                        class="time-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#bookingOptionsModal"
                                        data-time="{{ $time }}"
                                        data-restaurant-id="{{ $restaurant->id }}">
                                        {{ $time }}
                                    </button>
                                @endforeach
                            @endguest

                        </div>

                        {{-- Desktop --}}
                        <div class="d-none d-md-block">

                            @auth
                                @foreach($availableSlots as $time)
                                    <a href="{{ route('booking.create', $restaurant->id) }}?time={{ urlencode($time) }}"
                                        class="time-btn"
                                        data-time="{{ $time }}"
                                        data-restaurant-id="{{ $restaurant->id }}">
                                        {{ $time }}
                                    </a>
                                @endforeach
                            @endauth

                            @guest
                                @foreach($availableSlots as $time)
                                    <button
                                        type="button"
                                        class="time-btn"
                                        data-restaurant-id="{{ $restaurant->id }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#bookingOptionsModal"
                                        data-time="{{ $time }}">
                                        {{ $time }}
                                    </button>
                                @endforeach
                            @endguest

                        </div>

                    </div>

                    {{-- Features --}}
                    <div class="d-flex flex-wrap gap-1 ">
                        @if($restaurant->features)
                            @foreach($restaurant->features as $feature)
                                <span class="badge rounded-pill fw-normal px-2 py-1 text-muted"
                                    style="background-color:#e8ebf1;font-size:10px;">
                                    {{ $feature->feature_name }}
                                </span>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div> 
</a>

{{-- include  the modal here --}}
@include('customers.restaurants.partials.modals.booking_options')

<script>
document.querySelectorAll('.time-btn').forEach(button => {
    button.addEventListener('click', function () {
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

  .restaurant-card:hover{
    transform: translateY(-4px);
    box-shadow: 0 8px 15px rgba(3, 3, 3, 0.1) !important;
  }

  .restaurant-photos {
    width: 100%;
    height: 220px;
    object-fit: cover;
  }

  .no-photo{
    
    font-size: 100px;
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
  .avalable-time{
    color: #0a2540;
  }

  /* Mobile */
  @media (max-width: 767.98px) {
    .restaurant-photos  {
      height: 100%;
      min-height: 140px;
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
  .location-icon{
    color: #fdd6eb !important;
  }

  .time-icon{
    color: #fdd6eb;
  }

  .time-btn{
    color: #0a2540 !important;
    background-color: transparent;
    border: 1px solid #FCE7F3;
    font-size: 0.75rem;
    padding: 2px 9px;
    border-radius: 10px;

    text-decoration: none;   /* Remove underline */
    display: inline-block;
  }

  .time-btn:hover{
    background-color: #FCE7F3 !important;
    color: #0a2540 !important;
    text-decoration: none;
  }

</style>


