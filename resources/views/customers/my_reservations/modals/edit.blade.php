<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="font-family: inter; background-color: #fffefc; color:#0a2540;">

            <form action="#" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold" id="editModalLabel">
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
                            name="date"
                            class="form-control input-box"
                            value="{{ date('Y-m-d') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Time
                        </label>

                        <input
                            type="time"
                            name="time"
                            class="form-control input-box"
                            value="19:00">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Number of Guests
                        </label>

                        <select
                            name="guests"
                            class="form-select input-box">
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}">
                                    {{ $i }} {{ $i == 1 ? 'Guest' : 'Guests' }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold">
                            Special Requests
                        </label>

                        <textarea
                            name="notes"
                            rows="3"
                            class="form-control input-box"
                            placeholder="Optional"></textarea>
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