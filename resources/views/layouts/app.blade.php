<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <title>{{ config('app.name', 'Laravel') }}</title> --}}
    <title>{{ config('app.name') }} | @yield('title', 'Portal')</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/rikako-style.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            font-family: 'Nunito', 'Helvetica Neue', Arial, sans-serif;
            background-color: #ffffff;
            /* 基本の背景は白 */
            padding-bottom: 75px;
        }

        @media (min-width: 768px) {
            body {
                padding-bottom: 0;
            }
        }

        .text-navy {
            color: #0a2540;
        }

        /* 【PC用】ナビゲーションスタイル */
        .nav-link-custom {
            font-weight: 500;
            font-size: 0.95rem;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .nav-link-custom:hover {
            color: #0a2540;
            background-color: #f1f5f9;
        }

        .nav-link-custom.active {
            color: #0a2540;
            background-color: #FCE7F3;
        }

        .custom-search-container:focus-within {
            border-color: #CFB2C4 !important;
            box-shadow: 0 0 0 0.2rem rgba(233, 192, 228, 0.25) !important;
        }

        .custom-search-btn {

            background-color: #FCE7F3;
            color: #0A2540;
            cursor: pointer;
            transition: 0.3s;
        }

        /* カーソルが当たった時（Hover）：背景が濃いピンク色に染まり、文字と枠線が白に美しく変化*/
        .custom-search-btn:hover {
            background-color: #FDD6EB;
            color: #0A2A5E;
        }

        /* 【最強化版】ツールチップ（白背景＋紺文字） */
        div.tooltip div.tooltip-inner {
            background-color: #ffffff !important;
            /* 吹き出しの背景を「白」に */
            color: #0a2540 !important;
            /* 文字の色を「紺色」に */
            font-size: 13px !important;
            font-weight: 600 !important;
            /* 文字を少し太めにして見やすく */
            padding: 8px 16px !important;
            border-radius: 12px !important;
            /* 強めの丸み */
            box-shadow: 0 6px 16px rgba(5, 29, 59, 0.15) !important;
            /* 白背景でも同化しないように、クッキリとした綺麗な影をつける */
            border: 1px solid #e2e8f0 !important;
            /* ほんの少しだけ薄いグレーの枠線をつけて輪郭をハッキリさせる */
        }

        /* 吹き出しの小さなツノ（矢印）も「白色」に統一する設定 */
        div.tooltip.bs-tooltip-bottom .tooltip-arrow::before,
        div.tooltip.bs-tooltip-auto[data-popper-placement^=bottom] .tooltip-arrow::before {
            border-bottom-color: #ffffff !important;
            /* 下側に出るツノを白に */
        }

        div.tooltip.bs-tooltip-top .tooltip-arrow::before,
        div.tooltip.bs-tooltip-auto[data-popper-placement^=top] .tooltip-arrow::before {
            border-top-color: #ffffff !important;
            /* 上側に出るツノを白に */
        }

        div.tooltip.show {
            opacity: 1 !important;
            /* Bootstrapのデフォルトの半透明をクリアしてハッキリ出す */
        }

        /* アイコンにカーソルを乗せたときの動き（フィードバック） */
        .nav-icon-link:hover,
        .nav-link:hover {
            color: #0a2540 !important;
            transform: translateY(-1px);
            transition: all 0.2s ease;
        }

        /* 【スマホ用】下部5ボタン固定フッターナビ */
        .mobile-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 65px;
            background-color: #ffffff;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
            z-index: 1030;
            display: flex;
            align-items: center;
        }

        .mobile-bottom-nav .nav-item {
            flex: 1;
            text-align: center;
        }

        .mobile-bottom-nav .nav-link-item {
            color: #64748b;
            font-size: 11px;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
        }

        .mobile-bottom-nav .nav-link-item i {
            font-size: 1.4rem;
        }

        .mobile-bottom-nav .nav-link-item.active {
            color: #0a2540;
            font-weight: bold;
        }

        /* フローティングチャットボタン */
        .chat-badge {
            position: fixed;
            bottom: 85px;
            right: 20px;
            width: 55px;
            height: 55px;
            background-color: #0a2540;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        @media (min-width: 768px) {
            .chat-badge {
                bottom: 20px;
            }
        }
    </style>
