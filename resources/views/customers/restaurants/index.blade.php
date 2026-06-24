@extends('layouts.app')

@section('title', 'Restaurants')

@section('content')

@php
// $restaurants = [
//     [
//         'image' => 'https://picsum.photos/400/250?random=1',
//         'name' => 'Restaurant 1',
//         'category' => 'Sushi',
//         'area' => 'Ginza, Tokyo',
//         'rating' => 4.5,
//         'available_times' => ['18:00', '18:30', '19:00', '20:15', '20:15', '20:15'],
//         'features' => ['English Menu', 'Table', 'Credit Cards'],
//         'review_count' => 128
//     ],
//     [
//         'image' => 'https://picsum.photos/400/250?random=2',
//         'name' => 'Restaurant 2',
//         'category' => 'Ramen',
//         'area' => 'Shibuya, Tokyo',
//         'rating' => 4.8,
//         'available_times' => ['18:00', '18:30', '19:00', '20:15', '20:15', '20:15'],
//         'features' => ['English Menu', 'Table', 'Credit Cards'],
//         'review_count' => 128
//     ],
//     [
//         'image' => 'https://picsum.photos/400/250?random=3',
//         'name' => 'Restaurant 3',
//         'category' => 'Yakitori',
//         'area' => 'Shinjuku, Tokyo',
//         'rating' => 4.7,
//         'available_times' => ['18:00', '18:30', '19:00', '20:15', '20:15', '20:15'],
//         'features' => ['English Menu', 'Table', 'Credit Cards'],
//         'review_count' => 128
//     ],
//     [
//         'image' => 'https://picsum.photos/400/250?random=4',
//         'name' => 'Restaurant 4',
//         'category' => 'Kaiseki',
//         'area' => 'Roppongi, Tokyo',
//         'rating' => 4.9,
//         'available_times' => ['18:00', '18:30', '19:00', '20:15', '20:15', '20:15'],
//         'features' => ['English Menu', 'Table', 'Credit Cards'],
//         'review_count' => 128
//     ],
//     [
//         'image' => 'https://picsum.photos/400/250?random=4',
//         'name' => 'Restaurant 4',
//         'category' => 'Kaiseki',
//         'area' => 'Roppongi, Tokyo',
//         'rating' => 4.9,
//         'available_times' => ['18:00', '18:30', '19:00', '20:15', '20:15', '20:15'],
//         'features' => ['English Menu', 'Table', 'Credit Cards'],
//         'review_count' => 128
//     ],
//     [
//         'image' => 'https://picsum.photos/400/250?random=4',
//         'name' => 'Restaurant 4',
//         'category' => 'Kaiseki',
//         'area' => 'Roppongi, Tokyo',
//         'rating' => 4.9,
//         'available_times' => ['18:00', '18:30', '19:00', '20:15', '20:15', '20:15'],
//         'features' => ['English Menu', 'Table', 'Credit Cards'],
//         'review_count' => 128
//     ],
//     [
//         'image' => 'https://picsum.photos/400/250?random=4',
//         'name' => 'Restaurant 4',
//         'category' => 'Kaiseki',
//         'area' => 'Roppongi, Tokyo',
//         'rating' => 4.9,
//         'available_times' => ['18:00', '18:30', '19:00', '20:15', '20:15', '20:15'],
//         'features' => ['English Menu', 'Table', 'Credit Cards'],
//         'review_count' => 128
//     ],
//     [
//         'image' => 'https://picsum.photos/400/250?random=4',
//         'name' => 'Restaurant 4',
//         'category' => 'Kaiseki',
//         'area' => 'Roppongi, Tokyo',
//         'rating' => 4.9,
//         'available_times' => ['18:00', '18:30', '19:00', '20:15', '20:15', '20:15'],
//         'features' => ['English Menu', 'Table', 'Credit Cards'],
//         'review_count' => 128
//     ],
//     [
//         'image' => 'https://picsum.photos/400/250?random=4',
//         'name' => 'Restaurant 4',
//         'category' => 'Kaiseki',
//         'area' => 'Roppongi, Tokyo',
//         'rating' => 4.9,
//         'available_times' => ['18:00', '18:30', '19:00', '20:15', '20:15', '20:15'],
//         'features' => ['English Menu', 'Table', 'Credit Cards'],
//         'review_count' => 128
//     ],
//     [
//         'image' => 'https://picsum.photos/400/250?random=4',
//         'name' => 'Restaurant 4',
//         'category' => 'Kaiseki',
//         'area' => 'Roppongi, Tokyo',
//         'rating' => 4.9,
//         'available_times' => ['18:00', '18:30', '19:00', '20:15', '20:15', '20:15'],
//         'features' => ['English Menu', 'Table', 'Credit Cards'],
//         'review_count' => 128
//     ],
//     [
//         'image' => 'https://picsum.photos/400/250?random=4',
//         'name' => 'Restaurant 4',
//         'category' => 'Kaiseki',
//         'area' => 'Roppongi, Tokyo',
//         'rating' => 4.9,
//         'available_times' => ['18:00', '18:30', '19:00', '20:15', '20:15', '20:15'],
//         'features' => ['English Menu', 'Table', 'Credit Cards'],
//         'review_count' => 128
//     ],
//     [
//         'image' => 'https://picsum.photos/400/250?random=4',
//         'name' => 'Restaurant 4',
//         'category' => 'Kaiseki',
//         'area' => 'Roppongi, Tokyo',
//         'rating' => 4.9,
//         'available_times' => ['18:00', '18:30', '19:00', '20:15', '20:15', '20:15'],
//         'features' => ['English Menu', 'Table', 'Credit Cards'],
//         'review_count' => 128
//     ],
//     [
//         'image' => 'https://picsum.photos/400/250?random=4',
//         'name' => 'Restaurant 4',
//         'category' => 'Kaiseki',
//         'area' => 'Roppongi, Tokyo',
//         'rating' => 4.9,
//         'available_times' => ['18:00', '18:30', '19:00', '20:15', '20:15', '20:15'],
//         'features' => ['English Menu', 'Table', 'Credit Cards'],
//         'review_count' => 128
//     ],
//     [
//         'image' => 'https://picsum.photos/400/250?random=4',
//         'name' => 'Restaurant 4',
//         'category' => 'Kaiseki',
//         'area' => 'Roppongi, Tokyo',
//         'rating' => 4.9,
//         'available_times' => ['18:00', '18:30', '19:00', '20:15', '20:15', '20:15'],
//         'features' => ['English Menu', 'Table', 'Credit Cards'],
//         'review_count' => 128
//     ],
//     [
//         'image' => 'https://picsum.photos/400/250?random=4',
//         'name' => 'Restaurant 4',
//         'category' => 'Kaiseki',
//         'area' => 'Roppongi, Tokyo',
//         'rating' => 4.9,
//         'available_times' => ['18:00', '18:30', '19:00', '20:15', '20:15', '20:15'],
//         'features' => ['English Menu', 'Table', 'Credit Cards'],
//         'review_count' => 128
//     ],
//     [
//         'image' => 'https://picsum.photos/400/250?random=4',
//         'name' => 'Restaurant 4',
//         'category' => 'Kaiseki',
//         'area' => 'Roppongi, Tokyo',
//         'rating' => 4.9,
//         'available_times' => ['18:00', '18:30', '19:00', '20:15', '20:15', '20:15'],
//         'features' => ['English Menu', 'Table', 'Credit Cards'],
//         'review_count' => 128
//     ],
//     [
//         'image' => 'https://picsum.photos/400/250?random=4',
//         'name' => 'Restaurant 4',
//         'category' => 'Kaiseki',
//         'area' => 'Roppongi, Tokyo',
//         'rating' => 4.9,
//         'available_times' => ['18:00', '18:30', '19:00', '20:15', '20:15', '20:15'],
//         'features' => ['English Menu', 'Table', 'Credit Cards'],
//         'review_count' => 128
//     ],
// ];
@endphp

