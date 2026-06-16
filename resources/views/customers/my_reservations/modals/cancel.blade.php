<div class="modal fade" id="CancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="font-family: inter; color:#0a2540; background-color: #fffefc">

            <div class="modal-header border-0">
                <h5 class="modal-title" id="cancelModalLabel">
                    Cancel Reservation
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close">
                </button>
            </div>

            <div class="modal-body text-center">
                <p class=" mb-0" style="color: #0a2540;">
                    Are you sure you want to cancel your reservation?
                </p>
            </div>

            <div class="modal-footer border-0 justify-content-center gap-2">
                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    data-bs-dismiss="modal">
                    Keep Reservation
                </button>

                <form action="#" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-danger">
                        Cancel Reservation
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

