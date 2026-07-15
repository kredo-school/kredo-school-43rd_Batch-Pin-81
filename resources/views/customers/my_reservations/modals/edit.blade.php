<div class="modal fade" id="editModal-{{ $booking->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $booking->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="font-family: inter; background-color: #fffefc; color:#0a2540;">

            <form action="{{ route('my_reservations.update', ['reservation' => $booking->id]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold" id="editModalLabel-{{ $booking->id }}">
                        Modify Reservation
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Date
                        </label>

                        <input
                            type="date"
                            name="reservation_date"
                            class="form-control input-box"
                            value="{{ old('reservation_date', $booking->date ?? $booking->reservation_date) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Time
                        </label>

                        <input
                            type="time"
                            name="reservation_time"
                            class="form-control input-box"
                            value="{{ old('reservation_time', $booking->time ?? $booking->reservation_time) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Number of Guests
                        </label>

                        <select
                            name="num_of_people"
                            class="form-select input-box">
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}" @selected(old('num_of_people', $booking->guests ?? $booking->num_of_people) == $i)>
                                    {{ $i }} {{ $i == 1 ? 'Guest' : 'Guests' }}
                                </option>
                            @endfor
                        </select>
                    </div>

                </div>

                <div class="modal-footer border-0 justify-content-center gap-2">

                    <button
                        type="button"
                        class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                        Discard Changes
                    </button>

                    <button
                        type="submit"
                        class="btn custom-btn-a fw-semibold">
                        Save Changes
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>

<style>
.input-box:focus {
    border-color: #cfb2c4;
    box-shadow: 0 0 0 0.2rem rgba(233, 192, 228, 0.25);
}

.custom-btn-a {
    background-color: #FCE7F3;
    color: #0a2540; /* text color */
    cursor: pointer;
    transition: 0.3s;
}

/* mouse hover effect */
.custom-btn-a:hover {
    background-color: #fdd6eb;
    color: #0a2a5e;
}
</style>