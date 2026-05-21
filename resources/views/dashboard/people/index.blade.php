@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل باستخدام المكون المشترك --}}
        <x-dashboard-page-header title="إدارة الأشخاص" description="عرض وتصفية وتعديل شجرة العائلة وسجل الأقارب">
            <x-slot name="actions">
                <a href="{{ route('family-tree') }}" class="btn btn-dark shadow-sm">
                    <i class="fas fa-tree mr-1"></i> عرض شجرة العائلة
                </a>
                <a href="{{ route('people.export.excel', request()->query()) }}" class="btn btn-success shadow-sm">
                    <i class="fas fa-file-excel mr-1"></i> تصدير Excel
                </a>
                <a href="{{ route('people.create') }}" class="btn btn-primary shadow-sm">
                    <i class="fas fa-plus mr-1"></i> إضافة شخص
                </a>
            </x-slot>
        </x-dashboard-page-header>

        @include('components.alerts')

        {{-- إحصائيات الأشخاص --}}
        <div class="row mb-4">
            <x-stats-card icon="fas fa-users" title="إجمالي الأشخاص" :value="$stats['total']" color="primary" />
            <x-stats-card icon="fas fa-male" title="عدد الذكور" :value="$stats['male']" color="info" />
            <x-stats-card icon="fas fa-female" title="عدد الإناث" :value="$stats['female']" color="pink" />
            <x-stats-card icon="fas fa-heart" title="عدد الأحياء" :value="$stats['living']" color="success" />
            <x-stats-card icon="fas fa-camera" title="أشخاص لديهم صور" :value="$stats['with_photos']" color="warning" />
        </div>

        {{-- بطاقة قائمة الأشخاص الفخمة --}}
        <x-dashboard-card title="قائمة الأشخاص" icon="fe-users">
            {{-- تبويبات الجنس المنسقة --}}
            <ul class="nav nav-pills mb-4 bg-light p-1 rounded-lg d-inline-flex" style="background: rgba(255,255,255,0.03) !important;">
                <li class="nav-item">
                    <a class="nav-link rounded-lg px-4 py-2 {{ !request('gender') ? 'active bg-primary text-white font-weight-bold shadow-sm' : 'text-muted' }}"
                        href="{{ route('people.index', ['search' => request('search')]) }}">الكل</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link rounded-lg px-4 py-2 {{ request('gender') === 'male' ? 'active bg-primary text-white font-weight-bold shadow-sm' : 'text-muted' }}"
                        href="{{ route('people.index', ['gender' => 'male', 'search' => request('search')]) }}">الذكور</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link rounded-lg px-4 py-2 {{ request('gender') === 'female' ? 'active bg-primary text-white font-weight-bold shadow-sm' : 'text-muted' }}"
                        href="{{ route('people.index', ['gender' => 'female', 'search' => request('search')]) }}">الإناث</a>
                </li>
            </ul>

            {{-- نموذج البحث الأنيق --}}
            <form action="{{ route('people.index') }}" method="GET" class="mb-4">
                @if (request('gender'))
                    <input type="hidden" name="gender" value="{{ request('gender') }}">
                @endif
                @if (request('family_status'))
                    <input type="hidden" name="family_status" value="{{ request('family_status') }}">
                @endif
                <div class="input-group shadow-sm" style="border-radius: 12px; overflow: hidden;">
                    <input type="text" name="search" class="form-control border-0 p-4"
                        style="height: 50px; background: rgba(255,255,255,0.04) !important; color: #fff;"
                        placeholder="ابحث بالاسم الأول أو الأخير أو اللقب..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary px-4" type="submit">
                            <i class="fas fa-search"></i> بحث
                        </button>
                    </div>
                </div>
            </form>

            {{-- ويدجت تصفية داخل/خارج العائلة --}}
            <div class="mb-4 p-4 rounded-lg shadow-sm" style="background: rgba(255,255,255,0.01); border: 1px solid rgba(255,255,255,0.04); border-radius: 16px;">
                <h6 class="text-primary mb-3 font-weight-bold">
                    <i class="fas fa-users-cog mr-1"></i> تصنيف الأشخاص حسب العائلة
                </h6>
                <div class="row align-items-center">
                    <div class="col-md-8 mb-2 mb-md-0">
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="family_status" id="all"
                                   value="" {{ !request('family_status') ? 'checked' : '' }}>
                            <label class="btn btn-outline-secondary py-2" for="all">
                                <i class="fas fa-list"></i> الكل
                            </label>

                            <input type="radio" class="btn-check" name="family_status" id="family"
                                   value="1" {{ request('family_status') === '1' ? 'checked' : '' }}>
                            <label class="btn btn-outline-success py-2" for="family">
                                <i class="fas fa-home"></i> داخل العائلة
                            </label>

                            <input type="radio" class="btn-check" name="family_status" id="outside"
                                   value="0" {{ request('family_status') === '0' ? 'checked' : '' }}>
                            <label class="btn btn-outline-warning py-2" for="outside">
                                <i class="fas fa-external-link-alt"></i> خارج العائلة
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4 text-right">
                        <button type="button" class="btn btn-info px-4 shadow-sm" onclick="filterByFamilyStatus()">
                            <i class="fas fa-filter"></i> تطبيق التصفية
                        </button>
                        <button type="button" class="btn btn-secondary px-3 ml-1 shadow-sm" onclick="clearFamilyStatus()">
                            <i class="fas fa-times"></i> مسح
                        </button>
                    </div>
                </div>
            </div>

            {{-- جدول الأشخاص الفخم --}}
            <div class="table-responsive" style="border-radius: 12px; overflow: hidden; border: 1px solid rgba(255,255,255,0.05);">
                <table class="table table-hover align-middle mb-0" style="background: rgba(255,255,255,0.01);">
                    <thead style="background: rgba(255,255,255,0.03);">
                        <tr>
                            <th class="text-white border-0 py-3" style="width: 60px;">ترتيب</th>
                            <th class="text-white border-0 py-3">الاسم</th>
                            <th class="text-white border-0 py-3">الأم</th>
                            <th class="text-white border-0 py-3">الجنس</th>
                            <th class="text-white border-0 py-3">الحالة العائلية</th>
                            <th class="text-white border-0 py-3">العمر</th>
                            <th class="text-white border-0 py-3">فترة الحياة</th>
                            <th class="text-white border-0 py-3">المهنة</th>
                            <th class="text-white border-0 py-3">مكان الميلاد</th>
                            <th class="text-white border-0 py-3">مكان الإقامة</th>
                            <th class="text-white border-0 py-3 text-center" style="width: 120px;">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="people-sortable" data-url="{{ route('people.reorder') }}">
                        @forelse($people as $person)
                            <tr data-id="{{ $person->id }}" class="align-middle" style="transition: background-color 0.2s ease;">
                                <td class="text-center border-bottom-0 py-3" style="cursor: move; color: rgba(255,255,255,0.3); background: rgba(0,0,0,0.05);">
                                    <i class="fas fa-arrows-alt"></i>
                                </td>
                                <td class="border-bottom-0 py-3">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $person->avatar }}" alt="{{ $person->full_name }}"
                                            class="rounded-circle mr-3 shadow-sm border border-light" width="42" height="42" style="object-fit: cover;">
                                        <div>
                                            <a href="{{ route('people.show', $person->id) }}" class="font-weight-bold text-white text-decoration-none hover-primary">
                                                {{ $person->full_name }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="border-bottom-0 py-3 text-muted">
                                    @if($person->mother)
                                        <a href="{{ route('people.show', $person->mother_id) }}" class="text-muted hover-underline">
                                            {{ $person->mother->full_name }}
                                        </a>
                                    @else
                                        غير معروف
                                    @endif
                                </td>
                                <td class="border-bottom-0 py-3">
                                    <span class="badge badge-pill badge-{{ $person->gender == 'male' ? 'primary' : 'pink' }} px-3 py-2">
                                        {{ $person->gender == 'male' ? 'ذكر' : 'أنثى' }}
                                    </span>
                                </td>
                                <td class="border-bottom-0 py-3">
                                    @if(!$person->from_outside_the_family)
                                        <span class="badge badge-pill px-3 py-2" style="background: rgba(40,167,69,0.15); color: #28a745;">
                                            <i class="fas fa-home mr-1"></i> داخل العائلة
                                        </span>
                                    @else
                                        <span class="badge badge-pill px-3 py-2" style="background: rgba(255,193,7,0.15); color: #ffc107;">
                                            <i class="fas fa-external-link-alt mr-1"></i> خارج العائلة
                                        </span>
                                    @endif
                                </td>
                                <td class="border-bottom-0 py-3 text-white font-weight-bold">{{ $person->age ?? 'غير معروف' }}</td>
                                <td class="border-bottom-0 py-3 text-muted">{{ $person->life_span ?? 'غير معروف' }}</td>
                                <td class="border-bottom-0 py-3 text-muted">{{ $person->occupation ?? '-' }}</td>
                                <td class="border-bottom-0 py-3 text-muted">{{ $person->birth_place ?? '-' }}</td>
                                <td class="border-bottom-0 py-3 text-muted">{{ $person->location_display ?? '-' }}</td>
                                <td class="border-bottom-0 py-3 text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('people.edit', $person->id) }}" class="btn btn-sm btn-circle btn-primary shadow-sm"
                                            title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-circle btn-danger shadow-sm" data-toggle="modal"
                                            data-target="#deletePersonModal{{ $person->id }}" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    @include('dashboard.people.modals.delete', ['person' => $person])
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-5 text-muted">
                                    <i class="fas fa-search fa-3x mb-3" style="opacity: 0.2;"></i>
                                    <p class="mb-0 font-weight-bold">لا يوجد أشخاص مطابقين لنتيجة البحث</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- الترقيم المنسق --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $people->links() }}
            </div>
        </x-dashboard-card>
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
