{{-- مودال تأكيد الدمج --}}
<div class="modal fade" id="mergeConfirmModal" tabindex="-1" role="dialog" aria-labelledby="mergeConfirmLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mergeConfirmLabel">تأكيد دمج الأماكن</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من دمج الأماكن المختارة في المكان المستهدف؟</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> سيتم نقل جميع الأشخاص من الأماكن المختارة إلى المكان المستهدف، ثم حذف الأماكن المختارة.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-warning" id="confirmMergeBtn">تأكيد الدمج</button>
            </div>
        </div>
    </div>
</div>

