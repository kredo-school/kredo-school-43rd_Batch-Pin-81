@extends('layouts.restaurant')

@section('title', 'Table Schedule')

@section('content')
    <style>
        /* シェブロンボタンのホバー・透明設定 */
        .btn-chevron-custom {
            background-color: transparent !important;
            border: 1px solid #e9ecef !important;
            color: #6c757d !important;
            transition: all 0.2s ease-in-out;
        }

        .btn-chevron-custom:hover {
            background-color: #FCE7F3 !important;
            border-color: #FCE7F3 !important;
            color: #0A2540 !important;
        }

        /* テーブルヘッダーの余白を詰める */
        .custom-table-head th {
            padding-bottom: 4px !important;
            border-bottom: none !important;
        }

        /* テーブル選択セル用のスタイル */
        .table-clickable {
            cursor: pointer;
            transition: background-color 0.15s ease-in-out;
        }

        .table-clickable:hover {
            background-color: #f1f3f5 !important;
        }


        .bg-navy {
            background-color: #0A2540 !important;
            color: white !important;
        }

        .bg-pink {
            background-color: #FCE7F3 !important;
            color: #0A2540 !important;
        }
    </style>

    <form action="{{ route('restaurant.dashboard') }}" method="GET" class="d-flex flex-column d-md-none px-4 bg-white"
        style="padding-top: 95px; box-sizing: border-box;">

        <div class="d-flex justify-content-between align-items-center mb-2">
            <h2 class="h4 mb-0 fw-bold" style="color: #0A2540 !important;">Table Schedule</h2>
            <button type="button" class="btn btn-sm px-3 py-1 fw-bold ..." data-bs-toggle="modal"
                data-bs-target="#addTableModal">
                <i class="fa-solid fa-plus me-1"></i> Add Table
            </button>
        </div>

        <form action="{{ route('restaurant.dashboard') }}" method="GET" class="d-flex align-items-center gap-2 pb-2 mt-3">
            <button type="submit" name="date" value="{{ \Carbon\Carbon::parse($date)->subDay()->format('Y-m-d') }}" class="btn btn-chevron-custom btn-sm px-2 py-1"><i class="fa-solid fa-chevron-left"></i></button>
            
            <input type="date" name="date" class="form-control form-control-sm text-center fw-bold" value="{{ $date }}" onchange="this.form.submit()"
                style="width: 100%; background-color: #f8f9fa;">
                
            <button type="submit" name="date" value="{{ \Carbon\Carbon::parse($date)->addDay()->format('Y-m-d') }}" class="btn btn-chevron-custom btn-sm px-2 py-1"><i class="fa-solid fa-chevron-right"></i></button>
        </form>

    </form>

    <div class="d-none d-md-flex justify-content-between align-items-center px-lex4 pb-3 bg-white" style="padding-top: 100px;">
        <h2 class="h4 mb-0 fw-bold" style="color: #0A2540 !important;">Table Schedule</h2>

        <div class="d-flex align-items-center gap-3">

            <form action="{{ route('restaurant.dashboard') }}" method="GET" class="d-flex align-items-center gap-2">
                <button type="submit" name="date" value="{{ \Carbon\Carbon::parse($date)->subDay()->format('Y-m-d') }}"
                    class="btn btn-chevron-custom btn-sm px-2 py-1">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>

                <input type="date" name="date" class="form-control form-control-sm text-center fw-bold"
                    value="{{ $date }}" onchange="this.form.submit()"
                    style="width: 140px; background-color: #f8f9fa;">

                <button type="submit" name="date" value="{{ \Carbon\Carbon::parse($date)->addDay()->format('Y-m-d') }}"
                    class="btn btn-chevron-custom btn-sm px-2 py-1">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </form>

            <button class="btn btn-sm px-3 py-1 fw-bold d-inline-flex align-items-center justify-content-center"
                style="background-color: #FCE7F3; color: #0A2540; border-radius: 8px; height: 31px; border: none;"
                data-bs-toggle="modal" data-bs-target="#addTableModal">
                <i class="fa-solid fa-plus me-1"></i> Add Table
            </button>
        </div>
    </div>

    <div class="d-block d-md-none px-3 pt-3 pb-5">
        <div class="d-flex gap-2 overflow-x-auto pb-3 mb-3" style="scrollbar-width: none; -ms-overflow-style: none;">
            <style>
                ::-webkit-scrollbar {
                    display: none;
                }
            </style>
            <button class="btn rounded-pill px-3 py-2 small fw-medium text-nowrap"
                style="background-color: #f1f3f5; color: #495057; font-size: 13px;">17:00 - 19:00</button>
            <button class="btn rounded-pill px-3 py-2 small fw-bold text-nowrap text-white"
                style="background-color: #0A2540; font-size: 13px;">19:00 - 21:00</button>
            <button class="btn rounded-pill px-3 py-2 small fw-medium text-nowrap"
                style="background-color: #f1f3f5; color: #495057; font-size: 13px;">21:00 - 22:00</button>
        </div>

        <div>
            <h5 class="fw-bold mb-3" style="color: #0A2540; font-size: 18px;">Reservations (2)</h5>
            <div class="d-flex flex-column gap-3 mb-4">
                <div class="card p-3 border rounded-4 shadow-sm" style="background-color: #ffffff; cursor: pointer;"
                    onclick="openReservationModal('RM004', 'Sarah Johnson', '19:00', '2 hours', 6, 'Table 4', false)">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <div class="fw-bold text-dark" style="font-size: 15px;">Sarah Johnson</div>
                        <span class="badge text-secondary border bg-light px-2 py-1 rounded small fw-normal"
                            style="font-size: 10px;">RM004</span>
                    </div>
                    <div class="text-secondary small d-flex flex-column gap-1" style="font-size: 12px;">
                        <div><i class="fa-regular fa-clock me-1"></i> 19:00 <i class="fa-solid fa-user-group ms-3 me-1"></i>
                            6</div>
                        <div class="mt-1 text-dark fw-medium">Table 4</div>
                    </div>
                </div>

                <div class="card p-3 border rounded-4 shadow-sm" style="background-color: #ffffff; cursor: pointer;"
                    onclick="openReservationModal('RM007', 'Lisa Anderson', '20:15', '2 hours', 2, 'Table 1', false)">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <div class="fw-bold text-dark" style="font-size: 15px;">Lisa Anderson</div>
                        <span class="badge text-secondary border bg-light px-2 py-1 rounded small fw-normal"
                            style="font-size: 10px;">RM007</span>
                    </div>
                    <div class="text-secondary small d-flex flex-column gap-1" style="font-size: 12px;">
                        <div><i class="fa-regular fa-clock me-1"></i> 20:15 <i class="fa-solid fa-user-group ms-3 me-1"></i>
                            2</div>
                        <div class="mt-1 text-dark fw-medium">Table 1</div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <h6 class="fw-bold text-secondary mb-3 small text-uppercase" style="letter-spacing: 0.5px;">Canceled
                </h6>
                <div class="d-flex flex-column gap-3">
                    <div class="card p-3 border border-dashed rounded-4 bg-light opacity-75">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <div class="fw-bold text-secondary">Michael Chen</div>
                            <span class="text-muted small" style="font-size: 11px;">RM005</span>
                        </div>
                        <div class="text-danger small fw-medium mb-2" style="font-size: 13px;">Canceled by customer
                        </div>
                        <div class="text-muted small" style="font-size: 13px;">
                            <i class="fa-regular fa-clock me-1"></i> 17:30 <i
                                class="fa-solid fa-user-group ms-3 me-1"></i> 3
                        </div>
                    </div>

                    <div class="card p-3 border border-dashed rounded-4 bg-light opacity-75">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <div class="fw-bold text-secondary">Emma Wilson</div>
                            <span class="text-muted small" style="font-size: 11px;">RM006</span>
                        </div>
                        <div class="text-danger small fw-medium mb-2" style="font-size: 13px;">Canceled by shop</div>
                        <div class="text-muted small" style="font-size: 13px;">
                            <i class="fa-regular fa-clock me-1"></i> 19:30 <i
                                class="fa-solid fa-user-group ms-3 me-1"></i> 2
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-none d-md-flex flex-grow-1" style="overflow-x: auto;">

        <div class="bg-white p-3 d-flex flex-column gap-3" style="width: 320px; min-width: 320px; overflow-y: auto;">
            <div>
                <h6 class="fw-bold text-dark mb-3">Reservations (2)</h6>
                <div class="d-flex flex-column gap-2">
                    <div class="card p-3 border rounded-3 position-relative shadow-sm"
                        style="background-color: #ffffff; cursor: pointer;"
                        onclick="openReservationModal('RM001', 'John Smith', '18:00', '2 hours', 2, 'Table 1', false)">
                        <span
                            class="badge text-secondary border position-absolute top-0 end-0 mt-2 me-2 small fw-normal bg-light"
                            style="font-size: 10px;">RM001</span>
                        <div class="fw-bold text-dark mb-1">John Smith</div>
                        <div class="text-secondary small d-flex align-items-center gap-1">
                            <i class="fa-regular fa-clock"></i> 18:00
                            <i class="fa-solid fa-user-group ms-2"></i> 2
                        </div>
                        <div class="text-secondary small mt-1">Table 1</div>
                    </div>

                    <div class="card p-3 border rounded-3 position-relative shadow-sm"
                        style="background-color: #ffffff; cursor: pointer;"
                        onclick="openReservationModal('RM002', 'Maria Garcia', '18:00', '2.5 hours', 4, 'Table 2', true)">
                        <span
                            class="badge text-secondary border position-absolute top-0 end-0 mt-2 me-2 small fw-normal bg-light"
                            style="font-size: 10px;">RM002</span>
                        <div class="fw-bold text-dark mb-1">Maria Garcia</div>
                        <div class="text-secondary small d-flex align-items-center gap-1">
                            <i class="fa-regular fa-clock"></i> 18:00
                            <i class="fa-solid fa-user-group ms-2"></i> 4
                        </div>
                        <div class="text-secondary small mt-1">Table 2</div>
                    </div>
                </div>
            </div>

            <div class="mt-2">
                <h6 class="fw-bold text-secondary mb-3 small text-uppercase" style="letter-spacing: 0.5px;">Canceled
                </h6>
                <div class="d-flex flex-column gap-2">
                    <div class="card p-3 border border-dashed rounded-3 position-relative bg-light opacity-75">
                        <span class="text-muted position-absolute top-0 end-0 mt-2 me-2 small"
                            style="font-size: 10px;">RM005</span>
                        <div class="fw-bold text-secondary mb-1">Michael Chen</div>
                        <div class="text-danger small fw-medium">Canceled by customer</div>
                        <div class="text-muted small d-flex align-items-center gap-1 mt-1">
                            <i class="fa-regular fa-clock"></i> 17:30
                            <i class="fa-solid fa-user-group ms-2"></i> 3
                        </div>
                    </div>
                    <div class="card p-3 border border-dashed rounded-3 position-relative bg-light opacity-75">
                        <span class="text-muted position-absolute top-0 end-0 mt-2 me-2 small"
                            style="font-size: 10px;">RM006</span>
                        <div class="fw-bold text-secondary mb-1">Emma Wilson</div>
                        <div class="text-danger small fw-medium">Canceled by shop</div>
                        <div class="text-muted small d-flex align-items-center gap-1 mt-1">
                            <i class="fa-regular fa-clock"></i> 19:30
                            <i class="fa-solid fa-user-group ms-2"></i> 2
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex-grow-1 bg-white p-3 d-flex flex-column gap-3" style="overflow-y: auto;">
            <div class="d-flex justify-content-between align-items-center px-1">
                <button class="btn btn-chevron-custom btn-sm px-2 py-1"><i class="fa-solid fa-chevron-left"></i></button>
                <span class="fw-bold text-dark small" style="letter-spacing: 0.5px;">Showing 05/20 17:00 -
                    19:00</span>
                <button class="btn btn-chevron-custom btn-sm px-2 py-1"><i class="fa-solid fa-chevron-right"></i></button>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0"
                    style="min-width: 800px; border-collapse: separate; border-spacing: 0 12px; margin-top: -12px;">
                    <thead class="text-secondary small text-start custom-table-head">
                        <tr>
                            <th style="width: 150px;"></th>
                            <th style="font-weight: 600; padding-left: 8px;">17:00</th>
                            <th style="font-weight: 600; padding-left: 8px;">17:15</th>
                            <th style="font-weight: 600; padding-left: 8px;">17:30</th>
                            <th style="font-weight: 600; padding-left: 8px;">17:45</th>
                            <th style="font-weight: 600; padding-left: 8px;">18:00</th>
                            <th style="font-weight: 600; padding-left: 8px;">18:15</th>
                            <th style="font-weight: 600; padding-left: 8px;">18:30</th>
                            <th style="font-weight: 600; padding-left: 8px;">18:45</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($tables as $table)
                            <tr class="shadow-sm {{ $table->is_active ? '' : 'disabled-table' }}">
                                <td class="table-clickable"
                                    onclick="openEditModal('{{ $table->table_name }}', {{ $table->capacity }}, {{ !$table->is_active }})">
                                    <div class="fw-bold">{{ $table->table_name }}</div>
                                    <div class="text-muted" style="font-size: 11px;">{{ $table->capacity }} seats
                                    </div>
                                </td>

                                @foreach (['17:00', '17:15', '17:30', '17:45', '18:00', '18:15', '18:30', '18:45'] as $time)
                                    <td class="border-top border-bottom border-end"
                                        style="height: 50px; min-width: 60px; padding: 0;">
                                        @php
                                            $reservation = $table->reservations->first(function ($res) use ($time) {
                                                return \Carbon\Carbon::parse($res->reservation_time)->format('H:i') ===
                                                    $time;
                                            });
                                        @endphp

                                        @if ($reservation)
                                            @php
                                                $isConfirmed = $reservation->status === 'confirmed';
                                                $colorClass = $isConfirmed ? 'bg-navy' : 'bg-pink';
                                                $textColor = $isConfirmed ? 'text-white' : 'text-dark';

                                                $displayName = $reservation->user
                                                    ? $reservation->user->first_name .
                                                        ' ' .
                                                        $reservation->user->last_name
                                                    : 'Unknown';

                                                $displayGuests =
                                                    $reservation->num_of_people ?? ($reservation->guests ?? 0);
                                            @endphp

                                            <div class="p-1 rounded {{ $colorClass }} {{ $textColor }}"
                                                style="font-size: 10px; cursor: pointer; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center;"
                                                onclick="openReservationModal('RM{{ $reservation->id }}', '{{ $displayName }}', '{{ $time }}', '2 hours', {{ $displayGuests }}, '{{ $table->table_name }}', false)">

                                                <div class="fw-bold">{{ $displayName }}</div>
                                                <div>{{ $displayGuests }} guests</div>
                                            </div>
                                        @else
                                            &nbsp;
                                        @endif


                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @include('restaurants.dashboard.modals.edit_table')
                @include('restaurants.dashboard.modals.add_table')
                @include('restaurants.dashboard.modals.reservation_details')
                @include('restaurants.dashboard.modals.new_reservation')

            </div>
        </div>
    </div>

    <script>
        /**
         * 1. テーブル編集モーダルを開く（トグル初期化 ＆ 通常画面へのリセット）
         */
        function openEditModal(name, capacity, isDisabled = false) {
            // モーダル内の入力フォームオブジェクトに値をセット
            document.getElementById('tableNameInput').value = name;
            document.getElementById('tableCapacityInput').value = capacity;

            // トグルボタンの要素オブジェクトを取得
            const enableBtn = document.getElementById('status-enable-btn');
            const disableBtn = document.getElementById('status-disable-btn');
            const statusInput = document.getElementById('table-status-input');

            // 状態（isDisabled）に応じてトグルのアクティブクラスを制御
            if (isDisabled) {
                if (enableBtn) enableBtn.classList.remove('active-enable');
                if (disableBtn) disableBtn.classList.add('active-disable');
                if (statusInput) statusInput.value = 'disable';
            } else {
                if (enableBtn) enableBtn.classList.add('active-enable');
                if (disableBtn) disableBtn.classList.remove('active-disable');
                if (statusInput) statusInput.value = 'enable';
            }

            // 💡 起動時は必ず「入力フォーム画面（通常ビュー）」にリセットする
            hideConfirmView();

            // 状態に応じて下部のアクションボタンオブジェクト（Delete / Enable）の表示を切り替え
            if (name === 'Table 3' || isDisabled) {
                if (document.getElementById('btnEnableTable')) document.getElementById('btnEnableTable').classList.remove(
                    'd-none');
                if (document.getElementById('btnDisableTable')) document.getElementById('btnDisableTable').classList.add(
                    'd-none');
            } else {
                if (document.getElementById('btnDisableTable')) document.getElementById('btnDisableTable').classList.remove(
                    'd-none');
                if (document.getElementById('btnEnableTable')) document.getElementById('btnEnableTable').classList.add(
                    'd-none');
            }

            // モーダルオブジェクトを起動
            var myModal = new bootstrap.Modal(document.getElementById('editTableModal'));
            myModal.show();
        }

        /**
         * 2. 💡【アップデート】モーダル内を確認画面に切り替え ＋ トグルを非表示化
         */
        function showConfirmView(type) {
            // 💡 2段階確認中は右上のトグルオブジェクトを非表示にする
            if (document.getElementById('modalHeaderToggle')) document.getElementById('modalHeaderToggle').classList.add(
                'd-none');

            // 通常のフォーム入力エリアと標準のボタンオブジェクト群を非表示にする
            if (document.getElementById('modalMainFormView')) document.getElementById('modalMainFormView').classList.add(
                'd-none');
            if (document.getElementById('btnSaveChanges')) document.getElementById('btnSaveChanges').classList.add(
                'd-none');
            if (document.getElementById('btnDisableTable')) document.getElementById('btnDisableTable').classList.add(
                'd-none');
            if (document.getElementById('btnEnableTable')) document.getElementById('btnEnableTable').classList.add(
                'd-none');

            // ネイビーの戻る（Cancel）ボタンオブジェクトを表示
            if (document.getElementById('btnCancelConfirm')) document.getElementById('btnCancelConfirm').classList.remove(
                'd-none');

            // タイプオブジェクト（disable または delete）に応じて確認画面を切り替え
            if (type === 'disable') {
                if (document.getElementById('editTableModalLabel')) document.getElementById('editTableModalLabel')
                    .innerText = 'Disable Table';
                if (document.getElementById('editTableModalSub')) document.getElementById('editTableModalSub').innerText =
                    'Confirm table deactivation';
                if (document.getElementById('modalDisableConfirmView')) document.getElementById('modalDisableConfirmView')
                    .classList.remove('d-none');
                if (document.getElementById('btnExecuteDisable')) document.getElementById('btnExecuteDisable').classList
                    .remove('d-none');
            } else if (type === 'delete') {
                if (document.getElementById('editTableModalLabel')) document.getElementById('editTableModalLabel')
                    .innerText = 'Delete Table';
                if (document.getElementById('editTableModalSub')) document.getElementById('editTableModalSub').innerText =
                    'Confirm table deletion';
                if (document.getElementById('modalDeleteConfirmView')) document.getElementById('modalDeleteConfirmView')
                    .classList.remove('d-none');
                if (document.getElementById('btnExecuteDelete')) document.getElementById('btnExecuteDelete').classList
                    .remove('d-none');
            }
        }

        /**
         * 3. 💡【アップデート】通常画面に戻す ＋ トグルを再表示
         */
        function hideConfirmView() {
            // 💡 通常画面に戻ったらトグルオブジェクトを再表示する
            if (document.getElementById('modalHeaderToggle')) document.getElementById('modalHeaderToggle').classList.remove(
                'd-none');

            // ヘッダーテキストオブジェクトの初期化
            if (document.getElementById('editTableModalLabel')) document.getElementById('editTableModalLabel').innerText =
                'Edit Table';
            if (document.getElementById('editTableModalSub')) document.getElementById('editTableModalSub').innerText =
                'Update table information';

            // 各種確認ビュー・確認ボタンオブジェクトを非表示にする
            if (document.getElementById('modalDisableConfirmView')) document.getElementById('modalDisableConfirmView')
                .classList.add('d-none');
            if (document.getElementById('modalDeleteConfirmView')) document.getElementById('modalDeleteConfirmView')
                .classList.add('d-none');
            if (document.getElementById('btnCancelConfirm')) document.getElementById('btnCancelConfirm').classList.add(
                'd-none');
            if (document.getElementById('btnExecuteDisable')) document.getElementById('btnExecuteDisable').classList.add(
                'd-none');
            if (document.getElementById('btnExecuteDelete')) document.getElementById('btnExecuteDelete').classList.add(
                'd-none');

            // メインの入力フォームと保存ボタンオブジェクトを再表示
            if (document.getElementById('modalMainFormView')) document.getElementById('modalMainFormView').classList.remove(
                'd-none');
            if (document.getElementById('btnSaveChanges')) document.getElementById('btnSaveChanges').classList.remove(
                'd-none');

            // テーブル名オブジェクトの状態に合わせてアクションボタンを復元
            var tableName = document.getElementById('tableNameInput') ? document.getElementById('tableNameInput').value :
                '';
            if (tableName === 'Table 3') {
                if (document.getElementById('btnEnableTable')) document.getElementById('btnEnableTable').classList.remove(
                    'd-none');
                if (document.getElementById('btnDisableTable')) document.getElementById('btnDisableTable').classList.add(
                    'd-none');
            } else {
                if (document.getElementById('btnDisableTable')) document.getElementById('btnDisableTable').classList.remove(
                    'd-none');
                if (document.getElementById('btnEnableTable')) document.getElementById('btnEnableTable').classList.add(
                    'd-none');
            }
        }

        /**
         * 4. 予約詳細モーダルを開く
         */
        function openReservationModal(id, customer, time, duration, guests, table, isCompleted = false) {
            document.getElementById('resIdDisplay').innerText = id;
            document.getElementById('resCustomerDisplay').innerText = customer;
            document.getElementById('resTimeDisplay').innerText = time;
            document.getElementById('resDurationDisplay').innerText = duration;
            document.getElementById('resGuestsDisplay').innerText = guests;
            document.getElementById('resTableDisplay').innerText = table;

            if (isCompleted) {
                document.getElementById('normalResActions').classList.add('d-none');
                document.getElementById('completedResActions').classList.remove('d-none');
            } else {
                document.getElementById('normalResActions').classList.remove('d-none');
                document.getElementById('completedResActions').classList.add('d-none');
            }

            var myModal = new bootstrap.Modal(document.getElementById('reservationDetailsModal'));
            myModal.show();
        }

        // 5. モーダルが閉じられたときに自動でフォーム表示へリセットするイベントリスナーオブジェクト
        document.addEventListener("DOMContentLoaded", function() {
            var editTableModalElem = document.getElementById('editTableModal');
            if (editTableModalElem) {
                editTableModalElem.addEventListener('hidden.bs.modal', function() {
                    hideConfirmView();
                });
            }
        });

        // 6. 画面読み込み完了時に「即日予約モーダル」を自動表示
        // document.addEventListener("DOMContentLoaded", function() {
        //     var newResModalElem = document.getElementById('newReservationModal');
        //     if (newResModalElem) {
        //         var myModal = new bootstrap.Modal(newResModalElem);
        //         myModal.show();
        //     }
        // });
    </script>
    </div>
