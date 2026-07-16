@extends('layouts.admin')

@section('title', 'Review Management')

@section('content')

    <style>
        .text-navy {
            color: #0a2540;
        }
    </style>

    <div class="container-fluid py-4 px-5">

        <h2 class="mb-4 text-navy font-weight-bold">Review Management</h2>

        <div class="p-2 rounded-3 mb-4" style="background-color: #dbe1e8;">
            <ul class="nav nav-pills gap-1 align-items-center">
                <li class="nav-item">
                    <a class="nav-link px-3 py-1 text-navy d-flex align-items-center gap-1 {{ $currentTab === 'all' ? 'bg-white text-navy shadow-sm fw-bold' : '' }}"
                        style="border-radius: 6px; border: 1px solid {{ $currentTab === 'all' ? '#ccc' : 'transparent' }};"
                        href="{{ route('admin.reviews', ['tab' => 'all']) }}">
                        All <span class="badge bg-secondary text-white ms-1"
                            style="border-radius: 4px;">{{ $counts['all'] ?? 0 }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 py-1 text-navy d-flex align-items-center gap-1 {{ $currentTab === 'visible' ? 'bg-white text-navy shadow-sm fw-bold' : '' }}"
                        style="border-radius: 6px; border: 1px solid {{ $currentTab === 'visible' ? '#ccc' : 'transparent' }};"
                        href="{{ route('admin.reviews', ['tab' => 'visible']) }}">
                        Visible <span class="badge bg-success text-white ms-1"
                            style="border-radius: 4px;">{{ $counts['visible'] ?? 0 }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 py-1 text-navy d-flex align-items-center gap-1 {{ $currentTab === 'hidden' ? 'bg-white text-navy shadow-sm fw-bold' : '' }}"
                        style="border-radius: 6px; border: 1px solid {{ $currentTab === 'hidden' ? '#ccc' : 'transparent' }};"
                        href="{{ route('admin.reviews', ['tab' => 'hidden']) }}">
                        Hidden <span class="badge bg-warning text-navy ms-1"
                            style="border-radius: 4px;">{{ $counts['hidden'] ?? 0 }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 py-1 text-navy d-flex align-items-center gap-1 {{ $currentTab === 'reported' ? 'bg-white text-navy shadow-sm fw-bold' : '' }}"
                        style="border-radius: 6px; border: 1px solid {{ $currentTab === 'reported' ? '#ccc' : 'transparent' }};"
                        href="{{ route('admin.reviews', ['tab' => 'reported']) }}">
                        Reported <span class="badge bg-danger text-white ms-1"
                            style="border-radius: 4px;">{{ $counts['reported'] ?? 0 }}</span>
                    </a>
                </li>
            </ul>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive bg-white shadow-sm rounded-3" style="max-height: 720px; overflow-y: auto;">
            <table class="table table-hover align-middle mb-0" style="min-width: 1100px;">
                <thead class="sticky-top top-0 table-light text-secondary small text-uppercase" style="z-index: 1000;">
                    <tr>
                        <th class="py-3 px-4" style="width: 12%;">Name</th>
                        <th class="py-3" style="width: 15%;">Restaurant</th>
                        <th class="py-3" style="width: 10%;">Date</th>
                        <th class="py-3" style="width: 10%;">Rating</th>
                        <th class="py-3" style="width: 23%;">Comment</th>
                        <th class="py-3" style="width: 8%;">Image</th>
                        <th class="py-3 text-center" style="width: 8%;">Status</th>
                        <th class="py-3 text-end px-4" style="width: 14%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr style="{{ $review->is_reported ? 'background-color: #fff0f0;' : '' }}">

                            <td class="py-3 px-4 fw-semibold text-navy">
                                {{ $review->user ? $review->user->first_name . ' ' . $review->user->last_name : 'Unknown User' }}
                            </td>

                            <td class="py-3 text-secondary">
                                {{ optional($review->restaurant)->restaurant_name ?? 'N/A' }}
                            </td>

                            <td class="py-3 text-secondary small">
                                {{ $review->created_at->format('Y-m-d') }}
                            </td>

                            <td class="py-3 text-warning">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fa-{{ $i <= ($review->rating ?? 0) ? 'solid' : 'regular' }} fa-star"></i>
                                @endfor
                            </td>

                            <td class="py-3">
                                @if ($review->is_reported)
                                    <div class="text-danger small fw-bold mb-1">
                                        <i class="fa-solid fa-triangle-exclamation"></i> Reported by restaurant
                                    </div>
                                @endif
                                <div class="text-navy small text-wrap"
                                    style="max-height: 60px; overflow-y: auto; line-height: 1.5;">
                                    {{ $review->description }}
                                </div>
                            </td>

                            <td class="py-3">
                                @if ($review->image && file_exists(public_path($review->image)))
                                    <img src="{{ asset($review->image) }}" alt="Review Image"
                                        class="rounded border shadow-sm"
                                        style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center bg-light text-muted rounded border shadow-sm"
                                        style="width: 50px; height: 50px; font-size: 1.2rem;">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                @endif
                            </td>

                            <td class="py-3 text-center">
                                @if (($review->status ?? 'visible') === 'visible')
                                    <span class="badge px-3 text-success rounded-pill fw-semibold"
                                        style="background-color: #e6f7ed; color: #1f9254; padding-top: 6px; padding-bottom: 6px;">visible</span>
                                @else
                                    <span class="badge px-3 text-secondary rounded-pill fw-semibold"
                                        style="background-color: #f1f3f5; color: #6c757d; padding-top: 6px; padding-bottom: 6px;">hidden</span>
                                @endif
                            </td>

                            <td class="py-3 text-end px-4">
                                <div class="d-flex justify-content-end align-items-center gap-2">

                                    <form action="{{ route('admin.reviews.toggle', $review->id) }}" method="POST"
                                        class="d-inline mb-0">
                                        @csrf
                                        @method('PATCH')
                                        @if (($review->status ?? 'visible') === 'visible')
                                            <button type="submit"
                                                class="btn btn-outline-secondary btn-sm px-2 py-1 bg-white rounded shadow-sm text-navy border-secondary-subtle">
                                                Hide
                                            </button>
                                        @else
                                            <button type="submit"
                                                class="btn btn-outline-secondary btn-sm px-2 py-1 bg-white rounded shadow-sm text-dark border-secondary-subtle">
                                                Show
                                            </button>
                                        @endif
                                    </form>

                                    @if ($review->is_reported)
                                        <form action="{{ route('admin.reviews.dismiss', $review->id) }}" method="POST"
                                            class="d-inline mb-0">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="btn btn-outline-danger btn-sm px-2 py-1 bg-white rounded shadow-sm text-danger border-danger-subtle">
                                                Dismiss
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST"
                                            class="d-inline mb-0"
                                            onsubmit="return confirm('Are you sure you want to permanently remove this review?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-danger btn-sm px-2 py-1 rounded shadow-sm text-white">
                                                Remove
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fa-regular fa-folder-open d-block fs-3 mb-2"></i>
                                No reviews found in this section.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection