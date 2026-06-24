<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} | @yield('title', 'Portal')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* :rootは全体に反映される内容、--から始まる名前を付けると、それが共通の変数オブジェクトになる */
        :root {
            --brand-dark-blue: #0A2540;
            /* Figmaの紺色 */
        }

        .bg-brand-navbar {
            background-color: var(--brand-dark-blue);
        }

        /* マウスを乗せたとき（hover）に、白を10%透かした背景にしてクリック感を出す */
        .list-group-item-action:hover,
        .settings-trigger:hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
        }

        /* Settingsの親ボタンの角丸設定 */
        .settings-trigger {
            border-radius: var(--bs-border-radius-lg);
        }

        /*  Settings が閉じている時（JavaScriptで .collapsed クラスが付いた時）は矢印を180度回転させて下向きにする */
        .settings-trigger.collapsed .arrow-icon {
            transform: rotate(180deg);
        }

        /* 矢印の回転アニメーションを滑らかにする設定 */
        .arrow-icon {
            transition: transform 0.2s ease;
        }

        /* Bootstrapの不具合を回避するため、独自の開閉スタイルオブジェクトを定義 */
        .custom-menu-wrap {
            max-height: 500px;
            /* 十分な高さを確保 */
            overflow: hidden;
            transition: max-height 0.25s ease-out, opacity 0.2s ease-out;
            opacity: 1;
        }

        /* 閉じている状態（.collapsedが付いている時）は、高さを0にして完全に隠す */
        .settings-trigger.collapsed+.custom-menu-wrap {
            max-height: 0px;
            opacity: 0;
            pointer-events: none;
            /* 閉じている時はクリックできないようにする */
        }
    </style>
</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-brand-navbar px-4 py-3 fixed-top">
        <div class="container-fluid d-flex justify-content-between align-items-center">

            <a class="navbar-brand d-flex align-items-center" href="{{ route('restaurant.dashboard') }}">
                <span class="fw-bold ms-2 d-none d-md-inline">
                    Pin+81 <span class="fs-6 fw-normal text-white-50 ms-2">Restaurant Portal</span>
                </span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel"
        style="background-color: #0A2540 !important; width: 300px;">
        <div class="offcanvas-header justify-content-end p-3 pb-0">
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>

        <div class="offcanvas-body p-3">
            <div class="list-group list-group-flush gap-1">

                <a href="{{ route('restaurant.dashboard') }}"
                    class="list-group-item list-group-item-action border-0 rounded-3 py-2 px-3 {{ request()->routeIs('restaurant.dashboard') ? '' : 'bg-transparent text-white-50' }}"
                    style="{{ request()->routeIs('restaurant.dashboard') ? 'background-color: rgba(255, 255, 255, 0.15); color: #fff;' : '' }}">
                    <i class="fa-solid fa-table-cells-large me-3" style="width: 20px;"></i>Dashboard
                </a>

                <a href="{{ route('restaurant.reservations')}}"
                    class="list-group-item list-group-item-action border-0 rounded-3 py-2 px-3 {{ request()->routeIs('restaurant.reservations') ? '' : 'bg-transparent text-white-50' }}"
                    style="{{ request()->routeIs('restaurant.reservations') ? 'background-color: rgba(255, 255, 255, 0.15); color: #fff;' : '' }}">
                    <i class="fa-regular fa-calendar-check me-3" style="width: 20px;"></i>Reservations
                </a>

               

                <a href="#"
                    class="list-group-item list-group-item-action border-0 rounded-3 py-2 px-3 {{ request()->routeIs('restaurant.info') ? '' : 'bg-transparent text-white-50' }}"
                    style="{{ request()->routeIs('restaurant.info') ? 'background-color: rgba(255, 255, 255, 0.15); color: #fff;' : '' }}">
                    <i class="fa-solid fa-circle-info me-3" style="width: 20px;"></i>Restaurant information
                </a>

                <a href="{{ route('restaurant.menu.index')}}"
                    class="list-group-item list-group-item-action border-0 rounded-3 py-2 px-3 {{ request()->routeIs('restaurant.menu') ? '' : 'bg-transparent text-white-50' }}"
                    style="{{ request()->routeIs('restaurant.menu') ? 'background-color: rgba(255, 255, 255, 0.15); color: #fff;' : '' }}">
                    <i class="fa-solid fa-bars me-3" style="width: 20px;"></i>Menu
                </a>

                <a href="{{ route('restaurant.photos.index')}}"
                    class="list-group-item list-group-item-action border-0 rounded-3 py-2 px-3 {{ request()->routeIs('restaurant.photos') ? '' : 'bg-transparent text-white-50' }}"
                    style="{{ request()->routeIs('restaurant.photos') ? 'background-color: rgba(255, 255, 255, 0.15); color: #fff;' : '' }}">
                    <i class="fa-regular fa-image me-3" style="width: 20px;"></i>Photos
                </a>

                <a href="#"
                    class="list-group-item list-group-item-action border-0 rounded-3 py-2 px-3 {{ request()->routeIs('restaurant.reviews') ? '' : 'bg-transparent text-white-50' }}"
                    style="{{ request()->routeIs('restaurant.reviews') ? 'background-color: rgba(255, 255, 255, 0.15); color: #fff;' : '' }}">
                    <i class="fa-regular fa-star me-3" style="width: 20px;"></i>Reviews
                </a>

                <a href="{{ route('restaurant.notifications')}}"
                    class="list-group-item list-group-item-action border-0 rounded-3 py-2 px-3 {{ request()->routeIs('restaurant.notifications') ? '' : 'bg-transparent text-white-50' }}"
                    style="{{ request()->routeIs('restaurant.notifications') ? 'background-color: rgba(255, 255, 255, 0.15); color: #fff;' : '' }}">
                    <i class="fa-regular fa-bell me-3" style="width: 20px;"></i>Notifications
                </a>
            </div>

            <div class="mt-1">
                <div class="d-flex justify-content-between align-items-center py-2 px-3 text-white-50 settings-trigger"
                    style="cursor: pointer;" id="settingsDropdown">
                    <div>
                        <i class="fa-solid fa-gear me-3" style="width: 20px;"></i>Settings
                    </div>
                    <i class="fa-solid fa-chevron-up text-white-50 small arrow-icon"></i>
                </div>

                <div id="settingsSubMenu" class="custom-menu-wrap ps-4 pb-2 d-flex flex-column gap-1">
                    <a href="#"
                        class="text-white-50 text-decoration-none py-2 px-3 small rounded-3 d-flex align-items-center list-group-item-action">
                        <i class="fa-regular fa-user me-3" style="width: 16px;"></i>Owner account
                    </a>
                    <a href="#"
                        class="text-white-50 text-decoration-none py-2 px-3 small rounded-3 d-flex align-items-center list-group-item-action">
                        <i class="fa-solid fa-phone me-3" style="width: 16px;"></i>Contact
                    </a>
                    <a href="#"
                        class="text-danger text-decoration-none py-2 px-3 small rounded-3 d-flex align-items-center list-group-item-action">
                        <i class="fa-solid fa-arrow-right-from-bracket me-3" style="width: 16px;"></i>Logout
                    </a>
                </div>
            </div>

        </div>
    </div>

    <main style="margin-top: 100px;">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var dropdown = document.getElementById('settingsDropdown');

            dropdown.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                // 親項目に「collapsed」というクラス名があるかないかをトグル（自動切り替え）するだけ
                // CSS側の「.settings-trigger.collapsed + .custom-menu-wrap」が連動して中身を閉じます
                dropdown.classList.toggle('collapsed');
            });
        });
    </script>
</body>

</html>
