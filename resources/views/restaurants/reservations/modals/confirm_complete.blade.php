<div class="modal fade" id="listConfirmActionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content decline-modal-content shadow-lg">
            <form id="modalConfirmForm" action="" method="POST" class="m-0">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="confirmed">

                <div class="modal-header border-0 d-flex justify-content-between align-items-center pb-0">
                    <h5 class="modal-title fw-bold" style="color: #0A2540;">Confirm Reservation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 14px;"></button>
                </div>
                <div class="modal-body py-3">
                    <h6 class="fw-bold mb-2" style="font-size: 16px; color: #0A2540;">
                        Accept reservation for <span id="confirmTargetName"></span>?
                    </h6>
                    <p class="text-secondary small mb-0" style="font-size: 14px;">
                        This will approve the customer's request.
                    </p>
                </div>
                <div class="modal-footer border-0 d-flex gap-2 pt-2">
                    <button type="button" class="btn btn-modal-back py-2 px-4" data-bs-dismiss="modal">Back</button>
                    <button type="submit" class="btn btn-modal-confirm-custom bg-transparent border border-success text-success py-2 flex-grow-1 fw-bold">
                        Yes, Confirm
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="listCompleteActionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content decline-modal-content shadow-lg">
            <form id="modalCompleteForm" action="" method="POST" class="m-0">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="completed">

                <div class="modal-header border-0 d-flex justify-content-between align-items-center pb-0">
                    <h5 class="modal-title fw-bold" style="color: #0A2540;">Complete Reservation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 14px;"></button>
                </div>
                <div class="modal-body py-3">
                    <h6 class="fw-bold mb-2" style="font-size: 16px; color: #0A2540;">
                        Mark <span id="completeTargetName"></span>'s reservation as Completed?
                    </h6>
                    <p class="text-secondary small mb-0" style="font-size: 14px;">
                        This will change the status to completed.
                    </p>
                </div>
                <div class="modal-footer border-0 d-flex gap-2 pt-2">
                    <button type="button" class="btn btn-modal-back py-2 px-4" data-bs-dismiss="modal">Back</button>
                    <button type="submit" class="btn btn-modal-complete-custom bg-transparent py-2 flex-grow-1 fw-bold">
                        Yes, Complete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="listCancelActionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content decline-modal-content shadow-lg">
            <form id="modalCancelForm" action="" method="POST" class="m-0">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="cancelled">

                <div class="modal-header border-0 d-flex justify-content-between align-items-center pb-0">
                    <h5 class="modal-title fw-bold" style="color: #0A2540;">Cancel Reservation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 14px;"></button>
                </div>
                <div class="modal-body py-3">
                    <h6 class="fw-bold mb-2" style="font-size: 16px; color: #0A2540;">
                        Cancel reservation for <span id="cancelTargetName"></span>?
                    </h6>
                    <p class="text-secondary small mb-0" style="font-size: 14px;">
                        This will cancel the confirmed reservation.
                    </p>
                </div>
                <div class="modal-footer border-0 d-flex gap-2 pt-2">
                    <button type="button" class="btn btn-modal-back py-2 px-4" data-bs-dismiss="modal">Back</button>
                    <button type="submit" class="btn btn-modal-cancel-custom bg-transparent border border-danger text-danger py-2 flex-grow-1 fw-bold">
                        Yes, Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="listDeclineActionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content decline-modal-content shadow-lg">
            <form id="modalDeclineForm" action="" method="POST" class="m-0">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="cancelled">

                <div class="modal-header border-0 d-flex justify-content-between align-items-center pb-0">
                    <h5 class="modal-title fw-bold" style="color: #0A2540;">Decline Reservation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 14px;"></button>
                </div>
                <div class="modal-body py-3">
                    <h6 class="fw-bold mb-2" style="font-size: 16px; color: #0A2540;">
                        Decline reservation for <span id="declineTargetName"></span>?
                    </h6>
                    <p class="text-secondary small mb-0" style="font-size: 14px;">
                        This will reject the customer's request.
                    </p>
                </div>
                <div class="modal-footer border-0 d-flex gap-2 pt-2">
                    <button type="button" class="btn btn-modal-back py-2 px-4" data-bs-dismiss="modal">Back</button>
                    <button type="submit" class="btn btn-modal-cancel-custom bg-transparent border border-danger text-danger py-2 flex-grow-1 fw-bold">
                        Yes, Decline
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Complete用
    function openCompleteModal(customerName, formId) {
        document.getElementById('completeTargetName').innerText = customerName;
        let actionUrl = document.getElementById(formId).getAttribute('action');
        document.getElementById('modalCompleteForm').setAttribute('action', actionUrl);

        let modalElement = document.getElementById('listCompleteActionModal');
        let myModal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
        myModal.show();
    }

    // Confirm用
    function openConfirmModal(customerName, formId) {
        document.getElementById('confirmTargetName').innerText = customerName;
        let actionUrl = document.getElementById(formId).getAttribute('action');
        document.getElementById('modalConfirmForm').setAttribute('action', actionUrl);

        let modalElement = document.getElementById('listConfirmActionModal');
        let myModal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
        myModal.show();
    }

    // Cancel用
    function openCancelModal(customerName, formId) {
        document.getElementById('cancelTargetName').innerText = customerName;
        let actionUrl = document.getElementById(formId).getAttribute('action');
        document.getElementById('modalCancelForm').setAttribute('action', actionUrl);

        let modalElement = document.getElementById('listCancelActionModal');
        let myModal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
        myModal.show();
    }

    // Decline用
    function openDeclineModal(customerName, formId) {
        document.getElementById('declineTargetName').innerText = customerName;
        let actionUrl = document.getElementById(formId).getAttribute('action');
        document.getElementById('modalDeclineForm').setAttribute('action', actionUrl);

        let modalElement = document.getElementById('listDeclineActionModal');
        let myModal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
        myModal.show();
    }
</script>

<style>
    .btn-modal-confirm-custom {
        border-radius: 8px !important;
        transition: all 0.2s ease-in-out !important;
    }
    .btn-modal-confirm-custom:hover {
        background-color: #198754 !important;
        color: #ffffff !important;
        border-color: #198754 !important;
        opacity: 1 !important;
    }

    .btn-modal-complete-custom {
        border: 1px solid #0A2540 !important;
        color: #0A2540 !important;
        border-radius: 8px !important;
        transition: all 0.2s ease-in-out !important;
    }
    .btn-modal-complete-custom:hover {
        background-color: #0A2540 !important;
        color: #ffffff !important;
        border-color: #0A2540 !important;
        opacity: 1 !important;
    }

    .btn-modal-cancel-custom {
        border-radius: 8px !important;
        transition: all 0.2s ease-in-out !important;
    }
    .btn-modal-cancel-custom:hover {
        background-color: #dc3545 !important;
        color: #ffffff !important;
        border-color: #dc3545 !important;
        opacity: 1 !important;
    }
</style>