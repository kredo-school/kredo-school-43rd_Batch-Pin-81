@extends('layouts.app')

@section('title', 'Search Area')

@section('content')
    <div class="w-100 py-5" style="background-color: #fffefc; min-height: calc(100vh - 70px);">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h3 class="fw-bold m-0 h5" style="color: #0a2540;">Area: {{ $area }}</h3>
                    <p class="text-muted small m-0">Browse restaurants located in {{ $area }}</p>
                </div>
                <a href="{{ route('customer.areas.index') }}" class="text-decoration-none small fw-bold text-secondary custom-btn">← Back to Areas</a>
            </div>

            @php
                $restaurantImages = [
                    'Sushi Masaru' => 'https://images.unsplash.com/photo-1696449241254-11cf7f18ce32?w=600',
                    'Ramen Ichiban' => 'https://images.unsplash.com/photo-1681270496598-13c5365730c8?w=600',
                    'Yakitori Tori' => 'https://images.unsplash.com/photo-1601351841251-766245326eee?w=600',
                ];
                $defaultImage = 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=600';
            @endphp

            <div class="row row-cols-1 row-cols-md-3 g-3 g-md-4">
                @forelse ($restaurants as $res)
                    <div class="col" style="cursor: pointer;"
                        onclick="location.href='{{ route('restaurant.show', $res->id) }}'">

                        <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden d-flex justify-content-between flex-row flex-md-column bg-white card-effect">
                            <div class="col-4 col-md-12"
                                style="background: url('{{ $restaurantImages[$res->restaurant_name] ?? $defaultImage }}') center/cover; min-height: 140px;">
                            </div>
                            <div class="card-body p-3 col-8 col-md-12">
                                <div class="d-none d-md-block">
                                    <div class="d-flex justify-content-between align-items-start w-100 mb-1" style="min-width: 0;">
                                        <div class="flex-grow-1 text-truncate me-2" style="min-width: 0;">
                                            <h6 class="fw-bold card-title m-0 text-truncate" style="color: #0a2540;">
                                                {{ $res->restaurant_name }}</h6>
                                        </div>
                                        <div class="d-flex align-items-center flex-shrink-0 ms-auto pt-0.5" style="line-height: 1;">
                                            <span class="text-warning me-1"><i class="bi bi-star-fill"></i></span>
                                            <span class="fw-bold text-dark me-1" style="font-size: 13px;">{{ $res->rating ?? '4.7' }}</span>
                                            <span class="text-muted" style="font-size: 11px;">({{ $res->reviews ?? '120' }})</span>
                                        </div>
                                    </div>
                                    <p class="text-muted small mb-1" style="font-size: 12px;">{{ $res->type ?? 'Restaurant' }}</p>
                                </div>

                                <div class="d-block d-md-none">
                                    <h6 class="fw-bold card-title mb-1 text-truncate" style="color: #0a2540;">
                                        {{ $res->restaurant_name }}</h6>
                                    <p class="text-muted small mb-1" style="font-size: 12px;">{{ $res->type ?? 'Restaurant' }}</p>
                                    <div class="mb-2" style="line-height: 1;">
                                        <span class="text-warning me-1"><i class="bi bi-star-fill"></i></span>
                                        <span class="fw-bold me-1" style="font-size: 13px;">{{ $res->rating ?? '4.7' }}</span>
                                        <span class="text-muted" style="font-size: 11px;">({{ $res->reviews ?? '120' }})</span>
                                    </div>
                                </div>

                                <div class="text-muted small mb-2 d-flex flex-wrap gap-1" style="font-size: 12px;">
                                    <i class="bi bi-geo-alt me-1 location-icon"></i>{{ $res->city }}
                                </div>

                                <div class="d-flex flex-wrap gap-1">
                                    <span class="badge rounded-pill fw-normal px-2 py-1 text-muted" style="background-color: #e8ebf1; font-size: 10px;">English Menu</span>
                                    <span class="badge rounded-pill fw-normal px-2 py-1 text-muted" style="background-color: #e8ebf1; font-size: 10px;">Available Now</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted m-0">No restaurants found in this area.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <style>
        .card-effect {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            cursor: pointer;
        }
        .card-effect:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1) !important;
        }
        .location-icon {
            color: #fabede !important;
        }
    </style>
@endsection