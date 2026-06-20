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
        }

        .btn-submit:hover {
            background-color: #143554;
            color: #fff;
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

        /* ✨ 変更：FAQアコーディオン（背景は白を維持し、影でホバーを表現） */
        .accordion-item {
            background-color: #fff !important;
            border-radius: 8px;
            margin-bottom: 4px;
            transition: all 0.2s ease;
        }

        .accordion-item:hover {
            background-color: #fff !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            /* うっすらとした上品な影 */
            transform: translateY(-1px);
            /* 1ピクセルだけ上に浮き上がらせる */
        }

        .accordion-item:hover .text-navy {
            color: #143554 !important;
            /* 文字色を少し変化させる */
        }

        .accordion-button {
            background-color: #fff !important;
            border-radius: 8px !important;
        }

        /* ✨ 変更：メッセージ履歴（背景は白を維持し、影でホバーを表現） */
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
            /* うっすらとした上品な影 */
            border-color: #e9ecef;
            transform: translateY(-1px);
            padding-left: 8px !important;
            padding-right: 8px !important;
        }

        /* ーーー FAQアコーディオンの開閉矢印（∨ / ∧）の連動設定 ーーー */

        /* 1. 最初（閉じているとき ∨）のアイコン設定 */
        .accordion-button::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%230f2d4a'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
            transition: transform 0.2s ease-in-out;
        }

        /* 2. 開いているとき（∧）の設定 */
        /* !important を排除し、Bootstrap標準の回転（transform）を利用して ∧ に変えます */
        .accordion-button:not(.collapsed)::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%230b2238'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
            /* 元の下向き矢印の画像をそのまま180度ひっくり返して ∧ にする（Bootstrap標準の挙動） */
            transform: rotate(180deg) !important;
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

            <div class="tab-pane fade show active" id="new-message" role="tabpanel">
                <div class="support-card p-4">
                    <h5 class="fw-bold mb-3">Send us a message</h5>

                    <form action="{{ route('customer.contact.send') }}" method="POST">
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
                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-bold">
                                <i class="bi bi-paperclip"></i> Add images or files
                            </button>
                        </div>

                        <div class="form-check mb-4 bg-light p-3 rounded border">
                            <input class="form-check-input ms-0 me-2" type="checkbox" id="confirmCheck" required>
                            <label class="form-check-label small" for="confirmCheck">
                                I confirm that the above information is correct and I want to send this message to the
                                Pin+81 support team.
                            </label>
                        </div>

                        <button type="submit" class="btn btn-submit w-100 fw-bold">
                            <i class="bi bi-send me-2"></i>Send Message
                        </button>
                        <div class="text-center text-muted small mt-2">
                            We typically respond within 24 hours on business days
                        </div>
                    </form>
                </div>
            </div>

            <div class="tab-pane fade" id="message-history" role="tabpanel">
                <div class="support-card p-4">
                    <h5 class="fw-bold mb-3">Message History</h5>

                    <div class="list-group list-group-flush">
                        <a href="#"
                            class="list-group-item list-group-item-action py-3 d-flex justify-content-between align-items-center px-0">
                            <div>
                                <div class="fw-semibold"><i class="bi bi-chat-left-text me-2 text-success"></i>Reservation
                                    cancellation question</div>
                                <small class="text-muted"><i class="bi bi-clock me-1"></i>2026-05-12 10:00</small>
                            </div>
                            <span class="status-badge">replied</span>
                        </a>

                        <a href="#"
                            class="list-group-item list-group-item-action py-3 d-flex justify-content-between align-items-center px-0">
                            <div>
                                <div class="fw-semibold"><i class="bi bi-chat-left-text me-2 text-success"></i>Review
                                    photo upload issue</div>
                                <small class="text-muted"><i class="bi bi-clock me-1"></i>2026-05-09 09:20</small>
                            </div>
                            <span class="status-badge">replied</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script>
        document.querySelectorAll('.accordion-button').forEach(button => {
            button.removeAttribute('data-bs-toggle'); // Bootstrapの頑固な自動開閉システムを完全にオフにする
            button.addEventListener('click', function() {
                const target = document.querySelector(this.getAttribute('data-bs-target'));

                // 完全にあなたのやりたい仕様（開閉・矢印反転）を1対1で強制連動させます
                const isCollapsed = this.classList.toggle('collapsed');
                this.setAttribute('aria-expanded', !isCollapsed);
                target.classList.toggle('show');
            });
        });
    </script>
@endsection