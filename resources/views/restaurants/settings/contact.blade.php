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

        .contact-page .list-group-item-action {
            background-color: #fff !important;
            transition: all 0.2s ease;
            cursor: pointer;
            border: 1px solid transparent;
            border-radius: 8px;
            margin-bottom: 4px;
        }

        .contact-page .list-group-item-action:hover {
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
    
        /* Restaurant contact UI fix */
        .contact-page .btn-followup:not(:disabled),
        .contact-page #btn-send-followup:not(:disabled) {
            background-color: #0b2238 !important;
            color: #ffffff !important;
            border-color: #0b2238 !important;
        }

        .contact-page .btn-followup:not(:disabled):hover,
        .contact-page #btn-send-followup:not(:disabled):hover {
            background-color: #143554 !important;
            color: #ffffff !important;
            border-color: #143554 !important;
        }
        /* restaurant-contact-actions-v3:start */
        .contact-page .history-row-content {
            gap: 16px;
        }

        .contact-page .history-main-content {
            min-width: 0;
        }

        .contact-page .history-actions {
            min-width: 270px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 14px;
            flex-shrink: 0;
        }

        .contact-page .btn-resolve-history {
            min-width: 170px;
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
            font-weight: 600;
        }

        .contact-page .btn-delete-history {
            width: 44px;
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #dc3545;
            background: #ffffff;
            border: 1px solid transparent;
            padding: 0;
            border-radius: 50%;
            transition: background-color 0.2s, color 0.2s, border-color 0.2s;
        }

        .contact-page .btn-delete-history:hover {
            background-color: #fff5f5;
            color: #a71d2a;
            border-color: #f1b0b7;
        }

        .contact-page .contact-confirm-modal .modal-dialog {
            max-width: 560px;
        }

        .contact-page .contact-confirm-modal .modal-content {
            border: none;
            border-radius: 18px;
            box-shadow: 0 24px 60px rgba(10, 37, 64, 0.28);
            padding: 36px 42px 34px;
            color: #0A2540;
        }

        .contact-page .contact-confirm-modal .modal-header {
            border-bottom: none;
            padding: 0;
            align-items: flex-start;
        }

        .contact-page .contact-confirm-modal .modal-title {
            color: #0A2540;
            font-size: 2rem;
            line-height: 1.2;
            font-weight: 800;
        }

        .contact-page .contact-confirm-modal .btn-close {
            width: 1.25rem;
            height: 1.25rem;
            padding: 0;
            margin: 0;
            opacity: 0.55;
            box-shadow: none;
        }

        .contact-page .contact-confirm-modal .modal-body {
            padding: 34px 0 34px;
        }

        .contact-page .contact-confirm-modal .modal-subtitle {
            color: #0A2540;
            font-size: 1.45rem;
            line-height: 1.35;
            font-weight: 800;
            margin-bottom: 14px;
        }

        .contact-page .contact-confirm-modal .modal-message {
            color: #6c757d;
            font-size: 1.12rem;
            line-height: 1.5;
            margin: 0;
        }

        .contact-page .contact-confirm-modal .modal-footer {
            border-top: none;
            padding: 0;
            display: grid;
            grid-template-columns: 180px 1fr;
            gap: 18px;
        }

        .contact-page .contact-confirm-modal .btn-modal-cancel,
        .contact-page .contact-confirm-modal .btn-modal-submit {
            min-height: 52px;
            border-radius: 12px;
            font-size: 1.08rem;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .contact-page .contact-confirm-modal .btn-modal-cancel {
            color: #ffffff;
            background-color: #0A2540;
            border: 2px solid #0A2540;
        }

        .contact-page .contact-confirm-modal .btn-modal-resolve {
            color: #198754;
            background-color: #ffffff;
            border: 2px solid #198754;
        }

        .contact-page .contact-confirm-modal .btn-modal-resolve:hover {
            color: #ffffff;
            background-color: #198754;
        }

        .contact-page .contact-confirm-modal .btn-modal-delete {
            color: #ffffff;
            background-color: #dc3545;
            border: 2px solid #dc3545;
        }

        .contact-page .contact-confirm-modal .btn-modal-delete:hover {
            color: #ffffff;
            background-color: #bb2d3b;
            border-color: #bb2d3b;
        }

        @media (max-width: 768px) {
            .contact-page .history-row-content {
                align-items: flex-start !important;
                flex-direction: column;
            }

            .contact-page .history-actions {
                width: 100%;
                min-width: 0;
                justify-content: flex-end;
            }

            .contact-page .contact-confirm-modal .modal-content {
                padding: 28px 24px 26px;
            }

            .contact-page .contact-confirm-modal .modal-footer {
                grid-template-columns: 1fr;
            }
        }
        /* restaurant-contact-actions-v3:end */
    </style>

    <div class="container py-5 contact-page" style="max-width: 800px;">
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
                                <div class="history-row-content d-flex align-items-center w-100 justify-content-between">
                                    <div class="history-main-content d-flex align-items-center">
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
                                    <div class="history-actions" onclick="event.stopPropagation();">
                                        @if ($contact->status !== 'resolved')
                                            <button type="button"
                                                class="btn btn-outline-success btn-sm rounded-pill px-3 btn-resolve-history js-contact-action-modal"
                                                data-action="{{ route('restaurant.settings.contact.resolve', $contact->id) }}"
                                                data-method="PATCH"
                                                data-title="Confirm Resolve" data-subtitle="Mark this message as resolved?"
                                                data-message="This inquiry will be moved to resolved status."
                                                data-button-text="Yes, Mark as resolved"
                                                data-button-type="resolve">
                                                Mark as resolved
                                            </button>
                                        @else
                                            <span class="btn-resolve-history"></span>
                                        @endif

                                        <button type="button"
                                            class="btn-delete-history js-contact-action-modal"
                                            title="Delete message"
                                            data-action="{{ route('restaurant.settings.contact.destroy', $contact->id) }}"
                                            data-method="DELETE"
                                            data-title="Delete Message" data-subtitle="Delete this message?"
                                            data-message="This will delete the message and all related chat history. This action cannot be undone."
                                            data-button-text="Yes, Delete"
                                            data-button-type="delete">
                                            <i class="fa-regular fa-trash-can" style="font-size: 1.05rem;"></i>
                                        </button>
                                    </div>
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
    <!-- restaurant-contact-confirm-modal-v3:start -->
    <div class="modal fade contact-confirm-modal" id="contactActionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="contact-action-form" method="POST" class="modal-content">
                @csrf
                <input type="hidden" name="_method" id="contact-action-method" value="PATCH">
                <div class="modal-header">
                    <h5 class="modal-title" id="contactActionModalTitle">Confirm action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="modal-subtitle" id="contactActionModalSubtitle">Are you sure?</div>
                    <p class="modal-message" id="contactActionModalMessage">Please confirm this action.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">Back</button>
                    <button type="submit" id="contactActionModalSubmit" class="btn btn-modal-submit btn-modal-resolve">
                        Confirm
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- restaurant-contact-confirm-modal-v3:end -->

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
            // restaurant-contact-confirm-modal-v3:start
            const contactActionModalEl = document.getElementById('contactActionModal');
            const contactActionForm = document.getElementById('contact-action-form');
            const contactActionMethod = document.getElementById('contact-action-method');
            const contactActionModalTitle = document.getElementById('contactActionModalTitle');
            const contactActionModalSubtitle = document.getElementById('contactActionModalSubtitle');
            const contactActionModalMessage = document.getElementById('contactActionModalMessage');
            const contactActionModalSubmit = document.getElementById('contactActionModalSubmit');
            const contactActionModal = contactActionModalEl ? new bootstrap.Modal(contactActionModalEl) : null;

            document.querySelectorAll('.js-contact-action-modal').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    if (!contactActionModal || !contactActionForm) {
                        return;
                    }

                    const action = this.dataset.action;
                    const method = this.dataset.method || 'POST';
                    const title = this.dataset.title || 'Confirm action';
                    const subtitle = this.dataset.subtitle || 'Are you sure?';
                    const message = this.dataset.message || 'Please confirm this action.';
                    const buttonText = this.dataset.buttonText || 'Confirm';
                    const buttonType = this.dataset.buttonType || 'resolve';

                    contactActionForm.action = action;
                    contactActionMethod.value = method;
                    contactActionModalTitle.textContent = title;
                    contactActionModalSubtitle.textContent = subtitle;
                    contactActionModalMessage.textContent = message;
                    contactActionModalSubmit.textContent = buttonText;
                    contactActionModalSubmit.classList.remove('btn-modal-resolve', 'btn-modal-delete');
                    contactActionModalSubmit.classList.add(buttonType === 'delete' ? 'btn-modal-delete' : 'btn-modal-resolve');

                    contactActionModal.show();
                });
            });
            // restaurant-contact-confirm-modal-v3:end

            document.querySelectorAll('.history-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    if (e.target.closest('.delete-history-form') || e.target.closest('.resolve-history-form') || e.target.closest(
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
