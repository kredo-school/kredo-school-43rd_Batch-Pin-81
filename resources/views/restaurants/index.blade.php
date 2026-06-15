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
    </style>

    <div class="d-flex flex-column"
        style="min-height: calc(100vh - 70px); background-color: transparent; margin-top: -100px;">

        <div class="d-flex flex-column d-md-none px-4 bg-white" style="padding-top: 95px; box-sizing: border-box;">

            <div class="d-flex justify-content-between align-items-center mb-2">
                <h2 class="h4 mb-0 fw-bold" style="color: #0A2540 !important;">Table Schedule</h2>
                <button class="btn btn-sm px-3 py-1 fw-bold d-inline-flex align-items-center justify-content-center"
    style="background-color: #FCE7F3; color: #0A2540; border-radius: 8px; height: 31px; border: none;" 
    data-bs-toggle="modal"
    data-bs-target="#addTableModal">
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
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-chevron-custom btn-sm px-2 py-1"><i class="fa-solid fa-chevron-left"></i></button>
                <input type="text" class="form-control form-control-sm text-center fw-bold" value="2026/05/20"
                    style="width: 130px; background-color: #f8f9fa;" readonly>
                <button class="btn btn-chevron-custom btn-sm px-2 py-1"><i class="fa-solid fa-chevron-right"></i></button>
                
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
                    <button class="btn btn-chevron-custom btn-sm px-2 py-1"><i
                            class="fa-solid fa-chevron-left"></i></button>
                    <span class="fw-bold text-dark small" style="letter-spacing: 0.5px;">Showing 05/20 17:00 -
                        19:00</span>
                    <button class="btn btn-chevron-custom btn-sm px-2 py-1"><i
                            class="fa-solid fa-chevron-right"></i></button>
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
                            <tr class="shadow-sm" style="background-color: #ffffff; border-color: #e9ecef;">
                                <td class="text-start ps-3 border-top border-bottom border-start border-end rounded-start-3 table-clickable"
                                    style="background-color: #f8f9fa;" onclick="openEditModal('Table 1', 2)">
                                    <div class="fw-bold text-dark small">Table 1</div>
                                    <div class="text-muted" style="font-size: 11px;">2 seats</div>
                                </td>
                                <td class="border-top border-bottom border-end"></td>
                                <td class="border-top border-bottom border-end"></td>
                                <td class="border-top border-bottom border-end"></td>
                                <td class="border-top border-bottom border-end"></td>
                                <td colspan="4" class="p-1 border-top border-bottom border-end rounded-end-3">
                                    <div class="text-white text-start p-2 rounded-3 shadow-sm h-100 d-flex flex-column justify-content-center"
                                        style="background-color: #0A2540; min-height: 48px; cursor: pointer;"
                                        onclick="openReservationModal('RM001', 'John Smith', '18:00', '2 hours', 2, 'Table 1', false)">
                                        <div class="fw-bold" style="font-size: 12px;">John Smith</div>
                                        <div style="font-size: 10px; opacity: 0.8;">2 guests</div>
                                    </div>
                                </td>
                            </tr>

                            <tr class="shadow-sm" style="background-color: #ffffff; border-color: #e9ecef;">
                                <td class="text-start ps-3 border-top border-bottom border-start border-end rounded-start-3 table-clickable"
                                    style="background-color: #f8f9fa;" onclick="openEditModal('Table 2', 4)">
                                    <div class="fw-bold text-dark small">Table 2</div>
                                    <div class="text-muted" style="font-size: 11px;">4 seats</div>
                                </td>
                                <td class="border-top border-bottom border-end"></td>
                                <td class="border-top border-bottom border-end"></td>
                                <td class="border-top border-bottom border-end"></td>
                                <td class="border-top border-bottom border-end"></td>
                                <td colspan="4" class="p-1 border-top border-bottom border-end rounded-end-3">
                                    <div class="text-start p-2 rounded-3 shadow-sm h-100 d-flex flex-column justify-content-center"
                                        style="background-color: #FCE7F3; color: #0A2540; min-height: 48px; cursor: pointer;"
                                        onclick="openReservationModal('RM002', 'Maria Garcia', '18:00', '2.5 hours', 4, 'Table 2', true)">
                                        <div class="fw-bold" style="font-size: 12px;">Maria Garcia</div>
                                        <div class="fw-bold" style="font-size: 10px; opacity: 0.7;">4 guests</div>
                                    </div>
                                </td>
                            </tr>

                            <style>
                                .disabled-table {
                                    background-color: #f1f3f5 !important;
                                    opacity: 0.7;
                                }

                                .disabled-table .table-title-cell {
                                    background-color: #e9ecef !important;
                                    cursor: not-allowed !important;
                                    pointer-events: auto !important;
                                }

                                .disabled-timeline-cell {
                                    background-color: #f1f3f5 !important;
                                    pointer-events: none;
                                }

                                /* 💡 通常時：背景 #FCE7F3、文字色 ネイビー（#0A2540）を確実に固定 */
                                .btn.btn-add-main-custom {
                                    background-color: #FCE7F3 !important;
                                    color: #0A2540 !important;
                                    border: none !important;
                                    padding: 0 16px;
                                    transition: all 0.2s ease-in-out;
                                }

                                /* ホバー時：少しだけ濃いピンクオブジェクトにして、クリックできる感を出す */
                                .btn.btn-add-main-custom:hover {
                                    background-color: #fbcfe8 !important;
                                    color: #0A2540 !important;
                                }
                            </style>
                            <tr class="shadow-sm disabled-table" style="border-color: #cbd5e1;">
                                <td class="text-start ps-3 border-top border-bottom border-start border-end rounded-start-3 table-clickable table-title-cell"
                                    onclick="openEditModal('Table 3', 2, true)">
                                    <div class="fw-bold text-secondary small text-decoration-line-through">Table 3</div>
                                    <div class="text-danger fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">
                                        DISABLED</div>
                                </td>
                                <td class="border-top border-bottom border-end disabled-timeline-cell"></td>
                                <td class="border-top border-bottom border-end disabled-timeline-cell"></td>
                                <td class="border-top border-bottom border-end disabled-timeline-cell"></td>
                                <td class="border-top border-bottom border-end disabled-timeline-cell"></td>
                                <td class="border-top border-bottom border-end disabled-timeline-cell"></td>
                                <td class="border-top border-bottom border-end disabled-timeline-cell"></td>
                                <td class="border-top border-bottom border-end disabled-timeline-cell"></td>
                                <td class="border-top border-bottom border-end rounded-end-3 disabled-timeline-cell"></td>
                            </tr>

                            <tr class="shadow-sm" style="background-color: #ffffff; border-color: #e9ecef;">
                                <td class="text-start ps-3 border-top border-bottom border-start border-end rounded-start-3 table-clickable"
                                    style="background-color: #f8f9fa;" onclick="openEditModal('Table 4', 6)">
                                    <div class="fw-bold text-dark small">Table 4</div>
                                    <div class="text-muted" style="font-size: 11px;">6 seats</div>
                                </td>
                                @for ($j = 0; $j < 7; $j++)
                                    <td class="border-top border-bottom border-end"></td>
                                @endfor
                                <td class="border-top border-bottom border-end rounded-end-3"></td>
                            </tr>

                            <tr class="shadow-sm" style="background-color: #ffffff; border-color: #e9ecef;">
                                <td class="text-start ps-3 border-top border-bottom border-start border-end rounded-start-3 table-clickable"
                                    style="background-color: #f8f9fa;" onclick="openEditModal('Table 5', 2)">
                                    <div class="fw-bold text-dark small">Table 5</div>
                                    <div class="text-muted" style="font-size: 11px;">2 seats</div>
                                </td>
                                @for ($j = 0; $j < 7; $j++)
                                    <td class="border-top border-bottom border-end"></td>
                                @endfor
                                <td class="border-top border-bottom border-end rounded-end-3"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('modals.edit_table')
    @include('modals.add_table')
    @include('modals.reservation_details')
    @include('modals.new_reservation')

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
        document.addEventListener("DOMContentLoaded", function() {
            var newResModalElem = document.getElementById('newReservationModal');
            if (newResModalElem) {
                var myModal = new bootstrap.Modal(newResModalElem);
                myModal.show();
            }
        });
    </script>
@endsection
