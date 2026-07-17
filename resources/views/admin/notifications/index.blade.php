@extends('layouts.admin')

@section('title', 'Notifications')

@section('content')

<h2 class="mb-4">Notifications</h2>

@forelse($notifications as $notification)

    @switch($notification->data['type'] ?? 'restaurant')

        {{-- =========================
            Restaurant Notification
        ========================== --}}
        @case('restaurant')

            <button
                type="button"
                class="notification-item {{ $notification->read_at ? 'is-read' : 'is-unread' }}"
                data-bs-toggle="modal"
                data-bs-target="#restaurantApplicationNotificationModal-{{ $notification->id }}"
                data-notification-id="{{ $notification->id }}"
                data-mark-read-url="{{ route('admin.notifications.read', $notification->id) }}"
                data-notification-read="{{ $notification->read_at ? 'true' : 'false' }}">

                <div class="notification-content px-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="mb-1">
                            {{ $notification->data['restaurant_name'] }}
                        </h5>

                        <span class="status-badge ms-auto
                            @if($notification->restaurant_status === \App\Models\Restaurant::STATUS_PENDING)
                                status-pending
                            @elseif($notification->restaurant_status === \App\Models\Restaurant::STATUS_APPROVED)
                                status-approved
                            @elseif($notification->restaurant_status === \App\Models\Restaurant::STATUS_REJECTED)
                                status-rejected
                            @elseif($notification->restaurant_status === \App\Models\Restaurant::STATUS_SUSPENDED)
                                status-suspended
                            @endif">

                            {{ ucfirst($notification->restaurant_status) }}

                        </span>

                    </div>

                    <div class="d-flex justify-content-between align-items-center">

                        <p class="mb-2 text-muted">
                            {{ $notification->data['message'] }}
                        </p>

                        <small class="text-muted">
                            {{ $notification->created_at->diffForHumans() }}
                        </small>

                    </div>

                </div>

            </button>

            @include('admin.modals.restaurant_application_notification_details', [
                'notification' => $notification
            ])

        @break


        {{-- =========================
            Contact Notification
        ========================== --}}
        @case('contact')

            <button
                type="button"
                class="notification-item {{ $notification->read_at ? 'is-read' : 'is-unread' }}"
                data-bs-toggle="modal"
                data-bs-target="#contactNotificationModal-{{ $notification->id }}"
                data-notification-id="{{ $notification->id }}"
                data-mark-read-url="{{ route('admin.notifications.read', $notification->id) }}"
                data-notification-read="{{ $notification->read_at ? 'true' : 'false' }}">

                <div class="notification-content px-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="mb-1">
                            {{ $notification->data['title'] }}
                        </h5>

                        <span class="status-badge status-pending ms-auto">
                            New Contact
                        </span>

                    </div>

                    <div class="d-flex justify-content-between align-items-center">

                        <p class="mb-2 text-muted">
                            {{ $notification->data['user_name'] }} sent a contact message.
                        </p>

                        <small class="text-muted">
                            {{ $notification->created_at->diffForHumans() }}
                        </small>

                    </div>

                </div>

            </button>

            @include('admin.modals.contact_notification_details', [
                'notification' => $notification
            ])

        @break


        {{-- =========================
            Restaurant Contact Notification
        ========================== --}}
        @case('restaurant_contact')

            <button
                type="button"
                class="notification-item {{ $notification->read_at ? 'is-read' : 'is-unread' }}"
                data-bs-toggle="modal"
                data-bs-target="#restaurantContactNotificationModal-{{ $notification->id }}"
                data-notification-id="{{ $notification->id }}"
                data-mark-read-url="{{ route('admin.notifications.read', $notification->id) }}"
                data-notification-read="{{ $notification->read_at ? 'true' : 'false' }}">

                <div class="notification-content px-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="mb-1">
                            {{ $notification->data['title'] }}
                        </h5>

                        <span class="status-badge status-pending ms-auto">
                            New Restaurant Contact
                        </span>

                    </div>

                    <div class="d-flex justify-content-between align-items-center">

                        <p class="mb-2 text-muted">
                            {{ $notification->data['restaurant_name'] }} sent a contact message.
                        </p>

                        <small class="text-muted">
                            {{ $notification->created_at->diffForHumans() }}
                        </small>

                    </div>

                </div>

            </button>

            @include('admin.modals.restaurant_contact_notification_details', [
                'notification' => $notification
            ])

        @break


        {{-- =========================
            Review Report Notification
        ========================== --}}
        @case('review_report')

            <button
                type="button"
                class="notification-item {{ $notification->read_at ? 'is-read' : 'is-unread' }}"
                data-bs-toggle="modal"
                data-bs-target="#reviewReportNotificationModal-{{ $notification->id }}"
                data-notification-id="{{ $notification->id }}"
                data-mark-read-url="{{ route('admin.notifications.read', $notification->id) }}"
                data-notification-read="{{ $notification->read_at ? 'true' : 'false' }}">

                <div class="notification-content px-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="mb-1">
                            {{ $notification->data['title'] }}
                        </h5>

                        <span class="status-badge status-rejected ms-auto">
                            Reported Review
                        </span>

                    </div>

                    <div class="d-flex justify-content-between align-items-center">

                        <p class="mb-2 text-muted">
                            {{ $notification->data['reported_by'] }} reported a review for
                            {{ $notification->data['restaurant_name'] ?? 'an unknown restaurant' }}.
                        </p>

                        <small class="text-muted">
                            {{ $notification->created_at->diffForHumans() }}
                        </small>

                    </div>

                </div>

            </button>

            @include('admin.modals.review_report_notification_details', [
                'notification' => $notification
            ])

        @break


        {{-- =========================
            Reservation Notification
            (Future)
        ========================== --}}
        {{-- @case('reservation')

            <button
                type="button"
                class="notification-item"
                data-bs-toggle="modal"
                data-bs-target="#reservationNotificationModal-{{ $notification->id }}">

                <div class="notification-content px-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="mb-1">
                            Reservation
                        </h5>

                        <span class="status-badge status-approved ms-auto">
                            New Reservation
                        </span>

                    </div>

                    <div class="d-flex justify-content-between align-items-center">

                        <p class="mb-2 text-muted">
                            {{ $notification->data['customer_name'] }}
                            booked
                            {{ $notification->data['restaurant_name'] }}
                        </p>

                        <small class="text-muted">
                            {{ $notification->created_at->diffForHumans() }}
                        </small>

                    </div>

                </div>

            </button> --}}

            {{-- Create this modal later --}}
            {{-- @include('admin.modals.reservation_notification_details', [
                'notification' => $notification
            ]) --}}

            {{-- @break --}}

    @endswitch


