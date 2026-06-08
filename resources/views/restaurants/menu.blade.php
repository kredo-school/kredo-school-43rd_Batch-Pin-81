@extends('layouts.restaurant')

@section('title', 'Menu Management')

@section('content')

    <style>
        /* メニューカードの基本スタイル */
        .menu-card {
            background-color: #ffffff;
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            transition: transform 0.2s;
        }
        .menu-card:hover {
            transform: translateY(-2px);
        }

        /* 💡 追加：3個以上でスクロールさせるためのコンテナ */
        .menu-scroll-container {
            max-height: 340px; /* カード約3個分の高さ */
            overflow-y: auto;
            padding-right: 8px;
        }
        /* スクロールバーのデザインをスタイリッシュに */
        .menu-scroll-container::-webkit-scrollbar {
            width: 6px;
        }
        .menu-scroll-container::-webkit-scrollbar-track {
            background: transparent;
        }
        .menu-scroll-container::-webkit-scrollbar-thumb {
            background: #dee2e6;
            border-radius: 10px;
        }

        /* 画像プレースホルダーオブジェクト */
        .image-placeholder {
            width: 80px;
            height: 80px;
            background-color: #f5f7fa;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #adb5bd;
            font-size: 24px;
            flex-shrink: 0; /* スマホで画像が潰れるのを防ぐ */
        }

        /* 編集・削除ボタン */
        .btn-action-edit {
            background-color: #ffffff !important;
            color: #0A2540 !important;
            border-radius: 6px;
            transition: all 0.2s;
        }
        .btn-action-edit:hover {
            background-color: #0A2540 !important;
            color: #ffffff !important;
        }

        .btn-action-delete {
            background-color: #ffffff !important;
            color: #bb2d3b !important;
            border-radius: 6px;
            transition: all 0.2s;
        }
        .btn-action-delete:hover {
            background-color: #bb2d3b !important;
            color: #ffffff !important;
        }

        /* フォームコントロール */
        .form-custom-input {
            background-color: #f8f9fa !important;
            border: 1px solid #f1f3f5 !important;
            border-radius: 8px;
            padding: 10px 14px;
            color: #343a40;
            font-size: 14px;
        }
        .form-custom-input::placeholder {
            color: #adb5bd;
        }
        .form-custom-input:focus {
            background-color: #ffffff !important;
        }

        /* カテゴリ選択（Food / Drink）トグルボタン */
        .btn-category-toggle {
            border: 1px solid #0A2540 !important;
            background-color: #ffffff;
            color: #0A2540;
            font-weight: 500;
            padding: 10px 0;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .btn-category-toggle:hover {
            border: 1px solid #e9ecef !important;
            color: #0A2540 !important;
            background-color: #f8f9fa !important;
        }
        
        /*  選択された時だけのスタイルを設定 */
        .btn-category-toggle.active {
            border: 1px solid #0A2540 !important;
            color: #ffffff !important;
            background-color: #0A2540 !important;
            font-weight: bold;
        }

        /* 写真アップロードエリア */
        .upload-zone {
            border: 2px dashed #dee2e6;
            border-radius: 12px;
            background-color: #ffffff;
            padding: 24px;
            text-align: center;
            cursor: pointer;
            color: #6c757d;
            transition: all 0.2s;
        }
        .upload-zone:hover {
            border-color: #0A2540;
            background-color: #f8f9fa;
        }

        /* メインボタン */
        .btn-main-submit {
            background-color: #FCE7F3 !important;
            color: #0A2540 !important;
            border: none !important;
            border-radius: 8px;
            padding: 12px;
            font-weight: bold;
        }
        .btn-main-submit:hover {
            opacity: 0.9;
        }

        .btn-cancel {
            background-color: transparent !important;
            color: #0A2540 !important;
            border: 1px solid #0A2540 !important;
            border-radius: 8px;
            padding: 12px;
            font-weight: bold;
        }

        .btn-cancel:hover {
            background-color: #0A2540 !important;
            color: #ffffff !important;
        }

        /* 💡 カスタムモーダルのスタイル設定 */
        .modal-content-custom {
            border-radius: 16px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .btn-modal-delete {
            background-color: transparent !important;
            color: #bb2d3b !important;
            border: 1px solid #bb2d3b !important;
            border-radius: 8px;
            font-weight: bold;
        }
        .btn-modal-delete:hover {
            background-color: #bb2d3b !important;
            color: #ffffff !important;
        }
    </style>

    <div class="container pb-5" style="max-width: 1140px;">
        <h2 class="fw-bold mb-4" style="color: #0A2540; font-size: 28px;">Menu Management</h2>

        <div class="row d-flex flex-column-reverse flex-md-row gap-4 gap-md-0">
            
            <div class="col-12 col-md-7">
                <h5 class="fw-bold mb-3" style="color: #0A2540;">Current Menu Items</h5>
                
                <div class="mb-4">
                    <span class="text-secondary fw-bold small d-block mb-3" style="letter-spacing: 0.5px;">FOOD</span>
                    
                    <div class="menu-scroll-container">
                        <div class="menu-card p-3 mb-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="https://images.unsplash.com/photo-1579871494447-9811cf80d66c?w=160&auto=format&fit=crop&q=60" alt="Omakase" class="object-fit-cover rounded" style="width: 80px; height: 80px; flex-shrink:0;">
                                    <div>
                                        <div class="d-flex flex-column flex-md-row align-items-md-center gap-1 gap-md-2">
                                            <span class="fw-bold fs-6" style="color: #0A2540;">Omakase Course</span>
                                            <span class="fw-bold text-dark fs-6">¥15,000</span>
                                        </div>
                                        <p class="text-muted small m-0 mt-1">Chef's selection of seasonal sushi</p>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-action-edit p-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;" onclick="switchToEditMode('Omakase Course', 15000, 'Food', 'Chef\'s selection of seasonal sushi')">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-action-delete p-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="menu-card p-3 mb-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="image-placeholder">
                                        <i class="fa-regular fa-image"></i>
                                    </div>
                                    <div>
                                        <div class="d-flex flex-column flex-md-row align-items-md-center gap-1 gap-md-2">
                                            <span class="fw-bold fs-6" style="color: #0A2540;">Nigiri Sushi Set</span>
                                            <span class="fw-bold text-dark fs-6">¥8,000</span>
                                        </div>
                                        <p class="text-muted small m-0 mt-1">12 pieces of chef's choice nigiri</p>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-action-edit p-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;" onclick="switchToEditMode('Nigiri Sushi Set', 8000, 'Food', '12 pieces of chef\'s choice nigiri')">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-action-delete p-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="menu-card p-3 mb-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="image-placeholder">
                                        <i class="fa-regular fa-image"></i>
                                    </div>
                                    <div>
                                        <div class="d-flex flex-column flex-md-row align-items-md-center gap-1 gap-md-2">
                                            <span class="fw-bold fs-6" style="color: #0A2540;">Sashimi Platter</span>
                                            <span class="fw-bold text-dark fs-6">¥6,500</span>
                                        </div>
                                        <p class="text-muted small m-0 mt-1">Fresh seasonal fish</p>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-action-edit p-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;" onclick="switchToEditMode('Sashimi Platter', 6500, 'Food', 'Fresh seasonal fish')">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-action-delete p-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <span class="text-secondary fw-bold small d-block mb-3" style="letter-spacing: 0.5px;">DRINK</span>
                    
                    <div class="menu-scroll-container">
                        <div class="menu-card p-3 mb-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="image-placeholder">
                                        <i class="fa-regular fa-image"></i>
                                    </div>
                                    <div>
                                        <div class="d-flex flex-column flex-md-row align-items-md-center gap-1 gap-md-2">
                                            <span class="fw-bold fs-6" style="color: #0A2540;">Sake Pairing</span>
                                            <span class="fw-bold text-dark fs-6">¥3,000</span>
                                        </div>
                                        <p class="text-muted small m-0 mt-1">Curated sake selection</p>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-action-edit p-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;" onclick="switchToEditMode('Sake Pairing', 3000, 'Drink', 'Curated sake selection')">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-action-delete p-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="menu-card p-3 mb-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="image-placeholder">
                                        <i class="fa-regular fa-image"></i>
                                    </div>
                                    <div>
                                        <div class="d-flex flex-column flex-md-row align-items-md-center gap-1 gap-md-2">
                                            <span class="fw-bold fs-6" style="color: #0A2540;">Sake Pairing</span>
                                            <span class="fw-bold text-dark fs-6">¥3,000</span>
                                        </div>
                                        <p class="text-muted small m-0 mt-1">Curated sake selection</p>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-action-edit p-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;" onclick="switchToEditMode('Sake Pairing', 3000, 'Drink', 'Curated sake selection')">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-action-delete p-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="menu-card p-3 mb-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="image-placeholder">
                                        <i class="fa-regular fa-image"></i>
                                    </div>
                                    <div>
                                        <div class="d-flex flex-column flex-md-row align-items-md-center gap-1 gap-md-2">
                                            <span class="fw-bold fs-6" style="color: #0A2540;">Sake Pairing</span>
                                            <span class="fw-bold text-dark fs-6">¥3,000</span>
                                        </div>
                                        <p class="text-muted small m-0 mt-1">Curated sake selection</p>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-action-edit p-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;" onclick="switchToEditMode('Sake Pairing', 3000, 'Drink', 'Curated sake selection')">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-action-delete p-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                
                    
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-5 mb-md-0 mt-0 mt-md-4">
                <div class="bg-white p-4 rounded-3 shadow-sm border border-0">
                    <h5 class="fw-bold mb-4" id="form-title" style="color: #0A2540;">Add New Menu Item</h5>
                    
                    <form action="#" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold" style="color: #0A2540;">Item Name</label>
                            <input type="text" id="input-item-name" class="form-control form-custom-input" placeholder="e.g., Omakase Course">
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold" style="color: #0A2540;">Price (¥)</label>
                            <input type="number" id="input-price" class="form-control form-custom-input" placeholder="e.g., 15000">
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold" style="color: #0A2540;">Category</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <button type="button" id="btn-cat-food" class="btn w-100 btn-category-toggle active" onclick="selectCategory('Food')">Food</button>
                                </div>
                                <div class="col-6">
                                    <button type="button" id="btn-cat-drink" class="btn w-100 btn-category-toggle" onclick="selectCategory('Drink')">Drink</button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold" style="color: #0A2540;">Description</label>
                            <textarea id="input-description" class="form-control form-custom-input" rows="3" placeholder="Describe the menu..."></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold" style="color: #0A2540;">Photo (optional)</label>
                            <div class="upload-zone">
                                <i class="fa-regular fa-image mb-2 d-block text-muted" style="font-size: 28px;"></i>
                                <span class="small text-muted d-block">Click to upload photo</span>
                            </div>
                        </div>

                        <div id="action-buttons-container">
                            <button type="submit" class="btn w-100 btn-main-submit d-flex align-items-center justify-content-center gap-2">
                                <i class="fa-solid fa-plus small"></i> Add Menu Item
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
            <div class="modal-content modal-content-custom p-3">
                <div class="modal-body text-center">
                    <h5 class="fw-bold mb-3" style="color: #0A2540;">Are you sure?</h5>
                    <p class="text-muted small mb-4">Do you really want to delete this menu item? This action cannot be undone.</p>
                    
                    <div class="d-flex gap-2">
                        <button type="button" class="btn w-50 btn-cancel" data-bs-dismiss="modal" style="padding: 10px;">Cancel</button>
                        <button type="button" class="btn w-50 btn-modal-delete" style="padding: 10px;">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // 1. Food / Drink トグル切り替えの制御
        function selectCategory(category) {
            const foodBtn = document.getElementById('btn-cat-food');
            const drinkBtn = document.getElementById('btn-cat-drink');

            if (category === 'Food') {
                foodBtn.classList.add('active');
                drinkBtn.classList.remove('active');
            } else {
                drinkBtn.classList.add('active');
                foodBtn.classList.remove('active');
            }
        }

        // 2. 編集モード（Edit Menu Item）への切り替え
        function switchToEditMode(name, price, category, description) {
            // タイトルと各フォーム変数の値を書き換え
            document.getElementById('form-title').innerText = 'Edit Menu Item';
            document.getElementById('input-item-name').value = name;
            document.getElementById('input-price').value = price;
            document.getElementById('input-description').value = description;
            
            // カテゴリトグルの状態を合わせる
            selectCategory(category);

            // 下部のボタンを「Save Changes」と「Cancel」の2つオブジェクトに書き換える
            const buttonContainer = document.getElementById('action-buttons-container');
            buttonContainer.innerHTML = `
                <div class="d-flex gap-2">
                    <button type="button" class="btn w-50 btn-cancel" onclick="switchToCreateMode()">Cancel</button>
                    <button type="submit" class="btn w-50 btn-main-submit d-flex align-items-center justify-content-center gap-2">
                        <i class="fa-solid fa-check small"></i> Save Changes
                    </button>
                </div>
            `;
        }

        // 3. 新規追加モード（Add New Menu Item）へのリセット
        function switchToCreateMode() {
            document.getElementById('form-title').innerText = 'Add New Menu Item';
            document.getElementById('input-item-name').value = '';
            document.getElementById('input-price').value = '';
            document.getElementById('input-description').value = '';
            selectCategory('Food');

            const buttonContainer = document.getElementById('action-buttons-container');
            buttonContainer.innerHTML = `
                <button type="submit" class="btn w-100 btn-main-submit d-flex align-items-center justify-content-center gap-2">
                    <i class="fa-solid fa-plus small"></i> Add Menu Item
                </button>
            `;
        }
    </script>

@endsection