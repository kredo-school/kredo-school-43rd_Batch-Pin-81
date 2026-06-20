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
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
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
        background-color: #fffbe0; /* 優しいクリーム色の背景 */
        border-radius: 16px;
        width: 100%;
        max-width: 500px;
        padding: 30px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
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
        background-color: #f8f9fa;
    }
    .btn-verify {
        background-color: #0f2c59;
        color: #fff;
        font-weight: 600;
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        border: none;
    }
    .btn-verify:hover {
        background-color: #1d3e6e;
    }
</style>

<div class="container main-container">
    <h1 class="page-title">Owner Account</h1>

    {{-- フラッシュメッセージ（成功・エラー） --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card-custom">
        <h2 class="card-title-custom">Account Information</h2>
        
        <form action="{{ route('restaurant.owner_account.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label form-label-custom">Owner Name</label>
                    <input type="text" name="owner_name" class="form-control form-control-custom" value="{{ old('owner_name', $restaurant->owner_name ?? '') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label form-label-custom">Manager Name</label>
                    <input type="text" name="manager_name" class="form-control form-control-custom" value="{{ old('manager_name', $restaurant->manager_name ?? '') }}">
                </div>

                <div class="col-12">
                    <label class="form-label form-label-custom">Email</label>
                    <input type="email" class="form-control form-control-custom text-muted" value="{{ $restaurant->email ?? '' }}" readonly>
                    <div class="form-text text-muted" style="font-size: 12px; margin-top: 4px;">Email cannot be changed directly.</div>
                </div>

                <div class="col-12">
                    <label class="form-label form-label-custom">Phone Number</label>
                    <input type="text" name="phone" class="form-control form-control-custom" value="{{ old('phone', $restaurant->phone ?? '') }}" required>
                </div>

                <div class="col-12">
                    <label class="form-label form-label-custom">New Password</label>
                    <input type="password" name="password" class="form-control form-control-custom" placeholder="Enter new password">
                </div>

                <div class="col-12">
                    <label class="form-label form-label-custom">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control form-control-custom" placeholder="Confirm new password">
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-save">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- 🔑 2段階認証用オーバーレイモーダル (未認証時のみ出現) --}}
@if(!$isVerified)
<div class="verification-overlay" id="verificationOverlay">
    <div class="verification-modal">
        <button type="button" class="modal-close-btn" onclick="window.history.back();"><i class="bi bi-x-lg"></i></button>

        <div class="d-flex align-items-center gap-2 mb-2" style="color: #0f2c59;">
            <i class="bi bi-shield-lock-fill fs-4"></i>
            <h5 class="m-0" style="font-weight: 700;">Owner Verification Required</h5>
        </div>
        <p style="font-size: 13px; color: #6c757d; margin-bottom: 24px;">This section requires owner authentication</p>

        <div class="mb-3">
            <label class="form-label" style="font-size: 13px; font-weight: 600; color: #0f2c59;">Verification Method</label>
            <select class="form-select" style="border-radius: 8px; padding: 10px;" disabled>
                <option selected>Email</option>
            </select>
        </div>

        <button type="button" class="btn btn-send-code" id="btnSendCode" onclick="sendCode()">
            Send Code to {{ $restaurant->email ?? 'your registered email' }}
        </button>

        <div class="mb-4">
            <label class="form-label" style="font-size: 13px; font-weight: 600; color: #0f2c59;">Verification Code</label>
            <input type="text" id="verificationCode" class="form-control" placeholder="Enter 6-digit code" style="border-radius: 8px; padding: 10px;" maxlength="6">
            <div class="invalid-feedback" id="errorMessage"></div>
        </div>

        <button type="button" class="btn btn-verify" onclick="verifyCode()">Verify & Access</button>
    </div>
</div>
@endif

<script>
    // 認証コードを送信するAjax処理
    function sendCode() {
        const btn = document.getElementById('btnSendCode');
        btn.disabled = true;
        btn.innerText = 'Sending...';

        fetch("{{ route('restaurant.owner_account.send_code') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
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

        if(!code) {
            codeInput.classList.add('is-invalid');
            errorDiv.innerText = 'Please enter the code.';
            return;
        }

        fetch("{{ route('restaurant.owner_account.verify') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ code: code })
        })
        .then(async response => {
            const data = await response.json();
            if(response.ok && data.success) {
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