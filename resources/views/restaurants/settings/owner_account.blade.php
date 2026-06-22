@extends('layouts.app')

@section('title', 'Restaurant Owner Account')

@section('content')
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #0f2c59;
        }

        .main-container {
            max-width: 800px;
            margin-top: 40px;
            margin-bottom: 40px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 24px;
        }

        .card-custom {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
            background-color: #fff;
            padding: 30px;
        }

        .card-title-custom {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 24px;
        }

        .form-label-custom {
            font-weight: 600;
            font-size: 14px;
            color: #0f2c59;
            margin-bottom: 8px;
        }

        .form-control-custom {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 10px 14px;
            color: #495057;
        }

        .form-control-custom:focus {
            background-color: #fff;
            border-color: #0f2c59;
            box-shadow: none;
        }

        .btn-save {
            background-color: #0f2c59;
            color: #fff;
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 8px;
            border: none;
        }

        .btn-save:hover {
            background-color: #1d3e6e;
            color: #fff;
        }

        /* 🔒 2段階認証用：背景全体をロックするオーバーレイスタリング */
        .verification-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(2px);
            z-index: 1050;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .verification-modal {
            background-color: #fffbe0;
            border-radius: 16px;
            width: 100%;
            max-width: 500px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            position: relative;
        }

        .modal-close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: none;
            border: none;
            font-size: 20px;
            color: #6c757d;
            cursor: pointer;
        }

        .btn-send-code {
            background-color: #fff;
            border: 1px solid #dee2e6;
            color: #0f2c59;
            font-weight: 600;
            border-radius: 8px;
            padding: 10px;
            width: 100%;
            margin-bottom: 15px;
            transition: background-color 0.2s;
        }

        .btn-send-code:hover {
            background-color: #FCE7F3;
        }

        .btn-verify {
            background-color: #1d3e6e;
            color: #fff;
            font-weight: 600;
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: none;
        }

        .btn-verify:hover {
            background-color: #0a2540;
        }

        .style-exact-photo {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .style-exact-photo .plan-trigger {
            cursor: pointer;
        }

        .style-exact-photo .plan-card-base {
            border: 1px solid #e2e8f0 !important;
            border-radius: 14px !important;
            background-color: #ffffff !important;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .style-exact-photo .plan-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0a2540;
            margin-bottom: 12px;
        }

        .style-exact-photo .plan-price {
            margin-bottom: 24px;
        }

        .style-exact-photo .price-amount {
            font-size: 2.1rem;
            font-weight: 700;
            color: #0a2540;
        }

        .style-exact-photo .price-period {
            font-size: 0.95rem;
            color: #64748b;
            margin-left: 2px;
        }

        .style-exact-photo .plan-features {
            list-style: none;
            padding: 0;
            margin: 0 0 24px 0;
        }

        .style-exact-photo .plan-features li {
            font-size: 0.95rem;
            color: #0a2540;
            margin-bottom: 12px;
            line-height: 1.5;
        }

        .style-exact-photo .plan-btn {
            display: block;
            text-align: center;
            width: 100%;
            padding: 10px 0;
            font-size: 0.95rem;
            font-weight: 600;
            color: #0a2540;
            background-color: #fdf2e9;
            border: none;
            border-radius: 8px;
            transition: all 0.15s ease;
        }

        .style-exact-photo .plan-trigger input[type="radio"]:checked+.plan-card-base {
            border: 2px solid #0a2540 !important;
            box-shadow: 0 4px 12px rgba(10, 37, 64, 0.05) !important;
        }

        .style-exact-photo .plan-trigger input[type="radio"]:checked+.plan-card-base .plan-btn {
            background-color: #0a2540 !important;
            color: #ffffff !important;
        }
    </style>

    <div class="container main-container">
        <h1 class="page-title">Owner Account</h1>

        {{-- フラッシュメッセージ（成功・エラー） --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card-custom">
            <h2 class="card-title-custom">Account Information</h2>

            <form action="{{ route('restaurant.settings.owner_account.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label form-label-custom">Last Name</label>
                        <input type="text" name="last_name" class="form-control form-control-custom"
                            value="{{ old('last_name', $restaurant->last_name ?? '') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label form-label-custom">First Name</label>
                        <input type="text" name="first_name" class="form-control form-control-custom"
                            value="{{ old('first_name', $restaurant->first_name ?? '') }}" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label form-label-custom">Email</label>
                        <input type="email" class="form-control form-control-custom text-muted"
                            value="{{ $restaurant->email ?? '' }}" readonly>
                        <div class="form-text text-muted" style="font-size: 12px; margin-top: 4px;">Email cannot be changed
                            directly.</div>
                    </div>

                    <div class="col-12">
                        <label class="form-label form-label-custom">Phone Number</label>
                        <input type="text" name="phone" class="form-control form-control-custom"
                            value="{{ old('phone', $restaurant->phone ?? '') }}" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label form-label-custom">New Password</label>
                        <input type="password" name="password" class="form-control form-control-custom"
                            placeholder="Enter new password">
                    </div>

                    <div class="col-12">
                        <label class="form-label form-label-custom">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control form-control-custom"
                            placeholder="Confirm new password">
                    </div>

                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-save">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card mt-4 shadow-sm border-0" style="border-radius: 12px; background-color: #fff">
            <div class="card-body p-4">
                <h5 class="mb-1" style="color: #0f2c59; font-weight: 700;">
                    <i class="bi bi-bank me-2"></i>Payment Information
                </h5>
                <p class="text-muted small mb-4">Bank account for receiving payments from Pin+81</p>

                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label small fw-bold" style="color: #0f2c59;">Bank Name</label>
                        <input type="text" name="bank_name" class="form-control"
                            placeholder="e.g., Sumitomo Mitsui Banking Corporation"
                            value="{{ old('bank_name', $restaurant->bank_name ?? '') }}"
                            style="border-radius: 8px; padding: 10px; background-color: #f8fafc;">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold" style="color: #0f2c59;">Branch Code</label>
                        <input type="text" name="branch_code" class="form-control" placeholder="123"
                            value="{{ old('branch_code', $restaurant->branch_code ?? '') }}"
                            style="border-radius: 8px; padding: 10px; background-color: #f8fafc;">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label small fw-bold" style="color: #0f2c59;">Account Number</label>
                        <input type="text" name="account_number" class="form-control" placeholder="1234567"
                            value="{{ old('account_number', $restaurant->account_number ?? '') }}"
                            style="border-radius: 8px; padding: 10px; background-color: #f8fafc;">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label small fw-bold" style="color: #0f2c59;">Account Holder Name
                            (Katakana)</label>
                        <input type="text" name="account_holder_name" class="form-control"
                            placeholder="Full Name"
                            value="{{ old('account_holder_name', $restaurant->account_holder_name ?? '') }}"
                            style="border-radius: 8px; padding: 10px; background-color: #f8fafc;">
                    </div>
                </div>

                <button type="submit" class="btn btn-save mt-4 px-4 py-2">Save Payment Info</button>
            </div>
        </div>

        <h5 class="mt-5 mb-3" style="color: #0a2540; font-weight: 700; font-family: sans-serif;">Subscription Plan</h5>
        <div class="row g-4 style-exact-photo">

            <div class="col-md-4">
                <label class="plan-trigger w-100 h-100 m-0">
                    <input type="radio" name="subscription_plan" value="basic" class="d-none" checked>
                    <div class="card h-100 shadow-sm plan-card-base">
                        <div class="card-body d-flex flex-column text-start p-4">
                            <h4 class="plan-name">Basic</h4>
                            <div class="plan-price">
                                <span class="price-amount">¥5,000</span><span class="price-period">/month</span>
                            </div>
                            <ul class="plan-features flex-grow-1">
                                <li>✓ Up to 50 reservations/month</li>
                                <li>✓ Basic analytics</li>
                                <li>✓ Email support</li>
                            </ul>
                            <button type="submit" class="plan-btn">Current Plan</button>
                        </div>
                    </div>
                </label>
            </div>

            <div class="col-md-4">
                <label class="plan-trigger w-100 h-100 m-0">
                    <input type="radio" name="subscription_plan" value="pro" class="d-none">
                    <div class="card h-100 shadow-sm plan-card-base">
                        <div class="card-body d-flex flex-column text-start p-4">
                            <h4 class="plan-name">Pro</h4>
                            <div class="plan-price">
                                <span class="price-amount">¥15,000</span><span class="price-period">/month</span>
                            </div>
                            <ul class="plan-features flex-grow-1">
                                <li>✓ Unlimited reservations</li>
                                <li>✓ Advanced analytics</li>
                                <li>✓ Priority support</li>
                                <li>✓ Custom branding</li>
                            </ul>
                            <button type="submit" class="plan-btn">Upgrade to Pro</button>
                        </div>
                    </div>
                </label>
            </div>

            <div class="col-md-4">
                <label class="plan-trigger w-100 h-100 m-0">
                    <input type="radio" name="subscription_plan" value="enterprise" class="d-none">
                    <div class="card h-100 shadow-sm plan-card-base">
                        <div class="card-body d-flex flex-column text-start p-4">
                            <h4 class="plan-name">Enterprise</h4>
                            <div class="plan-price">
                                <span class="price-amount">Custom</span>
                            </div>
                            <ul class="plan-features flex-grow-1">
                                <li>✓ Everything in Pro</li>
                                <li>✓ Multi-location support</li>
                                <li>✓ Dedicated manager</li>
                                <li>✓ API access</li>
                            </ul>
                            <button type="submit" class="plan-btn">Contact Sales</button>
                        </div>
                    </div>
                </label>
            </div>
        </div>
    </div>

    {{-- 🔑 2段階認証用オーバーレイモーダル (未認証時のみ出現) --}}
    @if (!$isVerified)
        <div class="verification-overlay" id="verificationOverlay">
            <div class="verification-modal">
                {{-- 🛠️ 開発中：デザイン確認のために一時的に残す場合（onclickを書き換え） --}}
                <button type="button" class="modal-close-btn"
                    onclick="document.getElementById('verificationOverlay').style.display='none';"><i
                        class="bi bi-x-lg"></i></button>

                {{-- 🔒 本番運用：完全に無効化して非表示にする場合（丸ごとコメントアウト、または削除）
                <button type="button" class="modal-close-btn" onclick="window.history.back();"><i class="bi bi-x-lg"></i></button>

                ×ボタンを押したら別のページへリダイレクトさせる
                <button type="button" class="modal-close-btn" onclick="location.href='{{ route('restaurant.dashboard') }}';"><i class="bi bi-x-lg"></i></button> --}}

                <div class="d-flex align-items-center gap-2 mb-2" style="color: #0f2c59;">
                    <i class="bi bi-shield-lock-fill fs-4"></i>
                    <h5 class="m-0" style="font-weight: 700;">Owner Verification Required</h5>
                </div>
                <p style="font-size: 13px; color: #6c757d; margin-bottom: 24px;">This section requires owner authentication
                </p>

                <div class="mb-3">
                    <label class="form-label" style="font-size: 13px; font-weight: 600; color: #0f2c59;">Verification
                        Method</label>
                    <select class="form-select" id="verificationMethod" style="border-radius: 8px; padding: 10px;"
                        onchange="updateSendButton()">
                        <option value="email" selected>Email</option>
                        <option value="sms">SMS</option>
                    </select>
                </div>

                <button type="button" class="btn btn-send-code" id="btnSendCode" onclick="sendCode()"
                    data-email="{{ $restaurant->email ?? 'your registered email' }}"
                    data-phone="{{ $restaurant->phone ?? 'your registered phone number' }}">
                    Send Code to {{ $restaurant->email ?? 'your registered email' }}
                </button>

                <div class="mb-4">
                    <label class="form-label" style="font-size: 13px; font-weight: 600; color: #0f2c59;">Verification
                        Code</label>
                    <input type="text" id="verificationCode" class="form-control" placeholder="Enter 6-digit code"
                        style="border-radius: 8px; padding: 10px;" maxlength="6">
                    <div class="invalid-feedback" id="errorMessage"></div>
                </div>

                <button type="button" class="btn btn-verify text-white" onclick="verifyCode()">Verify & Access</button>
            </div>
        </div>
    @endif

    <script>
        //「SMS」に切り替えた瞬間に、ボタンの文字が自動で電話番号に切り替わるように
        function updateSendButton() {
            const methodSelect = document.getElementById('verificationMethod');
            const sendButton = document.getElementById('btnSendCode');

            // ボタンに仕込んだ宛先データを取得
            const email = sendButton.getAttribute('data-email');
            const phone = sendButton.getAttribute('data-phone');

            // 選択された方法に応じてボタンのテキストを書き換え
            if (methodSelect.value === 'email') {
                sendButton.textContent = `Send Code to ${email}`;
            } else if (methodSelect.value === 'sms') {
                sendButton.textContent = `Send Code to ${phone}`;
            }
        }
        // 認証コードを送信するAjax処理
        function sendCode() {
            const btn = document.getElementById('btnSendCode');
            btn.disabled = true;
            btn.innerText = 'Sending...';

            fetch("{{ route('restaurant.settings.owner_account.send_code') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Verification code sent to your email.');
                        btn.innerText = 'Resend Code';
                        btn.disabled = false;
                    }
                })
                .catch(error => {
                    alert('Error sending code. Please try again.');
                    btn.disabled = false;
                    btn.innerText = 'Send Code';
                });
        }

        // 入力されたコードを検証するAjax処理
        function verifyCode() {
            const codeInput = document.getElementById('verificationCode');
            const errorDiv = document.getElementById('errorMessage');
            const code = codeInput.value;

            if (!code) {
                codeInput.classList.add('is-invalid');
                errorDiv.innerText = 'Please enter the code.';
                return;
            }

            fetch("{{ route('restaurant.settings.owner_account.verify') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        code: code
                    })
                })
                .then(async response => {
                    const data = await response.json();
                    if (response.ok && data.success) {
                        document.getElementById('verificationOverlay').remove();
                    } else {
                        codeInput.classList.add('is-invalid');
                        errorDiv.innerText = data.message || 'Verification failed.';
                    }
                })
                .catch(error => {
                    codeInput.classList.add('is-invalid');
                    errorDiv.innerText = 'An error occurred. Please try again.';
                });
        }
    </script>
@endsection
