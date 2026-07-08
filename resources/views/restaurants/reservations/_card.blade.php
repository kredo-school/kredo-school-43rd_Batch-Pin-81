<div class="reservation-card p-3 mb-3">
    <div class="row align-items-md-center text-secondary small gy-2 gy-md-0">

        <div class="col-12 col-md-3 d-flex justify-content-between align-items-center d-md-block">
            <div>
                <span class="fw-bold fs-6" style="color: #0A2540;">
                    {{ $reservation->user->first_name ?? 'Guest' }} {{ $reservation->user->last_name ?? '' }}
                </span>
                <span
                    class="ms-1 text-muted d-none d-md-inline">#RM{{ str_pad($reservation->id, 3, '0', STR_PAD_LEFT) }}</span>
            </div>

            <div class="d-block d-md-none">
                @if ($reservation->status === 'confirmed')
                    <div class="d-flex gap-2">
                        <form id="mb-completeForm-{{ $reservation->id }}"
                            action="{{ route('restaurant.reservations.update_status', $reservation->id) }}"
                            method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="completed">
                            <button type="button"
                                class="btn btn-sm text-white fw-bold d-flex align-items-center justify-content-center"
                                style="background-color: #0A2540 !important; border-radius: 6px; width: 90px; height: 32px; padding: 0;"
                                onclick="openCompleteModal('{{ $reservation->user->first_name ?? 'Guest' }}', 'mb-completeForm-{{ $reservation->id }}')">
                                Complete
                            </button>
                        </form>
                        <form id="mb-cancelForm-{{ $reservation->id }}"
                            action="{{ route('restaurant.reservations.update_status', $reservation->id) }}"
                            method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="cancelled">
                            <button type="button"
                                class="btn btn-sm btn-list-decline fw-bold d-flex align-items-center justify-content-center"
                                style="width: 90px; height: 32px; padding: 0;"
                                onclick="openCancelModal('{{ $reservation->user->first_name ?? 'Guest' }}', 'mb-cancelForm-{{ $reservation->id }}')">
                                Cancel
                            </button>
                        </form>
                    </div>
                @elseif($reservation->status === 'pending')
                    <div class="d-flex gap-2">
                        <form id="mb-confirmForm-{{ $reservation->id }}"
                            action="{{ route('restaurant.reservations.update_status', $reservation->id) }}"
                            method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="confirmed">
                            <button type="button"
                                class="btn btn-sm btn-list-confirm fw-bold d-flex align-items-center justify-content-center"
                                style="width: 90px; height: 32px; padding: 0;"
                                onclick="openConfirmModal('{{ $reservation->user->first_name ?? 'Guest' }}', 'mb-confirmForm-{{ $reservation->id }}')">
                                Confirm
                            </button>
                        </form>

                        <form id="mb-declineForm-{{ $reservation->id }}"
                            action="{{ route('restaurant.reservations.update_status', $reservation->id) }}"
                            method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="cancelled">
                            <button type="button"
                                class="btn btn-sm btn-list-decline fw-bold d-flex align-items-center justify-content-center"
                                style="width: 90px; height: 32px; padding: 0;"
                                onclick="openDeclineModal('{{ $reservation->user->first_name ?? 'Guest' }}', 'mb-declineForm-{{ $reservation->id }}')">
                                Decline
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-12 col-md-2 text-md-center">
            <span
                class="text-muted d-inline d-md-none me-2">#RM{{ str_pad($reservation->id, 3, '0', STR_PAD_LEFT) }}</span>

            @if ($reservation->status === 'confirmed')
                <span class="badge bg-primary-subtle border border-primary-subtle px-2 py-1">confirmed</span>
            @elseif($reservation->status === 'pending')
                <span class="badge bg-warning-subtle border border-warning-subtle px-2 py-1">pending</span>
            @elseif($reservation->status === 'completed')
                <span class="badge bg-secondary-subtle border border-secondary-subtle px-2 py-1">completed</span>
            @elseif($reservation->status === 'cancelled')
                <span class="badge bg-danger-subtle border border-danger-subtle px-2 py-1">cancelled</span>
            @endif
        </div>

        <div class="col-12 col-md-3">
            <i class="fa-regular fa-calendar me-1"></i> {{ $reservation->reservation_date }}

            <span class="ms-2">
                <i class="fa-regular fa-clock me-1"></i>
                {{ date('H:i', strtotime($reservation->reservation_time)) }}
            </span>

            <span class="ms-2">
                <i class="fa-solid fa-chair me-1"></i>
                {{ $reservation->table->table_name ?? '-' }}
            </span>
        </div>

        <div class="col-12 col-md-2 text-md-center">
            <i class="fa-solid fa-users me-1"></i> {{ $reservation->num_of_people }} guests
        </div>

        <div class="col-md-2 text-end d-none d-md-block">
            @if ($reservation->status === 'confirmed')
                <div class="d-md-flex gap-2 justify-content-md-end">
                    <form id="pc-completeForm-{{ $reservation->id }}"
                        action="{{ route('restaurant.reservations.update_status', $reservation->id) }}" method="POST"
                        class="d-inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="completed">
                        <button type="button"
                            class="btn btn-sm text-white fw-bold d-flex align-items-center justify-content-center"
                            style="background-color: #0A2540 !important; border-radius: 6px; width: 90px !important; height: 32px !important; padding: 0 !important; border: none !important;"
                            onclick="openCompleteModal('{{ $reservation->user->first_name ?? 'Guest' }}', 'pc-completeForm-{{ $reservation->id }}')">
                            Complete
                        </button>
                    </form>

                    <form id="pc-cancelForm-{{ $reservation->id }}"
                        action="{{ route('restaurant.reservations.update_status', $reservation->id) }}" method="POST"
                        class="d-inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="button"
                            class="btn btn-sm btn-list-decline fw-bold d-flex align-items-center justify-content-center"
                            style="width: 90px !important; height: 32px !important; padding: 0 !important; min-width: 90px !important; max-width: 90px !important;"
                            onclick="openCancelModal('{{ $reservation->user->first_name ?? 'Guest' }}', 'pc-cancelForm-{{ $reservation->id }}')">
                            Cancel
                        </button>
                    </form>
                </div>
            @elseif($reservation->status === 'pending')
                <div class="d-md-flex gap-2 justify-content-md-end">
                    <form id="pc-confirmForm-{{ $reservation->id }}"
                        action="{{ route('restaurant.reservations.update_status', $reservation->id) }}" method="POST"
                        class="d-inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="confirmed">
                        <button type="button"
                            class="btn btn-sm btn-list-confirm fw-bold d-flex align-items-center justify-content-center"
                            style="width: 90px !important; height: 32px !important; padding: 0 !important; border: none !important; min-width: 90px !important; max-width: 90px !important;"
                            onclick="openConfirmModal('{{ $reservation->user->first_name ?? 'Guest' }}', 'pc-confirmForm-{{ $reservation->id }}')">
                            Confirm
                        </button>
                    </form>

                    <form id="pc-declineForm-{{ $reservation->id }}"
                        action="{{ route('restaurant.reservations.update_status', $reservation->id) }}"
                        method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="button"
                            class="btn btn-sm btn-list-decline fw-bold d-flex align-items-center justify-content-center"
                            style="width: 90px !important; height: 32px !important; padding: 0 !important; border: none !important; min-width: 90px !important; max-width: 90px !important;"
                            onclick="openDeclineModal('{{ $reservation->user->first_name ?? 'Guest' }}', 'pc-declineForm-{{ $reservation->id }}')">
                            Decline
                        </button>
                    </form>
                </div>
            @endif
        </div>

    </div>
</div>
