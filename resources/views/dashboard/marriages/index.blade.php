@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">إدارة الزواج</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">لوحة التحكم</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">سجلات الزواج</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts')

        {{-- إحصائيات الزواج --}}
        <div class="row mb-4">
            {{-- إجمالي حالات الزواج --}}
            <div class="col-xl-3 col-md-6 mb-4">
                <x-stats-card icon="fas fa-ring" title="إجمالي حالات الزواج" :value="$totalMarriages" color="primary" />
            </div>
            {{-- حالات الزواج النشطة --}}
            <div class="col-xl-3 col-md-6 mb-4">
                <x-stats-card icon="fas fa-heart" title="زواج نشط" :value="$activeMarriages" color="success" />
            </div>
            {{-- حالات الطلاق --}}
            <div class="col-xl-3 col-md-6 mb-4">
                <x-stats-card icon="fas fa-heart-broken" title="حالات الانفصال" :value="$divorcedMarriages" color="danger" />
            </div>
            {{-- حالات بدون تاريخ طلاق --}}
            <div class="col-xl-3 col-md-6 mb-4">
                <x-stats-card icon="fas fa-question-circle" title="غير محددة الحالة" :value="$unknownStatusMarriages" color="warning" />
            </div>
        </div>

        {{-- بطاقة قائمة الزواج --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">سجلات الزواج</h6>
                <button class="btn btn-primary" data-toggle="modal" data-target="#createMarriageModal">
                    <i class="fas fa-plus"></i> إضافة سجل زواج
                </button>
                <button class="btn btn-primary ml-2" data-toggle="modal" data-target="#addPersonsOutsideTheFamilyTreeModal">
                    <i class="fas fa-plus"></i> إضافة شخص خارج تواصل العائلة
                </button>
            </div>
            <div class="card-body">
                {{-- تبويب الحالات --}}
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'all' ? 'active' : '' }}"
                            href="{{ route('marriages.index') }}">الكل</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'active' ? 'active' : '' }}"
                            href="{{ route('marriages.index', ['status' => 'active']) }}">زواج نشط</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'divorced' ? 'active' : '' }}"
                            href="{{ route('marriages.index', ['status' => 'divorced']) }}">منفصلون</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'unknown' ? 'active' : '' }}"
                            href="{{ route('marriages.index', ['status' => 'unknown']) }}">غير محدد</a>
                    </li>
                </ul>

                {{-- نموذج البحث --}}
                <form action="{{ route('marriages.index') }}" method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="husband_search">بحث بالزوج:</label>
                                <select id="husband_search" name="husband_id" class="form-control select2">
                                    <option value="">اختر الزوج</option>
                                    @foreach ($persons as $person)
                                        <option value="{{ $person->id }}"
                                            {{ request('husband_id') == $person->id ? 'selected' : '' }}>
                                            {{ $person->full_name }} ({{ $person->birth_date }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="wife_search">بحث بالزوجة:</label>
                                <select id="wife_search" name="wife_id" class="form-control select2">
                                    <option value="">اختر الزوجة</option>
                                    @foreach ($persons as $person)
                                        <option value="{{ $person->id }}"
                                            {{ request('wife_id') == $person->id ? 'selected' : '' }}>
                                            {{ $person->full_name }} ({{ $person->birth_date }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date_range">نطاق التاريخ:</label>
                                <input type="text" id="date_range" name="date_range" class="form-control"
                                    value="{{ request('date_range') }}" placeholder="حدد نطاق التاريخ">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> بحث
                            </button>
                            <a href="{{ route('marriages.index') }}" class="btn btn-secondary">
                                <i class="fas fa-undo"></i> إعادة تعيين
                            </a>
                        </div>
                    </div>
                </form>

                {{-- جدول سجلات الزواج --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="marriagesTable">
                        <thead class="thead-light">
                            <tr>
                                <th>الزوج</th>
                                <th>الزوجة</th>
                                <th>تاريخ الزواج</th>
                                <th>تاريخ الانفصال</th>
                                <th>الحالة</th>
                                <th>المدة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($marriages as $marriage)
                                <tr>
                                    <td>
                                        <a href="#">
                                            {{ $marriage->husband->full_name }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#">
                                            {{ $marriage->wife->full_name }}
                                        </a>
                                    </td>
                                    <td>{{ $marriage->married_at ? $marriage->married_at->format('Y-m-d') : 'غير معروف' }}
                                    </td>
                                    <td>{{ $marriage->divorced_at ? $marriage->divorced_at->format('Y-m-d') : ($marriage->is_divorced ? '✅' : '-') }}</td>
                                    <td>
                                        @if ($marriage->isDivorced())
                                            <span class="badge badge-danger">{{ $marriage->status_text }}</span>
                                        @elseif($marriage->married_at)
                                            <span class="badge badge-success">{{ $marriage->status_text }}</span>
                                        @else
                                            <span class="badge badge-warning">{{ $marriage->status_text }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($marriage->married_at)
                                            @if ($marriage->isDivorced())
                                                {{ $marriage->married_at->diffInYears($marriage->divorced_at ?? now()) }} سنة
                                            @else
                                                {{ $marriage->married_at->diffInYears(now()) }} سنة
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        {{-- زر عرض --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-info" data-toggle="modal"
                                            data-target="#showMarriageModal{{ $marriage->id }}" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        {{-- زر تعديل --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-primary"
                                            data-toggle="modal" data-target="#editMarriageModal{{ $marriage->id }}"
                                            title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        {{-- زر حذف --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-danger"
                                            data-toggle="modal" data-target="#deleteMarriageModal{{ $marriage->id }}"
                                            title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                        {{-- تضمين المودالات لكل سجل زواج --}}
                                        @include('dashboard.marriages.modals.show', ['marriage' => $marriage])
                                        @include('dashboard.marriages.modals.edit', ['marriage' => $marriage])
                                        @include('dashboard.marriages.modals.delete', ['marriage' => $marriage])
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">لا توجد سجلات زواج</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- الترقيم --}}
                <div class="d-flex justify-content-center">
                    {{ $marriages->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- مودال إضافة سجل زواج (ثابت) --}}
    @include('dashboard.marriages.modals.create')
    @include('dashboard.people.modals.add-outside-the-family')

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // تفعيل select2

            // تفعيل date range picker
            $('#date_range').daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD',
                    applyLabel: 'تطبيق',
                    cancelLabel: 'إلغاء',
                    fromLabel: 'من',
                    toLabel: 'إلى',
                    customRangeLabel: 'مخصص',
                    daysOfWeek: ['ح', 'ن', 'ث', 'ر', 'خ', 'ج', 'س'],
                    monthNames: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس',
                        'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'
                    ],
                    firstDay: 6
                },
                opens: 'right',
                autoUpdateInput: false
            });

            $('#date_range').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                    'YYYY-MM-DD'));
            });

            $('#date_range').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            // تفعيل DataTable
            $('#marriagesTable').DataTable({
                paging: false,
                searching: false,
                info: false,
                ordering: true,
                language: {
                    url: "{{ asset('js/arabic.json') }}"
                }
            });

            // التحقق من صحة النموذج قبل الإرسال
            $('#createMarriageForm, .edit-marriage-form').on('submit', function(e) {
                const husbandId = $(this).find('select[name="husband_id"]').val();
                const wifeId = $(this).find('select[name="wife_id"]').val();

                if (husbandId === wifeId) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: 'لا يمكن أن يكون الزوج والزوجة نفس الشخص!',
                    });
                }
            });
        });
    </script>
@endpush
