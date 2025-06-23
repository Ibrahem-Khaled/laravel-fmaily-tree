@foreach ($people as $person)
    <div class="modal fade" id="deletePersonModal{{ $person->id }}" tabindex="-1" role="dialog"
        aria-labelledby="deletePersonModalLabel{{ $person->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deletePersonModalLabel{{ $person->id }}">تأكيد الحذف</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('people.destroy', $person->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <div class="text-center">
                            <i class="fas fa-exclamation-triangle fa-4x text-danger mb-3"></i>
                            <h4>هل أنت متأكد من حذف هذا الشخص؟</h4>
                            <p class="lead">{{ $person->full_name }}</p>

                            @if ($person->children()->count() > 0)
                                <div class="alert alert-warning">
                                    <strong>تحذير!</strong> هذا الشخص لديه {{ $person->children()->count() }} أبناء.
                                    سيتم حذفهم أيضاً.
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-danger">نعم، احذف</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
