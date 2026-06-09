@extends('layouts.app')

@section('title', 'Customer Profile')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <div class="bg-light min-vh-100 py-5">
        <div class="container" style="max-width: 850px;">
            <div class="d-flex align-items-center mb-4" style="padding-left: 10px;">
                <a href="/customer/my-page" class="text-decoration-none d-flex align-items-center justify-content-center me-3"
                    style="color: #051d3b; transition: transform 0.2s;">
                    <i class="fa-solid fa-chevron-left" style="font-size: 1.2rem;"></i>
                </a>
                <h2 class="fw-bold mb-0" style="color: #0a2540; font-size: 1.75rem; font-family: 'Poppins', sans-serif;">
                    Profile
                </h2>
            </div>

            <form class="needs-validation" action="{{ route('customer.profile.update') }}" method="POST"
                enctype="multipart/form-data" novalidate>
                @csrf

                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background-color: #ffffff;">
                    <h5 class="fw-bold mb-4 d-flex align-items-center" style="color: #0a2540;">
                        <i class="bi bi-person-circle me-2"></i>Profile Picture
                    </h5>
                    <div class="text-center py-1">
                        <div class="position-relative d-inline-block">

                            {{-- ✨ ユーザーが画像をアップロードしているか、していないかで切り替え --}}
                            @if (Auth::check() && Auth::user()->profile_image)
                                {{-- 🖼️ 画像をアップロードしている場合 --}}
                                <img id="avatar-preview" src="{{ asset('storage/' . Auth::user()->profile_image) }}"
                                    alt="Profile" class="rounded-circle object-fit-cover shadow-sm"
                                    style="width: 130px; height: 130px; border: 3px solid #f8f9fa;">
                            @else
                                {{-- 👤 画像をアップロードしていない場合（デフォルトのユーザーアイコン） --}}
                                <div id="avatar-placeholder"
                                    class="rounded-circle shadow-sm d-flex align-items-center justify-content-center"
                                    style="width: 130px; height: 130px; border: 3px solid #f8f9fa; background-color: #f1f5f9; color: #94a3b8;">
                                    <i class="fa-solid fa-circle-user" style="font-size: 130px; line-height: 1;"></i>
                                </div>
                                {{-- JavaScriptのプレビュー用に、隠しタグとしてimgも置いておきます --}}
                                <img id="avatar-preview" class="rounded-circle object-fit-cover shadow-sm d-none"
                                    style="width: 130px; height: 130px; border: 3px solid #f8f9fa;">
                            @endif

                            <label for="avatar-input"
                                class="btn position-absolute bottom-0 end-0 rounded-circle d-flex align-items-center justify-content-center p-0 shadow-sm"
                                style="width: 38px; height: 38px; background-color: #0a2540; color: white; cursor: pointer; border: 2px solid white;">
                                <i class="bi bi-camera-fill" style="font-size: 0.9rem;"></i>
                            </label>
                            <input type="file" name="avatar" id="avatar-input" class="d-none" accept="image/*"
                                onchange="previewImage(this);">
                        </div>
                        <p class="text-muted small mt-3 mb-0">Click the camera icon to upload a new photo</p>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-4" style="background-color: #ffffff;">
                    <h5 class="fw-bold mb-4 d-flex align-items-center" style="color: #0a2540; font-size: 1.15rem;">
                        <i class="fa-solid fa-address-card me-2"></i>Personal Information
                    </h5>

                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-bold small mb-1" style="color: #0a2540;">First Name</label>
                            <input type="text" name="first_name"
                                class="form-control border-0 rounded-3 px-3 py-2 shadow-none" placeholder="John"
                                style="background-color: #f4f6f9;" required>
                            <div class="invalid-feedback small">Required</div>
                        </div>

                        <div class="col-6">
                            <label class="form-label fw-bold small mb-1" style="color: #0a2540;">Last Name</label>
                            <input type="text" name="last_name"
                                class="form-control border-0 rounded-3 px-3 py-2 shadow-none" placeholder="Doe"
                                style="background-color: #f4f6f9;" required>
                            <div class="invalid-feedback small">Required</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold small mb-1" style="color: #0a2540;">Email</label>
                            <input type="email" name="email"
                                class="form-control border-0 rounded-3 px-3 py-2 shadow-none" placeholder="john@example.com"
                                style="background-color: #f4f6f9;" required>
                            <div class="invalid-feedback small">Valid email required</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold small mb-1" style="color: #0a2540;">Phone</label>
                            <input type="text" name="phone"
                                class="form-control border-0 rounded-3 px-3 py-2 shadow-none" placeholder="+1 234 567 8900"
                                style="background-color: #f4f6f9;">
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small mb-1" style="color: #0a2540;">Country</label>
                            <select name="country" class="form-select border-0 rounded-3 px-3 py-2 shadow-none"
                                style="background-color: #f4f6f9; color: #334155;">
                                <option value="United States" selected>United States</option>
                                <option value="Japan">Japan</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small mb-1" style="color: #0a2540;">Language
                                Preference</label>
                            <select name="language" class="form-select border-0 rounded-3 px-3 py-2 shadow-none"
                                style="background-color: #f4f6f9; color: #334155;">
                                <option value="English" selected>English</option>
                                <option value="Japanese">日本語 (Japanese)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn w-100 fw-bold text-navy py-2 rounded-3 custom-save-btn">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        .text-navy {
            color: #0a2540;
        }

        .custom-save-btn {
            background-color: #FCE7F3 !important;
            border: none;
            color: #0a2540;
            cursor: pointer;
            letter-spacing: 0.5px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .custom-save-btn:hover {
            background-color: #fdd6eb !important;
            color: #0a2a5e;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(5, 29, 59, 0.15);
        }

        /* バリデーション時の枠線調整 */
        .was-validated .form-control:invalid {
            background-color: #fff5f5 !important;
        }
    </style>

    <script>
        // 画像プレビュー機能
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var preview = document.getElementById('avatar-preview');
                    var placeholder = document.getElementById('avatar-placeholder');

                    // プレビュー用imgに画像を設定して表示する
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');

                    // アイコン側（placeholder）があれば非表示にする
                    if (placeholder) {
                        placeholder.classList.add('d-none');
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Bootstrap バリデーション
        (() => {
            'use strict'
            const forms = document.querySelectorAll('.needs-validation')
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
@endsection
