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
                            How do I cancel a reservation?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse">
                        <div class="accordion-body text-muted">
                            Go to My Bookings, find your reservation, and click 'Cancel'. You must cancel at least 24 hours
                            before your reservation time to avoid a cancellation fee.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold text-navy" type="button"
                            data-bs-toggle="collapse" data-bs-target="#faq2">
                            How do I leave a review?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse">
                        <div class="accordion-body text-muted">
                            You can leave a review by going to the restaurant's page after your reservation is completed.
                            You can also add photos to your review.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold text-navy" type="button"
                            data-bs-toggle="collapse" data-bs-target="#faq3">
                            What if the restaurant cancels my reservation?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse">
                        <div class="accordion-body text-muted">
                            If a restaurant cancels your reservation, you will receive an email notification and a full
                            refund immediately if any payment was made. We apologize for the inconvenience.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold text-navy" type="button"
                            data-bs-toggle="collapse" data-bs-target="#faq4">
                            How do I change my account information?
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse">
                        <div class="accordion-body text-muted">
                            Go to Settings from the navigation bar to update your profile, email, or password.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold text-navy" type="button"
                            data-bs-toggle="collapse" data-bs-target="#faq5">
                            Is there a fee to use Pin+81?
                        </button>
                    </h2>
                    <div id="faq5" class="accordion-collapse collapse">
                        <div class="accordion-body text-muted">
                            Pin+81 is completely free for customers. You only pay the restaurant for your reservation (if
                            required).
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="tab-content" id="supportTabContent">

    {{-- ✉️ 1. 新規問い合わせタブ --}}
    <div class="tab-pane fade show active" id="new-message" role="tabpanel">
        <div class="support-card p-4">
            <h5 class="fw-bold mb-3">Send us a message</h5>

            <form action="{{ route('customer.contact.send') }}" method="POST" enctype="multipart/form-data">
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
                    <input type="file" id="attachments" name="attachments[]" class="d-none" multiple>
                    <button type="button" id="btn-upload-trigger"
                        class="btn btn-outline-secondary btn-sm rounded-3 px-3 fw-bold">
                        <i class="bi bi-paperclip"></i> Add images or files
                    </button>
                    <div id="file-list-preview" class="form-text mt-2 text-primary fw-semibold"></div>
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

    {{-- 📜 2. 履歴一覧 ＆ 詳細チャットタブ --}}
    <div class="tab-pane fade" id="message-history" role="tabpanel">

        {{-- 📝 A. 履歴リスト表示エリア --}}
        <div id="history-list-view" class="support-card p-4">
            <h5 class="fw-bold mb-3">Message History</h5>

            <div class="list-group list-group-flush gap-2">
                @forelse ($contacts as $contact)
                    <a href="#"
                        class="history-item list-group-item list-group-item-action py-3 d-flex align-items-center justify-content-between px-3 border rounded-3 text-decoration-none shadow-sm mb-1 bg-white"
                        data-id="{{ $contact->id }}">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                style="width: 40px; height: 40px; background-color: #e8f7f0;">
                                <i class="bi bi-chat-left-text text-success" style="font-size: 1.1rem;"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">
                                    {{ $contact->title ?? 'Inquiry' }}
                                </div>
                                <div class="d-flex align-items-center gap-2 small text-muted">
                                    <span><i class="bi bi-clock me-1"></i>{{ $contact->created_at->format('Y-m-d H:i') }}</span>
                                    <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill"
                                        style="font-size: 0.75rem; font-weight: 500;">{{ $contact->status }}</span>
                                </div>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-muted small"></i>
                    </a>
                @empty
                    <div class="text-center text-muted py-4">No message history found.</div>
                @endforelse
            </div>
        </div>

        {{-- 💬 B. チャット詳細表示エリア --}}
        <div id="history-detail-view" class="support-card p-4 d-none">
            <div class="d-flex align-items-center mb-4">
                <button type="button" id="btn-back-to-list" class="btn btn-back p-0 fw-bold me-2">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </button>
                <h5 id="detail-view-title" class="fw-bold mb-0" style="color: #0f2d4a;">Subject</h5>
            </div>

            <div class="chat-log mb-4 d-flex flex-column gap-3">
                @foreach ($contacts as $contact)
                    <div class="chat-thread d-none" id="thread-{{ $contact->id }}">
                        <div class="d-flex flex-column gap-3">

                            {{-- 1️⃣ 親メッセージ（最初に自分が送った内容） --}}
                            <div class="align-self-end text-end" style="max-width: 80%;">
                                <div class="p-3 text-start rounded-3 text-white shadow-sm"
                                    style="background-color: #0b2238; font-size: 0.95rem;">
                                    {{ $contact->message }}

                                    {{-- 📸 親メッセージに画像が添付されている場合 --}}
                                    @if(!empty($contact->attachments))
                                        <div class="chat-attachments mt-2 d-flex flex-wrap gap-2 justify-content-end">
                                            @foreach($contact->attachments as $path)
                                                <img src="{{ asset('storage/' . $path) }}" 
                                                     class="img-thumbnail zoomable-img" 
                                                     style="max-width: 150px; max-height: 150px; cursor: pointer; object-fit: cover;" 
                                                     alt="Attachment">
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <small class="text-muted d-block mt-1"
                                    style="font-size: 0.75rem;">{{ $contact->created_at->format('Y-m-d H:i') }}</small>
                            </div>

                            {{-- 子メッセージ（Adminからの返信や、自分のFollow-up） --}}
                            @foreach ($contact->replies as $reply)
                                @if ($reply->user_id == Auth::id())
                                    {{-- 2️⃣ 子メッセージ：自分（右側） --}}
                                    <div class="align-self-end text-end" style="max-width: 80%;">
                                        <div class="p-3 text-start rounded-3 text-white shadow-sm"
                                            style="background-color: #0b2238; font-size: 0.95rem;">
                                            {{ $reply->message }}

                                            {{-- 📸 自分の返信に画像が添付されている場合 --}}
                                            @if(!empty($reply->attachments))
                                                <div class="chat-attachments mt-2 d-flex flex-wrap gap-2 justify-content-end">
                                                    @foreach($reply->attachments as $path)
                                                        <img src="{{ asset('storage/' . $path) }}" 
                                                             class="img-thumbnail zoomable-img" 
                                                             style="max-width: 150px; max-height: 150px; cursor: pointer; object-fit: cover;" 
                                                             alt="Attachment">
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        <small class="text-muted d-block mt-1"
                                            style="font-size: 0.75rem;">{{ $reply->created_at->format('Y-m-d H:i') }}</small>
                                    </div>
                                @else
                                    {{-- 3️⃣ 子メッセージ：Adminなど相手（左側） --}}
                                    <div class="align-self-start" style="max-width: 80%;">
                                        <div class="p-3 rounded-3 text-dark shadow-sm bg-light border"
                                            style="font-size: 0.95rem;">
                                            {{ $reply->message }}

                                            {{-- 📸 相手の返信に画像が添付されている場合 --}}
                                            @if(!empty($reply->attachments))
                                                <div class="chat-attachments mt-2 d-flex flex-wrap gap-2">
                                                    @foreach($reply->attachments as $path)
                                                        <img src="{{ asset('storage/' . $path) }}" 
                                                             class="img-thumbnail zoomable-img" 
                                                             style="max-width: 150px; max-height: 150px; cursor: pointer; object-fit: cover;" 
                                                             alt="Attachment">
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        <small class="text-muted d-block mt-1"
                                            style="font-size: 0.75rem;">{{ $reply->created_at->format('Y-m-d H:i') }}</small>
                                    </div>
                                @endif
                            @endforeach

                        </div>
                    </div>
                @endforeach
            </div>

            {{-- 🔄 返信フォーム --}}
            <div class="border-top pt-4">
                <h6 class="fw-bold mb-2 text-dark">Add a follow-up message</h6>

                <form action="{{ route('customer.contact.send') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="parent_id" id="form-parent-id" value="">

                    <div class="mb-3">
                        <textarea name="message" class="form-control" rows="3" placeholder="Type your follow-up question..." required></textarea>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <input type="file" id="reply-attachments" name="attachments[]" class="d-none" multiple>

                        <button type="button" id="btn-reply-upload-trigger"
                            class="btn btn-outline-secondary btn-sm rounded-3 px-3 fw-bold">
                            <i class="bi bi-paperclip"></i> Attach files
                        </button>

                        <button type="submit" class="btn fw-bold px-4 py-2"
                            style="background-color: #0b2238; color: #fff; border: none; border-radius: 8px;">
                            <i class="bi bi-send me-2"></i>Send Follow-up
                        </button>
                    </div>

                    <div id="reply-file-list-preview" class="form-text mt-2 text-primary fw-semibold"></div>
                </form>
            </div>

        </div>
    </div>

