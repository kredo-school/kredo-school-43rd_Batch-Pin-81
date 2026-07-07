@extends('layouts.admin')

@section('title', 'Notifications')

@section('content')

<h2 class="mb-4">Notifications</h2>

@forelse($notifications as $notification)

<button
    type="button"
    class="notification-item"
    data-bs-toggle="modal"
    data-bs-target="#notificationModal-{{ $notification->id }}">

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

@include('admin.modals.notification_details', [
    'notification' => $notification
])

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
</style>

@endsection

