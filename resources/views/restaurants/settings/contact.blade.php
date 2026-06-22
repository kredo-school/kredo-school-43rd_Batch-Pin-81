@extends('layouts.app')

@section('title', 'Contact')

@section('content')
    <style>
        body {
            background-color: #f8f9fa;
            color: #0f2d4a;
        }

        .nav-pills .nav-link {
            color: #6c757d;
            background-color: #fff;
            border: 1px solid #dee2e6;
            margin-right: 10px;
            border-radius: 20px;
            padding: 6px 20px;
        }

        .nav-pills .nav-link.active {
            background-color: #0b2238;
            color: #fff;
            border-color: #0b2238;
        }

        .support-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e9ecef;
        }

        .accordion-button:not(.collapsed) {
            background-color: #fff;
            color: #0f2d4a;
            box-shadow: none;
        }

        .accordion-button:focus {
            box-shadow: none;
            border-color: rgba(0, 0, 0, .125);
        }

        .btn-submit {
            background-color: #0b2238;
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 8px;
            transition: background-color 0.2s ease, opacity 0.2s ease;
        }

        .btn-submit:hover:not(:disabled) {
            background-color: #143554;
            color: #fff;
        }

        .btn-submit:disabled {
            background-color: #8596a6;
            color: #fff;
            opacity: 1;
            cursor: not-allowed;
        }

        .status-badge {
            background-color: #d1e7dd;
            color: #0f5132;
            font-size: 0.75rem;
            padding: 4px 8px;
            border-radius: 12px;
        }

        .text-navy {
            color: #0a2540 !important;
        }

        .accordion-item {
            background-color: #fff !important;
            border-radius: 8px;
            margin-bottom: 4px;
            transition: all 0.2s ease;
        }

        .accordion-item:hover {
            background-color: #fff !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transform: translateY(-1px);
        }

        .accordion-item:hover .text-navy {
            color: #143554 !important;
        }

        .accordion-button {
            background-color: #fff !important;
            border-radius: 8px !important;
        }

        .list-group-item-action {
            background-color: #fff !important;
            transition: all 0.2s ease;
            cursor: pointer;
            border: 1px solid transparent;
            border-radius: 8px;
            margin-bottom: 4px;
        }

        .list-group-item-action:hover {
            background-color: #fff !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-color: #e9ecef;
            transform: translateY(-1px);
            padding-left: 8px !important;
            padding-right: 8px !important;
        }

        /* ーーー FAQアコーディオンの開閉矢印（∨ / ∧）の連動設定 ーーー */
        .accordion-button::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%230f2d4a'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
            transition: transform 0.2s ease-in-out;
        }

        .accordion-button:not(.collapsed)::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%230b2238'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
            transform: rotate(180deg) !important;
        }

        .bg-success-subtle {
            background-color: #dff6eb !important;
        }

        .btn-back {
            background: none;
            border: none;
            color: #8596a6;
            font-size: 1.05rem;
            transition: color 0.2s ease;
        }

        .btn-back:hover {
            color: #0f2d4a;
        }

        .btn-followup {
            background-color: #8596a6;
            color: #fff;
            border: none;
            border-radius: 8px;
            transition: background-color 0.2s ease;
        }

        .btn-followup:not(:disabled) {
            background-color: #0b2238;
            cursor: pointer;
        }

        .btn-followup:not(:disabled):hover {
            background-color: #143554;
        }
    </style>

    <div class="container py-5" style="max-width: 800px;">
        <h2 class="mb-4 fw-bold" style="color: #0f2d4a;">Contact & Support</h2>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert"
                style="background-color: #d1e7dd; color: #0f5132; border-radius: 12px;">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <ul class="nav nav-pills mb-4" id="supportTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold" id="new-message-tab" data-bs-toggle="pill"
                    data-bs-target="#new-message" type="button" role="tab">New Message</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold" id="message-history-tab" data-bs-toggle="pill"
                    data-bs-target="#message-history" type="button" role="tab">
                    Message History <span class="badge bg-secondary rounded-circle ms-1" style="font-size: 0.7rem;">2</span>
                </button>
            </li>
        </ul>

        <div class="support-card p-4 mb-4">
            <h5 class="fw-bold mb-3">Frequently Asked Questions</h5>
            <div class="accordion accordion-flush" id="faqAccordion">

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold text-navy" type="button"
                            data-bs-toggle="collapse" data-bs-target="#faq1">
                            How do I change my restaurant's operating hours?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse">
                        <div class="accordion-body text-muted">
                            Go to Restaurant Information and scroll to the Operating Hours section. You can set different
                            hours for each day of the week and mark days as closed.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold text-navy" type="button"
                            data-bs-toggle="collapse" data-bs-target="#faq2">
                            What payment methods are accepted?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse">
                        <div class="accordion-body text-muted">
                            Pin+81 accepts credit cards, debit cards, and bank transfers. All payments are processed
                            securely through our payment gateway.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold text-navy" type="button"
                            data-bs-toggle="collapse" data-bs-target="#faq3">
                            How do I cancel my subscription?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse">
                        <div class="accordion-body text-muted">
                            You can cancel your subscription anytime from the Owner Account > Subscription section. Your
                            service will continue until the end of your current billing period.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold text-navy" type="button"
                            data-bs-toggle="collapse" data-bs-target="#faq4">
                            How long does it take to receive payments?
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse">
                        <div class="accordion-body text-muted">
                            Payments are typically deposited to your bank account within 3-5 business days after the
                            reservation is completed.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold text-navy" type="button"
                            data-bs-toggle="collapse" data-bs-target="#faq5">
                            Can I export my reservation data?
                        </button>
                    </h2>
                    <div id="faq5" class="accordion-collapse collapse">
                        <div class="accordion-body text-muted">
                            Yes, you can export your reservation data as CSV from the Reservations page using the Export
                            button.
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="tab-content" id="supportTabContent">

            <div class="tab-pane fade show active" id="new-message" role="tabpanel">
                <div class="support-card p-4">
                    <h5 class="fw-bold mb-3">Send us a message</h5>

                    <form action="{{ route('restaurant.settings.contact.send') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="message" class="form-label fw-semibold">Message</label>
                            <textarea id="message" name="message" class="form-control @error('message') is-invalid @enderror" rows="4"
                                placeholder="Please describe your issue in detail..." required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block fw-semibold">Attach Files (optional)</label>
                            <input type="file" id="restaurant-attachments" name="attachments[]" class="d-none"
                                multiple>
                            <button type="button" id="btn-restaurant-upload"
                                class="btn btn-outline-secondary btn-sm rounded-3 px-3 fw-bold">
                                <i class="bi bi-paperclip"></i> Add images or files
                            </button>
                            <div id="restaurant-file-preview" class="form-text mt-2 text-primary fw-semibold"></div>
                        </div>

                        <div class="form-check mb-4 bg-light p-3 rounded border">
                            <input class="form-check-input ms-0 me-2" type="checkbox" id="confirmCheck" required>
                            <label class="form-check-label small" for="confirmCheck">
                                I confirm that the above information is correct and I want to send this message to the
                                Pin+81 support team.
                            </label>
                        </div>

                        <button type="submit" id="submitBtn" class="btn btn-submit w-100 fw-bold" disabled>
                            <i class="bi bi-send me-2"></i>Send Message
                        </button>
                        <div class="text-center text-muted small mt-2">
                            We typically respond within 24 hours on business days
                        </div>
                    </form>
                </div>
            </div>

            <div class="tab-pane fade" id="message-history" role="tabpanel">
                <div id="history-list-view" class="support-card p-4">
                    <h5 class="fw-bold mb-3">Message History</h5>

                    <div class="list-group list-group-flush gap-2">

                        <a href="#"
                            class="history-item list-group-item list-group-item-action py-3 d-flex align-items-center justify-content-between px-3 border rounded-3 text-decoration-none shadow-sm mb-1 bg-white"
                            data-title="Subscription inquiry"
                            data-messages='[
                                {"sender": "user", "text": "I would like to know more about upgrading our plan.", "time": "2026-05-10 09:30"},
                                {"sender": "support", "text": "Thank you for reaching out! We offer premium plans starting at ¥9,800/month. Please check your email for the full pricing breakdown.", "time": "2026-05-10 14:20"}
                            ]'>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                    style="width: 40px; height: 40px; background-color: #e8f7f0;">
                                    <i class="bi bi-chat-left-text text-success" style="font-size: 1.1rem;"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">Subscription inquiry
                                    </div>
                                    <div class="d-flex align-items-center gap-2 small text-muted">
                                        <span><i class="bi bi-clock me-1"></i>2026-05-10 09:30</span>
                                        <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill"
                                            style="font-size: 0.75rem; font-weight: 500;">replied</span>
                                    </div>
                                </div>
                            </div>
                            <i class="bi bi-chevron-right text-muted small"></i>
                        </a>

                        <a href="#"
                            class="history-item list-group-item list-group-item-action py-3 d-flex align-items-center justify-content-between px-3 border rounded-3 text-decoration-none shadow-sm bg-white"
                            data-title="Dashboard access issue"
                            data-messages='[
                                {"sender": "user", "text": "We are unable to access our dashboard since this morning.", "time": "2026-05-08 11:15"},
                                {"sender": "support", "text": "Our team has resolved the access issue. Please try logging in again and let us know if the problem persists.", "time": "2026-05-08 12:00"}
                            ]'>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                    style="width: 40px; height: 40px; background-color: #e8f7f0;">
                                    <i class="bi bi-chat-left-text text-success" style="font-size: 1.1rem;"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">Dashboard access issue
                                    </div>
                                    <div class="d-flex align-items-center gap-2 small text-muted">
                                        <span><i class="bi bi-clock me-1"></i>2026-05-08 11:15</span>
                                        <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill"
                                            style="font-size: 0.75rem; font-weight: 500;">replied</span>
                                    </div>
                                </div>
                            </div>
                            <i class="bi bi-chevron-right text-muted small"></i>
                        </a>

                    </div>
                </div>

                <div id="history-detail-view" class="support-card p-4 d-none">
                    <div class="d-flex align-items-center mb-4">
                        <button type="button" id="btn-back-to-list" class="btn btn-back p-0 fw-normal me-2">
                            <span style="font-size: 0.95rem; font-family: sans-serif; margin-right: 4px;">←</span>Back
                        </button>
                        <h5 id="detail-view-title" class="fw-bold mb-0 ms-2" style="color: #0f2d4a; font-size: 1.15rem;">
                        </h5>
                    </div>

                    <div id="chat-log-container" class="chat-log mb-4 d-flex flex-column gap-3"></div>

                    <div class="border-top pt-4">
                        <h6 class="fw-bold mb-2 text-dark" style="font-size: 0.95rem;">Add a follow-up message</h6>
                        <div class="mb-3">
                            <textarea id="follow-up-textarea" class="form-control" rows="3" placeholder="Type your follow-up question..."></textarea>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 px-3 fw-semibold"
                                style="border-color: #dee2e6; color: #6c757d;">
                                <i class="bi bi-paperclip"></i> Attach files
                            </button>
                            <button type="button" id="btn-send-followup" class="btn btn-followup fw-semibold px-4 py-2"
                                disabled>
                                <i class="bi bi-send me-2"></i>Send Follow-up
                            </button>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
    <script>
        document.querySelectorAll('.accordion-button').forEach(button => {
            button.removeAttribute('data-bs-toggle');
            button.addEventListener('click', function() {
                const target = document.querySelector(this.getAttribute('data-bs-target'));
                const isCollapsed = this.classList.toggle('collapsed');
                this.setAttribute('aria-expanded', !isCollapsed);
                target.classList.toggle('show');
            });
        });

        const restUploadBtn = document.getElementById('btn-restaurant-upload');
        const restFileInput = document.getElementById('restaurant-attachments');
        const restPreviewArea = document.getElementById('restaurant-file-preview');

        restUploadBtn.addEventListener('click', () => {
            restFileInput.click();
        });

        restFileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                let fileNames = [];
                for (let i = 0; i < this.files.length; i++) {
                    fileNames.push(`<i class="bi bi-file-earmark-check me-1"></i>${this.files[i].name}`);
                }
                restPreviewArea.innerHTML = `Selected: ${fileNames.join(', ')}`;
            } else {
                restPreviewArea.innerHTML = '';
            }
        });

        const confirmCheck = document.getElementById('confirmCheck');
        const submitBtn = document.getElementById('submitBtn');

        confirmCheck.addEventListener('change', function() {
            submitBtn.disabled = !this.checked;
        });

        // ーーー 一覧と個別詳細メッセージの動的切り替えロジック ーーー
        const historyListView = document.getElementById('history-list-view');
        const historyDetailView = document.getElementById('history-detail-view');
        const detailViewTitle = document.getElementById('detail-view-title');
        const btnBackToList = document.getElementById('btn-back-to-list');
        const chatLogContainer = document.getElementById('chat-log-container');

        document.querySelectorAll('.history-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();

                // 件名を動的に設定
                const title = this.getAttribute('data-title');
                detailViewTitle.textContent = title;

                // メッセージ配列データをパースしてチャット欄をクリア＆再構築
                const messages = JSON.parse(this.getAttribute('data-messages'));
                chatLogContainer.innerHTML = '';

                messages.forEach(msg => {
                    const bubble = document.createElement('div');
                    if (msg.sender === 'user') {
                        bubble.className = 'align-self-end text-end';
                        bubble.style.maxWidth = '80%';
                        bubble.innerHTML = `
                            <div class="p-3 text-start rounded-3 text-white shadow-sm" style="background-color: #0b2238; font-size: 0.95rem;">
                                ${escapeHTML(msg.text)}
                            </div>
                            <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">${msg.time}</small>
                        `;
                    } else {
                        bubble.className = 'align-self-start';
                        bubble.style.maxWidth = '80%';
                        bubble.innerHTML = `
                            <div class="p-3 rounded-3 text-dark shadow-sm bg-light border" style="font-size: 0.95rem;">
                                ${escapeHTML(msg.text)}
                            </div>
                            <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">${msg.time}</small>
                        `;
                    }
                    chatLogContainer.appendChild(bubble);
                });

                // 表示の切り替え
                historyListView.classList.add('d-none');
                historyDetailView.classList.remove('d-none');
                chatLogContainer.scrollTop = chatLogContainer.scrollHeight;
            });
        });

        btnBackToList.addEventListener('click', function() {
            historyDetailView.classList.add('d-none');
            historyListView.classList.remove('d-none');
        });

        const followUpTextarea = document.getElementById('follow-up-textarea');
        const btnSendFollowup = document.getElementById('btn-send-followup');

        // テキスト入力の有無を検知してボタン色と状態を切り替える
        followUpTextarea.addEventListener('input', function() {
            if (this.value.trim().length > 0) {
                btnSendFollowup.disabled = false;
            } else {
                btnSendFollowup.disabled = true;
            }
        });

        // 送信ボタン押下時の送信処理
        btnSendFollowup.addEventListener('click', function() {
            const messageText = followUpTextarea.value.trim();
            if (messageText === '') return;

            // 現在日時（2026年システム時間想定フォーマット）を作成
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const formattedTime = `${year}-${month}-${day} ${hours}:${minutes}`;

            // 新しいチャットバブル（ユーザー側）を現在開いているスレッドの最下部へ動的に生成
            const newBubble = document.createElement('div');
            newBubble.className = 'align-self-end text-end';
            newBubble.style.maxWidth = '80%';
            newBubble.innerHTML = `
                <div class="p-3 text-start rounded-3 text-white shadow-sm" style="background-color: #0b2238; font-size: 0.95rem;">
                    ${escapeHTML(messageText)}
                </div>
                <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">${formattedTime}</small>
            `;

            // チャットログに追加して下部へ自動スクロール
            chatLogContainer.appendChild(newBubble);
            chatLogContainer.scrollTop = chatLogContainer.scrollHeight;

            // 入力欄をクリアしてボタンを再び初期化（グレー無効化）
            followUpTextarea.value = '';
            btnSendFollowup.disabled = true;
        });

        // XSS防止用のエスケープ関数
        function escapeHTML(str) {
            return str.replace(/[&<>'"]/g,
                tag => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    "'": '&#39;',
                    '"': '&quot;'
                } [tag] || tag)
            );
        }
    </script>
@endsection
