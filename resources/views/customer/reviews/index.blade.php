@extends('layouts.app')

@section('title', 'Reviews')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <div class="bg-light min-vh-100 py-4">
        <div class="container" style="max-width: 680px;">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div style="width: 85px;"></div>

                <div class="p-1 rounded-pill d-flex shadow-sm"
                    style="width: 100%; max-width: 320px; background-color: #f1f5f9;">
                    <button type="button" id="tabFollowingBtn"
                        class="btn w-50 rounded-pill fw-bold btn-sm py-2 bg-white text-navy shadow-sm transition-all">
                        Following
                    </button>
                    <button type="button" id="tabDiscoverBtn"
                        class="btn w-50 rounded-pill fw-bold btn-sm py-2 text-muted transition-all">
                        Discover
                    </button>
                </div>

                <button
                    class="d-none d-md-flex align-items-center btn btn-light btn-sm border shadow-sm rounded-3 px-3 py-2 fw-bold small text-white"
                    style="background-color: #0a2540;" type="button" id="openFilterBtn">
                    <i class="fa-solid fa-sliders me-2"></i>Filter
                </button>

                <button
                    class="d-flex d-md-none btn btn-light btn-xs ms-4 border shadow-sm rounded-3 px-3 py-2 fw-bold xsmall text-white"
                    style="background-color: #0a2540;" type="button" id="openFilterBtnMobile">
                    <i class="fa-solid fa-sliders"></i>
                </button>

            </div>

            <div id="reviewsContainer"></div>
        </div>
    </div>

    <div class="filter-drawer-overlay" id="filterOverlay"></div>
    <div class="filter-drawer shadow" id="filterDrawer">
        <div class="d-flex align-items-center justify-content-between p-4 border-bottom">
            <h5 class="fw-bold mb-0 text-navy" style="font-size: 1.2rem;">Filter Reviews</h5>
            <button type="button" class="btn-close shadow-none" id="closeFilterBtn" style="font-size: 0.9rem;"></button>
        </div>

        <div class="p-4 d-flex flex-column flex-grow-1 justify-content-between" style="overflow-y: auto;">
            <div>
                <div class="mb-4">
                    <label class="form-label fw-bold text-navy small mb-2">Restaurant Category</label>
                    <select id="filterCategory" class="form-select custom-filter-select px-3">
                        <option value="">All Categories</option>
                        <option value="Sushi">Sushi</option>
                        <option value="Ramen">Ramen</option>
                        <option value="Yakitori">Yakitori</option>
                        <option value="Tempura">Tempura</option>
                        <option value="Tonkatsu">Tonkatsu</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-navy small mb-2">Minimum Star Rating</label>
                    <select id="filterRating" class="form-select custom-filter-select px-3">
                        <option value="">All Ratings</option>
                        <option value="3">3+ Stars</option>
                        <option value="3.5">3.5+ Stars</option>
                        <option value="4">4+ Stars</option>
                        <option value="4.5">4.5+ Stars</option>
                        <option value="5">5 Stars</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-navy small mb-2">Area</label>
                    <select id="filterArea" class="form-select custom-filter-select px-3">
                        <option value="">All Areas</option>
                        <option value="Ginza, Tokyo">Ginza</option>
                        <option value="Shibuya, Tokyo">Shibuya</option>
                        <option value="Shinjuku">Shinjuku</option>
                        <option value="Aoyama">Aoyama</option>
                    </select>
                </div>

                <div class="mb-4 form-check d-flex align-items-center">
                    <input type="checkbox" id="filterEnglish" class="form-check-input custom-checkbox me-2 ms-0 mt-0">
                    <label class="form-check-label text-navy small fw-semibold" for="filterEnglish"
                        style="cursor: pointer;">English Available Only</label>
                </div>
            </div>

            <div class="pt-4 border-top">
                <button type="button" id="applyFilterBtn"
                    class="btn btn-dark w-100 fw-bold py-25 rounded-pill mb-2 transition-all border-0"
                    style="background-color: #0a2540; font-size: 0.95rem; height: 46px;">
                    Apply Filters
                </button>
                <button type="button" id="resetFilterBtn"
                    class="btn w-100 text-center small fw-bold rounded-pill transition-all text-navy border-0 d-flex align-items-center justify-content-center"
                    style="background-color: #FCE7F3; color: #b42d53 !important; font-size: 0.95rem; height: 46px;">
                    Reset Filters
                </button>
            </div>

        </div>

        <style>
            .text-navy {
                color: #0a2540 !important;
            }

            .btn-outline-navy {
                border: 1px solid #0a2540 !important;
                color: #0a2540 !important;
                background-color: transparent;
                transition: all 0.2s ease;
            }

            .btn-outline-navy:hover {
                background-color: #0a2540 !important;
                color: #ffffff !important;
            }

            .transition-all {
                transition: all 0.2s ease;
            }

            .btn-follow {
                background-color: #0a2540 !important;
                color: white !important;
                border: none;
            }

            .btn-unfollow {
                background-color: #f5efe6 !important;
                color: #4a4a4a !important;
                border: 1px solid #e2e8f0;
            }

            .hover-opacity:hover {
                opacity: 0.8;
            }

            .block-review-link:hover {
                opacity: 0.85;
            }

            .custom-filter-btn {
                background-color: #fffaf4 !important;
                border: none;
                color: #0a2540;
                cursor: pointer;
            }

            .custom-filter-btn:hover {
                background-color: #0a2540 !important;
                color: #ffffff !important;
                transform: translateY(-2px);
                box-shadow: 0 8px 5px rgba(5, 29, 59, 0.15);
            }

            /* 🚪 ドロワースライド用 */
            .filter-drawer {
                position: fixed;
                top: 0;
                right: -380px;
                width: 380px;
                height: 100vh;
                background-color: #fbf5e9;
                z-index: 1050;
                transition: right 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                display: flex;
                flex-direction: column;
            }

            .filter-drawer.open {
                right: 0;
            }

            .filter-drawer-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background-color: rgba(0, 0, 0, 0.4);
                z-index: 1040;
                display: none;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .filter-drawer-overlay.show {
                display: block;
                opacity: 1;
            }

            .custom-filter-select {
                height: 46px;
                border: 1px solid #cfb2c4;
                border-radius: 8px;
                font-size: 0.9rem;
            }

            .custom-checkbox {
                width: 18px;
                height: 18px;
            }

            /* 💻 PC版写真用コンテナ：スクロールバーの装飾 */
            .pc-photos-scrollbar::-webkit-scrollbar {
                height: 6px;
            }

            .pc-photos-scrollbar::-webkit-scrollbar-track {
                background: #f1f5f9;
                border-radius: 10px;
            }

            .pc-photos-scrollbar::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 10px;
            }

            /* ==========================================================================
                   【重要】PC版に影響を一切出さないためのモバイル専用スタイル隔離
                   ========================================================================== */
            @media screen and (max-width: 767.98px) {
                .mobile-photos-container {
                    display: grid !important;
                    grid-template-columns: repeat(2, 1fr);
                    /* 横2列のグリッド */
                    gap: 10px;
                    width: 100%;
                }

                /* モバイル版画像がPC用スタイルを汚染して巨大化・縦並びするのを防ぐ */
                .mobile-photos-container img.mobile-photo {
                    width: 100% !important;
                    height: 100% !important;
                    aspect-ratio: 1 / 1 !important;
                    /* 綺麗な正方形を保証 */
                    object-fit: cover !important;
                    display: block;
                }

                /* 「＋」拡大ボタンの正方形維持 */
                .mobile-photos-container .expand-photos-btn {
                    width: 100% !important;
                    height: 100% !important;
                    aspect-ratio: 1 / 1 !important;
                }
            }

            /* スライダー全体のコンテナ */
            .modal-image-container {
                width: 100%;
                background-color: #000;
            }

            /* 写真を並べるラッパー */
            .modal-image-slider {
                width: 100%;
                /* モバイル版：スワイプ（横スクロール）を有効化 */
                overflow-x: auto;
                scroll-snap-type: x mandatory;
                scroll-behavior: smooth;
                -webkit-overflow-scrolling: touch;
            }

            /* PC版：JSで制御するためスクロールバーを隠す */
            @media (min-width: 768px) {
                .modal-image-slider {
                    overflow-x: hidden;
                }
            }

            /* スクロールバー自体を非表示にする（デザイン性維持のため） */
            .modal-image-slider::-webkit-scrollbar {
                display: none;
            }

            /* 各スライド（写真）の調整 */
            .modal-slide-item {
                min-width: 100%;
                width: 100%;
                scroll-snap-align: start;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .modal-slide-item img {
                width: 100%;
                height: auto;
                object-fit: contain;
            }

            /* PC版用：左右矢印ボタンの共通スタイル */
            .btn-slider {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                background: rgba(0, 0, 0, 0.5);
                color: #fff;
                border: none;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: background 0.3s;
                z-index: 10;
            }

            .btn-slider:hover {
                background: rgba(0, 0, 0, 0.8);
            }

            .prev-btn {
                left: 15px;
            }

            .next-btn {
                right: 15px;
            }
            /* モーダルの全体的なレイアウト制限 */
#instagramModal .modal-content {
    max-height: 90vh;
}

