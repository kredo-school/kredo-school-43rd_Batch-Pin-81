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

    <!-- 検索結果一覧（共通カード部品を使用） -->
    <div class="row g-4">
        @forelse($restaurants as $restaurant)
            {{-- index.blade.php と全く同じように、カードを丸ごとクリック可能にし、共通部品を読み込みます --}}
            <div class="col-12 col-md-6 col-lg-4" style="cursor: pointer;"
                onclick="window.location.href='/restaurant/{{ $restaurant->id }}'">

                @include('customers.restaurants.partials.card', ['restaurant' => $restaurant])

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