@extends('layouts.admin')

@section('title', 'Restaurant Details')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold mb-0">Restaurant Details</h1>

    <a href="{{ route('admin.restaurants.edit', $restaurant) }}"
       class="btn btn-primary">
        Edit Restaurant
    </a>
</div>

<div class="row g-4">

    {{-- 🍽 Restaurant Info Card --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Restaurant Info</h5>

                <p class="mb-2">
                    <strong>Name:</strong> {{ $restaurant->restaurant_name }}
                </p>

                <p class="mb-2">
                    <strong>Email:</strong> {{ $restaurant->email }}
                </p>

                <p class="mb-2">
                    <strong>Category:</strong> {{ $restaurant->category->name ?? '-' }}
                </p>

                <p class="mb-0">
                    <strong>Location:</strong> {{ $restaurant->location ?? '-' }}
                </p>
            </div>
        </div>
    </div>

    {{-- 👤 Owner Info Card --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Owner Info</h5>

                <p class="mb-2">
                    <strong>Name:</strong>
                    {{ $restaurant->user->first_name ?? '' }}
                    {{ $restaurant->user->last_name ?? '' }}
                </p>

                <p class="mb-2">
                    <strong>Email:</strong> {{ $restaurant->user->email ?? '-' }}
                </p>

                <p class="mb-0">
                    <strong>Role:</strong> {{ $restaurant->user->role->name ?? '-' }}
                </p>
            </div>
        </div>
    </div>

    {{-- 📊 Status Card --}}
    <div class="col-md-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body d-flex justify-content-between align-items-center">

                <div>
                    <h5 class="fw-bold mb-1">Status</h5>

                    @php
                        $status = $restaurant->is_active;
                    @endphp

                    <span class="badge
                        @if($status == 'pending') status-pending
                        @elseif($status == 'approved') status-active
                        @else status-rejected
                        @endif
                    ">
                        {{ ucfirst($status) }}
                    </span>
                </div>

                {{-- quick status change (optional but powerful UX) --}}
                <form action="{{ route('admin.restaurants.status', $restaurant) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <select name="is_active" class="form-select" onchange="this.form.submit()">
                        <option value="pending" @selected($status == 'pending')>Pending</option>
                        <option value="approved" @selected($status == 'approved')>Active</option>
                        <option value="rejected" @selected($status == 'rejected')>Rejected</option>
                    </select>
                </form>

            </div>
        </div>
    </div>

</div>

@endsection