<div class="container bg-light"> 
    {{-- Desktop --}}
    <div class="mb-3 filter-bar d-none d-md-block bg-white">

        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="h4 fw-bold" style="color: #0a2540;">
                125 Restaurants Found
            </h5>

            <button class="btn filter-btn ms-3 flex-shrink-0"
                    data-bs-toggle="modal"
                    data-bs-target="#filterModal">
                    <i class="fa-solid fa-sliders"></i> Filters
            </button>
        </div>

        {{-- Active Filters Display --}}
        <div id="activeFilters" class="d-flex gap-2 flex-wrap justify-content-end">

            @foreach(request('cuisines', []) as $cuisine)
                <span class="badge bg-light border text-secondary fs-6 px-2 pt-2" style="height: 2rem">
                    {{ $cuisine }}
                </span>
            @endforeach

            @foreach(request('features', []) as $feature)
                <span class="badge bg-light border text-secondary fs-6 px-2 pt-2" style="height: 2rem">
                    {{ $feature }}
                </span>
            @endforeach

            @if(request('rating'))
                <span class="badge bg-light border text-secondary fs-6 px-2 pt-2" style="height: 2rem">
                    At least {{ request('rating') }} stars
                </span>
            @endif
            
            @if(request('distance'))
                <span class="badge bg-light border text-secondary fs-6 px-2 pt-2" style="height: 2rem">
                    {{ request('distance') }}
                </span>
            @endif

        </div>
    </div>

    {{-- Mobile --}}
    <div class="d-flex justify-content-between align-items-center mb-3 d-md-none">
        <div>
            <h5 class="mb-0">125 Restaurants Found</h5>
        </div>

        <button
            class="btn filter-btn rounded-pill"
            data-bs-toggle="modal"
            data-bs-target="#filterModal">
            <i class="bi bi-sliders"></i>
        </button>
    </div>


    {{-- Restaurants cards --}}
    <div class="row g-4">
        @foreach ($restaurants as $restaurant)
            <div class="col-12 col-md-6 col-lg-4">
                @include('customers.restaurants.partials.card', [
                    'restaurant' => $restaurant
                ])
            </div>
        @endforeach
    </div>
</div>


<style>
    .filter-bar{
        position: sticky;
        top: 50px;
        z-index: 100;
        padding: 12px 0;
        --bs-bg-opacity: .9;
        border-radius: 10px;
    }

    .filter-btn{
        background-color: transparent;
        color: #0a2540; /* text color */
        border: 2px solid #0a2540;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
    }

    .filter-btn:hover{
        background-color: #0a2540;
        color: #fff;
    }

</style>

@endsection

{{-- include  the modal here --}}
@include('customers.restaurants.partials.modals.filter')
               