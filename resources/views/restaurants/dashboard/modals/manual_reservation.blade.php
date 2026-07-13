<div class="modal fade" id="manualReservationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('restaurant.dashboard.manual_reservations.store') }}"
            class="modal-content border-0 rounded-4 shadow">
            @csrf
            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <div>
                    <h5 class="modal-title fw-bold text-navy">Add Reservation / Walk-in</h5>
                    <div class="small text-muted">Choose an available table for a phone booking or walk-in.</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold small">Booking type</label>
                        <select class="form-select" name="booking_source" id="manualBookingSource" required>
                            <option value="phone">Phone reservation</option>
                            <option value="walk_in">Walk-in</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Guest name <span
                                class="text-muted fw-normal">(Optional)</span></label>
                        <input type="text" class="form-control" name="guest_name" maxlength="255">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Phone <span
                                class="text-muted fw-normal">(Optional)</span></label>
                        <input type="text" class="form-control" name="phone_number" maxlength="50">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Guests</label>
                        <input type="number" min="1" max="50"
                            class="form-control manual-availability-input" name="num_of_people" id="manualGuests"
                            value="1" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Date</label>
                        <input type="date" class="form-control manual-availability-input" name="reservation_date"
                            id="manualDate" value="{{ $date }}" min="{{ now()->format('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Time</label>
                        <input type="time" step="900" class="form-control manual-availability-input"
                            name="reservation_time" id="manualTime" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold small">Available table</label>
                        <select class="form-select" name="table_id" id="manualTable" required disabled>
                            <option value="">Select date, time and guests first</option>
                        </select>
                        <div id="manualAvailabilityMessage" class="form-text"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer manual-reservation-actions border-0 px-4 pb-4 pt-2">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Back</button>
                <button type="submit" id="manualReservationSubmit" class="btn manual-reservation-submit px-4"
                    disabled>Add Reservation</button>
            </div>
        </form>
    </div>
</div>
