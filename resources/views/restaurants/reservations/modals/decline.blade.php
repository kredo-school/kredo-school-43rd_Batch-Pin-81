<div class="modal fade" id="listDeclineConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content decline-modal-content shadow-lg">
            <div class="modal-header border-0 d-flex justify-content-between align-items-center pb-0">
                <h5 class="modal-title fw-bold" style="color: #0A2540;">Decline Reservation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 14px;"></button>
            </div>
            <div class="modal-body py-3">
                <h6 class="fw-bold mb-2" style="font-size: 16px; color: #0A2540;">
                    Decline reservation for <span id="declineTargetName"></span>?
                </h6>
                <p class="text-secondary small mb-0" style="font-size: 14px;">
                    This will reject the customer's request (<span id="declineTargetId"></span>), and they will be notified.
                </p>
            </div>
            <div class="modal-footer border-0 d-flex gap-2 pt-2">
                <button type="button" class="btn btn-modal-back py-2 px-4" data-bs-dismiss="modal">Back</button>
                <button type="button" class="btn btn-modal-decline-confirm py-2 flex-grow-1">Yes, Decline Reservation</button>
            </div>
        </div>
    </div>
</div>