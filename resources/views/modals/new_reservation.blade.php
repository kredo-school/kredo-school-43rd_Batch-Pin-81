<style>

    .new-res-content {
        background-color: #ffffff !important;
        border: none !important;
        border-radius: 16px !important;
        padding: 20px;
    }
    .new-res-header {
        border-bottom: none !important;
        padding-bottom: 0 !important;
    }
    .new-res-body {
        color: #0A2540 !important;
    }
    .new-res-footer {
        border-top: none !important;
        padding-top: 10px !important;
    }

    /* ラベル・値オブジェクトのスタイル */
    .new-res-label {
        color: #6c757d;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 2px;
    }
    .new-res-value {
        color: #0A2540;
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 16px;
    }

    /*  Acceptボタン（初期は緑枠・背景透明 ➔ ホバーで緑塗りつぶし） */
    .btn-new-res-accept {
        background-color: transparent !important;
        border: 1px solid #198754 !important;
        color: #198754 !important;
        border-radius: 8px !important;
        font-weight: bold;
        padding: 12px;
        font-size: 15px;
        transition: all 0.2s ease-in-out;
    }
    .btn-new-res-accept:hover {
        background-color: #198754 !important;
        color: #ffffff !important;
    }

    /*  Declineボタン（初期は赤枠・背景透明 ➔ ホバーで赤塗りつぶし） */
    .btn-new-res-decline {
        background-color: transparent !important;
        border: 1px solid #DC3545 !important;
        color: #DC3545 !important;
        border-radius: 8px !important;
        font-weight: bold;
        padding: 12px;
        font-size: 15px;
        transition: all 0.2s ease-in-out;
    }
    .btn-new-res-decline:hover {
        background-color: #DC3545 !important;
        color: #ffffff !important;
    }

    /*  確認画面のBackボタン（塗りつぶしネイビー） */
    .btn-new-res-back {
        background-color: #0A2540 !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 8px !important;
        font-weight: bold;
        padding: 12px;
        font-size: 15px;
        transition: all 0.2s ease-in-out;
    }
    .btn-new-res-back:hover {
        opacity: 0.9 !important;
    }
</style>

<div class="modal fade" id="newReservationModal" tabindex="-1" aria-labelledby="newReservationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
        <div class="modal-content new-res-content shadow-lg">
            
            <div class="modal-header new-res-header d-flex justify-content-between align-items-start">
                <div>
                    <h4 class="modal-title fw-bold" id="newReservationModalLabel" style="color: #0A2540; font-family: 'Poppins', sans-serif;">New Reservation!</h4>
                    <p class="text-secondary small fw-medium mb-0" id="newReservationModalSub" style="font-size: 14px;">Immediate booking within 1 hour</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 14px;"></button>
            </div>
            
            <div id="newResMainView" class="modal-body new-res-body mt-3">
                <div class="row">
                    <div class="col-12">
                        <div class="new-res-label">Customer</div>
                        <div class="new-res-value">Lisa Anderson</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="new-res-label">Time</div>
                        <div class="new-res-value">20:15</div>
                    </div>
                    <div class="col-6">
                        <div class="new-res-label">Guests</div>
                        <div class="new-res-value">2</div>
                    </div>
                </div>
            </div>

            <div id="newResDeclineConfirmView" class="modal-body new-res-body mt-4 text-center d-none">
                <h5 class="fw-bold mb-2" style="font-size: 18px; color: #0A2540;">Decline reservation for Lisa Anderson?</h5>
                <p class="text-secondary small mb-0" style="font-size: 14px;">This will reject the customer's request, and they will be notified.</p>
            </div>
            
            <div class="modal-footer new-res-footer d-flex gap-2">
                <div id="newResNormalActions" class="d-flex w-100 gap-2">
                    <button type="button" class="btn btn-new-res-accept flex-grow-1" id="btnAcceptNewRes">Accept</button>
                    <button type="button" class="btn btn-new-res-decline flex-grow-1" onclick="showNewResDeclineConfirm()">Decline</button>
                </div>

                <div id="newResConfirmActions" class="d-flex w-100 gap-2 d-none">
                    <button type="button" class="btn btn-new-res-back fw-bold py-2 px-4" onclick="hideNewResDeclineConfirm()">Back</button>
                    <button type="button" class="btn btn-new-res-decline fw-bold py-2 flex-grow-1">Yes, Decline Reservation</button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    /**
     *  Declineの確認ビュー
     */
    function showNewResDeclineConfirm() {
        // タイトルとサブテキストの変更
        document.getElementById('newReservationModalLabel').innerText = 'Decline Reservation';
        document.getElementById('newReservationModalSub').innerText = 'Confirm booking rejection';
        
        document.getElementById('newResMainView').classList.add('d-none');
        document.getElementById('newResDeclineConfirmView').classList.remove('d-none');
        
        // ボタンエリアの切り替え
        document.getElementById('newResNormalActions').classList.add('d-none');
        document.getElementById('newResConfirmActions').classList.remove('d-none');
    }

    function hideNewResDeclineConfirm() {
        document.getElementById('newReservationModalLabel').innerText = 'New Reservation!';
        document.getElementById('newReservationModalSub').innerText = 'Immediate booking within 1 hour';
        
        // 表示の切り替え
        document.getElementById('newResMainView').classList.remove('d-none');
        document.getElementById('newResDeclineConfirmView').classList.add('d-none');
        
        // ボタンエリアの切り替え
        document.getElementById('newResConfirmActions').classList.add('d-none');
        document.getElementById('newResNormalActions').classList.remove('d-none');
    }

    // モーダルが完全に閉じられたときに自動で通常画面表示へリセット
    document.addEventListener("DOMContentLoaded", function() {
        var modalElement = document.getElementById('newReservationModal');
        if (modalElement) {
            modalElement.addEventListener('hidden.bs.modal', function () {
                hideNewResDeclineConfirm();
            });
        }
    });
</script>