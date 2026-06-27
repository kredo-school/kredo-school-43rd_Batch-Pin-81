{{-- 💡 delete_photo.blade.php の全体をこれで上書き --}}
<div class="modal fade" id="deletePhotoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content modal-content-custom p-3">
            <div class="modal-body text-center">
                <h5 class="fw-bold mb-3" style="color: #0A2540;">Delete this photo?</h5>
                <p class="text-muted small mb-4">Are you sure you want to remove this photo? This action cannot be undone.</p>
                
                <form id="delete-photo-form" action="#" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="target-photo-id" name="photo_id" value="">
                    
                    {{-- 💡 ボタンオブジェクトを配置。デザインをMenuと統一 --}}
                    <div class="d-flex gap-2">
                        <button type="button" class="btn w-50 btn-cancel" data-bs-dismiss="modal" style="padding: 10px;">Cancel</button>
                        <button type="submit" class="btn w-50 btn-modal-delete" style="padding: 10px;">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>