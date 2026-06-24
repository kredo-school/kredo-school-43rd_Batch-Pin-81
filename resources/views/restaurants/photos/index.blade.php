@extends('layouts.restaurant')

@section('title', 'Restaurant Photos')

@section('content')

    <style>
        /* 写真カード全体のラッパーオブジェクト */
        .photo-section-card {
            background-color: #ffffff;
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
            padding: 24px;
        }

        /* 画像と削除ボタンを入れるコンテナオブジェクト（相対配置の基準） */
        .photo-wrapper {
            position: relative;
            width: 100%;
            aspect-ratio: 1 / 1;
            border-radius: 12px;
            overflow: hidden;
            /* 角丸からはみ出るのを防ぐ */
        }

        /* 画像オブジェクトの基本スタイル */
        .photo-item-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.2s, filter 0.2s;
        }

        /* ホバー時に画像を少し暗くしてバツマークを目立たせる */
        .photo-wrapper:hover .photo-item-img {
            transform: scale(1.02);
            filter: brightness(0.7);
        }

        /* Figma風の削除ボタン（ホバーで浮き上がるオブジェクト） */
        .btn-photo-delete {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 28px;
            height: 28px;
            background-color: rgba(255, 255, 255, 0.9) !important;
            color: #bb2d3b !important;
            border: none !important;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            cursor: pointer;
            opacity: 0;
            transition: all 0.2s ease-in-out;
            z-index: 2;
        }

        /* 親のphoto-wrapperにカーソルが乗ったら削除ボタンを表示 */
        .photo-wrapper:hover .btn-photo-delete {
            opacity: 1;
        }

        /* 削除ボタン自体にホバーした時は赤背景に白文字 */
        .btn-photo-delete:hover {
            background-color: #bb2d3b !important;
            color: #ffffff !important;
        }

        /* 写真アップロード・ダミー枠のスタイル（#e5e7eb を採用） */
        .photo-add-placeholder {
            width: 100%;
            aspect-ratio: 1 / 1;
            border: 2px dashed #e5e7eb;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            cursor: pointer;
            text-decoration: none;
            background-color: #ffffff;
            transition: all 0.2s;
        }

        .photo-add-placeholder:hover {
            border-color: #0A2540;
            background-color: #f8f9fa;
            color: #0A2540;
        }

        /* グレーの画像プレースホルダーオブジェクト */
        .photo-loading-placeholder {
            width: 100%;
            aspect-ratio: 1 / 1;
            background-color: #f1f3f5;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #adb5bd;
            font-size: 32px;
        }

        /* アクションボタン（Upload） */
        .btn-photo-upload {
            background-color: #0A2540 !important;
            color: #ffffff !important;
            border: none !important;
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 600;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-photo-upload:hover {
            opacity: 0.9;
        }

        /* 「See all photos」のトグルリンクボタン */
        .btn-toggle-photos {
            color: #0A2540;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: none;
            border: none;
            padding: 0;
            margin-top: 16px;
        }

        .btn-toggle-photos:hover {
            text-decoration: underline;
        }

        .hidden-photos {
            display: none;
        }

        /* 💡 追記：モーダル内のCancelボタン（ネイビーの枠線にネイビーの文字） */
        .btn-cancel {
            background-color: transparent !important;
            color: #0A2540 !important;
            border: 1px solid #0A2540 !important;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s;
        }

        .btn-cancel:hover {
            background-color: #0A2540 !important;
            color: #ffffff !important;
        }

        /*  追記：モーダル内のDeleteボタン（赤の枠線に赤の文字、ホバーで赤背景） */
        .btn-modal-delete {
            background-color: transparent !important;
            color: #bb2d3b !important;
            border: 1px solid #bb2d3b !important;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s;
        }

        .btn-modal-delete:hover {
            background-color: #bb2d3b !important;
            color: #ffffff !important;
        }
    </style>

    <div class="container pb-5" style="max-width: 1140px;">
        <h2 class="fw-bold mb-4" style="color: #0A2540; font-size: 28px;">Restaurant Photos</h2>

        <div class="d-flex flex-column gap-4">

            {{-- 💡 カテゴリーの連想配列オブジェクト --}}
            @php
                $categories = [
                    'food' => 'Food',
                    'drink' => 'Drink',
                    'interior' => 'Interior',
                    'exterior' => 'Exterior',
                    'other' => 'Other',
                ];
            @endphp

            @foreach ($categories as $key => $label)
                @php
                    // コントローラーから送られてきた全体の $photos コレクションから、現在のカテゴリーに合うオブジェクトを抽出
                    $filteredPhotos = $photos->where('photo_category', $key);
                    $photoCount = $filteredPhotos->count();
                @endphp

                <div class="photo-section-card">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="d-flex align-items-baseline gap-2">
                            <h5 class="fw-bold m-0" style="color: #0A2540;">{{ $label }}</h5>
                            <span class="text-muted small">{{ $photoCount }}
                                {{ Str::plural('photo', $photoCount) }}</span>
                        </div>
                        <button class="btn btn-photo-upload" data-bs-toggle="modal" data-bs-target="#uploadPhotoModal"
                            data-category="{{ $key }}">
                            <i class="fa-solid fa-arrow-up-from-bracket"></i> Upload
                        </button>
                    </div>

                    <div class="row g-3">
                        {{-- 📸 各カテゴリー内の写真をループ表示 --}}
                        @foreach ($filteredPhotos->values() as $index => $photo)
                            {{-- 3枚目(インデックス2)より後ろの写真オブジェクトは隠しクラスを付与 --}}
                            <div class="col-6 col-md-3 {{ $index >= 2 ? 'hidden-photos ' . $key . '-extra' : '' }}">
                                <div class="photo-wrapper">
                                    {{-- 💡 実際の画像ストレージURLをセット（仮画像から本番用に移行） --}}
                                    <img src="{{ asset('storage/' . $photo->photo_path) }}"
                                        alt="{{ $label }} {{ $index + 1 }}" class="photo-item-img">

                                    {{-- 💡 写真固有のデータベースIDオブジェクト（$photo->id）を関数に渡す --}}
                                    <button type="button" class="btn-photo-delete"
                                        onclick="prepareDelete({{ $photo->id }})" data-bs-toggle="modal"
                                        data-bs-target="#deletePhotoModal">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach

                        {{-- ➕ 常に末尾に配置する写真追加用のプレースホルダー --}}
                        <div class="col-6 col-md-3">
                            <div class="photo-add-placeholder" data-bs-toggle="modal" data-bs-target="#uploadPhotoModal"
                                data-category="{{ $key }}">
                                <i class="fa-solid fa-arrow-up-from-bracket mb-2" style="font-size: 18px;"></i>
                                <span class="small fw-semibold">Add Photo</span>
                            </div>
                        </div>
                    </div>

                    {{-- 3枚以上写真がある場合のみ「See all」トグルを表示 --}}
                    @if ($photoCount > 2)
                        <button type="button" class="btn-toggle-photos"
                            onclick="togglePhotos('{{ $key }}-extra', this, {{ $photoCount }})">
                            <i class="fa-solid fa-chevron-down small icon-arrow"></i> <span>See all {{ $photoCount }}
                                photos</span>
                        </button>
                    @endif
                </div>
            @endforeach

        </div>
    </div>

    {{-- 💡 分割管理したモーダルコンポーネントを綺麗に読み込み --}}
    @include('restaurants.photos.modals.delete_photo')
    @include('restaurants.photos.modals.upload_photo')

    <script>
        // どの写真が選ばれたかをモーダルのフォームに動的にセットするオブジェクト関数
        function prepareDelete(photoId) {
            // 1. モーダル内の隠し変数に入力
            document.getElementById('target-photo-id').value = photoId;

            // 2. フォームの送信先URLを動的に書き換える
            document.getElementById('delete-photo-form').action = '/restaurant/photos/' + photoId;
        }

        // 写真表示のトグル関数（表示文字を総数に応じて動的に書き換えるようにアップデート）
        function togglePhotos(targetClass, buttonObj, totalCount) {
            const extraPhotos = document.querySelectorAll('.' + targetClass);
            const textSpan = buttonObj.querySelector('span');
            const icon = buttonObj.querySelector('.icon-arrow');

            if (extraPhotos.length > 0 && (extraPhotos[0].style.display === 'none' || extraPhotos[0].style.display ===
                '')) {
                extraPhotos.forEach(el => {
                    el.style.display = 'block';
                });
                textSpan.innerText = 'Show less';
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            } else {
                extraPhotos.forEach(el => {
                    el.style.display = 'none';
                });
                textSpan.innerText = 'See all ' + totalCount + ' photos';
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        }

        // Uploadモーダルが開いたときにカテゴリーを自動選択するオブジェクト
        const uploadPhotoModal = document.getElementById('uploadPhotoModal');
        if (uploadPhotoModal) {
            uploadPhotoModal.addEventListener('show.bs.modal', function(event) {
                // クリックされたボタン（またはdiv）オブジェクトを取得
                const button = event.relatedTarget;
                // ボタンに仕込んだ data-category の値（food, drink など）を取得
                const category = button.getAttribute('data-category');

                // モーダル内のセレクトボックス要素を特定
                const selectMode = uploadPhotoModal.querySelector('#photo_category');

                // カテゴリーが取得できている場合は、その値を初期選択にする
                if (category) {
                    selectMode.value = category;
                } else {
                    selectMode.value = ""; // 直押しなどの場合はデフォルト
                }
            });
        }
    </script>

@endsection
