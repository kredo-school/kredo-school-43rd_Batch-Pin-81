<div class="modal fade" id="addTableModal" tabindex="-1" aria-labelledby="addTableModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 px-4 pt-4 pb-2 position-relative">
                <h5 class="modal-title fw-bold text-dark" id="addTableModalLabel" style="font-size: 20px;">Add New Table</h5>
                <button type="button" class="btn-close position-absolute top-0 end-0 mt-4 me-4 shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('restaurant.tables.store') }}" method="POST" id="addTableForm">
                @csrf
                <input type="hidden" name="date" value="{{ $date }}">
                <input type="hidden" name="start_time" value="{{ $displayStartTime }}">

                <div class="modal-body px-4 py-2">
                    <div class="mb-3 mt-2">
                        <label for="addTableNameInput" class="form-label small fw-bold text-secondary text-uppercase" style="letter-spacing: 0.5px;">Table Name</label>
                        <input type="text" class="form-control rounded-3" id="addTableNameInput" name="name" placeholder="e.g. Table 5" required style="border-color: #cbd5e1; padding: 10px 12px;">
                    </div>

                    <div class="mb-2">
                        <label for="addTableCapacityInput" class="form-label small fw-bold text-secondary text-uppercase" style="letter-spacing: 0.5px;">Capacity (Seats)</label>
                        <input type="number" class="form-control rounded-3" id="addTableCapacityInput" name="capacity" min="1" max="50" placeholder="e.g. 4" required style="border-color: #cbd5e1; padding: 10px 12px;">
                    </div>
                </div>

                <div class="modal-footer border-0 px-4 pb-4 pt-3 d-flex gap-2">
                    <button type="button" class="btn btn-cancel-custom rounded-3 flex-grow-1 fw-bold" style="height: 44px; font-size: 14px;" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-add-custom rounded-3 flex-grow-1 fw-bold" style="height: 44px; font-size: 14px;">Add Table</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .btn.btn-add-custom { background-color: #FCE7F3 !important; color: #0A2540 !important; border: none !important; transition: all 0.2s ease-in-out; }
    .btn.btn-add-custom:hover { background-color: #fbcfe8 !important; color: #0A2540 !important; }
    .btn.btn-cancel-custom { color: #0A2540 !important; background-color: #ffffff !important; border: 1px solid #0A2540 !important; transition: all 0.2s ease-in-out; }
    .btn.btn-cancel-custom:hover { background-color: #0A2540 !important; color: #ffffff !important; }
</style>