</head>

<body class="bg-light">
    <div id="app">
        {{-- Web VER --}}
        {{-- Search Ber & 8 Buttons --}}
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm border-0 d-none d-md-block sticky-top py-2">
            <div class="container d-flex align-items-center justify-content-between">

                <div class="d-flex align-items-center gap-3 flex-grow-1">
                    <a class="navbar-brand fw-bold text-navy m-0" href="#">Pin+81</a>

                    <div class="bg-white rounded-3 shadow-sm d-flex align-items-center border px-3 py-1 ms-2 custom-search-container"
                        style="max-width: 380px; flex-grow: 1;">
                        <div class="d-flex align-items-center flex-grow-1 text-secondary me-2">
                            <i class="bi bi-search me-2"></i>
                            <input type="text" class="form-control border-0 bg-transparent shadow-none text-dark p-0"
                                placeholder="Where do you want to eat?" style="font-size: 0.9rem;">
                        </div>
                    </div>
                    <button class="btn fw-bold custom-search-btn shadow-sm"
                        style="height: 35px; px-4; border-radius: 8px; font-size: 0.85rem; transition: all 0.2s ease-in-out;">
                        Search
                    </button>
                </div>

                <div class="d-flex align-items-center gap-3 bg-transparent ms-4">

                    <a href="#" class="nav-link text-decoration-none"
                        style="-webkit-text-stroke: 0.5px; color: #97a2b5; padding: 6px;" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" data-bs-title="Admin">
                        <i class="bi bi-shield-check fs-5"></i>
                    </a>

                    <a href="#" class="nav-icon-link text-decoration-none"
                        style="-webkit-text-stroke: 0.5px; color: #97a2b5; padding: 6px;" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" data-bs-title="Home">
                        <i class="bi bi-house fs-5"></i>
                    </a>

                    <a href="#" class="nav-icon-link text-decoration-none"
                        style="-webkit-text-stroke: 0.5px; color: #97a2b5; padding: 6px;" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" data-bs-title="Favorites">
                        <i class="bi bi-heart fs-5"></i>
                    </a>

                    <a href="#" class="nav-link text-decoration-none"
                        style="-webkit-text-stroke: 0.5px; color: #97a2b5; padding: 6px;" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" data-bs-title="Reservations">
                        <i class="bi bi-calendar-event fs-5"></i>
                    </a>

                    <a href="#" class="nav-link text-decoration-none"
                        style="-webkit-text-stroke: 0.5px; color: #97a2b5; padding: 6px;" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" data-bs-title="Reviews">
                        <i class="bi bi-star fs-5"></i>
                    </a>

                    <a href="#" class="nav-link text-decoration-none"
                        style="-webkit-text-stroke: 0.5px; color: #97a2b5; padding: 6px;" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" data-bs-title="Account">
                        <i class="bi bi-person fs-5"></i>
                    </a>

                    <a href="#" class="nav-link text-decoration-none"
                        style="-webkit-text-stroke: 0.5px; color: #97a2b5; padding: 6px;" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" data-bs-title="Notifications">
                        <i class="bi bi-bell fs-5"></i>
                    </a>

                    <a href="#" class="nav-link text-decoration-none"
                        style="-webkit-text-stroke: 0.5px; color: #97a2b5; padding: 6px;" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" data-bs-title="Contact">
                        <i class="bi bi-chat-left fs-5"></i>
                    </a>

                    <a href="{{ route('customer.settings') }}" class="nav-link text-decoration-none"
                        style="-webkit-text-stroke: 0.5px; color: #97a2b5; padding: 6px;" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" data-bs-title="Settings">
                        <i class="bi bi-gear fs-5"></i>
                    </a>
                </div>
            </div>
        </nav>
        {{-- Mobile VER --}}
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm border-0 d-md-none sticky-top py-2">
            <div class="container-fluid px-3 d-flex align-items-center gap-1">

                <div class="bg-white rounded-3 shadow-sm d-flex align-items-center border px-2 py-1 custom-search-container"
                    style="max-width: 180px; width: 180px; height: 35px;">
                    <div class="d-flex align-items-center flex-grow-1 text-secondary me-1">
                        <i class="bi bi-search me-1" style="font-size: 0.85rem;"></i>
                        <input type="text" class="form-control border-0 bg-transparent shadow-none text-dark p-0"
                            placeholder="Where to eat?" style="font-size: 0.8rem;">
                    </div>
                </div>

                <button class="btn rounded-3 px-2 py-1 fw-bold custom-search-btn"
                    style="font-size: 0.75rem; height: 35px; flex-shrink: 0;">
                    Search
                </button>

                <button class="navbar-toggler border-0 shadow-none p-0 text-navy fs-1 flex-shrink-0 ms-auto"
                    type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenuOffcanvas"
                    style="line-height: 1;">
                    <i class="bi bi-list"></i>
                </button>

            </div>
        </nav>

        <div class="offcanvas offcanvas-end d-md-none" tabindex="-1" id="mobileMenuOffcanvas"
            style="width: 280px;">
            <div class="offcanvas-header border-bottom py-3">
                <h5 class="offcanvas-title fw-bold text-navy fs-4">Menu</h5>
                <button type="button" class="btn-close shadow-none fs-5" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-0">
                <div class="list-group list-group-flush">
                    <a href="#"
                        class="list-group-item list-group-item-action border-0 py-3 px-4 d-flex align-items-center gap-3 fw-bold text-navy fs-6">
                        <i class="bi bi-bell fs-5 text-navy"></i> Notifications
                    </a>
                    <a href="#"
                        class="list-group-item list-group-item-action border-0 py-3 px-4 d-flex align-items-center gap-3 fw-bold text-navy fs-6">
                        <i class="bi bi-chat-right fs-5 text-navy"></i> Contact
                    </a>
                    <a href="#"
                        class="list-group-item list-group-item-action border-0 py-3 px-4 d-flex align-items-center gap-3 fw-bold text-navy fs-6">
                        <i class="bi bi-gear fs-5 text-navy"></i> Settings
                    </a>
                    <a href="#"
                        class="list-group-item list-group-item-action border-0 py-3 px-4 d-flex align-items-center gap-3 fw-bold text-danger fs-6">
                        <i class="bi bi-box-arrow-right fs-5 text-danger"></i> Log out
                    </a>
                </div>
            </div>
        </div>

        <main>
            @yield('content')
        </main>

        <a href="#" class="chat-badge"><i class="bi bi-chat-text-fill"></i></a>

        <div class="mobile-bottom-nav d-md-none">
            <div class="nav-item"><a class="nav-link-item active" href="#"><i
                        class="bi bi-house"></i><span>Home</span></a></div>
            <div class="nav-item"><a class="nav-link-item" href="#"><i
                        class="bi bi-heart"></i><span>Favorites</span></a></div>
            <div class="nav-item"><a class="nav-link-item" href="#"><i
                        class="bi bi-calendar-event"></i><span>Reservations</span></a></div>
            <div class="nav-item"><a class="nav-link-item" href="#"><i
                        class="bi bi-star"></i><span>Reviews</span></a></div>
            <div class="nav-item"><a class="nav-link-item" href="#"><i
                        class="bi bi-person"></i><span>Account</span></a></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Bootstrapが完全にグローバルに読み込まれるのを数ミリ秒だけ待ってから実行する
            setTimeout(function() {
                const currentBootstrap = window.bootstrap || (typeof bootstrap !== 'undefined' ? bootstrap :
                    null);

                if (currentBootstrap) {
                    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new currentBootstrap
                        .Tooltip(tooltipTriggerEl));
                } else {
                    console.error("Bootstrap body could not be loaded.");
                }
            }, 100); // 0.1秒だけ待つことで読込エラーを防ぐ
        });
    </script>
</body>

</html>
