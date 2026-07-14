@extends('layouts.admin')

@section('title', 'Reservations')

@section('content')
	<style>
		.page-title {
			color: #0a2540;
			font-size: 48px;
			font-weight: 700;
			letter-spacing: -0.03em;
		}

		.page-subtitle {
			color: #64748b;
		}

		.btn-dark-blue {
			background-color: #0a2540;
			color: #fff !important;
			border-color: #0a2540;
      border-radius: 10px;
		}

		.btn-dark-blue:hover {
			background-color: #143554;
			border-color: #143554;
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

		.summary-card {
			border: 1px solid #e8edf3;
			border-radius: 1.25rem;
			background: #fff;
			padding: 1.1rem 1.25rem;
		}

		.summary-label {
			color: #64748b;
			font-size: .9rem;
			font-weight: 600;
		}

		.summary-value {
			color: #0a2540;
			font-size: 2rem;
			font-weight: 800;
			letter-spacing: -0.03em;
		}

		.badge-status {
			border: none;
			padding: .5rem .8rem;
			border-radius: 999px;
			font-weight: 700;
			text-transform: capitalize;
		}

		.status-pending {
			background: #fff7d6;
			color: #b45309;
		}

		.status-confirmed {
			background: #dbeafe;
			color: #2563eb;
		}

		.status-completed {
			background: #e5e7eb;
			color: #374151;
		}

		.status-cancelled {
			background: #fee2e2;
			color: #dc2626;
		}

		.table th {
			font-size: 13px;
			color: #6b7280;
			padding: 1rem 1.25rem;
			text-transform: uppercase;
			letter-spacing: .04em;
		}

		.table td {
			padding: 1rem 1.25rem;
			vertical-align: middle;
		}

		.table-responsive {
			overflow: visible;
		}

		.pill-nav .btn {
			border-radius: 10px;
			font-weight: 700;
			padding: .55rem 1rem;
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

		.reservation-modal .modal-content {
			border-radius: 20px;
			border: none;
		}

		.reservation-modal .modal-header,
		.reservation-modal .modal-footer {
			border-color: #e5e7eb;
		}

		.detail-card {
			background: #f8fafc;
			border: 1px solid #e8edf3;
			border-radius: 1rem;
			padding: .9rem 1rem;
			height: 100%;
		}

		.detail-label {
			color: #64748b;
			font-size: .8rem;
			font-weight: 700;
			text-transform: uppercase;
			letter-spacing: .04em;
		}

		.detail-value {
			color: #0f172a;
			font-weight: 700;
		}
	</style>

	@php
		$tabUrl = fn ($tab) => route('admin.reservations', ['tab' => $tab, 'search' => $search]);
	@endphp

	<div class="container-fluid py-4 px-5">
		<div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-4">
			<div>
				<h1 class="page-title mb-2">Reservations</h1>
				<p class="page-subtitle mb-0">Review every booking, inspect the guest details, and track the current status.</p>
			</div>

			<form action="{{ route('admin.reservations') }}" method="GET" class="search-shell d-flex gap-2 align-items-center">
				<input type="hidden" name="tab" value="{{ $currentTab }}">
				<div class="input-group">
					<span class="input-group-text bg-white border-end-0 rounded-start-pill text-secondary">
						<i class="fa-solid fa-magnifying-glass"></i>
					</span>
					<input type="text" name="search" value="{{ $search }}" class="form-control border-start-0 rounded-end-pill"
						placeholder="Search code, guest, restaurant, table">
				</div>
				<button type="submit" class="btn btn-dark-blue rounded-pill px-4">Search</button>
			</form>
		</div>

		@if (session('success'))
			<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4">
				{{ session('success') }}
				<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
			</div>
		@endif

		<div class="row g-3 mb-4">
			<div class="col-6 col-xl-2">
				<div class="summary-card">
					<div class="summary-label">All</div>
					<div class="summary-value">{{ $counts['all'] ?? 0 }}</div>
				</div>
			</div>
			<div class="col-6 col-xl-2">
				<div class="summary-card">
					<div class="summary-label">Pending</div>
					<div class="summary-value">{{ $counts['pending'] ?? 0 }}</div>
				</div>
			</div>
			<div class="col-6 col-xl-2">
				<div class="summary-card">
					<div class="summary-label">Confirmed</div>
					<div class="summary-value">{{ $counts['confirmed'] ?? 0 }}</div>
				</div>
			</div>
			<div class="col-6 col-xl-2">
				<div class="summary-card">
					<div class="summary-label">Completed</div>
					<div class="summary-value">{{ $counts['completed'] ?? 0 }}</div>
				</div>
			</div>
			<div class="col-6 col-xl-2">
				<div class="summary-card">
					<div class="summary-label">Cancelled</div>
					<div class="summary-value">{{ $counts['cancelled'] ?? 0 }}</div>
				</div>
			</div>
		</div>

		<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
			<div class="pill-nav d-flex flex-wrap gap-2">
				<a href="{{ $tabUrl('all') }}" class="btn {{ $currentTab === 'all' ? 'btn-dark-blue' : 'btn-unactive' }} me-2">All</a>
				<a href="{{ $tabUrl('pending') }}" class="btn {{ $currentTab === 'pending' ? 'btn-dark-blue' : 'btn-unactive' }} me-2">Pending</a>
				<a href="{{ $tabUrl('confirmed') }}" class="btn {{ $currentTab === 'confirmed' ? 'btn-dark-blue' : 'btn-unactive' }} me-2">Confirmed</a>
				<a href="{{ $tabUrl('completed') }}" class="btn {{ $currentTab === 'completed' ? 'btn-dark-blue' : 'btn-unactive' }} me-2">Completed</a>
				<a href="{{ $tabUrl('cancelled') }}" class="btn {{ $currentTab === 'cancelled' ? 'btn-dark-blue' : 'btn-unactive' }}">Cancelled</a>
			</div>

			@if ($search !== '' || $currentTab !== 'all')
				<a href="{{ route('admin.reservations') }}" class="text-decoration-none fw-semibold text-secondary">Reset filters</a>
			@endif
		</div>

		<div class="card border-0 shadow-sm rounded-4">
			<div class="table-responsive">
				<table class="table align-middle mb-0">
					<thead class="border-top">
						<tr>
							<th>Code</th>
							<th>Guest</th>
							<th>Restaurant</th>
							<th>Date & Time</th>
							<th>Table / People</th>
							<th>Status</th>
							<th class="text-end">Details</th>
						</tr>
					</thead>
					<tbody>
						@forelse ($reservations as $reservation)
							@php
								$guestName = $reservation->guest_name
									?? trim((optional($reservation->user)->first_name ?? '') . ' ' . (optional($reservation->user)->last_name ?? ''))
									?: 'Guest';
								$reservationDate = $reservation->reservation_date?->format('Y-m-d') ?? '-';
								$reservationTime = $reservation->reservation_time ? date('H:i', strtotime($reservation->reservation_time)) : '-';
								$status = $reservation->status ?? 'pending';
							@endphp
							<tr>
								<td class="fw-bold text-navy">{{ $reservation->reservation_code }}</td>
								<td>
									<div class="fw-semibold">{{ $guestName }}</div>
									<div class="text-secondary small">{{ optional($reservation->user)->email ?? $reservation->phone_number ?? 'No contact info' }}</div>
								</td>
								<td>
									<div class="fw-semibold">{{ optional($reservation->restaurant)->restaurant_name ?? '-' }}</div>
									<div class="text-secondary small">{{ optional($reservation->restaurant)->city ?? '' }} {{ optional($reservation->restaurant)->prefecture ? '· ' . optional($reservation->restaurant)->prefecture : '' }}</div>
								</td>
								<td>
									<div class="fw-semibold">{{ $reservationDate }}</div>
									<div class="text-secondary small">{{ $reservationTime }}</div>
								</td>
								<td>
									<div class="fw-semibold">{{ optional($reservation->table)->table_name ?? '-' }}</div>
									<div class="text-secondary small">{{ $reservation->num_of_people }} guests</div>
								</td>
								<td>
									<span class="badge-status status-{{ $status }}">{{ $status }}</span>
								</td>
								<td class="text-end">
									<button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3"
										data-bs-toggle="modal" data-bs-target="#reservationModal{{ $reservation->id }}">
										View
									</button>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="7" class="text-center py-5 text-secondary">
									<i class="fa-regular fa-calendar-xmark d-block fs-3 mb-2"></i>
									No reservations found.
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>
	</div>

	@foreach ($reservations as $reservation)
		@php
			$guestName = $reservation->guest_name
				?? trim((optional($reservation->user)->first_name ?? '') . ' ' . (optional($reservation->user)->last_name ?? ''))
				?: 'Guest';
			$status = $reservation->status ?? 'pending';
		@endphp
		<div class="modal fade reservation-modal" id="reservationModal{{ $reservation->id }}" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
				<div class="modal-content">
					<div class="modal-header px-4 py-3">
						<div>
							<h5 class="modal-title fw-bold mb-1">{{ $reservation->reservation_code }} Reservation Details</h5>
							<div class="text-secondary small">{{ optional($reservation->restaurant)->restaurant_name ?? 'Restaurant' }}</div>
						</div>
						<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body p-4">
						<div class="row g-3 mb-3">
							<div class="col-12 col-md-3">
								<div class="detail-card">
									<div class="detail-label">Status</div>
									<div class="detail-value mt-1"><span class="badge-status status-{{ $status }}">{{ $status }}</span></div>
								</div>
							</div>
							<div class="col-12 col-md-3">
								<div class="detail-card">
									<div class="detail-label">Guest</div>
									<div class="detail-value mt-1">{{ $guestName }}</div>
								</div>
							</div>
							<div class="col-12 col-md-3">
								<div class="detail-card">
									<div class="detail-label">Date</div>
									<div class="detail-value mt-1">{{ $reservation->reservation_date?->format('Y-m-d') ?? '-' }}</div>
								</div>
							</div>
							<div class="col-12 col-md-3">
								<div class="detail-card">
									<div class="detail-label">Time</div>
									<div class="detail-value mt-1">{{ $reservation->reservation_time ? date('H:i', strtotime($reservation->reservation_time)) : '-' }}</div>
								</div>
							</div>
						</div>

						<div class="row g-3">
							<div class="col-12 col-lg-6">
								<div class="detail-card h-100">
									<div class="detail-label mb-2">Reservation Information</div>
									<div class="d-grid gap-2">
										<div><span class="text-secondary">Code:</span> <span class="fw-semibold">{{ $reservation->reservation_code }}</span></div>
										<div><span class="text-secondary">People:</span> <span class="fw-semibold">{{ $reservation->num_of_people }}</span></div>
										<div><span class="text-secondary">Table:</span> <span class="fw-semibold">{{ optional($reservation->table)->table_name ?? '-' }}</span></div>
										<div><span class="text-secondary">Booking source:</span> <span class="fw-semibold">{{ ucfirst($reservation->booking_source ?? 'online') }}</span></div>
										<div><span class="text-secondary">Cancelled by:</span> <span class="fw-semibold">{{ $reservation->cancelled_by ? ucfirst($reservation->cancelled_by) : '-' }}</span></div>
										<div><span class="text-secondary">Created at:</span> <span class="fw-semibold">{{ optional($reservation->created_at)->format('Y-m-d H:i') ?? '-' }}</span></div>
									</div>
								</div>
							</div>
							<div class="col-12 col-lg-6">
								<div class="detail-card h-100">
									<div class="detail-label mb-2">Guest & Restaurant Information</div>
									<div class="d-grid gap-2">
										<div><span class="text-secondary">Guest name:</span> <span class="fw-semibold">{{ $guestName }}</span></div>
										<div><span class="text-secondary">Email:</span> <span class="fw-semibold">{{ optional($reservation->user)->email ?? '-' }}</span></div>
										<div><span class="text-secondary">Phone:</span> <span class="fw-semibold">{{ $reservation->phone_number ?? optional($reservation->user)->phone_number ?? '-' }}</span></div>
										<div><span class="text-secondary">Restaurant:</span> <span class="fw-semibold">{{ optional($reservation->restaurant)->restaurant_name ?? '-' }}</span></div>
										<div><span class="text-secondary">Location:</span> <span class="fw-semibold">{{ trim((optional($reservation->restaurant)->city ?? '') . ' ' . (optional($reservation->restaurant)->prefecture ? '· ' . optional($reservation->restaurant)->prefecture : '')) ?: '-' }}</span></div>
										<div><span class="text-secondary">Updated at:</span> <span class="fw-semibold">{{ optional($reservation->updated_at)->format('Y-m-d H:i') ?? '-' }}</span></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer px-4 py-3">
						<button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	@endforeach
@endsection
