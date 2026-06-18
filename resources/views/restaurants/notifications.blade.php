@extends('layouts.restaurant')

@section('title', 'Notifications')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <div class="bg-light min-vh-100 pt-0 pb-4 pb-md-5">
        <div class="container pt-0" style="max-width: 800px;">
            
                <h2 class="fw-bold mb-4" style="color: #0A2540; font-size: 28px;">Notifications</h2>

            <div class="d-flex flex-column gap-3">
                
                {{-- 
                    💡 バックエンド実装時のループ処理オブジェクトの記述例
                    @foreach($notifications as $notification) 
                --}}

                <div class="card shadow-sm rounded-4 p-3 border-unread custom-notification-card bg-white">
                    <div class="d-flex align-items-start justify-content-between w-100">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3 shadow-sm border" style="width: 44px; height: 44px; min-width: 44px;">
                                <i class="fa-regular fa-calendar text-navy" style="font-size: 1.1rem;"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-navy" style="font-size: 0.95rem;">New Reservation</h6>
                                <p class="text-secondary mb-1" style="font-size: 0.85rem;">John Smith booked a table for 2 guests at 19:00</p>
                                <span class="text-muted small" style="font-size: 0.75rem;">5 minutes ago</span>
                            </div>
                        </div>
                        <span class="badge badge-new fw-bold px-2 py-1 rounded-3">New</span>
                    </div>
                </div>

                <div class="card shadow-sm rounded-4 p-3 border-unread custom-notification-card bg-white">
                    <div class="d-flex align-items-start justify-content-between w-100">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3 shadow-sm border" style="width: 44px; height: 44px; min-width: 44px;">
                                <i class="fa-regular fa-clock text-navy" style="font-size: 1.1rem;"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-navy" style="font-size: 0.95rem;">Customer Running Late</h6>
                                <p class="text-secondary mb-1" style="font-size: 0.85rem;">Maria Garcia will be 15 minutes late for 18:00 reservation</p>
                                <span class="text-muted small" style="font-size: 0.75rem;">10 minutes ago</span>
                            </div>
                        </div>
                        <span class="badge badge-new fw-bold px-2 py-1 rounded-3">New</span>
                    </div>
                </div>

                <div class="card shadow-sm rounded-4 p-3 border-light custom-notification-card bg-white">
                    <div class="d-flex align-items-start justify-content-between w-100">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3 shadow-sm border" style="width: 44px; height: 44px; min-width: 44px;">
                                <i class="fa-regular fa-circle-xmark text-secondary" style="font-size: 1.1rem;"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-navy" style="font-size: 0.95rem;">Reservation Canceled</h6>
                                <p class="text-secondary mb-1" style="font-size: 0.85rem;">David Lee canceled reservation for 4 guests</p>
                                <span class="text-muted small" style="font-size: 0.75rem;">1 hour ago</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm rounded-4 p-3 border-light custom-notification-card bg-white">
                    <div class="d-flex align-items-start justify-content-between w-100">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3 shadow-sm border" style="width: 44px; height: 44px; min-width: 44px;">
                                <i class="fa-regular fa-star text-secondary" style="font-size: 1.1rem;"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-navy" style="font-size: 0.95rem;">New Review</h6>
                                <p class="text-secondary mb-1" style="font-size: 0.85rem;">Sarah Johnson left a 5-star review</p>
                                <span class="text-muted small" style="font-size: 0.75rem;">2 hours ago</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 
                    @endforeach 
                --}}

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