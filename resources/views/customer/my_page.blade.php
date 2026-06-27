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
                            style="color: #0a2540; font-size: 1.6rem; line-height: 1.2;">{{ $user->name ?? 'john_doe' }}
                        </h2>
                    </div>
                    <div class="d-flex align-items-center gap-2 gap-md-3 mb-md-4 w-60">
                        <a href="/customer/profile"
                            class="btn btn-sm fw-bold text-navy text-center mt-3 mt-md-3 px-2 px-md-3 py-1 rounded-3 custom-edit-btn w-30 w-md-auto"
                            style="color: #0a2540; border: 1px solid #dbdbdb; border-radius: 5px; font-size: 0.85rem; background-color: #fffaf4; display: inline-block;">

                            <span class="d-md-none">Edit <i class="fa-solid fa-user"></i></span>

                            <span class="d-none d-md-inline">Edit Profile</span>
                        </a>

                        <button type="button" onclick="openCreatePostModal()"
                            class="btn btn-sm fw-bold text-navy text-center mt-3 mt-md-3 px-2 px-md-3 py-1 rounded-3 custom-post-btn w-30 w-md-auto"
                            style="color: #0a2540; border: 1px solid #dbdbdb; border-radius: 5px; font-size: 0.85rem; background-color: #fffaf4; display: inline-block;">

                            <span class="d-md-none">Post <i class="fa-solid fa-pencil"></i></span>

                            <span class="d-none d-md-inline">Post Review</span>
                        </button>
                    </div>
                    <div class="d-none d-md-flex align-items-center" style="gap: 40px;">
                        <div>
                            <span class="fw-bold" style="font-size: 1.15rem; color: #0a2540;">{{ $posts->count() }}</span>
                            <span class="text-secondary ms-1">reviews</span>
                        </div>
                        <div>
                            <span class="fw-bold" style="font-size: 1.15rem; color: #0a2540;">142</span>
                            <span class="text-secondary ms-1">followers</span>
                        </div>
                        <div>
                            <span class="fw-bold" style="font-size: 1.15rem; color: #0a2540;">89</span>
                            <span class="text-secondary ms-1">following</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-flex d-md-none text-center pt-3" style="border-color: #efefef !important;">
                <div class="col-4"><span class="fw-bold d-block"
                        style="font-size: 1.1rem; color: #051d3b;">{{ $posts->count() }}</span><span class="text-muted"
                        style="font-size: 0.8rem; color: #8e8e8e !important;">reviews</span></div>
                <div class="col-4"><span class="fw-bold d-block" style="font-size: 1.1rem; color: #051d3b;">142</span><span
                        class="text-muted" style="font-size: 0.8rem; color: #8e8e8e !important;">followers</span></div>
                <div class="col-4"><span class="fw-bold d-block" style="font-size: 1.1rem; color: #051d3b;">89</span><span
                        class="text-muted" style="font-size: 0.8rem; color: #8e8e8e !important;">following</span></div>
            </div>
            <hr class="mt-4 mb-0" style="border-color: #dbdbdb; opacity: 0.5;">
        </div>

        <div class="container px-2 px-md-0" style="max-width: 935px;">
            <div class="row g-1 g-md-4">
                @forelse($posts as $post)
                    @php
                        if (!empty($post->image)) {
                            $images = is_string($post->image) ? explode(',', $post->image) : [$post->image];
                        } else {
                            $images = [
                                'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?w=600&auto=format&fit=crop',
                            ];
                        }
                        $firstImage = asset($images[0]);
                    @endphp

                    <div class="col-4">
                        <div class="ratio ratio-1x1 overflow-hidden bg-light review-item review-hover-box"
                            style="cursor: pointer; position: relative;"
                            onclick="openPostModal('modal-{{ $post->id }}')">

                            @if(preg_match('/\.(mp4|mov|ogg|qt)$/i', $firstImage))
                                <video src="{{ $firstImage }}" class="object-fit-cover w-100 h-100" muted></video>
                                <div class="position-absolute top-0 end-0 p-2 text-white" style="z-index: 2;">
                                    <i class="fa-solid fa-video shadow-sm"></i>
                                </div>
                            @else
                                <img src="{{ $firstImage }}" class="object-fit-cover w-100 h-100" alt="Post Image">
                            @endif

                            <div class="hover-mask">
                                <span class="hover-likes-text">
                                    <i class="fa-solid fa-heart me-2"></i>{{ $post->likes_count ?? rand(5, 50) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5 text-muted">
                        Create your first post<i class="fa-regular fa-paper-plane ms-1"></i>
                    </div>
                @endforelse
            </div>
        </div>

        @foreach ($posts as $post)
            @php
                if (!empty($post->image)) {
                    $images = is_string($post->image) ? explode(',', $post->image) : [$post->image];
                } else {
                    $images = [
                        'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?w=600&auto=format&fit=crop',
                        'https://images.unsplash.com/photo-1611143669185-af224c5e3252?w=600&auto=format&fit=crop',
                    ];
                }
            @endphp
            <div id="modal-{{ $post->id }}" class="custom-post-modal-wrapper" style="display: none;">
                <div class="custom-modal-backdrop" onclick="closePostModal('modal-{{ $post->id }}')"></div>

                <div class="custom-modal-content-box">
                    <button class="custom-modal-close-btn"
                        onclick="closePostModal('modal-{{ $post->id }}')">&times;</button>

                    <div class="custom-modal-body d-flex flex-column flex-md-row">

                        <div class="custom-modal-img-container position-relative">
                            <div id="carousel-{{ $post->id }}" class="carousel slide h-100 w-100" data-bs-ride="false">
                                <div class="carousel-inner h-100">
                                    @foreach ($images as $index => $img)
                                        <div class="carousel-item h-100 @if ($index === 0) active @endif">
                                            <div class="d-flex align-items-center justify-content-center h-100 w-100">
                                                @if(preg_match('/\.(mp4|mov|ogg|qt)$/i', asset($img)))
                                                    <video src="{{ asset($img) }}" class="d-block w-100 h-100 object-fit-contain" controls></video>
                                                @else
                                                    <img src="{{ asset($img) }}" class="d-block" alt="Post Slide">
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @if (count($images) > 1)
                                    <button class="carousel-control-prev d-none d-md-flex" type="button"
                                        data-bs-target="#carousel-{{ $post->id }}" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next d-none d-md-flex" type="button"
                                        data-bs-target="#carousel-{{ $post->id }}" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                @endif
                            </div>
                        </div>

                        <div class="custom-modal-info-container d-flex flex-column bg-white">

                            <div class="d-flex align-items-center p-3 border-bottom">
                                <img src="{{ $post->user->avatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop' }}"
                                    alt="Avatar" class="rounded-circle object-fit-cover me-2"
                                    style="width: 32px; height: 32px;">
                                <div>
                                    <span class="fw-bold d-block text-dark lh-1"
                                        style="font-size: 0.9rem;">{{ $post->user->name ?? 'john_doe' }}</span>
                                    <span class="text-muted"
                                        style="font-size: 0.75rem;">{{ $post->created_at ? $post->created_at->format('Y-m-d') : '2026-05-10' }}</span>
                                </div>
                            </div>

                            <div class="p-3 flex-grow-1 overflow-y-auto"
                                style="font-size: 0.9rem; background-color: #fffaf7;">
                                <div class="d-flex mb-3">
                                    <div class="flex-grow-1">
                                        <span class="fw-bold me-1 text-dark">{{ $post->user->name ?? 'john_doe' }}</span>
                                        <p class="text-secondary my-1">{{ $post->description ?? 'ここに投稿のテキスト文章が入ります。' }}
                                        </p>
                                    </div>
                                </div>
                                <hr class="my-2 opacity-25">

                                <div class="comments-list-container">
                                    @if ($post->comments && $post->comments->count() > 0)
                                        @foreach ($post->comments as $comment)
                                            <div class="d-flex mb-2 align-items-start" style="font-size: 0.85rem;">
                                                <img src="{{ $comment->user->avatar ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=80&auto=format&fit=crop' }}"
                                                    class="rounded-circle object-fit-cover me-2 mt-1"
                                                    style="width: 24px; height: 24px;">
                                                <div>
                                                    <span
                                                        class="fw-bold text-dark me-1">{{ $comment->user->name ?? 'User' }}</span>
                                                    <span class="text-secondary">{{ $comment->content }}</span>
                                                    <span class="d-block text-muted mt-1 cursor-pointer reply-trigger"
                                                        style="font-size: 0.7rem;"
                                                        onclick="setupReply('{{ $post->id }}', '{{ $comment->user->name ?? 'User' }}')">Reply</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted text-center py-3" style="font-size: 0.8rem;">No comments yet.
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="p-3 border-top bg-white">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex gap-3 fs-4 text-dark">
                                        <i class="fa-regular fa-heart cursor-pointer hover-red"
                                            onclick="toggleLike(this, '{{ $post->id }}')"></i>
                                        <i class="fa-regular fa-comment cursor-pointer"
                                            onclick="focusCommentInput('{{ $post->id }}')"></i>
                                        <i class="fa-regular fa-paper-plane cursor-pointer"></i>
                                    </div>
                                </div>
                                <div class="fw-bold text-dark" style="font-size: 0.85rem;">
                                    <span id="likes-count-{{ $post->id }}">{{ $post->likes_count ?? 24 }}</span>
                                    likes
                                </div>
                            </div>

                            <div class="border-top p-2 bg-light">
                                <form action="/customer/posts/{{ $post->id }}/comment" method="POST"
                                    class="d-flex align-items-center m-0">
                                    @csrf
                                    <input type="text" id="comment-input-{{ $post->id }}" name="content"
                                        class="form-control form-control-sm border-0 bg-transparent focus-none px-2"
                                        placeholder="Add a comment..." required autocomplete="off"
                                        style="font-size: 0.85rem;">
                                    <button type="submit" class="btn btn-sm fw-bold text-primary border-0 bg-transparent"
                                        style="font-size: 0.85rem;">Post</button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div id="createPostModal" class="custom-post-modal-wrapper" style="display: none;">
            <div class="custom-modal-backdrop" onclick="closeCreatePostModal()"></div>

            <div class="custom-modal-content-box p-4" style="max-width: 500px; max-height: auto;">
                <button class="custom-modal-close-btn" onclick="closeCreatePostModal()" style="color: #000;">&times;</button>
                
                <h4 class="fw-bold mb-3" style="color: #0a2540;">Write a Review</h4>
                
                <form action="/restaurants/0/post" method="POST" enctype="multipart/form-data" id="reviewForm">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="restaurant_select" class="form-label fw-bold text-dark" style="font-size: 0.85rem;">Select Restaurant</label>
                        <select name="restaurant_id" id="restaurant_select" class="form-select focus-none" required style="font-size: 0.9rem;">
                            <option value="" disabled selected>-- Visited within 1 week --</option>
                            @foreach($visitedRestaurants as $restaurant)
                                <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
                            @endforeach
                        </select>
                        @if($visitedRestaurants->isEmpty())
                            <small class="text-danger d-block mt-1" style="font-size: 0.75rem;">No restaurants visited within the last week.</small>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="rating_select" class="form-label fw-bold text-dark" style="font-size: 0.85rem;">Rating (Optional)</label>
                        <select name="rating" id="rating_select" class="form-select focus-none" style="font-size: 0.9rem;">
                            <option value="">-- Select Rating --</option>
                            <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                            <option value="4">⭐⭐⭐⭐ (4)</option>
                            <option value="3">⭐⭐⭐ (3)</option>
                            <option value="2">⭐⭐ (2)</option>
                            <option value="1">⭐ (1)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="description_textarea" class="form-label fw-bold text-dark" style="font-size: 0.85rem;">Comment (Optional)</label>
                        <textarea name="description" id="description_textarea" rows="4" class="form-control focus-none" placeholder="Share your experience..." style="font-size: 0.9rem; border-radius: 6px;"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="media_input" class="form-label fw-bold text-dark" style="font-size: 0.85rem;">Photo / Video (Optional)</label>
                        <input type="file" name="media" id="media_input" class="form-control focus-none" accept="image/*,video/*" style="font-size: 0.85rem;">
                        <small class="text-muted d-block mt-1" style="font-size: 0.7rem;">Max file size: 50MB (Supports images & video formats)</small>
                    </div>

                    <div class="text-end pt-2">
                        <button type="button" class="btn btn-sm btn-light border me-2 px-3" onclick="closeCreatePostModal()">Cancel</button>
                        <button type="submit" id="submitReviewBtn" class="btn btn-sm text-white px-3" style="background-color: #0a2540;" {{ $visitedRestaurants->isEmpty() ? 'disabled' : '' }}>Submit</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <style>
        @media (max-width: 767.98px) {
            .avatar-responsive {
                width: 77px !important;
                height: 77px !important;
            }

            .custom-modal-content-box {
                height: 90vh;
            }

            .custom-modal-img-container {
                width: 100%;
                height: 45%;
                overflow: hidden;
            }

            .carousel-inner {
                display: flex !important;
                overflow-x: auto !important;
                scroll-snap-type: x mandatory;
                height: 100%;
            }

            .carousel-item {
                display: block !important;
                flex: 0 0 100%;
                scroll-snap-align: start;
                width: 100%;
                height: 100%;
            }

            .custom-modal-info-container {
                width: 100%;
                height: 55%;
            }
        }

        @media (min-width: 768px) {
            .avatar-responsive {
                width: 150px !important;
                height: 150px !important;
            }

            .custom-modal-content-box {
                height: 85vh;
                display: flex;
            }

            .custom-modal-img-container {
                width: 60%;
                height: 100%;
            }

            .custom-modal-info-container {
                width: 40%;
                height: 100%;
            }
        }

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

        .custom-modal-backdrop {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.75);
        }

        .custom-modal-content-box {
            position: relative;
            background-color: #fff;
            width: 100%;
            max-width: 935px;
            border-radius: 8px;
            overflow: hidden;
            z-index: 2001;
            animation: fadeInModal 0.2s ease-out;
        }

        .custom-modal-body {
            width: 100%;
            height: 100%;
        }

        .custom-modal-img-container {
            background-color: #000;
        }

        .custom-modal-img-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        @keyframes fadeInModal {
            from {
                opacity: 0;
                transform: scale(0.97);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .custom-modal-close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            background: none;
            border: none;
            font-size: 1.8rem;
            color: #fff;
            z-index: 2200;
            cursor: pointer;
            opacity: 0.8;
        }

        .custom-modal-close-btn:hover {
            opacity: 1;
            color: #ddd;
        }

        @media (max-width: 767.98px) {
            .custom-modal-close-btn {
                color: #000;
                top: 5px;
                right: 10px;
            }
        }

        .review-hover-box {
            position: relative;
        }

        .hover-mask {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s;
            z-index: 3;
        }

        .hover-likes-text {
            color: #fff;
            font-size: 1.2rem;
            font-weight: 700;
        }

        .review-hover-box:hover .hover-mask {
            opacity: 1;
            visibility: visible;
        }

        .custom-edit-btn,
        .custom-post-btn {
            background-color: #fffaf4 !important;
            border: 1px solid #dbdbdb;
            transition: all 0.2s ease;
        }

        .custom-edit-btn:hover,
        .custom-post-btn:hover {
            background-color: #0a2540 !important;
            color: #fff !important;
            transform: translateY(-1px);
        }

        .focus-none:focus {
            box-shadow: none !important;
        }

        .cursor-pointer {
            disabled: pointer;
            cursor: pointer;
        }

        .hover-red:hover {
            color: #ed4956;
        }
    </style>

    <script>
        // 💡 🆕 新規追加: 投稿モーダルを開閉するJavaScript関数
        function openCreatePostModal() {
            document.getElementById('createPostModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeCreatePostModal() {
            document.getElementById('createPostModal').style.display = 'none';
            document.body.style.overflow = '';
        }

        // 💡 🆕 新規追加: ドロップダウンが変更されたら送信先URLを選択された店舗IDにリアルタイムで書き換える
        document.getElementById('restaurant_select').addEventListener('change', function() {
            var restaurantId = this.value;
            var form = document.getElementById('reviewForm');
            // アクションURLを '/restaurants/{id}/post' 形式に書き換える
            form.action = '/restaurants/' + restaurantId + '/post';
        });

        function openPostModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closePostModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
            document.body.style.overflow = '';
        }

        function focusCommentInput(postId) {
            document.getElementById('comment-input-' + postId).focus();
        }

        function setupReply(postId, username) {
            const input = document.getElementById('comment-input-' + postId);
            input.value = '@' + username + ' ';
            input.focus();
        }

        function toggleLike(element, postId) {
            const likesCountSpan = document.getElementById('likes-count-' + postId);
            let currentLikes = parseInt(likesCountSpan.innerText);

            if (element.classList.contains('fa-regular')) {
                element.classList.remove('fa-regular');
                element.classList.add('fa-solid', 'text-danger');
                likesCountSpan.innerText = currentLikes + 1;
            } else {
                element.classList.remove('fa-solid', 'text-danger');
                element.classList.add('fa-regular');
                likesCountSpan.innerText = currentLikes - 1;
            }
        }
    </script>
@endsection