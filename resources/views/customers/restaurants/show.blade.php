@extends('layouts.app')

@section('title', 'Show Restaurant')
    
@section('content')

<div class="container bg-light px-lg-5 py-4">
    <div class="row g-4">

        {{-- LEFT SIDE: Restaurant Photos, Details & Tabs --}}
        <div class="col-lg-9 order-lg-1 order-1">
            
            {{-- Inline Restaurant Photos Container --}}
            <div id="restaurantCarousel" class="carousel slide position-relative w-100 bg-black rounded-4 overflow-hidden mb-4 shadow-sm" data-bs-touch="true" data-bs-interval="false">
                <div class="carousel-inner">
                    @foreach($photos as $index => $photo)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                            <div class="d-flex justify-content-center align-items-center image-container">
                                <img src="{{ $photo }}" class="restaurant-image"
                                alt="Restaurant Photo">
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Carousel Controls --}}
                <button class="carousel-control-prev custom-minimal-control" 
                        style="font-size: 3rem"
                        type="button"  
                        data-bs-target="#restaurantCarousel" 
                        data-bs-slide="prev">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button class="carousel-control-next custom-minimal-control"
                        style="font-size: 3rem" 
                        type="button" 
                        data-bs-target="#restaurantCarousel" 
                        data-bs-slide="next">
                    <i class="bi bi-chevron-right"></i>
                </button>

                {{-- Indicators --}}
                <div class="carousel-indicators custom-indicators">
                    @foreach($photos as $index => $photo)
                        <button type="button" data-bs-target="#restaurantCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"></button>
                    @endforeach
                </div>
            </div>

            {{-- Main Restaurant Header Specs --}}
            <div class="card bg-white border-0 shadow-sm rounded-4 p-4 mb-4">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <h1 class="fw-bold mb-0 text-navy">Sushi Masaru</h1>
                    <span class="fs-6 fw-bold text-warning ms-2">★ 4.8 <span class="text-muted fw-normal">(245)</span></span>
                </div>

                <p class="text-muted mb-3 fs-5">
                    Traditional Edomae sushi in a contemporary setting
                </p>

                {{-- Features --}}
                <div class="d-flex flex-wrap gap-1 ">
                  {{-- @foreach ($restaurant['features'] as $feature) --}}
                    <span class="badge rounded-pill fw-normal px-2 py-1 text-muted"
                          style="background-color: #e8ebf1; font-size: 12px;">{{ /* $feature */  "English Menu"}}</span>
                          {{-- Navy --}}
                          {{-- <span class="badge rounded-pill fw-normal px-2 py-1"
                          style="background-color: #eff6fd; color:#0a2540; font-size: 10px;">{{ $feature }}</span> --}}
                  {{-- @endforeach --}}
                </div>
            </div>

            {{-- MAIN TRACK PILL TABS SYSTEM --}}
            <div class="custom-tabs-container mb-4">
                <ul class="nav nav-pills custom-track-pills text-center" id="restaurantTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active text-navy" id="overview-tab" data-bs-toggle="pill" data-bs-target="#overview" type="button" role="tab">Overview</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link text-navy" id="menu-tab" data-bs-toggle="pill" data-bs-target="#menu" type="button" role="tab">Menu</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link text-navy" id="photos-tab" data-bs-toggle="pill" data-bs-target="#photos" type="button" role="tab">Photos</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link text-navy" id="reviews-tab" data-bs-toggle="pill" data-bs-target="#reviews" type="button" role="tab">Reviews</button>
                    </li>
                </ul>
            </div>

            {{-- TAB PANELS --}}
            <div class="tab-content" id="restaurantTabsContent">
                
                {{-- 1. OVERVIEW --}}
                <div class="tab-pane fade show active" id="overview" role="tabpanel">
                    <div class="card bg-white border-0 shadow-sm rounded-4 p-4">
                        <h4 class="fw-bold text-navy mb-3">About</h4>
                        <p class="text-muted mb-4 lead fs-6">
                            Experience the art of Edomae sushi at Sushi Masaru, where Chef Masaru combines decades of tradition with the freshest seasonal ingredients from Tsukiji Market.
                        </p>
                        
                        <div class="mt-4">
    <div class="row g-4">
        
        {{-- LEFT COLUMN: Location, Phone, Party Size --}}
        <div class="col-md-6 d-flex flex-column gap-4 overview">
            
            {{-- Location --}}
            <div class="d-flex align-items-start gap-3">
                <i class="bi bi-geo-alt fs-4 mt-1 icon"></i>
                <div>
                    <h6 class="fw-bold text-navy mb-1">Location</h6>
                    <a href="https://maps.google.com/?q=3-8-15+Ginza,+Chuo-ku,+Tokyo" target="_blank" class="text-muted text-decoration-underline">
                        3-8-15 Ginza, Chuo-ku, Tokyo
                    </a>
                </div>
            </div>

            {{-- Phone --}}
            <div class="d-flex align-items-start gap-3">
                <i class="bi bi-telephone fs-4 mt-1 icon"></i>
                <div>
                    <h6 class="fw-bold text-navy mb-1">Phone</h6>
                    <p class="text-muted mb-1">+81-3-1234-5678</p>
                    <small class="text-success d-flex align-items-center gap-1">
                        <i class="bi bi-check-lg"></i> English speaking staff available
                    </small>
                </div>
            </div>

            {{-- Party Size --}}
            <div class="d-flex align-items-start gap-3">
                <i class="bi bi-people fs-4 mt-1 icon"></i>
                <div>
                    <h6 class="fw-bold text-navy mb-1">Party Size</h6>
                    <p class="text-muted mb-0">Up to 8 guests</p>
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN: Hours, Website & SNS Links --}}
        <div class="col-md-6 d-flex flex-column gap-4 overview">
            
            {{-- Hours --}}
            <div class="d-flex align-items-start gap-3">
                <i class="bi bi-clock fs-4 mt-1 icon"></i>
                <div>
                    <h6 class="fw-bold text-navy mb-1">Hours</h6>
                    <p class="text-muted mb-0">17:00 - 22:00</p>
                </div>
            </div>

            {{-- Website & SNS Links --}}
            <div class="d-flex align-items-start gap-3">
                <i class="bi bi-globe fs-4 mt-1 icon"></i>
                <div>
                    <h6 class="fw-bold text-navy mb-1">Website & Socials</h6>
                    <p class="mb-2">
                        <a href="https://www.sushimasaru.jp" target="_blank" class="text-muted text-decoration-none hover-underline">
                            www.sushimasaru.jp
                        </a>
                    </p>
                    
                    {{-- SNS Quick Links Row --}}
                    <div class="d-flex align-items-center gap-3 mt-2">
                        <a href="#" class="text-muted fs-5 transition-colors" title="Instagram" style="opacity: 0.85;">
                            <i class="bi bi-instagram text-danger"></i>
                        </a>
                        <a href="#" class="text-muted fs-5 transition-colors" title="Facebook" style="opacity: 0.85;">
                            <i class="bi bi-facebook text-primary"></i>
                        </a>
                        <a href="#" class="text-muted fs-5 transition-colors" title="X (Twitter)" style="opacity: 0.85;">
                            <i class="bi bi-twitter-x text-dark"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
                    </div>
                </div>

                {{-- 2. MENU --}}
                <div class="tab-pane fade" id="menu" role="tabpanel">
                    <div class="custom-tabs-container mb-4 d-inline-block">
                        <ul class="nav nav-pills custom-track-pills text-center" id="menuSubTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active px-4" id="food-tab" data-bs-toggle="pill" data-bs-target="#menu-food" type="button" role="tab">Food</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link px-4" id="drink-tab" data-bs-toggle="pill" data-bs-target="#menu-drink" type="button" role="tab">Drink</button>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="tab-content" id="menuSubTabsContent">
                        <div class="tab-pane fade show active" id="menu-food" role="tabpanel">
                            <div class="card bg-white border-0 shadow-sm rounded-4 p-4 d-flex flex-column gap-4">
                                <div class="d-flex justify-content-between align-items-start border-bottom pb-3">
                                    <div>
                                        <h5 class="fw-bold text-navy mb-1">Omakase Course</h5>
                                        <p class="text-muted mb-0 small">Chef's selection of seasonal sushi</p>
                                    </div>
                                    <span class="fw-bold text-navy fs-5">¥15,000</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="fw-bold text-navy mb-1">Nigiri Sushi Set</h5>
                                        <p class="text-muted mb-0 small">12 pieces of chef's choice nigiri</p>
                                    </div>
                                    <span class="fw-bold text-navy fs-5">¥8,000</span>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="menu-drink" role="tabpanel">
                            <div class="card bg-white border-0 shadow-sm rounded-4 p-4">
                                <p class="text-muted mb-0">Premium Japanese Sake selections available.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3. PHOTOS --}}
                <div class="tab-pane fade" id="photos" role="tabpanel">
                    <div class="custom-tabs-container mb-4 d-inline-block">
                        <ul class="nav nav-pills custom-track-pills text-center" id="photoSubTabs" role="tablist">
                            <li class="nav-item"><button class="nav-link active px-3" data-bs-toggle="pill" data-bs-target="#photo-all" type="button" role="tab">All</button></li>
                            <li class="nav-item"><button class="nav-link px-3" data-bs-toggle="pill" data-bs-target="#photo-food" type="button" role="tab">Food</button></li>
                            <li class="nav-item"><button class="nav-link px-3" data-bs-toggle="pill" data-bs-target="#photo-interior" type="button" role="tab">Interior</button></li>
                        </ul>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="photo-all" role="tabpanel">
                            <div class="row g-3">
                                @foreach($photos as $photo)
                                    <div class="col-md-4 col-6">
                                        <div class="ratio ratio-1x1 rounded-4 overflow-hidden shadow-sm">
                                            <img src="{{ $photo }}" alt="Gallery Image" class="w-100 h-100 object-fit-cover">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 4. REVIEWS --}}
                <div class="tab-pane fade" id="reviews" role="tabpanel">
                    <div class="card bg-white border-0 shadow-sm rounded-4 p-4">

                        {{-- User Profile Header --}}
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <img src="https://i.pravatar.cc/150?img=32" alt="Avatar" class="rounded-circle" style="width: 48px; height: 48px;">
                            <div>
                                <h6 class="fw-bold text-navy mb-0">Sarah J. <span class="text-muted fw-normal fs-7">· 2 days ago</span></h6>
                                <div class="text-warning fs-7">★★★★★</div>
                            </div>
                        </div>

                        {{-- Review Food Image (Matches your design layout) --}}
                        <div class="row">
                            <div class="col">
                                <img src="https://images.unsplash.com/photo-1579871494447-9811cf80d66c?q=80&w=600&auto=format&fit=crop" 
                                    alt="Review Image" 
                                    class="review-uploaded-img rounded-4">
                            </div>

                            {{-- Review Text Comment --}}
                            <p class="col text-muted mb-0">Absolutely amazing! Best sushi I've ever had.</p>
                        </div>
                        
                    </div>
                </div>

            </div>
        </div>

        {{-- RIGHT SIDE: Calendar Reservation Card --}}
        <div class="col-lg-3 order-lg-2 order-2">
            <div class="card bg-white border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 24px; z-index: 100;">
                <h5 class="fw-bold text-navy mb-4">Make a Reservation</h5>
                
                <form action="#" method="POST" id="reservationForm">
                    @csrf
                    <input type="hidden" name="reservation_date" id="selectedReservationDate" value="{{ date('Y-m-d') }}">

                    {{-- Calendar Widget Component --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold text-navy small mb-2">Select Date</label>
                        <div class="calendar-widget border rounded-4 p-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <button type="button" class="btn btn-sm btn-link p-0 text-muted" id="prevMonthBtn">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                                <span class="fw-bold text-navy" id="calendarMonthYear"></span>
                                <button type="button" class="btn btn-sm btn-link p-0 text-muted" id="nextMonthBtn">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </div>
                            
                            {{-- Day Matrix Label Layout --}}
                            <div class="calendar-grid text-center mb-1">
                                <div class="calendar-day text-muted fw-semibold">Su</div>
                                <div class="calendar-day text-muted fw-semibold">Mo</div>
                                <div class="calendar-day text-muted fw-semibold">Tu</div>
                                <div class="calendar-day text-muted fw-semibold">We</div>
                                <div class="calendar-day text-muted fw-semibold">Th</div>
                                <div class="calendar-day text-muted fw-semibold">Fr</div>
                                <div class="calendar-day text-muted fw-semibold">Sa</div>
                            </div>

                            {{-- Target container loaded automatically by Javascript engine --}}
                            <div class="calendar-grid text-center" id="calendarDaysContainer"></div>
                        </div>
                    </div>

                    {{-- Time Picker --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold text-navy small">Time</label>
                        <select name="reservation_time" class="form-select border rounded-3 py-2 text-muted input-box" required>
                            <option value="" selected disabled>Select time</option>
                            <option value="17:00">17:00</option>
                            <option value="18:00">18:00</option>
                            <option value="19:00">19:00</option>
                            <option value="20:00">20:00</option>
                        </select>
                    </div>

                    {{-- Guests --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold text-navy small">Guests</label>
                        <select name="guests" class="form-select border rounded-3 py-2 text-muted input-box">
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'Person' : 'People' }}</option>
                            @endfor
                        </select>
                    </div>

                    <a href="{{ route('restaurant.book') }}" class="btn custom-btn-a w-100 py-2.5 rounded-3 fw-semibold text-decoration-none d-block text-center mb-3">
                        Book as a Guest
                    </a>

                    <a href="{{ route('login') }}" 
                        class="btn custom-btn-b w-100 py-2.5 rounded-3 fw-semibold text-decoration-none d-block text-center">
                        Book as a User (Login)
                    </a>
                </form>
            </div>
        </div>

    </div>
</div>

<div class="chat-floating-btn shadow">
    <i class="bi bi-chat-left-dots-fill text-white"></i>
</div>
@endsection

@include('customers.restaurants.partials.modals.booking_options')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
:root {
    --navy-base: #0a2540;
    --pill-bg: #e5e7e9;
    --pink-badge: #FCE7F3;
}

.text-navy { color: var(--navy-base); }
.fs-7 { font-size: 0.85rem; }

*{
    font-family: inter;
}

/* ==========================================================================
   1. PHOTO CAROUSEL CONTAINERS & RESPONSIVE WIDTHS
   ========================================================================== */
.image-container { 
    height: 460px; 
    width: 100%;
    background-color: #000; /* Keeps the black backdrop edges matching your theme */
}

/* Default Mobile: Image stretches 100% to fill screen and give a huge swipe target */
.restaurant-image { 
    width: 100%; 
    height: 100%; 
    object-fit: cover; 
}

/* Shrink image container height on mobile screens */
@media (max-width: 768px) { 
    .image-container { 
        height: 240px; 
    } 
}

/* Large Screens: Center and shrink the photo to 70% width */
@media (min-width: 769px) {
    .restaurant-image { 
        width: 100%; 
    }
}

/* ==========================================================================
   2. CHEVRON CONTROLS (Invisible on mobile to allow swiping, visible on desktop)
   ========================================================================== */
#restaurantCarousel {
    touch-action: pan-y; /* Forces mobile browsers to pass horizontal swipes to Bootstrap */
}

.custom-minimal-control {
    background: none !important;
    border: none !important;
    width: 50px;
    height: 100%;
    display: flex !important; /* Keeps container rendered on mobile so swipe listeners stay alive */
    align-items: center;
    justify-content: center;
}

/* Mobile-First: Make the chevron icons completely invisible, but touch zone stays live */
.custom-minimal-control i {
    font-size: 2.5rem; /* Large, clickable arrow dimensions */
    color: #ffffff !important;
    text-shadow: 0px 1px 4px rgba(0, 0, 0, 0.35);
    opacity: 0 !important; 
    transition: opacity 0.2s ease;
}

/* Desktop: Bring back the icons on larger screens */
@media (min-width: 769px) {
    .custom-minimal-control i {
        opacity: 0.7 !important;
    }
    
    .custom-minimal-control:hover i {
        opacity: 1 !important;
    }
}

/* ==========================================================================
   3. CAROUSEL INDICATORS (Clean round dots)
   ========================================================================== */
.custom-indicators [data-bs-target] {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.5);
    border: none;
    margin: 0 4px;
}

.custom-indicators .active { 
    background-color: #fff; 
}

/* Continuous Container Rail Tab Settings */
.custom-tabs-container {
    background-color: var(--pill-bg) !important;
    padding: 6px !important;
    border-radius: 30px !important;
}

.custom-track-pills {
    border: none !important;
    margin: 0 !important;
    padding: 0 !important;
    display: flex !important;
    width: 100%;
}

.custom-track-pills .nav-item { flex: 1; }
.custom-track-pills .nav-link {
    background: transparent !important;
    color: #4a5568 !important;
    font-weight: 500;
    padding: 8px 16px !important;
    border-radius: 24px !important;
    border: none !important;
    transition: all 0.2s ease;
    width: 100%;
}

.custom-track-pills .nav-link.active {
    background-color: #ffffff !important;
    color: var(--navy-base) !important;
    font-weight: 600;
    box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.08) !important;
}

.d-inline-block .custom-track-pills { width: auto !important; }
.d-inline-block .custom-track-pills .nav-item { flex: none !important; }

/* Overview Icon color */
.overview .icon{
    color: #fdd6eb !important;
}

/* Outer Widget Wrapper */
.calendar-widget {
    background-color: #ffffff;
    max-width: 100%;
    box-sizing: border-box;
}

/* Force strict 7-column matrix distribution with zero bleed */
.calendar-grid {
    display: grid !important;
    grid-template-columns: repeat(7, 1fr) !important; /* Divides space into 7 equal tracks */
    gap: 4px 0px; /* Controls row spacing cleanly without breaking widths */
    width: 100% !important;
    box-sizing: border-box;
}

/* Day Headings (Su, Mo, Tu...) */
.calendar-day { 
    font-size: 0.8rem; 
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    color: #6c757d; /* Clean Bootstrap secondary gray text */
    height: 32px;
    box-sizing: border-box;
}

/* Base Date Circles Layout */
.calendar-date {
    font-size: 0.9rem;
    font-weight: 500;
    color: #333;
    display: flex;
    align-items: center;
    justify-content: center;
    
    /* Responsive Circle Equation */
    width: 85%;            /* Dynamic width: fills 85% of its 1/7th column slice */
    aspect-ratio: 1 / 1;   /* Perfect Square Engine: guarantees height always matches width */
    border-radius: 50%;    /* Turns the perfect square into a flawless circle */
    
    box-sizing: border-box;
    margin: 0 auto;        /* Centers the circle within its grid track */
    transition: background-color 0.15s ease, color 0.15s ease;
}

/* Interactive Hover States */
.active-month-day { 
    cursor: pointer; 
}

.active-month-day:hover { 
    background-color: #e9ecef; 
}

/* Selected Active Date Highlight Color Ring */
.calendar-date.active {
    background-color: #0f2942 !important; /* Deep navy theme matching your design */
    color: white !important;
    font-weight: bold;
}

/* Today or Special In-Focus Neutral Ring Highlight Style (Optional) */
.calendar-date.today-highlight {
    border: 1px solid #0f2942;
    color: #0f2942;
}

/* Dimmed Text styling for previous/next month filler days */
.muted-day { 
    color: #ccc !important; 
    cursor: default;
}

.input-box:focus {
    border-color: #cfb2c4 !important;
    box-shadow: 0 0 0 0.2rem rgba(233, 192, 228, 0.25) !important;
}

/* Book as a Guest */
.custom-btn-a {
    background-color: #FCE7F3 !important;
    color: #0a2540 !important; /* text color */
    cursor: pointer !important;
    transition: 0.3s !important;
}

.custom-btn-a:hover {
    background-color: #fdd6eb !important;
    color: #0a2a5e !important;
}

/* Book as a User */
.custom-btn-b {
    background-color: transparent !important;
    color: #0a2540 !important; /* text color */
    border: 1px solid #0a2540 !important;
    cursor: pointer !important;
    transition: 0.3s !important;
}

.custom-btn-b:hover {
    background-color: #0a2540 !important;
    color: white !important;
    border-color: #0a2540 !important;
}

.chat-floating-btn {
    position: fixed;
    bottom: 24px;
    right: 24px;
    background-color: var(--navy-base);
    width: 54px;
    height: 54px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    z-index: 1050;
}
</style>

{{-- Real-Time Dynamic Grid Generation Script --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentDate = new Date(); 
    let selectedDate = new Date(); 

    const monthYearElement = document.getElementById('calendarMonthYear');
    const daysContainer = document.getElementById('calendarDaysContainer');
    const prevBtn = document.getElementById('prevMonthBtn');
    const nextBtn = document.getElementById('nextMonthBtn');
    const dateInput = document.getElementById('selectedReservationDate');

    const months = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();

        monthYearElement.textContent = `${months[month]} ${year}`;

        const firstDayIndex = new Date(year, month, 1).getDay();
        const lastDay = new Date(year, month + 1, 0).getDate();
        const prevLastDay = new Date(year, month, 0).getDate();
        
        const totalSquares = 42; 
        let daysHTML = "";

        // 1. Padding days (Previous month blocks)
        for (let x = firstDayIndex; x > 0; x--) {
            daysHTML += `<div class="calendar-date text-muted muted-day">${prevLastDay - x + 1}</div>`;
        }

        // 2. Main Active Days
        for (let i = 1; i <= lastDay; i++) {
            const isToday = i === new Date().getDate() && month === new Date().getMonth() && year === new Date().getFullYear();
            const isSelected = i === selectedDate.getDate() && month === selectedDate.getMonth() && year === selectedDate.getFullYear();
            
            let classList = "calendar-date active-month-day";
            if (isSelected) classList += " active";
            if (isToday && !isSelected) classList += " bg-light border text-navy";

            daysHTML += `<div class="${classList}" data-date="${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}">${i}</div>`;
        }

        // 3. Padding days (Next month blocks)
        const drawnDays = firstDayIndex + lastDay;
        const nextMonthPadding = totalSquares - drawnDays;
        for (let j = 1; j <= nextMonthPadding; j++) {
            daysHTML += `<div class="calendar-date text-muted muted-day">${j}</div>`;
        }

        daysContainer.innerHTML = daysHTML;

        // Add interactive event triggers
        document.querySelectorAll('.active-month-day').forEach(day => {
            day.addEventListener('click', function() {
                const targetValue = this.getAttribute('data-date');
                dateInput.value = targetValue;
                selectedDate = new Date(targetValue);
                renderCalendar(); 
            });
        });
    }

    prevBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });

    nextBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });

    renderCalendar();
});
</script>