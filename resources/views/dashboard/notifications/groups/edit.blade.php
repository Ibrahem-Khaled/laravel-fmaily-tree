@extends('layouts.app')

@section('title', 'تعديل المجموعة')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-users text-primary mr-2"></i>{{ $notificationGroup->name }}
            </h1>
            <p class="text-muted mt-1">تعديل المجموعة وإدارة الأعضاء (أشخاص لديهم واتساب)</p>
        </div>
        <a href="{{ route('dashboard.notification-groups.index') }}" class="btn btn-secondary btn-sm">العودة للمجموعات</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">تعديل البيانات</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.notification-groups.update', $notificationGroup) }}" method="post">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">اسم المجموعة</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $notificationGroup->name) }}" required>
                </div>
                <div class="form-group">
                    <label for="description">الوصف (اختياري)</label>
                    <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $notificationGroup->description) }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">أعضاء المجموعة ({{ $notificationGroup->persons->count() }})</h6>
            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addPersonModal">إضافة شخص</button>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                @forelse($notificationGroup->persons as $p)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>{{ $p->full_name }}</span>
                    <form action="{{ route('dashboard.notification-groups.detach-person', [$notificationGroup, $p]) }}" method="post" class="d-inline" onsubmit="return confirm('إزالة هذا الشخص من المجموعة؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">إزالة</button>
                    </form>
                </li>
                @empty
                <li class="list-group-item text-muted">لا يوجد أعضاء. أضف أشخاصاً لديهم واتساب.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

<div class="modal fade" id="addPersonModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة شخص للمجموعة</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{ route('dashboard.notification-groups.attach-person', $notificationGroup) }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>اختر شخصاً (لديه واتساب)</label>
                        <select name="person_id" id="addPersonSelect" class="form-control no-search" required style="width:100%;"></select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">إضافة</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    var searchUrl = '{{ url()->route("dashboard.notifications.persons-with-whatsapp") }}';
    if (typeof $ !== 'undefined' && $.fn.select2) {
        var $addSelect = $('#addPersonSelect');
        if ($addSelect.data('select2')) $addSelect.select2('destroy');
        $addSelect.select2({
            placeholder: 'ابحث بالاسم...',
            dir: 'rtl',
            width: '100%',
            dropdownParent: $('#addPersonModal'),
            ajax: {
                url: searchUrl,
                dataType: 'json',
                delay: 300,
                data: function(params) {
                    return { q: params.term || '' };
                },
                processResults: function(data) {
                    var persons = (data && data.persons) ? data.persons : [];
                    return {
                        results: persons.map(function(p) {
                            return { id: p.id, text: p.full_name || ('#' + p.id) };
                        })
                    };
                },
                cache: true
            },
            minimumInputLength: 0
        });
    }
})();
</script>
@endpush
@endsection
