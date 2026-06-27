{{-- resources/views/restaurants/photos/modals/upload_photo.blade.php --}}
<div class="modal fade" id="uploadPhotoModal" tabindex="-1" aria-labelledby="uploadPhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
        <div class="modal-content modal-content-custom p-3">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold" style="color: #0A2540;">Upload New Photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                {{-- 💡 重要：画像を送信するため enctype="multipart/form-data" オブジェクトを必ず指定します --}}
                <form action="{{ route('restaurant.photos.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- 📸 画像ファイル選択変数 --}}
                    <div class="mb-3">
                        <label for="photo_file" class="form-label small fw-semibold text-muted">Select Image</label>
                        <input type="file" class="form-control" id="photo_file" name="photo_file" accept="image/*"
                            required>
                    </div>

                    {{-- 📁 カテゴリー選択変数（DBのenum型と一致させます） --}}
                    <div class="mb-4">
                        <label for="photo_category" class="form-label small fw-semibold text-muted">Category</label>
                        <select class="form-select" id="photo_category" name="photo_category" required>
                            <option value="" disabled selected>-- Choose a category --</option>
                            <option value="food">Food</option>
                            <option value="drink">Drink</option>
                            <option value="interior">Interior</option>
                            <option value="exterior">Exterior</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn w-50 btn-cancel" data-bs-dismiss="modal"
                            style="padding: 10px;">Cancel</button>
                        <button type="submit" class="btn w-50 btn-photo-upload justify-content-center"
                            style="padding: 10px;">Upload</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
