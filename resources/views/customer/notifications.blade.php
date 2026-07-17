@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <div class="bg-light min-vh-100 py-4 py-md-5">
        <div class="container" style="max-width: 700px;">

            <h2 class="fw-bold mb-4" style="color: #2b2b2b; font-size: 28px;">Notifications</h2>

            <div class="d-flex flex-column gap-3">
                @forelse($notifications as $notification)
                    @php
                        $isUnread = is_null($notification->read_at);
                    @endphp

                    @switch($notification->data['type'] ?? '')

                        @case('reservation')
                            <a href="{{ route('customer.notifications.read', $notification) }}" class="text-decoration-none text-reset">
                                <div class="card shadow-sm rounded-4 p-3 custom-notification-card bg-white {{ $isUnread ? 'border-unread' : 'border-light' }}">
                                    <div class="d-flex align-items-start justify-content-between w-100">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm border border-pink"
                                                style="width: 44px; height: 44px; min-width: 44px; background-color: #FCE7F3;">
                                                @if(data_get($notification->data, 'status') === 'confirmed')
                                                    <i class="fa-solid fa-calendar-check {{ $isUnread ? 'style-pink-text' : 'style-pink-text-light' }}" style="font-size: 1.05rem;"></i>
                                                @else
                                                    <i class="fa-solid fa-calendar-day {{ $isUnread ? 'style-pink-text' : 'style-pink-text-light' }}" style="font-size: 1.05rem;"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.95rem;">{{ data_get($notification->data, 'title', 'Reservation Update') }}</h6>
                                                <p class="text-secondary mb-1" style="font-size: 0.85rem;">{{ data_get($notification->data, 'message', 'Your reservation has been updated.') }}</p>
                                                <span class="text-muted small" style="font-size: 0.75rem;">{{ $notification->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>

                                        @if($isUnread)
                                            <span class="badge badge-new fw-bold px-2 py-1 rounded-3">New</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @break

                        @case('reservation_canceled')
                            <button
                                type="button"
                                class="w-100 border-0 bg-transparent p-0 text-start"
                                onclick="openReservationCancelledModal(this)"
                                data-read-url="{{ route('customer.notifications.read', $notification) }}"
                            >
                                <div class="card shadow-sm rounded-4 p-3 custom-notification-card bg-white {{ $isUnread ? 'border-unread' : 'border-light' }}">
                                    <div class="d-flex align-items-start justify-content-between w-100">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm border border-pink"
                                                style="width: 44px; height: 44px; min-width: 44px; background-color: #FCE7F3;">
                                                <i class="fa-solid fa-calendar-xmark {{ $isUnread ? 'style-pink-text' : 'style-pink-text-light' }}" style="font-size: 1.05rem;"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.95rem;">{{ data_get($notification->data, 'title', 'Reservation has been cancelled') }}</h6>
                                                <p class="text-secondary mb-1" style="font-size: 0.85rem;">{{ data_get($notification->data, 'message', 'Your reservation has been cancelled by the restaurant.') }}</p>
                                                <span class="text-muted small" style="font-size: 0.75rem;">{{ $notification->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>

                                        @if($isUnread)
                                            <span class="badge badge-new fw-bold px-2 py-1 rounded-3">New</span>
                                        @endif
                                    </div>
                                </div>
                            </button>
                        @break

                        @case('contact_reply')
                        @case('restaurant_contact_reply')
                            <a href="{{ route('customer.notifications.read', $notification) }}" class="text-decoration-none text-reset">
                                <div class="card shadow-sm rounded-4 p-3 custom-notification-card bg-white {{ $isUnread ? 'border-unread' : 'border-light' }}">
                                    <div class="d-flex align-items-start justify-content-between w-100">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm border border-pink"
                                                style="width: 44px; height: 44px; min-width: 44px; background-color: #FCE7F3;">
                                                <i class="fa-solid fa-envelope {{ $isUnread ? 'style-pink-text' : 'style-pink-text-light' }}" style="font-size: 1.05rem;"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.95rem;">New Reply</h6>
                                                <p class="text-secondary mb-1" style="font-size: 0.85rem;">{{ data_get($notification->data, 'message', 'You have a new reply.') }}</p>
                                                @if(data_get($notification->data, 'reply'))
                                                    <div class="small text-secondary mt-2 p-2 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                                        <div class="fw-semibold mb-1">Reply</div>
                                                        <div>{{ data_get($notification->data, 'reply') }}</div>
                                                    </div>
                                                @endif
                                                <span class="text-muted small" style="font-size: 0.75rem;">{{ $notification->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>

                                        @if($isUnread)
                                            <span class="badge badge-new fw-bold px-2 py-1 rounded-3">New</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @break

                        @case('restaurant_application') 
                            <a href="{{ route('customer.notifications.read', $notification) }}" class="text-decoration-none text-reset">
                                <div class="card shadow-sm rounded-4 p-3 custom-notification-card bg-white {{ $isUnread ? 'border-unread' : 'border-light' }}">
                                    <div class="d-flex align-items-start justify-content-between w-100">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm border border-pink"
                                                style="width: 44px; height: 44px; min-width: 44px; background-color: #FCE7F3;">
                                                @if(data_get($notification->data, 'status') === 'approved')
                                                    <i class="fa-solid fa-house-circle-check {{ $isUnread ? 'style-pink-text' : 'style-pink-text-light' }}" style="font-size: 1.05rem;"></i>
                                                @else
                                                    <i class="fa-solid fa-house-circle-xmark {{ $isUnread ? 'style-pink-text' : 'style-pink-text-light' }}" style="font-size: 1.05rem;"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.95rem;">
                                                    Restaurant {{ ucfirst((string) data_get($notification->data, 'status', 'Update')) }}
                                                </h6>
                                                <p class="text-secondary mb-1" style="font-size: 0.85rem;">{{ data_get($notification->data, 'message', 'Your restaurant application has been updated.') }}</p>
                                                <span class="text-muted small" style="font-size: 0.75rem;">{{ $notification->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>

                                        @if($isUnread)
                                            <span class="badge badge-new fw-bold px-2 py-1 rounded-3">New</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @break
                                                                                        
                        @case('new_follower')
                            <a href="{{ route('customer.notifications.read', $notification) }}" class="text-decoration-none text-reset">
                                <div class="card shadow-sm rounded-4 p-3 custom-notification-card bg-white {{ $isUnread ? 'border-unread' : 'border-light' }}">
                                    <div class="d-flex align-items-start justify-content-between w-100">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm border border-pink"
                                                style="width: 44px; height: 44px; min-width: 44px; background-color: #FCE7F3;">
                                                @if(data_get($notification->data, 'profile_photo'))
                                                    <img src="{{ asset('storage/' . data_get($notification->data, 'profile_photo')) }}"
                                                        alt="Follower profile photo"
                                                        class="rounded-circle"
                                                        style="width: 44px; height: 44px; object-fit: cover;">
                                                @else
                                                    <i class="fa-solid fa-user-plus {{ $isUnread ? 'style-pink-text' : 'style-pink-text-light' }}" style="font-size: 1.05rem;"></i>
                                                @endif
                                            </div>

                                            <div>
                                                <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.95rem;">
                                                    {{ data_get($notification->data, 'title', 'New Follower') }}
                                                </h6>
                                                <p class="text-secondary mb-1" style="font-size: 0.85rem;">
                                                    {{ data_get($notification->data, 'follower_name', 'A user') }}
                                                    {{ data_get($notification->data, 'message', 'started following you.') }}
                                                </p>
                                                <span class="text-muted small" style="font-size: 0.75rem;">{{ $notification->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>

                                        @if($isUnread)
                                            <span class="badge badge-new fw-bold px-2 py-1 rounded-3">New</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @break

                        @case('post_liked')
                            <a href="{{ route('customer.notifications.read', $notification) }}" class="text-decoration-none text-reset">
                                <div class="card shadow-sm rounded-4 p-3 custom-notification-card bg-white {{ $isUnread ? 'border-unread' : 'border-light' }}">
                                    <div class="d-flex align-items-start justify-content-between w-100">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm border border-pink"
                                                style="width: 44px; height: 44px; min-width: 44px; background-color: #FCE7F3;">
                                                @if(data_get($notification->data, 'profile_photo'))
                                                    <img src="{{ asset('storage/' . data_get($notification->data, 'profile_photo')) }}"
                                                        alt="Liker profile photo"
                                                        class="rounded-circle"
                                                        style="width: 44px; height: 44px; object-fit: cover;">
                                                @else
                                                    <i class="fa-solid fa-heart {{ $isUnread ? 'style-pink-text' : 'style-pink-text-light' }}" style="font-size: 1.05rem;"></i>
                                                @endif
                                            </div>

                                            <div>
                                                <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.95rem;">
                                                    {{ data_get($notification->data, 'title', 'Someone liked your post') }}
                                                </h6>
                                                <p class="text-secondary mb-1" style="font-size: 0.85rem;">{{ data_get($notification->data, 'message', 'Your post received a new like.') }}</p>
                                                <span class="text-muted small" style="font-size: 0.75rem;">{{ $notification->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>

                                        @if($isUnread)
                                            <span class="badge badge-new fw-bold px-2 py-1 rounded-3">New</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @break

                        @default
                            <a href="{{ route('customer.notifications.read', $notification) }}" class="text-decoration-none text-reset">
                                <div class="card shadow-sm rounded-4 p-3 custom-notification-card bg-white {{ $isUnread ? 'border-unread' : 'border-light' }}">
                                    <div class="d-flex align-items-start justify-content-between w-100">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm border border-pink"
                                                style="width: 44px; height: 44px; min-width: 44px; background-color: #FCE7F3;">
                                                <i class="fa-regular fa-bell {{ $isUnread ? 'style-pink-text' : 'style-pink-text-light' }}" style="font-size: 1.05rem;"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.95rem;">{{ data_get($notification->data, 'title', 'Notification') }}</h6>
                                                <p class="text-secondary mb-1" style="font-size: 0.85rem;">{{ data_get($notification->data, 'message', 'You have a new notification.') }}</p>
                                                <span class="text-muted small" style="font-size: 0.75rem;">{{ $notification->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>

                                        @if($isUnread)
                                            <span class="badge badge-new fw-bold px-2 py-1 rounded-3">New</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @break

                    @endswitch
                @empty
                    <div class="text-center py-5 text-muted">
                        No notifications.
                    </div>
                @endforelse

            </div>
        </div>
    </div>

    <div class="modal fade" id="reservationCancelledModal" tabindex="-1" aria-labelledby="reservationCancelledModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg" style="border: none; border-radius: 16px;">
                <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">
                    <div>
                        <h5 class="modal-title fw-bold" id="reservationCancelledModalLabel" style="color: #0A2540;">Reservation has been cancelled</h5>
                        <p class="text-muted small mb-0">We’re sorry for the inconvenience.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body pt-3" style="color: #0A2540;">
                    <p class="mb-3">
                        Your reservation was cancelled by the restaurant due to:
                    </p>

                    <ul class="mb-3 ps-3">
                        <li>Kitchen or building emergencies</li>
                        <li>Double-booked tables</li>
                    </ul>

                    <p class="mb-0 text-secondary">
                        We apologize for the inconvenience and appreciate your understanding.
                    </p>
                </div>

                <div class="modal-footer" style="border-top: none; padding-top: 0;">
                    <a href="{{ route('customer.search') }}" class="btn btn-sm text-white" style="background-color: #0A2540; border-radius: 8px;">Find another Restaurant</a>
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">Close</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* 💡 未読カードオブジェクト専用の枠線オブジェクトを、レストランと同じ薄ピンク（#FCE7F3）に変更 */
        .border-unread {
            border: 1px solid #FCE7F3 !important;
        }

        /* アイコンのサイズ */
        .fa-solid{
            font-size: 1.1rem;
        }

        /* 💡 アイコンオブジェクトの丸枠オブジェクトの線も優しくフィットするように設定 */
        .border-pink {
            border-color: #FBC4D6 !important;
        }

        /* 💡 目立たせたい未読アイコンオブジェクト用の濃いめのピンク文字色オブジェクト */
        .style-pink-text {
            color: #E91E63 !important;
        }

        /* 💡 既読アイコンオブジェクト用のなじむピンク文字色オブジェクト */
        .style-pink-text-light {
            color: #FBC4D6 !important;
        }

        /* 💡 ピンクのNewマークバッジオブジェクト（背景#FCE7F3、文字色を読みやすいピンクグレーに変更） */
        .badge.badge-new {
            background-color: #FCE7F3 !important;
            color: #0a2540 !important;
            font-size: 0.75rem;
            letter-spacing: 0.3px;
        }

        /* 各通知カードオブジェクト全体のホバーインタラクション効果 */
        .custom-notification-card {
            transition: all 0.2s ease-in-out;
        }
        .custom-notification-card:hover {
            transform: translateY(-1px);
            /* 💡 ホバー時の影のトーンオブジェクトもネイビーからピンクグレー寄りに修正 */
            box-shadow: 0 6px 15px rgba(194, 24, 91, 0.06) !important;
        }
    </style>

    <script>
        function openReservationCancelledModal(trigger) {
            const readUrl = trigger?.dataset?.readUrl;

            if (readUrl) {
                fetch(readUrl, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                }).catch(() => {});
            }

            const modalElement = document.getElementById('reservationCancelledModal');
            if (modalElement && window.bootstrap) {
                bootstrap.Modal.getOrCreateInstance(modalElement).show();
            }
        }
    </script>
@endsection