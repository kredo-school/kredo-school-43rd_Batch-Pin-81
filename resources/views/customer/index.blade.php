@extends('layouts.app')

@section('title', 'Restaurant Search')

@section('content')

    {{-- Google Map --}}
    <div style="background-color: #fffefc;">
        <div class="container py-3">
            <div class="position-relative rounded-4 overflow-hidden shadow-sm" style="height: 350px;">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d103706.74411130098!2d139.704051!3d35.676192!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x60188b8576282355%3A0xc324620e31575651!2z5p2x5Lqs!5e0!3m2!1sen!2sjp!4v1717040000000!5m2!1sen!2sjp&language=en"
                    class="w-100 h-100 border-0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                </iframe>
                <div class="position-absolute bottom-0 start-0 m-3 p-3 bg-dark bg-opacity-75 rounded-3 text-white"
                    style="max-width: 250px; pointer-events: none;">
                    <h3 class="fw-bold mb-1 h6 text-white">Explore Tokyo</h3>
                    <p class="small m-0 opacity-75" style="font-size: 10px;">Interactive Restaurant Discovery</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Food Categories --}}
    <div class="py-5" style="background-color: #fffefc;">
        <div class="container">

            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h3 class="fw-bold m-0 h5 text-color: #0a2540">Food Categories</h3>
                    <p class="text-muted small m-0">Explore your favorite dishes in Tokyo</p>
                </div>
                <a href="#" class="text-decoration-none small fw-bold text-secondary custom-btn">View all →</a>
            </div>

            @php
                $categoryImages = [
                    'Sushi' => 'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?w=400',
                    'Ramen' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=400',
                    'Yakitori' => 'https://x.gd/OdlVj',
                    'Kaiseki' => 'https://x.gd/EXsEH',
                    'Izakaya' => 'https://x.gd/ePNZx',
                ];
                $allTags = ['Sushi', 'Ramen', 'Yakitori', 'Kaiseki', 'Izakaya'];

                $displayTags = array_slice($allTags, 0, 4);
            @endphp

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-3">
                @foreach ($displayTags as $tag)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden bg-white card-effect">
                            <div class="position-relative">

                                <div
                                    style="height: 140px; background: url('{{ $categoryImages[$tag] ?? 'https://via.placeholder.com/300xl40' }}') center/cover;">
                                </div>

                                <div class="position-absolute bottom-0 start-0 w-100 p-2 text-white"
                                    style="background: linear-gradient(to top, rgba(0,0,0,0.6), transparent);">
                                    <h6 class="fw-bold mb-0 text-white">{{ $tag }}</h6>
                                    <span style="font-size: 11px; opacity: 0.9;">View restaurants</span>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>

    {{-- Popular Areas --}}
    <div class="py-5" style="background-color: #fffefc;">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h3 class="fw-bold m-0 h5 text-color: #0a2540">Popular Areas</h3>
                    <p class="text-muted small m-0">Dining hotspots in Tokyo</p>
                </div>
                <a href="#" class="text-decoration-none small fw-bold text-secondary custom-btn">View all →</a>
            </div>

            @php
                $areaImages = [
                    'Ginza' => 'https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?w=400',
                    'Shibuya' => 'https://images.unsplash.com/photo-1542051841857-5f90071e7989?w=400',
                    'Shinjuku' => 'https://images.unsplash.com/photo-1503899036084-c55cdd92da26?w=400',
                    'Roppongi' => 'https://images.unsplash.com/photo-1524413840807-0c3cb6fa808d?w=400',
                    'Asakusa' => 'https://images.unsplash.com/photo-1524413840807-0c3cb6fa808d?w=400',
                ];
                $allTags = ['Ginza', 'Shibuya', 'Shinjuku', 'Roppongi', 'Asakusa'];

                $displayTags = array_slice($allTags, 0, 4);
            @endphp

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-3">
                @foreach ($chartData as $area)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden bg-white card-effect">
                            <div class="position-relative"
                                style="height: 140px; background: url('{{ $areaImages[$area['name']] ?? 'https://via.placeholder.com/300x140' }}') center/cover;">
                                <div class="position-absolute bottom-0 start-0 w-100 p-2 text-white"
                                    style="background: linear-gradient(to top, rgba(0,0,0,0.6), transparent);">
                                    <h6 class="fw-bold mb-0 text-white">{{ $area['name'] }}</h6>
                                    <span style="font-size: 11px; opacity: 0.9;">{{ $area['count'] }} restaurants</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- All Restaurants --}}
    <div class="py-5" style="background-color: #fffefc;">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h3 class="fw-bold m-0 h5 text-color: #0a2540">All Restaurants</h3>
                    <p class="text-muted small m-0">Browse our complete collection</p>
                </div>
                <a href="{{ route('restaurants.view') }}" class="text-decoration-none small fw-bold text-secondary custom-btn">View all →</a>
            </div>

            @php
                $restaurantImages = [
                    'Sushi Masaru' => 'https://images.unsplash.com/photo-1696449241254-11cf7f18ce32?w=600',
                    'Ramen Ichiban' => 'https://images.unsplash.com/photo-1681270496598-13c5365730c8?w=600',
                    'Yakitori Tori' => 'https://images.unsplash.com/photo-1601351841251-766245326eee?w=600',
                ];
                $allTags = ['Sushi Masaru', 'Ramen Ichiban', 'Yakitori Tori'];

                $displayTags = array_slice($allTags, 0, 3);
            @endphp

            <div class="row row-cols-1 row-cols-md-3 g-3 g-md-4">
                @foreach ($restaurantData as $res)
                    <div class="col">
                        <div
                            class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden d-flex justify-content-between flex-row flex-md-column bg-white card-effect">
                            <div class="col-4 col-md-12"
                                style="background: url('{{ $restaurantImages[$res['name']] ?? 'https://via.placeholder.com/300x200' }}') center/cover; min-height: 140px;">
                            </div>
                            <div class="card-body p-3 col-8 col-md-12">

                                <div class="d-none d-md-block">
                                    <div class="d-flex justify-content-between align-items-start w-100 mb-1"
                                        style="min-width: 0;">
                                        <div class="flex-grow-1 text-truncate me-2" style="min-width: 0;">
                                            <h6 class="fw-bold card-title m-0 text-truncate" style="color: #0a2540;">
                                                {{ $res['name'] }}
                                            </h6>
                                        </div>
                                        <div class="d-flex align-items-center flex-shrink-0 ms-auto pt-0.5"
                                            style="line-height: 1;">
                                            <span class="text-warning me-1"><i class="bi bi-star-fill"></i></span>
                                            <span class="fw-bold text-dark me-1"
                                                style="font-size: 13px;">{{ $res['rating'] }}</span>
                                            <span class="text-muted" style="font-size: 11px;">({{ $res['reviews'] }})</span>
                                        </div>
                                    </div>

                                    <p class="text-muted small mb-1" style="font-size: 12px;">{{ $res['type'] }}</p>
                                </div>


                                <div class="d-block d-md-none">
                                    <h6 class="fw-bold card-title mb-1 text-truncate" style="color: #0a2540;">
                                        {{ $res['name'] }}
                                    </h6>

                                    <p class="text-muted small mb-1" style="font-size: 12px;">
                                        {{ $res['type'] }}
                                    </p>

                                    <div class="mb-2" style="line-height: 1;">
                                        <span class="text-warning me-1"><i class="bi bi-star-fill"></i></span>
                                        <span class="fw-bold me-1" style="font-size: 13px;">{{ $res['rating'] }}</span>
                                        <span class="text-muted" style="font-size: 11px;">({{ $res['reviews'] }})</span>
                                    </div>
                                </div>


                                <div class="text-muted small mb-2 d-flex flex-wrap gap-1" style="font-size: 12px;">
                                    <i class="bi bi-geo-alt me-1 location-icon"></i>{{ $res['loc'] }}
                                </div>

                                <div class="d-flex flex-wrap gap-1">
                                    @foreach ($res['tags'] as $t)
                                        <span class="badge rounded-pill fw-normal px-2 py-1 text-muted"
                                            style="background-color: #e8ebf1; font-size: 10px;">{{ $t }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
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

        .custom-btn {
            transition: color 0.2s ease-in-out;
        }

        .custom-btn:hover {
            color: #0a2540 !important;
        }

        .location-icon {
            color: #fabede !important;
        }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=places">
        document.addEventListener('DOMContentLoaded', function() {
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(
                tooltipTriggerEl))
        });
    </script>
@endsection