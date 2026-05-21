@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل باستخدام المكون المشترك --}}
        <x-dashboard.page-header title="إدارة الأشخاص" description="عرض وتصفية وتعديل شجرة العائلة وسجل الأقارب">
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
        </x-dashboard.page-header>

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
        <x-dashboard.card title="قائمة الأشخاص" icon="fe-users">
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

            {{-- لوحة التصفية والبحث المتقدمة الفخمة --}}
            <form action="{{ route('people.index') }}" method="GET" class="mb-4" id="searchFilterForm">
                @if (request('gender'))
                    <input type="hidden" name="gender" value="{{ request('gender') }}">
                @endif
                
                <div class="glass-filter-card shadow-lg">
                    <div class="row align-items-center">
                        {{-- تصنيف الأشخاص حسب العائلة (اليمين) --}}
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <label class="d-block text-muted mb-2 font-weight-bold" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                                <i class="fas fa-filter text-primary mr-1"></i> تصنيف الأشخاص حسب العائلة
                            </label>
                            <div class="family-status-segmented-control shadow-sm">
                                <!-- الكل -->
                                <div class="family-status-tab">
                                    <input type="radio" name="family_status" id="fs_all" value="" class="family-status-radio" 
                                           {{ !request('family_status') ? 'checked' : '' }} onchange="this.form.submit()">
                                    <label for="fs_all" class="family-status-label all-tab">
                                        <i class="fas fa-globe-americas"></i>
                                        <span>الكل</span>
                                    </label>
                                </div>
                                
                                <!-- داخل العائلة -->
                                <div class="family-status-tab">
                                    <input type="radio" name="family_status" id="fs_inside" value="1" class="family-status-radio" 
                                           {{ request('family_status') === '1' ? 'checked' : '' }} onchange="this.form.submit()">
                                    <label for="fs_inside" class="family-status-label inside-tab">
                                        <i class="fas fa-home"></i>
                                        <span>داخل العائلة</span>
                                    </label>
                                </div>
                                
                                <!-- خارج العائلة -->
                                <div class="family-status-tab">
                                    <input type="radio" name="family_status" id="fs_outside" value="0" class="family-status-radio" 
                                           {{ request('family_status') === '0' ? 'checked' : '' }} onchange="this.form.submit()">
                                    <label for="fs_outside" class="family-status-label outside-tab">
                                        <i class="fas fa-user-friends"></i>
                                        <span>خارج العائلة</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        {{-- البحث الذكي في السجلات (اليسار) --}}
                        <div class="col-lg-6">
                            <label class="d-block text-muted mb-2 font-weight-bold" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                                <i class="fas fa-search text-primary mr-1"></i> البحث الذكي في السجلات
                            </label>
                            <div class="premium-search-wrapper shadow-sm">
                                <i class="fas fa-search search-icon-inside"></i>
                                <input type="text" name="search" class="form-control premium-search-input" 
                                       placeholder="ابحث بالاسم، اللقب، أو اللقب العائلي..." 
                                       value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary premium-search-btn">
                                    <span>بحث</span>
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

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
        </x-dashboard.card>
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

    </script>

    {{-- إضافة أنماط إضافية للتوجال --}}
    <style>
        /* Glass Filter Card */
        .glass-filter-card {
            background: rgba(255, 255, 255, 0.02) !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            border-radius: 20px !important;
            padding: 24px !important;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25) !important;
            backdrop-filter: blur(15px);
            position: relative;
            overflow: hidden;
        }

        .glass-filter-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(130deg, rgba(255,255,255,0.03) 0%, rgba(255,255,255,0) 100%);
            pointer-events: none;
        }

        /* Segmented Control */
        .family-status-segmented-control {
            display: flex;
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.04);
            padding: 4px;
            border-radius: 14px;
            width: 100%;
        }

        .family-status-tab {
            flex: 1;
            position: relative;
        }

        .family-status-radio {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
            pointer-events: none;
        }

        .family-status-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 10px;
            color: rgba(255, 255, 255, 0.5) !important;
            font-weight: 500;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            margin: 0;
            user-select: none;
        }

        .family-status-label:hover {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.03);
        }

        /* Radio Checked States */
        .family-status-radio:checked + .all-tab {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.12), rgba(255, 255, 255, 0.04)) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 15px rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
        }

        .family-status-radio:checked + .inside-tab {
            background: linear-gradient(135deg, #10b981, #059669) !important;
            color: #ffffff !important;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3) !important;
        }

        .family-status-radio:checked + .outside-tab {
            background: linear-gradient(135deg, #f59e0b, #d97706) !important;
            color: #ffffff !important;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3) !important;
        }

        /* Premium Search Wrapper */
        .premium-search-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            width: 100%;
        }

        .premium-search-input {
            height: 50px !important;
            background: rgba(0, 0, 0, 0.2) !important;
            border: 1px solid rgba(255, 255, 255, 0.04) !important;
            border-radius: 14px !important;
            color: #ffffff !important;
            padding-left: 100px !important; /* Space for the button on the left */
            padding-right: 45px !important; /* Space for the search icon on the right */
            font-size: 0.95rem !important;
            transition: all 0.3s ease !important;
        }

        .premium-search-input:focus {
            background: rgba(0, 0, 0, 0.3) !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.03) !important;
        }

        .search-icon-inside {
            position: absolute;
            right: 18px;
            color: rgba(255, 255, 255, 0.3);
            font-size: 1.1rem;
            pointer-events: none;
            transition: color 0.3s ease;
        }

        .premium-search-input:focus ~ .search-icon-inside {
            color: #3b82f6; /* Modern blue highlight on focus */
            text-shadow: 0 0 10px rgba(59, 130, 246, 0.5);
        }

        .premium-search-btn {
            position: absolute;
            left: 4px;
            height: 42px;
            border-radius: 10px !important;
            padding: 0 20px !important;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8) !important;
            border: none !important;
            color: #ffffff !important;
            font-weight: 500 !important;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        .premium-search-btn:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4) !important;
        }

        .card.border-primary {
            border-width: 2px !important;
        }
    </style>
@endpush
