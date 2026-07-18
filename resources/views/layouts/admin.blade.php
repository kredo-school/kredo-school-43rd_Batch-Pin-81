<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.min.js"></script>

    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>


    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.Pusher = Pusher;

        window.Echo = new Echo({
            broadcaster: 'reverb',

            key: "{{ env('REVERB_APP_KEY') }}",
            wsHost: "{{ env('REVERB_HOST') }}",
            wsPort: "{{ env('REVERB_PORT') }}",
            wssPort: "{{ env('REVERB_PORT') }}",

            forceTLS: false,
            enabledTransports: ['ws', 'wss'],

            auth: {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }
        });
    </script>


    <style>
        * {
            font-family: Inter, sans-serif;
        }

        body {
            background: #f8f9fa;
        }

        @media (min-width: 992px) {
            .sidebar {
                width: 280px;
                flex-shrink: 0;
                position: sticky;
                top: 0;
                height: 100vh;
            }

            .content-area {
                height: 100vh;
                overflow-y: hidden;
            }

        }

        .admin-panel {
            font-size: 12px;
        }

        .nav-link {
            color: #0a2540 !important;
            margin-top: .5rem;
            padding: 1rem;
            text-decoration: none !important;
            border-radius: 20px;
        }

        .nav-link i {
            color: #0a2540;
        }

        .nav-link:hover {
            background-color: #0a2540;
            color: white !important;
        }

        .nav-link:hover i {
            color: white !important;
        }

        .nav-link,
        .nav-link i {
            transition: .2s;
        }

        .notification-icon-wrap {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .notification-dot {
            position: absolute;
            top: -2px;
            right: -2px;
            width: 9px;
            height: 9px;
            border-radius: 999px;
            background: #dc2626;
            border: 2px solid #ffffff;
        }

        .footer-a {
            color: #0a2540 !important;
        }

        .footer-a:hover {
            text-decoration: underline !important;
        }
    </style>

</head>

<body>

    @php
        $hasUnreadNotifications = auth()->user()->unreadNotifications()->exists();
    @endphp

    <div class="container-fluid">

        <div class="row">

            <!-- SIDEBAR -->
            <div class="col-12 col-lg-auto border-end p-3 sidebar d-flex flex-column min-vh-lg-100"
                style="background: white;">

                <!-- Mobile Toggle -->
                <button class="btn btn-outline-secondary d-lg-none mb-3" type="button" data-bs-toggle="collapse"
                    data-bs-target="#sidebarMenu">

                    <i class="fa-solid fa-bars"></i>
                </button>

                <!-- Sidebar Content -->
                <div class="collapse d-lg-flex flex-column h-100 flex-grow-1" id="sidebarMenu">

                    <!-- Header -->
                    <header class="border-bottom text-center py-3">

                        <h4 class="fw-bold mb-1">
                            Pin+81
                        </h4>

                        <small class="text-secondary">
                            <i class="bi bi-shield-check"></i>
                            Admin Panel
                        </small>

                    </header>

                    <!-- Navigation -->
                    <div class="nav flex-column flex-grow-1 mt-3">

                        <a href="{{ route('admin.notifications') }}"
                            class="nav-link fw-bold d-flex justify-content-between align-items-center">

                            <span>
                                <span class="notification-icon-wrap me-2">
                                    <i class="fa-solid fa-bell"></i>
                                    @if ($hasUnreadNotifications)
                                        <span id="unread-notifications-dot" class="notification-dot"></span>
                                    @endif
                                </span>
                                Notifications
                            </span>

                        </a>

                        <a href="{{ route('admin.users') }}" class="nav-link fw-bold">

                            <span>
                                <i class="fa-solid fa-user-group me-2"></i>
                                Users

                                {{-- @if ($pendingUsers > 0)
                                <span class="badge bg-danger rounded-pill">
                                    {{ $pendingUsers }}
                                </span>
                            @endif --}}
                            </span>

                        </a>

                        <a href="{{ route('admin.restaurants') }}" class="nav-link fw-bold">

                            <span>
                                <i class="fa-solid fa-store me-2"></i>
                                Restaurants
                            </span>

                            {{-- @if ($pendingRestaurants > 0)
                            <span class="badge bg-danger rounded-pill">
                                {{ $pendingRestaurants }}
                            </span>
                        @endif --}}
                        </a>

                        <a href="{{ route('admin.reservations') }}" class="nav-link fw-bold">

                            <span>
                                <i class="fa-solid fa-calendar-check me-2"></i>
                                Reservations
                            </span>

                        </a>

                        <a href="{{ route('admin.contacts.index') }}" class="nav-link fw-bold">

                            <span>
                                <i class="fa-solid fa-envelope me-2"></i>
                                Contact
                            </span>
                        </a>

                        <a href="{{ route('admin.reviews') }}" class="nav-link fw-bold">

                            <span>
                                <i class="fa-solid fa-star me-2"></i>
                                Reviews
                            </span>

                            {{-- @if ($reportedReviews > 0)
                            <span class="badge bg-danger rounded-pill">
                                {{ $reportedReviews }}
                            </span>
                        @endif --}}


                        </a>

                        <a href="{{ route('admin.categories_features') }}" class="nav-link fw-bold">

                            <span>
                                <i class="fa-solid fa-tag me-2"></i>
                                Categories & Features & Areas
                            </span>
                        </a>

                    </div>

                    <!-- Footer -->
                    <footer class="border-top mt-3 mt-lg-auto pt-3 text-center">

                        <a href="{{ route('customer.search') }}" class="text-decoration-none footer-a">

                            ← Back to Customer View

                        </a>

                    </footer>

                </div>

            </div>

            <!-- MAIN CONTENT -->
            <div class="col-12 col-lg p-4 content-area" style="min-width: 0;">

                @yield('content')

            </div>

        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.Echo.private('App.Models.User.{{ auth()->id() }}')
                .notification((notification) => {
                    const badge = document.querySelector("#unread-notifications-count-badge");
                    const unreadCount = Number(badge?.getAttribute("data-unread-count"));
                    badge?.setAttribute("data-unread-count", unreadCount + 1);
                    badge.innerText = unreadCount;
                });
        });
    </script>

</body>

</html>
