<style>
    /* 1つ目の確認画面：Backボタン（塗りつぶしネイビー） */
    .btn-confirm-back {
        background-color: #0A2540 !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 8px;
        font-size: 15px;
        transition: all 0.2s ease-in-out;
    }
    .btn-confirm-back:hover {
        opacity: 0.9 !important;
    }

    /*  赤枠系ボタン（初期は赤枠・背景透明 ➔ ホバーで赤塗りつぶし） */
    .btn-outline-danger-custom {
        background-color: transparent !important;
        border: 1px solid #DC3545 !important;
        color: #DC3545 !important;
        border-radius: 8px;
        font-size: 15px;
        transition: all 0.2s ease-in-out;
    }
    .btn-outline-danger-custom:hover {
        background-color: #DC3545 !important;
        color: #ffffff !important;
    }

    /*  緑枠系ボタン（初期は緑枠・背景透明 ➔ ホバーで緑塗りつぶし） */
    .btn-outline-success-custom {
        background-color: transparent !important;
        border: 1px solid #198754 !important;
        color: #198754 !important;
        border-radius: 8px;
        font-size: 15px;
        transition: all 0.2s ease-in-out;
    }
    .btn-outline-success-custom:hover {
        background-color: #198754 !important;
        color: #ffffff !important;
    }
</style>

<!-- テーブル編集モーダル -->
<div class="modal fade" id="editTableModal" tabindex="-1" aria-labelledby="editTableModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; background-color: #ffffff;">
            
            <!-- ヘッダーエリア -->
            <div class="modal-header border-0 pb-0 px-4 pt-4 d-flex flex-column align-items-start">
                <h4 class="modal-title fw-bold" id="editTableModalLabel" style="color: #0A2540;">Edit Table</h4>
                <p class="text-secondary small mb-0" id="editTableModalSub">Update table information</p>
            </div>

            <!-- 1️ 通常フォームビュー -->
            <div id="modalMainFormView" class="modal-body px-4 pt-3 pb-2">
                <div class="mb-3">
                    <label for="tableNameInput" class="form-label text-secondary small fw-medium">Table Name</label>
                    <input type="text" class="form-control" id="tableNameInput" style="border-radius: 8px; background-color: #f8f9fa;" readonly>
                </div>
                <div class="mb-2">
                    <label for="tableCapacityInput" class="form-label text-secondary small fw-medium">Seats (Capacity)</label>
                    <input type="text" class="form-control" id="tableCapacityInput" style="border-radius: 8px; background-color: #f8f9fa;" readonly>
                </div>
            </div>

            <!-- 2️ 無効化確認ビュー -->
            <div id="modalDisableConfirmView" class="modal-body px-4 pt-4 pb-3 text-center d-none">
                <h5 class="fw-bold mb-2" style="font-size: 18px; color: #0A2540;">Are you sure you want to disable this table?</h5>
                <p class="text-secondary small mb-0" style="font-size: 14px;">This table will hidden from the active schedule view immediately.</p>
            </div>

            <!-- 3️ 有効化確認ビュー -->
            <div id="modalEnableConfirmView" class="modal-body px-4 pt-4 pb-3 text-center d-none">
                <h5 class="fw-bold mb-2" style="font-size: 18px; color: #0A2540;">Enable this table?</h5>
                <p class="text-secondary small mb-0" style="font-size: 14px;">This table will appear back on the schedule layout.</p>
            </div>

            <!-- フッターボタンエリア -->
            <div class="modal-footer border-0 px-4 pb-4 pt-2 d-flex gap-2">
                <!-- 【通常画面用ボタン】 -->
                <button type="button" id="btnSaveChanges" class="btn flex-grow-1 text-white fw-bold py-2" style="background-color: #0A2540; border-radius: 8px; font-size: 15px;">Save Changes</button>
                <button type="button" id="btnDisableTable" class="btn fw-bold py-2 px-3 btn-outline-danger-custom" onclick="showConfirmView('disable')">
                    Disable Table
                </button>
                <button type="button" id="btnEnableTable" class="btn fw-bold py-2 px-3 btn-outline-success-custom d-none" onclick="showConfirmView('enable')">
                    Enable Table
                </button>

                <!-- 【確認画面用ボタン】 -->
                <button type="button" id="btnCancelConfirm" class="btn btn-confirm-back fw-bold py-2 px-4 d-none" onclick="hideConfirmView()">Back</button>
                <button type="button" id="btnExecuteDisable" class="btn fw-bold py-2 flex-grow-1 btn-outline-danger-custom d-none">Yes, Disable Table</button>
                <button type="button" id="btnExecuteEnable" class="btn fw-bold py-2 flex-grow-1 btn-outline-success-custom d-none">Yes, Enable Table</button>
            </div>

        </div>
    </div>
