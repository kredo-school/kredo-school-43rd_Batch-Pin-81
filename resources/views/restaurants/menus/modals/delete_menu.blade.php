<style>
    /*  カスタムモーダルのスタイル設定 */
    .modal-content-custom {
        border-radius: 16px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .btn-modal-delete {
        background-color: transparent !important;
        color: #bb2d3b !important;
        border: 1px solid #bb2d3b !important;
        border-radius: 8px;
        font-weight: bold;
    }

    .btn-modal-delete:hover {
        background-color: #bb2d3b !important;
        color: #ffffff !important;
    }
</style>


<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content modal-content-custom p-3">

            <form action="" method="POST" id="delete-menu-form">
                @csrf
                @method('DELETE')
                <div class="modal-body text-center">
                    <h5 class="fw-bold mb-3" style="color: #0A2540;">Are you sure?</h5>
                    <p class="text-muted small mb-4">Do you really want to delete this menu item? This action cannot be
                        undone.</p>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn w-50 btn-cancel" data-bs-dismiss="modal"
                            style="padding: 10px;">Cancel</button>
                        <button type="submit" class="btn w-50 btn-modal-delete" style="padding: 10px;">Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
