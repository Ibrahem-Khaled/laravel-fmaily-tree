<div class="modal fade" id="showPadgeModal{{ $padge->id }}" tabindex="-1" role="dialog"
    aria-labelledby="showPadgeModalLabel{{ $padge->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showPadgeModalLabel{{ $padge->id }}">تفاصيل الشارة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <img src="{{ $padge->image ? asset('storage/' . $padge->image) : asset('img/default-badge.png') }}"
                        alt="{{ $padge->name }}" class="rounded-circle" width="120" height="120"
                        style="object-fit: cover; border: 4px solid {{ $padge->color ?? '#6c757d' }};">
                </div>
                <table class="table table-bordered">
                    <tr>
                        <th>الاسم</th>
                        <td><span class="badge"
                                style="background-color: {{ $padge->color ?? '#6c757d' }}; color: white; padding: 5px 10px;">{{ $padge->name }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>الوصف</th>
                        <td>{{ $padge->description ?? 'لا يوجد وصف' }}</td>
                    </tr>
                    <tr>
                        <th>الحالة</th>
                        <td>
                            <span class="badge badge-{{ $padge->is_active ? 'success' : 'danger' }}">
                                {{ $padge->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>ترتيب العرض</th>
                        <td>{{ $padge->sort_order }}</td>
                    </tr>
                    <tr>
                        <th>تاريخ الإنشاء</th>
                        <td>{{ $padge->created_at->format('Y-m-d') }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
