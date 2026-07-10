@extends('layouts.app')

@section('title', 'Customer My Page')

@section('content')

    <div class="bg-white min-vh-100 pb-5" style="font-family: 'Poppins', 'Helvetica Neue', Arial, sans-serif;">

        <div class="container py-4 py-md-5" style="max-width: 935px;">
            <div class="row align-items-center">
                <div class="col-4 col-md-3 text-center mt-2 mb-2 mb-md-0">
                    <div class="position-relative d-inline-block">
                        <div class="avatar-container-fixed">
                            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}"
                                alt="User Avatar">
                        </div>
                    </div>
                </div>

                <div class="col-8 col-md-9 ps-3 ps-md-4 d-md-flex flex-md-column justify-content-md-center">
                    <div class="mb-md-2">
                        <h2 class="fw-bold my-0 mb-2 tracking-tight"
                            style="color: #0a2540; font-size: min(1.6rem, 6vw); line-height: 1.2;">
                            {{ $user->username ?? explode('@', $user->email)[0] }}
                        </h2>
                    </div>

                    <div class="d-flex align-items-center mt-1 gap-2 gap-md-3 mb-md-4 w-100">
                        <a href="{{ route('customer.profile') }}"
                            class="btn btn-sm fw-bold text-navy text-center px-2 px-md-3 py-1 rounded-3 custom-edit-btn"
                            style="color: #0a2540; border: 1px solid #dbdbdb; border-radius: 5px; font-size: clamp(0.75rem, 3vw, 0.85rem); background-color: #fffaf4; display: inline-block; min-width: fit-content;">
                            <span>Edit Profile</span>
                        </a>
                        <button type="button" onclick="openCreatePostModal()"
                            class="btn btn-sm fw-bold text-navy text-center px-2 px-md-3 py-1 rounded-3 custom-post-btn"
                            style="color: #0a2540; border: 1px solid #dbdbdb; border-radius: 5px; font-size: clamp(0.75rem, 3vw, 0.85rem); background-color: #fffaf4; display: inline-block; min-width: fit-content;">
                            <span>Post Review</span>
                        </button>
                    </div>

                    <div class="d-none d-md-flex align-items-center" style="gap: 40px;">
                        <div>
                            <span class="fw-bold" style="font-size: 1.15rem; color: #0a2540;">{{ $posts->count() }}</span>
                            <span class="text-secondary ms-1">reviews</span>
                        </div>
                        <div>
                            <span class="fw-bold"
                                style="font-size: 1.15rem; color: #0a2540;">{{ $followers->count() }}</span>
                            <span class="text-secondary ms-1">followers</span>
                        </div>
                        <div>
                            <span class="fw-bold"
                                style="font-size: 1.15rem; color: #0a2540;">{{ $followings->count() }}</span>
                            <span class="text-secondary ms-1">following</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row d-flex d-md-none text-center pt-3" style="border-color: #efefef !important;">
                <div class="col-4">
                    <span class="fw-bold d-block" style="font-size: 1.1rem; color: #051d3b;">{{ $posts->count() }}</span>
                    <span class="text-muted" style="font-size: 0.8rem;">reviews</span>
                </div>
                <div class="col-4">
                    <span class="fw-bold d-block"
                        style="font-size: 1.1rem; color: #051d3b;">{{ $followers->count() }}</span>
                    <span class="text-muted" style="font-size: 0.8rem;">followers</span>
                </div>
                <div class="col-4">
                    <span class="fw-bold d-block"
                        style="font-size: 1.1rem; color: #051d3b;">{{ $followings->count() }}</span>
                    <span class="text-muted" style="font-size: 0.8rem;">following</span>
                </div>
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

                            @if (preg_match('/\.(mp4|mov|ogg|qt)$/i', $firstImage))
                                <video src="{{ $firstImage }}" class="object-fit-cover w-100 h-100" muted></video>
                            @else
                                <img src="{{ $firstImage }}" class="object-fit-cover w-100 h-100" alt="Post Image">
                            @endif

                            <div class="hover-mask">
                                <span class="hover-likes-text">
                                    <i class="fa-solid fa-heart me-2"></i>
                                    <span
                                        id="hover-likes-count-{{ $post->id }}">{{ count($post->likes ?? []) }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5 text-muted">
                        Create your first post <i class="fa-regular fa-paper-plane ms-1"></i>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    @foreach ($posts as $post)
        @php
            if (!empty($post->image)) {
                $images = is_string($post->image) ? explode(',', $post->image) : [$post->image];
            } else {
                $images = ['https://images.unsplash.com/photo-1579871494447-9811cf80d66c?w=600&auto=format&fit=crop'];
            }
        @endphp

        <div id="modal-{{ $post->id }}" class="instagram-modal-mask" style="display: none;">
            <div class="instagram-modal-backdrop" onclick="closePostModal('modal-{{ $post->id }}')"></div>

            <button class="instagram-modal-close" onclick="closePostModal('modal-{{ $post->id }}')">&times;</button>

            <div class="instagram-modal-container">
                <div class="instagram-modal-content">
                    <div class="instagram-modal-left">
                        <div id="carousel-{{ $post->id }}" class="carousel slide h-100 w-100" data-bs-ride="false">
                            <div class="carousel-inner h-100 scroll-container-mobile">
                                @foreach ($images as $index => $img)
                                    <div class="carousel-item h-100 @if ($index === 0) active @endif">
                                        <div class="d-flex align-items-center justify-content-center h-100 w-100-container">
                                            @if (preg_match('/\.(mp4|mov|ogg|qt)$/i', asset($img)))
                                                <video src="{{ asset($img) }}"
                                                    class="d-block w-100 h-100 object-fit-contain" controls></video>
                                            @else
                                                <img src="{{ asset($img) }}"
                                                    class="d-block w-100 h-100 object-fit-contain" alt="Post Slide">
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if (count($images) > 1)
                                <button class="carousel-control-prev d-none d-md-block" type="button"
                                    data-bs-target="#carousel-{{ $post->id }}" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                </button>
                                <button class="carousel-control-next d-none d-md-block" type="button"
                                    data-bs-target="#carousel-{{ $post->id }}" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="instagram-modal-right">
                        <div class="instagram-right-header">
                            <div class="d-flex align-items-center">
                                <img src="{{ $post->user->avatar ? asset('storage/' . $post->user->avatar) : asset('images/default-avatar.png') }}"
                                    class="rounded-circle object-fit-cover me-2" style="width: 32px; height: 32px;">
                                <span class="fw-bold text-dark"
                                    style="font-size: 0.9rem;">{{ $post->user->username }}</span>
                            </div>
                            <div class="position-relative">
                                <span class="cursor-pointer fw-bold text-secondary p-1"
                                    onclick="togglePostMenu(event, '{{ $post->id }}')">…</span>
                                <div id="post-dropdown-{{ $post->id }}"
                                    class="post-dropdown-menu shadow-sm border rounded bg-white position-absolute p-1"
                                    style="display: none; right: 0; top: 25px; z-index: 1100; min-width: 140px;">
                                    @if ($post->user_id === Auth::id())
                                        <button
                                            class="btn btn-link text-dark text-start w-100 px-2 py-1.5 m-0 border-0 fw-bold d-flex align-items-center justify-content-between"
                                            style="font-size: 0.85rem; text-decoration: none;"
                                            onclick="startEditPost('{{ $post->id }}')">
                                            <span>Edit</span><i class="fa-regular fa-pen-to-square text-secondary"></i>
                                        </button>
                                        <button
                                            class="btn btn-link text-danger text-start w-100 px-2 py-1.5 m-0 border-0 fw-bold d-flex align-items-center justify-content-between"
                                            style="font-size: 0.85rem; text-decoration: none;"
                                            onclick="openCustomDeleteModal('post', '{{ $post->id }}')">
                                            <span>Delete</span><i class="fa-regular fa-trash-can text-danger"></i>
                                        </button>
                                    @else
                                        <button
                                            class="btn btn-link text-secondary text-start w-100 px-2 py-1.5 m-0 border-0 fw-bold"
                                            style="font-size: 0.85rem; text-decoration: none;"
                                            onclick="alert('Reported')">Report</button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="instagram-right-feed">
                            <div class="d-flex mb-3 align-items-start" style="font-size: 0.85rem;">
                                <img src="{{ $post->user->avatar ? asset('storage/' . $post->user->avatar) : asset('images/default-avatar.png') }}"
                                    class="rounded-circle object-fit-cover me-2" style="width: 32px; height: 32px;">
                                <div class="w-100">
                                    <span class="fw-bold text-dark me-1">{{ $post->user->username }}</span>
                                    <span id="post-text-{{ $post->id }}">{!! nl2br(e($post->description)) !!}</span>

                                    <div id="post-edit-form-{{ $post->id }}" style="display: none;" class="mt-2">
                                        <textarea id="post-edit-input-{{ $post->id }}" class="form-control form-control-sm mb-1" rows="3">{{ $post->description }}</textarea>
                                        <div class="d-flex gap-1 justify-content-end">
                                            <button type="button" class="btn btn-sm btn-secondary py-0 px-2"
                                                style="font-size: 0.75rem;"
                                                onclick="cancelEditPost('{{ $post->id }}')">Cancel</button>
                                            <button type="button" class="btn btn-sm btn-dark py-0 px-2"
                                                style="font-size: 0.75rem;"
                                                onclick="submitEditPost('{{ $post->id }}')">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr style="border-color: #efefef; margin: 12px 0;">

                            <div id="comments-container-{{ $post->id }}">
                                @forelse($post->comments as $comment)
                                    <div class="d-flex mb-3 align-items-start position-relative comment-row"
                                        style="font-size: 0.85rem;" id="comment-wrapper-{{ $comment->id }}">
                                        <img src="{{ $comment->user->avatar ? asset('storage/' . $comment->user->avatar) : asset('images/default-avatar.png') }}"
                                            class="rounded-circle object-fit-cover me-2"
                                            style="width: 28px; height: 28px;">
                                        <div class="w-100 pe-2">
                                            <div>
                                                <span class="fw-bold text-dark me-1">{{ $comment->user->username }}</span>
                                                <span id="comment-text-{{ $comment->id }}">{!! nl2br(e($comment->body)) !!}</span>

                                                <div id="comment-edit-form-{{ $comment->id }}" style="display: none;"
                                                    class="mt-1">
                                                    <input type="text" id="comment-edit-input-{{ $comment->id }}"
                                                        class="form-control form-control-sm mb-1"
                                                        value="{{ $comment->body }}">
                                                    <div class="d-flex gap-1 justify-content-end align-items-center">
                                                        <button type="button"
                                                            class="btn btn-link p-0 text-danger text-decoration-none fw-bold"
                                                            style="font-size: 0.75rem; line-height: 1;"
                                                            onclick="cancelEditComment('{{ $comment->id }}')">Cancel</button>
                                                        <span class="text-muted mx-1"
                                                            style="font-size: 0.75rem; line-height: 1;">/</span>
                                                        <button type="button"
                                                            class="btn btn-link p-0 text-primary text-decoration-none fw-bold"
                                                            style="font-size: 0.75rem; line-height: 1;"
                                                            onclick="submitEditComment('{{ $comment->id }}')">Save</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-1 d-flex gap-2 align-items-center"
                                                style="font-size: 0.7rem; color: #8e8e8e;">
                                                <span>{{ $comment->created_at->diffForHumans() }}</span>
                                                <span class="fw-bold cursor-pointer text-primary"
                                                    onclick="setupReply('{{ $post->id }}', '{{ $comment->user->username }}')">Reply</span>

                                                <div class="position-relative d-inline">
                                                    <span class="fw-bold cursor-pointer text-secondary px-1"
                                                        onclick="toggleCommentMenu(event, '{{ $comment->id }}')">…</span>
                                                    <div id="comment-dropdown-{{ $comment->id }}"
                                                        class="comment-dropdown-menu shadow-sm border rounded bg-white position-absolute p-1"
                                                        style="display: none; left: 0; top: 15px; z-index: 1100; min-width: 130px;">
                                                        @if ($comment->user_id === Auth::id())
                                                            <button
                                                                class="btn btn-link text-dark text-start w-100 px-2 py-1 m-0 border-0 fw-bold d-flex align-items-center justify-content-between"
                                                                style="font-size: 0.75rem; text-decoration: none;"
                                                                onclick="startEditComment('{{ $comment->id }}')">
                                                                <span>Edit</span><i
                                                                    class="fa-regular fa-pen-to-square text-secondary"></i>
                                                            </button>
                                                            <button
                                                                class="btn btn-link text-danger text-start w-100 px-2 py-1 m-0 border-0 fw-bold d-flex align-items-center justify-content-between"
                                                                style="font-size: 0.75rem; text-decoration: none;"
                                                                onclick="openCustomDeleteModal('comment', '{{ $comment->id }}')">
                                                                <span>Delete</span><i
                                                                    class="fa-regular fa-trash-can text-danger"></i>
                                                            </button>
                                                        @else
                                                            <button
                                                                class="btn btn-link text-secondary text-start w-100 px-2 py-1 m-0 border-0 fw-bold"
                                                                style="font-size: 0.75rem; text-decoration: none;"
                                                                onclick="alert('Reported')">Report</button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-muted py-3" id="no-comments-{{ $post->id }}"
                                        style="font-size: 0.85rem;">No comments yet.</div>
                                @endforelse
                            </div>
                        </div>

                        <div class="instagram-right-actions border-top">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <button type="button" class="btn-like-trigger"
                                    onclick="toggleLike(this, '{{ $post->id }}')"
                                    style="background: none; border: none; padding: 0; cursor: pointer; outline: none;">
                                    @if (Auth::check() && $post->isLikedBy(Auth::user()))
                                        <i class="fa-solid fa-heart text-danger" style="font-size: 1.5rem;"></i>
                                    @else
                                        <i class="fa-regular fa-heart text-dark" style="font-size: 1.5rem;"></i>
                                    @endif
                                </button>
                            </div>
                            <div class="fw-bold text-dark mb-1" style="font-size: 0.85rem;">
                                <span id="likes-count-{{ $post->id }}">{{ $post->likes()->count() }}</span> likes
                            </div>
                            <div class="text-uppercase text-muted" style="font-size: 0.65rem; letter-spacing: 0.2px;">
                                {{ $post->created_at->format('F d, Y') }}
                            </div>
                        </div>

                        <div class="instagram-right-comment-box d-flex align-items-center px-3 py-2 border-top">
                            <div class="me-2 text-dark d-flex align-items-center">
                                <i class="fa-regular fa-comment" style="font-size: 1.3rem;"></i>
                            </div>
                            <input type="text" id="comment-input-{{ $post->id }}"
                                class="form-control border-0 p-0 shadow-none flex-grow-1" placeholder="Add a comment..."
                                style="font-size: 0.85rem; background: transparent;"
                                oninput="document.getElementById('comment-btn-{{ $post->id }}').disabled = this.value.trim() === '';">
                            <button type="button" id="comment-btn-{{ $post->id }}" class="comment-submit-btn ms-2"
                                onclick="submitComment('{{ $post->id }}')" disabled>Post</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div id="createPostModal" class="instagram-modal-mask" style="display: none;">
        <div class="instagram-modal-backdrop" onclick="closeCreatePostModal()"></div>

        <div class="bg-white p-4 shadow-lg border-0 position-relative"
            style="max-width: 500px; width: 90%; border-radius: 12px; z-index: 1060; margin: auto;">

            <button type="button" class="instagram-modal-close" onclick="closeCreatePostModal()"
                style="color: #333; position: absolute; top: 15px; right: 20px; font-size: 1.5rem; background: none; border: none; padding: 0; line-height: 1;">&times;</button>

            <div class="text-center mb-4">
                <h4 class="fw-bold m-0" style="color: #0a2540;">Write a Review</h4>
            </div>

            <form id="reviewForm" action="{{ route('customer.posts.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <div class="form-group fw-bold" style="color: #0a2540;">
                    <label for="restaurant">Restaurant Name <span style="color: red;">*Required</span></label>
                    <select id="restaurant" name="restaurant_id" class="form-control text-dark mb-2"
                        style="color: #000 !important; background-color: #fff !important;">
                        <option value="" style="color: #000 !important;">Select a restaurant</option>

                        @foreach ($visitedRestaurants as $item)
                            @if ($item)
                                @php
                                    // 🕵️‍♂️ オブジェクトの構造を自動判別して、確実にIDと名前を抽出します
                                    $resId = isset($item->restaurant) ? $item->restaurant->id : $item->id;
                                    $resName = isset($item->restaurant)
                                        ? $item->restaurant->name
                                        : $item->name ?? ($item->restaurant_name ?? 'Unknown Restaurant');
                                @endphp

                                <option value="{{ $resId }}"
                                    style="color: #000 !important; background-color: #fff !important;">
                                    {{ $resName }}
                                </option>
                            @endif
                        @endforeach

                    </select>
                </div>

                <div class="form-group fw-bold" style="color: #0a2540;">
                    <label>Rating <span style="color: red;">*Required</span></label>
                    <div class="rating-group">
                        @for ($i = 1; $i <= 5; $i++)
                            <input type="radio" name="rating" value="{{ $i }}"
                                id="star{{ $i }}" class="rating-input">
                            <label for="star{{ $i }}">★</label>
                        @endfor
                    </div>
                </div>

                <div class="form-group fw-bold" style="color: #0a2540;">
                    <label for="comment">Comment (Optional)</label>
                    <textarea name="comment" id="comment" class="form-control mb-2" placeholder="Write your review here..."></textarea>
                </div>

                <div class="form-group fw-bold" style="color: #0a2540;">
                    <label for="mediaInput">Photos / Videos (Optional)</label>
                    <input type="file" name="media[]" id="mediaInput" accept="image/*,video/*" multiple
                        class="form-control mb-2">

                    <div id="mediaPreview" style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px;"></div>
                </div>

                <button type="submit" id="submitBtn" class="btn-submit mt-2" disabled>
                    Submit Review
                </button>
            </form>
        </div>
    </div>

    <div id="customDeleteModal" class="custom-confirm-mask" style="display: none;">
        <div class="custom-confirm-backdrop" onclick="closeCustomDeleteModal()"></div>
        <div class="custom-confirm-box">
            <div class="custom-confirm-body">
                <h5 class="m-0 fw-bold text-dark" style="font-size: 1rem;">Delete Item?</h5>
                <p class="text-secondary mt-2 mb-0" style="font-size: 0.85rem;">Are you sure you want to delete this? This
                    action cannot be undone.</p>
            </div>
            <div class="custom-confirm-footer">
                <button type="button" id="customDeleteConfirmBtn" class="btn-confirm-delete">Delete</button>
                <button type="button" class="btn-confirm-cancel" onclick="closeCustomDeleteModal()">Cancel</button>
            </div>
        </div>
    </div>

    <form id="globalDeleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <style>
        /* ========================================== */
        /* 1. 全体共通・プロファイル関連 */
        /* ========================================== */
        /* PC版アバターの基準サイズ指定 (150px) */
        .avatar-container-fixed {
            display: inline-block !important;
            width: 150px !important;
            height: 150px !important;
            min-width: 150px !important;
            min-height: 150px !important;
            max-width: 150px !important;
            max-height: 150px !important;
            overflow: hidden !important;
            border-radius: 50% !important;
            padding: 0 !important;
            border: 1px solid #dbdbdb;
        }

        .avatar-container-fixed img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            border-radius: 50% !important;
        }

        .review-hover-box {
            position: relative;
            transition: transform 0.2s ease;
        }

        .review-hover-box:hover {
            transform: scale(1.01);
        }

        .hover-mask {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .review-hover-box:hover .hover-mask {
            opacity: 1;
        }

        .hover-likes-text {
            color: #ffffff;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .hover-red:hover {
            color: #ed4956 !important;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        /* ========================================== */
        /* 2. インスタ風モーダル（共通構造） */
        /* ========================================== */
        .instagram-modal-mask {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1050;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .instagram-modal-backdrop {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.65);
        }

        .instagram-modal-container {
            position: relative;
            background-color: #ffffff;
            width: 95%;
            max-width: 935px;
            height: 85vh;
            max-height: 850px;
            border-radius: 4px;
            overflow: hidden;
            z-index: 1060;
            display: flex;
            flex-direction: column;
        }

        .instagram-modal-close {
            position: fixed;
            top: 25px;
            right: 35px;
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.75);
            font-size: 2.8rem;
            font-weight: 300;
            cursor: pointer;
            line-height: 1;
            z-index: 1150;
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .instagram-modal-close:hover {
            color: #ffffff;
            transform: scale(1.1);
        }

        /* ========================================== */
        /* 3. 投稿詳細モーダル内部 */
        /* ========================================== */
        .instagram-modal-content {
            display: flex;
            flex-direction: row;
            width: 100%;
            height: 100%;
        }

        .instagram-modal-left {
            flex: 1.2;
            background-color: #000000;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        .instagram-modal-right {
            flex: 1;
            display: flex;
            flex-direction: column;
            background-color: #ffffff;
            border-left: 1px solid #efefef;
            height: 100%;
        }

        .instagram-right-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 16px;
            border-bottom: 1px solid #efefef;
        }

        .instagram-right-feed {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
        }

        .instagram-right-feed span {
            word-break: break-all;
        }

        .instagram-right-actions {
            padding: 14px 16px !important;
            background-color: #ffffff;
        }

        .instagram-right-comment-box {
            padding: 0 16px;
            height: 56px;
            border-top: 1px solid #efefef;
            display: flex;
            align-items: center;
            background-color: #ffffff;
        }

        .comment-submit-btn {
            background: none;
            border: none;
            color: #0095f6;
            font-weight: bold;
            font-size: 0.85rem;
            cursor: pointer;
            padding: 0 8px;
            white-space: nowrap;
        }

        .comment-submit-btn:disabled {
            opacity: 0.4;
            cursor: default;
        }

        /* ドロップダウンメニュー共通調整 */
        .post-dropdown-menu,
        .comment-dropdown-menu {
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1) !important;
        }

        /* ========================================== */
        /* 4. カスタム確認モーダルのスタイル */
        /* ========================================== */
        .custom-confirm-mask {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .custom-confirm-backdrop {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .custom-confirm-box {
            position: relative;
            background-color: #ffffff;
            width: 85%;
            max-width: 320px;
            border-radius: 12px;
            overflow: hidden;
            z-index: 2010;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            animation: confirmPop 0.2s ease-out;
        }

        @keyframes confirmPop {
            from {
                transform: scale(0.9);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .custom-confirm-body {
            padding: 24px 20px;
            text-align: center;
        }

        .custom-confirm-footer {
            display: flex;
            border-top: 1px solid #efefef;
        }

        .btn-confirm-delete,
        .btn-confirm-cancel {
            flex: 1;
            border: none;
            background: none;
            padding: 12px 0;
            font-size: 0.9rem;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
        }

        .btn-confirm-delete {
            color: #ed4956;
            border-right: 1px solid #efefef;
        }

        .btn-confirm-delete:hover {
            background-color: #fafafa;
        }

        .btn-confirm-cancel {
            color: #262626;
        }

        .btn-confirm-cancel:hover {
            background-color: #fafafa;
        }

        /* ========================================== */
        /* 🌟 5. 新規追加: レビュー投稿フォーム関連 */
        /* ========================================== */
        /* Rating（星評価グループ）の横並び調整 */
        .rating-group {
            display: flex;
            flex-direction: row-reverse;
            /* 右から並べることでCSSでのホバー連動を容易に */
            justify-content: flex-end;
            gap: 4px;
        }

        .rating-input {
            display: none;
            /* 本物のラジオボタンは隠す */
        }

        .rating-group label {
            font-size: 1.8rem;
            color: #dbdbdb;
            /* 未選択時の星の色（薄いグレー） */
            cursor: pointer;
            transition: color 0.15s ease;
        }

        /* ラジオボタンがチェックされた時と、ホバー時の星の色（鮮やかなゴールド） */
        .rating-input:checked~label,
        .rating-group label:hover,
        .rating-group label:hover~label {
            color: #ffb703;
        }

        /* ユーザー選択済みの写真・動画プレビュー表示用の枠 */
        .preview-item-wrapper {
            position: relative;
            width: 100px;
            height: 100px;
            border-radius: 6px;
            overflow: hidden;
            border: 1px solid #dbdbdb;
            background-color: #000000;
        }

        .preview-item-wrapper img,
        .preview-item-wrapper video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* レビュー送信ボタン（Submit Review）の基本定義 */
        .btn-submit {
            display: block;
            width: 100%;
            background-color: #1a2a4a;
            /* ベースの紺色 */
            color: #ffffff;
            font-weight: bold;
            font-size: 1rem;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: not-allowed;
            opacity: 0.4;
            /* 条件未達時は薄く表示 */
            transition: opacity 0.2s ease, background-color 0.2s ease;
        }

        /* 必須2点が選択され、活性化した際のクラス指定 */
        .btn-submit.active {
            opacity: 1;
            /* 濃く鮮明に表示 */
            cursor: pointer;
        }

        .btn-submit.active:hover {
            background-color: #111c33; //#0a2540;
            /* ホバー時に少し深みのある紺に */
        }

        /* ========================================== */
        /* 6. レスポンシブ対応（スマホ表示時の上書き） */
        /* ========================================== */
        @media (max-width: 767.98px) {
            .instagram-modal-close {
                position: absolute;
                top: 10px;
                right: 15px;
                color: #333333;
                background: rgba(255, 255, 255, 0.7);
                border-radius: 50%;
                width: 30px;
                height: 30px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
                z-index: 1070;
            }

            /* スマホアバターサイズ変更: 被らず見栄えの良い「100px」に指定を上書き */
            .avatar-container-fixed,
            .avatar-responsive {
                width: 100px !important;
                height: 100px !important;
                min-width: 100px !important;
                min-height: 100px !important;
                max-width: 100px !important;
                max-height: 100px !important;
            }

            /* スマホ版のネイティブ横スクロール制御（スナップ付き・崩れ防止） */
            .scroll-container-mobile {
                display: flex !important;
                flex-direction: row !important;
                flex-wrap: nowrap !important;
                overflow-x: auto !important;
                scroll-snap-type: x mandatory !important;
                -webkit-overflow-scrolling: touch !important;
            }

            /* Bootstrapカルーセルアイテムのスマホ時スライド幅固定 */
            .scroll-container-mobile .carousel-item {
                display: block !important;
                margin-right: 0 !important;
                flex-shrink: 0 !important;
                width: 100% !important;
                scroll-snap-align: start !important;
            }

            /* スクロールバーを完全に非表示にしてスッキリさせる */
            .scroll-container-mobile::-webkit-scrollbar {
                display: none !important;
            }

            .scroll-container-mobile {
                -ms-overflow-style: none !important;
                scrollbar-width: none !important;
            }

            /* モーダル配置の最適化 */
            .instagram-modal-container {
                height: 90vh;
                max-height: none;
            }

            .instagram-modal-content {
                flex-direction: column;
            }

            .instagram-modal-left {
                flex: none;
                height: 40%;
            }

            .instagram-modal-right {
                flex: 1;
                height: 60%;
                border-left: none;
            }

            .instagram-right-header {
                padding: 10px 12px;
                padding-right: 50px !important;
            }

            .instagram-right-feed {
                padding: 12px;
            }
        }
    </style>

    <script>
        // ==========================================
        // 1. Modal Open & Close Actions
        // ==========================================
        function openPostModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }

        function closePostModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = '';

                const postId = modalId.replace('modal-', '');
                cancelEditPost(postId);
            }
        }

        function openCreatePostModal() {
            const modal = document.getElementById('createPostModal');
            if (modal) {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }

        function closeCreatePostModal() {
            const modal = document.getElementById('createPostModal');
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = '';

                const form = document.getElementById('reviewForm');
                if (form) {
                    form.reset();
                    // Reset the custom media preview area
                    const previewZone = document.getElementById('mediaPreview');
                    if (previewZone) previewZone.innerHTML = '';
                    // Re-validate the form state to disable the submit button
                    validateReviewForm();
                }
            }
        }

        // ==========================================
        // 2. Review Form Validation (Live Listener)
        // ==========================================
        function validateReviewForm() {
            const form = document.getElementById('reviewForm');
            if (!form) return;

            const restaurantSelect = form.querySelector('select[name="restaurant_id"]');
            const ratingRadios = form.querySelectorAll('input[name="rating"]');
            const submitBtn = document.getElementById('submitBtn');

            if (!submitBtn) return;

            // Check if a restaurant is selected
            const isRestaurantSelected = restaurantSelect && restaurantSelect.value !== '';

            // Check if any rating star is selected
            let isRatingSelected = false;
            ratingRadios.forEach(radio => {
                if (radio.checked) isRatingSelected = true;
            });

            // Toggle visual state and disable attribute based on both required fields
            if (isRestaurantSelected && isRatingSelected) {
                submitBtn.disabled = false;
                submitBtn.classList.add('active');
            } else {
                submitBtn.disabled = true;
                submitBtn.classList.remove('active');
            }
        }

        // ==========================================
        // 3. Review & Edit Dropdown Menus
        // ==========================================
        function togglePostMenu(event, postId) {
            event.stopPropagation();
            document.querySelectorAll('.post-dropdown-menu, .comment-dropdown-menu').forEach(menu => {
                if (menu.id !== `post-dropdown-${postId}`) menu.style.display = 'none';
            });

            const dropdown = document.getElementById(`post-dropdown-${postId}`);
            if (dropdown) {
                dropdown.style.display = (dropdown.style.display === 'none' || dropdown.style.display === '') ? 'block' :
                    'none';
            }
        }

        function toggleCommentMenu(event, commentId) {
            event.stopPropagation();
            document.querySelectorAll('.post-dropdown-menu, .comment-dropdown-menu').forEach(menu => {
                if (menu.id !== `comment-dropdown-${commentId}`) menu.style.display = 'none';
            });

            const dropdown = document.getElementById(`comment-dropdown-${commentId}`);
            if (dropdown) {
                dropdown.style.display = (dropdown.style.display === 'none' || dropdown.style.display === '') ? 'block' :
                    'none';
            }
        }

        // ==========================================
        // 4. Post Edit Actions (In-place)
        // ==========================================
        function startEditPost(postId) {
            const textSpan = document.getElementById(`post-text-${postId}`);
            const editForm = document.getElementById(`post-edit-form-${postId}`);
            const editInput = document.getElementById(`post-edit-input-${postId}`);
            const dropdown = document.getElementById(`post-dropdown-${postId}`);

            if (textSpan && editInput) {
                editInput.value = textSpan.innerHTML.replace(/<br\s*\/?>/gi, '\n').trim();
            }

            if (textSpan) textSpan.style.display = 'none';
            if (editForm) editForm.style.display = 'block';
            if (dropdown) dropdown.style.display = 'none';
        }

        function cancelEditPost(postId) {
            const textSpan = document.getElementById(`post-text-${postId}`);
            const editForm = document.getElementById(`post-edit-form-${postId}`);
            const editInput = document.getElementById(`post-edit-input-${postId}`);

            if (textSpan && editInput) {
                editInput.value = textSpan.innerHTML.replace(/<br\s*\/?>/gi, '\n').trim();
            }

            if (textSpan) textSpan.style.display = 'block';
            if (editForm) editForm.style.display = 'none';
        }

        function submitEditPost(postId) {
            const editInput = document.getElementById(`post-edit-input-${postId}`);
            if (!editInput) return;
            const newText = editInput.value;
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch(`/customer/posts/${postId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        _method: 'PUT',
                        description: newText
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error();
                    const textSpan = document.getElementById(`post-text-${postId}`);
                    if (textSpan) textSpan.innerHTML = newText.replace(/\n/g, '<br>');
                    cancelEditPost(postId);
                })
                .catch(() => alert('Failed to update post.'));
        }

        // ==========================================
        // 5. Comment Edit Actions (In-place)
        // ==========================================
        function startEditComment(commentId) {
            const textSpan = document.getElementById(`comment-text-${commentId}`);
            const editForm = document.getElementById(`comment-edit-form-${commentId}`);
            const editInput = document.getElementById(`comment-edit-input-${commentId}`);
            const dropdown = document.getElementById(`comment-dropdown-${commentId}`);

            if (textSpan && editInput) {
                editInput.value = textSpan.innerHTML.replace(/<br\s*\/?>/gi, '\n').replace(/&nbsp;/g, ' ').trim();
            }

            if (textSpan) textSpan.style.display = 'none';
            if (editForm) editForm.style.display = 'block';
            if (dropdown) dropdown.style.display = 'none';
        }

        function cancelEditComment(commentId) {
            const textSpan = document.getElementById(`comment-text-${commentId}`);
            const editForm = document.getElementById(`comment-edit-form-${commentId}`);
            const editInput = document.getElementById(`comment-edit-input-${commentId}`);

            if (textSpan && editInput) {
                editInput.value = textSpan.innerHTML.replace(/<br\s*\/?>/gi, '\n').replace(/&nbsp;/g, ' ').trim();
            }

            if (textSpan) textSpan.style.display = 'inline';
            if (editForm) editForm.style.display = 'none';
        }

        function submitEditComment(commentId) {
            const editInput = document.getElementById(`comment-edit-input-${commentId}`);
            if (!editInput) return;

            const newBody = editInput.value.trim();
            if (newBody === "") {
                alert("Comment cannot be empty.");
                return;
            }

            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch(`/customer/comments/${commentId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        _method: 'PUT',
                        body: newBody
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');

                    const textSpan = document.getElementById(`comment-text-${commentId}`);
                    if (textSpan) {
                        textSpan.innerHTML = newBody.replace(/\n/g, '<br>');
                    }
                    cancelEditComment(commentId);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to update comment. Please try again.');
                });
        }

        // ==========================================
        // 6. Submit New Comment & Likes Interaction
        // ==========================================
        function focusCommentInput(postId) {
            const input = document.getElementById(`comment-input-${postId}`);
            if (input) input.focus();
        }

        function setupReply(postId, username) {
            const input = document.getElementById(`comment-input-${postId}`);
            if (input) {
                input.value = `@${username} `;
                input.focus();
            }
        }

        function submitComment(postId) {
            const input = document.getElementById(`comment-input-${postId}`);
            const submitBtn = document.getElementById(`comment-btn-${postId}`);
            if (!input || input.value.trim() === '') return;

            const commentBody = input.value;
            if (submitBtn) submitBtn.disabled = true;

            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch(`/customer/posts/${postId}/comment`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        body: commentBody
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        input.value = '';

                        const container = document.getElementById(`comments-container-${postId}`);
                        const noCommentsDiv = document.getElementById(`no-comments-${postId}`);
                        if (noCommentsDiv) noCommentsDiv.remove();

                        const avatarSrc = data.user_avatar ? `/storage/${data.user_avatar}` :
                            '/images/default-avatar.png';
                        const newCommentHtml = `
                        <div class="d-flex mb-3 align-items-start position-relative comment-row" style="font-size: 0.85rem;" id="comment-wrapper-${data.comment_id}">
                            <img src="${avatarSrc}" class="rounded-circle object-fit-cover me-2" style="width: 28px; height: 28px;">
                            <div class="w-100 pe-2">
                                <div>
                                    <span class="fw-bold text-dark me-1">${data.display_id || 'User'}</span>
                                    <span id="comment-text-${data.comment_id}">${data.body}</span>
                                    <div id="comment-edit-form-${data.comment_id}" style="display: none;" class="mt-1">
                                        <textarea id="comment-edit-input-${data.comment_id}" class="form-control form-control-sm mb-1" rows="2">${data.body}</textarea>
                                        <div class="d-flex gap-1 justify-content-end align-items-center">
                                            <button type="button" class="btn btn-link p-0 text-danger text-decoration-none fw-bold" style="font-size: 0.75rem; line-height: 1;" onclick="cancelEditComment('${data.comment_id}')">Cancel</button>
                                            <span class="text-muted mx-1" style="font-size: 0.75rem; line-height: 1;">/</span>
                                            <button type="button" class="btn btn-link p-0 text-primary text-decoration-none fw-bold" style="font-size: 0.75rem; line-height: 1;" onclick="submitEditComment('${data.comment_id}')">Save</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-1 d-flex gap-2 align-items-center" style="font-size: 0.7rem; color: #8e8e8e;">
                                    <span>Just now</span>
                                    <span class="fw-bold cursor-pointer text-primary" onclick="setupReply('${postId}', '${data.display_id || 'User'}')">Reply</span>
                                    <div class="position-relative d-inline">
                                        <span class="fw-bold cursor-pointer text-secondary px-1" onclick="toggleCommentMenu(event, '${data.comment_id}')">…</span>
                                        <div id="comment-dropdown-${data.comment_id}" class="comment-dropdown-menu shadow-sm border rounded bg-white position-absolute p-1" style="display: none; left: 0; top: 15px; z-index: 1100; min-width: 130px;">
                                            <button class="btn btn-link text-dark text-start w-100 px-2 py-1 m-0 border-0 fw-bold d-flex align-items-center justify-content-between" style="font-size: 0.75rem; text-decoration: none;" onclick="startEditComment('${data.comment_id}')">
                                                <span>Edit</span><i class="fa-regular fa-pen-to-square text-secondary"></i>
                                            </button>
                                            <button class="btn btn-link text-danger text-start w-100 px-2 py-1 m-0 border-0 fw-bold d-flex align-items-center justify-content-between" style="font-size: 0.75rem; text-decoration: none;" onclick="openCustomDeleteModal('comment', '${data.comment_id}')">
                                                <span>Delete</span><i class="fa-regular fa-trash-can text-danger"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                        if (container) {
                            container.insertAdjacentHTML('beforeend', newCommentHtml);
                            const feed = container.closest('.instagram-right-feed');
                            if (feed) feed.scrollTop = feed.scrollHeight;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to send comment. Please try again.');
                })
                .finally(() => {
                    if (submitBtn) submitBtn.disabled = false;
                });
        }

        function toggleLike(element, postId) {
            const heartIcon = element.querySelector('i');
            const likesCountSpan = document.getElementById(`likes-count-${postId}`);
            const hoverLikesCountSpan = document.getElementById(`hover-likes-count-${postId}`);

            if (!heartIcon) return;
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch(`/customer/posts/${postId}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network error');
                    return response.json();
                })
                .then(data => {
                    if (data.isLiked) {
                        heartIcon.className = 'fa-solid fa-heart text-danger';
                    } else {
                        heartIcon.className = 'fa-regular fa-heart text-dark';
                    }
                    if (likesCountSpan) likesCountSpan.innerText = data.likes_count;
                    if (hoverLikesCountSpan) hoverLikesCountSpan.innerText = data.likes_count;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        // ==========================================
        // 7. Media Live Preview Function (Enhanced)
        // ==========================================
        function previewMedia(input) {
            const previewContainer = document.getElementById('mediaPreview');
            if (!previewContainer) return;

            // Clear old previews
            previewContainer.innerHTML = '';

            if (input.files && input.files.length > 0) {
                Array.from(input.files).forEach(file => {
                    const reader = new FileReader();

                    // Create wrapper matching CSS definition
                    const wrapper = document.createElement('div');
                    wrapper.className = 'preview-item-wrapper';

                    reader.onload = function(e) {
                        if (file.type.startsWith('image/')) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            wrapper.appendChild(img);
                        } else if (file.type.startsWith('video/')) {
                            const video = document.createElement('video');
                            video.src = e.target.result;
                            video.muted = true;
                            video.playsInline = true;
                            wrapper.appendChild(video);
                        }
                    };

                    reader.readAsDataURL(file);
                    previewContainer.appendChild(wrapper);
                });
            }
        }

        // ==========================================
        // 8. Custom Confirmation Delete Modals
        // ==========================================
        let deleteTargetUrl = '';
        let deleteTargetType = '';
        let deleteTargetId = '';

        function openCustomDeleteModal(type, id) {
            deleteTargetType = type;
            deleteTargetId = id;

            if (type === 'post') {
                deleteTargetUrl = `/customer/posts/${id}`;
            } else if (type === 'comment') {
                deleteTargetUrl = `/customer/comments/${id}`;
            }

            const modal = document.getElementById('customDeleteModal');
            const confirmBtn = document.getElementById('customDeleteConfirmBtn');

            if (modal) modal.style.display = 'flex';

            if (confirmBtn) {
                confirmBtn.onclick = function() {
                    if (type === 'comment') {
                        executeDeleteComment(id);
                    } else {
                        const form = document.getElementById('globalDeleteForm');
                        if (form) {
                            form.action = deleteTargetUrl;
                            form.submit();
                        }
                    }
                };
            }
        }

        function closeCustomDeleteModal() {
            const modal = document.getElementById('customDeleteModal');
            if (modal) modal.style.display = 'none';
            deleteTargetUrl = '';
            deleteTargetType = '';
            deleteTargetId = '';
        }

        function executeDeleteComment(commentId) {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch(`/customer/comments/${commentId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        _method: 'DELETE'
                    })
                })
                .then(response => {
                    const wrapper = document.getElementById(`comment-wrapper-${commentId}`);
                    if (wrapper) {
                        wrapper.style.transition = 'opacity 0.2s ease';
                        wrapper.style.opacity = '0';
                        setTimeout(() => wrapper.remove(), 200);
                    }
                    closeCustomDeleteModal();
                })
                .catch(error => {
                    console.error('Error:', error);
                    const form = document.getElementById('globalDeleteForm');
                    if (form) {
                        form.action = `/customer/comments/${commentId}`;
                        form.submit();
                    }
                });
        }

        // ==========================================
        // 9. Document DOMReady Initialization
        // ==========================================
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('reviewForm');

            if (form) {
                const restaurantSelect = form.querySelector('select[name="restaurant_id"]');
                const ratingRadios = form.querySelectorAll('input[name="rating"]');
                const mediaInput = document.getElementById('mediaInput');

                // Listen to Restaurant Selection Changes
                if (restaurantSelect) {
                    restaurantSelect.addEventListener('change', validateReviewForm);
                }

                // Listen to HTML Radio Star Changes
                ratingRadios.forEach(radio => {
                    radio.addEventListener('change', validateReviewForm);
                });

                // Attach refined media upload preview zone behavior
                if (mediaInput) {
                    mediaInput.addEventListener('change', function() {
                        previewMedia(this);
                    });
                }
            }

            // Global click outside to close floating dropdown panels
            document.addEventListener('click', function() {
                document.querySelectorAll('.post-dropdown-menu, .comment-dropdown-menu').forEach(menu => {
                    menu.style.display = 'none';
                });
            });
        });
    </script>
@endsection
