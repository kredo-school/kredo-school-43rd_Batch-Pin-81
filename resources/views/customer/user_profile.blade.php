@extends('layouts.app')

@section('title', 'User Reviews')

@section('content')
    <div class="container py-4 py-md-5" style="max-width: 960px;">
        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
            <div class="card-body p-4 p-md-5 d-flex flex-column flex-md-row align-items-md-center gap-4">
                <div class="flex-shrink-0 text-center text-md-start">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->username ?? $user->display_id }}"
                        class="rounded-circle shadow-sm" style="width: 104px; height: 104px; object-fit: cover;">
                </div>

                <div class="flex-grow-1">
                    <p class="text-uppercase text-muted small mb-1">Reviewer profile</p>
                    <h1 class="fw-bold text-navy mb-2">{{ $user->username ?? $user->display_id }}</h1>
                    <p class="text-muted mb-3">{{ $user->full_name ?: 'Restaurant customer' }}</p>

                    <div class="d-flex gap-4 flex-wrap">
                        <div>
                            <div class="fw-bold text-navy fs-5">{{ $reviews->count() }}</div>
                            <div class="text-muted small">Reviews</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="h5 fw-bold text-navy mb-0">Recent Reviews</h2>
        </div>

        <div class="d-flex flex-column gap-3">
            @forelse ($reviews as $review)
                @php
                    $images = !empty($review->image) ? explode(',', $review->image) : [];
                    $firstImage = !empty($images) ? $images[0] : null;
                @endphp
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                            <div>
                                <a href="{{ route('restaurant.show', $review->restaurant) }}"
                                    class="fw-bold text-navy text-decoration-none d-inline-block mb-1">
                                    {{ $review->restaurant->restaurant_name ?? 'Restaurant' }}
                                </a>
                                <div class="text-warning small">
                                    {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                                </div>
                            </div>
                            <div class="text-muted small">{{ $review->created_at?->diffForHumans() }}</div>
                        </div>

                        <div class="row g-3 align-items-start">
                            @if ($firstImage)
                                <div class="col-md-4 col-12">
                                    <img src="{{ asset($firstImage) }}" alt="Review image"
                                        class="img-fluid rounded-4 w-100 object-fit-cover shadow-sm"
                                        style="max-height: 180px;">
                                </div>
                            @endif

                            <div class="col text-muted">
                                {{ $review->description }}
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2 mt-3 text-muted small">
                            <i class="bi bi-heart-fill text-danger"></i>
                            <span>{{ $review->likes->count() }} likes</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-5 text-center text-muted">
                        This user has not posted any reviews yet.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection