<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>

        *{
            font-family: Inter, sans-serif;
        }

        body{
            background: #f8f9fa;
        }

        .sidebar{
             width: 280px;
             min-height: 100vh;
             background: white;
        }

        .admin-panel{
            font-size: 12px;
        }

        .nav-link{
            color: #0a2540 !important;
            margin-top: .5rem;
            padding: 1rem;
            text-decoration: none !important;
            border-radius: 20px;
        }

        .nav-link i{
            color: #0a2540;
        }

        .nav-link:hover{
            background-color: #0a2540;
            color: white !important;
        }

        .nav-link:hover i{
            color: white !important;
        }

        .nav-link,
        .nav-link i{
            transition: .2s;
        }

        .footer-a{
            color: #0a2540 !important;
        }

        .footer-a:hover{
            text-decoration: underline !important;
        }

        /* Mobile */

        @media (max-width: 991.98px){

            .sidebar{
                width: 100%;
                min-height: auto;
            }

            .content-area{
                width: 100%;
            }

        }

    </style>

</head>

<body>

<div class="container-fluid">

    <div class="row">

        <!-- SIDEBAR -->
        <div class="col-12 col-lg-auto border-end p-3 sidebar d-flex flex-column">

            <!-- Mobile Toggle -->
            <button
                class="btn btn-outline-secondary d-lg-none mb-3"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#sidebarMenu">

                <i class="fa-solid fa-bars"></i>
            </button>

            <!-- Sidebar Content -->
            <div class="d-flex flex-column h-100">

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
                <div class="nav flex-column flex-grow-1 mt-3 flex-grow-1">

                    <a href="{{ route('admin.users') }}"
                       class="nav-link fw-bold">
                        <i class="fa-solid fa-user-group me-2"></i>
                        Users
                    </a>

                    <a href="{{ route('admin.restaurants') }}"
                       class="nav-link fw-bold">
                        <i class="fa-solid fa-store me-2"></i>
                        Restaurants
                    </a>

                    <a href="{{ route('admin.reservations') }}"
                       class="nav-link fw-bold">
                        <i class="fa-solid fa-calendar me-2"></i>
                        Reservations
                    </a>

                    <a href="{{ route('admin.reviews') }}"
                       class="nav-link fw-bold">
                        <i class="fa-solid fa-star me-2"></i>
                        Reviews
                    </a>

                    <a href="{{ route('admin.categories_features') }}"
                       class="nav-link fw-bold">
                        <i class="fa-solid fa-tag me-2"></i>
                        Categories & Features
                    </a>

                </div>

                <!-- Footer -->
                <footer class="border-top mt-auto pt-3 text-center">

                    <a href="{{ route('customer.search') }}"
                       class="text-decoration-none footer-a">

                        ← Back to Customer View

                    </a>

                </footer>

            </div>

        </div>

        <!-- MAIN CONTENT -->
        <div class="col content-area p-4">

            @yield('content')

        </div>

    </div>

</div>

</body>
</html>