</div>

<script>
    /**
     *  確認ビューに切り替える関数オブジェクト
     * @param {string} mode - 'disable' または 'enable'
     */
    function showConfirmView(mode) {
        // 通常の入力フォームとボタンを隠す
        document.getElementById('modalMainFormView').classList.add('d-none');
        document.getElementById('btnSaveChanges').classList.add('d-none');
        document.getElementById('btnDisableTable').classList.add('d-none');
        document.getElementById('btnEnableTable').classList.add('d-none');

        // 共通のBackボタンを表示
        document.getElementById('btnCancelConfirm').classList.remove('d-none');

        if (mode === 'disable') {
            // ヘッダーテキストの切り替え
            document.getElementById('editTableModalLabel').innerText = 'Disable Table';
            document.getElementById('editTableModalSub').innerText = 'Confirm table deactivation';

            // 無効化用の警告文と「Yes, Disable」ボタンを表示
            document.getElementById('modalDisableConfirmView').classList.remove('d-none');
            document.getElementById('btnExecuteDisable').classList.remove('d-none');
        } else if (mode === 'enable') {
            // ヘッダーテキストの切り替え
            document.getElementById('editTableModalLabel').innerText = 'Enable Table';
            document.getElementById('editTableModalSub').innerText = 'Confirm table activation';

            // 有効化用の確認文と「Yes, Enable」ボタンを表示
            document.getElementById('modalEnableConfirmView').classList.remove('d-none');
            document.getElementById('btnExecuteEnable').classList.remove('d-none');
        }
    }

    /**
     *  通常のフォーム入力ビューに戻す関数オブジェクト
     */
    function hideConfirmView() {
        // ヘッダーを元に戻す
        document.getElementById('editTableModalLabel').innerText = 'Edit Table';
        document.getElementById('editTableModalSub').innerText = 'Update table information';

        // 確認用のテキストとボタンをすべて隠す
        document.getElementById('modalDisableConfirmView').classList.add('d-none');
        document.getElementById('modalEnableConfirmView').classList.add('d-none');
        document.getElementById('btnCancelConfirm').classList.add('d-none');
        document.getElementById('btnExecuteDisable').classList.add('d-none');
        document.getElementById('btnExecuteEnable').classList.add('d-none');

        // 通常フォームと通常のSaveボタンを再表示
        document.getElementById('modalMainFormView').classList.remove('d-none');
        document.getElementById('btnSaveChanges').classList.remove('d-none');

        // 現在のテーブルの状態（無効化されているかどうか）を判定して、適切な通常ボタンを出す
        // ※この判定は元のコードのロジック変数と連動させてください
        var isCurrentlyDisabled = document.getElementById('btnEnableTable').classList.contains('active-state-check'); 
        
        if (isCurrentlyDisabled) {
            document.getElementById('btnEnableTable').classList.remove('d-none');
        } else {
            document.getElementById('btnDisableTable').classList.remove('d-none');
        }
    }

    // モーダルが完全に閉じられた時、自動で通常画面表示へリセットするイベントリスナー
    document.getElementById('editTableModal').addEventListener('hidden.bs.modal', function () {
        hideConfirmView();
    });
</script>