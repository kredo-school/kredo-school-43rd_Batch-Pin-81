<div class="modal fade"
     id="contactNotificationModal-{{ $notification->id }}"
     tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-envelope-fill me-2"></i>
                    New Contact Message
                </h5>

                <button class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <small class="text-muted">From</small>
                    <div class="fw-semibold">
                        {{ $notification->data['user_name'] }}
                    </div>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Title</small>
                    <div class="fw-semibold">
                        {{ $notification->data['title'] }}
                    </div>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Message Preview</small>

                    <div class="border rounded p-3 bg-light">
                        {{ Str::limit($notification->data['message'], 150) }}
                    </div>
                </div>

                @if(!empty($notification->data['has_attachments']))
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-paperclip me-2"></i>
                        This message includes one or more attached files.
                    </div>
                @endif

            </div>

            <div class="modal-footer">

                <button class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                    Close
                </button>

                <a href="{{ route('admin.contacts.index', $notification->data['contact_id']) }}"
                   class="btn btn-primary">
                    <i class="bi bi-eye me-1"></i>
                    Open Contact
                </a>

            </div>

        </div>
    </div>

</div>