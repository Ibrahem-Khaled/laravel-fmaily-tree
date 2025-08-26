<div class="modal fade" id="showCategoryModal{{ $category->id }}" tabindex="-1" role="dialog"
    aria-labelledby="showCategoryModalLabel{{ $category->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showCategoryModalLabel{{ $category->id }}">تفاصيل القسم</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <img src="{{ $category->image ? asset('storage/' . $category->image) : asset('img/default-image.png') }}"
                        class="img-fluid rounded" alt="{{ $category->name }}" style="max-height: 200px;">
                </div>
                <table class="table table-bordered">
                    <tr>
                        <th>الاسم</th>
                        <td>{{ $category->name }}</td>
                    </tr>
                    <tr>
                        <th>القسم الرئيسي</th>
                        <td>
                            @if ($category->parent)
                                {{ $category->parent->name }}
                            @else
                                قسم رئيسي
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>الوصف</th>
                        <td>{{ $category->description ?? 'لا يوجد وصف' }}</td>
                    </tr>
                    <tr>
                        <th>تاريخ الإنشاء</th>
                        <td>{{ $category->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
