@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">إدارة الأماكن</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/dashboard') }}">لوحة التحكم</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">الأماكن</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts')

        {{-- بطاقة الإحصائيات --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    إجمالي الأماكن
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-map-marker-alt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    أماكن مرتبطة بأشخاص
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['with_persons'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    أماكن فارغة
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['empty'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-map fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- بطاقة قائمة الأماكن --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">قائمة الأماكن</h6>
                <div>
                    <button class="btn btn-info btn-sm" id="findSimilarBtn" title="البحث عن أماكن متشابهة">
                        <i class="fas fa-search"></i> البحث عن متشابهات
                    </button>
                    <button class="btn btn-warning btn-sm" id="mergeLocationsBtn" title="دمج أماكن مختارة">
                        <i class="fas fa-compress-arrows-alt"></i> دمج الأماكن المختارة
                    </button>
                    <a href="{{ route('locations.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> إضافة مكان جديد
                    </a>
                </div>
            </div>
            <div class="card-body">
                {{-- نموذج البحث --}}
                <form action="{{ route('locations.index') }}" method="GET" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="ابحث بالاسم أو الدولة أو المدينة..."
                            value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> بحث
                            </button>
                        </div>
                    </div>
                </form>

                {{-- جدول الأماكن --}}
                <form id="mergeForm" action="{{ route('locations.do-merge') }}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 50px;">
                                        <input type="checkbox" id="selectAll">
                                    </th>
                                    <th>الاسم</th>
                                    <th>الدولة</th>
                                    <th>المدينة</th>
                                    <th>عدد الأشخاص</th>
                                    <th>الوصف</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($locations as $location)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="source_location_ids[]" value="{{ $location->id }}" class="location-checkbox">
                                        </td>
                                        <td>
                                            <strong>{{ $location->display_name }}</strong>
                                        </td>
                                        <td>{{ $location->country ?? '-' }}</td>
                                        <td>{{ $location->city ?? '-' }}</td>
                                        <td>
                                            <span class="badge badge-info" style="font-size: 0.9rem;">
                                                <i class="fas fa-users"></i> {{ $location->persons_count ?? 0 }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ Str::limit($location->description, 50) }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('locations.show', $location) }}" class="btn btn-sm btn-circle btn-info" title="عرض">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('locations.edit', $location) }}" class="btn btn-sm btn-circle btn-primary" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-circle btn-danger" data-toggle="modal"
                                                data-target="#deleteLocationModal{{ $location->id }}" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- مودال الحذف --}}
                                    @include('dashboard.locations.modals.delete', ['location' => $location])
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">لا توجد أماكن لعرضها.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- حقل المستهدف للدمج - خارج الجدول --}}
                    <div class="card border-warning mt-4">
                        <div class="card-header bg-warning text-white">
                            <h6 class="mb-0"><i class="fas fa-compress-arrows-alt"></i> دمج الأماكن المختارة</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-0">
                                <label for="target_location_id" class="font-weight-bold">
                                    <i class="fas fa-map-marker-alt"></i> اختر المكان المستهدف للدمج:
                                </label>
                                <select name="target_location_id" id="target_location_id" class="form-control form-control-lg" required>
                                    <option value="">-- اختر المكان المستهدف --</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}">{{ $location->display_name }} ({{ $location->persons_count }} شخص)</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">
                                    سيتم نقل جميع الأشخاص من الأماكن المختارة إلى المكان المستهدف، ثم حذف الأماكن المختارة.
                                </small>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- الترقيم --}}
                <div class="d-flex justify-content-center">
                    {{ $locations->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- مودال البحث عن أماكن متشابهة --}}
    @include('dashboard.locations.modals.find-similar')
    @include('dashboard.locations.modals.merge-confirm')
@endsection

@push('scripts')
    <script>
        // تحديد/إلغاء تحديد الكل
        $('#selectAll').on('change', function() {
            $('.location-checkbox').prop('checked', this.checked);
            updateMergeButton();
        });

        $('.location-checkbox').on('change', function() {
            updateMergeButton();
            // تحديث حالة checkbox "تحديد الكل"
            $('#selectAll').prop('checked', $('.location-checkbox:checked').length === $('.location-checkbox').length);
        });

        function updateMergeButton() {
            const checkedCount = $('.location-checkbox:checked').length;
            if (checkedCount > 0) {
                $('#mergeLocationsBtn').prop('disabled', false).html(`<i class="fas fa-compress-arrows-alt"></i> دمج (${checkedCount}) مكان`);
            } else {
                $('#mergeLocationsBtn').prop('disabled', true).html('<i class="fas fa-compress-arrows-alt"></i> دمج الأماكن المختارة');
            }
        }

        // زر دمج الأماكن
        $('#mergeLocationsBtn').on('click', function() {
            const checkedCount = $('.location-checkbox:checked').length;
            if (checkedCount < 2) {
                alert('يجب اختيار مكانين على الأقل للدمج.');
                return;
            }

            const targetId = $('#target_location_id').val();
            if (!targetId) {
                alert('يجب اختيار المكان المستهدف للدمج.');
                return;
            }

            // التحقق من عدم اختيار المكان المستهدف نفسه
            if ($('.location-checkbox[value="' + targetId + '"]').is(':checked')) {
                alert('لا يمكن اختيار المكان المستهدف نفسه في القائمة المختارة.');
                return;
            }

            $('#mergeConfirmModal').modal('show');
        });

        // تأكيد الدمج
        $('#confirmMergeBtn').on('click', function(e) {
            e.preventDefault();
            // التأكد من أن الـ form يستخدم POST
            const form = $('#mergeForm');
            form.attr('method', 'POST');
            // إزالة أي حقول _method قد تكون موجودة
            form.find('input[name="_method"]').remove();
            form.submit();
        });

        // البحث عن أماكن متشابهة
        $('#findSimilarBtn').on('click', function() {
            $('#findSimilarModal').modal('show');
        });

        $('#similarSearchInput').on('keyup', function() {
            const name = $(this).val();
            if (name.length < 2) {
                $('#similarResults').html('<p class="text-muted">اكتب اسم المكان للبحث...</p>');
                return;
            }

            $.ajax({
                url: '{{ route("locations.find-similar") }}',
                method: 'GET',
                data: { name: name },
                success: function(response) {
                    if (response.similar.length === 0) {
                        $('#similarResults').html('<p class="text-muted">لم يتم العثور على أماكن مشابهة.</p>');
                    } else {
                        let html = '<div class="list-group">';
                        response.similar.forEach(function(item) {
                            html += `
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>${item.name}</strong>
                                            <small class="text-muted d-block">${item.persons_count} شخص</small>
                                        </div>
                                        <span class="badge badge-info">${item.similarity}%</span>
                                    </div>
                                </div>
                            `;
                        });
                        html += '</div>';
                        $('#similarResults').html(html);
                    }
                }
            });
        });
    </script>
@endpush

