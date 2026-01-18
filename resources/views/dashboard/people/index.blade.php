@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">إدارة الأشخاص</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">لوحة التحكم</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">الأشخاص</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts')

        {{-- إحصائيات الأشخاص --}}
        <div class="row mb-4">
            {{-- البطاقات الإحصائية --}}

            <x-stats-card icon="fas fa-users" title="إجمالي الأشخاص" :value="$stats['total']" color="primary" />

            <x-stats-card icon="fas fa-male" title="عدد الذكور" :value="$stats['male']" color="info" />

            <x-stats-card icon="fas fa-female" title="عدد الإناث" :value="$stats['female']" color="pink" />

            <x-stats-card icon="fas fa-heart" title="عدد الأحياء" :value="$stats['living']" color="success" />

            <x-stats-card icon="fas fa-camera" title="أشخاص لديهم صور" :value="$stats['with_photos']" color="warning" />

            <a href="{{ route('family-tree') }}" class="btn btn-dark btn-block h-100 py-3">
                <i class="fas fa-tree"></i> عرض تواصل العائلة
            </a>
        </div>

        {{-- بطاقة قائمة الأشخاص --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">قائمة الأشخاص</h6>
                <div>
                    <a href="{{ route('people.export.excel', request()->query()) }}" class="btn btn-success mr-2">
                        <i class="fas fa-file-excel"></i> تصدير Excel
                    </a>
                    <a href="{{ route('people.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> إضافة شخص
                    </a>
                </div>
            </div>
            <div class="card-body">
                {{-- ✅ تعديل تبويبات الجنس: إضافة قيمة البحث الحالية إلى الروابط --}}
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <a class="nav-link {{ !request('gender') ? 'active' : '' }}"
                            href="{{ route('people.index', ['search' => request('search')]) }}">الكل</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('gender') === 'male' ? 'active' : '' }}"
                            href="{{ route('people.index', ['gender' => 'male', 'search' => request('search')]) }}">الذكور</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('gender') === 'female' ? 'active' : '' }}"
                            href="{{ route('people.index', ['gender' => 'female', 'search' => request('search')]) }}">الإناث</a>
                    </li>
                </ul>

                {{-- ✅ تعديل نموذج البحث: إضافة حقل خفي للجنس والحالة العائلية للحفاظ على الفلتر --}}
                <form action="{{ route('people.index') }}" method="GET" class="mb-4">
                    @if (request('gender'))
                        <input type="hidden" name="gender" value="{{ request('gender') }}">
                    @endif
                    @if (request('family_status'))
                        <input type="hidden" name="family_status" value="{{ request('family_status') }}">
                    @endif
                    <div class="input-group">
                        <input type="text" name="search" class="form-control"
                            placeholder="ابحث بالاسم الأول أو الأخير..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> بحث
                            </button>
                        </div>
                    </div>
                </form>

                {{-- توجال داخل/خارج العائلة --}}
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card border-primary">
                            <div class="card-body">
                                <h6 class="card-title text-primary mb-3">
                                    <i class="fas fa-users-cog"></i> تصنيف الأشخاص حسب العائلة
                                </h6>
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <div class="btn-group w-100" role="group">
                                            <input type="radio" class="btn-check" name="family_status" id="all"
                                                   value="" {{ !request('family_status') ? 'checked' : '' }}>
                                            <label class="btn btn-outline-secondary" for="all">
                                                <i class="fas fa-list"></i> الكل
                                            </label>

                                            <input type="radio" class="btn-check" name="family_status" id="family"
                                                   value="1" {{ request('family_status') === '1' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-success" for="family">
                                                <i class="fas fa-home"></i> داخل العائلة
                                            </label>

                                            <input type="radio" class="btn-check" name="family_status" id="outside"
                                                   value="0" {{ request('family_status') === '0' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-warning" for="outside">
                                                <i class="fas fa-external-link-alt"></i> خارج العائلة
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <button type="button" class="btn btn-info" onclick="filterByFamilyStatus()">
                                            <i class="fas fa-filter"></i> تطبيق التصفية
                                        </button>
                                        <button type="button" class="btn btn-secondary ml-1" onclick="clearFamilyStatus()">
                                            <i class="fas fa-times"></i> مسح
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- جدول الأشخاص --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                {{-- ✅ الخطوة 3: إضافة عمود للترتيب --}}
                                <th style="width: 50px;">ترتيب</th>
                                <th>الاسم</th>
                                <th>الام</th>
                                <th>الجنس</th>
                                <th>الحالة العائلية</th>
                                <th>العمر</th>
                                <th>فترة الحياة</th>
                                <th>المهنة</th>
                                <th>مكان الميلاد</th>
                                <th>مكان الإقامة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        {{-- ✅ الخطوة 2: إضافة `id` و `data-url` لجسم الجدول --}}
                        <tbody id="people-sortable" data-url="{{ route('people.reorder') }}">
                            @forelse($people as $person)
                                {{-- ✅ إضافة `data-id` لكل صف --}}
                                <tr data-id="{{ $person->id }}">
                                    {{-- ✅ إضافة أيقونة السحب --}}
                                    <td class="text-center" style="cursor: move;">
                                        <i class="fas fa-arrows-alt"></i>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $person->avatar }}" alt="{{ $person->full_name }}"
                                                class="rounded-circle mr-2" width="40" height="40">
                                            <a href="{{ route('people.show', $person->id) }}">
                                                {{ $person->full_name }}
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="#">
                                            {{ $person->mother ? $person->mother->full_name : 'غير معروف' }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $person->gender == 'male' ? 'primary' : 'pink' }}">
                                            {{ $person->gender == 'male' ? 'ذكر' : 'أنثى' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if(!$person->from_outside_the_family)
                                            <span class="badge badge-success">
                                                <i class="fas fa-home"></i> داخل العائلة
                                            </span>
                                        @else
                                            <span class="badge badge-warning">
                                                <i class="fas fa-external-link-alt"></i> خارج العائلة
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $person->age ?? 'غير معروف' }}</td>
                                    <td>{{ $person->life_span ?? 'غير معروف' }}</td>
                                    <td>{{ $person->occupation ?? '-' }}</td>
                                    <td>{{ $person->birth_place ?? '-' }}</td>
                                    <td>{{ $person->location_display ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('people.edit', $person->id) }}" class="btn btn-sm btn-circle btn-primary"
                                            title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-circle btn-danger" data-toggle="modal"
                                            data-target="#deletePersonModal{{ $person->id }}" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @include('dashboard.people.modals.delete', ['person' => $person])
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">لا يوجد أشخاص مطابقين لنتيجة البحث</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- الترقيم --}}
                <div class="d-flex justify-content-center">
                    {{-- لم نعد بحاجة إلى appends() هنا لأننا استخدمنا withQueryString() في المتحكم --}}
                    {{ $people->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- ✅ الخطوة 1: تضمين مكتبة SortableJS --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    {{-- ✅ الخطوة 4: كود JavaScript الخاص بـ SortableJS --}}
    <script>
        const el = document.getElementById('people-sortable');

        // التحقق من وجود العنصر قبل تهيئة SortableJS
        if (el) {
            const sortable = Sortable.create(el, {
                animation: 150, // سرعة الحركة بالمللي ثانية
                handle: '.fa-arrows-alt', // تحديد الأيقونة كمقبض للسحب
                onEnd: function(evt) {
                    // الحصول على الترتيب الجديد للصفوف
                    const order = Array.from(evt.to.children).map((row, index) => {
                        return {
                            id: row.dataset.id,
                            order: index + 1
                        };
                    });

                    // إرسال الترتيب الجديد إلى الخادم
                    updateOrder(order);
                }
            });
        }

        function updateOrder(order) {
            const url = document.getElementById('people-sortable').dataset.url;

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // مهم جدا للأمان في Laravel
                    },
                    body: JSON.stringify({
                        order: order
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                    // يمكنك إضافة رسالة نجاح هنا إذا أردت
                })
                .catch((error) => {
                    console.error('Error:', error);
                    // يمكنك إضافة رسالة خطأ هنا
                });
        }

        // دوال التعامل مع تصنيف العائلة
        function filterByFamilyStatus() {
            const selectedStatus = document.querySelector('input[name="family_status"]:checked');
            const url = new URL(window.location);

            if (selectedStatus && selectedStatus.value) {
                url.searchParams.set('family_status', selectedStatus.value);
            } else {
                url.searchParams.delete('family_status');
            }

            // الحفاظ على المعاملات الأخرى
            if ('{{ request("gender") }}') {
                url.searchParams.set('gender', '{{ request("gender") }}');
            }
            if ('{{ request("search") }}') {
                url.searchParams.set('search', '{{ request("search") }}');
            }

            window.location.href = url.toString();
        }

        function clearFamilyStatus() {
            const url = new URL(window.location);
            url.searchParams.delete('family_status');

            // الحفاظ على المعاملات الأخرى
            if ('{{ request("gender") }}') {
                url.searchParams.set('gender', '{{ request("gender") }}');
            }
            if ('{{ request("search") }}') {
                url.searchParams.set('search', '{{ request("search") }}');
            }

            window.location.href = url.toString();
        }
    </script>

    {{-- إضافة أنماط إضافية للتوجال --}}
    <style>
        .btn-check:checked + .btn-outline-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }

        .btn-check:checked + .btn-outline-success {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
        }

        .btn-check:checked + .btn-outline-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }

        .card.border-primary {
            border-width: 2px !important;
        }

        .btn-check + .btn {
            transition: all 0.3s ease;
        }

        .btn-check + .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
    </style>
@endpush
