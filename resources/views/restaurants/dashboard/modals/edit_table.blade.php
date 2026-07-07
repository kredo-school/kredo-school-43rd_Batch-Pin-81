<div class="modal fade" id="editTableModal" tabindex="-1" aria-labelledby="editTableModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 px-4 pt-4 pb-2 d-flex flex-column align-items-start position-relative">
                <div class="d-flex align-items-center justify-content-between w-100 pe-5">
                    <h5 class="modal-title fw-bold text-dark" id="editTableModalLabel" style="font-size: 20px; white-space: nowrap;">Edit Table</h5>

                    <div id="modalHeaderToggle" class="btn-group border rounded-3 p-1 bg-white" style="height: 32px;">
                        <button type="button" id="status-enable-btn" class="btn btn-sm fw-bold px-2 border-0 rounded-2 d-flex align-items-center" style="font-size: 11px;" onclick="toggleStatus('enable')">Active</button>
                        <button type="button" id="status-disable-btn" class="btn btn-sm fw-bold px-2 border-0 rounded-2 d-flex align-items-center" style="font-size: 11px;" onclick="toggleStatus('disable')">Inactive</button>
                    </div>
                    <input type="hidden" id="table-status-input" name="status" value="enable">
                </div>
                <p class="text-muted small mb-0 mt-1" id="editTableModalSub">Update table information</p>
                <button type="button" class="btn-close position-absolute top-0 end-0 mt-4 me-3 shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="#" method="POST" id="editTableForm">
                @csrf
                <input type="hidden" name="_method" id="editTableMethod" value="PUT">
                <input type="hidden" id="tableIdInput" value="">
                <input type="hidden" id="deleteTableAction" value="">
                <input type="hidden" name="date" value="{{ $date }}">
                <input type="hidden" name="start_time" value="{{ $displayStartTime }}">
                <input type="hidden" id="table-status-hidden" name="status" value="enable">

                <div id="modalMainFormView" class="modal-body px-4 py-2">
                    <div class="mb-3 mt-2">
                        <label for="tableNameInput" class="form-label small fw-bold text-secondary text-uppercase" style="letter-spacing: 0.5px;">Table Name</label>
                        <input type="text" class="form-control rounded-3" id="tableNameInput" name="name" required style="border-color: #cbd5e1; padding: 10px 12px;">
                    </div>

                    <div class="mb-2">
                        <label for="tableCapacityInput" class="form-label small fw-bold text-secondary text-uppercase" style="letter-spacing: 0.5px;">Capacity (Seats)</label>
                        <input type="number" class="form-control rounded-3" id="tableCapacityInput" name="capacity" min="1" max="50" required style="border-color: #cbd5e1; padding: 10px 12px;">
                    </div>
                </div>

                <div id="modalDisableConfirmView" class="modal-body px-4 py-4 d-none">
                    <div class="text-center">
                        <p class="text-dark fw-bold mb-1" style="font-size: 16px;">Are you sure you want to deactivate this table?</p>
                        <p class="text-secondary small mb-0">It will be hidden from available customer booking slots.</p>
                    </div>
                </div>

                <div id="modalDeleteConfirmView" class="modal-body px-4 py-4 d-none">
                    <div class="text-center">
                        <p class="text-dark fw-bold mb-1" style="font-size: 16px;">Are you sure you want to delete this table?</p>
                        <p class="text-danger small fw-medium mb-0">Tables with active reservations cannot be deleted.</p>
                    </div>
                </div>

                <div class="modal-footer border-0 px-4 pb-4 pt-2 d-flex flex-column gap-2">
                    <div class="d-flex w-100 gap-2">
                        <button type="button" id="btnDisableTable" class="btn btn-delete-custom rounded-3 flex-grow-1 fw-bold" style="height: 44px; font-size: 14px;" onclick="showConfirmView('delete')">Delete Table</button>
                        <button type="button" id="btnEnableTable" class="btn btn-outline-success border rounded-3 flex-grow-1 fw-bold d-none" style="height: 44px; font-size: 14px;" onclick="toggleStatus('enable')">Enable Table</button>
                        <button type="submit" id="btnSaveChanges" class="btn text-white rounded-3 flex-grow-1 fw-bold" style="background-color: #0A2540; height: 44px; font-size: 14px;">Save Changes</button>
                    </div>

                    <button type="button" id="btnCancelConfirm" class="btn text-white rounded-3 w-100 fw-bold d-none" style="background-color: #0A2540; height: 44px; font-size: 14px;" onclick="hideConfirmView()">Back to Edit</button>
                    <button type="submit" id="btnExecuteDisable" class="btn btn-warning text-dark w-100 fw-bold rounded-3 d-none" style="height: 44px;" onclick="toggleStatus('disable')">Confirm Deactivation</button>
                    <button type="submit" id="btnExecuteDelete" class="btn btn-delete-custom w-100 fw-bold rounded-3 d-none" style="height: 44px;" onclick="prepareTableDelete()">Delete Table</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .active-enable { background-color: #22c55e !important; color: white !important; box-shadow: 0 2px 4px rgba(34, 197, 94, 0.2); }
    .active-disable { background-color: #ef4444 !important; color: white !important; box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2); }
    .btn.btn-delete-custom { color: #ef4444 !important; background-color: #ffffff !important; border: 1px solid #ef4444 !important; transition: all 0.2s ease-in-out; }
    .btn.btn-delete-custom:hover { background-color: #ef4444 !important; color: #ffffff !important; }
</style>

<script>
    function toggleStatus(state) {
        const enableBtn = document.getElementById('status-enable-btn');
        const disableBtn = document.getElementById('status-disable-btn');
        const statusInput = document.getElementById('table-status-input');
        const statusHidden = document.getElementById('table-status-hidden');

        if (state === 'enable') {
            enableBtn?.classList.add('active-enable');
            disableBtn?.classList.remove('active-disable');
            if (statusInput) statusInput.value = 'enable';
            if (statusHidden) statusHidden.value = 'enable';
        } else {
            enableBtn?.classList.remove('active-enable');
            disableBtn?.classList.add('active-disable');
            if (statusInput) statusInput.value = 'disable';
            if (statusHidden) statusHidden.value = 'disable';
        }
    }

    function showConfirmView(type) {
        document.getElementById('modalHeaderToggle')?.classList.add('d-none');
        document.getElementById('modalMainFormView')?.classList.add('d-none');
        document.getElementById('btnSaveChanges')?.classList.add('d-none');
        document.getElementById('btnDisableTable')?.classList.add('d-none');
        document.getElementById('btnEnableTable')?.classList.add('d-none');
        document.getElementById('btnCancelConfirm')?.classList.remove('d-none');

        if (type === 'disable') {
            document.getElementById('editTableModalLabel').innerText = 'Disable Table';
            document.getElementById('editTableModalSub').innerText = 'Confirm table deactivation';
            document.getElementById('modalDisableConfirmView')?.classList.remove('d-none');
            document.getElementById('btnExecuteDisable')?.classList.remove('d-none');
        } else {
            document.getElementById('editTableModalLabel').innerText = 'Delete Table';
            document.getElementById('editTableModalSub').innerText = 'Confirm table deletion';
            document.getElementById('modalDeleteConfirmView')?.classList.remove('d-none');
            document.getElementById('btnExecuteDelete')?.classList.remove('d-none');
        }
    }

    function hideConfirmView() {
        document.getElementById('modalHeaderToggle')?.classList.remove('d-none');
        document.getElementById('editTableModalLabel').innerText = 'Edit Table';
        document.getElementById('editTableModalSub').innerText = 'Update table information';
        document.getElementById('modalDisableConfirmView')?.classList.add('d-none');
        document.getElementById('modalDeleteConfirmView')?.classList.add('d-none');
        document.getElementById('btnCancelConfirm')?.classList.add('d-none');
        document.getElementById('btnExecuteDisable')?.classList.add('d-none');
        document.getElementById('btnExecuteDelete')?.classList.add('d-none');
        document.getElementById('modalMainFormView')?.classList.remove('d-none');
        document.getElementById('btnSaveChanges')?.classList.remove('d-none');
    }

    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('editTableModal');
        if (modal) {
            modal.addEventListener('hidden.bs.modal', hideConfirmView);
        }
    });
</script>
