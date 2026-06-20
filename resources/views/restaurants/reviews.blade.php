@extends('layouts.app')

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

        /* 進行状況バーのカスタムカラー（黄色） */
        .progress-bar-yellow {
            background-color: #ffc107;
        }

        /* レビュー添付画像のスタイル */
        .review-img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
        }

        /* 画像なしプレースホルダーのスタイル */
        .review-img-placeholder {
            width: 70px;
            height: 70px;
            background-color: #f1f3f5;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #adb5bd;
        }

        .btn-report {
            color: #6c757d;
            font-size: 0.85rem;
            text-decoration: none;
        }

        .btn-report:hover {
            color: #dc3545;
        }
    </style>

    <div class="container py-5" style="max-width: 1000px;">
        <h2 class="mb-4 fw-bold text-navy">Reviews & Ratings</h2>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="review-card p-4 text-center h-100 d-flex flex-column justify-content-center">
                    <h1 class="display-4 fw-bold text-navy mb-1">{{ number_format($stats['average_rating'], 1) }}</h1>
                    <div class="mb-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <i
                                class="bi bi-star-fill {{ $i <= round($stats['average_rating']) ? 'star-rating' : 'star-rating-muted' }} fs-5"></i>
                        @endfor
                    </div>
                    <small class="text-muted">{{ $stats['total_reviews'] }} reviews</small>
                </div>
            </div>

            <div class="col-md-4">
                <div class="review-card p-4 h-100 d-flex flex-column justify-content-center">
                    @foreach ($stats['stars'] as $star => $data)
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
                    <h1 class="display-4 fw-bold text-danger mb-1">{{ $stats['reported_count'] }}</h1>
                    <div class="fw-semibold text-muted mb-1">Reported Reviews</div>
                    <small class="text-muted">Reported reviews are sent to admin for removal</small>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column gap-3">
            @foreach ($reviews as $review)
                <div class="review-card p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h5 class="fw-bold text-navy mb-1">USER</h5>
                            <div class="d-flex align-items-center gap-2">
                                <div class="star-rating small">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i
                                            class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'star-rating-muted bi-star-fill' }}"></i>
                                    @endfor
                                </div>
                                <small class="text-muted">2026/06/19</small>
                            </div>
                        </div>
                        <a href="#" class="btn-report d-flex align-items-center gap-1">
                            <i class="bi bi-flag"></i> Report
                        </a>
                    </div>

                    <p class="text-navy mb-3" style="line-height: 1.6;">
                        {{ $review->comment }}
                    </p>

                    <div class="d-flex gap-2 mb-2">
    <img src="https://images.unsplash.com/photo-1579584425555-c3ce17fd4351?w=400" alt="Review Image 1"
        class="review-img"
        style="cursor: pointer;"
        data-bs-toggle="modal"
        data-bs-target="#imageModal-{{ $loop->index }}"
        data-bs-slide-to="0">
    <img src="https://images.unsplash.com/photo-1583623025817-d180a2221d0a?w=400" alt="Review Image 2"
        class="review-img"
        style="cursor: pointer;"
        data-bs-toggle="modal"
        data-bs-target="#imageModal-{{ $loop->index }}"
        data-bs-slide-to="1">
</div>

                    @if (!empty($review->images))
                        <div class="d-flex gap-2">
                            @foreach ($review->images as $index => $imgUrl)
                                <img src="{{ $imgUrl }}" alt="Review Image" class="review-img"
                                    style="cursor: pointer;" data-bs-toggle="modal"
                                    data-bs-target="#imageModal-{{ $loop->parent->index }}"
                                    data-bs-slide-to="{{ $index }}">
                            @endforeach
                        </div>
                    @else
                        <div class="review-img-placeholder">
                            <i class="bi bi-image fs-4"></i>
                        </div>
                    @endif

                    @if (!empty($review->images))
                        <div class="modal fade" id="imageModal-{{ $loop->index }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content bg-transparent border-0">

                                    <div class="modal-body p-0 position-relative">
                                        <button type="button"
                                            class="btn-close btn-close-white position-absolute top-0 end-0 m-3 z-3"
                                            data-bs-dismiss="modal" aria-label="Close"></button>

                                        <div id="carousel-{{ $loop->index }}" class="carousel slide" data-bs-ride="false">

                                            <div class="carousel-inner text-center">
                                                @foreach ($review->images as $index => $imgUrl)
                                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                        <img src="{{ $imgUrl }}" class="img-fluid rounded"
                                                            alt="Enlarged Review Image"
                                                            style="max-height: 80vh; object-fit: contain;">
                                                    </div>
                                                @endforeach
                                            </div>

                                            @if (count($review->images) > 1)
                                                <button class="carousel-control-prev" type="button"
                                                    data-bs-target="#carousel-{{ $loop->index }}" data-bs-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Previous</span>
                                                </button>
                                                <button class="carousel-control-next" type="button"
                                                    data-bs-target="#carousel-{{ $loop->index }}" data-bs-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Next</span>
                                                </button>
                                            @endif

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endsection