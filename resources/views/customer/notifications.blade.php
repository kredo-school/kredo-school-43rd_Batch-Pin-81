@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <div class="bg-light min-vh-100 py-4 py-md-5">
        <div class="container" style="max-width: 700px;">
            
            <h2 class="fw-bold mb-4" style="color: #2b2b2b; font-size: 28px;">Notifications</h2>

            <div class="d-flex flex-column gap-3">
                
                {{-- 
                    💡 バックエンド実装時のループ処理オブジェクトの記述例
                    @foreach($notifications as $notification) 
                --}}

                <div class="card shadow-sm rounded-4 p-3 border-unread custom-notification-card bg-white">
                    <div class="d-flex align-items-start justify-content-between w-100">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm border border-pink" style="width: 44px; height: 44px; min-width: 44px; background-color: #FCE7F3;">
                                <i class="fa-regular fa-circle-xmark style-pink-text" style="font-size: 1.1rem;"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.95rem;">Reservation Canceled</h6>
                                <p class="text-secondary mb-1" style="font-size: 0.85rem;">Sushi Jiro canceled your reservation for June 20, 19:00.</p>
                                <span class="text-muted small" style="font-size: 0.75rem;">10 minutes ago</span>
                            </div>
                        </div>
                        <span class="badge badge-new fw-bold px-2 py-1 rounded-3">New</span>
                    </div>
                </div>

                <div class="card shadow-sm rounded-4 p-3 border-unread custom-notification-card bg-white">
                    <div class="d-flex align-items-start justify-content-between w-100">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm border border-pink" style="width: 44px; height: 44px; min-width: 44px; background-color: #FCE7F3;">
                                <i class="fa-solid fa-user-plus style-pink-text" style="font-size: 1rem;"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.95rem;">New Follower</h6>
                                <p class="text-secondary mb-1" style="font-size: 0.85rem;">Mizutama started following you.</p>
                                <span class="text-muted small" style="font-size: 0.75rem;">1 hour ago</span>
                            </div>
                        </div>
                        <span class="badge badge-new fw-bold px-2 py-1 rounded-3">New</span>
                    </div>
                </div>

                <div class="card shadow-sm rounded-4 p-3 border-light custom-notification-card bg-white">
                    <div class="d-flex align-items-start justify-content-between w-100">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm border border-pink" style="width: 44px; height: 44px; min-width: 44px; background-color: #FFF5F9;">
                                <i class="fa-regular fa-thumbs-up style-pink-text-light" style="font-size: 1.1rem;"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.95rem;">Review Liked</h6>
                                <p class="text-secondary mb-1" style="font-size: 0.85rem;">Someone liked your review for Izakaya Sakura.</p>
                                <span class="text-muted small" style="font-size: 0.75rem;">Yesterday</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm rounded-4 p-3 border-light custom-notification-card bg-white">
                    <div class="d-flex align-items-start justify-content-between w-100">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm border border-pink" style="width: 44px; height: 44px; min-width: 44px; background-color: #FFF5F9;">
                                <i class="fa-regular fa-calendar-check style-pink-text-light" style="font-size: 1.1rem;"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.95rem;">Reservation Confirmed</h6>
                                <p class="text-secondary mb-1" style="font-size: 0.85rem;">Your reservation at Ramen Ichiran has been confirmed.</p>
                                <span class="text-muted small" style="font-size: 0.75rem;">3 days ago</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 
                    @endforeach 
                --}}

                <!-- For test -->
                @foreach($notifications as $notification) 

                 <div class="card shadow-sm rounded-4 p-3 border-unread custom-notification-card bg-white">
                    <div class="d-flex align-items-start justify-content-between w-100">

                        <div class="d-flex align-items-center">

                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm border border-pink"
                                style="width:44px;height:44px;min-width:44px;background-color:#FCE7F3;">

                                @if(($notification->data['status'] ?? '') === 'approved')
                                    <i class="fa-solid fa-circle-check text-success"></i>
                                @elseif(($notification->data['status'] ?? '') === 'rejected')
                                    <i class="fa-solid fa-circle-xmark text-danger"></i>
                                @else
                                    <i class="fa-regular fa-bell style-pink-text"></i>
                                @endif

                            </div> 

                            <div>

                                <h6 class="fw-bold mb-1 text-dark" style="font-size:0.95rem;">
                                    {{ $notification->data['title'] }}
                                </h6>

                                <p class="text-secondary mb-2" style="font-size:0.85rem;">
                                    {{ $notification->data['message'] }}
                                </p>

                                @if(isset($notification->data['url']))
                                    <a href="{{ $notification->data['url'] }}"
                                    class="btn btn-primary btn-sm mb-2">
                                        {{ $notification->data['button_text'] }}
                                    </a>
                                @endif

                                <span class="text-muted small">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>

                            </div>
                        </div>

                        @if(is_null($notification->read_at))
                            <span class="badge badge-new fw-bold px-2 py-1 rounded-3">
                                New
                            </span>
                        @endif

                    </div>
                </div>

@endforeach

            </div>
        </div>
    </div>

    <style>
        /* 💡 未読カードオブジェクト専用の枠線オブジェクトを、レストランと同じ薄ピンク（#FCE7F3）に変更 */
        .border-unread {
            border: 1px solid #FCE7F3 !important;
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