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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
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

        <div class="d-flex flex-column gap-3 mb-4">
            
            <div>
                <h2 class="fw-bold m-0 mb-2" style="color: #0A2540; font-size: 28px;">Reservations</h2>
            </div>
            
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <form action="{{ route('restaurant.reservations') }}" method="GET" class="d-flex align-items-center bg-white px-2 py-1 border border-light shadow-sm" style="border-radius: 8px; height: 38px; width: 180px; margin: 0;">
                        <i class="fa-solid fa-magnifying-glass text-secondary me-2 small"></i>
                        <input type="text" name="search_id" class="datepicker-input" style="width: 130px; cursor: text;" placeholder="Search ID (e.g. 003)" value="{{ request('search_id') }}">
                        @if(request('date'))
                            <input type="hidden" name="date" value="{{ request('date') }}">
                        @endif
                    </form>

                    <div class="d-flex align-items-center bg-white px-2 py-1 border border-light shadow-sm"
                        style="border-radius: 8px; cursor: pointer; height: 38px;">
                        <i class="fa-regular fa-calendar text-secondary me-2 small"></i>
                        <input type="text" id="targetDatePicker" class="datepicker-input"
                            value="{{ request('date') ? $selectedDate : 'All Days' }}" readonly>
                        <i class="fa-solid fa-chevron-down text-secondary small" style="font-size: 10px;"></i>
                    </div>

                    @if (request('date') || request('search_id'))
                        <a href="{{ route('restaurant.reservations') }}" class="btn btn-sm text-white border-0 d-flex align-items-center justify-content-center"
                            style="background-color: #0A2540 !important; border-radius: 8px; height: 38px; padding: 0 16px; font-weight: 500; font-size: 14px; transition: opacity 0.2s;"
                            onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                            <i class="fa-solid fa-rotate-left me-1 small"></i> Clear
                        </a>
                    @endif
                </div>

                <div class="nav gap-2 scrollable-buttons" id="statusFilterTab" role="tablist">
                    <button class="btn btn-status-filter active" id="tab-all" data-bs-toggle="tab" data-bs-target="#panel-all" type="button" role="tab">All</button>
                    <button class="btn btn-status-filter" id="tab-confirmed" data-bs-toggle="tab" data-bs-target="#panel-confirmed" type="button" role="tab">Confirmed</button>
                    <button class="btn btn-status-filter" id="tab-pending" data-bs-toggle="tab" data-bs-target="#panel-pending" type="button" role="tab">Pending</button>
                    <button class="btn btn-status-filter" id="tab-completed" data-bs-toggle="tab" data-bs-target="#panel-completed" type="button" role="tab">Completed</button>
                    <button class="btn btn-status-filter" id="tab-cancelled" data-bs-toggle="tab" data-bs-target="#panel-cancelled" type="button" role="tab">Cancelled</button>
                </div>

            </div>
        </div>


        <div class="tab-content" id="statusFilterContent">
            <div class="tab-pane fade show active" id="panel-all" role="tabpanel">
                @forelse($reservations as $reservation)
                    @include('restaurants.reservations._card', ['reservation' => $reservation])
                @empty
                    <p class="text-center text-muted my-4">No reservations found.</p>
                @endforelse
            </div>

            <div class="tab-pane fade" id="panel-confirmed" role="tabpanel">
                @forelse($confirmedReservations as $reservation)
                    @include('restaurants.reservations._card', ['reservation' => $reservation])
                @empty
                    <p class="text-center text-muted my-4">No confirmed reservations.</p>
                @endforelse
            </div>

            <div class="tab-pane fade" id="panel-pending" role="tabpanel">
                @forelse($pendingReservations as $reservation)
                    @include('restaurants.reservations._card', ['reservation' => $reservation])
                @empty
                    <p class="text-center text-muted my-4">No pending reservations.</p>
                @endforelse
            </div>

            <div class="tab-pane fade" id="panel-completed" role="tabpanel">
                @forelse($completedReservations as $reservation)
                    @include('restaurants.reservations._card', ['reservation' => $reservation])
                @empty
                    <p class="text-center text-muted my-4">No completed reservations.</p>
                @endforelse
            </div>

            <div class="tab-pane fade" id="panel-cancelled" role="tabpanel">
                @forelse($cancelledReservations as $reservation)
                    @include('restaurants.reservations._card', ['reservation' => $reservation])
                @empty
                    <p class="text-center text-muted my-4">No cancelled reservations.</p>
                @endforelse
            </div>
        </div>

    </div>

    @include('restaurants.reservations.modals.decline')
    @include('restaurants.reservations.modals.confirm_complete')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            flatpickr("#targetDatePicker", {
                dateFormat: "Y-m-d",
                defaultDate: "{{ request('date') ? $selectedDate : '' }}",
                altInput: true,
                altFormat: "{{ request('date') ? 'Y/m/d' : 'All Days' }}",
                onChange: function(selectedDates, dateStr) {
                    let searchId = document.querySelector('input[name="search_id"]').value;
                    let url = "{{ route('restaurant.reservations') }}?date=" + dateStr;
                    if(searchId) {
                        url += "&search_id=" + encodeURIComponent(searchId);
                    }
                    window.location.href = url;
                }
            });
        });

        // 💡 重複していた各モーダル制御用の JavaScript 関数オブジェクトは、
        // 全て 『_confirm_complete_modals.blade.php』 側に美しく統合されているため、
        // ここからは綺麗に削除しました。
    </script>

@endsection