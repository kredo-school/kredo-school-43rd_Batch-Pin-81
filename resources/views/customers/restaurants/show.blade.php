@extends('layouts.app')

@section('title', 'Show Restaurant')

@section('content')

    <div class="container bg-light px-lg-5 py-4">

        <a href="{{ url()->previous() }}" class="pt-5 mb-3"><i class="fa-solid fa-chevron-left text-dark fs-3"></i></a>

        <div class="row g-4 mt-2">

            {{-- LEFT SIDE: Restaurant Photos, Details & Tabs --}}
            <div class="col-12 col-lg-9 order-lg-1 order-1">

                {{-- Inline Restaurant Photos Container --}}
                <div id="restaurantCarousel"
                    class="carousel slide position-relative w-100 bg-black rounded-4 overflow-hidden mb-4 shadow-sm"
                    data-bs-touch="true" data-bs-interval="false">

                    @if ($restaurant->photos && $restaurant->photos->isNotEmpty())
                        <div class="carousel-inner">
                            @foreach ($restaurant->photos as $photo)
                                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                    <div class="d-flex justify-content-center align-items-center image-container"
                                        style="background-color: #000; min-height: 400px; max-height: 500px; overflow: hidden;">
                                        <img src="{{ asset('storage/' . $photo->photo_path) }}"
                                            class="d-block w-100 h-100 restaurant-image"
                                            alt="{{ $restaurant->restaurant_name }} Photo">
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Carousel Controls --}}
                        <button class="carousel-control-prev custom-minimal-control" style="font-size: 3rem" type="button"
                            data-bs-target="#restaurantCarousel" data-bs-slide="prev">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button class="carousel-control-next custom-minimal-control" style="font-size: 3rem" type="button"
                            data-bs-target="#restaurantCarousel" data-bs-slide="next">
                            <i class="bi bi-chevron-right"></i>
                        </button>

                        {{-- Indicators --}}
                        <div class="carousel-indicators custom-indicators">
                            @foreach ($restaurant->photos as $index => $photo)
                                <button type="button" data-bs-target="#restaurantCarousel"
                                    data-bs-slide-to="{{ $index }}" class="{{ $loop->first ? 'active' : '' }}"
                                    aria-current="{{ $loop->first ? 'true' : 'false' }}"></button>
                            @endforeach
                        </div>
                    @else
                        <div class="d-flex justify-content-center align-items-center image-container"
                            style="background: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1200') center/cover; min-height: 400px; border-radius: 8px; position: relative;">
                            <div class="text-center text-white px-3 py-2 rounded" style="background: rgba(0, 0, 0, 0.5);">
                                <i class="bi bi-image fa-2x mb-2"></i>
                                <p class="mb-0 small">No photos available (Showing default view)</p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Main Restaurant Header Specs --}}
                <div class="card bg-white border-0 shadow-sm rounded-4 p-4 mb-4">
                    <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                        <h1 class="fw-bold mb-0 text-navy">{{ $restaurant->restaurant_name }}</h1>

                        @php
                            $averageRating = $restaurant->posts_avg_rating ?? 0;
                            $filledStars = (int) floor($averageRating);
                            $hasHalfStar = ($averageRating - $filledStars) >= 0.5;
                        @endphp
                        <div class="d-flex align-items-center gap-3 ms-auto flex-wrap justify-content-end">
                            {{-- Mobile --}}
                            <div class="d-inline-flex d-md-none align-items-center gap-2">
                                <div class="d-inline-flex align-items-center gap-1 text-navy rounded-pill px-2 py-1"
                                    aria-label="{{ number_format($averageRating, 1) }} out of 5 stars from {{ $restaurant->posts_count ?? 0 }} reviews">
                                    <i class="fa-solid fa-star text-warning"></i>
                                    <span class="fw-semibold">{{ number_format($averageRating, 1) }}</span>
                                    <span class="text-muted small">({{ $restaurant->posts_count ?? 0 }})</span>
                                </div>

                                @auth
                                    <button type="button" class="favorite-btn mb-0 d-inline-flex d-md-none"
                                        data-favorite-url="{{ route('favorites.store', $restaurant->id) }}"
                                        data-unfavorite-url="{{ route('favorites.destroy', $restaurant->id) }}"
                                        data-favorited="{{ $restaurant->is_favorited ? '1' : '0' }}"
                                        aria-label="{{ $restaurant->is_favorited ? 'Remove from favorites' : 'Add to favorites' }}">
                                        <i class="fa-{{ $restaurant->is_favorited ? 'solid' : 'regular' }} fa-heart {{ $restaurant->is_favorited ? 'text-warning' : 'text-dark' }}"></i>
                                    </button>
                                @endauth
                            </div>

                            {{-- Desktop --}}
                            <div class="star-rating d-none d-md-flex align-items-center" id="restaurantReviewsShortcut"
                                role="button" tabindex="0" aria-label="Open reviews tab">
                                <div class="star-rating-stars" aria-label="{{ number_format($averageRating, 1) }} out of 5 stars">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="star-rating-star {{ $i <= $filledStars ? 'filled' : ($i === $filledStars + 1 && $hasHalfStar ? 'half' : 'empty') }}">
                                            ★
                                        </span>
                                    @endfor
                                </div>
                                <span class="fs-6 mx-1">{{ number_format($averageRating, 1) }}</span>
                                <span class="small text-secondary">({{ $restaurant->posts_count ?? 0 }})</span>
                            </div>
                            @auth
                                <button type="button" class="favorite-btn mb-0 d-none d-md-inline-flex"
                                    data-favorite-url="{{ route('favorites.store', $restaurant->id) }}"
                                    data-unfavorite-url="{{ route('favorites.destroy', $restaurant->id) }}"
                                    data-favorited="{{ $restaurant->is_favorited ? '1' : '0' }}"
                                    aria-label="{{ $restaurant->is_favorited ? 'Remove from favorites' : 'Add to favorites' }}">
                                    <i class="fa-{{ $restaurant->is_favorited ? 'solid' : 'regular' }} fa-heart {{ $restaurant->is_favorited ? 'text-warning' : 'text-dark' }}"></i>
                                </button>
                            @endauth
                        </div>
                    </div>

                    <p class="text-muted mb-3 fs-5">
                        {{ $restaurant->description }}
                    </p>

                    @if ($restaurant->features && $restaurant->features->isNotEmpty())
                        <div class="mt-3">
                            <div class="feature-chip-scroll">
                                <div class="feature-chip-rail">
                                    @foreach ($restaurant->features as $feature)
                                        <span class="badge feature-chip rounded-pill fw-normal px-2 py-1 text-muted"
                                            style="background-color: #e8ebf1; font-size: 10px;">
                                            {{ $feature->feature_name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- MAIN TRACK PILL TABS SYSTEM --}}
                <div class="custom-tabs-container mb-4">
                    <ul class="nav nav-pills custom-track-pills text-center" id="restaurantTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active text-navy" id="overview-tab" data-bs-toggle="pill"
                                data-bs-target="#overview" type="button" role="tab">Overview</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-navy" id="menu-tab" data-bs-toggle="pill" data-bs-target="#menu"
                                type="button" role="tab">Menu</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-navy" id="photos-tab" data-bs-toggle="pill"
                                data-bs-target="#photos" type="button" role="tab">Photos</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-navy" id="reviews-tab" data-bs-toggle="pill"
                                data-bs-target="#reviews" type="button" role="tab">Reviews</button>
                        </li>
                    </ul>
                </div>

                {{-- TAB PANELS --}}
                <div class="tab-content" id="restaurantTabsContent">

                    {{-- 1. OVERVIEW --}}
                    <div class="tab-pane fade show active" id="overview" role="tabpanel">
                        <div class="card bg-white border-0 shadow-sm rounded-4 p-4">


                            <div class="mt-4">
                                <div class="row g-4">
                                    {{-- LEFT COLUMN: Location, Phone, Party Size --}}
                                    <div class="col-md-6 d-flex flex-column gap-4 overview">
                                        {{-- Location --}}
                                        <div class="d-flex align-items-start gap-3">
                                            <i class="bi bi-geo-alt fs-4 mt-1 icon"></i>
                                            <div>
                                                <h6 class="fw-bold text-navy mb-1">Location</h6>
                                                <a href="https://maps.google.com/?q=3-8-15+Ginza,+Chuo-ku,+Tokyo"
                                                    target="_blank" class="text-muted text-decoration-underline">
                                                    {{ $restaurant->postal_code }}
                                                    {{ $restaurant->prefecture }}
                                                    {{ $restaurant->city }}
                                                    {{ $restaurant->street_address_building }}
                                                </a>
                                            </div>
                                        </div>

                                        {{-- Phone --}}
                                        <div class="d-flex align-items-start gap-3">
                                            <i class="bi bi-telephone fs-4 mt-1 icon"></i>
                                            <div>
                                                <h6 class="fw-bold text-navy mb-1">Phone</h6>
                                                <p class="text-muted mb-1">{{ $restaurant->phone_number }}</p>
                                                <small class="text-success d-flex align-items-center gap-1">
                                                    <i class="bi bi-check-lg"></i> English speaking staff available
                                                </small>
                                            </div>
                                        </div>

                                        {{-- Party Size --}}
                                        <div class="d-flex align-items-start gap-3">
                                            <i class="bi bi-people fs-4 mt-1 icon"></i>
                                            <div>
                                                <h6 class="fw-bold text-navy mb-1">Maximum Party Size</h6>
                                                <p class="text-muted mb-0">Up to {{ $restaurant->capacity }} guests</p>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- RIGHT COLUMN: Hours, Website & SNS Links --}}
                                    <div class="col-md-6 d-flex flex-column gap-4 overview">
                                        {{-- Hours --}}
                                        <div class="d-flex align-items-start gap-3">
                                            <i class="bi bi-clock fs-4 mt-1 icon"></i>
                                            <div>
                                                <h6 class="fw-bold text-navy mb-1">Today's Hours</h6>
                                                <p class="text-muted mb-0">
                                                    @if ($restaurant->today_hours)
                                                        {{ $restaurant->today_hours }}
                                                    @else
                                                        Closed today
                                                    @endif
                                                </p>
                                            </div>
                                        </div>

                                        {{-- Website & SNS Links --}}
                                        <div class="d-flex align-items-start gap-3">
                                            <i class="bi bi-globe fs-4 mt-1 icon"></i>
                                            <div>
                                                <p class="mb-2 d-flex align-items-center gap-2 flex-wrap">
                                                    @if ($restaurant->website)
                                                        <a href="{{ $restaurant->website }}" target="_blank"
                                                            rel="noopener noreferrer"
                                                            class="text-muted text-decoration-none hover-underline d-inline-flex align-items-center gap-2">
                                                            {{ $restaurant->website }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </p>

                                                {{-- SNS Quick Links Row --}}
                                                <div class="d-flex align-items-center gap-3 mt-2">
                                                    @if ($restaurant->instagram)
                                                        <a href="{{ $restaurant->instagram }}" target="_blank"
                                                            rel="noopener noreferrer"
                                                            class="text-muted fs-5 transition-colors" title="Instagram"
                                                            style="opacity: 0.85;">
                                                            <i class="bi bi-instagram text-danger"></i>
                                                        </a>
                                                    @endif
                                                    @if ($restaurant->facebook)
                                                        <a href="{{ $restaurant->facebook }}" target="_blank"
                                                            rel="noopener noreferrer"
                                                            class="text-muted fs-5 transition-colors" title="Facebook"
                                                            style="opacity: 0.85;">
                                                            <i class="bi bi-facebook text-primary"></i>
                                                        </a>
                                                    @endif
                                                    @if ($restaurant->twitter)
                                                        <a href="{{ $restaurant->twitter }}" target="_blank"
                                                            rel="noopener noreferrer"
                                                            class="text-muted fs-5 transition-colors" title="X (Twitter)"
                                                            style="opacity: 0.85;">
                                                            <i class="bi bi-twitter-x text-dark"></i>
                                                        </a>
                                                    @endif
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
                                    <button class="nav-link active px-4" id="food-tab" data-bs-toggle="pill"
                                        data-bs-target="#menu-food" type="button" role="tab">Food</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link px-4" id="drink-tab" data-bs-toggle="pill"
                                        data-bs-target="#menu-drink" type="button" role="tab">Drink</button>
                                </li>
                            </ul>
                        </div>

                        @php
                            $foodMenus = $restaurant->menus->where('menu_category', 'food');
                            $drinkMenus = $restaurant->menus->where('menu_category', 'drink');
                        @endphp

                        <div class="tab-content" id="menuSubTabsContent">
                            <div class="tab-pane fade show active" id="menu-food" role="tabpanel">
                                <div class="card bg-white border-0 shadow-sm rounded-4 p-4">
                                    @forelse ($foodMenus as $menu)
                                        <div class="menu-item row g-3 align-items-center border-bottom pb-3 mb-3">
                                            <div class="col-md-2 col-4">
                                                @if ($menu->menu_image)
                                                    @php
                                                        $menuImageUrl = str_starts_with($menu->menu_image, 'demo/')
                                                            ? asset('storage/' . $menu->menu_image)
                                                            : asset('assets/images/menu/' . $menu->menu_image);
                                                    @endphp
                                                    <img src="{{ $menuImageUrl }}"
                                                        alt="{{ $menu->menu_name }}"
                                                        class="img-fluid rounded-3 w-100 object-fit-cover"
                                                        style="aspect-ratio: 1 / 1; min-height: 84px;">
                                                @else
                                                    <div class="rounded-3 bg-light d-flex align-items-center justify-content-center text-muted"
                                                        style="aspect-ratio: 1 / 1; min-height: 84px;">
                                                        <i class="bi bi-image"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-7 col-8">
                                                <h5 class="fw-bold text-navy mb-1">{{ $menu->menu_name }}</h5>
                                                <p class="text-muted mb-0 small">{{ $menu->description }}</p>
                                                <span class="fw-bold text-navy fs-5 d-md-none d-block mt-2">¥{{ number_format($menu->price) }}</span>
                                            </div>
                                            <div class="col-md-3 col-12 text-md-end d-none d-md-block">
                                                <span
                                                    class="fw-bold text-navy fs-5">¥{{ number_format($menu->price) }}</span>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted mb-0">No food menu items available.</p>
                                    @endforelse
                                </div>
                            </div>
                            <div class="tab-pane fade" id="menu-drink" role="tabpanel">
                                <div class="card bg-white border-0 shadow-sm rounded-4 p-4">
                                    @forelse ($drinkMenus as $menu)
                                        <div class="menu-item row g-3 align-items-center border-bottom pb-3 mb-3">
                                            <div class="col-md-2 col-4">
                                                @if ($menu->menu_image)
                                                    @php
                                                        $menuImageUrl = str_starts_with($menu->menu_image, 'demo/')
                                                            ? asset('storage/' . $menu->menu_image)
                                                            : asset('assets/images/menu/' . $menu->menu_image);
                                                    @endphp
                                                    <img src="{{ $menuImageUrl }}"
                                                        alt="{{ $menu->menu_name }}"
                                                        class="img-fluid rounded-3 w-100 object-fit-cover"
                                                        style="aspect-ratio: 1 / 1; min-height: 84px;">
                                                @else
                                                    <div class="rounded-3 bg-light d-flex align-items-center justify-content-center text-muted"
                                                        style="aspect-ratio: 1 / 1; min-height: 84px;">
                                                        <i class="bi bi-cup-straw"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-7 col-8">
                                                <h5 class="fw-bold text-navy mb-1">{{ $menu->menu_name }}</h5>
                                                <p class="text-muted mb-0 small">{{ $menu->description }}</p>
                                                <span class="fw-bold text-navy fs-5 d-md-none d-block mt-2">¥{{ number_format($menu->price) }}</span>
                                            </div>
                                            <div class="col-md-3 col-12 text-md-end d-none d-md-block">
                                                <span
                                                    class="fw-bold text-navy fs-5">¥{{ number_format($menu->price) }}</span>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted mb-0">No drink menu items available.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. PHOTOS --}}
                    <div class="tab-pane fade" id="photos" role="tabpanel">
                        <div class="custom-tabs-container photo-tabs-container mb-4">
                            <ul class="nav nav-pills custom-track-pills photo-sub-tabs text-center"
                                id="photoSubTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active px-3" data-bs-toggle="pill"
                                        data-bs-target="#photo-all" type="button" role="tab">All</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link px-3" data-bs-toggle="pill" data-bs-target="#photo-food"
                                        type="button" role="tab">Food</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link px-3" data-bs-toggle="pill" data-bs-target="#photo-drink"
                                        type="button" role="tab">Drink</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link px-3" data-bs-toggle="pill" data-bs-target="#photo-interior"
                                        type="button" role="tab">Interior</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link px-3" data-bs-toggle="pill" data-bs-target="#photo-exterior"
                                        type="button" role="tab">Exterior</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link px-3" data-bs-toggle="pill" data-bs-target="#photo-other"
                                        type="button" role="tab">Other</button>
                                </li>
                            </ul>
                        </div>

                        @php
                            $photoCategories = [
                                'all' => 'All',
                                'food' => 'Food',
                                'drink' => 'Drink',
                                'interior' => 'Interior',
                                'exterior' => 'Exterior',
                                'other' => 'Other',
                            ];

                            $photosByCategory = [
                                'all' => $restaurant->photos,
                                'food' => $restaurant->photos->where('photo_category', 'food'),
                                'drink' => $restaurant->photos->where('photo_category', 'drink'),
                                'interior' => $restaurant->photos->where('photo_category', 'interior'),
                                'exterior' => $restaurant->photos->where('photo_category', 'exterior'),
                                'other' => $restaurant->photos->where('photo_category', 'other'),
                            ];
                        @endphp

                        <div class="tab-content">
                            @foreach ($photoCategories as $key => $label)
                                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                    id="photo-{{ $key }}" role="tabpanel">
                                    @php $filteredPhotos = $photosByCategory[$key]; @endphp

                                    <div class="row g-3">
                                        @forelse ($filteredPhotos as $photo)
                                            <div class="col-md-4 col-6">
                                                <div
                                                    class="photo-wrapper shadow-sm rounded-4 overflow-hidden position-relative">
                                                    <img src="{{ asset('storage/' . $photo->photo_path) }}"
                                                        alt="{{ $label }} photo"
                                                        class="photo-item-img photo-zoom-trigger"
                                                        data-photo-src="{{ asset('storage/' . $photo->photo_path) }}"
                                                        data-photo-alt="{{ $label }} photo">
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12 text-center py-4 text-muted">
                                                No {{ strtolower($label) }} photos available.
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- 4. REVIEWS (💡 更生ポイント: ここをダミーから本物のループ画像対応に変更) --}}
                    <div class="tab-pane fade" id="reviews" role="tabpanel">
                        <div class="card bg-white border-0 shadow-sm rounded-4 p-4 d-flex flex-column gap-4">

                            @forelse ($restaurant->posts as $post)
                                @php
                                    $user = $post->user;
                                    $isLiked = auth()->check()
                                        ? $post->likes->contains('user_id', auth()->id())
                                        : false;
                                    $likesCount = $post->likes->count();
                                @endphp
                                <div class="review-item border-bottom pb-4 mb-2">
                                    <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="https://i.pravatar.cc/150?img={{ $post->user_id ?? 1 }}"
                                                alt="Avatar" class="rounded-circle"
                                                style="width: 48px; height: 48px; object-fit: cover;">
                                            <div>
                                                <div class="d-flex align-items-center flex-wrap gap-2">
                                                    <a href="{{ $user ? route('customer.user.profile', $user) : '#' }}"
                                                        class="fw-bold text-navy mb-0 text-decoration-none d-inline-flex align-items-center gap-2">
                                                        <span>{{ $user->name ?? 'Anonymous User' }}</span>
                                                    </a>
                                                    <div class="text-warning fs-7">
                                                        {{ str_repeat('★', $post->rating) }}{{ str_repeat('☆', 5 - $post->rating) }}
                                                    </div>
                                                </div>
                                                <div class="text-muted fw-normal fs-7">·
                                                    {{ $post->created_at ? $post->created_at->diffForHumans() : '' }}</div>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                            @auth
                                                <button type="button"
                                                    class="btn btn-link p-0 border-0 bg-transparent shadow-none review-like-btn d-inline-flex align-items-center justify-content-center"
                                                    data-like-url="{{ route('customer.posts.like', $post) }}"
                                                    data-post-id="{{ $post->id }}"
                                                    data-liked="{{ $isLiked ? '1' : '0' }}" aria-label="Like review"
                                                    style="width: auto; height: auto;">
                                                    <i
                                                        class="bi {{ $isLiked ? 'bi-heart-fill text-danger' : 'bi-heart text-muted' }} fs-4"></i>
                                                </button>
                                            @else
                                                <a href="{{ route('login') }}"
                                                    class="btn btn-link p-0 border-0 bg-transparent shadow-none d-inline-flex align-items-center justify-content-center"
                                                    aria-label="Login to like review" style="width: auto; height: auto;">
                                                    <i class="bi bi-heart text-muted fs-4"></i>
                                                </a>
                                            @endauth

                                            <span class="text-muted small fw-semibold review-like-count"
                                                data-like-count-for="{{ $post->id }}">{{ $likesCount }}</span>
                                        </div>
                                    </div>

                                    <div class="row g-3 align-items-start">
                                        @if ($post->image)
                                            <div class="col-md-4 col-12">
                                                <img src="{{ asset('storage/' . $post->image) }}" alt="Review Image"
                                                    class="img-fluid rounded-4 shadow-sm w-100 object-fit-cover"
                                                    style="max-height: 180px; cursor: pointer;"
                                                    onclick="window.open(this.src)">
                                            </div>
                                        @endif

                                        <div class="col text-muted mb-0 fs-6">
                                            {{ $post->description }}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted mb-0 text-center py-3">No reviews yet. Be the first to leave a review!
                                </p>
                            @endforelse

                        </div>
                    </div>

                </div>
            </div>

            {{-- RIGHT SIDE: Calendar Reservation Card --}}
            <div class="col-12 col-lg-3 order-lg-2 order-2">
                <div class="card bg-white border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 24px; z-index: 100;">
                    <h5 class="fw-bold text-navy mb-4">Make a Reservation</h5>

                    <form action="{{ route('booking.create', $restaurant->id) }}" method="GET" id="reservationForm">
                        <input type="hidden" name="date" id="selectedReservationDate"
                            value="{{ now()->format('Y-m-d') }}">

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
                                <div class="calendar-grid text-center mb-1">
                                    <div class="calendar-day text-muted fw-semibold">Su</div>
                                    <div class="calendar-day text-muted fw-semibold">Mo</div>
                                    <div class="calendar-day text-muted fw-semibold">Tu</div>
                                    <div class="calendar-day text-muted fw-semibold">We</div>
                                    <div class="calendar-day text-muted fw-semibold">Th</div>
                                    <div class="calendar-day text-muted fw-semibold">Fr</div>
                                    <div class="calendar-day text-muted fw-semibold">Sa</div>
                                </div>
                                <div class="calendar-grid text-center" id="calendarDaysContainer"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-navy small">Guests</label>
                            <select name="guests" id="reservationGuests"
                                class="form-select border rounded-3 py-2 text-muted input-box" required>
                                @php
                                    $maxBookableGuests =
                                        (int) ($restaurant->tables()->where('is_active', true)->max('capacity') ?? 0);
                                @endphp
                                @for ($i = 1; $i <= $maxBookableGuests; $i++)
                                    <option value="{{ $i }}">{{ $i }}
                                        {{ $i === 1 ? 'Person' : 'People' }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="mb-2">
                            <label class="form-label fw-bold text-navy small">Available Time</label>
                            <select name="time" id="reservationTime"
                                class="form-select border rounded-3 py-2 text-muted input-box" required>
                                <option value="">Select time</option>
                            </select>
                        </div>
                        <div id="reservationAvailabilityMessage" class="small text-muted mb-4"></div>

                        <button type="submit" id="reservationSubmitButton"
                            class="btn custom-btn-a w-100 py-2 rounded-3 fw-semibold" disabled>
                            Continue to Booking
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="chat-floating-btn shadow">
        <i class="bi bi-chat-left-dots-fill text-white"></i>
    </div>

    <div class="modal fade" id="photoZoomModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-body p-0 position-relative">
                    <button type="button"
                        class="photo-zoom-close-btn position-absolute top-0 end-0 m-3 z-3 p-0 border-0 bg-transparent text-white"
                        data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                    <img id="photoZoomModalImage" src="" alt="Zoomed photo"
                        class="w-100 rounded-4 shadow-lg bg-black" style="max-height: 90vh; object-fit: contain;">
                </div>
            </div>
        </div>
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

    .text-navy {
        color: var(--navy-base);
    }

    .fs-7 {
        font-size: 0.85rem;
    }

    * {
        font-family: inter;
    }

    /* ==========================================================================
   1. PHOTO CAROUSEL CONTAINERS & RESPONSIVE WIDTHS
   ========================================================================== */
    .image-container {
        height: 460px;
        width: 100%;
        background-color: #000;
        /* Keeps the black backdrop edges matching your theme */
    }

    /* Default Mobile: Image stretches 100% to fill screen and give a huge swipe target */
    .restaurant-image {
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: center;
        background-color: #000;
    }

    /* Shrink image container height on mobile screens */
    @media (max-width: 768px) {
        .image-container {
            height: 235px !important;
            min-height: 235px !important;
            max-height: 235px !important;
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
        touch-action: pan-y;
        /* Forces mobile browsers to pass horizontal swipes to Bootstrap */
    }

    .custom-minimal-control {
        background: none !important;
        border: none !important;
        width: 50px;
        height: 100%;
        display: flex !important;
        /* Keeps container rendered on mobile so swipe listeners stay alive */
        align-items: center;
        justify-content: center;
    }

    /* Mobile-First: Make the chevron icons completely invisible, but touch zone stays live */
    .custom-minimal-control i {
        font-size: 2.5rem;
        /* Large, clickable arrow dimensions */
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

    /* Stars */
    .star-rating {
        position: relative;
        display: inline-block;
        font-size: 1rem;
        line-height: 1;
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

    .favorite-form {
        display: inline-flex;
        align-items: center;
        justify-content: center;
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
        padding: 0.1rem 0.25rem;
        appearance: none;
    }

    .favorite-btn i {
        font-size: 1.9rem;
        margin: 4px;
    }

    @media (max-width: 768px) {
        .favorite-btn {
            padding: 0;
        }

        .favorite-btn i {
            font-size: 1.45rem;
            margin: 0;
        }
    }

    .feature-chip-scroll {
        width: 100%;
    }

    .feature-chip-rail {
        display: inline-flex;
        gap: 0.5rem;
        min-width: 0;
        flex-wrap: wrap;
        width: 100%;
    }

    .feature-chip {
        flex: 0 0 auto;
        white-space: nowrap;
    }

    @media (max-width: 768px) {
        .feature-chip-scroll {
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            padding-bottom: 2px;
            touch-action: pan-x;
            overscroll-behavior-x: contain;
        }

        .feature-chip-scroll::-webkit-scrollbar {
            display: none;
        }

        .feature-chip-rail {
            display: inline-flex;
            flex-wrap: nowrap;
            min-width: max-content;
            width: max-content;
        }
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

    .custom-track-pills .nav-item {
        flex: 1;
    }

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

    .d-inline-block .custom-track-pills {
        width: auto !important;
    }

    .d-inline-block .custom-track-pills .nav-item {
        flex: none !important;
    }

    .photo-tabs-container {
        width: 100%;
        max-width: 100%;
        overflow-x: hidden;
    }

    @media (min-width: 769px) {
        .photo-tabs-container {
            display: inline-block;
            width: auto;
            max-width: 100%;
        }

        .photo-sub-tabs {
            width: max-content !important;
        }
    }

    .photo-sub-tabs {
        flex-wrap: nowrap !important;
        gap: 0.25rem;
        overflow-x: auto;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }

    .photo-sub-tabs::-webkit-scrollbar {
        display: none;
    }

    .photo-sub-tabs .nav-item {
        flex: 0 0 auto !important;
    }

    .photo-sub-tabs .nav-item:last-child {
        padding-right: 0.25rem;
    }

    .photo-sub-tabs .nav-link {
        white-space: nowrap;
    }

    .photo-sub-tabs .nav-link.active {
        flex-shrink: 0;
    }

    @media (max-width: 768px) {
        #restaurantTabs.custom-track-pills {
            flex-wrap: wrap !important;
            gap: 6px;
        }

        #restaurantTabs .nav-item {
            flex: 1 1 calc(33.333% - 4px) !important;
            min-width: 0;
        }

        #restaurantTabs .nav-item:first-child {
            flex: 0 0 100% !important;
        }

        #restaurantTabs .nav-link {
            width: 100%;
            text-align: center;
        }
    }

    /* Overview Icon color */
    .overview .icon {
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
        grid-template-columns: repeat(7, 1fr) !important;
        /* Divides space into 7 equal tracks */
        gap: 4px 0px;
        /* Controls row spacing cleanly without breaking widths */
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
        color: #6c757d;
        /* Clean Bootstrap secondary gray text */
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
        width: 85%;
        /* Dynamic width: fills 85% of its 1/7th column slice */
        aspect-ratio: 1 / 1;
        /* Perfect Square Engine: guarantees height always matches width */
        border-radius: 50%;
        /* Turns the perfect square into a flawless circle */

        box-sizing: border-box;
        margin: 0 auto;
        /* Centers the circle within its grid track */
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
        background-color: #0f2942 !important;
        /* Deep navy theme matching your design */
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
        color: #0a2540 !important;
        /* text color */
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
        color: #0a2540 !important;
        /* text color */
        border: 1px solid #0a2540 !important;
        cursor: pointer !important;
        transition: 0.3s !important;
    }

    .custom-btn-b:hover {
        background-color: #0a2540 !important;
        color: white !important;
        border-color: #0a2540 !important;
    }

    .photo-wrapper {
        width: 100%;
        aspect-ratio: 1 / 1;
        background-color: #f1f3f5;
    }

    .photo-zoom-trigger {
        cursor: zoom-in;
    }

    .photo-zoom-trigger:hover {
        opacity: 0.95;
    }

    .photo-zoom-close-btn {
        font-size: 2rem;
        line-height: 1;
        box-shadow: none !important;
    }

    .photo-zoom-close-btn:hover,
    .photo-zoom-close-btn:focus {
        color: #ffffff;
        box-shadow: none !important;
        outline: none;
    }

    .photo-item-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
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

    .review-like-btn {
        transition: transform 0.15s ease, background-color 0.15s ease;
    }

    .review-like-btn:hover {
        transform: scale(1.04);
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
        const guestsInput = document.getElementById('reservationGuests');
        const timeInput = document.getElementById('reservationTime');
        const availabilityMessage = document.getElementById('reservationAvailabilityMessage');
        const submitButton = document.getElementById('reservationSubmitButton');
        const availabilityEndpoint = @json(route('booking.availability', $restaurant));
        const todayString = new Date().toLocaleDateString('en-CA');

        async function refreshReservationAvailability() {
            if (!dateInput?.value || !guestsInput?.value || !timeInput) return;

            timeInput.disabled = true;
            submitButton.disabled = true;
            availabilityMessage.textContent = 'Checking availability...';

            try {
                const url = new URL(availabilityEndpoint, window.location.origin);
                url.searchParams.set('date', dateInput.value);
                url.searchParams.set('guests', guestsInput.value);
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                if (!response.ok) throw new Error('Unable to load availability.');

                const data = await response.json();
                timeInput.innerHTML = '<option value="">Select time</option>';
                data.slots.forEach(slot => {
                    const option = document.createElement('option');
                    option.value = slot;
                    option.textContent = slot;
                    timeInput.appendChild(option);
                });

                timeInput.disabled = false;
                submitButton.disabled = data.slots.length === 0;
                availabilityMessage.textContent = data.slots.length ?
                    `${data.slots.length} available time slot(s).` :
                    'No available time slots for this date and party size.';
            } catch (error) {
                timeInput.innerHTML = '<option value="">Select time</option>';
                availabilityMessage.textContent = error.message;
            }
        }

        guestsInput?.addEventListener('change', refreshReservationAvailability);

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
                const isToday = i === new Date().getDate() && month === new Date().getMonth() && year ===
                    new Date().getFullYear();
                const isSelected = i === selectedDate.getDate() && month === selectedDate.getMonth() && year ===
                    selectedDate.getFullYear();

                const dateValue = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
                const isPast = dateValue < todayString;
                let classList = "calendar-date";
                if (!isPast) classList += " active-month-day";
                if (isPast) classList += " text-muted muted-day";
                if (isSelected && !isPast) classList += " active";
                if (isToday && !isSelected) classList += " bg-light border text-navy";

                daysHTML += `<div class="${classList}" ${isPast ? '' : `data-date="${dateValue}"`}>${i}</div>`;
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
                    selectedDate = new Date(`${targetValue}T00:00:00`);
                    renderCalendar();
                    refreshReservationAvailability();
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
        refreshReservationAvailability();

        const zoomModalElement = document.getElementById('photoZoomModal');
        const zoomModalImage = document.getElementById('photoZoomModalImage');
        const restaurantReviewsShortcut = document.getElementById('restaurantReviewsShortcut');
        const reviewsTabButton = document.getElementById('reviews-tab');
        const reviewsPane = document.getElementById('reviews');

        if (restaurantReviewsShortcut && reviewsTabButton && reviewsPane) {
            const openReviewsTab = function() {
                bootstrap.Tab.getOrCreateInstance(reviewsTabButton).show();
            };

            restaurantReviewsShortcut.addEventListener('click', openReviewsTab);
            restaurantReviewsShortcut.addEventListener('keydown', function(event) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    openReviewsTab();
                }
            });

            reviewsTabButton.addEventListener('shown.bs.tab', function() {
                reviewsPane.setAttribute('tabindex', '-1');
                reviewsPane.focus({
                    preventScroll: true
                });
                reviewsPane.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            });
        }

        document.querySelectorAll('.photo-zoom-trigger').forEach((photo) => {
            photo.addEventListener('click', function() {
                zoomModalImage.src = this.dataset.photoSrc;
                zoomModalImage.alt = this.dataset.photoAlt || 'Zoomed photo';

                const zoomModal = bootstrap.Modal.getOrCreateInstance(zoomModalElement);
                zoomModal.show();
            });
        });

        document.querySelectorAll('.review-like-btn').forEach((button) => {
            button.addEventListener('click', async function() {
                const likeUrl = this.dataset.likeUrl;
                const postId = this.dataset.postId;
                const icon = this.querySelector('i');
                const countElement = document.querySelector(
                    `[data-like-count-for="${postId}"]`);

                try {
                    const response = await fetch(likeUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (response.status === 401) {
                        window.location.href = '{{ route('login') }}';
                        return;
                    }

                    if (!response.ok) {
                        throw new Error('Failed to toggle like');
                    }

                    const data = await response.json();
                    const isLiked = data.isLiked;

                    this.dataset.liked = isLiked ? '1' : '0';
                    icon.className =
                        `bi ${isLiked ? 'bi-heart-fill text-danger' : 'bi-heart text-muted'} fs-4`;

                    if (countElement) {
                        countElement.textContent = data.likes_count;
                    }
                } catch (error) {
                    console.error(error);
                }
            });
        });

        document.querySelectorAll('.favorite-btn[data-favorite-url]').forEach((button) => {
            button.addEventListener('click', async function() {
                const favoriteUrl = this.dataset.favoriteUrl;
                const unfavoriteUrl = this.dataset.unfavoriteUrl;
                const isFavorited = this.dataset.favorited === '1';
                const icon = this.querySelector('i');

                try {
                    const response = await fetch(isFavorited ? unfavoriteUrl : favoriteUrl, {
                        method: isFavorited ? 'DELETE' : 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (response.status === 401) {
                        window.location.href = '{{ route('login') }}';
                        return;
                    }

                    if (!response.ok) {
                        throw new Error('Failed to update favorite');
                    }

                    const data = await response.json();
                    const nextFavorited = Boolean(data.isFavorited);

                    this.dataset.favorited = nextFavorited ? '1' : '0';
                    this.setAttribute('aria-label', nextFavorited ? 'Remove from favorites' : 'Add to favorites');
                    icon.className = `fa-${nextFavorited ? 'solid' : 'regular'} fa-heart ${nextFavorited ? 'text-warning' : 'text-dark'}`;
                } catch (error) {
                    console.error(error);
                }
            });
        });

        zoomModalElement.addEventListener('hidden.bs.modal', function() {
            zoomModalImage.src = '';
            zoomModalImage.alt = 'Zoomed photo';
        });

    });
</script>
