@extends('layouts.admin')

@section('title', 'Restaurants')

@section('content')

    <style>
        .page-title {
            color: #0a2540;
            font-size: 48px;
            font-weight: 700;
        }

        .btn-dark-blue {
            background-color: #0a2540;
            color: #fff !important;
            border-radius: 10px;
        }

        .btn-dark-blue:hover {
            background-color: #0a2540;
            color: #fff !important;
            border-radius: 10px;
        }

        .btn-unactive {
            background: #fff;
            color: #0a2540;
            border: 1px solid #0a2540;
            border-radius: 10px;
        }

        .btn-unactive:hover {
            background-color: #0a2540;
            color: #fff !important;
            border-radius: 10px;
        }

        .btn-dark-blue:approved,
        .btn-dark-blue.approved,
        .btn-dark-blue:focus,
        .btn-dark-blue:focus-visible {
            background-color: #0a2540 !important;
            color: #fff !important;
            border-color: #0a2540 !important;
            box-shadow: none !important;
        }

        .btn-unactive:approved,
        .btn-unactive.approved {
            background-color: #0a2540 !important;
            color: #fff !important;
            border-color: #0a2540 !important;
        }

        .table th{
            font-size:13px;
            color:#6b7280;
            padding:1rem 1.5rem;
        }

        .table td {
            padding: 1rem 1.5rem;
        }

        .sticky-th {
            position: sticky !important;
            top: 0 !important;
            background-color: #f8f9fa !important;
            z-index: 10;
            border-bottom: 2px solid #dee2e6 !important;
        }

        .table-responsive {
            max-height: calc(100vh - 260px);
            overflow: auto;
        }

        /* .table thead th {
            position: sticky;
            top: 0;
            background-color: 
            z-index: 2;
        } */

        .restaurant-table-scroll {
            max-height: 730px;
            overflow-y: auto !important;
            position: relative;
        }

        .search-shell {
            min-width: 320px;
        }

        .search-shell .form-control {
            border-radius: 999px;
            padding-left: 1rem;
            padding-right: 1rem;
            min-height: 44px;
        }

        /* Pending */
        .status-pending {
            background: #dbeafe;
            color: #2563eb;
        }

        /* approved */
        .status-approved {
            background: #dcffd5;
            color: #5dea0c;
        }

        /* Reject */
        .status-rejected {
            background: #f3e8ff;
            color: #9333ea;
        }

        /* Suspended */
        .status-suspended {
            background: #fee2e2;
            color: #dc2626;
        }

        .status-pending,
        .status-approved,
        .status-rejected,
        .status-suspended {
            border: none;
            padding: .5rem .75rem;
            border-radius: 999px;
        }

        .dropdown-menu {
            border: none;
            border-radius: 16px;
            padding: .5rem;
            min-width: 180px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .08);
        }

        .dropdown {
            position: relative;
        }

        .dropdown .dropdown-toggle {
            cursor: pointer;
        }

        .dropdown .dropdown-menu {
            position: absolute;
            top: calc(100% + 6px);
            right: 0;
            left: auto;
            z-index: 2000;
        }

        .status-option {
            display: block;
            width: 100%;
            text-align: left;
            border: none;
            background: transparent;
            padding: .6rem .8rem;
            border-radius: 10px;
            font-weight: 500;
        }

        /* Pending */
        .status-option-pending {
            color: #2563eb;
        }

        .status-option-pending:hover {
            background: #dbeafe;
            color: #2563eb;
        }

        /* approved */
        .status-option-approved {
            color: #16a34a;
        }

        .status-option-approved:hover {
            background: #dcffd5;
            color: #16a34a;
        }

        /* Admin */
        .status-option-rejected {
            color: #9333ea;
        }

        .status-option-rejected:hover {
            background: #f3e8ff;
            color: #9333ea;
        }

        .status-option-suspended {
            color: #dc2626;
        }

        .status-option-suspended:hover {
            background: #fee2e2;
            color: #dc2626;
        }

        /* Modal style */
        .modal-content {
            border-radius: 20px;
            border: none;
        }

        .modal-header {
            border-bottom: 1px solid #e5e7eb;
        }

        .modal-footer {
            border-top: 1px solid #e5e7eb;
        }
    </style>

    <h1 class="fw-bold mb-4 page-title">
        Restaurants
    </h1>

    <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-4">
        <div class="mb-2 mb-md-0">
            <a href="{{ route('admin.restaurants', ['search' => $search]) }}"
                class="btn {{ request()->routeIs('admin.restaurants') ? 'btn-dark-blue' : 'btn-unactive' }} me-2">All</a>
            <a href="{{ route('admin.restaurants.pending', ['search' => $search]) }}"
                class="btn {{ request()->routeIs('admin.restaurants.pending') ? 'btn-dark-blue' : 'btn-unactive' }} me-2">Pending</a>
            <a href="{{ route('admin.restaurants.approved', ['search' => $search]) }}"
                class="btn {{ request()->routeIs('admin.restaurants.approved') ? 'btn-dark-blue' : 'btn-unactive' }} me-2">Approved</a>
            <a href="{{ route('admin.restaurants.rejected', ['search' => $search]) }}"
                class="btn {{ request()->routeIs('admin.restaurants.rejected') ? 'btn-dark-blue' : 'btn-unactive' }} me-2">Rejected</a>
            <a href="{{ route('admin.restaurants.suspended', ['search' => $search]) }}"
                class="btn {{ request()->routeIs('admin.restaurants.suspended') ? 'btn-dark-blue' : 'btn-unactive' }}">Suspended</a>
        </div>

        <form action="{{ url()->current() }}" method="GET" class="search-shell d-flex gap-2 align-items-center">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 rounded-start-pill text-secondary">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" name="search" value="{{ $search }}"
                    class="form-control border-start-0 rounded-end-pill"
                    placeholder="Search restaurant, owner, location, status">
            </div>
            <button type="submit" class="btn btn-dark-blue rounded-pill px-4">Search</button>
            @if (!empty($search))
                <a href="{{ route('admin.restaurants') }}" class="text-decoration-none fw-semibold text-secondary">Reset</a>
            @endif
        </form>
    </div>

    <div class="card border-0 shadow-sm rounded-4">

        <div class="table-responsive restaurant-table-scroll">

            <table class="table align-middle mb-0" style="border-collapse: separate; border-spacing: 0;">

                <thead class="bg-light">
                    <tr>
                        <th class="sticky-th">RESTAURANT</th>
                        <th class="sticky-th">OWNER</th>
                        <th class="sticky-th">LOCATION</th>
                        <th class="sticky-th">CATEGORY</th>
                        <th class="sticky-th">STATUS</th>
                        <th class="sticky-th"></th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($restaurants as $restaurant)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">

                                    <div>
                                        <strong>
                                            {{ $restaurant->restaurant_name }}
                                        </strong>

                                        <div class="small text-secondary">
                                            {{ $restaurant->user->email }}
                                        </div>
                                    </div>

                                </div>
                            </td>

                            <td>{{ $restaurant->owner_name }}</td>
                            <td>{{ trim(($restaurant->postal_code ?? '') . ' ' . ($restaurant->prefecture ?? '') . ' ' . ($restaurant->city ?? '') . ' ' . ($restaurant->street_address_building ?? '')) ?: '-' }}
                            </td>
                            <td>{{ $restaurant->category ?? '-' }}</td>

                            <td>
                                <div class="dropdown">

                                    <button type="button"
                                        class="badge border-0 dropdown-toggle

                                  @if ($restaurant->status == 'pending') status-pending
                                  @elseif($restaurant->status == 'approved')
                                      status-approved
                                  @elseif($restaurant->status == 'rejected')
                                      status-rejected
                                  @else
                                      status-suspended @endif"
                                        data-bs-toggle="dropdown">

                                        {{ ucfirst($restaurant->status) }}

                                    </button>

                                    <ul class="dropdown-menu">

                                        <li>
                                            <form action="{{ route('admin.restaurants.status', $restaurant) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')

                                                <input type="hidden" name="status" value="pending">

                                                <button type="submit"
                                                    class="status-option status-option-pending d-flex justify-content-between">

                                                    <span>Pending</span>

                                                    @if ($restaurant->status == 'pending')
                                                        <span>✓</span>
                                                    @endif

                                                </button>
                                            </form>
                                        </li>

                                        <li>
                                            <form action="{{ route('admin.restaurants.status', $restaurant) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')

                                                <input type="hidden" name="status" value="approved">

                                                <button type="submit"
                                                    class="status-option status-option-approved d-flex justify-content-between">

                                                    <span>Approved</span>

                                                    @if ($restaurant->status == 'approved')
                                                        <span>✓</span>
                                                    @endif

                                                </button>
                                            </form>
                                        </li>

                                        <li>
                                            <form action="{{ route('admin.restaurants.status', $restaurant) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')

                                                <input type="hidden" name="status" value="rejected">

                                                <button type="submit"
                                                    class="status-option status-option-rejected d-flex justify-content-between">

                                                    <span>Rejected</span>

                                                    @if ($restaurant->status == 'rejected')
                                                        <span>✓</span>
                                                    @endif

                                                </button>
                                            </form>
                                        </li>

                                        <li>
                                            <form action="{{ route('admin.restaurants.status', $restaurant) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')

                                                <input type="hidden" name="status" value="suspended">

                                                <button type="submit"
                                                    class="status-option status-option-suspended d-flex justify-content-between">

                                                    <span>Suspended</span>

                                                    @if ($restaurant->status == 'suspended')
                                                        <span>✓</span>
                                                    @endif

                                                </button>
                                            </form>
                                        </li>

                                    </ul>

                                </div>

                            </td>

                            <td>
                                <a href="{{ route('admin.restaurants.show', $restaurant) }}"
                                    class="btn text-secondary border-0">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </a>

                                <button type="button" class="btn text-danger border-0" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $restaurant->id }}">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>

                            </td>

                        </tr>

                        {{-- include  the modal here --}}
                        @include('admin.modals.delete', [
                            'id' => $restaurant->id,
                            'route' => route('admin.restaurants.destroy', $restaurant),
                            'title' => 'Delete Restaurant',
                            'message' => 'Are you sure you want to delete ' . $restaurant->restaurant_name . '?',
                        ])

                    @empty

                        <tr>

                            <td colspan="6" class="text-center py-4">
                                No restaurants found.
                            </td>

                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>

    </div>

@endsection
