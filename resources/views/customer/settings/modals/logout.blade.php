<div class="modal fade" id="logoutConfirmationModal" tabindex="-1" aria-labelledby="logoutConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="font-family: 'Inter', sans-serif; color: #0a2540; background-color: #fffefc">

            <div class="modal-header border-0">
                <h5 class="modal-title" id="logoutConfirmationModalLabel">
                    Log Out
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body text-center">
                <p class="mb-0" style="color: #0a2540;">
                    Are you sure you want to log out of your account?
                </p>
            </div>

            <div class="modal-footer border-0 justify-content-center gap-2">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>
                
                <form action="{{ route('logout')}}" method="POST" class="d-inline mb-0">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        Log Out
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>