/* --- 写真スライダーエリアのスタイル --- */
.modal-image-slider {
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
    height: 100%;
}

/* PC版：JSのボタンで操作するため、ネイティブのスクロールバーを隠す */
@media (min-width: 768px) {
    .modal-image-slider {
        overflow-x: hidden;
    }
}

/* モバイル版等で出るスクロールバー自体を完全に非表示化 */
.modal-image-slider::-webkit-scrollbar {
    display: none;
}

/* 各写真スライドの等幅設定 */
.modal-slide-item {
    min-width: 100%;
    width: 100%;
    height: 100%;
    scroll-snap-align: start;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #000; /* 縦横比が合わないときの余白を黒に */
}

.modal-slide-item img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* インスタ風に枠いっぱいに表示（完全に全体を見せたい場合は contain に変更してください） */
}

/* PC版用：左右矢印ボタンのデザイン */
.btn-slider {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0, 0, 0, 0.4);
    color: #fff;
    border: none;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s, opacity 0.2s;
    z-index: 10;
}

.btn-slider:hover {
    background: rgba(0, 0, 0, 0.7);
}

.prev-btn { left: 12px; }
.next-btn { right: 12px; }

/* コメント欄などの文字色微調整 */
#instagramModal .text-secondary {
    color: #262626 !important;
}
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {

                // 1. フロント確認用のダミーデータ
                const dummyReviews = [{
                        id: 1,
                        tab: 'following',
                        username: 'Sarah_Johnson',
                        avatar: "https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=200",
                        time: '2 days ago',
                        is_following: true,
                        restaurant_name: 'Sushi Masaru',
                        area: 'Ginza, Tokyo',
                        category: 'Sushi',
                        rating: 5,
                        photos: [
                            "https://images.unsplash.com/photo-1579584425555-c3ce17fd4351?w=400",
                            "https://images.unsplash.com/photo-1583623025817-d180a2221d0a?w=400",
                            "https://images.unsplash.com/photo-1563245372-f21724e3856d?w=400",
                            "https://images.unsplash.com/photo-1553621042-f6e147245754?w=400",
                            "https://images.unsplash.com/photo-1579871494447-9811cf80d66c?w=400"
                        ],
                        comment: 'Absolutely amazing experience! The omakase was incredible and the chef was very welcoming to international guests. Every piece of sushi was perfect.',
                        is_english: true
                    },
                    {
                        id: 2,
                        tab: 'following',
                        username: 'Emma_Wilson',
                        avatar: "https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=200",
                        time: '5 hours ago',
                        is_following: true,
                        restaurant_name: 'Yakitori Tori',
                        area: 'Shinjuku',
                        category: 'Yakitori',
                        rating: 5,
                        photos: [],
                        comment: "Best yakitori I've had! The chef grilled everything perfectly. Love the intimate counter seating experience.",
                        is_english: true
                    },
                    {
                        id: 3,
                        tab: 'discover',
                        username: 'Micheal_Chen',
                        avatar: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200",
                        time: '1 week ago',
                        is_following: false,
                        restaurant_name: 'Ramen Ichiban',
                        area: 'Shibuya, Tokyo',
                        category: 'Ramen',
                        rating: 4,
                        photos: [
                            "https://images.unsplash.com/photo-1557872943-16a5ac26437e?w=400",
                        ],
                        comment: 'Great tonkotsu ramen! Rich broth and perfect noodles. The wait time was a bit long but worth it. English menu available.',
                        is_english: false
                    },
                    {
                        id: 4,
                        tab: 'discover',
                        username: 'David Kim',
                        avatar: "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=200",
                        time: '3 weeks ago',
                        is_following: false,
                        restaurant_name: 'Tempura Kondo',
                        area: 'Ginza, Tokyo',
                        category: 'Tempura',
                        rating: 4,
                        photos: [
                            "https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=400",
                        ],
                        comment: 'Excellent tempura! Each piece was light and crispy. Great quality ingredients.',
                        is_english: true
                    },
                    {
                        id: 5,
                        tab: 'discover',
                        username: 'Lisa_Anderson',
                        avatar: "https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200",
                        time: '1 month ago',
                        is_following: false,
                        restaurant_name: 'Tonkatsu Maisen',
                        area: 'Aoyama',
                        category: 'Tonkatsu',
                        rating: 3,
                        photos: [],
                        comment: 'Good tonkatsu, but a bit crowded. Service was quick and efficient.',
                        is_english: true
                    },
                    {
                        id: 6,
                        tab: 'discover',
                        username: 'Takeshi_Hirai',
                        avatar: null,
                        time: '3 month ago',
                        is_following: false,
                        restaurant_name: 'Ramen Ichiran',
                        area: 'Yurakucyo',
                        category: 'Ramen',
                        rating: 5,
                        photos: [],
                        comment: 'Good taste and quality. Service was quick and efficient.',
                        is_english: true
                    }
                ];

                let currentTab = 'following';

                // ELEMENTの取得
                const container = document.getElementById('reviewsContainer');
                const tabFollowingBtn = document.getElementById('tabFollowingBtn');
                const tabDiscoverBtn = document.getElementById('tabDiscoverBtn');

                const openFilterBtn = document.getElementById('openFilterBtn');
                const openFilterBtnMobile = document.getElementById('openFilterBtnMobile');
                const closeFilterBtn = document.getElementById('closeFilterBtn');
                const drawer = document.getElementById('filterDrawer');
                const overlay = document.getElementById('filterOverlay');

                const applyFilterBtn = document.getElementById('applyFilterBtn');
                const resetFilterBtn = document.getElementById('resetFilterBtn');

                // 2. 📝 画面にカードを描画する関数
                function renderReviews() {
                    container.innerHTML = ''; // 一度クリア

                    // 現在のタブ ＆ フィルター条件でデータを絞り込む
                    const filtered = dummyReviews.filter(review => {
                        if (review.tab !== currentTab) return false;

                        // ジャンル絞り込み
                        const cat = document.getElementById('filterCategory').value;
                        if (cat && review.category !== cat) return false;

                        // 星評価絞り込み
                        const rat = document.getElementById('filterRating').value;
                        if (rat && review.rating < parseInt(rat)) return false;

                        // エリア絞り込み
                        const area = document.getElementById('filterArea').value;
                        if (area && review.area !== area) return false;

                        // 英語対応絞り込み
                        const eng = document.getElementById('filterEnglish').checked;
                        if (eng && !review.is_english) return false;

                        return true;
                    });

                    if (filtered.length === 0) {
                        container.innerHTML = `
                        <div class="text-center py-5 text-muted bg-white rounded-4 shadow-sm">
                            <i class="fa-regular fa-comment-dots display-4 mb-3" style="color: #cbd5e1;"></i>
                            <p class="mb-0">No reviews match your filters.</p>
                        </div>`;
                        return;
                    }

                    // HTMLカードの生成
                    filtered.forEach(review => {
                        const card = document.createElement('div');
                        card.className = 'card border-0 shadow-sm rounded-4 mb-4 p-3';
                        card.style.backgroundColor = '#ffffff';

                        let stars = '';
                        for (let i = 1; i <= 5; i++) {
                            stars += `<i class="fa-${i <= review.rating ? 'solid' : 'regular'} fa-star"></i>`;
                        }

                        const followBtn = review.is_following ?
                            `<button class="btn btn-sm rounded-pill px-3 fw-bold btn-unfollow toggle-follow-mock" data-id="${review.id}"><i class="fa-solid fa-user-minus me-1"></i>Unfollow</button>` :
                            `<button class="btn btn-sm rounded-pill px-3 fw-bold btn-follow toggle-follow-mock" data-id="${review.id}"><i class="fa-solid fa-user-plus me-1"></i>Follow</button>`;

                        // ==========================================
                        // 📸 口コミの店舗写真を生成する処理
                        // ==========================================
                        let pcPhotosHtml = '';
                        let mobilePhotosHtml = '';
                        const photoCount = review.photos ? review.photos.length : 0;

                        if (photoCount > 0) {
                            // --- 💻 1. PC版用の店舗写真（横1列・横スクロール維持） ---
                            pcPhotosHtml =
                                `
                            <div class="d-none d-md-flex mt-3 flex-row gap-2 pc-photos-scrollbar" style="max-width: 100%; overflow-x: auto; flex-wrap: nowrap; padding-bottom: 8px;">`;
                            review.photos.forEach(photoUrl => {
                                pcPhotosHtml += `
                                    <img src="${photoUrl}" class="rounded-3 transition-all flex-shrink-0" 
                                         style="width: calc((100% / 4) - 0.4rem); min-width: 140px; height: auto; aspect-ratio: 1/1; object-fit: cover; cursor: pointer;" 
                                         alt="food photo">`;
                            });
                            pcPhotosHtml += '</div>';

                            // --- 📱 2. Mobile版用の店舗写真（2×2グリッド） ---
                            mobilePhotosHtml =
                                `<div class="d-flex d-md-none mt-3 mobile-photos-container" data-review-id="${review.id}">`;
                            review.photos.forEach((photoUrl, index) => {
                                // 4枚以上かつ最初から見せるのは3枚のみ
                                const hiddenClass = (photoCount > 3 && index >= 3) ? 'd-none' : '';
                                mobilePhotosHtml +=
                                    `
                                    <img src="${photoUrl}" class="rounded-3 mobile-photo ${hiddenClass}" alt="food photo">`;
                            });

                            // 写真が4枚以上あれば、4つ目の枠として「＋」ボタンを追加
                            if (photoCount > 3) {
                                mobilePhotosHtml += `
                                    <div class="bg-secondary text-white rounded-3 d-flex flex-column align-items-center justify-content-center fw-bold text-center expand-photos-btn" 
                                         style="cursor: pointer; font-size: 1.4rem; border: 2px dashed rgba(255,255,255,0.4);">
                                        <span>+${photoCount - 3}</span>
                                    </div>`;
                            }
                            mobilePhotosHtml += '</div>';
                        }

                        // PC用のアバター部分
                        let pcAvatarHtml = '';
                        if (review.avatar) {
                            pcAvatarHtml =
                                `<img src="${review.avatar}" class="rounded-circle me-3 flex-shrink-0" style="width: 44px; height: 44px; object-fit: cover;" alt="avatar">`;
                        } else {
                            pcAvatarHtml = `
                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold flex-shrink-0" style="width: 44px; height: 44px; font-size:1.2rem;">
                                ${review.username.charAt(0)}
                            </div>`;
                        }

                        // モバイル用のアバター部分
                        let mobileAvatarHtml = '';
                        if (review.avatar) {
                            mobileAvatarHtml =
                                `<img src="${review.avatar}" class="rounded-circle me-3 flex-shrink-0" style="width: 38px; height: 38px; object-fit: cover; min-width: 38px; min-height: 38px;" alt="avatar">`;
                        } else {
                            mobileAvatarHtml = `
                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold flex-shrink-0" style="width: 38px; height: 38px; font-size:1.0rem; min-width: 38px; min-height: 38px;">
                                ${review.username.charAt(0)}
                            </div>`;
                        }

                        // 💥 組み立て
                        card.innerHTML = `
                            <div class="d-none d-md-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center hover-opacity" style="cursor:pointer;">
                                    ${pcAvatarHtml}
                                    <div>
                                        <h6 class="fw-bold mb-0 text-navy" style="font-size: 0.95rem;">${review.username}</h6>
                                        <p class="text-muted small mb-0" style="font-size: 0.8rem;">
                                            ${review.restaurant_name} • ${review.area} <span class="mx-1">•</span> ${review.time}
                                        </p>
                                    </div>
                                </div>
                                <div>${followBtn}</div> 
                            </div>

                            <div class="d-flex d-md-none align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center hover-opacity" style="cursor:pointer;">
                                    ${mobileAvatarHtml}
                                    <div>
                                        <h6 class="fw-bold mb-0 text-navy" style="font-size: 0.95rem;">${review.username}</h6>
                                        <p class="text-muted small mb-0" style="font-size: 0.8rem;">
                                            ${review.restaurant_name} • ${review.area} <span class="mx-1">•</span> ${review.time}
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <button class="btn btn-sm rounded-pill fw-bold d-inline-flex align-items-center justify-content-center ${review.is_following ? 'btn-unfollow' : 'btn-follow'} toggle-follow-mock" data-id="${review.id}" style="width: 36px; height: 36px; padding: 0 !important;">
                                        <i class="fa-solid ${review.is_following ? 'fa-user-minus' : 'fa-user-plus'}"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="text-warning mb-2" style="font-size: 0.9rem;">${stars}</div>

                            <div class="block-review-link text-decoration-none text-dark" style="cursor:pointer;">
                                <p class="mb-0 text-secondary" style="font-size: 0.95rem; line-height: 1.6; color: #475569 !important;">
                                    ${review.comment}
                                </p>
                            </div>

                            ${pcPhotosHtml}
                            ${mobilePhotosHtml}

                            <div class="d-flex align-items-center mt-3 pt-2 border-top border-light text-muted small">
                                <button type="button" class="btn btn-link text-decoration-none text-muted p-0 d-flex align-items-center me-4 shadow-none" data-bs-toggle="modal" data-bs-target="#commentModal">
                                    <i class="fa-regular fa-heart me-2" style="font-size: 1.1rem;"></i> <span>14</span>
                                    <i class="fa-regular fa-comment ms-3 me-2" style="font-size: 1.1rem;"></i> <span>3</span>
                                </button>
                            </div>
                        `;

                        container.appendChild(card);
                    });

                    // ➕ モバイル版の「＋」ボタンをクリックしたときに写真を全展開するイベント
                    document.querySelectorAll('.expand-photos-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const parentContainer = this.closest('.mobile-photos-container');
                            if (parentContainer) {
                                parentContainer.style.gridTemplateColumns =
                                    'repeat(auto-fill, minmax(calc(50% - 5px), 1fr))';

                                parentContainer.querySelectorAll('.mobile-photo.d-none').forEach(
                                    img => {
                                        img.classList.remove('d-none');
                                    });
                                this.remove();
                            }
                        });
                    });

                    // フォローボタンのモック動作をバインド
                    document.querySelectorAll('.toggle-follow-mock').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const id = parseInt(this.getAttribute('data-id'));
                            const target = dummyReviews.find(r => r.id === id);
                            if (target) {
                                target.is_following = !target.is_following;
                                renderReviews(); // 再描画
                            }
                        });
                    });
                }

                // 3. 🔄 タブ切り替えのイベント
                tabFollowingBtn.addEventListener('click', () => {
                    currentTab = 'following';
                    tabFollowingBtn.className =
                        'btn w-50 rounded-pill fw-bold btn-sm py-2 bg-white text-navy shadow-sm transition-all';
                    tabDiscoverBtn.className =
                        'btn w-50 rounded-pill fw-bold btn-sm py-2 text-muted transition-all';
                    renderReviews();
                });

                tabDiscoverBtn.addEventListener('click', () => {
                    currentTab = 'discover';
                    tabDiscoverBtn.className =
                        'btn w-50 rounded-pill fw-bold btn-sm py-2 bg-white text-navy shadow-sm transition-all';
                    tabFollowingBtn.className =
                        'btn w-50 rounded-pill fw-bold btn-sm py-2 text-muted transition-all';
                    renderReviews();
                });

                // 🚪 ドロワー開閉イベント
                if (openFilterBtn) {
                    openFilterBtn.addEventListener('click', () => {
                        drawer.classList.add('open');
                        overlay.classList.add('show');
                    });
                }
                if (openFilterBtnMobile) {
                    openFilterBtnMobile.addEventListener('click', () => {
                        drawer.classList.add('open');
                        overlay.classList.add('show');
                    });
                }
                closeFilterBtn.addEventListener('click', () => {
                    drawer.classList.remove('open');
                    overlay.classList.remove('show');
                });
                overlay.addEventListener('click', () => {
                    drawer.classList.remove('open');
                    overlay.classList.remove('show');
                });

                // 🔍 フィルター適用・リセット
                applyFilterBtn.addEventListener('click', () => {
                    renderReviews();
                    drawer.classList.remove('open');
                    overlay.classList.remove('show');
                });
                resetFilterBtn.addEventListener('click', () => {
                    document.getElementById('filterCategory').value = '';
                    document.getElementById('filterRating').value = '';
                    document.getElementById('filterArea').value = '';
                    document.getElementById('filterEnglish').checked = false;
                    renderReviews();
                    drawer.classList.remove('open');
                    overlay.classList.remove('show');
                });
                document.addEventListener('DOMContentLoaded', () => {
    const slider = document.getElementById('modalImageSlider');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    if (!slider || !prevBtn || !nextBtn) return;

    // 次の写真へ
    nextBtn.addEventListener('click', () => {
        const slideWidth = slider.clientWidth;
        // 現在のスクロール位置から1枚分右にスクロール
        slider.scrollBy({ left: slideWidth, behavior: 'smooth' });
    });

    // 前の写真へ
    prevBtn.addEventListener('click', () => {
        const slideWidth = slider.clientWidth;
        // 現在のスクロール位置から1枚分左にスクロール
        slider.scrollBy({ left: -slideWidth, behavior: 'smooth' });
    });

    // モーダルを閉じて再度開いた時などにスクロール位置をリセットする処理（必要に応じて）
    function resetSlider() {
        slider.scrollLeft = 0;
    }
});
document.addEventListener('DOMContentLoaded', () => {
    const slider = document.getElementById('modalImageSlider');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    if (!slider || !prevBtn || !nextBtn) return;

    // 「次へ (＞)」ボタンのクリックイベント
    nextBtn.addEventListener('click', () => {
        const slideWidth = slider.clientWidth;
        // 写真1枚分の幅だけ右へスムーズスクロール
        slider.scrollBy({ left: slideWidth, behavior: 'smooth' });
    });

    // 「前へ (＜)」ボタンのクリックイベント
    prevBtn.addEventListener('click', () => {
        const slideWidth = slider.clientWidth;
        // 写真1枚分の幅だけ左へスムーズスクロール
        slider.scrollBy({ left: -slideWidth, behavior: 'smooth' });
    });

    // 【おまけ機能】モーダルが閉じられた際、スライダーの表示位置を1枚目にリセットする処理
    const instagramModalEl = document.getElementById('instagramModal');
    if (instagramModalEl) {
        instagramModalEl.addEventListener('hidden.bs.modal', () => {
            slider.scrollLeft = 0;
        });
    }
});

                // 初期表示
                renderReviews();
            });
        </script>
    </div>

    <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-fullscreen-md-down">
            <div class="modal-content overflow-hidden" style="border-radius: 12px;">

                <div class="modal-body p-0 d-flex flex-column flex-md-row" style="height: 80vh; min-height: 500px;">

                    <div class="col-12 col-md-7 bg-black d-flex align-items-center justify-content-center p-0 position-relative overflow-hidden"
                        style="min-height: 250px;">
                        
                        <div class="modal-image-slider d-flex w-100 h-100" id="modalImageSlider">
                            <div class="modal-slide-item">
                                <img src="https://images.unsplash.com/photo-1579584425555-c3ce17fd4351?w=800" class="w-100 h-100" style="object-fit: contain;" alt="Post image 1">
                            </div>
                            <div class="modal-slide-item">
                                <img src="https://images.unsplash.com/photo-1611143669185-af224c5e3252?w=800" class="w-100 h-100" style="object-fit: contain;" alt="Post image 2">
                            </div>
                            <div class="modal-slide-item">
                                <img src="https://images.unsplash.com/photo-1553621042-f6e147245754?w=800" class="w-100 h-100" style="object-fit: contain;" alt="Post image 3">
                            </div>
                        </div>

                        <button class="btn-slider prev-btn d-none d-md-flex" id="prevBtn" aria-label="Previous image">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="btn-slider next-btn d-none d-md-flex" id="nextBtn" aria-label="Next image">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>

                    <div class="col-12 col-md-5 d-flex flex-column bg-white border-start border-light"
                        style="overflow: hidden;">

                        <div
                            class="d-flex align-items-center justify-content-between p-3 border-bottom border-light bg-white">
                            <div class="d-flex align-items-center">
                                <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=200"
                                    class="rounded-circle me-2" width="40" height="40" style="object-fit: cover;"
                                    alt="Avatar">
                                <div>
                                    <div class="fw-bold small text-dark">Sarah_Johnson</div>
                                    <div class="text-muted" style="font-size: 11px;">Sushi Masaru • Ginza, Tokyo</div>
                                </div>
                            </div>
                            <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="flex-grow-1 overflow-y-auto p-3" style="background-color: #fafafa;">
                            <div class="d-flex mb-3">
                                <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=200"
                                    class="rounded-circle me-2" width="32" height="32" style="object-fit: cover;"
                                    alt="Avatar">
                                <div class="small">
                                    <span class="fw-bold me-1 text-dark">Sarah_Johnson</span>
                                    <span class="text-secondary">Absolutely amazing experience! The omakase was incredible
                                        and the chef was very welcoming...</span>
                                    <div class="text-muted mt-1" style="font-size: 11px;">2 days ago</div>
                                </div>
                            </div>
                            <hr class="text-muted my-2 opacity-25">

                            <div class="d-flex mb-3">
                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-2 fw-bold"
                                    style="width: 32px; height: 32px; font-size:0.8rem; flex-shrink: 0;">E</div>
                                <div class="small">
                                    <span class="fw-bold me-1 text-dark">emi72480</span>
                                    <span class="text-secondary">Looks delicious! Definitely a must-visit spot when I go to
                                        Ginza 😍</span>
                                    <div class="text-muted mt-1" style="font-size: 11px;">5w · Reply</div>
                                </div>
                            </div>

                            <div class="d-flex mb-3">
                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-2 fw-bold"
                                    style="width: 32px; height: 32px; font-size:0.8rem; flex-shrink: 0;">C</div>
                                <div class="small">
                                    <span class="fw-bold me-1 text-dark">carnelian315</span>
                                    <span class="text-secondary">The chef's hospitality was so wonderful, and my friends
                                        from overseas absolutely loved it! Will definitely book again.</span>
                                    <div class="text-muted mt-1" style="font-size: 11px;">5w · Reply</div>
                                </div>
                            </div>
                        </div>

                        <div class="p-3 border-top border-light bg-white">
                            <div class="d-flex align-items-center mb-1 text-dark">
                                <i class="fa-regular fa-heart me-2" style="font-size: 1.2rem; cursor: pointer;"></i>
                                <i class="fa-regular fa-comment me-2" style="font-size: 1.2rem;"></i>
                            </div>
                            <div class="fw-bold small text-dark">「Likes」14</div>
                            <div class="text-muted" style="font-size: 10px;">8 days ago</div>
                        </div>

                        <div class="p-3 border-top border-light bg-white">
                            <form class="d-flex align-items-center">
                                <input type="text" class="form-control form-control-sm border-0 ps-0 shadow-none"
                                    placeholder="Add comments..." style="font-size: 14px;">
                                <button type="submit"
                                    class="btn btn-link text-decoration-none fw-bold p-0 small shadow-none"
                                    style="color: #0095f6;">POST</button>
                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
