<style>
    .res-modal-content {
        background-color: #ffffff !important;
        border: none !important;
        border-radius: 16px !important;
        padding: 20px;
    }
    .res-modal-header {
        border-bottom: none !important;
        padding-bottom: 0 !important;
    }
    .res-modal-body {
        color: #0A2540 !important;
    }
    .res-modal-footer {
        border-top: none !important;
        padding-top: 10px !important;
    }

    .res-label {
        color: #6c757d;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 2px;
    }
    .res-value {
        color: #0A2540;
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 16px;
    }

    .btn-res-complete {
        background-color: #0A2540 !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 8px !important;
        font-weight: bold;
        padding: 12px;
        font-size: 15px;
        transition: all 0.2s ease-in-out;
    }
    .btn-res-complete:hover {
        opacity: 0.9 !important;
    }

    .btn-res-cancel-outline {
        background-color: transparent !important;
        border: 1px solid #DC3545 !important;
        color: #DC3545 !important;
        border-radius: 8px !important;
        font-weight: bold;
        padding: 12px;
        font-size: 15px;
        transition: all 0.2s ease-in-out;
    }
    .btn-res-cancel-outline:hover {
        background-color: #DC3545 !important;
        border-color: #DC3545 !important;
        color: #ffffff !important;
    }

    .btn-res-back {
        background-color: #0A2540 !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 8px !important;
        font-weight: bold;
        padding: 12px;
        font-size: 15px;
        transition: all 0.2s ease-in-out;
    }
    .btn-res-back:hover {
        opacity: 0.9 !important;
    }

    /* 枠線あり・ホバーで赤塗りつぶしのExecuteデザイン */
    .btn-res-execute-cancel-outline {
        background-color: transparent !important;
        border: 1px solid #DC3545 !important;
        color: #DC3545 !important;
        border-radius: 8px !important;
        font-weight: bold;
        padding: 12px;
        font-size: 15px;
        transition: all 0.2s ease-in-out;
    }
    .btn-res-execute-cancel-outline:hover {
        background-color: #DC3545 !important;
        color: #ffffff !important;
    }
</style>

<div class="modal fade" id="reservationDetailsModal" tabindex="-1" aria-labelledby="resDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
        <div class="modal-content res-modal-content shadow-lg">
            
            <div class="modal-header res-modal-header d-flex justify-content-between align-items-start">
                <div>
                    <h4 class="modal-title fw-bold" id="resDetailsModalLabel" style="color: #0A2540; font-family: 'Poppins', sans-serif;">Reservation Details</h4>
                    <p class="text-muted small mb-0" style="font-size: 14px;">Reservation ID: <span id="resIdDisplay" class="fw-medium">RM001</span></p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 14px;"></button>
            </div>
            
            <div id="resMainView" class="modal-body res-modal-body mt-3">
                <div class="row">
                    <div class="col-12">
                        <div class="res-label">Customer</div>
                        <div class="res-value" id="resCustomerDisplay">John Smith</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="res-label">Time</div>
                        <div class="res-value" id="resTimeDisplay">18:00</div>
                    </div>
                    <div class="col-6">
                        <div class="res-label">Duration</div>
                        <div class="res-value" id="resDurationDisplay">2 hours</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="res-label">Guests</div>
                        <div class="res-value" id="resGuestsDisplay">2</div>
                    </div>
                    <div class="col-6">
                        <div class="res-label">Table</div>
                        <div class="res-value" id="resTableDisplay">Table 1</div>
                    </div>
                </div>
            </div>

            <div id="resCancelConfirmView" class="modal-body res-modal-body mt-4 text-center d-none">
                <h5 class="fw-bold mb-2" style="font-size: 18px; color: #0A2540;">
                    Cancel reservation for <span id="resCancelCustomerDisplay">John Smith</span>?
                </h5>
                <p class="text-secondary small mb-0" style="font-size: 14px;">
                    This action cannot be undone. This table will be released immediately.
                </p>
            </div>
            
            <div class="modal-footer res-modal-footer d-flex gap-2">
                <div id="normalResActions" class="d-flex w-100 gap-2">
                    <button type="button" class="btn btn-res-complete flex-grow-1">Completed Visit</button>
                    <button type="button" class="btn btn-res-cancel-outline flex-grow-1" onclick="showResCancelConfirm()">Cancel Reservation</button>
                </div>
                
                <div id="completedResActions" class="d-flex w-100 d-none">
                    <button type="button" class="btn btn-res-complete flex-grow-1" data-bs-dismiss="modal">Close</button>
                </div>

                <div id="confirmResCancelActions" class="d-flex w-100 gap-2 d-none">
                    <button type="button" class="btn btn-res-back fw-bold py-2 px-4" onclick="hideResCancelConfirm()">Back</button>
                    <button type="button" class="btn btn-res-execute-cancel-outline fw-bold py-2 flex-grow-1">Yes, Cancel Reservation</button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function showResCancelConfirm() {
        var currentCustomer = document.getElementById('resCustomerDisplay').innerText;
        document.getElementById('resCancelCustomerDisplay').innerText = currentCustomer;

        document.getElementById('resDetailsModalLabel').innerText = 'Cancel Reservation';
        document.getElementById('resMainView').classList.add('d-none');
        document.getElementById('resCancelConfirmView').classList.remove('d-none');
        document.getElementById('normalResActions').classList.add('d-none');
        document.getElementById('confirmResCancelActions').classList.remove('d-none');
    }

    function hideResCancelConfirm() {
        document.getElementById('resDetailsModalLabel').innerText = 'Reservation Details';
        document.getElementById('resMainView').classList.remove('d-none');
        document.getElementById('resCancelConfirmView').classList.add('d-none');
        document.getElementById('confirmResCancelActions').classList.add('d-none');
        document.getElementById('normalResActions').classList.remove('d-none');
    }

    document.getElementById('reservationDetailsModal').addEventListener('hidden.bs.modal', function () {
        hideResCancelConfirm();
    });
</script>