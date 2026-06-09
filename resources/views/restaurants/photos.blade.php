@extends('layouts.restaurant')

@section('title', 'Restaurant Photos')

@section('content')

    <style>
        /* 写真カード全体のラッパーオブジェクト */
        .photo-section-card {
            background-color: #ffffff;
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
            padding: 24px;
        }

        /* 画像と削除ボタンを入れるコンテナオブジェクト（相対配置の基準） */
        .photo-wrapper {
            position: relative;
            width: 100%;
            aspect-ratio: 1 / 1;
            border-radius: 12px;
            overflow: hidden; /* 角丸からはみ出るのを防ぐ */
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

        /* カスタムモーダルのスタイル設定 */
        .modal-content-custom {
            border-radius: 16px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .btn-cancel {
            background-color: transparent !important;
            color: #0A2540 !important;
            border: 1px solid #0A2540 !important;
            border-radius: 8px;
            font-weight: bold;
        }
        .btn-cancel:hover {
            background-color: #0A2540 !important;
            color: #ffffff !important;
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
        <h2 class="fw-bold mb-4" style="color: #0A2540; font-size: 28px;">Restaurant Photos</h2>

        <div class="d-flex flex-column gap-4">

            <div class="photo-section-card">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="d-flex align-items-baseline gap-2">
                        <h5 class="fw-bold m-0" style="color: #0A2540;">Food</h5>
                        <span class="text-muted small">4 photos</span>
                    </div>
                    <button class="btn btn-photo-upload">
                        <i class="fa-solid fa-arrow-up-from-bracket"></i> Upload
                    </button>
                </div>

                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="photo-wrapper">
                            <img src="https://images.unsplash.com/photo-1579871494447-9811cf80d66c?w=400&auto=format&fit=crop&q=60" alt="Sushi 1" class="photo-item-img">
                            <button type="button" class="btn-photo-delete" onclick="prepareDelete(101)" data-bs-toggle="modal" data-bs-target="#deletePhotoModal">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="photo-wrapper">
                            <img src="https://images.unsplash.com/photo-1611143669185-af224c5e3252?w=400&auto=format&fit=crop&q=60" alt="Sushi 2" class="photo-item-img">
                            <button type="button" class="btn-photo-delete" onclick="prepareDelete(102)" data-bs-toggle="modal" data-bs-target="#deletePhotoModal">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="photo-loading-placeholder">
                            <i class="fa-regular fa-image"></i>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="photo-add-placeholder">
                            <i class="fa-solid fa-arrow-up-from-bracket mb-2" style="font-size: 18px;"></i>
                            <span class="small fw-semibold">Add Photo</span>
                        </div>
                    </div>

                    <div class="col-6 col-md-3 hidden-photos food-extra">
                        <div class="photo-wrapper">
                            <img src="https://images.unsplash.com/photo-1553621042-f6e147245754?w=400&auto=format&fit=crop&q=60" alt="Sushi 3" class="photo-item-img">
                            <button type="button" class="btn-photo-delete" onclick="prepareDelete(103)" data-bs-toggle="modal" data-bs-target="#deletePhotoModal">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 hidden-photos food-extra">
                        <div class="photo-wrapper">
                            <img src="https://images.unsplash.com/photo-1563612116625-3012372fccbc?w=400&auto=format&fit=crop&q=60" alt="Sushi 4" class="photo-item-img">
                            <button type="button" class="btn-photo-delete" onclick="prepareDelete(104)" data-bs-toggle="modal" data-bs-target="#deletePhotoModal">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn-toggle-photos" onclick="togglePhotos('food-extra', this)">
                    <i class="fa-solid fa-chevron-down small icon-arrow"></i> <span>See all 4 photos</span>
                </button>
            </div>


            <div class="photo-section-card">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="d-flex align-items-baseline gap-2">
                        <h5 class="fw-bold m-0" style="color: #0A2540;">Drink</h5>
                        <span class="text-muted small">0 photos</span>
                    </div>
                    <button class="btn btn-photo-upload">
                        <i class="fa-solid fa-arrow-up-from-bracket"></i> Upload
                    </button>
                </div>

                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="photo-add-placeholder">
                            <i class="fa-solid fa-arrow-up-from-bracket mb-2" style="font-size: 18px;"></i>
                            <span class="small fw-semibold">Add Photo</span>
                        </div>
                    </div>
                </div>
            </div>


            <div class="photo-section-card">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="d-flex align-items-baseline gap-2">
                        <h5 class="fw-bold m-0" style="color: #0A2540;">Interior</h5>
                        <span class="text-muted small">2 photos</span>
                    </div>
                    <button class="btn btn-photo-upload">
                        <i class="fa-solid fa-arrow-up-from-bracket"></i> Upload
                    </button>
                </div>

                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="photo-wrapper">
                            <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=400&auto=format&fit=crop&q=60" alt="Interior 1" class="photo-item-img">
                            <button type="button" class="btn-photo-delete" onclick="prepareDelete(201)" data-bs-toggle="modal" data-bs-target="#deletePhotoModal">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="photo-wrapper">
                            <img src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?w=400&auto=format&fit=crop&q=60" alt="Interior 2" class="photo-item-img">
                            <button type="button" class="btn-photo-delete" onclick="prepareDelete(202)" data-bs-toggle="modal" data-bs-target="#deletePhotoModal">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="photo-add-placeholder">
                            <i class="fa-solid fa-arrow-up-from-bracket mb-2" style="font-size: 18px;"></i>
                            <span class="small fw-semibold">Add Photo</span>
                        </div>
                    </div>
                </div>
            </div>


            <div class="photo-section-card">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="d-flex align-items-baseline gap-2">
                        <h5 class="fw-bold m-0" style="color: #0A2540;">Exterior</h5>
                        <span class="text-muted small">0 photos</span>
                    </div>
                    <button class="btn btn-photo-upload">
                        <i class="fa-solid fa-arrow-up-from-bracket"></i> Upload
                    </button>
                </div>

                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="photo-add-placeholder">
                            <i class="fa-solid fa-arrow-up-from-bracket mb-2" style="font-size: 18px;"></i>
                            <span class="small fw-semibold">Add Photo</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="photo-section-card">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="d-flex align-items-baseline gap-2">
                        <h5 class="fw-bold m-0" style="color: #0A2540;">Other</h5>
                        <span class="text-muted small">0 photos</span>
                    </div>
                    <button class="btn btn-photo-upload">
                        <i class="fa-solid fa-arrow-up-from-bracket"></i> Upload
                    </button>
                </div>

                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="photo-add-placeholder">
                            <i class="fa-solid fa-arrow-up-from-bracket mb-2" style="font-size: 18px;"></i>
                            <span class="small fw-semibold">Add Photo</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="deletePhotoModal" tabindex="-1" aria-labelledby="deletePhotoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
            <div class="modal-content modal-content-custom p-3">
                <div class="modal-body text-center">
                    {{-- <div class="text-danger mb-3" style="font-size: 48px;">
                        <i class="fa-regular fa-circle-question"></i>
                    </div> --}}
                    <h5 class="fw-bold mb-3" style="color: #0A2540;">Delete this photo?</h5>
                    <p class="text-muted small mb-4">Are you sure you want to remove this photo? This action cannot be undone.</p>
                    
                    <form id="delete-photo-form" action="#" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" id="target-photo-id" name="photo_id" value="">
                        
                        <div class="d-flex gap-2">
                            <button type="button" class="btn w-50 btn-cancel" data-bs-dismiss="modal" style="padding: 10px;">Cancel</button>
                            <button type="submit" class="btn w-50 btn-modal-delete" style="padding: 10px;">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // どの写真が選ばれたかをモーダルのフォームにセットするオブジェクト
        function prepareDelete(photoId) {
            document.getElementById('target-photo-id').value = photoId;
        }

        // 写真表示のトグル関数
        function togglePhotos(targetClass, buttonObj) {
            const extraPhotos = document.querySelectorAll('.' + targetClass);
            const textSpan = buttonObj.querySelector('span');
            const icon = buttonObj.querySelector('.icon-arrow');
            
            if (extraPhotos.length > 0 && (extraPhotos[0].style.display === 'none' || extraPhotos[0].style.display === '')) {
                extraPhotos.forEach(el => { el.style.display = 'block'; });
                textSpan.innerText = 'Show less';
                icon.classList.remove('fa-chevron-down'); icon.classList.add('fa-chevron-up');
            } else {
                extraPhotos.forEach(el => { el.style.display = 'none'; });
                textSpan.innerText = 'See all 4 photos';
                icon.classList.remove('fa-chevron-up'); icon.classList.add('fa-chevron-down');
            }
        }
    </script>

@endsection