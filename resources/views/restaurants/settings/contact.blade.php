@extends('layouts.restaurant')


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
        }

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

        .chat-img-preview {
            max-width: 150px;
            max-height: 150px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .chat-img-preview:hover {
            opacity: 0.8;
        }

        .btn-delete-history {
            color: #dc3545;
            background: none;
            border: none;
            padding: 8px;
            border-radius: 6px;
            transition: background-color 0.2s, color 0.2s;
        }

        .btn-delete-history:hover {
            background-color: #fff5f5;
            color: #a71d2a;
        }

        .modal-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .modal-close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: transparent;
            border: none;
            color: #ffffff;
            font-size: 35px;
            font-weight: bold;
            cursor: pointer;
            line-height: 1;
            padding: 5px 10px;
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .modal-close-btn:hover {
            color: #cccccc;
            transform: scale(1.1);
        }

        .btn-followup {
            background-color: #ffffff;
            color: #0b2238;
            border: 1px solid #0b2238;
            border-radius: 8px;
        }

        .btn-followup:hover:not(:disabled) {
            background-color: #0b2238;
            color: #ffffff;
            border-color: #0b2238;
        }

        .btn-followup:disabled {
            background-color: #ffffff;
            color: #6c757d;
            border: 1px solid #ced4da;
        }
    </style>

    <div class="container py-5" style="max-width: 800px;">
        <h2 class="mb-4 fw-bold" style="color: #0f2d4a;">Contact & Support</h2>

        {{-- フラッシュメッセージ --}}
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
                    Message History <span class="badge bg-secondary rounded-circle ms-1"
                        style="font-size: 0.7rem;">{{ $activeContactsCount ?? $contacts->where('status', '!=', 'resolved')->count() }}</span>
                </button>
            </li>
        </ul>

        {{-- FAQ セクション --}}
        <div class="support-card p-4 mb-4">
            <h5 class="fw-bold mb-3">Frequently Asked Questions</h5>
            <div class="accordion accordion-flush" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold text-navy" type="button"
                            data-bs-target="#faq1">
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
                            data-bs-target="#faq2">
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
                            data-bs-target="#faq3">
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
                            data-bs-target="#faq4">
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
                            data-bs-target="#faq5">
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
            {{-- 新規メッセージ送信タブ --}}
            <div class="tab-pane fade show active" id="new-message" role="tabpanel">
                <div class="support-card p-4">
                    <h5 class="fw-bold mb-3">Send us a message</h5>

                    <form action="{{ route('restaurant.settings.contact.send') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold">Title</label>
                            <input type="text" id="title" name="title"
                                class="form-control @error('title') is-invalid @enderror"
                                placeholder="Please enter a short title" value="{{ old('title') }}">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

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
                                multiple accept="image/*">
                            <button type="button" id="btn-restaurant-upload"
                                class="btn btn-outline-secondary btn-sm rounded-3 px-3 fw-bold">
                                <i class="bi bi-paperclip"></i> Add images
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
                    </form>
                </div>
            </div>

            {{-- 履歴・チャットやり取りタブ --}}
            <div class="tab-pane fade" id="message-history" role="tabpanel">
                {{-- 一覧表示エリア --}}
                <div id="history-list-view" class="support-card p-4">
                    <h5 class="fw-bold mb-3">Message History</h5>
                    <div class="list-group list-group-flush gap-2">
                        @forelse ($contacts as $contact)
                            @php
                                // 💡 配列データ、および返信履歴の子データをJavaScriptに安全に届けるための整形
                                $attachmentsArr = is_array($contact->attachments) ? $contact->attachments : [];

                                $repliesArr = ($contact->replies ?? collect())->map(function ($reply) {
                                    return [
                                        'id' => $reply->id,
                                        'message' => $reply->message,
                                        'created_at' => $reply->created_at->toISOString(),
                                        'attachments' => is_array($reply->attachments) ? $reply->attachments : [],
                                        'is_admin' => $reply->user && $reply->user->isAdmin() ? 1 : 0,
                                    ];
                                });
                            @endphp
                            <a href="#"
                                class="history-item list-group-item list-group-item-action py-3 d-flex align-items-center justify-content-between px-3 border rounded-3 text-decoration-none shadow-sm mb-1 bg-white"
                                data-id="{{ $contact->id }}"
                                data-action="{{ route('restaurant.settings.contact.reply', $contact->id) }}"
                                data-message="{{ $contact->message }}"
                                data-time="{{ $contact->created_at->format('Y-m-d H:i') }}"
                                data-attachments='@json($attachmentsArr)'
                                data-replies='@json($repliesArr)'>
                                <div class="d-flex align-items-center w-100 justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                            style="width: 40px; height: 40px; background-color: #e8f7f0;">
                                            <i class="bi bi-chat-left-text text-success" style="font-size: 1.1rem;"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark mb-1 text-truncate"
                                                style="font-size: 0.95rem; max-width: 350px;">
                                                {{ $contact->title ?: Str::limit($contact->message, 60) }}
                                            </div>
                                            <div class="d-flex align-items-center gap-2 small text-muted">
                                                <span><i
                                                        class="bi bi-clock me-1"></i>{{ $contact->created_at->format('Y-m-d H:i') }}</span>
                                                <span
                                                    class="badge {{ $contact->status === 'resolved' ? 'bg-secondary text-white' : ($contact->status === 'replied' ? 'bg-success-subtle text-success' : 'bg-warning text-dark') }} px-2 py-1 rounded-pill">
                                                    {{ $contact->status }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($contact->status !== 'resolved')
                                        <form action="{{ route('restaurant.settings.contact.resolve', $contact->id) }}"
                                            method="POST" class="d-inline m-0">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="btn btn-outline-success btn-sm rounded-pill px-3"
                                                onclick="return confirm('Mark this message as resolved?')">
                                                Mark as resolved
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('restaurant.settings.contact.destroy', $contact->id) }}"
                                        method="POST" class="delete-history-form d-inline m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete-history" title="Delete message"
                                            onclick="return confirm('Are you sure you want to delete this message and all its chat history?')">
                                            <i class="bi bi-trash3" style="font-size: 1.1rem;"></i>
                                        </button>
                                    </form>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-4 text-muted">No message history found.</div>
                        @endforelse
                    </div>
                </div>

                {{-- 詳細（チャット画面）表示エリア --}}
                <div id="history-detail-view" class="support-card p-4 d-none">
                    <div class="d-flex align-items-center mb-4 justify-content-between">
                        <div class="d-flex align-items-center">
                            <button type="button" id="btn-back-to-list" class="btn btn-back p-0 fw-normal me-2">←
                                Back</button>
                            <h5 class="fw-bold mb-0 ms-2" style="color: #0f2d4a; font-size: 1.15rem;">Discussion</h5>
                        </div>
                    </div>

                    <div id="chat-log-container" class="chat-log mb-4 d-flex flex-column gap-3"
                        style="max-height: 400px; overflow-y: auto;"></div>

                    <form id="follow-up-form" action="" method="POST" enctype="multipart/form-data"
                        class="border-top pt-4">
                        @csrf
                        <h6 class="fw-bold mb-2 text-dark" style="font-size: 0.95rem;">Add a follow-up message</h6>
                        <div class="mb-3">
                            <textarea id="follow-up-textarea" name="message" class="form-control" rows="3"
                                placeholder="Type your follow-up question..." required></textarea>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <input type="file" id="followup-attachments" name="attachments[]" class="d-none"
                                    multiple accept="image/*">
                                <button type="button" id="btn-followup-upload"
                                    class="btn btn-outline-secondary btn-sm rounded-3 px-3 fw-semibold">
                                    <i class="bi bi-paperclip"></i> Attach files
                                </button>
                                <div id="followup-file-preview" class="form-text mt-1 small text-primary"></div>
                            </div>
                            <button type="submit" id="btn-send-followup" class="btn btn-followup fw-semibold px-4 py-2"
                                disabled>
                                <i class="bi bi-send me-2"></i>Send Follow-up
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- 画像拡大用モーダル --}}
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0 position-relative">
                <button type="button" class="btn-close btn-close-white position-absolute" data-bs-dismiss="modal"
                    aria-label="Close" style="top: 15px; right: 15px; z-index: 1060; width: 20px; height: 20px;">
                </button>
                <div class="modal-body text-center p-0">
                    <img id="modalTargetImage" src="" class="img-fluid rounded shadow" data-bs-dismiss="modal"
                        style="max-height: 85vh; cursor: pointer;" title="クリックで閉じる">
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // FAQ アコーディオン
            document.querySelectorAll('.accordion-button').forEach(button => {
                button.removeAttribute('data-bs-toggle');
                button.addEventListener('click', function() {
                    const target = document.querySelector(this.getAttribute('data-bs-target'));
                    const isCollapsed = this.classList.toggle('collapsed');
                    this.setAttribute('aria-expanded', !isCollapsed);
                    target.classList.toggle('show');
                });
            });

            // 新規アップロード処理
            const restUploadBtn = document.getElementById('btn-restaurant-upload');
            const restFileInput = document.getElementById('restaurant-attachments');
            const restPreviewArea = document.getElementById('restaurant-file-preview');

            if (restUploadBtn && restFileInput) {
                restUploadBtn.addEventListener('click', () => restFileInput.click());
                restFileInput.addEventListener('change', function() {
                    if (this.files.length > 0) {
                        let fileNames = Array.from(this.files).map(f =>
                            `<i class="bi bi-file-earmark-check me-1"></i>${f.name}`);
                        restPreviewArea.innerHTML = `Selected: ${fileNames.join(', ')}`;
                    } else {
                        restPreviewArea.innerHTML = '';
                    }
                });
            }

            // フォローアップ用アップロード処理
            const followupUploadBtn = document.getElementById('btn-followup-upload');
            const followupFileInput = document.getElementById('followup-attachments');
            const followupPreviewArea = document.getElementById('followup-file-preview');

            if (followupUploadBtn && followupFileInput) {
                followupUploadBtn.addEventListener('click', () => followupFileInput.click());
                followupFileInput.addEventListener('change', function() {
                    if (this.files.length > 0) {
                        let fileNames = Array.from(this.files).map(f => f.name);
                        followupPreviewArea.textContent = `Selected: ${fileNames.join(', ')}`;
                    } else {
                        followupPreviewArea.textContent = '';
                    }
                });
            }

            const confirmCheck = document.getElementById('confirmCheck');
            const submitBtn = document.getElementById('submitBtn');
            if (confirmCheck && submitBtn) {
                confirmCheck.addEventListener('change', function() {
                    submitBtn.disabled = !this.checked;
                });
            }

            // チャットログのロードと切り替え制御
            const historyListView = document.getElementById('history-list-view');
            const historyDetailView = document.getElementById('history-detail-view');
            const btnBackToList = document.getElementById('btn-back-to-list');
            const chatLogContainer = document.getElementById('chat-log-container');
            const followUpForm = document.getElementById('follow-up-form');

            document.querySelectorAll('.history-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    if (e.target.closest('.delete-history-form') || e.target.closest(
                            '.btn-delete-history')) {
                        return;
                    }
                    e.preventDefault();

                    const contactId = this.getAttribute('data-id');
                    const mainMessage = this.getAttribute('data-message');
                    const mainTime = this.getAttribute('data-time');

                    // 配列データを安全に解析
                    const attachments = JSON.parse(this.getAttribute('data-attachments') || '[]');
                    const replies = JSON.parse(this.getAttribute('data-replies') || '[]');

                    const actionUrl = this.getAttribute('data-action');
                    if (actionUrl) {
                        followUpForm.action = actionUrl;
                    } else {
                        followUpForm.action = `/restaurant/settings/contact/${contactId}/reply`;
                    }

                    chatLogContainer.innerHTML = '';

                    // 1. 親メッセージ（送信主：オーナー自身）を表示
                    appendChatBubble('user', mainMessage, mainTime, attachments);

                    // 2. 返信ラリーをループ表示
                    replies.forEach(reply => {
                        // 💡 更正ポイント: user_idではなく、安全な「is_admin」フラグで送信者をカチッと仕分けます
                        const role = (reply.is_admin == 1) ? 'support' : 'user';

                        let replyTime = reply.created_at;
                        if (reply.created_at && reply.created_at.includes('T')) {
                            try {
                                replyTime = new Date(reply.created_at).toLocaleString(
                                    'ja-JP', {
                                        timeStyle: 'short',
                                        dateStyle: 'medium'
                                    }).replace(/\//g, '-');
                            } catch (err) {
                                replyTime = reply.created_at;
                            }
                        }

                        appendChatBubble(role, reply.message, replyTime, reply.attachments);
                    });

                    historyListView.classList.add('d-none');
                    historyDetailView.classList.remove('d-none');
                    chatLogContainer.scrollTop = chatLogContainer.scrollHeight;
                });
            });

            // 削除ボタンのクリックバブリング（伝播）をブロック
            document.querySelectorAll('.delete-history-form').forEach(form => {
                form.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });

            if (btnBackToList) {
                btnBackToList.addEventListener('click', function() {
                    historyDetailView.classList.add('d-none');
                    historyListView.classList.remove('d-none');
                });
            }

            const followUpTextarea = document.getElementById('follow-up-textarea');
            const btnSendFollowup = document.getElementById('btn-send-followup');

            if (followUpTextarea && btnSendFollowup) {
                followUpTextarea.addEventListener('input', function() {
                    btnSendFollowup.disabled = (this.value.trim().length === 0);
                });
            }

            // 📸 吹き出し生成および添付画像のバインドロジック
            function appendChatBubble(sender, text, time, images) {
                const bubble = document.createElement('div');
                let imgHtml = '';

                const imageArray = Array.isArray(images) ? images : [];
                if (imageArray.length > 0) {
                    imgHtml = '<div class="mt-2 d-flex flex-wrap gap-2">';
                    imageArray.forEach(path => {
                        if (path && typeof path === 'string') {
                            // バックスラッシュ等のエスケープノイズをクリーニング
                            const cleanPath = path.replace(/\\/g, '');
                            imgHtml +=
                                `<img src="/storage/${cleanPath}" class="chat-img-preview border" style="max-width: 120px; max-height: 120px; object-fit: cover; cursor: pointer; border-radius: 8px;" onclick="viewImage('/storage/${cleanPath}')">`;
                        }
                    });
                    imgHtml += '</div>';
                }

                if (sender === 'user') {
                    bubble.className = 'align-self-end text-end w-100 d-flex flex-column align-items-end mb-2';
                    bubble.style.maxWidth = '80%';
                    bubble.innerHTML = `
                    <div class="p-3 text-start rounded-3 text-white shadow-sm" style="background-color: #0b2238; font-size: 0.95rem; display: inline-block; max-width: 100%;">
                        <div style="white-space: pre-wrap;">${escapeHTML(text)}</div>
                        ${imgHtml}
                    </div>
                    <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">${time}</small>
                `;
                } else {
                    bubble.className =
                        'align-self-start text-start w-100 d-flex flex-column align-items-start mb-2';
                    bubble.style.maxWidth = '80%';
                    bubble.innerHTML = `
                    <div class="p-3 rounded-3 text-dark shadow-sm bg-light border" style="font-size: 0.95rem; display: inline-block; max-width: 100%;">
                        <strong style="color: #0b2238;"><i class="bi bi-shield-check"></i> Support Team:</strong><br>
                        <div class="mt-1" style="white-space: pre-wrap;">${escapeHTML(text)}</div>
                        ${imgHtml}
                    </div>
                    <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">${time}</small>
                `;
                }
                chatLogContainer.appendChild(bubble);
            }

            window.viewImage = function(src) {
                const modalImg = document.getElementById('modalTargetImage');
                if (modalImg) {
                    modalImg.src = src;
                    const myModal = new bootstrap.Modal(document.getElementById('imageModal'));
                    myModal.show();
                }
            }

            function escapeHTML(str) {
                if (!str) return '';
                return str.replace(/[&<>'"]/g, tag => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    "'": '&#39;',
                    '"': '&quot;'
                } [tag] || tag));
            }
        });
    </script>
@endsection