</div>

{{-- ======================================================= --}}
{{-- ✨ 画像拡大用モーダル（タブの外側に綺麗に配置しました） --}}
{{-- ======================================================= --}}
<div class="modal fade" id="imageZoomModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0 position-relative">
            <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body p-0 text-center">
                <img id="zoomed-image" src="" class="img-fluid rounded-3 shadow-lg" style="max-height: 85vh;">
            </div>
        </div>
    </div>
</div>

    <script>
        // FAQ アコーディオンの手動制御
        document.querySelectorAll('.accordion-button').forEach(button => {
            button.removeAttribute('data-bs-toggle');
            button.addEventListener('click', function() {
                const target = document.querySelector(this.getAttribute('data-bs-target'));
                const isCollapsed = this.classList.toggle('collapsed');
                this.setAttribute('aria-expanded', !isCollapsed);
                target.classList.toggle('show');
            });
        });

        // 📎 添付ファイルボタンのトリガーとプレビュー制御
        const uploadTrigger = document.getElementById('btn-upload-trigger');
        const realFileInput = document.getElementById('attachments');
        const fileListPreview = document.getElementById('file-list-preview');

        uploadTrigger.addEventListener('click', () => {
            realFileInput.click();
        });

        realFileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                let fileNames = [];
                for (let i = 0; i < this.files.length; i++) {
                    fileNames.push(`<i class="bi bi-file-earmark-check me-1"></i>${this.files[i].name}`);
                }
                fileListPreview.innerHTML = `Selected: ${fileNames.join(', ')}`;
            } else {
                fileListPreview.innerHTML = '';
            }
        });

        // ✅ 送信前確認チェックボックスの制御
        const confirmCheck = document.getElementById('confirmCheck');
        const submitBtn = document.getElementById('submitBtn');

        confirmCheck.addEventListener('change', function() {
            submitBtn.disabled = !this.checked;
        });

        // 🔄 一覧と詳細メッセージの画面切り替え・連動ロジック
        const historyListView = document.getElementById('history-list-view');
        const historyDetailView = document.getElementById('history-detail-view');
        const detailViewTitle = document.getElementById('detail-view-title');
        const btnBackToList = document.getElementById('btn-back-to-list');

        // 各履歴アイテムをクリックした時の処理
        document.querySelectorAll('.history-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();

                // 1. クリックした要素内のタイトルテキストを取得して詳細画面のヘッダーにセット
                const title = this.querySelector('.fw-bold').textContent.trim();
                detailViewTitle.textContent = title;

                // 2. クリックされた会話のID（data-id）を取得
                const id = this.getAttribute('data-id');

                // ✨【重要】返信フォームの隠しパラメータ（parent_id）にこのIDを自動セット
                const formParentId = document.getElementById('form-parent-id');
                if (formParentId) {
                    formParentId.value = id;
                }

                // 3. すべてのスレッドを一旦非表示にして、クリックされたIDのスレッドだけを表示
                document.querySelectorAll('.chat-thread').forEach(thread => {
                    thread.classList.add('d-none');
                });

                const activeThread = document.getElementById(`thread-${id}`);
                if (activeThread) {
                    activeThread.classList.remove('d-none');
                }

                // 4. 一覧ビューを隠し、詳細チャットビューを表示
                historyListView.classList.add('d-none');
                historyDetailView.classList.remove('d-none');
            });
        });

        // 「Back」ボタンを押して一覧に戻る時の処理
        btnBackToList.addEventListener('click', function() {
            historyDetailView.classList.add('d-none');
            historyListView.classList.remove('d-none');
        });
        // 📎 【追加】返信フォーム用の添付ファイルボタンとプレビュー制御
        const replyUploadTrigger = document.getElementById('btn-reply-upload-trigger');
        const replyRealFileInput = document.getElementById('reply-attachments');
        const replyFileListPreview = document.getElementById('reply-file-list-preview');

        if (replyUploadTrigger && replyRealFileInput) {
            // 見た目のボタンをクリックした時に実際のinput[type=file]をクリックさせる
            replyUploadTrigger.addEventListener('click', () => {
                replyRealFileInput.click();
            });

            // ファイルが選択されたらファイル名を画面に表示する
            replyRealFileInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    let fileNames = [];
                    for (let i = 0; i < this.files.length; i++) {
                        fileNames.push(`<i class="bi bi-file-earmark-check me-1"></i>${this.files[i].name}`);
                    }
                    replyFileListPreview.innerHTML = `Selected: ${fileNames.join(', ')}`;
                } else {
                    replyFileListPreview.innerHTML = '';
                }
            });
        }
        // 🔍 添付画像の拡大モーダル制御
    const imageZoomModalEl = document.getElementById('imageZoomModal');
    
    // BootstrapのModalインスタンスを手動生成（前回の制御ロジックと合わせるため）
    if (imageZoomModalEl) {
        // 画像が新しく追加された場合にも対応できるよう、document全体でクリックを監視
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('zoomable-img')) {
                const src = e.target.getAttribute('src');
                const zoomedImage = document.getElementById('zoomed-image');
                
                if (zoomedImage) {
                    zoomedImage.setAttribute('src', src);
                    
                    // モーダルを表示させるクラス操作（アコーディオンと同様の手動トグル手法）
                    imageZoomModalEl.classList.add('show');
                    imageZoomModalEl.style.display = 'block';
                    document.body.classList.add('modal-open');
                    
                    // 背景のオーバーレイ（黒幕）を作成して追加
                    const backdrop = document.createElement('div');
                    backdrop.className = 'modal-backdrop fade show';
                    backdrop.id = 'modal-custom-backdrop';
                    document.body.appendChild(backdrop);
                }
            }
        });

        // モーダルの閉じるボタン、または黒幕クリックで閉じる処理
        const closeModalFn = function() {
            imageZoomModalEl.classList.remove('show');
            imageZoomModalEl.style.display = 'none';
            document.body.classList.remove('modal-open');
            const backdrop = document.getElementById('modal-custom-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
        };

        // 閉じるボタン、およびモーダルの背景クリックで閉じる
        imageZoomModalEl.addEventListener('click', function(e) {
            if (e.target === imageZoomModalEl || e.target.classList.contains('btn-close')) {
                closeModalFn();
            }
        });
    }
    </script>
@endsection
