<div class="modal fade" id="deleteLocationModal{{ $location->id }}" tabindex="-1" role="dialog"
    aria-labelledby="deleteLocationLabel{{ $location->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteLocationLabel{{ $location->id }}">حذف مكان</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('locations.destroy', $location) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>هل أنت متأكد من حذف المكان <strong>{{ $location->display_name }}</strong>؟</p>
                    @if($location->persons_count > 0)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> يوجد {{ $location->persons_count }} شخص مرتبط بهذا المكان. لا يمكن حذفه.
                        </div>
                    @else
                        <ul class="small text-muted mb-0">
                            <li>لن يتم الحذف إذا كان مرتبطاً بأشخاص.</li>
                        </ul>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-danger" {{ $location->persons_count > 0 ? 'disabled' : '' }}>حذف</button>
                </div>
            </form>
        </div>
    </div>
</div>

