<div class="modal fade" id="showImageModal{{ $image->id }}" tabindex="-1" role="dialog"
    aria-labelledby="showImageModalLabel{{ $image->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showImageModalLabel{{ $image->id }}">
                    {{ $image->name ?? 'تفاصيل الصورة' }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img src="{{ asset('storage/' . $image->path) }}" class="img-fluid rounded mb-3"
                    alt="{{ $image->name }}">
                <p><strong>القسم:</strong> {{ $image->category->name ?? 'غير مصنف' }}</p>
                <p><strong>تاريخ الرفع:</strong> {{ $image->created_at->diffForHumans() }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
