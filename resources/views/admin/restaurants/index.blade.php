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
        }

        .btn-dark-blue:hover {
            background-color: #0a2540;
            color: #fff !important;
        }

        .btn-unactive {
            background: #fff;
            color: #0a2540;
            border: 1px solid #0a2540;
        }

        .btn-unactive:hover {
            background-color: #0a2540;
            color: #fff !important;
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

        .table th {
            font-size: 13px;
            color: #6b7280;
            padding: 1rem 1.5rem;
        }

        .table td {
            padding: 1rem 1.5rem;
        }

        .table-responsive {
            overflow: visible;
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

        /* CSS-only dropdown (no JS): show on hover or keyboard focus */
        .dropdown {
            position: relative;
        }

        .dropdown .dropdown-toggle {
            cursor: pointer;
        }

        .dropdown .dropdown-menu {
            display: none;
            position: absolute;
            top: calc(100% + 6px);
            right: 0;
            left: auto;
            z-index: 2000;
        }

        .dropdown:hover .dropdown-menu,
        .dropdown:focus-within .dropdown-menu {
            display: block;
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

    <div class="mb-4">
        <a href="{{ route('admin.restaurants') }}"
            class="btn {{ request()->routeIs('admin.restaurants') ? 'btn-dark-blue' : 'btn-unactive' }} me-2">All</a>
        <a href="{{ route('admin.restaurants.pending') }}"
            class="btn {{ request()->routeIs('admin.restaurants.pending') ? 'btn-dark-blue' : 'btn-unactive' }} me-2">Pending</a>
        <a href="{{ route('admin.restaurants.approved') }}"
            class="btn {{ request()->routeIs('admin.restaurants.approved') ? 'btn-dark-blue' : 'btn-unactive' }} me-2">Approved</a>
        <a href="{{ route('admin.restaurants.rejected') }}"
            class="btn {{ request()->routeIs('admin.restaurants.rejected') ? 'btn-dark-blue' : 'btn-unactive' }}">Rejected</a>
        <a href="{{ route('admin.restaurants.suspended') }}"
            class="btn {{ request()->routeIs('admin.restaurants.suspended') ? 'btn-dark-blue' : 'btn-unactive' }}">Suspended</a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>
                    <tr>
                        <th>RESTAURANT</th>
                        <th>OWNER</th>
                        <th>LOCATION</th>
                        <th>CATEGORY</th>
                        <th>STATUS</th>
                        <th></th>
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
