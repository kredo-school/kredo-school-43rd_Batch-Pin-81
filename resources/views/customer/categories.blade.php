@extends('layouts.app')

@section('title', 'All Categories')

@section('content')
<div class="w-100 py-5" style="background-color: #fffefc; min-height: calc(100vh - 70px);">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h3 class="fw-bold m-0 h5" style="color: #0a2540;">Food Categories</h3>
                <p class="text-muted small m-0">Explore your favorite dishes in Tokyo</p>
            </div>
            <a href="{{ route('customer.search') }}" class="text-decoration-none small fw-bold text-secondary custom-btn">← Back to Search</a>
        </div>

        @php
            $categoryImages = [
                'Sushi' => 'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?w=400',
                'Ramen' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=400',
                'Yakitori' => 'https://x.gd/OdlVj',
                'Kaiseki' => 'https://x.gd/EXsEH',
                'Japanese BBQ' => 'https://x.gd/OdlVj',
                'Cafe' => 'https://x.gd/ePNZx',
                'Vegetarian Friendly' => 'https://x.gd/EXsEH',
                'Tempura' => 'https://x.gd/ePNZx',
                'Udon' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=400',
                'Vegan' => 'https://x.gd/EXsEH',
                'Izakaya' => 'https://x.gd/OdlVj',
                'Curry' => 'https://x.gd/EXsEH',
                'Italian' => 'https://x.gd/ePNZx',
                'Thai' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=400',
                'French' => 'https://x.gd/ePNZx',
                'Bakery' => 'https://x.gd/EXsEH',
            ];
            $defaultCatImage = 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=400';
        @endphp

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-3">
            @foreach ($categories as $cat)
                <div class="col">
                    <a href="{{ route('customer.categories.show', $cat['name']) }}" class="text-decoration-none d-block h-100">
                        <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden bg-white card-effect">
                            <div class="position-relative">
                                <div style="height: 140px; background: url('{{ $categoryImages[$cat['name']] ?? 'https://via.placeholder.com/300x140' }}') center/cover;"></div>
                                <div class="position-absolute bottom-0 start-0 w-100 p-2 text-white" style="background: linear-gradient(to top, rgba(0,0,0,0.6), transparent);">
                                    <h6 class="fw-bold mb-0 text-white">{{ $cat['name'] }}</h6>
                                    <span style="font-size: 11px; opacity: 0.9;">View restaurants</span>
                                </div>
                            </div>
                        </div>
                    </a>
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
</style>
@endsection