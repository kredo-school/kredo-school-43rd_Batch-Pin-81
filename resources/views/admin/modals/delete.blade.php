<div class="modal fade"
     id="deleteModal{{ $id }}"
     tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Delete {{ $type ?? 'Item' }}
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">
                <strong>{{ $message }}</strong>
            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Cancel
                </button>

                <form action="{{ $route }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="btn btn-danger">
                        Delete
                    </button>

                </form>

            </div>

        </div>
    </div>
</div>