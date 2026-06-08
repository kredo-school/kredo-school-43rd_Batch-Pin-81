@extends('layouts.restaurant')

@section('title', 'Reservations')

@section('content')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        /* 日付の横のステータスフィルターボタン */
        .btn-status-filter {
            background-color: #FFF0F5 !important;
            color: #0A2540 !important;
            border: 1px solid #FFF0F5 !important;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            padding: 6px 16px;
            white-space: nowrap;
            transition: all 0.2s ease-in-out;
        }
        .btn-status-filter:hover {
            opacity: 0.85;
        }
   
        /* アクティブ（選択中）になったときのボタンスタイル */
        .btn-status-filter.active {
            background-color: #0A2540 !important;
            color: #ffffff !important;
            border-color: #0A2540 !important;
        }

        /* 予約カードオブジェクト */
        .reservation-card {
            background-color: #ffffff;
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            transition: transform 0.2s;
        }
        .reservation-card:hover {
            transform: translateY(-2px);
        }

        /* 日付入力オブジェクト */
        .datepicker-input {
            font-size: 13px;
            font-weight: bold;
            color: #0A2540;
            border: none;
            background-color: transparent;
            outline: none;
            cursor: pointer;
            width: 95px;
        }

        /* リスト内バッジの文字色をすべてネイビーに強制固定 */
        .badge {
            color: #0A2540 !important;
        }

        /* 各種ボタンのスタイル */
        .btn-list-confirm {
            background-color: #198754 !important;
            color: #ffffff !important;
            border: none !important;
            border-radius: 6px;
        }
        .btn-list-confirm:hover {
            background-color: #157347 !important;
        }

        .btn-list-decline {
            background-color: #dc3545 !important;
            color: #ffffff !important;
            border: none !important;
            border-radius: 6px;
        }
        .btn-list-decline:hover {
            background-color: #bb2d3b !important;
        }

        .btn-list-complete {
            background-color: #ffffff !important;
            color: #0A2540 !important;
            border: 1px solid #0A2540 !important;
            border-radius: 6px;
        }
        .btn-list-complete:hover {
            background-color: #0A2540 !important;
            color: #ffffff !important;
        }

        /* モーダルカスタムスタイル */
        .decline-modal-content {
            background-color: #ffffff !important;
            border: none !important;
            border-radius: 16px !important;
            padding: 10px;
        }
        .btn-modal-back {
            background-color: #0A2540 !important;
            color: #ffffff !important;
            border: none !important;
            border-radius: 8px !important;
            font-weight: bold;
        }
        .btn-modal-decline-confirm {
            background-color: transparent !important;
            border: 1px solid #dc3545 !important;
            color: #dc3545 !important;
            border-radius: 8px !important;
            font-weight: bold;
            transition: all 0.2s ease-in-out;
        }
        .btn-modal-decline-confirm:hover {
            background-color: #dc3545 !important;
            color: #ffffff !important;
        }

        /* スマホ版でステータスボタン群を1行で横スクロールさせる調整 */
        @media (max-width: 767.98px) {
            .scrollable-buttons {
                display: flex !important;
                flex-wrap: nowrap !important;
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch;
                padding-bottom: 5px;
            }
            .scrollable-buttons::-webkit-scrollbar {
                display: none;
            }
        }
    </style>

    <div class="container pb-2" style="max-width: 1140px;">
        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div class="d-flex align-items-center gap-3">
                <h2 class="fw-bold m-0" style="color: #0A2540; font-size: 28px;">Reservations</h2>
                <div class="d-flex align-items-center bg-white px-2 py-1 border border-light shadow-sm" style="border-radius: 8px; cursor: pointer; height: 38px;">
                    <i class="fa-regular fa-calendar text-secondary me-2 small"></i>
                    <input type="text" id="targetDatePicker" class="datepicker-input" value="2026/05/20" readonly>
                    <i class="fa-solid fa-chevron-down text-secondary small ms-1" style="font-size: 10px;"></i>
                </div>
            </div>

            <div class="nav gap-2 scrollable-buttons" id="statusFilterTab" role="tablist">
                <button class="btn btn-status-filter active" id="tab-all" data-bs-toggle="tab" data-bs-target="#panel-all" type="button" role="tab">All</button>
                <button class="btn btn-status-filter" id="tab-confirmed" data-bs-toggle="tab" data-bs-target="#panel-confirmed" type="button" role="tab">Confirmed</button>
                <button class="btn btn-status-filter" id="tab-pending" data-bs-toggle="tab" data-bs-target="#panel-pending" type="button" role="tab">Pending</button>
                <button class="btn btn-status-filter" id="tab-completed" data-bs-toggle="tab" data-bs-target="#panel-completed" type="button" role="tab">Completed</button>
                <button class="btn btn-status-filter" id="tab-cancelled" data-bs-toggle="tab" data-bs-target="#panel-cancelled" type="button" role="tab">Cancelled</button>
            </div>
        </div>

        <div class="tab-content" id="statusFilterContent">
            <div class="tab-pane fade show active" id="panel-all" role="tabpanel">
                
                <div class="reservation-card p-3 mb-3">
                    <div class="row align-items-md-center text-secondary small gy-2 gy-md-0">
                        <div class="col-12 col-md-3 d-flex justify-content-between align-items-center d-md-block">
                            <div>
                                <span class="fw-bold fs-6" style="color: #0A2540;">John Smith</span>
                                <span class="ms-1 text-muted d-none d-md-inline">#RM001</span>
                            </div>
                            <div class="d-block d-md-none">
                                <button class="btn btn-sm btn-list-complete fw-bold">Mark Complete</button>
                            </div>
                        </div>
                        <div class="col-12 col-md-2 text-md-center">
                            <span class="text-muted d-inline d-md-none me-2">#RM001</span>
                            <span class="badge bg-primary-subtle border border-primary-subtle px-2 py-1">confirmed</span>
                        </div>
                        <div class="col-12 col-md-3 text-md-center">
                            <i class="fa-regular fa-calendar me-1"></i> 2026-05-15
                            <span class="ms-2"><i class="fa-regular fa-clock me-1"></i> 17:00</span>
                        </div>
                        <div class="col-12 col-md-2 text-md-center">
                            <i class="fa-solid fa-users me-1"></i> 2 guests
                        </div>
                        <div class="col-md-2 text-end d-none d-md-block">
                            <button class="btn btn-sm btn-list-complete fw-bold">Mark Complete</button>
                        </div>
                    </div>
                </div>

                <div class="reservation-card p-3 mb-3">
                    <div class="row align-items-md-center text-secondary small gy-2 gy-md-0">
                        <div class="col-12 col-md-3 d-flex justify-content-between align-items-center d-md-block">
                            <div>
                                <span class="fw-bold fs-6" style="color: #0A2540;">Maria Garcia</span>
                                <span class="ms-1 text-muted d-none d-md-inline">#RM002</span>
                            </div>
                            <div class="d-flex gap-2 d-block d-md-none">
                                <button class="btn btn-sm btn-list-confirm fw-bold px-2">Confirm</button>
                                <button class="btn btn-sm btn-list-decline fw-bold px-2" onclick="openDeclineModal('Customer 2', '#RM002')">Decline</button>
                            </div>
                        </div>
                        <div class="col-12 col-md-2 text-md-center">
                            <span class="text-muted d-inline d-md-none me-2">#RM002</span>
                            <span class="badge bg-warning-subtle border border-warning-subtle px-2 py-1">pending</span>
                        </div>
                        <div class="col-12 col-md-3 text-md-center">
                            <i class="fa-regular fa-calendar me-1"></i> 2026-05-15
                            <span class="ms-2"><i class="fa-regular fa-clock me-1"></i> 18:00</span>
                        </div>
                        <div class="col-12 col-md-2 text-md-center">
                            <i class="fa-solid fa-users me-1"></i> 3 guests
                        </div>
                        <div class="col-md-2 text-end d-none d-md-block d-md-flex gap-2 justify-content-md-end">
                            <button class="btn btn-sm btn-list-confirm fw-bold px-3">Confirm</button>
                            <button class="btn btn-sm btn-list-decline fw-bold px-3" onclick="openDeclineModal('Customer 2', '#RM002')">Decline</button>
                        </div>
                    </div>
                </div>

                <div class="reservation-card p-3 mb-3">
                    <div class="row align-items-md-center text-secondary small gy-2 gy-md-0">
                        <div class="col-12 col-md-3">
                            <span class="fw-bold fs-6" style="color: #0A2540;">Michael Chen</span>
                            <span class="ms-1 text-muted d-none d-md-inline">#RM003</span>
                        </div>
                        <div class="col-12 col-md-2 text-md-center">
                            <span class="text-muted d-inline d-md-none me-2">#RM003</span>
                            <span class="badge bg-secondary-subtle border border-secondary-subtle px-2 py-1">completed</span>
                        </div>
                        <div class="col-12 col-md-3 text-md-center">
                            <i class="fa-regular fa-calendar me-1"></i> 2026-05-15
                            <span class="ms-2"><i class="fa-regular fa-clock me-1"></i> 19:00</span>
                        </div>
                        <div class="col-12 col-md-2 text-md-center">
                            <i class="fa-solid fa-users me-1"></i> 4 guests
                        </div>
                        <div class="col-md-2 text-end"></div>
                    </div>
                </div>

                <div class="reservation-card p-3 mb-3">
                    <div class="row align-items-md-center text-secondary small gy-2 gy-md-0">
                        <div class="col-12 col-md-3">
                            <span class="fw-bold fs-6" style="color: #0A2540;">Emma Wilson</span>
                            <span class="ms-1 text-muted d-none d-md-inline">#RM004</span>
                        </div>
                        <div class="col-12 col-md-2 text-md-center">
                            <span class="text-muted d-inline d-md-none me-2">#RM004</span>
                            <span class="badge bg-danger-subtle border border-danger-subtle px-2 py-1">cancelled</span>
                        </div>
                        <div class="col-12 col-md-3 text-md-center">
                            <i class="fa-regular fa-calendar me-1"></i> 2026-05-15
                            <span class="ms-2"><i class="fa-regular fa-clock me-1"></i> 20:00</span>
                        </div>
                        <div class="col-12 col-md-2 text-md-center">
                            <i class="fa-solid fa-users me-1"></i> 6 guests
                        </div>
                        <div class="col-md-2 text-end"></div>
                    </div>
                </div>

                <div class="reservation-card p-3 mb-3">
                    <div class="row align-items-md-center text-secondary small gy-2 gy-md-0">
                        <div class="col-12 col-md-3 d-flex justify-content-between align-items-center d-md-block">
                            <div>
                                <span class="fw-bold fs-6" style="color: #0A2540;">Lisa Anderson</span>
                                <span class="ms-1 text-muted d-none d-md-inline">#RM005</span>
                            </div>
                            <div class="d-block d-md-none">
                                <button class="btn btn-sm btn-list-complete fw-bold">Mark Complete</button>
                            </div>
                        </div>
                        <div class="col-12 col-md-2 text-md-center">
                            <span class="text-muted d-inline d-md-none me-2">#RM005</span>
                            <span class="badge bg-primary-subtle border border-primary-subtle px-2 py-1">confirmed</span>
                        </div>
                        <div class="col-12 col-md-3 text-md-center">
                            <i class="fa-regular fa-calendar me-1"></i> 2026-05-15
                            <span class="ms-2"><i class="fa-regular fa-clock me-1"></i> 17:00</span>
                        </div>
                        <div class="col-12 col-md-2 text-md-center">
                            <i class="fa-solid fa-users me-1"></i> 2 guests
                        </div>
                        <div class="col-md-2 text-end d-none d-md-block">
                            <button class="btn btn-sm btn-list-complete fw-bold">Mark Complete</button>
                        </div>
                    </div>
                </div>

            </div>

            <div class="tab-pane fade" id="panel-confirmed" role="tabpanel"></div>
            <div class="tab-pane fade" id="panel-pending" role="tabpanel"></div>
            <div class="tab-pane fade" id="panel-completed" role="tabpanel"></div>
            <div class="tab-pane fade" id="panel-cancelled" role="tabpanel"></div>
        </div>
    </div>

    <div class="modal fade" id="listDeclineConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
            <div class="modal-content decline-modal-content shadow-lg">
                <div class="modal-header border-0 d-flex justify-content-between align-items-center pb-0">
                    <h5 class="modal-title fw-bold" style="color: #0A2540;">Decline Reservation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 14px;"></button>
                </div>
                <div class="modal-body py-3">
                    <h6 class="fw-bold mb-2" style="font-size: 16px; color: #0A2540;">
                        Decline reservation for <span id="declineTargetName"></span>?
                    </h6>
                    <p class="text-secondary small mb-0" style="font-size: 14px;">
                        This will reject the customer's request (<span id="declineTargetId"></span>), and they will be notified.
                    </p>
                </div>
                <div class="modal-footer border-0 d-flex gap-2 pt-2">
                    <button type="button" class="btn btn-modal-back py-2 px-4" data-bs-dismiss="modal">Back</button>
                    <button type="button" class="btn btn-modal-decline-confirm py-2 flex-grow-1">Yes, Decline Reservation</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            flatpickr("#targetDatePicker", {
                dateFormat: "Y/m/d",
                defaultDate: "2026-05-20"
            });
        });

        function openDeclineModal(customerName, reservationId) {
            document.getElementById('declineTargetName').innerText = customerName;
            document.getElementById('declineTargetId').innerText = reservationId;
            
            var myModal = new bootstrap.Modal(document.getElementById('listDeclineConfirmModal'));
            myModal.show();
        }
    </script>

@endsection