 <a href="#"
    class="text-decoration-none">

    <div class="card restaurant-card h-100" style="background: #fffefc;">
      <div class="row g-0">

        <!-- Image -->
        <div class="col-4 col-md-12">
            <img
                src="{{ /*$restaurant->image*/ $restaurant['image'] }}"
                class="restaurant-image"
                alt="{{ /*$restaurant->name*/ $restaurant['name'] }}"
            >
        </div>

        <!-- Content -->
        <div class="col-8 col-md-12">
            <div class="card-body " style="color: #0a2540;">

                {{-- Rating --}}
                <div class="d-flex justify-content-between align-items-start mb-0">
                    <h4 class="fw-bold">{{ /*$restaurant->name*/ $restaurant['name'] }}</h4>
                    <span class="small text-nowrap">⭐ {{ /*$restaurant->rating*/ $restaurant['rating']}} ({{ $restaurant['review_count'] }})</span>
                </div>

                {{-- Category --}}
                <div class="d-flex justify-content-start">
                  <p class="mb-1 d-inline pe-4" style="color: #0a2540;">{{ /*$restaurant->category*/ $restaurant['category'] }}</p>
                </div>

                {{-- Area --}}
                <div class="d-flex justify-content-start">
                  <p class="mb-1" style="color: #0a2540;">
                    <i class="fa-solid fa-location-dot location-icon"></i>
                    {{ /*$restaurant->area*/ $restaurant['area'] }}
                  </p>
                </div>
                
                {{-- Avalable time --}}
                <div class="mb-2">

                  <p class="mb-1" style="color: #0a2540;">
                    <i class="fa-regular fa-clock time-icon"></i>
                    Avalable Now
                  </p>

                  @php
                      $mobileTimes = array_slice($restaurant['available_times'], 0, 3);
                      $remainingCount = count($restaurant['available_times']) - 3;
                  @endphp

                  {{-- Mobile --}}
                  <div class="d-md-none">

                      <div class="time-slider">
                          @foreach($restaurant['available_times'] as $time)
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
                  
                      @foreach(/*$restaurant->available_times*/ $restaurant['available_times'] as $time)
                        @auth
                            <a href="{{ route('booking.create', [
                              'restaurant' => $restaurant['id'],
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
                <div class="d-flex flex-wrap gap-1 ">
                  @foreach ($restaurant['features'] as $feature)
                    <span class="badge rounded-pill fw-normal px-2 py-1 text-muted"
                          style="background-color: #e8ebf1; font-size: 10px;">{{ $feature }}</span>
                          {{-- Navy --}}
                          {{-- <span class="badge rounded-pill fw-normal px-2 py-1"
                          style="background-color: #eff6fd; color:#0a2540; font-size: 10px;">{{ $feature }}</span> --}}
                  @endforeach
                </div>
            </div>
        </div>

      </div>
    </div> 
  </a>

{{-- include  the modal here --}}
@include('customers.restaurants.partials.modals.booking-options')

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
  /* Desktop */
  .restaurant-card {
    border: 1px solid rgb(238, 238, 238);
    border-radius: 20px;
    overflow: hidden;
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    cursor: pointer;
  }

  .restaurant-card:hover{
    transform: translateY(-4px);
    box-shadow: 0 8px 15px rgba(3, 3, 3, 0.1) !important;
  }

  .restaurant-card img {
    width: 100%;
    height: 220px;
    object-fit: cover;
  }

  /* Mobile */
  @media (max-width: 767.98px) {
    .restaurant-card img {
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


