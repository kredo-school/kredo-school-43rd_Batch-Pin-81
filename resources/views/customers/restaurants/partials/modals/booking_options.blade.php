<div class="modal fade" id="bookingOptionsModal" tabindex="-1" aria-labelledby="guestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow position-relative" style="background-color: #fffefc; font-family: inter; color: #0a2540;">

            <button
                type="button"
                class="btn-close position-absolute top-0 end-0 m-3"
                data-bs-dismiss="modal"
                aria-label="Close">
            </button>

            <div class="modal-body text-center mt-5 mb-3 mx-3 bg-transparent">

                 <h5 class="mb-3">How would you like to continue?</h5>

                <p class="text-muted mb-4">
                    Create an account for faster bookings and to manage your reservations.
                </p>

                <a href="/register" class="btn custom-btn-a w-100 mb-3 fw-semibold">
                    Create Account
                </a>

                <a href="#" class="btn custom-btn-b w-100 mb-4 fw-semibold">
                    Continue as Guest
                </a>

                <p class="text-muted mb-0">
                    Already have an account?
                    <a href="/login" class="text-decoration-none fw-semibold login-link">
                        Log In 
                    </a>
                </p>

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

    .login-link{
        color: #0a2540;
        font-weight: 600;
        text-decoration: none;
    }

    .login-link:hover {
        text-decoration: underline !important;
    }
</style>