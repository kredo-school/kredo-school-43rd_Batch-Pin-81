<div class="modal fade" id="NotifyLateModal-{{ $booking->id }}" tabindex="-1"
    aria-labelledby="notifyLateModalLabel-{{ $booking->id }}" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="font-family: inter; color:#0a2540; background-color: #fffefc">

            <form action="#" method="POST" class="d-inline" id="notifyLateForm">
                @csrf

                <div class="modal-header border-0">
                    <h5 class="modal-title" id="notifyLateModalLabel">
                        Notify Restaurant
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>
                </div>

                <div class="modal-body text-center">
                    <p class="text-muted mb-3">
                        Would you like to notify the restaurant that you will be arriving late?
                    </p>

                    <div class="text-start">
                        <label for="lateMinutes" class="form-label fw-semibold">
                            Estimated delay
                        </label>

                        <select name="late_minutes" id="lateMinutes" class="form-select" required>
                            <option value="10">10 minutes</option>
                            <option value="15" selected>15 minutes</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer border-0 justify-content-center gap-2">
                    <button
                        type="button"
                        class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                        No, I'll be there in time!
                    </button>

                    <button type="submit" class="btn custom-btn-a fw-semibold">
                        Yes, Notify Restaurant
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const notifyLateModal = document.getElementById('NotifyLateModal');
        const notifyLateForm = document.getElementById('notifyLateForm');

        if (!notifyLateModal || !notifyLateForm) {
            return;
        }

        notifyLateModal.addEventListener('show.bs.modal', function (event) {
            const triggerButton = event.relatedTarget;

            if (!triggerButton) {
                return;
            }

            const actionUrl = triggerButton.getAttribute('data-notify-late-url');

            if (actionUrl) {
                notifyLateForm.action = actionUrl;
            }
        });
    });
</script>

<style>
    .btn-close:focus {
        box-shadow: 0 0 0 0.15rem rgba(0, 0, 0, .15) !important;
    }

    .custom-btn-a {
        background-color: #FCE7F3;
        color: #0a2540;
        cursor: pointer;
        transition: 0.3s;
    }

    .custom-btn-a:hover {
        background-color: #fdd6eb;
        color: #0a2a5e;
    }

    .custom-btn-b {
        background-color: transparent;
        color: #0a2540;
        border: 1px solid #0a2540;
        cursor: pointer;
        transition: 0.3s;
    }

    .custom-btn-b:hover {
        background-color: #0a2540;
        color: white;
        border-color: #0a2540;
    }
</style>
