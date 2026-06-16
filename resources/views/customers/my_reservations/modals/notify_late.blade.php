<div class="modal fade" id="NotifyLateModal" tabindex="-1" aria-labelledby="notifyLateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="font-family: inter; color:#0a2540; background-color: #fffefc">

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
                <p class="text-muted mb-0">
                    Would you like to notify the restaurant that you will be arriving late?
                </p>
            </div>

            <div class="modal-footer border-0 justify-content-center gap-2">

                
                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    data-bs-dismiss="modal">
                    No, I'll be there in time!
                </button>

                <form action="#"
                      method="POST"
                      class="d-inline">

                    @csrf

                    <button
                        type="submit"
                        class="btn custom-btn-a fw-semibold">
                        Yes, Notify Restaurant
                    </button>

                </form>

            </div>

        </div>
    </div>
</div>

<style>
    .btn-close:focus {
        box-shadow: 0 0 0 0.15rem rgba(0,0,0,.15) !important;
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

    /* login as a guest button */
    .custom-btn-b {
        background-color: transparent;
        color: #0a2540; /* text color */
        border: 1px solid #0a2540;
        cursor: pointer;
        transition: 0.3s;
    }

    /* mouse hover effect */
    .custom-btn-b:hover {
        background-color: #0a2540;
        color: white;
        border-color: #0a2540;
    }

</style>