@empty

    <div class="alert alert-secondary text-center" style="margin-top: 70px">
        No notifications found.
    </div>

@endforelse

<style>

.notification-item{

    width:100%;

    display:flex;
    align-items:flex-start;

    gap:20px;

    padding:20px;

    border:1px solid #e9ecef;
    border-radius:14px;

    background:#fff;

    margin-bottom:15px;

    transition:.2s;

    text-align:left;
}

.notification-item.is-read{
    background:#f3f4f6;
    border-color:#d1d5db;
    box-shadow:none;
}

.notification-item.is-read:hover{
    transform:none;
    box-shadow:none;
}

.notification-item.is-read h5,
.notification-item.is-read p,
.notification-item.is-read small{
    color:#6b7280 !important;
}

.notification-item.is-read .status-badge{
    background:#e5e7eb;
    color:#4b5563;
}

.notification-item:hover{
    transform: translateY(-2px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
}

.notification-content{

    flex:1;
}

.notification-item h5{

    font-weight:600;
}

.notification-item p{

    margin-bottom:0;
}

.status-badge{
    display: inline-block;
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 0.85rem;
    font-weight: 600;
}

.status-pending{
    background: #dbeafe;
    color: #2563eb;
}

/* Approved */
.status-approved{
    background:#dcffd5;
    color:#5dea0c;
}

/* Rejected */
.status-rejected{
    background: #f3e8ff;
    color: #9333ea;
}

/* Suspended */
.status-suspended{
    background:#fee2e2;
    color:#dc2626;
}

.notification-item[data-notification-read="false"] {
    border-color: #cbd5e1;
}

.notification-item[data-notification-read="true"] {
    opacity: 0.92;
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        const unreadDot = document.getElementById('unread-notifications-dot');
        const notificationItems = document.querySelectorAll('.notification-item[data-mark-read-url]');

        const updateUnreadDot = () => {
            const hasUnreadNotifications = Array.from(notificationItems)
                .some((item) => item.dataset.notificationRead === 'false');

            if (!hasUnreadNotifications && unreadDot) {
                unreadDot.remove();
            }
        };

        notificationItems.forEach((item) => {
            const modalSelector = item.getAttribute('data-bs-target');
            if (!modalSelector) {
                return;
            }

            const modalElement = document.querySelector(modalSelector);
            if (!modalElement) {
                return;
            }

            modalElement.addEventListener('shown.bs.modal', async () => {
                if (item.dataset.notificationRead === 'true') {
                    return;
                }

                try {
                    const response = await fetch(item.dataset.markReadUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                    });

                    if (!response.ok && response.status !== 204) {
                        return;
                    }

                    item.dataset.notificationRead = 'true';
                    item.classList.remove('is-unread');
                    item.classList.add('is-read');
                    updateUnreadDot();
                } catch (error) {
                    console.error('Unable to mark notification as read.', error);
                }
            }, { once: true });
        });

        updateUnreadDot();
    });
</script>

@endsection

