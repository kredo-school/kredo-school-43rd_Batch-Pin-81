<div class="modal fade" id="reviewReportNotificationModal-{{ $notification->id }}" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h4 class="modal-title mb-1">
                        Review Reported
                    </h4>
                    <small class="text-muted">
                        Review the reported post before taking action.
                    </small>
                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="row g-4">

                    <div class="col-md-6">
                        <h5 class="border-bottom pb-2 mb-3">Review Details</h5>

                        <table class="table table-borderless mb-0">
                            <tr>
                                <th>Restaurant</th>
                                <td>{{ $notification->data['restaurant_name'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Reported By</th>
                                <td>{{ $notification->data['reported_by'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Rating</th>
                                <td>{{ $notification->data['rating'] ?? '-' }} / 5</td>
                            </tr>
                            <tr>
                                <th>Created</th>
                                <td>{{ $notification->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <h5 class="border-bottom pb-2 mb-3">Review Preview</h5>

                        <div class="alert alert-light border mb-3">
                            {{ $notification->data['message'] ?? 'A review has been reported.' }}
                        </div>

                        <div class="border rounded p-3 bg-light" style="min-height: 120px;">
                            {{ $notification->data['description'] ?? 'No preview available.' }}
                        </div>
                    </div>

                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Close
                </button>

                <a href="{{ $notification->data['url'] ?? route('admin.reviews', ['tab' => 'reported']) }}"
                    class="btn btn-danger">
                    {{ $notification->data['button_text'] ?? 'Open Reported Reviews' }}
                </a>
            </div>

        </div>
    </div>
</div>