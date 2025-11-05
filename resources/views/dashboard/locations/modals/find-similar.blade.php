{{-- مودال البحث عن أماكن متشابهة --}}
<div class="modal fade" id="findSimilarModal" tabindex="-1" role="dialog" aria-labelledby="findSimilarLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="findSimilarLabel">البحث عن أماكن متشابهة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="similarSearchInput">اكتب اسم المكان:</label>
                    <input type="text" class="form-control" id="similarSearchInput" placeholder="مثال: جدة">
                </div>
                <div id="similarResults" class="mt-3">
                    <p class="text-muted">اكتب اسم المكان للبحث...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

