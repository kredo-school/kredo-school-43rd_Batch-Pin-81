@extends('layouts.app')

@section('title', 'All Areas')

@section('content')
    <div class="w-100 py-5" style="background-color: #fffefc; min-height: calc(100vh - 70px);">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h3 class="fw-bold m-0 h5" style="color: #0a2540;">Popular Areas</h3>
                <p class="text-muted small m-0">Dining hotspots in Tokyo</p>
            </div>
            <a href="{{ route('customer.search') }}"
                class="text-decoration-none small fw-bold text-secondary custom-btn">← Back to Search</a>
        </div>

        @php
            $areaImages = [
                'Ginza' => 'https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?w=500',
                'Shibuya' => 'https://images.unsplash.com/photo-1542051841857-5f90071e7989?w=500',
                'Shinjuku' => 'https://images.unsplash.com/photo-1503899036084-c55cdd92da26?w=500',
                'Roppongi' => 'https://images.unsplash.com/photo-1524413840807-0c3cb6fa808d?w=500',
                'Asakusa' => 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?w=500',
            ];
        @endphp

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-3">
            @foreach ($areas as $ar)
                @php
                    $matchedImage = null;
                    foreach ($areaImages as $key => $url) {
                        if (stripos($ar['name'], $key) !== false) {
                            $matchedImage = $url;
                            break;
                        }
                    }
                @endphp

                <div class="col">
                    <!-- HTML構造のねじれ（</a>の閉じ位置）を正しい位置へ修正 -->
                    <a href="{{ route('customer.areas.show', $ar['name']) }}" class="text-decoration-none d-block h-100">
                        <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden bg-white card-effect position-relative">
                            
                            <div class="position-relative">
                                <!-- 【大修正】エラーの元だった $restaurant を廃止し、マッチしたUnsplash画像、またはエリアデータ内の画像を参照 -->
                                @if ($matchedImage)
                                    <img src="{{ $matchedImage }}" class="w-100 object-cover" style="height: 240px; object-fit: cover;">
                                @elseif (!empty($ar['photos']) && method_exists($ar['photos'], 'isNotEmpty') ? $ar['photos']->isNotEmpty() : !empty($ar['photos']))
                                    <!-- エリア自体の写真データがある場合のバックアップ（変数名を $ar に修正） -->
                                    <img src="{{ asset('storage/' . (is_array($ar['photos']) ? $ar['photos'][0]['path'] : $ar['photos']->first()->path)) }}"
                                        class="w-100 object-cover" style="height: 240px; object-fit: cover;">
                                @else
                                    <!-- 画像が何も無い場合のデフォルトプレースホルダー -->
                                    <div class="d-flex flex-column align-items-center justify-content-center bg-light text-secondary" style="height: 240px;">
                                        <i class="fa-solid fa-image fa-3x mb-2" style="opacity: 0.4;"></i>
                                        <span class="text-muted small">No image available</span>
                                    </div>
                                @endif
                            </div>

                            <!-- グラデーション文字エリアが、正しくカードの最前面かつ最下部に乗るように配置 -->
                            <div class="position-absolute bottom-0 start-0 w-100 p-3 text-white"
                                style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); z-index: 2;">
                                <h6 class="fw-bold mb-0 text-white">{{ $ar['name'] }}</h6>
                                <span class="text-white-50" style="font-size: 11px;">View restaurants</span>
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

        .location-icon {
            color: #fabede !important;
        }
    </style>
@endsection
