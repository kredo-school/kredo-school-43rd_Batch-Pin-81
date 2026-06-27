@extends('layouts.restaurant')

@section('title', 'Menu Management')

@section('content')

    <style>
        /* メニューカードの基本スタイル */
        .menu-card {
            background-color: #ffffff;
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            transition: transform 0.2s;
        }

        .menu-card:hover {
            transform: translateY(-2px);
        }

        /* 追加：3個以上でスクロールさせるためのコンテナ */
        .menu-scroll-container {
            max-height: 340px;
            /* カード約3個分の高さ */
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

        /* 画像プレースホルダー */
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
            flex-shrink: 0;
            /* スマホで画像が潰れるのを防ぐ */
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

        /* 選択された時だけのスタイルを設定 */
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
    </style>

    <div class="container pb-5" style="max-width: 1140px;">
        <h2 class="fw-bold mb-4" style="color: #0A2540; font-size: 28px;">Menu Management</h2>

        <div class="row d-flex flex-column-reverse flex-md-row gap-4 gap-md-0">

            <div class="col-12 col-md-7">
                <h5 class="fw-bold mb-3" style="color: #0A2540;">Current Menu Items</h5>

                <div class="mb-4">
                    <span class="text-secondary fw-bold small d-block mb-3" style="letter-spacing: 0.5px;">FOOD</span>

                    <div class="menu-scroll-container">
                        @foreach ($menus->where('menu_category', 'food') as $menu)
                            <div class="menu-card p-3 mb-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">

                                        {{-- FOOD画像表示エリア --}}
                                        @if ($menu->menu_image)
                                            <img src="{{ asset('assets/images/menu/' . $menu->menu_image) }}"
                                                alt="{{ $menu->menu_name }}"
                                                style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; flex-shrink: 0;">
                                        @else
                                            <div class="image-placeholder">
                                                <i class="fa-regular fa-image"></i>
                                            </div>
                                        @endif

                                        <div>
                                            <div
                                                class="d-flex flex-column flex-md-row align-items-md-center gap-1 gap-md-2">
                                                <span class="fw-bold fs-6"
                                                    style="color: #0A2540;">{{ $menu->menu_name }}</span>
                                                <span
                                                    class="fw-bold text-dark fs-6">¥{{ number_format($menu->price) }}</span>
                                            </div>
                                            <p class="text-muted small m-0 mt-1">{{ $menu->description }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-sm btn-action-edit p-2"
                                            style="width: 32px; height: 32px; display: flex; align-items: center; 
                                            justify-content: center;"
                                            onclick="switchToEditMode({{ $menu->id }}, '{{ $menu->menu_name }}', {{ $menu->price }}, 'food', '{{ $menu->description }}')">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-action-delete p-2"
                                            style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;"
                                            data-bs-toggle="modal" data-bs-target="#deleteConfirmModal"
                                            onclick="setDeleteAction({{ $menu->id }})">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if ($menus->where('menu_category', 'food')->isEmpty())
                            <p class="text-muted small text-center my-4">No food items added yet.</p>
                        @endif
                    </div>

                </div>

                <div class="mb-4">
                    <span class="text-secondary fw-bold small d-block mb-3" style="letter-spacing: 0.5px;">DRINK</span>

                    <div class="menu-scroll-container">
                        @foreach ($menus->where('menu_category', 'drink') as $menu)
                            <div class="menu-card p-3 mb-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">

                                        {{-- DRINK画像表示エリア --}}
                                        @if ($menu->menu_image)
                                            <img src="{{ asset('assets/images/menu/' . $menu->menu_image) }}"
                                                alt="{{ $menu->menu_name }}"
                                                style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; flex-shrink: 0;">
                                        @else
                                            <div class="image-placeholder">
                                                <i class="fa-regular fa-image"></i>
                                            </div>
                                        @endif

                                        <div>
                                            <div
                                                class="d-flex flex-column flex-md-row align-items-md-center gap-1 gap-md-2">
                                                <span class="fw-bold fs-6"
                                                    style="color: #0A2540;">{{ $menu->menu_name }}</span>
                                                <span
                                                    class="fw-bold text-dark fs-6">¥{{ number_format($menu->price) }}</span>
                                            </div>
                                            <p class="text-muted small m-0 mt-1">{{ $menu->description }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-sm btn-action-edit p-2"
                                            style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;"
                                            onclick="switchToEditMode({{ $menu->id }}, '{{ $menu->menu_name }}', {{ $menu->price }}, 'drink', '{{ $menu->description }}')">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-action-delete p-2"
                                            style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;"
                                            data-bs-toggle="modal" data-bs-target="#deleteConfirmModal"
                                            onclick="setDeleteAction({{ $menu->id }})">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if ($menus->where('menu_category', 'drink')->isEmpty())
                            <p class="text-muted small text-center my-4">No drink items added yet.</p>
                        @endif
                    </div>
                </div>

            </div>

            <div class="col-12 col-md-5 mb-md-0 mt-0 mt-md-4">
                <div class="bg-white p-4 rounded-3 shadow-sm border border-0">
                    <h5 class="fw-bold mb-4" id="form-title" style="color: #0A2540;">Add New Menu Item</h5>

                    <form action="{{ route('restaurant.menu.store') }}" method="POST" id="menu-form"
                        enctype="multipart/form-data">
                        @csrf

                        <div id="method-field"></div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold" style="color: #0A2540;">Item Name</label>
                            <input type="text" id="input-item-name" class="form-control form-custom-input"
                                placeholder="e.g., Omakase Course" name="menu_name">

                            @error('menu_name')
                                <div class="invalid-feedback d-block small mt-1 fw-bold text-danger">{{ $message }}</div>
                            @enderror

                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold" style="color: #0A2540;">Price (¥)</label>
                            <input type="number" id="input-price" class="form-control form-custom-input"
                                placeholder="e.g., 15000" name="price">

                            @error('price')
                                <div class="invalid-feedback d-block small mt-1 fw-bold text-danger">{{ $message }}</div>
                            @enderror

                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold" style="color: #0A2540;">Category</label>

                            <input type="hidden" id="input-menu-category" name="menu_category" value="food">

                            <div class="row g-2">
                                <div class="col-6">
                                    <button type="button" id="btn-cat-food" class="btn w-100 btn-category-toggle active"
                                        onclick="selectCategory('food')">Food</button>
                                </div>
                                <div class="col-6">
                                    <button type="button" id="btn-cat-drink" class="btn w-100 btn-category-toggle"
                                        onclick="selectCategory('drink')">Drink</button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold" style="color: #0A2540;">Description</label>
                            <textarea id="input-description" class="form-control form-custom-input" rows="3"
                                placeholder="Describe the menu..." name="description"></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold" style="color: #0A2540;">Photo (optional)</label>
                            <div class="upload-zone" onclick="document.getElementById('input-photo').click()" style="min-height: 140px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px;">
                                
                                {{-- プレビュー画像（選択されたらJavaScriptでd-noneを外す） --}}
                                <img id="upload-preview" src="#" alt="Preview" class="d-none" style="max-width: 100%; max-height: 100px; border-radius: 8px; object-fit: cover;">
                                
                                {{-- デフォルト表示（画像選択後は非表示にする） --}}
                                <div id="upload-zone-default" class="text-center">
                                    <i class="fa-regular fa-image mb-2 d-block text-muted" style="font-size: 28px;"></i>
                                    <span class="small text-muted d-block" id="upload-zone-text">Click to upload photo</span>
                                </div>
                            </div>
                        
                            <input type="file" id="input-photo" name="menu_image" class="d-none" accept="image/*"
                                onchange="previewImage(this)">
                        
                            @error('menu_image')
                                <div class="invalid-feedback d-block small mt-1 fw-bold text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="action-buttons-container">
                            <button type="submit" id="btn-create-submit"
                                class="btn w-100 btn-main-submit d-flex align-items-center justify-content-center gap-2">
                                <i class="fa-solid fa-plus small"></i> Add Menu Item
                            </button>

                            <div id="edit-buttons-group" class="d-none gap-2">
                                <button type="button" class="btn w-50 btn-cancel"
                                    onclick="switchToCreateMode()">Cancel</button>
                                <button type="submit"
                                    class="btn w-50 btn-main-submit d-flex align-items-center justify-content-center gap-2">
                                    <i class="fa-solid fa-check small"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    @include('restaurants.menus.modals.delete_menu')

    <script>
        // 💡 追加：表示されているエラーメッセージを非表示にする関数
        function clearValidationErrors() {
            const errorMessages = document.querySelectorAll('.invalid-feedback');
            errorMessages.forEach(function(error) {
                error.classList.add('d-none');
            });
        }

        // 1. Food / Drink トグル切り替えの制御
        function selectCategory(category) {
            const foodBtn = document.getElementById('btn-cat-food');
            const drinkBtn = document.getElementById('btn-cat-drink');
            const categoryInput = document.getElementById('input-menu-category');

            if (category === 'food') {
                foodBtn.classList.add('active');
                drinkBtn.classList.remove('active');
                categoryInput.value = 'food';
            } else {
                drinkBtn.classList.add('active');
                foodBtn.classList.remove('active');
                categoryInput.value = 'drink';
            }
        }

        // 2. 編集モード（Edit Menu Item）への切り替え
        function switchToEditMode(menuId, name, price, category, description) {
            // 他のメニュー編集を押した時に古いエラーをクリア
            clearValidationErrors();

            document.getElementById('form-title').innerText = 'Edit Menu Item';
            document.getElementById('input-item-name').value = name;
            document.getElementById('input-price').value = price;
            document.getElementById('input-description').value = description;

            selectCategory(category);

            const form = document.getElementById('menu-form');
            form.action = `/restaurant/menu/${menuId}/update`;

            const methodField = document.getElementById('method-field');
            methodField.innerHTML = `@method('PATCH')`;

            document.getElementById('btn-create-submit').classList.add('d-none');
            document.getElementById('edit-buttons-group').classList.remove('d-none');
            document.getElementById('edit-buttons-group').classList.add('d-flex');
        }

        // 3. 新規追加モード（Add New Menu Item）へのリセット
        function switchToCreateMode() {
            // キャンセルボタンを押した時などにエラーをクリア
            clearValidationErrors();

            document.getElementById('form-title').innerText = 'Add New Menu Item';
            document.getElementById('input-item-name').value = '';
            document.getElementById('input-price').value = '';
            document.getElementById('input-description').value = '';
            document.getElementById('input-photo').value = '';

            // プレビュー状態をリセット
            const preview = document.getElementById('upload-preview');
            const defaultZone = document.getElementById('upload-zone-default');
            if (preview && defaultZone) {
                preview.src = '#';
                preview.classList.add('d-none');
                defaultZone.classList.remove('d-none');
            }

            document.getElementById('upload-zone-text').innerText = "Click to upload photo";

            selectCategory('food');

            const form = document.getElementById('menu-form');
            form.action = "{{ route('restaurant.menu.store') }}";

            document.getElementById('method-field').innerHTML = '';

            document.getElementById('edit-buttons-group').classList.add('d-none');
            document.getElementById('edit-buttons-group').classList.remove('d-flex');
            document.getElementById('btn-create-submit').classList.remove('d-none');
        }

        // 4. ファイル選択時にプレビューを表示するオブジェクト
        function previewImage(input) {
            const preview = document.getElementById('upload-preview');
            const defaultZone = document.getElementById('upload-zone-default');
            const textSpan = document.getElementById('upload-zone-text');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none'); // プレビュー画像を表示
                    defaultZone.classList.add('d-none'); // デフォルト表示を隠す
                }

                reader.readAsDataURL(input.files[0]);
                textSpan.innerText = input.files[0].name;
            } else {
                preview.src = '#';
                preview.classList.add('d-none');
                defaultZone.classList.remove('d-none');
                textSpan.innerText = "Click to upload photo";
            }
        }

        // ゴミ箱ボタンが押されたときのアクション設定
        function setDeleteAction(menuId) {
            const deleteForm = document.getElementById('delete-menu-form');
            deleteForm.action = `/restaurant/menu/${menuId}/destroy`;
        }
    </script>

@endsection