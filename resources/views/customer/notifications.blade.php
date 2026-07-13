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
                                                <i class="fa-solid fa-calendar-day {{ $isUnread ? 'style-pink-text' : 'style-pink-text-light' }}" style="font-size: 1.05rem;"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.95rem;">Reservation Submitted</h6>
                                                <p class="text-secondary mb-1" style="font-size: 0.85rem;">{{ data_get($notification->data, 'message', 'A reservation has been submitted.') }}</p>
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

                        @case('contact_reply')
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

                        @case('restaurant')
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
                                                                                        
                        {{-- @case('new_follower')
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
                                                    New Follower
                                                </h6>
                                                <p class="text-secondary mb-1" style="font-size: 0.85rem;">{{ data_get($notification->data, 'message', 'Followed you') }}</p>
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
                        @break --}}

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
@endsection