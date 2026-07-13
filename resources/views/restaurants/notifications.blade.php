@extends('layouts.restaurant')

@section('title', 'Notifications')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <div class="bg-light min-vh-100 pt-0 pb-4 pb-md-5">
        <div class="container pt-0" style="max-width: 800px;">
            
                <h2 class="fw-bold mb-4" style="color: #0A2540; font-size: 28px;">Notifications</h2>

            <div class="d-flex flex-column gap-3">
                @forelse($notifications as $notification)
                    @php
                        $type = $notification->data['type'] ?? '';
                        $isUnread = is_null($notification->read_at);
                        $notificationUrl = $notification->data['url'] ?? '#';
                    @endphp

                    @switch($type)
                        @case('new_reservation')
                            <a href="{{ $notificationUrl }}" class="text-decoration-none text-reset">
                                <div class="card shadow-sm rounded-4 p-3 custom-notification-card {{ $isUnread ? 'border-unread' : 'border-light' }} bg-white">
                                    <div class="d-flex align-items-start justify-content-between w-100">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3 shadow-sm border" style="width: 44px; height: 44px; min-width: 44px;">
                                                <i class="fa-regular fa-calendar-check text-success" style="font-size: 1.1rem;"></i>
                                            </div>
                                            <div>
                                                <div class="d-flex align-items-center">
                                                    <h6 class="fw-bold mb-1 text-navy" style="font-size: 0.95rem;">{{ $notification->data['title'] ?? 'New Reservation' }}</h6>
                                                    @if($isUnread)
                                                        <span class="badge bg-danger ms-2">New</span>
                                                    @endif
                                                </div>
                                                <p class="text-secondary mb-1" style="font-size: 0.85rem;">
                                                    {{ $notification->data['customer_name'] ?? 'A customer' }}
                                                    made a reservation for
                                                    <strong>{{ $notification->data['num_of_people'] ?? '-' }}</strong>
                                                    people.
                                                </p>
                                                <div class="small text-muted mb-1">
                                                    <div><strong>Date:</strong> {{ $notification->data['reservation_date'] ?? '-' }}</div>
                                                    <div><strong>Time:</strong> {{ $notification->data['reservation_time'] ?? '-' }}</div>
                                                    <div><strong>Code:</strong> {{ $notification->data['reservation_code'] ?? '-' }}</div>
                                                </div>
                                                <span class="text-muted small" style="font-size: 0.75rem;">{{ $notification->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            @break

                        @case('changed_reservation')
                            <a href="{{ $notificationUrl }}" class="text-decoration-none text-reset">
                                <div class="card shadow-sm rounded-4 p-3 custom-notification-card {{ $isUnread ? 'border-unread' : 'border-light' }} bg-white">
                                    <div class="d-flex align-items-start justify-content-between w-100">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3 shadow-sm border" style="width: 44px; height: 44px; min-width: 44px;">
                                                <i class="fa-regular fa-calendar-days text-primary" style="font-size: 1.1rem;"></i>
                                            </div>
                                            <div>
                                                <div class="d-flex align-items-center">
                                                    <h6 class="fw-bold mb-1 text-navy" style="font-size: 0.95rem;">{{ $notification->data['title'] ?? 'Reservation Updated' }}</h6>
                                                    @if($isUnread)
                                                        <span class="badge bg-danger ms-2">New</span>
                                                    @endif
                                                </div>
                                                <p class="text-secondary mb-1" style="font-size: 0.85rem;">
                                                    {{ $notification->data['customer_name'] ?? 'A customer' }}
                                                    changed a reservation.
                                                </p>
                                                <div class="small text-muted mb-1">
                                                    <div><strong>Date:</strong> {{ $notification->data['reservation_date'] ?? '-' }}</div>
                                                    <div><strong>Time:</strong> {{ $notification->data['reservation_time'] ?? '-' }}</div>
                                                    <div><strong>Code:</strong> {{ $notification->data['reservation_code'] ?? '-' }}</div>
                                                </div>
                                                <span class="text-muted small" style="font-size: 0.75rem;">{{ $notification->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            @break

                        @case('customer_running_late')
                            <a href="{{ $notificationUrl }}" class="text-decoration-none text-reset">
                                <div class="card shadow-sm rounded-4 p-3 custom-notification-card {{ $isUnread ? 'border-unread' : 'border-light' }} bg-white">
                                    <div class="d-flex align-items-start justify-content-between w-100">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3 shadow-sm border" style="width: 44px; height: 44px; min-width: 44px;">
                                                <i class="fa-regular fa-clock text-warning" style="font-size: 1.1rem;"></i>
                                            </div>
                                            <div>
                                                <div class="d-flex align-items-center">
                                                    <h6 class="fw-bold mb-1 text-navy" style="font-size: 0.95rem;">{{ $notification->data['title'] ?? 'Customer Running Late' }}</h6>
                                                    @if($isUnread)
                                                        <span class="badge bg-danger ms-2">New</span>
                                                    @endif
                                                </div>
                                                <p class="text-secondary mb-1" style="font-size: 0.85rem;">
                                                    {{ $notification->data['customer_name'] ?? 'A customer' }}
                                                    will be late for the reservation.
                                                    @if(!empty($notification->data['late_minutes']))
                                                        They are running {{ $notification->data['late_minutes'] }} minutes late.
                                                    @endif
                                                </p>
                                                <div class="small text-muted mb-1">
                                                    <div><strong>Date:</strong> {{ $notification->data['reservation_date'] ?? '-' }}</div>
                                                    <div><strong>Time:</strong> {{ $notification->data['reservation_time'] ?? '-' }}</div>
                                                    <div><strong>Code:</strong> {{ $notification->data['reservation_code'] ?? '-' }}</div>
                                                </div>
                                                <span class="text-muted small" style="font-size: 0.75rem;">{{ $notification->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            @break

                        @case('canceled_reservation')
                            <a href="{{ $notificationUrl }}" class="text-decoration-none text-reset">
                                <div class="card shadow-sm rounded-4 p-3 custom-notification-card {{ $isUnread ? 'border-unread' : 'border-light' }} bg-white">
                                    <div class="d-flex align-items-start justify-content-between w-100">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3 shadow-sm border" style="width: 44px; height: 44px; min-width: 44px;">
                                                <i class="fa-regular fa-circle-xmark text-secondary" style="font-size: 1.1rem;"></i>
                                            </div>
                                            <div>
                                                <div class="d-flex align-items-center">
                                                    <h6 class="fw-bold mb-1 text-navy" style="font-size: 0.95rem;">{{ $notification->data['title'] ?? 'Reservation Canceled' }}</h6>
                                                    @if($isUnread)
                                                        <span class="badge bg-danger ms-2">New</span>
                                                    @endif
                                                </div>
                                                <p class="text-secondary mb-1" style="font-size: 0.85rem;">
                                                    {{ $notification->data['customer_name'] ?? 'A customer' }}
                                                    canceled a reservation.
                                                </p>
                                                <div class="small text-muted mb-1">
                                                    <div><strong>Date:</strong> {{ $notification->data['reservation_date'] ?? '-' }}</div>
                                                    <div><strong>Time:</strong> {{ $notification->data['reservation_time'] ?? '-' }}</div>
                                                    <div><strong>Code:</strong> {{ $notification->data['reservation_code'] ?? '-' }}</div>
                                                </div>
                                                <span class="text-muted small" style="font-size: 0.75rem;">{{ $notification->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            @break

                        @default
                            <div class="card shadow-sm rounded-4 p-3 custom-notification-card {{ $isUnread ? 'border-unread' : 'border-light' }} bg-white">
                                <div class="d-flex align-items-start justify-content-between w-100">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3 shadow-sm border" style="width: 44px; height: 44px; min-width: 44px;">
                                            <i class="fa-regular fa-bell text-secondary" style="font-size: 1.1rem;"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1 text-navy" style="font-size: 0.95rem;">{{ $notification->data['title'] ?? 'Notification' }}</h6>
                                            <p class="text-secondary mb-1" style="font-size: 0.85rem;">{{ $notification->data['message'] ?? 'You have a new notification.' }}</p>
                                            <span class="text-muted small" style="font-size: 0.75rem;">{{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    @if($isUnread)
                                        <span class="badge bg-danger ms-2">New</span>
                                    @endif
                                </div>
                            </div>
                    @endswitch
                @empty
                    <div class="text-center py-5 text-muted">
                        No notifications.
                    </div>
                @endforelse

            </div>
        </div>
    </div>

    <style>
        .text-navy {
            color: #0a2540 !important;
        }

        /* 未読カード専用の枠線オブジェクト */
        .border-unread {
            border: 1px solid #0a2540 !important;
        }

        /* ピンクのNewマークバッジオブジェクト */
        .badge.badge-new {
            background-color: #FCE7F3 !important;
            color: #0a2540 !important;
            font-size: 0.75rem;
            letter-spacing: 0.3px;
        }

        /* 各通知カードオブジェクト全体のインタラクション効果 */
        .custom-notification-card {
            transition: all 0.2s ease-in-out;
        }
        .custom-notification-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(10, 37, 64, 0.08) !important;
        }
    </style>
@endsection