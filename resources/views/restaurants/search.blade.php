@extends('layouts.app')

@section('title', 'Search Results')

@section('content')
<div class="container my-5">
    <!-- 検索キーワードの表示 -->
    <div class="mb-4">
        <h2 class="fw-bold text-navy">
            @if(!empty($keyword))
                Search Results for "{{ $keyword }}"
            @else
                All Restaurants
            @endif
        </h2>
        <p class="text-secondary">{{ $restaurants->total() }} restaurants found.</p>
    </div>

    <!-- 検索結果一覧 -->
    <div class="row">
        @forelse($restaurants as $restaurant)
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm border-0 rounded-3 overflow-hidden">
                    <!-- 店舗画像（ダミー画像） -->
                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="bi bi-shop fs-1"></i>
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-light text-navy border mb-2 align-self-start">
                            {{ $restaurant->prefecture }} {{ $restaurant->city }}
                        </span>
                        <h5 class="card-title fw-bold text-navy mb-2">
                            {{ $restaurant->restaurant_name }}
                        </h5>
                        <p class="card-text text-secondary small flex-grow-1">
                            {{ Str::limit($restaurant->description, 80, '...') }}
                        </p>
                        
                        @if(Route::has('restaurants.show'))
                            <a href="{{ route('restaurants.show', $restaurant->id) }}" class="btn btn-navy w-100 mt-3 fw-bold custom-search-btn py-2" style="border-radius: 8px;">
                                View Details
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <!-- 結果がゼロだった場合 -->
            <div class="col-12 text-center py-5">
                <i class="bi bi-search text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3 fs-5">No restaurants matched your search.</p>
            </div>
        @endforelse
    </div>

    <!-- ページネーションリンク -->
    <div class="d-flex justify-content-center mt-5">
        {{ $restaurants->appends(['keyword' => $keyword])->links() }}
    </div>
</div>
@endsection