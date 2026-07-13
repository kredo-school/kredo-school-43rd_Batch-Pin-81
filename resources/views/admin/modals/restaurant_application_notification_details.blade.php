<div class="modal fade" id="restaurantApplicationNotificationModal-{{ $notification->id }}" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-xl">

        <div class="modal-content">

            {{-- Header --}}
            <div class="modal-header">
                <div>
                    <h4 class="modal-title mb-1">
                        Restaurant Application
                    </h4>
                    <small class="text-muted">
                        Review the application before approving or rejecting.
                    </small>
                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body">

                <div class="row">

                    {{-- Restaurant Info --}}
                    <div class="col-md-6">
                        <h5 class="border-bottom pb-2 mb-3">Restaurant Information</h5>

                        <table class="table table-borderless">

                            <tr>
                                <th>Restaurant</th>
                                <td>{{ $notification->restaurant?->restaurant_name }}</td>
                            </tr>

                            <tr>
                                <th>Address</th>
                                <td>{{ $notification->restaurant ? trim(($notification->restaurant->postal_code ?? '') . ' ' . ($notification->restaurant->prefecture ?? '') . ' ' . ($notification->restaurant->city ?? '') . ' ' . ($notification->restaurant->street_address_building ?? '')) : '-' }}
                                </td>
                            </tr>

                        </table>
                    </div>

                    {{-- Owner Info --}}
                    <div class="col-md-6">
                        <h5 class="border-bottom pb-2 mb-3">Applicant Information</h5>

                        <table class="table table-borderless">
                            <tr>
                                <th>Owner</th>
                                <td>{{ $notification->restaurant?->user?->name }}</td>
                            </tr>

                            <tr>
                                <th>Email</th>
                                <td>{{ $notification->restaurant?->user?->email }}</td>
                            </tr>

                            <tr>
                                <th>Phone</th>
                                <td>{{ $notification->restaurant?->phone }}</td>
                            </tr>

                            <tr>
                                <th>Applied</th>
                                <td>{{ $notification->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        </table>
                    </div>

                </div>

                <hr>

                <h5 class="mb-3">Application Message</h5>

                <div class="alert alert-light border">
                    {{ $notification->data['message'] }}
                </div>

                <h5 class="mt-4 mb-3">Business License</h5>

                @if ($notification->restaurant?->business_license)
                    <a href="{{ asset('storage/' . $notification->restaurant->business_license) }}" target="_blank"
                        class="btn btn-outline-primary">
                        View Business License PDF
                    </a>
                @else
                    <span class="text-muted">No business license uploaded.</span>
                @endif

            </div>

            {{-- Footer --}}
            <div class="modal-footer">

                @if ($notification->restaurant_status === \App\Models\Restaurant::STATUS_PENDING)

                    <form method="POST" action="{{ route('admin.restaurants.reject', $notification->restaurant->id) }}"
                        class="me-3">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="notification_id" value="{{ $notification->id }}">

                        <button class="btn btn-danger">
                            Reject
                        </button>
                    </form>

                    <form method="POST"
                        action="{{ route('admin.restaurants.approve', $notification->restaurant->id) }}">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="notification_id" value="{{ $notification->id }}">

                        <button class="btn btn-success">
                            Approve
                        </button>
                    </form>
                @else
                    <div class="w-100">
                        <div
                            class="alert mb-0
                            @if ($notification->restaurant_status === \App\Models\Restaurant::STATUS_APPROVED) alert-success
                            @elseif($notification->restaurant_status === \App\Models\Restaurant::STATUS_REJECTED)
                                alert-danger @endif">

                            @if ($notification->restaurant_status === \App\Models\Restaurant::STATUS_APPROVED)
                                <strong>Application Approved</strong><br>
                                This restaurant application has been approved.
                            @elseif($notification->restaurant_status === \App\Models\Restaurant::STATUS_REJECTED)
                                <strong>Application Rejected</strong><br>
                                This restaurant application has been rejected.
                            @endif

                        </div>
                    </div>

                @endif


            </div>

        </div>
    </div>
</div>
