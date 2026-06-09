@extends('layouts.app')

@section('title', 'Customer My Page')

@section('content')

    <div class="bg-white min-vh-100 pb-5" style="font-family: 'Poppins', 'Helvetica Neue', Arial, sans-serif;">

        <div class="container py-4 py-md-5" style="max-width: 935px;">
            <div class="row align-items-center">
                <div class="col-4 col-md-3 text-center mt-2 mb-2 mb-md-0">
                    <div class="position-relative d-inline-block">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=300&auto=format&fit=crop"
                            alt="Profile" class="rounded-circle object-fit-cover shadow-sm avatar-responsive">
                    </div>
                </div>
                <div class="col-8 col-md-9 ps-3 ps-md-4 d-md-flex flex-md-column justify-content-md-center">

                    <div class="mb-md-2">
                        <h2 class="fw-bold my-0 tracking-tight"
                            style="color: #051d3b; font-size: 1.6rem; line-height: 1.2;">john_doe</h2>
                    </div>

                    <div class="mb-md-4">
                        <a href="/customer/profile"
                            class="btn btn-sm fw-bold text-navy mt-3 px-3 py-1 rounded-3 custom-edit-btn"
                            style="color: #051d3b; border: 1px solid #dbdbdb; border-radius: 5px; font-size: 0.85rem; background-color: #fffaf4; display: inline-block;">
                            Edit Profile
                        </a>
                    </div>

                    <div class="d-none d-md-flex align-items-center" style="gap: 40px;">
                        <div>
                            <span class="fw-bold" style="font-size: 1.15rem; color: #051d3b;">6</span>
                            <span class="text-secondary ms-1">reviews</span>
                        </div>
                        <div>
                            <span class="fw-bold" style="font-size: 1.15rem; color: #051d3b;">142</span>
                            <span class="text-secondary ms-1">followers</span>
                        </div>
                        <div>
                            <span class="fw-bold" style="font-size: 1.15rem; color: #051d3b;">89</span>
                            <span class="text-secondary ms-1">following</span>
                        </div>
                    </div>

                </div>
            </div>
            <div class="row d-flex d-md-none text-center pt-3" style="border-color: #efefef !important;">
                <div class="col-4"><span class="fw-bold d-block" style="font-size: 1.1rem; color: #051d3b;">6</span><span
                        class="text-muted" style="font-size: 0.8rem; color: #8e8e8e !important;">reviews</span></div>
                <div class="col-4"><span class="fw-bold d-block" style="font-size: 1.1rem; color: #051d3b;">142</span><span
                        class="text-muted" style="font-size: 0.8rem; color: #8e8e8e !important;">followers</span></div>
                <div class="col-4"><span class="fw-bold d-block" style="font-size: 1.1rem; color: #051d3b;">89</span><span
                        class="text-muted" style="font-size: 0.8rem; color: #8e8e8e !important;">following</span></div>
            </div>

            <hr class="mt-4 mb-0" style="border-color: #dbdbdb; opacity: 0.5;">
            
        </div>

        <div class="container px-2 px-md-0" style="max-width: 935px;">
            <div class="row g-1 g-md-4">

                <div class="col-4">
                    <div class="ratio ratio-1x1 overflow-hidden bg-light review-item review-hover-box"
                        style="cursor: pointer; position: relative;">

                        <img src="https://images.unsplash.com/photo-1579871494447-9811cf80d66c?w=600&auto=format&fit=crop"
                            class="object-fit-cover w-100 h-100" alt="Sushi">

                        <div class="hover-mask">
                            <span class="hover-likes-text">
                                <i class="fa-solid fa-heart me-2"></i>24
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="ratio ratio-1x1 overflow-hidden bg-light review-item review-hover-box"
                        style="cursor: pointer; position: relative;">

                        <img src="https://images.unsplash.com/photo-1611143669185-af224c5e3252?w=600&auto=format&fit=crop"
                            class="object-fit-cover w-100 h-100" alt="Sushi Combo">

                        <div class="hover-mask">
                            <span class="hover-likes-text">
                                <i class="fa-solid fa-heart me-2"></i>18
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="ratio ratio-1x1 overflow-hidden bg-light review-item review-hover-box"
                        style="cursor: pointer; position: relative;">

                        <img src="https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=600&auto=format&fit=crop"
                            class="object-fit-cover w-100 h-100" alt="Ramen">

                        <div class="hover-mask">
                            <span class="hover-likes-text">
                                <i class="fa-solid fa-heart me-2"></i>42
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <style>
            @media (max-width: 767.98px) {
                .avatar-responsive {
                    width: 77px !important;
                    height: 77px !important;
                }

                .custom-tab-active {
                    position: relative;
                }

                .custom-tab-active::after {
                    content: '';
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    height: 1.5px;
                    background-color: #051d3b;
                }
            }

            @media (min-width: 768px) {
                .avatar-responsive {
                    width: 150px !important;
                    height: 150px !important;
                }

                .custom-tab-active {
                    border-top: 1.5px solid #051d3b;
                    margin-top: -1px;
                    padding-top: 18px !important;
                }
            }

            .custom-edit-btn {
                background-color: #fffaf4 !important;
                border: none;
                color: #0a2540;
                cursor: pointer;
            }

            .custom-edit-btn:hover {
                background-color: #0a2540 !important;
                color: #ffffff !important;
                transform: translateY(-2px);
                box-shadow: 0 8px 5px rgba(5, 29, 59, 0.15);
            }

            .text-navy {
                color: #0a2540;
            }

            .img-fade-in {
                transition: opacity 0.2s;
            }

            .img-fade-in:hover {
                opacity: 0.85;
            }

            /* 📦 写真の親ボックス（重なりの基準を作る） */
            .review-hover-box {
                position: relative;
            }

            /* 🖤 初期状態：黒幕レイヤーを完全に不透明度ゼロ（隠し状態）にする */
            .hover-mask {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.45);
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 0;
                /* 最初は完全に透明で見えないようにする */
                visibility: hidden;
                /* 完全に存在を隠す */
                transition: opacity 0.2s ease, visibility 0.2s ease;
                /* 滑らかに切り替える */
                z-index: 3;
            }

            /* 🤍 中央に表示するいいねテキストの装飾 */
            .hover-likes-text {
                color: #ffffff !important;
                font-size: 1.3rem;
                font-weight: 700;
                display: flex;
                align-items: center;
                letter-spacing: 0.5px;
            }

            /* 🖱️ 【超重要】カーソルが当たった（hover）写真のマスクだけを表示状態にする */
            .review-hover-box:hover .hover-mask {
                opacity: 1;
                /* カーソルが乗ったら不透明度を1にして表示 */
                visibility: visible;
                /* 隠し状態を解除 */
            }
        </style>
    @endsection
