@extends('layouts.restaurant')


@section('title', 'Reviews & Ratings')

@section('content')
    <style>
        body {
            background-color: #f8f9fa;
            color: #0f2d4a;
        }

        .review-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e9ecef;
        }

        .text-navy {
            color: #0f2d4a !important;
        }

        .star-rating {
            color: #ffc107;
        }

        .star-rating-muted {
            color: #dee2e6;
        }

        .progress-bar-yellow {
            background-color: #ffc107;
        }

        .review-img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
        }

        .btn-report {
            color: #6c757d;
            font-size: 0.85rem;
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-weight: 800;
            border: none;
            background: transparent;
        }

        .btn-report:hover:not(:disabled) {
            color: #dc3545;
            background-color: #fde8e8;
        }

        /* 🚨 通常の通報済み（赤）から、ご要望の「緑」のスタイルへ変更 */
        .btn-report:disabled,
        .btn-report.reported {
            color: #198754 !important;
            background-color: #e8f5e9 !important;
            cursor: not-allowed;
        }

        /* モーダルのボタン用カスタムスタイル */
        .btn-modal-cancel {
            background-color: #f1f3f5;
            color: #495057;
            border: none;
            font-weight: 600;
        }

        .btn-modal-report {
            background-color: #dc3545;
            color: #fff;
            border: none;
            font-weight: 600;
        }

        .btn-modal-report:hover {
            background-color: #bb2d3b;
            color: #fff;
        }

        .scrollbar-hidden::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hidden {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="container py-5" style="max-width: 1000px;">
        <h2 class="mb-4 fw-bold text-navy">Reviews & Ratings</h2>

        {{-- 📊 統計カードエリア --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="review-card p-4 text-center h-100 d-flex flex-column justify-content-center">
                    <h1 class="display-4 fw-bold text-navy mb-1">{{ number_format($stats['average_rating'] ?? 0.0, 1) }}</h1>
                    <div class="mb-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <i
                                class="bi bi-star-fill {{ $i <= round($stats['average_rating'] ?? 0) ? 'star-rating' : 'star-rating-muted' }} fs-5"></i>
                        @endfor
                    </div>
                    <small class="text-muted">{{ $reviews->count() }} reviews</small>
                </div>
            </div>

            <div class="col-md-4">
                <div class="review-card p-4 h-100 d-flex flex-column justify-content-center">
                    @foreach ($stats['stars'] ?? [] as $star => $data)
                        <div class="d-flex align-items-center mb-1">
                            <span class="small text-muted me-2" style="width: 15px;">{{ $star }}★</span>
                            <div class="progress flex-grow-1" style="height: 6px;">
                                <div class="progress-bar progress-bar-yellow" role="progressbar"
                                    style="width: {{ $data['percentage'] }}%;"></div>
                            </div>
                            <span class="small text-muted ms-2 text-end" style="width: 30px;">{{ $data['count'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-md-4">
                <div class="review-card p-4 text-center h-100 d-flex flex-column justify-content-center">
                    <h1 class="display-4 fw-bold text-danger mb-1" id="reported-count-display">
                        {{ $stats['reported_count'] ?? 0 }}</h1>
                    <div class="fw-semibold text-muted mb-1">Reported Reviews</div>
                    <small class="text-muted">Reported reviews are sent to admin for removal</small>
                </div>
            </div>
        </div>

        {{-- 💬 口コミ一覧リストエリア --}}
        <div class="d-flex flex-column gap-3">
            @forelse ($reviews as $review)
                <div class="review-card p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h5 class="fw-bold text-navy mb-1">{{ $review->user->username ?? $review->user->last_name }}
                            </h5>
                            <div class="d-flex align-items-center gap-2">
                                <div class="star-rating small">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i
                                            class="bi bi-star-fill {{ $i <= $review->rating ? 'star-rating' : 'star-rating-muted' }}"></i>
                                    @endfor
                                </div>
                                <small
                                    class="text-muted">{{ \Carbon\Carbon::parse($review->created_at)->format('Y-m-d') }}</small>
                            </div>
                        </div>

                        <button type="button"
                            class="btn-report d-flex align-items-center gap-1 {{ $review->is_reported ? 'reported' : '' }}"
                            data-report-url="{{ route('restaurant.posts.report', $review->id) }}"
                            onclick="openReportModal(this, {{ $review->id }})"
                            {{ $review->is_reported ? 'disabled' : '' }}>
                            <i class="bi {{ $review->is_reported ? 'bi-flag-fill' : 'bi-flag' }}"></i>
                            <span class="report-text">{{ $review->is_reported ? 'Reported' : 'Report' }}</span>
                        </button>
                    </div>

                    <p class="text-navy mb-3" style="line-height: 1.6;">
                        {{ $review->description }}
                    </p>

                    {{-- 🔄 複数画像・動画対応の表示ロジック --}}
                    @if ($review->image)
                        @php
                            $mediaItems = array_filter(array_map('trim', explode(',', $review->image)));
                            $sharedMobileModalId = 'mobileModal-' . $review->id;
                        @endphp

                        {{-- 📱 モバイル版: 横スクロール表示 --}}
                        <div class="d-flex d-md-none gap-2 overflow-x-auto pb-2 scrollbar-hidden"
                            style="white-space: nowrap; -webkit-overflow-scrolling: touch;">
                            @foreach ($mediaItems as $index => $filePath)
                                @php
                                    $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                                    $isVideo = in_array(strtolower($extension), ['mp4', 'mov', 'ogg', 'qt']);
                                @endphp

                                {{-- タップ用のサムネイル一覧 --}}
                                <div class="d-inline-block flex-shrink-0" style="width: 140px; height: 140px;">
                                    @if ($isVideo)
                                        <video src="{{ asset($filePath) }}" class="review-img w-100 h-100 rounded"
                                            style="cursor: pointer; object-fit: cover;" data-bs-toggle="modal"
                                            data-bs-target="#{{ $sharedMobileModalId }}"
                                            data-bs-slide-to="{{ $index }}"></video>
                                    @else
                                        <img src="{{ asset($filePath) }}" alt="Review Image"
                                            class="review-img w-100 h-100 rounded"
                                            style="cursor: pointer; object-fit: cover;" data-bs-toggle="modal"
                                            data-bs-target="#{{ $sharedMobileModalId }}"
                                            data-bs-slide-to="{{ $index }}">
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- 📱 モバイル用共通カルーセルモーダル --}}
                        <div class="modal fade d-md-none" id="{{ $sharedMobileModalId }}" tabindex="-1"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content bg-transparent border-0">
                                    <div class="modal-body p-0 position-relative">
                                        {{-- 閉じるボタン --}}
                                        <button type="button"
                                            class="btn-close btn-close-white position-absolute top-0 end-0 m-3 z-3"
                                            data-bs-dismiss="modal" aria-label="Close"></button>

                                        {{-- カルーセル本体 --}}
                                        <div id="carouselMobile-{{ $review->id }}" class="carousel slide"
                                            data-bs-ride="false" data-bs-touch="true">

                                            {{-- カルーセルの中身 --}}
                                            <div class="carousel-inner bg-dark rounded p-2">
                                                @foreach ($mediaItems as $index => $filePath)
                                                    @php
                                                        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                                                        $isVideo = in_array(strtolower($extension), [
                                                            'mp4',
                                                            'mov',
                                                            'ogg',
                                                            'qt',
                                                        ]);
                                                    @endphp
                                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                        <div class="d-flex justify-content-center align-items-center"
                                                            style="max-height: 80vh;">
                                                            @if ($isVideo)
                                                                <video src="{{ asset($filePath) }}"
                                                                    class="img-fluid rounded" controls
                                                                    style="max-height: 80vh; object-fit: contain;"></video>
                                                            @else
                                                                <img src="{{ asset($filePath) }}" class="img-fluid rounded"
                                                                    alt="Enlarged Review Media"
                                                                    style="max-height: 80vh; object-fit: contain;">
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            {{-- 複数メディアがある場合のみナビゲーションを表示 --}}
                                            @if (count($mediaItems) > 1)
                                                <button class="carousel-control-prev" type="button"
                                                    data-bs-target="#carouselMobile-{{ $review->id }}"
                                                    data-bs-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Previous</span>
                                                </button>
                                                <button class="carousel-control-next" type="button"
                                                    data-bs-target="#carouselMobile-{{ $review->id }}"
                                                    data-bs-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Next</span>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 💻 PC版: 複数枚画像をきれいに並列表示 (Grid) --}}
                        <div class="d-none d-md-flex flex-wrap gap-2 mt-2 mb-3">
                            @foreach ($mediaItems as $index => $filePath)
                                @php
                                    $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                                    $isVideo = in_array(strtolower($extension), ['mp4', 'mov', 'ogg', 'qt']);
                                @endphp

                                <div class="position-relative border rounded overflow-hidden shadow-sm"
                                    style="width: 120px; height: 120px; cursor: pointer;">
                                    @if ($isVideo)
                                        <video src="{{ asset($filePath) }}" class="w-100 h-100 object-fit-cover"
                                            data-bs-toggle="modal" data-bs-target="#pcModal-{{ $review->id }}"
                                            data-bs-slide-to="{{ $index }}"></video>
                                        <div class="position-absolute top-50 start-50 translate-middle text-white bg-dark bg-opacity-50 rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 30px; height: 30px; pointer-events: none;">
                                            <i class="bi bi-play-fill fs-5"></i>
                                        </div>
                                    @else
                                        <img src="{{ asset($filePath) }}" class="w-100 h-100 object-fit-cover"
                                            alt="Thumbnail" data-bs-toggle="modal"
                                            data-bs-target="#pcModal-{{ $review->id }}"
                                            data-bs-slide-to="{{ $index }}">
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- 💻 PC版: 1つのスライダー(モーダル)で複数枚をまとめて見る構造 --}}
                        <div class="modal fade" id="pcModal-{{ $review->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content bg-transparent border-0 text-end">
                                    <button type="button" class="btn-close btn-close-white ms-auto mb-2"
                                        data-bs-dismiss="modal" aria-label="Close"></button>

                                    <div class="modal-body p-0 position-relative">
                                        <div id="carousel-review-{{ $review->id }}" class="carousel slide"
                                            data-bs-ride="false">
                                            <div class="carousel-inner bg-dark rounded p-2">
                                                @foreach ($mediaItems as $index => $filePath)
                                                    @php
                                                        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                                                        $isVideo = in_array(strtolower($extension), [
                                                            'mp4',
                                                            'mov',
                                                            'ogg',
                                                            'qt',
                                                        ]);
                                                    @endphp
                                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                        <div class="d-flex align-items-center justify-content-center"
                                                            style="height: 70vh;">
                                                            @if ($isVideo)
                                                                <video src="{{ asset($filePath) }}"
                                                                    class="img-fluid rounded" controls
                                                                    style="max-height: 70vh; object-fit: contain;"></video>
                                                            @else
                                                                <img src="{{ asset($filePath) }}"
                                                                    class="img-fluid rounded" alt="Enlarged Review Media"
                                                                    style="max-height: 70vh; object-fit: contain;">
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            @if (count($mediaItems) > 1)
                                                <button class="carousel-control-prev" type="button"
                                                    data-bs-target="#carousel-review-{{ $review->id }}"
                                                    data-bs-slide="prev">
                                                    <span class="carousel-control-prev-icon bg-dark rounded-circle p-2"
                                                        aria-hidden="true"></span>
                                                    <span class="visually-hidden">Previous</span>
                                                </button>
                                                <button class="carousel-control-next" type="button"
                                                    data-bs-target="#carousel-review-{{ $review->id }}"
                                                    data-bs-slide="next">
                                                    <span class="carousel-control-next-icon bg-dark rounded-circle p-2"
                                                        aria-hidden="true"></span>
                                                    <span class="visually-hidden">Next</span>
                                                </button>

                                                <div class="carousel-indicators" style="bottom: -25px;">
                                                    @foreach ($mediaItems as $index => $filePath)
                                                        <button type="button"
                                                            data-bs-target="#carousel-review-{{ $review->id }}"
                                                            data-bs-slide-to="{{ $index }}"
                                                            class="{{ $index === 0 ? 'active' : '' }} bg-white"
                                                            aria-current="{{ $index === 0 ? 'true' : 'false' }}"></button>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="review-card p-5 text-center text-muted">
                    No reviews yet for this restaurant.
                </div>
            @endforelse
        </div>
    </div>

    {{-- 🔒 共通の「通報確認用」Bootstrapモーダル構造 --}}
    <div class="modal fade" id="reportConfirmModal" tabindex="-1" aria-labelledby="reportConfirmModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
            <div class="modal-content border-0 shadow" style="border-radius: 16px;">
                <div class="modal-body text-center p-4">
                    <div class="text-danger mb-3">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-bold mb-3 text-navy" id="reportConfirmModalLabel">Confirm Report</h5>
                    <p class="text-muted mb-4" style="font-size: 0.95rem;">
                        Are you sure you want to report this review to admin?
                    </p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-modal-cancel px-4 py-2 flex-grow-1 rounded-3"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-modal-report px-4 py-2 flex-grow-1 rounded-3"
                            id="executeReportBtn">Report</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let targetButton = null;
        let targetReviewId = null;
        let targetReportUrl = null;
        let reportModalInstance = null;

        // 通報用カスタムモーダルを開く関数
        function openReportModal(button, reviewId) {
            targetButton = button;
            targetReviewId = reviewId;
            targetReportUrl = button.getAttribute('data-report-url');

            // Bootstrapのモーダルを初期化して表示
            const modalEl = document.getElementById('reportConfirmModal');
            reportModalInstance = new bootstrap.Modal(modalEl);
            reportModalInstance.show();
        }

        // モーダル内の「Report」ボタンをクリックしたときの処理
        document.getElementById('executeReportBtn').addEventListener('click', function() {
            if (!targetReviewId || !targetButton) return;

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // 1. まず先にモーダルを閉じて画面に戻す
            if (reportModalInstance) {
                reportModalInstance.hide();
            }

            // 2. バックエンドへ非同期リクエストを送信
            fetch(targetReportUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // 3. 【即時変更】「Reported(緑)」に見た目のクラスとテキストを切り替える
                        targetButton.disabled = true;
                        targetButton.classList.add('reported');
                        targetButton.querySelector('.report-text').innerText = 'Reported';

                        const icon = targetButton.querySelector('i');
                        icon.classList.remove('bi-flag');
                        icon.classList.add('bi-flag-fill');

                        // 4. 上部の統計情報(Reported Reviews)の数値を+1加算
                        const countDisplay = document.getElementById('reported-count-display');
                        if (countDisplay) {
                            let currentCount = parseInt(countDisplay.innerText) || 0;
                            countDisplay.innerText = currentCount + 1;
                        }
                    } else {
                        alert('Failed to report: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while reporting.');
                })
                .finally(() => {
                    // 変数の初期化
                    targetButton = null;
                    targetReviewId = null;
                    targetReportUrl = null;
                });
        });
        document.addEventListener('DOMContentLoaded', function() {
            // [data-bs-slide-to]を持つサムネイル要素のクリックを監視
            const thumbnails = document.querySelectorAll('[data-bs-slide-to]');

            thumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('click', function() {
                    const targetModalId = this.getAttribute('data-bs-target');
                    const slideIndex = parseInt(this.getAttribute('data-bs-slide-to'), 10);

                    // モーダル内のカルーセル要素を探す
                    const carouselElement = document.querySelector(`${targetModalId} .carousel`);
                    if (carouselElement) {
                        // カルーセルインスタンスを取得し、クリックされた位置へスライドさせる
                        const carousel = bootstrap.Carousel.getOrCreateInstance(carouselElement);
                        carousel.to(slideIndex);
                    }
                });
            });
        });
    </script>
@endsection
