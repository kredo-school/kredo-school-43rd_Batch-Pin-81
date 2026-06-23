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
                            <span class="fw-bold" style="font-size: 1.15rem; color: #051d3b;">{{ $posts->count() }}</span>
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
                <div class="col-4"><span class="fw-bold d-block" style="font-size: 1.1rem; color: #051d3b;">{{ $posts->count() }}</span><span
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

                @foreach($posts as $post)
                    <div class="col-4">
                        <div class="ratio ratio-1x1 overflow-hidden bg-light review-item review-hover-box"
                            style="cursor: pointer; position: relative;" 
                            onclick="openPostModal('post-modal-{{ $post->id }}')">

                            <img src="{{ $post->image }}" class="object-fit-cover w-100 h-100" alt="Sushi">

                            <div class="hover-mask">
                                <span class="hover-likes-text">
                                    <i class="fa-solid fa-heart me-2"></i>24
                                </span>
                            </div>
                        </div>
                    </div>

                    <div id="post-modal-{{ $post->id }}" class="custom-post-modal-wrapper" style="display: none;">
                        <div class="custom-modal-backdrop" onclick="closePostModal('post-modal-{{ $post->id }}')"></div>
                        
                        <div class="custom-modal-content-box">
                            <button onclick="closePostModal('post-modal-{{ $post->id }}')" class="custom-modal-close-btn">&times;</button>

                            <div class="custom-modal-body d-flex flex-column flex-md-row">
                                <div class="custom-modal-img-container">
                                    <img src="{{ $post->image }}" alt="拡大画像">
                                </div>

                                <div class="custom-modal-info-container d-flex flex-column">
                                    <div class="p-3 border-bottom bg-white d-flex align-items-center gap-3">
                                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=300&auto=format&fit=crop" alt="User Icon" class="rounded-circle object-fit-cover" style="width: 38px; height: 38px;">
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size: 0.9rem;">john_doe</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">{{ $post->created_at->format('Y-m-d') }}</div>
                                        </div>
                                    </div>

                                    <div class="flex-grow-1 overflow-auto p-3 bg-light" style="max-height: 400px;">
                                        <div class="pb-3 border-bottom mb-3">
                                            <p class="text-secondary my-0" style="font-size: 0.85rem; whitespace: pre-wrap;">{{ $post->description }}</p>
                                        </div>

                                        <div class="d-flex flex-column gap-3">
                                            @forelse($post->comments as $comment)
                                                <div class="d-flex align-items-start gap-2">
                                                    <img src="/images/default-avatar.png" class="rounded-circle object-fit-cover" style="width: 28px; height: 28px;">
                                                    <div class="p-2 rounded-3 flex-grow-1" style="background-color: #eaeaea; max-width: 85%;">
                                                        <span class="fw-bold d-block text-dark" style="font-size: 0.75rem;">{{ $comment->user->name ?? 'User' }}</span>
                                                        <p class="text-dark my-0 mt-1" style="font-size: 0.8rem; line-height: 1.3;">{{ $comment->body }}</p>
                                                    </div>
                                                </div>
                                            @empty
                                                <p class="text-center text-muted py-4 my-0" style="font-size: 0.8rem;">まだコメントはありません。</p>
                                            @endforelse
                                        </div>
                                    </div>

                                    <div class="p-3 border-top bg-white">
                                        <form action="{{ route('comments.store') }}" method="POST" class="my-0">
                                            @csrf
                                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                                            
                                            <div class="d-flex gap-2">
                                                <textarea 
                                                    name="body" 
                                                    rows="1" 
                                                    placeholder="コメントを追加..." 
                                                    required
                                                    style="font-size: 0.85rem; resize: none;"
                                                    class="form-control form-control-sm rounded-3 py-2 px-3 focus-none"
                                                ></textarea>
                                                <button type="submit" class="btn btn-sm btn-primary px-3 fw-bold rounded-3" style="background-color: #051d3b; border: none; font-size: 0.8rem;">
                                                    投稿
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>

        <style>
            /* モーダル全体を覆うラッパー */
            .custom-post-modal-wrapper {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 2000;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }

            /* 黒い背景のレイヤー */
            .custom-modal-backdrop {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.7);
            }

            /* 白いコンテンツボックス本体 */
            .custom-modal-content-box {
                position: relative;
                background-color: #fff;
                width: 100%;
                max-width: 935px;
                height: 85vh;
                border-radius: 12px;
                overflow: hidden;
                z-index: 2001;
                animation: fadeInModal 0.25s ease-out;
            }

            @keyframes fadeInModal {
                from { opacity: 0; transform: scale(0.95); }
                to { opacity: 1; transform: scale(1); }
            }

            /* モーダルのインナースケール構造 */
            .custom-modal-body {
                width: 100%;
                height: 100%;
            }

            /* 左側：拡大画像スペース */
            .custom-modal-img-container {
                width: 60%;
                height: 100%;
                background-color: #000;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .custom-modal-img-container img {
                max-width: 100%;
                max-height: 100%;
                object-fit: contain;
            }

            /* 右側：情報＆コメントスペース */
            .custom-modal-info-container {
                width: 40%;
                height: 100%;
            }

            /* モバイル環境（768px以下）でのレスポンシブ調整 */
            @media (max-width: 767.98px) {
                .custom-modal-content-box {
                    height: 90vh;
                }
                .custom-modal-img-container {
                    width: 100%;
                    height: 45%;
                }
                .custom-modal-info-container {
                    width: 100%;
                    height: 55%;
                }
            }

            /* × 閉じるボタン */
            .custom-modal-close-btn {
                position: absolute;
                top: 10px;
                right: 15px;
                background: none;
                border: none;
                font-size: 2rem;
                font-weight: bold;
                color: #8e8e8e;
                z-index: 2100;
                cursor: pointer;
                line-height: 1;
            }
            .custom-modal-close-btn:hover {
                color: #000;
            }

            /* フォーム入力欄の青枠を消す微調整 */
            .focus-none:focus {
                box-shadow: none !important;
                border-color: #ced4da !important;
            }

            /* 既存マイページスタイル群保持 */
            @media (max-width: 767.98px) {
                .avatar-responsive { width: 77px !important; height: 77px !important; }
            }
            @media (min-width: 768px) {
                .avatar-responsive { width: 150px !important; height: 150px !important; }
            }
            .custom-edit-btn { background-color: #fffaf4 !important; border: none; color: #0a2540; cursor: pointer; }
            .custom-edit-btn:hover { background-color: #0a2540 !important; color: #ffffff !important; transform: translateY(-2px); box-shadow: 0 8px 5px rgba(5, 29, 59, 0.15); }
            .text-navy { color: #0a2540; }
            .review-hover-box { position: relative; }
            .hover-mask { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.45); display: flex; align-items: center; justify-content: center; opacity: 0; visibility: hidden; transition: opacity 0.2s ease, visibility 0.2s ease; z-index: 3; }
            .hover-likes-text { color: #ffffff !important; font-size: 1.3rem; font-weight: 700; display: flex; align-items: center; letter-spacing: 0.5px; }
            .review-hover-box:hover .hover-mask { opacity: 1; visibility: visible; }
        </style>

    </div>

    <script>
        // モーダルを開く
        function openPostModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
            document.body.style.overflow = 'hidden'; // 背後のマイページをスクロール不可に固定
        }

        // モーダルを閉じる
        function closePostModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
            document.body.style.overflow = ''; // スクロール固定を解除
        }
    </script>

@endsection