@endsection



{{-- 

hinan

@extends('layouts.restaurant')

@section('title', 'Reservations')

@section('content')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>

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


        .btn-status-filter.active {
            background-color: #0A2540 !important;
            color: #ffffff !important;
            border-color: #0A2540 !important;
        }


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


        .badge {
            color: #0A2540 !important;
        }


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
                        <input type="text" name="search_id" class="datepicker-input" style="width: 130px; cursor: text;" placeholder="Reservation Code" value="{{ request('search_id') }}">
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
                    
                    <button class="btn btn-status-filter" id="tab-pending" data-bs-toggle="tab" data-bs-target="#panel-pending" type="button" role="tab">Pending</button>
                    <button class="btn btn-status-filter" id="tab-confirmed" data-bs-toggle="tab" data-bs-target="#panel-confirmed" type="button" role="tab">Confirmed</button>
                    
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

    </script>

@endsection --}}



index

@extends('layouts.restaurant')

@section('title', 'Table Schedule')

@section('content')
    <style>
        /* シェブロンボタンのホバー・透明設定 */
        .btn-chevron-custom {
            background-color: transparent !important;
            border: 1px solid #e9ecef !important;
            color: #6c757d !important;
            transition: all 0.2s ease-in-out;
        }

        .btn-chevron-custom:hover {
            background-color: #FCE7F3 !important;
            border-color: #FCE7F3 !important;
            color: #0A2540 !important;
        }

        /* テーブルヘッダーの余白を詰める */
        .custom-table-head th {
            padding-bottom: 4px !important;
            border-bottom: none !important;
        }

        /* テーブル選択セル用のスタイル */
        .table-clickable {
            cursor: pointer;
            transition: background-color 0.15s ease-in-out;
        }

        .table-clickable:hover {
            background-color: #f1f3f5 !important;
        }


        .bg-navy {
            background-color: #0A2540 !important;
            color: white !important;
        }

        .bg-pink {
            background-color: #FCE7F3 !important;
            color: #0A2540 !important;
        }

        /* テーブルのレイアウトを強制的に固定する */
        .table-responsive table {
            table-layout: fixed !important;
            width: 100% !important;
        }

        /* 各セル（時間枠）の幅を一定（60px）にする */
        .table-responsive table td,
        .table-responsive table th {
            width: 60px !important;
            /* 全ての枠が60pxで固定されます */
            min-width: 60px !important;
            overflow: hidden;
            /* 枠からはみ出さない */
            white-space: nowrap;
        }
    </style>

    <div class="d-flex flex-column"
        style="min-height: calc(100vh - 70px); background-color: transparent; margin-top: -100px;">

        <div class="d-flex flex-column d-md-none px-4 bg-white" style="padding-top: 95px; box-sizing: border-box;">

            <div class="d-flex justify-content-between align-items-center mb-2">
                <h2 class="h4 mb-0 fw-bold" style="color: #0A2540 !important;">Table Schedule</h2>
                <button
                    class="btn btn-sm px-3 py-1 fw-bold d-inline-flex align-items-center            justify-content-center"
                    style="background-color: #FCE7F3; color: #0A2540; border-radius: 8px; height: 31px; border: none;"
                    data-bs-toggle="modal" data-bs-target="#addTableModal">
                    <i class="fa-solid fa-plus me-1"></i> Add Table
                </button>
            </div>

            <div class="d-flex align-items-center gap-2 pb-2 mt-3">
                <button class="btn btn-chevron-custom btn-sm px-2 py-1"><i class="fa-solid fa-chevron-left"></i></button>
                <input type="text" class="form-control form-control-sm text-center fw-bold" value="2026/05/20"
                    style="width: 100%; background-color: #f8f9fa;" readonly>
                <button class="btn btn-chevron-custom btn-sm px-2 py-1"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
        </div>

        <div class="d-none d-md-flex justify-content-between align-items-center px-4 pb-3 bg-white"
            style="padding-top: 100px;">
            <h2 class="h4 mb-0 fw-bold" style="color: #0A2540 !important;">Table Schedule</h2>

            <div class="d-flex align-items-center gap-3">

                <form action="{{ route('restaurant.dashboard') }}" method="GET" class="d-flex align-items-center gap-2">
                    <button type="submit" name="date"
                        value="{{ \Carbon\Carbon::parse($date)->subDay()->format('Y-m-d') }}"
                        class="btn btn-chevron-custom btn-sm px-2 py-1">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>

                    <input type="date" name="date" class="form-control form-control-sm text-center fw-bold"
                        value="{{ $date }}" onchange="this.form.submit()"
                        style="width: 140px; background-color: #f8f9fa;">

                    <button type="submit" name="date"
                        value="{{ \Carbon\Carbon::parse($date)->addDay()->format('Y-m-d') }}"
                        class="btn btn-chevron-custom btn-sm px-2 py-1">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </form>

                <button class="btn btn-sm px-3 py-1 fw-bold d-inline-flex align-items-center justify-content-center"
                    style="background-color: #FCE7F3; color: #0A2540; border-radius: 8px; height: 31px; border: none;"
                    data-bs-toggle="modal" data-bs-target="#addTableModal">
                    <i class="fa-solid fa-plus me-1"></i> Add Table
                </button>
            </div>
        </div>

        <div class="d-block d-md-none px-3 pt-3 pb-5">
            <div class="d-flex gap-2 overflow-x-auto pb-3 mb-3" style="scrollbar-width: none; -ms-overflow-style: none;">
                <style>
                    ::-webkit-scrollbar {
                        display: none;
                    }
                </style>
                <button class="btn rounded-pill px-3 py-2 small fw-medium text-nowrap"
                    style="background-color: #f1f3f5; color: #495057; font-size: 13px;">17:00 - 19:00</button>
                <button class="btn rounded-pill px-3 py-2 small fw-bold text-nowrap text-white"
                    style="background-color: #0A2540; font-size: 13px;">19:00 - 21:00</button>
                <button class="btn rounded-pill px-3 py-2 small fw-medium text-nowrap"
                    style="background-color: #f1f3f5; color: #495057; font-size: 13px;">21:00 - 22:00</button>
            </div>

            <div>
                <h5 class="fw-bold mb-3" style="color: #0A2540; font-size: 18px;">Reservations (2)</h5>
                <div class="d-flex flex-column gap-3 mb-4">
                    <div class="card p-3 border rounded-4 shadow-sm" style="background-color: #ffffff; cursor: pointer;"
                        onclick="openReservationModal('RM004', 'Sarah Johnson', '19:00', '2 hours', 6, 'Table 4', false)">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <div class="fw-bold text-dark" style="font-size: 15px;">Sarah Johnson</div>
                            <span class="badge text-secondary border bg-light px-2 py-1 rounded small fw-normal"
                                style="font-size: 10px;">RM004</span>
                        </div>
                        <div class="text-secondary small d-flex flex-column gap-1" style="font-size: 12px;">
                            <div><i class="fa-regular fa-clock me-1"></i> 19:00 <i
                                    class="fa-solid fa-user-group ms-3 me-1"></i> 6</div>
                            <div class="mt-1 text-dark fw-medium">Table 4</div>
                        </div>
                    </div>

                    <div class="card p-3 border rounded-4 shadow-sm" style="background-color: #ffffff; cursor: pointer;"
                        onclick="openReservationModal('RM007', 'Lisa Anderson', '20:15', '2 hours', 2, 'Table 1', false)">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <div class="fw-bold text-dark" style="font-size: 15px;">Lisa Anderson</div>
                            <span class="badge text-secondary border bg-light px-2 py-1 rounded small fw-normal"
                                style="font-size: 10px;">RM007</span>
                        </div>
                        <div class="text-secondary small d-flex flex-column gap-1" style="font-size: 12px;">
                            <div><i class="fa-regular fa-clock me-1"></i> 20:15 <i
                                    class="fa-solid fa-user-group ms-3 me-1"></i> 2</div>
                            <div class="mt-1 text-dark fw-medium">Table 1</div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <h6 class="fw-bold text-secondary mb-3 small text-uppercase" style="letter-spacing: 0.5px;">Canceled
                    </h6>
                    <div class="d-flex flex-column gap-3">
                        <div class="card p-3 border border-dashed rounded-4 bg-light opacity-75">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div class="fw-bold text-secondary">Michael Chen</div>
                                <span class="text-muted small" style="font-size: 11px;">RM005</span>
                            </div>
                            <div class="text-danger small fw-medium mb-2" style="font-size: 13px;">Canceled by customer
                            </div>
                            <div class="text-muted small" style="font-size: 13px;">
                                <i class="fa-regular fa-clock me-1"></i> 17:30 <i
                                    class="fa-solid fa-user-group ms-3 me-1"></i> 3
                            </div>
                        </div>

                        <div class="card p-3 border border-dashed rounded-4 bg-light opacity-75">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div class="fw-bold text-secondary">Emma Wilson</div>
                                <span class="text-muted small" style="font-size: 11px;">RM006</span>
                            </div>
                            <div class="text-danger small fw-medium mb-2" style="font-size: 13px;">Canceled by shop</div>
                            <div class="text-muted small" style="font-size: 13px;">
                                <i class="fa-regular fa-clock me-1"></i> 19:30 <i
                                    class="fa-solid fa-user-group ms-3 me-1"></i> 2
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-none d-md-flex flex-grow-1" style="overflow-x: auto;">

            <div class="bg-white p-3 d-flex flex-column gap-3" style="width: 320px; min-width: 320px; overflow-y: auto;">
                <div>
                    <h6 class="fw-bold text-dark mb-3">Reservations (2)</h6>
                    <div class="d-flex flex-column gap-2">
                        <div class="card p-3 border rounded-3 position-relative shadow-sm"
                            style="background-color: #ffffff; cursor: pointer;"
                            onclick="openReservationModal('RM001', 'John Smith', '18:00', '2 hours', 2, 'Table 1', false)">
                            <span
                                class="badge text-secondary border position-absolute top-0 end-0 mt-2 me-2 small fw-normal bg-light"
                                style="font-size: 10px;">RM001</span>
                            <div class="fw-bold text-dark mb-1">John Smith</div>
                            <div class="text-secondary small d-flex align-items-center gap-1">
                                <i class="fa-regular fa-clock"></i> 18:00
                                <i class="fa-solid fa-user-group ms-2"></i> 2
                            </div>
                            <div class="text-secondary small mt-1">Table 1</div>
                        </div>

                        <div class="card p-3 border rounded-3 position-relative shadow-sm"
                            style="background-color: #ffffff; cursor: pointer;"
                            onclick="openReservationModal('RM002', 'Maria Garcia', '18:00', '2.5 hours', 4, 'Table 2', true)">
                            <span
                                class="badge text-secondary border position-absolute top-0 end-0 mt-2 me-2 small fw-normal bg-light"
                                style="font-size: 10px;">RM002</span>
                            <div class="fw-bold text-dark mb-1">Maria Garcia</div>
                            <div class="text-secondary small d-flex align-items-center gap-1">
                                <i class="fa-regular fa-clock"></i> 18:00
                                <i class="fa-solid fa-user-group ms-2"></i> 4
                            </div>
                            <div class="text-secondary small mt-1">Table 2</div>
                        </div>
                    </div>
                </div>

                <div class="mt-2">
                    <h6 class="fw-bold text-secondary mb-3 small text-uppercase" style="letter-spacing: 0.5px;">Canceled
                    </h6>
                    <div class="d-flex flex-column gap-2">
                        <div class="card p-3 border border-dashed rounded-3 position-relative bg-light opacity-75">
                            <span class="text-muted position-absolute top-0 end-0 mt-2 me-2 small"
                                style="font-size: 10px;">RM005</span>
                            <div class="fw-bold text-secondary mb-1">Michael Chen</div>
                            <div class="text-danger small fw-medium">Canceled by customer</div>
                            <div class="text-muted small d-flex align-items-center gap-1 mt-1">
                                <i class="fa-regular fa-clock"></i> 17:30
                                <i class="fa-solid fa-user-group ms-2"></i> 3
                            </div>
                        </div>
                        <div class="card p-3 border border-dashed rounded-3 position-relative bg-light opacity-75">
                            <span class="text-muted position-absolute top-0 end-0 mt-2 me-2 small"
                                style="font-size: 10px;">RM006</span>
                            <div class="fw-bold text-secondary mb-1">Emma Wilson</div>
                            <div class="text-danger small fw-medium">Canceled by shop</div>
                            <div class="text-muted small d-flex align-items-center gap-1 mt-1">
                                <i class="fa-regular fa-clock"></i> 19:30
                                <i class="fa-solid fa-user-group ms-2"></i> 2
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex-grow-1 bg-white p-3 d-flex flex-column gap-3" style="overflow-y: auto;">
                <div class="d-flex justify-content-between align-items-center px-1">
                    {{-- 30分戻る --}}
                    <a href="{{ route('restaurant.dashboard', ['date' => $date,'start_time' => \Carbon\Carbon::createFromFormat('H:i', $displayStartTime ?? '17:00')->subMinutes(30)->format('H:i')]) }}"
                        class="btn btn-chevron-custom btn-sm px-2 py-1"><i class="fa-solid fa-chevron-left"></i></a>

                    {{-- 現在の表示時間 --}}
                    <span class="fw-bold text-dark small" style="letter-spacing: 0.5px;">
                        Showing {{ \Carbon\Carbon::parse($date)->format('m/d') }}
                        {{ $displayStartTime ?? '17:00' }} -
                        {{ \Carbon\Carbon::createFromFormat('H:i', $displayStartTime ?? '17:00')->addHours(2)->format('H:i') }}
                    </span>

                    {{-- 30分進む --}}
                    <a href="{{ route('restaurant.dashboard', ['date' => $date,'start_time' => \Carbon\Carbon::parse($displayStartTime ?? '17:00')->addMinutes(30)->format('H:i')]) }}"
                        class="btn btn-chevron-custom btn-sm px-2 py-1"><i class="fa-solid fa-chevron-right"></i></a>
                </div>

                <div class="table-responsive">

                    <div style="background: #fff3cd; padding: 10px; margin-bottom: 20px; border: 1px solid #ffeeba;">
                        <p>現在の時間スロット数: <strong>{{ count($timeSlots) }}</strong></p>
                        <p>最初の時間: {{ $timeSlots[0] ?? 'なし' }}</p>
                        <p>最後の時間: {{ end($timeSlots) ?? 'なし' }}</p>
                    </div>

                    <table class="table align-middle mb-0"
                        style="min-width: 800px; border-collapse: separate; border-spacing: 0 12px; margin-top: -12px;">
                        <thead class="text-secondary small text-start custom-table-head">
                            <tr>
                                <th style="width: 150px;"></th>

                                @foreach ($timeSlots as $index => $time)
                                    <th style="font-weight: 600; padding-left: 8px;">{{ $time }}</th>
                                @endforeach

                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($tables as $table)
                                <tr class="shadow-sm {{ $table->is_active ? '' : 'disabled-table' }}">
                                    <td class="table-clickable"
                                        onclick="openEditModal('{{ $table->table_name }}', {{ $table->capacity }}, {{ !$table->is_active }})">
                                        <div class="fw-bold">{{ $table->table_name }}</div>
                                        <div class="text-muted" style="font-size: 11px;">{{ $table->capacity }} seats
                                        </div>
                                    </td>

                                    @php
                                        // 現在の列カウンタ（8枠を守るためのカウンター）
                                        $currentColumn = 0;
                                        $totalColumns = 8;
                                    @endphp

                                    @foreach ($timeSlots as $time)
                                        @if ($currentColumn >= $totalColumns)
                                            @break
                                        @endif

                                        {{-- index.blade.php の @foreach ($timeSlots as $time) ループ内 --}}

                                        @php
                                            // コレクションに対して、モデルのロジックを再利用する形です
                                            $reservation = $table->reservations
                                                ->whereIn('status', ['pending', 'confirmed', 'completed'])
                                                ->first(function ($res) use ($time) {
                                                    return \Carbon\Carbon::parse($res->reservation_time)->format(
                                                        'H:i',
                                                    ) === $time;
                                                });
                                        @endphp

                                        @if ($reservation)
                                            <!-- 予約がある場合の処理 -->
                                        @else
                                            {{-- デバッグ用：この時間枠で予約がヒットしたか確認 --}}
                                            <div style="font-size: 8px; color: red;">
                                                {{ $table->reservations->count() }}件の予約あり
                                                @foreach ($table->reservations as $r)
                                                    {{ \Carbon\Carbon::parse($r->reservation_time)->format('H:i') }}
                                                @endforeach
                                            </div>
                                        @endif

                                        @if ($reservation)
                                            @php
                                                $durationHours = (int) filter_var(
                                                    $reservation->duration ?? '2 hours',
                                                    FILTER_SANITIZE_NUMBER_INT,
                                                );
                                                $colspan = ($durationHours * 60 + 15) / 15;

                                                // 残りの枠数を超えないように調整
                                                if ($currentColumn + $colspan > $totalColumns) {
                                                    $colspan = $totalColumns - $currentColumn;
                                                }

                                                $currentColumn += $colspan;

                                                $displayName = $reservation->user
                                                    ? $reservation->user->first_name .
                                                        ' ' .
                                                        $reservation->user->last_name
                                                    : 'Unknown';
                                                $displayGuests =
                                                    $reservation->num_of_people ?? ($reservation->guests ?? 0);
                                            @endphp

                                            <td colspan="{{ $colspan }}" class="p-1" style="height: 50px;">
                                                <div class="rounded {{ $reservation->status === 'confirmed' ? 'bg-navy' : 'bg-pink' }} {{ $reservation->status === 'confirmed' ? 'text-white' : 'text-dark' }}"
                                                    style="height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; font-size: 10px;"
                                                    onclick="openReservationModal('RM{{ $reservation->id }}', '{{ $displayName }}', '{{ $time }}', '{{ $reservation->duration }}', {{ $displayGuests }}, '{{ $table->table_name }}', false)">
                                                    <div class="fw-bold text-truncate" style="max-width: 90%;">
                                                        {{ $displayName }}</div>
                                                    <div class="text-truncate">{{ $displayGuests }} guests</div>
                                                </div>
                                            </td>
                                        @else
                                            <td class="border-top border-bottom border-end"
                                                style="height: 50px; min-width: 60px; padding: 0;">&nbsp;</td>
                                            @php $currentColumn++; @endphp
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @include('restaurants.dashboard.modals.edit_table')
                    @include('restaurants.dashboard.modals.add_table')
                    @include('restaurants.dashboard.modals.reservation_details')
                    @include('restaurants.dashboard.modals.new_reservation')

                </div>
            </div>
        </div>

        <script>
            /**
             * 1. テーブル編集モーダルを開く（トグル初期化 ＆ 通常画面へのリセット）
             */
            function openEditModal(name, capacity, isDisabled = false) {
                // モーダル内の入力フォームオブジェクトに値をセット
                document.getElementById('tableNameInput').value = name;
                document.getElementById('tableCapacityInput').value = capacity;

                // トグルボタンの要素オブジェクトを取得
                const enableBtn = document.getElementById('status-enable-btn');
                const disableBtn = document.getElementById('status-disable-btn');
                const statusInput = document.getElementById('table-status-input');

                // 状態（isDisabled）に応じてトグルのアクティブクラスを制御
                if (isDisabled) {
                    if (enableBtn) enableBtn.classList.remove('active-enable');
                    if (disableBtn) disableBtn.classList.add('active-disable');
                    if (statusInput) statusInput.value = 'disable';
                } else {
                    if (enableBtn) enableBtn.classList.add('active-enable');
                    if (disableBtn) disableBtn.classList.remove('active-disable');
                    if (statusInput) statusInput.value = 'enable';
                }

                //  起動時は必ず「入力フォーム画面（通常ビュー）」にリセットする
                hideConfirmView();

                // 状態に応じて下部のアクションボタンオブジェクト（Delete / Enable）の表示を切り替え
                if (name === 'Table 3' || isDisabled) {
                    if (document.getElementById('btnEnableTable')) document.getElementById('btnEnableTable').classList.remove(
                        'd-none');
                    if (document.getElementById('btnDisableTable')) document.getElementById('btnDisableTable').classList.add(
                        'd-none');
                } else {
                    if (document.getElementById('btnDisableTable')) document.getElementById('btnDisableTable').classList.remove(
                        'd-none');
                    if (document.getElementById('btnEnableTable')) document.getElementById('btnEnableTable').classList.add(
                        'd-none');
                }

                // モーダルオブジェクトを起動
                var myModal = new bootstrap.Modal(document.getElementById('editTableModal'));
                myModal.show();
            }

            /**
             * 2. 💡【アップデート】モーダル内を確認画面に切り替え ＋ トグルを非表示化
             */
            function showConfirmView(type) {
                // 💡 2段階確認中は右上のトグルオブジェクトを非表示にする
                if (document.getElementById('modalHeaderToggle')) document.getElementById('modalHeaderToggle').classList.add(
                    'd-none');

                // 通常のフォーム入力エリアと標準のボタンオブジェクト群を非表示にする
                if (document.getElementById('modalMainFormView')) document.getElementById('modalMainFormView').classList.add(
                    'd-none');
                if (document.getElementById('btnSaveChanges')) document.getElementById('btnSaveChanges').classList.add(
                    'd-none');
                if (document.getElementById('btnDisableTable')) document.getElementById('btnDisableTable').classList.add(
                    'd-none');
                if (document.getElementById('btnEnableTable')) document.getElementById('btnEnableTable').classList.add(
                    'd-none');

                // ネイビーの戻る（Cancel）ボタンオブジェクトを表示
                if (document.getElementById('btnCancelConfirm')) document.getElementById('btnCancelConfirm').classList.remove(
                    'd-none');

                // タイプオブジェクト（disable または delete）に応じて確認画面を切り替え
                if (type === 'disable') {
                    if (document.getElementById('editTableModalLabel')) document.getElementById('editTableModalLabel')
                        .innerText = 'Disable Table';
                    if (document.getElementById('editTableModalSub')) document.getElementById('editTableModalSub').innerText =
                        'Confirm table deactivation';
                    if (document.getElementById('modalDisableConfirmView')) document.getElementById('modalDisableConfirmView')
                        .classList.remove('d-none');
                    if (document.getElementById('btnExecuteDisable')) document.getElementById('btnExecuteDisable').classList
                        .remove('d-none');
                } else if (type === 'delete') {
                    if (document.getElementById('editTableModalLabel')) document.getElementById('editTableModalLabel')
                        .innerText = 'Delete Table';
                    if (document.getElementById('editTableModalSub')) document.getElementById('editTableModalSub').innerText =
                        'Confirm table deletion';
                    if (document.getElementById('modalDeleteConfirmView')) document.getElementById('modalDeleteConfirmView')
                        .classList.remove('d-none');
                    if (document.getElementById('btnExecuteDelete')) document.getElementById('btnExecuteDelete').classList
                        .remove('d-none');
                }
            }

            /**
             * 3. 💡【アップデート】通常画面に戻す ＋ トグルを再表示
             */
            function hideConfirmView() {
                // 💡 通常画面に戻ったらトグルオブジェクトを再表示する
                if (document.getElementById('modalHeaderToggle')) document.getElementById('modalHeaderToggle').classList.remove(
                    'd-none');

                // ヘッダーテキストオブジェクトの初期化
                if (document.getElementById('editTableModalLabel')) document.getElementById('editTableModalLabel').innerText =
                    'Edit Table';
                if (document.getElementById('editTableModalSub')) document.getElementById('editTableModalSub').innerText =
                    'Update table information';

                // 各種確認ビュー・確認ボタンオブジェクトを非表示にする
                if (document.getElementById('modalDisableConfirmView')) document.getElementById('modalDisableConfirmView')
                    .classList.add('d-none');
                if (document.getElementById('modalDeleteConfirmView')) document.getElementById('modalDeleteConfirmView')
                    .classList.add('d-none');
                if (document.getElementById('btnCancelConfirm')) document.getElementById('btnCancelConfirm').classList.add(
                    'd-none');
                if (document.getElementById('btnExecuteDisable')) document.getElementById('btnExecuteDisable').classList.add(
                    'd-none');
                if (document.getElementById('btnExecuteDelete')) document.getElementById('btnExecuteDelete').classList.add(
                    'd-none');

                // メインの入力フォームと保存ボタンオブジェクトを再表示
                if (document.getElementById('modalMainFormView')) document.getElementById('modalMainFormView').classList.remove(
                    'd-none');
                if (document.getElementById('btnSaveChanges')) document.getElementById('btnSaveChanges').classList.remove(
                    'd-none');

                // テーブル名オブジェクトの状態に合わせてアクションボタンを復元
                var tableName = document.getElementById('tableNameInput') ? document.getElementById('tableNameInput').value :
                    '';
                if (tableName === 'Table 3') {
                    if (document.getElementById('btnEnableTable')) document.getElementById('btnEnableTable').classList.remove(
                        'd-none');
                    if (document.getElementById('btnDisableTable')) document.getElementById('btnDisableTable').classList.add(
                        'd-none');
                } else {
                    if (document.getElementById('btnDisableTable')) document.getElementById('btnDisableTable').classList.remove(
                        'd-none');
                    if (document.getElementById('btnEnableTable')) document.getElementById('btnEnableTable').classList.add(
                        'd-none');
                }
            }

            /**
             * 4. 予約詳細モーダルを開く
             */
            function openReservationModal(id, customer, time, duration, guests, table, isCompleted = false) {
                document.getElementById('resIdDisplay').innerText = id;
                document.getElementById('resCustomerDisplay').innerText = customer;
                document.getElementById('resTimeDisplay').innerText = time;
                document.getElementById('resDurationDisplay').innerText = duration;
                document.getElementById('resGuestsDisplay').innerText = guests;
                document.getElementById('resTableDisplay').innerText = table;

                if (isCompleted) {
                    document.getElementById('normalResActions').classList.add('d-none');
                    document.getElementById('completedResActions').classList.remove('d-none');
                } else {
                    document.getElementById('normalResActions').classList.remove('d-none');
                    document.getElementById('completedResActions').classList.add('d-none');
                }

                var myModal = new bootstrap.Modal(document.getElementById('reservationDetailsModal'));
                myModal.show();
            }

            // 5. モーダルが閉じられたときに自動でフォーム表示へリセットするイベントリスナーオブジェクト
            document.addEventListener("DOMContentLoaded", function() {
                var editTableModalElem = document.getElementById('editTableModal');
                if (editTableModalElem) {
                    editTableModalElem.addEventListener('hidden.bs.modal', function() {
                        hideConfirmView();
                    });
                }
            });

            // 6. 画面読み込み完了時に「即日予約モーダル」を自動表示
            // document.addEventListener("DOMContentLoaded", function() {
            //     var newResModalElem = document.getElementById('newReservationModal');
            //     if (newResModalElem) {
            //         var myModal = new bootstrap.Modal(newResModalElem);
            //         myModal.show();
            //     }
            // });
        </script>
    </div>
@endsection
