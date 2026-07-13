@extends('layouts.restaurant')

@section('title', 'Table Schedule')

@section('content')
    @php
        $displayDate = \Carbon\Carbon::parse($date);
        $blockMinutes = (int) (($restaurant->stay_duration ?? 120) + 15);
        $slotMinutes = (int) ($restaurant->stay_duration ?? 120);
        $totalColumns = count($timeSlots);
        $currentStart = $displayStartTime ?? ($timelineOpen ?? '17:00');
        $showingEnd = $currentStart
            ? \Carbon\Carbon::createFromFormat('H:i', $currentStart)
                ->addMinutes(max($totalColumns - 1, 0) * 15)
                ->format('H:i')
            : null;
        $prevStart = $currentStart
            ? \Carbon\Carbon::createFromFormat('H:i', $currentStart)->subMinutes(30)->format('H:i')
            : null;
        $nextStart = $currentStart
            ? \Carbon\Carbon::createFromFormat('H:i', $currentStart)->addMinutes(30)->format('H:i')
            : null;

        $fullName = function ($reservation) {
            $userName = trim(($reservation->user->first_name ?? '') . ' ' . ($reservation->user->last_name ?? ''));
            if ($userName !== '') {
                return $userName;
            }
            if (!empty($reservation->guest_name)) {
                return $reservation->guest_name;
            }
            return match ($reservation->booking_source ?? 'online') {
                'phone' => 'Phone Guest',
                'walk_in' => 'Walk-in Guest',
                default => 'Guest',
            };
        };

        $statusBadgeClass = function ($status) {
            return match ($status) {
                'pending' => 'bg-light text-navy border',
                'confirmed' => 'bg-navy text-white',
                'completed' => 'bg-pink text-navy',
                'cancelled' => 'bg-danger-subtle text-danger border',
                default => 'bg-light text-secondary border',
            };
        };

        $reservationBlockClass = function ($status) {
            return match ($status) {
                'pending' => 'reservation-block-pending',
                'confirmed' => 'reservation-block-confirmed',
                'completed' => 'reservation-block-completed',
                default => 'reservation-block-pending',
            };
        };

        $cancelledByLabel = function ($reservation) {
            return $reservation->cancelled_by === 'restaurant' ? 'Canceled by restaurant' : 'Canceled by customer';
        };
    @endphp

    <style>
        .text-navy {
            color: #0A2540 !important;
        }

        .bg-navy {
            background-color: #0A2540 !important;
        }

        .bg-pink {
            background-color: #FCE7F3 !important;
        }

        .btn-chevron-custom {
            background-color: transparent !important;
            border: 1px solid #e9ecef !important;
            color: #6c757d !important;
            transition: all 0.2s ease-in-out;
        }

        .btn-chevron-custom:hover {
            background-color: #FCE7F3 !important;
            border-color: #FCE7F3 !important;
            color: #0A2540 !important;
        }

        .btn-pink-custom {
            background-color: #FCE7F3 !important;
            color: #0A2540 !important;
            border: none !important;
            border-radius: 8px;
            height: 31px;
        }

        .btn-pink-custom:hover {
            background-color: #fbcfe8 !important;
        }


        .manual-reservation-actions .btn {
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-weight: 700;
        }

        .manual-reservation-submit,
        .manual-reservation-submit:disabled {
            background-color: #FCE7F3 !important;
            color: #0A2540 !important;
            border: none !important;
            opacity: 1 !important;
        }

        .manual-reservation-submit:hover:not(:disabled) {
            background-color: #fbcfe8 !important;
            color: #0A2540 !important;
        }

        .manual-reservation-submit:disabled {
            cursor: not-allowed;
        }

        .custom-table-head th {
            padding-bottom: 4px !important;
            border-bottom: none !important;
        }

        .table-clickable {
            cursor: pointer;
            transition: background-color 0.15s ease-in-out;
        }

        .table-clickable:hover {
            background-color: #f1f3f5 !important;
        }

        .schedule-table {
            table-layout: fixed !important;
            width: 100% !important;
            min-width: 800px;
            border-collapse: separate;
            border-spacing: 0 12px;
            margin-top: -12px;
        }

        .schedule-table .table-name-cell {
            width: 100px !important;
            min-width: 100px !important;
            background-color: #fff;
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }

        .schedule-table .time-cell,
        .schedule-table .time-head-cell {
            width: 60px !important;
            min-width: 60px !important;
            overflow: hidden;
            white-space: nowrap;
        }

        .reservation-block-confirmed {
            background-color: #0A2540 !important;
            color: #ffffff !important;
        }

        .reservation-block-pending {
            background-color: #e9ecef !important;
            color: #0A2540 !important;
            border: 1px solid #ced4da;
        }

        .reservation-block-completed {
            background-color: #FCE7F3 !important;
            color: #0A2540 !important;
        }
        }

        .disabled-table {
            opacity: 0.45;
        }

        .status-badge {
            font-size: 10px;
            letter-spacing: 0.2px;
            text-transform: capitalize;
        }

        .empty-slot {
            height: 50px;
            min-width: 60px;
            padding: 0;
            background-color: #fff;
        }

        @media (max-width: 767.98px) {
            ::-webkit-scrollbar {
                display: none;
            }
        }
    </style>

    <div class="d-flex flex-column"
        style="min-height: calc(100vh - 70px); background-color: transparent; margin-top: -100px;">
        <div class="d-flex flex-column d-md-none px-4 bg-white" style="padding-top: 95px; box-sizing: border-box;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h2 class="h4 mb-0 fw-bold text-navy">Table Schedule</h2>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm px-3 py-1 fw-bold btn-pink-custom" data-bs-toggle="modal"
                        data-bs-target="#manualReservationModal">
                        <i class="fa-solid fa-calendar-plus me-1"></i> Add Reservation
                    </button>
                    <button
                        class="btn btn-sm px-3 py-1 fw-bold d-inline-flex align-items-center justify-content-center btn-pink-custom"
                        data-bs-toggle="modal" data-bs-target="#addTableModal">
                        <i class="fa-solid fa-plus me-1"></i> Add Table
                    </button>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2 pb-2 mt-3">
                <a href="{{ route('restaurant.dashboard', ['date' => $displayDate->copy()->subDay()->format('Y-m-d'), 'start_time' => $currentStart]) }}"
                    class="btn btn-chevron-custom btn-sm px-2 py-1">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
                <input type="date" class="form-control form-control-sm text-center fw-bold" value="{{ $date }}"
                    onchange="location.href='{{ route('restaurant.dashboard') }}?date=' + this.value + '&start_time={{ $currentStart }}'"
                    style="width: 100%; background-color: #f8f9fa;">
                <a href="{{ route('restaurant.dashboard', ['date' => $displayDate->copy()->addDay()->format('Y-m-d'), 'start_time' => $currentStart]) }}"
                    class="btn btn-chevron-custom btn-sm px-2 py-1">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            </div>
        </div>

        <div class="d-none d-md-flex justify-content-between align-items-center px-4 pb-3 bg-white"
            style="padding-top: 100px;">
            <h2 class="h4 mb-0 fw-bold text-navy">Table Schedule</h2>

            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('restaurant.dashboard', ['date' => $displayDate->copy()->subDay()->format('Y-m-d'), 'start_time' => $currentStart]) }}"
                        class="btn btn-chevron-custom btn-sm px-2 py-1">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>

                    <input type="date" class="form-control form-control-sm text-center fw-bold"
                        value="{{ $date }}"
                        onchange="location.href='{{ route('restaurant.dashboard') }}?date=' + this.value + '&start_time={{ $currentStart }}'"
                        style="width: 140px; background-color: #f8f9fa;">

                    <a href="{{ route('restaurant.dashboard', ['date' => $displayDate->copy()->addDay()->format('Y-m-d'), 'start_time' => $currentStart]) }}"
                        class="btn btn-chevron-custom btn-sm px-2 py-1">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-sm px-3 py-1 fw-bold btn-pink-custom" data-bs-toggle="modal"
                        data-bs-target="#manualReservationModal">
                        <i class="fa-solid fa-calendar-plus me-1"></i> Add Reservation
                    </button>
                    <button
                        class="btn btn-sm px-3 py-1 fw-bold d-inline-flex align-items-center justify-content-center btn-pink-custom"
                        data-bs-toggle="modal" data-bs-target="#addTableModal">
                        <i class="fa-solid fa-plus me-1"></i> Add Table
                    </button>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success mx-4 mt-3 mb-0">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger mx-4 mt-3 mb-0">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger mx-4 mt-3 mb-0">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="d-block d-md-none px-3 pt-3 pb-5">
            <div class="d-flex gap-2 overflow-x-auto pb-3 mb-3" style="scrollbar-width: none; -ms-overflow-style: none;">
                @forelse ($timeSlots as $time)
                    <a href="{{ route('restaurant.dashboard', ['date' => $date, 'start_time' => $time]) }}"
                        class="btn rounded-pill px-3 py-2 small {{ $time === $currentStart ? 'fw-bold text-white bg-navy' : 'fw-medium' }} text-nowrap"
                        style="{{ $time === $currentStart ? '' : 'background-color: #f1f3f5; color: #495057;' }} font-size: 13px;">
                        {{ $time }}
                    </a>
                @empty
                    <span class="text-muted small">Closed on this date.</span>
                @endforelse
            </div>

            <h5 class="fw-bold mb-3 text-navy" style="font-size: 18px;">Reservations ({{ $activeReservations->count() }})
            </h5>
            <div class="d-flex flex-column gap-3 mb-4">
                @forelse ($activeReservations as $reservation)
                    @php
                        $customer = $fullName($reservation);
                        $time = \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i');
                        $tableName = $reservation->table->table_name ?? '-';
                    @endphp
                    <div class="card p-3 border rounded-4 shadow-sm" style="background-color: #ffffff; cursor: pointer;"
                        onclick="focusReservation('{{ $date }}', '{{ $time }}', {{ $reservation->id }})">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <div class="fw-bold text-dark" style="font-size: 15px;">{{ $customer }}</div>
                            <span class="badge text-secondary border bg-light px-2 py-1 rounded small fw-normal"
                                style="font-size: 10px;">
                                RM{{ str_pad($reservation->id, 3, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                        <div class="text-secondary small d-flex flex-column gap-1" style="font-size: 12px;">
                            <div><i class="fa-regular fa-clock me-1"></i> {{ $time }} <i
                                    class="fa-solid fa-user-group ms-3 me-1"></i> {{ $reservation->num_of_people }}</div>
                            <div class="mt-1 d-flex align-items-center gap-2 flex-wrap">
                                <span class="text-dark fw-medium">{{ $tableName }}</span>
                                <span
                                    class="badge rounded-pill px-2 py-1 status-badge {{ $statusBadgeClass($reservation->status) }}">
                                    {{ $reservation->status }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted small mb-0">No reservations.</p>
                @endforelse
            </div>

            <div class="mt-4">
                <h6 class="fw-bold text-secondary mb-3 small text-uppercase" style="letter-spacing: 0.5px;">Canceled</h6>
                <div class="d-flex flex-column gap-3">
                    @forelse ($cancelledReservations as $reservation)
                        @php
                            $customer = $fullName($reservation);
                            $time = \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i');
                        @endphp
                        <div class="card p-3 border border-dashed rounded-4 bg-light opacity-75">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div class="fw-bold text-secondary">{{ $customer }}</div>
                                <span class="text-muted small"
                                    style="font-size: 11px;">RM{{ str_pad($reservation->id, 3, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="text-danger small fw-medium mb-2" style="font-size: 13px;">
                                {{ $cancelledByLabel($reservation) }}</div>
                            <div class="text-muted small" style="font-size: 13px;">
                                <i class="fa-regular fa-clock me-1"></i> {{ $time }}
                                <i class="fa-solid fa-user-group ms-3 me-1"></i> {{ $reservation->num_of_people }}
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">No canceled reservations.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="d-none d-md-flex flex-grow-1" style="overflow-x: auto;">
            <div class="bg-white p-3 d-flex flex-column gap-3" style="width: 320px; min-width: 320px; overflow-y: auto;">
                <div>
                    <h6 class="fw-bold text-dark mb-3">Reservations ({{ $activeReservations->count() }})</h6>
                    <div class="d-flex flex-column gap-2">
                        @forelse ($activeReservations as $reservation)
                            @php
                                $customer = $fullName($reservation);
                                $time = \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i');
                                $tableName = $reservation->table->table_name ?? '-';
                            @endphp
                            <div class="card p-3 border rounded-3 position-relative shadow-sm"
                                style="background-color: #ffffff; cursor: pointer;"
                                onclick="focusReservation('{{ $date }}', '{{ $time }}', {{ $reservation->id }})">
                                <span
                                    class="badge text-secondary border position-absolute top-0 end-0 mt-2 me-2 small fw-normal bg-light"
                                    style="font-size: 10px;">
                                    RM{{ str_pad($reservation->id, 3, '0', STR_PAD_LEFT) }}
                                </span>

                                <div class="fw-bold text-dark mb-1 pe-5">{{ $customer }}</div>

                                <div class="text-secondary small d-flex align-items-center gap-1">
                                    <i class="fa-regular fa-clock"></i> {{ $time }}
                                    <i class="fa-solid fa-user-group ms-2"></i> {{ $reservation->num_of_people }}
                                </div>

                                <div class="d-flex align-items-center gap-2 flex-wrap mt-1">
                                    <span class="text-secondary small">{{ $tableName }}</span>
                                    <span
                                        class="badge rounded-pill px-2 py-1 status-badge {{ $statusBadgeClass($reservation->status) }}">
                                        {{ $reservation->status }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted small mb-0">No reservations.</p>
                        @endforelse
                    </div>
                </div>

                <div class="mt-2">
                    <h6 class="fw-bold text-secondary mb-3 small text-uppercase" style="letter-spacing: 0.5px;">Canceled
                    </h6>
                    <div class="d-flex flex-column gap-2">
                        @forelse ($cancelledReservations as $reservation)
                            @php
                                $customer = $fullName($reservation);
                                $time = \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i');
                            @endphp
                            <div class="card p-3 border border-dashed rounded-3 position-relative bg-light opacity-75">
                                <span class="text-muted position-absolute top-0 end-0 mt-2 me-2 small"
                                    style="font-size: 10px;">
                                    RM{{ str_pad($reservation->id, 3, '0', STR_PAD_LEFT) }}
                                </span>
                                <div class="fw-bold text-secondary mb-1 pe-5">{{ $customer }}</div>
                                <div class="text-danger small fw-medium">{{ $cancelledByLabel($reservation) }}</div>
                                <div class="text-muted small d-flex align-items-center gap-1 mt-1">
                                    <i class="fa-regular fa-clock"></i> {{ $time }}
                                    <i class="fa-solid fa-user-group ms-2"></i> {{ $reservation->num_of_people }}
                                </div>
                            </div>
                        @empty
                            <p class="text-muted small mb-0">No canceled reservations.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="flex-grow-1 bg-white p-3 d-flex flex-column gap-3" style="overflow-y: auto;">
                <div class="d-flex justify-content-between align-items-center px-1">
                    <a href="{{ route('restaurant.dashboard', ['date' => $date, 'start_time' => $prevStart]) }}"
                        class="btn btn-chevron-custom btn-sm px-2 py-1">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>

                    <span class="fw-bold text-dark small" style="letter-spacing: 0.5px;">
                        Showing {{ $displayDate->format('m/d') }}
                        @if ($timeSlots)
                            {{ $currentStart }} - {{ $showingEnd }}
                        @else
                            Closed
                        @endif
                    </span>

                    <a href="{{ route('restaurant.dashboard', ['date' => $date, 'start_time' => $nextStart]) }}"
                        class="btn btn-chevron-custom btn-sm px-2 py-1">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </div>

                <div class="table-responsive">
                    @if ($isClosed)
                        <div class="alert alert-light border text-center text-muted mb-0">This restaurant is closed on this
                            date.</div>
                    @elseif (empty($timeSlots))
                        <div class="alert alert-light border text-center text-muted mb-0">No displayable time slots.</div>
                    @else
                        <table class="table schedule-table align-middle mb-0">
                            <thead class="text-secondary small text-start custom-table-head">
                                <tr>
                                    <th class="table-name-cell"></th>
                                    @foreach ($timeSlots as $time)
                                        <th class="time-head-cell" style="font-weight: 600; padding-left: 8px;">
                                            {{ $time }}</th>
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($tables as $table)
                                    <tr class="shadow-sm {{ $table->is_active ? '' : 'disabled-table' }}">

                                        <td class="table-name-cell table-clickable border-top border-bottom border-start border-end"
                                            onclick="openEditModal({{ $table->id }}, {{ \Illuminate\Support\Js::from($table->table_name) }}, {{ $table->capacity }}, {{ $table->is_active ? 'false' : 'true' }})">

                                            <div class="d-flex align-items-center gap-2 flex-nowrap">
                                                <div class="fw-bold text-truncate">{{ $table->table_name }}</div>

                                                @unless ($table->is_active)
                                                    <span class="badge bg-light text-secondary border flex-shrink-0"
                                                        style="font-size: 10px;">
                                                        Inactive
                                                    </span>
                                                @endunless
                                            </div>

                                            <div class="text-muted" style="font-size: 11px;">{{ $table->capacity }} seats
                                            </div>

                                        </td>

                                        @php
                                            $currentColumn = 0;
                                            $visibleStart = \Carbon\Carbon::parse($date . ' ' . $timeSlots[0]);
                                            $visibleEnd = \Carbon\Carbon::parse(
                                                $date . ' ' . end($timeSlots),
                                            )->addMinutes(15);
                                        @endphp

                                        @while ($currentColumn < $totalColumns)
                                            @php
                                                $slotStart = $visibleStart->copy()->addMinutes($currentColumn * 15);
                                                $reservation = $table->reservations->first(function ($res) use (
                                                    $slotStart,
                                                    $restaurant,
                                                ) {
                                                    $resStart = \Carbon\Carbon::parse(
                                                        $res->reservation_date->format('Y-m-d') .
                                                            ' ' .
                                                            $res->reservation_time,
                                                    );
                                                    $resEnd = $resStart
                                                        ->copy()
                                                        ->addMinutes((int) (($restaurant->stay_duration ?? 120) + 15));
                                                    $slotEnd = $slotStart->copy()->addMinutes(15);
                                                    return $resStart->lt($slotEnd) && $resEnd->gt($slotStart);
                                                });
                                            @endphp

                                            @if ($reservation)
                                                @php
                                                    $resStart = \Carbon\Carbon::parse(
                                                        $reservation->reservation_date->format('Y-m-d') .
                                                            ' ' .
                                                            $reservation->reservation_time,
                                                    );
                                                    $resEnd = $resStart->copy()->addMinutes($blockMinutes);
                                                    $clippedStart = $resStart->gt($slotStart) ? $resStart : $slotStart;
                                                    $clippedEnd = $resEnd->lt($visibleEnd) ? $resEnd : $visibleEnd;
                                                    $colspan = max(
                                                        1,
                                                        (int) ceil($clippedStart->diffInMinutes($clippedEnd) / 15),
                                                    );
                                                    $colspan = min($colspan, $totalColumns - $currentColumn);
                                                    $currentColumn += $colspan;
                                                    $customer = $fullName($reservation);
                                                    $reservationTime = $resStart->format('H:i');
                                                @endphp

                                                <td colspan="{{ $colspan }}"
                                                    class="p-1 border-top border-bottom border-end" style="height: 50px;">

                                                    <div id="reservation-block-{{ $reservation->id }}"
                                                        class="rounded {{ $reservationBlockClass($reservation->status) }}"
                                                        style="height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; font-size: 10px;"
                                                        onclick="openReservationModal({{ $reservation->id }}, 'RM{{ str_pad($reservation->id, 3, '0', STR_PAD_LEFT) }}', {{ \Illuminate\Support\Js::from($customer) }}, '{{ $reservationTime }}', {{ \Illuminate\Support\Js::from($durationLabel) }}, {{ $reservation->num_of_people }}, {{ \Illuminate\Support\Js::from($table->table_name) }}, '{{ $reservation->status }}')">
                                                        <div class="fw-bold text-truncate" style="max-width: 90%;">
                                                            {{ $customer }}</div>
                                                        <div class="text-truncate">{{ $reservation->num_of_people }}
                                                            guests</div>
                                                    </div>
                                                </td>
                                            @else
                                                <td class="border-top border-bottom border-end empty-slot">&nbsp;</td>
                                                @php $currentColumn++; @endphp
                                            @endif
                                        @endwhile
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                    @include('restaurants.dashboard.modals.edit_table')
                    @include('restaurants.dashboard.modals.add_table')
                    @include('restaurants.dashboard.modals.manual_reservation')
                    @include('restaurants.dashboard.modals.reservation_details')
                    @include('restaurants.dashboard.modals.new_reservation')
                </div>
            </div>
        </div>

        <script>
            const tableUpdateUrlTemplate = @json(route('restaurant.tables.update', ['table' => '__ID__']));
            const tableDeleteUrlTemplate = @json(route('restaurant.tables.destroy', ['table' => '__ID__']));
            const reservationUpdateUrlTemplate = @json(route('restaurant.reservations.update_status', ['reservation' => '__ID__']));

            function focusReservation(date, time, reservationId) {
                const url = new URL(@json(route('restaurant.dashboard')), window.location.origin);
                url.searchParams.set('date', date);
                url.searchParams.set('start_time', time);
                url.searchParams.set('focus', reservationId);
                window.location.href = url.toString();
            }

            function roundUpToNextQuarter(date = new Date()) {
                const rounded = new Date(date);
                rounded.setSeconds(0, 0);
                const remainder = rounded.getMinutes() % 15;
                if (remainder !== 0) rounded.setMinutes(rounded.getMinutes() + (15 - remainder));
                return `${String(rounded.getHours()).padStart(2, '0')}:${String(rounded.getMinutes()).padStart(2, '0')}`;
            }

            async function refreshManualTables() {
                const date = document.getElementById('manualDate')?.value;
                const time = document.getElementById('manualTime')?.value;
                const guests = document.getElementById('manualGuests')?.value;
                const tableSelect = document.getElementById('manualTable');
                const message = document.getElementById('manualAvailabilityMessage');
                const submit = document.getElementById('manualReservationSubmit');
                if (!date || !time || !guests || !tableSelect) return;

                tableSelect.disabled = true;
                submit.disabled = true;
                tableSelect.innerHTML = '<option value="">Checking availability...</option>';
                message.textContent = '';

                const url = new URL(@json(route('restaurant.dashboard.manual_availability')), window.location.origin);
                url.searchParams.set('date', date);
                url.searchParams.set('time', time);
                url.searchParams.set('guests', guests);

                try {
                    const response = await fetch(url, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    const data = await response.json();
                    tableSelect.innerHTML = '<option value="">Select an available table</option>';
                    (data.tables || []).forEach(table => {
                        const option = document.createElement('option');
                        option.value = table.id;
                        option.textContent = `${table.name} (${table.capacity} seats)`;
                        tableSelect.appendChild(option);
                    });
                    tableSelect.disabled = !(data.tables || []).length;
                    message.textContent = data.message || '';
                } catch (error) {
                    tableSelect.innerHTML = '<option value="">Unable to load tables</option>';
                    message.textContent = 'Could not check table availability.';
                }
            }

            document.addEventListener('DOMContentLoaded', () => {
                const source = document.getElementById('manualBookingSource');
                const time = document.getElementById('manualTime');
                const modal = document.getElementById('manualReservationModal');
                document.querySelectorAll('.manual-availability-input').forEach(el => {
                    el.addEventListener('change', refreshManualTables);
                    el.addEventListener('input', refreshManualTables);
                });
                document.getElementById('manualTable')?.addEventListener('change', function() {
                    document.getElementById('manualReservationSubmit').disabled = !this.value;
                });
                source?.addEventListener('change', () => {
                    if (source.value === 'walk_in') {
                        document.getElementById('manualDate').value = new Date().toISOString().slice(0, 10);
                        time.value = roundUpToNextQuarter();
                        refreshManualTables();
                    }
                });
                modal?.addEventListener('shown.bs.modal', () => {
                    if (!time.value) time.value = roundUpToNextQuarter();
                    refreshManualTables();
                });
            });

            function openEditModal(id, name, capacity, isDisabled = false) {
                document.getElementById('tableIdInput').value = id;
                document.getElementById('tableNameInput').value = name;
                document.getElementById('tableCapacityInput').value = capacity;

                const form = document.getElementById('editTableForm');
                const methodInput = document.getElementById('editTableMethod');
                form.action = tableUpdateUrlTemplate.replace('__ID__', id);
                methodInput.value = 'PUT';

                if (document.getElementById('deleteTableAction')) {
                    document.getElementById('deleteTableAction').value = tableDeleteUrlTemplate.replace('__ID__', id);
                }

                const enableBtn = document.getElementById('status-enable-btn');
                const disableBtn = document.getElementById('status-disable-btn');
                const statusInput = document.getElementById('table-status-input');

                if (isDisabled) {
                    toggleStatus('disable');
                    document.getElementById('btnEnableTable')?.classList.remove('d-none');
                    document.getElementById('btnDisableTable')?.classList.add('d-none');
                } else {
                    toggleStatus('enable');
                    document.getElementById('btnDisableTable')?.classList.remove('d-none');
                    document.getElementById('btnEnableTable')?.classList.add('d-none');
                }

                hideConfirmView();
                new bootstrap.Modal(document.getElementById('editTableModal')).show();
            }

            function prepareTableDelete() {
                const deleteUrl = document.getElementById('deleteTableAction').value;
                const form = document.getElementById('editTableForm');
                const methodInput = document.getElementById('editTableMethod');
                form.action = deleteUrl;
                methodInput.value = 'DELETE';
            }

            function openReservationModal(reservationId, code, customer, time, duration, guests, table, status = 'confirmed') {
                document.getElementById('resIdDisplay').innerText = code;
                document.getElementById('resCustomerDisplay').innerText = customer;
                document.getElementById('resTimeDisplay').innerText = time;
                document.getElementById('resDurationDisplay').innerText = duration;
                document.getElementById('resGuestsDisplay').innerText = guests;
                document.getElementById('resTableDisplay').innerText = table;
                document.getElementById('resStatusDisplay').innerText = status;

                const form = document.getElementById('reservationStatusForm');
                form.action = reservationUpdateUrlTemplate.replace('__ID__', reservationId);

                document.getElementById('normalResActions').classList.toggle('d-none', status === 'completed' || status ===
                    'cancelled');
                document.getElementById('pendingResActions').classList.toggle('d-none', status !== 'pending');
                document.getElementById('confirmedResActions').classList.toggle('d-none', status !== 'confirmed');
                document.getElementById('completedResActions').classList.toggle('d-none', status !== 'completed' && status !==
                    'cancelled');

                hideResCancelConfirm();
                new bootstrap.Modal(document.getElementById('reservationDetailsModal')).show();
            }
        </script>
    </div>
@endsection
