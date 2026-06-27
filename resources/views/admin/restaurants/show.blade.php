@extends('layouts.admin')

@section('title', 'Restaurant Details')

@section('content')

<style>
.page-title{
    color:#0a2540;
    font-size:48px;
    font-weight:700;
}
.btn-dark-blue{
    background-color:#0a2540 !important;
    color:#fff !important;
}

.btn-dark-blue:hover{
    background-color:#294664 !important;
    color:#fff !important;
}
.status-pending{
    background: #dbeafe;
    color: #2563eb;
}

/* Active */
.status-active{
    background:#dcffd5;
    color:#5dea0c;
}

/* Reject */
.status-rejected{
    background: #f3e8ff;
    color: #9333ea;
}

/* Suspended */
.status-suspended{
    background:#fee2e2;
    color:#dc2626;
}

.status-pending,
.status-active,
.status-rejected,
.status-suspended{
    font-size: 17px;
    border: none;
    padding: .9rem 1.2rem;
    border-radius: 999px;
}

.dropdown-menu{
    border: none;
    border-radius: 16px;
    padding: .5rem;
    min-width: 180px;
    box-shadow: 0 8px 24px rgba(0,0,0,.08);
}

.status-option{
    display: block;
    width: 100%;
    text-align: left;
    border: none;
    background: transparent;
    padding: .6rem .8rem;
    border-radius: 10px;
    font-weight: 500;
}
.status-option-pending{
    color: #2563eb;
}

.status-option-pending:hover{
    background: #dbeafe;
    color: #2563eb;
}

/* active */
.status-option-active{
    color:#16a34a;
}

.status-option-active:hover{
    background:#dcffd5;
    color:#16a34a;
}

/* Admin */
.status-option-rejected{
    color: #9333ea;
}

.status-option-rejected:hover{
    background: #f3e8ff;
    color: #9333ea;
}

.status-option-suspended{
    color:#dc2626;
}

.status-option-suspended:hover{
    background:#fee2e2;
    color:#dc2626;
}
</style>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold mb-0 page-title">Restaurant Details</h1>

    <a href="{{ route('admin.restaurants.edit', $restaurant) }}"
       class="btn btn-dark-blue">
       <i class="fa-solid fa-pen-to-square"></i>
        Edit Restaurant
    </a>
</div>

<div class="row g-4">
    
    {{-- 📊 Status Card --}}
    <div class="col-md-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body d-flex justify-content-between align-items-center">

                <div>
                    <h5 class="fw-bold mb-1">Status</h5>
                </div>

                <div>
                
                    <div class="dropdown">

                      <button
                          type="button"
                          class="badge border-0 dropdown-toggle

                          @if($restaurant->status == 'pending')
                              status-pending
                          @elseif($restaurant->status == 'approved')
                              status-active
                          @elseif($restaurant->status == 'rejected')
                              status-rejected
                          @else
                              status-suspended
                          @endif"

                          data-bs-toggle="dropdown">

                          {{ ucfirst($restaurant->status) }}

                      </button>

                        <ul class="dropdown-menu">

                            <li>
                                <form action="{{ route('admin.restaurants.status', $restaurant) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <input type="hidden" name="status" value="pending">

                                    <button type="submit"
                                            class="status-option status-option-pending d-flex justify-content-between">

                                        <span>Pending</span>

                                        @if($restaurant->status == 'pending')
                                            <span>✓</span>
                                        @endif

                                    </button>
                                </form>
                            </li>

                            <li>
                                <form action="{{ route('admin.restaurants.status', $restaurant) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <input type="hidden" name="status" value="active">

                                    <button type="submit"
                                            class="status-option status-option-active d-flex justify-content-between">

                                        <span>Active</span>

                                        @if($restaurant->status == 'active')
                                            <span>✓</span>
                                        @endif

                                    </button>
                                </form>
                            </li>

                            <li>
                                <form action="{{ route('admin.restaurants.status', $restaurant) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <input type="hidden" name="status" value="rejected">

                                    <button type="submit"
                                            class="status-option status-option-rejected d-flex justify-content-between">

                                        <span>Rejected</span>

                                        @if($restaurant->status == 'rejected')
                                            <span>✓</span>
                                        @endif

                                    </button>
                                </form>
                            </li>

                            <li>
                                <form action="{{ route('admin.restaurants.status', $restaurant) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <input type="hidden" name="status" value="suspended">

                                    <button type="submit"
                                            class="status-option status-option-suspended d-flex justify-content-between">

                                        <span>Suspended</span>

                                        @if($restaurant->status == 'suspended')
                                            <span>✓</span>
                                        @endif

                                    </button>
                                </form>
                            </li>

                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 🍽 Restaurant Info Card --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Restaurant Info</h5>

                <p class="mb-2">
                    <strong>Name:</strong> {{ $restaurant->restaurant_name }}
                </p>

                <p class="mb-2">
                    <strong>Email:</strong> {{ $restaurant->user->email }}
                </p>

                <p class="mb-2">
                  <strong>Phone:</strong> {{ $restaurant->phone_number }}
                </p>

                <p class="mb-2">
                    <strong>Location:</strong> {{ $restaurant->address }}
                </p>

                <p class="mb-2">
                    <strong>Category:</strong> {{ $restaurant->category->name ?? '-' }}
                </p>

                <p class="mb-2">
                    <strong>Features:</strong> {{ $restaurant->features->name ?? '-' }}
                </p>

                <p>
                  <strong>Description:</strong> {{ $restaurant->description ?? '-' }}
                </p>

                @php
                $hours = $restaurant->operating_hours ?? [];
                @endphp

                <p>
                  <strong>Operating Hours:</strong>
                </p>

                @foreach($restaurant->operating_hours ?? [] as $day => $slots)
                    <p><strong class="text-capitalize">{{ $day }}:</strong></p>

                    @foreach($slots as $slot)
                        <div>
                            {{ $slot['open'] }} - {{ $slot['close'] }}
                        </div>
                    @endforeach
                @endforeach
                
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

            </div>
        </div>
    </div>

    

</div>

@endsection