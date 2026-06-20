@extends('layouts.app')

@section('title', 'Setting')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <div class="bg-light min-vh-100 py-4 py-md-5">
        <div class="container" style="max-width: 700px;">

            <div class="text-start mb-4" style="padding-left: 5px;">
                <h2 class="fw-bold mb-0" style="color: #0A2540; font-size: 28px; font-family: 'Poppins', sans-serif;">
                    Settings
                </h2>
            </div>

            {{-- Profile Information Form --}}
            <form class="needs-validation" action="#" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background-color: #ffffff;">
                    <h6 class="fw-bold mb-4 text-navy" style="font-size: 1rem;">Profile Information</h6>

                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-bold text-navy small mb-1">First Name</label>
                            <input type="text" name="first_name"
                                class="form-control border-0 rounded-3 px-3 py-2 shadow-none" placeholder="John"
                                style="background-color: #f4f6f9;"
                                value="{{ old('first_name', Auth::user()->first_name ?? '') }}" required>
                            <div class="invalid-feedback small">Required</div>
                        </div>

                        <div class="col-6">
                            <label class="form-label fw-bold text-navy small mb-1">Last Name</label>
                            <input type="text" name="last_name"
                                class="form-control border-0 rounded-3 px-3 py-2 shadow-none" placeholder="Doe"
                                style="background-color: #f4f6f9;"
                                value="{{ old('last_name', Auth::user()->last_name ?? '') }}" required>
                            <div class="invalid-feedback small">Required</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold text-navy small mb-1">Email</label>
                            <input type="email" name="email"
                                class="form-control border-0 rounded-3 px-3 py-2 shadow-none"
                                placeholder="john.doe@example.com" style="background-color: #f4f6f9;"
                                value="{{ old('email', Auth::user()->email ?? '') }}" required>
                            <div class="invalid-feedback small">Valid email required</div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-pink-custom w-100 fw-bold py-2 rounded-3">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>

            {{-- Change Password Form --}}
            <form class="needs-validation" action="#" method="POST" novalidate>
                @csrf
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background-color: #ffffff;">
                    <h6 class="fw-bold mb-4 text-navy" style="font-size: 1rem;">Change Password</h6>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold text-navy small mb-1">Current Password</label>
                            <input type="password" name="current_password"
                                class="form-control border-0 rounded-3 px-3 py-2 shadow-none" placeholder="••••••••"
                                style="background-color: #f4f6f9;" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold text-navy small mb-1">New Password</label>
                            <input type="password" name="new_password"
                                class="form-control border-0 rounded-3 px-3 py-2 shadow-none" placeholder="••••••••"
                                style="background-color: #f4f6f9;" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold text-navy small mb-1">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation"
                                class="form-control border-0 rounded-3 px-3 py-2 shadow-none" placeholder="••••••••"
                                style="background-color: #f4f6f9;" required>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-pink-custom w-100 fw-bold py-2 rounded-3">
                            Update Password
                        </button>
                    </div>
                </div>
            </form>

            {{-- Partner Link --}}
            <a href="{{ url('restaurant/register') }}" class="text-decoration-none d-block mb-4">
                <div class="card border shadow-sm rounded-4 p-3 custom-partner-card"
                    style="background-color: #ffffff;">
                    <div class="d-flex align-items-center justify-content-between w-100 text-dark">
                        <div class="d-flex align-items-center">
                            <div class="rounded-3 d-flex align-items-center justify-content-center me-3"
                                style="width: 40px; height: 40px; border: 1px solid #FBC4D6; background-color: #FCE7F3;">
                                <i class="fa-solid fa-store text-navy" style="font-size: 1.1rem;"></i>
                            </div>
                            <div>
                                <p class="fw-bold mb-0 text-navy" style="font-size: 0.95rem;">Partner with Us</p>
                                <p class="text-muted small mb-0" style="font-size: 0.8rem;">Register your restaurant on Pin+81</p>
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-right text-secondary" style="font-size: 0.9rem;"></i>
                    </div>
                </div>
            </a>

            <button type="button" class="btn btn-logout-custom w-100 fw-bold py-2 rounded-3 d-flex align-items-center justify-content-center gap-2" data-bs-toggle="modal" data-bs-target="#logoutConfirmationModal">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Log out
            </button>

        </div>
    </div>

    <div class="modal fade" id="logoutConfirmationModal" tabindex="-1" aria-labelledby="logoutConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow" style="font-family: 'Inter', sans-serif; color: #0a2540; background-color: #fffefc">

                <div class="modal-header border-0">
                    <h5 class="modal-title" id="logoutConfirmationModalLabel">
                        Log Out
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body text-center">
                    <p class="mb-0" style="color: #0a2540;">
                        Are you sure you want to log out of your account?
                    </p>
                </div>

                <div class="modal-footer border-0 justify-content-center gap-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    
                    <form action="#" method="POST" class="d-inline mb-0">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            Log Out
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <style>
        .text-navy {
            color: #0a2540 !important;
        }

        .btn.btn-pink-custom {
            background-color: #FCE7F3 !important;
            color: #0a2540 !important;
            border: none;
            transition: all 0.2s ease-in-out;
        }

        .btn.btn-pink-custom:hover {
            background-color: #fbcfe8 !important;
            color: #0a2540 !important;
        }

        .btn.btn-logout-custom {
            background-color: #ffffff !important;
            color: #dc3545 !important;
            border: 1px solid #dc3545 !important;
            transition: all 0.2s ease-in-out;
        }

        .btn.btn-logout-custom:hover {
            background-color: #dc3545 !important;
            color: #ffffff !important;
        }

        /* 💡 モーダルオブジェクト専用のカスタムボタンオブジェクトCSS設定 */
        .btn-modal-cancel {
            background-color: #ffffff !important;
            color: #0a2540 !important;
            border: 1px solid #e2e8f0 !important;
            transition: background-color 0.2s;
        }
        .btn-modal-cancel:hover {
            background-color: #f4f6f9 !important;
        }

        .btn-modal-confirm {
            background-color: #dc3545 !important;
            color: #ffffff !important;
            border: none;
            transition: opacity 0.2s;
        }
        .btn-modal-confirm:hover {
            opacity: 0.9;
        }

        .custom-partner-card {
            transition: all 0.2s ease-in-out;
            border-color: #e2e8f0 !important;
        }

        .custom-partner-card:hover {
            background-color: #FFF0F5 !important;
            border-color: #FBC4D6 !important;
            transform: translateY(-1px);
        }

        .was-validated .form-control:invalid {
            background-color: #FFF0F5 !important;
            border-color: #FBC4D6 !important;
        }
    </style>

    <script>
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