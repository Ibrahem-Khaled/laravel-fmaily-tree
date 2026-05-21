@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل باستخدام المكون المشترك --}}
        <x-dashboard.page-header title="ملف {{ $person->full_name }}" description="عرض وتحديث بيانات الشخص وعلاقاته العائلية">
            <x-slot name="actions">
                <button type="button" class="btn btn-warning btn-sm shadow-sm" data-toggle="modal"
                    data-target="#addPersonsOutsideTheFamilyTreeModal">
                    <i class="fas fa-user-plus mr-1"></i> إضافة شخص من خارج العائلة
                </button>
            </x-slot>
        </x-dashboard.page-header>

        {{-- ✅ تضمين المودال الجديد الذي أنشأناه --}}
        @include('dashboard.people.modals.add-outside-the-family')

        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="background: rgba(255,255,255,0.03); border-radius: 12px; padding: 12px 20px;">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="text-muted">لوحة التحكم</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('people.index') }}" class="text-muted">الأشخاص</a>
                </li>
                <li class="breadcrumb-item active text-primary" aria-current="page">{{ $person->full_name }}</li>
            </ol>
        </nav>

        @include('components.alerts')


        {{-- مودالات حسابات التواصل --}}
        @include('dashboard.people.modals.add-contact-account', ['person' => $person])
        @foreach ($person->contactAccounts as $account)
            @include('dashboard.people.modals.edit-contact-account', [
                'account' => $account,
                'person' => $person,
            ])
        @endforeach

        {{-- مودالات المواقع --}}
        @include('dashboard.people.modals.add-person-location', ['person' => $person])
        @foreach ($person->personLocations as $personLocation)
            @include('dashboard.people.modals.edit-person-location', [
                'personLocation' => $personLocation,
                'person' => $person,
            ])
        @endforeach

        {{-- بطاقة المعلومات الأساسية للشخص --}}
        <x-dashboard.card title="المعلومات الأساسية" icon="fe-user">
            <x-slot name="headerAction">
                <a href="{{ route('people.edit', $person->id) }}" class="btn btn-sm btn-primary shadow-sm" title="تعديل">
                    <i class="fas fa-edit mr-1"></i> تعديل
                </a>
            </x-slot>

            <div class="row">
                <div class="col-md-3 text-center mb-4 mb-md-0">
                    <div class="position-relative d-inline-block">
                        <img src="{{ $person->avatar }}" alt="{{ $person->full_name }}"
                            class="img-fluid rounded-circle mb-3 shadow-sm border border-light" style="width: 150px; height: 150px; object-fit: cover;">
                    </div>

                    @if ($person->photo_url)
                        <form action="{{ route('people.removePhoto', $person->id) }}" method="POST"
                            onsubmit="return confirm('هل أنت متأكد أنك تريد حذف الصورة؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm mt-2 shadow-sm" style="border-radius: 8px;">
                                <i class="fas fa-trash-alt mr-1"></i> حذف الصورة
                            </button>
                        </form>
                    @endif
                </div>
                <div class="col-md-9">
                    <div class="table-responsive" style="border-radius: 12px; overflow: hidden; border: 1px solid rgba(255,255,255,0.05);">
                        <table class="table table-hover mb-0" style="background: rgba(255,255,255,0.01);">
                            <tbody>
                                <tr>
                                    <th class="py-3 text-muted" style="width: 25%; background: rgba(255,255,255,0.02); border-color: rgba(255,255,255,0.05) !important;">الاسم الكامل</th>
                                    <td class="py-3 text-white font-weight-bold" style="border-color: rgba(255,255,255,0.05) !important;">{{ $person->full_name }}</td>
                                </tr>
                                <tr>
                                    <th class="py-3 text-muted" style="background: rgba(255,255,255,0.02); border-color: rgba(255,255,255,0.05) !important;">الجنس</th>
                                    <td class="py-3" style="border-color: rgba(255,255,255,0.05) !important;">
                                        <span class="badge badge-pill badge-{{ $person->gender == 'male' ? 'primary' : 'pink' }} px-3 py-2">
                                            {{ $person->gender == 'male' ? 'ذكر' : 'أنثى' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="py-3 text-muted" style="background: rgba(255,255,255,0.02); border-color: rgba(255,255,255,0.05) !important;">الأب</th>
                                    <td class="py-3" style="border-color: rgba(255,255,255,0.05) !important;">
                                        @if ($person->parent)
                                            <a href="{{ route('people.show', $person->parent_id) }}" class="text-white hover-underline font-weight-bold">
                                                {{ $person->parent->full_name }}
                                            </a>
                                        @else
                                            <span class="text-muted">غير معروف</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="py-3 text-muted" style="background: rgba(255,255,255,0.02); border-color: rgba(255,255,255,0.05) !important;">الأم</th>
                                    <td class="py-3" style="border-color: rgba(255,255,255,0.05) !important;">
                                        @if ($person->mother)
                                            <a href="{{ route('people.show', $person->mother_id) }}" class="text-white hover-underline font-weight-bold">
                                                {{ $person->mother->full_name }}
                                            </a>
                                        @else
                                            <span class="text-muted">غير معروف</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="py-3 text-muted" style="background: rgba(255,255,255,0.02); border-color: rgba(255,255,255,0.05) !important;">تاريخ الميلاد</th>
                                    <td class="py-3" style="border-color: rgba(255,255,255,0.05) !important;">
                                        @if ($person->birth_date)
                                            <span class="text-white font-weight-bold mr-2">{{ format_gregorian_in_arabic($person->birth_date) }}</span>
                                            <span class="badge badge-pill px-3 py-2" style="font-size: 0.85rem; border-radius: 50px; background: rgba(54, 185, 204, 0.15); border: 1px solid rgba(54, 185, 204, 0.3); color: #36b9cc;">
                                                <i class="fas fa-calendar-alt mr-1"></i> {{ gregorian_to_hijri($person->birth_date) }}
                                            </span>
                                        @else
                                            <span class="text-muted">غير معروف</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($person->death_date)
                                <tr>
                                    <th class="py-3 text-muted" style="background: rgba(255,255,255,0.02); border-color: rgba(255,255,255,0.05) !important;">تاريخ الوفاة</th>
                                    <td class="py-3" style="border-color: rgba(255,255,255,0.05) !important;">
                                        <span class="text-white font-weight-bold mr-2">{{ format_gregorian_in_arabic($person->death_date) }}</span>
                                        <span class="badge badge-pill px-3 py-2" style="font-size: 0.85rem; border-radius: 50px; background: rgba(231, 74, 59, 0.15); border: 1px solid rgba(231, 74, 59, 0.3); color: #e74a3b;">
                                            <i class="fas fa-calendar-alt mr-1"></i> {{ gregorian_to_hijri($person->death_date) }}
                                        </span>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <th class="py-3 text-muted" style="background: rgba(255,255,255,0.02); border-color: rgba(255,255,255,0.05) !important;">فترة الحياة</th>
                                    <td class="py-3 text-white" style="border-color: rgba(255,255,255,0.05) !important;">
                                        {{ $person->life_span ?? 'غير معروف' }} (العمر: {{ $person->age ?? 'غير معروف' }})
                                    </td>
                                </tr>
                                <tr>
                                    <th class="py-3 text-muted" style="background: rgba(255,255,255,0.02); border-color: rgba(255,255,255,0.05) !important;">مكان الميلاد</th>
                                    <td class="py-3 text-white" style="border-color: rgba(255,255,255,0.05) !important;">{{ $person->birth_place ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="py-3 text-muted" style="background: rgba(255,255,255,0.02); border-color: rgba(255,255,255,0.05) !important;">المهنة</th>
                                    <td class="py-3 text-white" style="border-color: rgba(255,255,255,0.05) !important;">{{ $person->occupation ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="py-3 text-muted" style="background: rgba(255,255,255,0.02); border-color: rgba(255,255,255,0.05) !important;">مكان الإقامة</th>
                                    <td class="py-3 text-white" style="border-color: rgba(255,255,255,0.05) !important;">{{ $person->location_display ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="py-3 text-muted" style="background: rgba(255,255,255,0.02); border-color: rgba(255,255,255,0.05) !important;">مكان الوفاة</th>
                                    <td class="py-3 text-white" style="border-color: rgba(255,255,255,0.05) !important;">{{ $person->death_place ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="py-3 text-muted" style="background: rgba(255,255,255,0.02); border-color: rgba(255,255,255,0.05) !important;">المقبرة</th>
                                    <td class="py-3 text-white" style="border-color: rgba(255,255,255,0.05) !important;">{{ $person->cemetery ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="py-3 text-muted" style="background: rgba(255,255,255,0.02); border-color: rgba(255,255,255,0.05) !important;">لوكيشن القبر</th>
                                    <td class="py-3 text-white" style="border-color: rgba(255,255,255,0.05) !important;">{{ $person->cemetery_location ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="py-3 text-muted" style="background: rgba(255,255,255,0.02); border-color: rgba(255,255,255,0.05) !important;">رقم القبر</th>
                                    <td class="py-3 text-white" style="border-color: rgba(255,255,255,0.05) !important;">{{ $person->grave_number ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </x-dashboard.card>

        {{-- بطاقة الزوجات/الزوج --}}
        <x-dashboard.card title="{{ $person->gender == 'male' ? 'الزوجات' : 'الزوج' }}" icon="fe-heart">
            <x-slot name="headerAction">
                <button class="btn btn-info btn-sm shadow-sm" data-toggle="modal" data-target="#addMarriageModal">
                    <i class="fas fa-plus mr-1"></i> إضافة زواج
                </button>
            </x-slot>

            @if ($marriages->isNotEmpty())
                <div class="table-responsive" style="border-radius: 12px; overflow: hidden; border: 1px solid rgba(255,255,255,0.05);">
                    <table class="table table-hover align-middle mb-0" style="background: rgba(255,255,255,0.01);">
                        <thead style="background: rgba(255,255,255,0.03);">
                            <tr>
                                <th class="text-white border-0 py-3">{{ $person->gender == 'male' ? 'الزوجة' : 'الزوج' }}</th>
                                <th class="text-white border-0 py-3">تاريخ الزواج</th>
                                <th class="text-white border-0 py-3">تاريخ الانفصال</th>
                                <th class="text-white border-0 py-3">الحالة</th>
                                <th class="text-white border-0 py-3">المدة</th>
                                <th class="text-white border-0 py-3 text-center" style="width: 150px;">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($marriages as $marriage)
                                @php
                                    $spouse = $person->gender == 'male' ? $marriage->wife : $marriage->husband;
                                @endphp
                                <tr style="transition: background-color 0.2s ease;">
                                    <td class="border-bottom-0 py-3">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $spouse->avatar }}" alt="{{ $spouse->full_name }}"
                                                class="rounded-circle mr-3 border border-light" width="40" height="40" style="object-fit: cover;">
                                            <a href="{{ route('people.show', $spouse->id) }}" class="font-weight-bold text-white text-decoration-none hover-primary">
                                                {{ $spouse->full_name }}
                                            </a>
                                        </div>
                                    </td>
                                    <td class="border-bottom-0 py-3 text-muted">
                                        @if($marriage->married_at)
                                            <span class="text-white font-weight-bold">{{ format_gregorian_in_arabic($marriage->married_at) }}</span>
                                            <span class="badge badge-pill px-2 py-1 ml-1" style="font-size: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #ccc; border-radius: 50px;">
                                                {{ gregorian_to_hijri($marriage->married_at) }}
                                            </span>
                                        @else
                                            غير معروف
                                        @endif
                                    </td>
                                    <td class="border-bottom-0 py-3 text-muted">
                                        @if($marriage->divorced_at)
                                            <span class="text-white font-weight-bold">{{ format_gregorian_in_arabic($marriage->divorced_at) }}</span>
                                            <span class="badge badge-pill px-2 py-1 ml-1" style="font-size: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #ccc; border-radius: 50px;">
                                                {{ gregorian_to_hijri($marriage->divorced_at) }}
                                            </span>
                                        @else
                                            {{ $marriage->is_divorced ? '✅' : '-' }}
                                        @endif
                                    </td>
                                    <td class="border-bottom-0 py-3">
                                        @if ($marriage->isDivorced())
                                            <span class="badge badge-pill px-3 py-2" style="background: rgba(231,74,59,0.15); color: #e74a3b;">
                                                {{ $marriage->status_text }}
                                            </span>
                                        @elseif($marriage->married_at)
                                            <span class="badge badge-pill px-3 py-2" style="background: rgba(40,167,69,0.15); color: #28a745;">
                                                {{ $marriage->status_text }}
                                            </span>
                                        @else
                                            <span class="badge badge-pill px-3 py-2" style="background: rgba(255,193,7,0.15); color: #ffc107;">
                                                {{ $marriage->status_text }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="border-bottom-0 py-3 text-white font-weight-bold">
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
                                    <td class="border-bottom-0 py-3 text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <button type="button" class="btn btn-sm btn-circle btn-info shadow-sm"
                                                data-toggle="modal" data-target="#showMarriageModal{{ $marriage->id }}"
                                                title="عرض تفاصيل الزواج">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-circle btn-primary shadow-sm"
                                                data-toggle="modal" data-target="#editMarriageModal{{ $marriage->id }}"
                                                title="تعديل الزواج">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-circle btn-danger shadow-sm"
                                                data-toggle="modal" data-target="#deleteMarriageModal{{ $marriage->id }}"
                                                title="حذف الزواج">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>

                                    @include('dashboard.marriages.modals.delete', [
                                        'marriage' => $marriage,
                                    ])
                                    @include('dashboard.marriages.modals.show', [
                                        'marriage' => $marriage,
                                    ])
                                    @include('dashboard.marriages.modals.edit', [
                                        'marriage' => $marriage,
                                    ])
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center p-4 rounded-lg" style="background: rgba(255,255,255,0.01); border: 1px dashed rgba(255,255,255,0.1);">
                    <i class="fas fa-heart-broken fa-2x text-muted mb-2" style="opacity: 0.3;"></i>
                    <p class="mb-0 text-muted">لا يوجد سجلات زواج حالياً. يمكنك إضافة سجل باستخدام الزر أعلاه.</p>
                </div>
            @endif
        </x-dashboard.card>

        {{-- بطاقة الأبناء (مع تطبيق إعادة الترتيب) --}}
        <x-dashboard.card title="الأبناء ({{ $children->count() }})" icon="fe-users">
            <x-slot name="headerAction">
                <button class="btn btn-success btn-sm shadow-sm" data-toggle="modal" data-target="#addChildModal">
                    <i class="fas fa-plus mr-1"></i> إضافة ابن/ابنة
                </button>
            </x-slot>

            @if ($children->isNotEmpty())
                <div class="table-responsive" style="border-radius: 12px; overflow: hidden; border: 1px solid rgba(255,255,255,0.05);">
                    <table class="table table-hover align-middle mb-0" style="background: rgba(255,255,255,0.01);">
                        <thead style="background: rgba(255,255,255,0.03);">
                            <tr>
                                <th class="text-white border-0 py-3" style="width: 80px;">الترتيب</th>
                                <th class="text-white border-0 py-3">الاسم</th>
                                <th class="text-white border-0 py-3">الجنس</th>
                                <th class="text-white border-0 py-3">العمر</th>
                                <th class="text-white border-0 py-3 text-center" style="width: 150px;">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody id="children-sortable" data-url="{{ route('people.reorder') }}">
                            @foreach ($children as $child)
                                <tr data-id="{{ $child->id }}" style="transition: background-color 0.2s ease;">
                                    <td class="border-bottom-0 py-3 text-center text-muted" style="cursor: move;">
                                        <i class="fas fa-arrows-alt"></i>
                                    </td>
                                    <td class="border-bottom-0 py-3">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $child->avatar }}" alt="{{ $child->full_name }}"
                                                class="rounded-circle mr-3 border border-light" width="40" height="40" style="object-fit: cover;">
                                            <a href="{{ route('people.show', $child->id) }}" class="font-weight-bold text-white text-decoration-none hover-primary">
                                                {{ $child->full_name }}
                                            </a>
                                        </div>
                                    </td>
                                    <td class="border-bottom-0 py-3">
                                        <span class="badge badge-pill badge-{{ $child->gender == 'male' ? 'primary' : 'pink' }} px-3 py-2">
                                            {{ $child->gender == 'male' ? 'ذكر' : 'أنثى' }}
                                        </span>
                                    </td>
                                    <td class="border-bottom-0 py-3 text-white font-weight-bold">
                                        {{ $child->age ?? 'غير معروف' }}
                                    </td>
                                    <td class="border-bottom-0 py-3 text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('people.edit', $child->id) }}" class="btn btn-sm btn-circle btn-primary shadow-sm"
                                                title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-circle btn-danger shadow-sm"
                                                data-toggle="modal"
                                                data-target="#deletePersonModal{{ $child->getKey() }}" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>

                                        {{-- مودال حذف الابن المضمن --}}
                                        <div class="modal fade" id="deletePersonModal{{ $child->getKey() }}"
                                            tabindex="-1" role="dialog"
                                            aria-labelledby="deletePersonModalLabel{{ $child->getKey() }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content text-right" style="background: #1e1e2d; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 16px; color: #fff;">
                                                    <div class="modal-header border-bottom-0 pb-0" style="border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                                                        <h5 class="modal-title text-danger d-flex align-items-center"
                                                            id="deletePersonModalLabel{{ $child->getKey() }}">
                                                            <i class="fas fa-exclamation-triangle mr-2"></i>
                                                            <span>تأكيد الحذف النهائي</span>
                                                        </h5>
                                                        <button type="button" class="close text-white" data-dismiss="modal"
                                                            aria-label="Close" style="opacity: 0.5;">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>

                                                    <form action="{{ route('people.destroy', $child->getKey()) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="modal-body text-center pt-4">
                                                            <div class="position-relative d-inline-block mb-3">
                                                                <img src="{{ $child->avatar }}" alt="{{ $child->full_name }}"
                                                                    class="rounded-circle border border-danger" 
                                                                    style="width: 100px; height: 100px; object-fit: cover; border: 3px solid rgba(231, 74, 59, 0.3); box-shadow: 0 0 20px rgba(231, 74, 59, 0.2);">
                                                            </div>
                                                            
                                                            <h4 class="font-weight-bold text-white mb-2">{{ $child->full_name }}</h4>
                                                            
                                                            <span class="badge badge-pill badge-{{ $child->gender == 'male' ? 'primary' : 'pink' }} px-3 py-2 mb-3">
                                                                {{ $child->gender == 'male' ? 'ذكر' : 'أنثى' }}
                                                            </span>

                                                            <div class="bg-dark p-3 rounded mb-3" style="background: rgba(0,0,0,0.2) !important; border: 1px solid rgba(255,255,255,0.03);">
                                                                @if ($child->birth_date)
                                                                    <div class="mb-2 text-muted" style="font-size: 0.9rem;">
                                                                        <span class="ml-1">تاريخ الميلاد:</span>
                                                                        <span class="text-white font-weight-bold ml-1">{{ format_gregorian_in_arabic($child->birth_date) }}</span>
                                                                        <span class="badge badge-pill px-2 py-1" style="font-size: 0.75rem; background: rgba(54, 185, 204, 0.15); border: 1px solid rgba(54, 185, 204, 0.3); color: #36b9cc;">
                                                                            {{ gregorian_to_hijri($child->birth_date) }}
                                                                        </span>
                                                                    </div>
                                                                @endif
                                                                @if ($child->death_date)
                                                                    <div class="text-muted" style="font-size: 0.9rem;">
                                                                        <span class="ml-1">تاريخ الوفاة:</span>
                                                                        <span class="text-white font-weight-bold ml-1">{{ format_gregorian_in_arabic($child->death_date) }}</span>
                                                                        <span class="badge badge-pill px-2 py-1" style="font-size: 0.75rem; background: rgba(231, 74, 59, 0.15); border: 1px solid rgba(231, 74, 59, 0.3); color: #e74a3b;">
                                                                            {{ gregorian_to_hijri($child->death_date) }}
                                                                        </span>
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            @php
                                                                $childDescendantsCount = $child->children_count ?? $child->children()->count();
                                                            @endphp
                                                            @if ($childDescendantsCount > 0)
                                                                <div class="alert alert-danger border-0 text-right" style="background: rgba(231, 74, 59, 0.15); border-right: 4px solid #e74a3b !important; border-radius: 8px; color: #ff8578;">
                                                                    <h6 class="font-weight-bold mb-1"><i class="fas fa-skull-crossbones mr-1"></i> تحذير حرج: حذف متسلسل!</h6>
                                                                    هذا العضو لديه <strong>{{ $childDescendantsCount }}</strong> من الأبناء المباشرين. حذف هذا الحساب سيؤدي إلى <strong>حذف كافة التابعين له تلقائياً</strong> من شجرة العائلة بشكل نهائي!
                                                                </div>
                                                            @else
                                                                <div class="alert alert-warning border-0 text-right" style="background: rgba(255, 193, 7, 0.1); border-right: 4px solid #ffc107 !important; border-radius: 8px; color: #ffe082;">
                                                                    <i class="fas fa-exclamation-triangle mr-1"></i> تنبيه: هذا الإجراء لا يمكن التراجع عنه وسيتم إزالة العضو نهائياً من شجرة العائلة.
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer border-top-0 pt-0" style="border-top: 1px solid rgba(255, 255, 255, 0.05); padding: 20px;">
                                                            <button type="button" class="btn btn-light btn-sm px-4" data-dismiss="modal" style="border-radius: 8px; background: rgba(255,255,255,0.08); border: none; color: #fff;">إلغاء</button>
                                                            <button type="submit" class="btn btn-danger btn-sm px-4" style="border-radius: 8px; background: #e74a3b; border: none; box-shadow: 0 4px 12px rgba(231, 74, 59, 0.3);">تأكيد الحذف النهائي</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center p-4 rounded-lg" style="background: rgba(255,255,255,0.01); border: 1px dashed rgba(255,255,255,0.1);">
                    <i class="fas fa-child fa-2x text-muted mb-2" style="opacity: 0.3;"></i>
                    <p class="mb-0 text-muted">لا يوجد أبناء حالياً. يمكنك إضافة ابن/ابنة باستخدام الزر أعلاه.</p>
                </div>
            @endif
        </x-dashboard.card>

        {{-- بطاقة حسابات التواصل --}}
        <x-dashboard.card title="حسابات التواصل" icon="fe-message-square">
            <x-slot name="headerAction">
                <button class="btn btn-success btn-sm shadow-sm" data-toggle="modal"
                    data-target="#addContactAccountModal{{ $person->id }}">
                    <i class="fas fa-plus mr-1"></i> إضافة حساب تواصل
                </button>
            </x-slot>

            @if ($person->contactAccounts->isNotEmpty())
                <div class="table-responsive" style="border-radius: 12px; overflow: hidden; border: 1px solid rgba(255,255,255,0.05);">
                    <table class="table table-hover align-middle mb-0" style="background: rgba(255,255,255,0.01);">
                        <thead style="background: rgba(255,255,255,0.03);">
                            <tr>
                                <th class="text-white border-0 py-3">النوع</th>
                                <th class="text-white border-0 py-3">القيمة</th>
                                <th class="text-white border-0 py-3">التسمية</th>
                                <th class="text-white border-0 py-3 text-center" style="width: 120px;">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($person->contactAccounts as $account)
                                <tr style="transition: background-color 0.2s ease;">
                                    <td class="border-bottom-0 py-3 text-white">
                                        <i class="fas {{ $account->icon }} mr-1"></i>
                                        {{ ucfirst($account->type) }}
                                    </td>
                                    <td class="border-bottom-0 py-3">
                                        <a href="{{ $account->url }}" target="_blank" class="text-white hover-underline font-weight-bold">
                                            {{ $account->value }}
                                        </a>
                                    </td>
                                    <td class="border-bottom-0 py-3 text-muted">
                                        {{ $account->label ?? '-' }}
                                    </td>
                                    <td class="border-bottom-0 py-3 text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <button type="button" class="btn btn-sm btn-circle btn-primary shadow-sm"
                                                data-toggle="modal"
                                                data-target="#editContactAccountModal{{ $account->id }}" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form
                                                action="{{ route('people.contact-accounts.destroy', ['person' => $person->id, 'contactAccountId' => $account->id]) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('هل أنت متأكد من حذف هذا الحساب؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-circle btn-danger shadow-sm"
                                                    title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center p-4 rounded-lg" style="background: rgba(255,255,255,0.01); border: 1px dashed rgba(255,255,255,0.1);">
                    <i class="fas fa-address-book fa-2x text-muted mb-2" style="opacity: 0.3;"></i>
                    <p class="mb-0 text-muted">لا توجد حسابات تواصل حالياً. يمكنك إضافة حساب باستخدام الزر أعلاه.</p>
                </div>
            @endif
        </x-dashboard.card>

        {{-- بطاقة المواقع المتعددة --}}
        <x-dashboard.card title="المواقع" icon="fe-map-pin">
            <x-slot name="headerAction">
                <button class="btn btn-success btn-sm shadow-sm" data-toggle="modal"
                    data-target="#addPersonLocationModal{{ $person->id }}">
                    <i class="fas fa-plus mr-1"></i> إضافة موقع
                </button>
            </x-slot>

            @if ($person->locations->isNotEmpty())
                <div class="table-responsive" style="border-radius: 12px; overflow: hidden; border: 1px solid rgba(255,255,255,0.05);">
                    <table class="table table-hover align-middle mb-0" style="background: rgba(255,255,255,0.01);">
                        <thead style="background: rgba(255,255,255,0.03);">
                            <tr>
                                <th class="text-white border-0 py-3">الموقع</th>
                                <th class="text-white border-0 py-3">التسمية</th>
                                <th class="text-white border-0 py-3">الحالة</th>
                                <th class="text-white border-0 py-3 text-center" style="width: 120px;">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($person->locations as $location)
                                @php
                                    $personLocation = $person->personLocations
                                        ->where('location_id', $location->id)
                                        ->first();
                                @endphp
                                <tr style="transition: background-color 0.2s ease;">
                                    <td class="border-bottom-0 py-3 text-white font-weight-bold">
                                        {{ $location->name }}
                                    </td>
                                    <td class="border-bottom-0 py-3 text-muted">
                                        {{ $personLocation->label ?? '-' }}
                                    </td>
                                    <td class="border-bottom-0 py-3">
                                        @if ($personLocation && $personLocation->is_primary)
                                            <span class="badge badge-pill px-3 py-2" style="background: rgba(40,167,69,0.15); color: #28a745;">أساسي</span>
                                        @else
                                            <span class="badge badge-pill px-3 py-2" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #ccc;">ثانوي</span>
                                        @endif
                                    </td>
                                    <td class="border-bottom-0 py-3 text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            @if ($personLocation)
                                                <button type="button" class="btn btn-sm btn-circle btn-primary shadow-sm"
                                                    data-toggle="modal"
                                                    data-target="#editPersonLocationModal{{ $personLocation->id }}"
                                                    title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form
                                                    action="{{ route('people.locations.destroy', [$person->id, $personLocation->id]) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('هل أنت متأكد من حذف هذا الموقع؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-circle btn-danger shadow-sm"
                                                        title="حذف">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('people.locations.store', $person->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="location_id" value="{{ $location->id }}">
                                                    <button type="submit" class="btn btn-sm btn-circle btn-success shadow-sm"
                                                        title="ربط الموقع">
                                                        <i class="fas fa-link"></i>
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-sm btn-circle btn-primary shadow-sm"
                                                    data-toggle="modal"
                                                    data-target="#addPersonLocationModal{{ $person->id }}"
                                                    title="إضافة موقع">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center p-4 rounded-lg" style="background: rgba(255,255,255,0.01); border: 1px dashed rgba(255,255,255,0.1);">
                    <i class="fas fa-map-marker-alt fa-2x text-muted mb-2" style="opacity: 0.3;"></i>
                    <p class="mb-0 text-muted">لا توجد مواقع حالياً. يمكنك إضافة موقع باستخدام الزر أعلاه.</p>
                </div>
            @endif
        </x-dashboard.card>

        {{-- بطاقة الرضاعة --}}
        <x-dashboard.card title="علاقات الرضاعة" icon="fe-activity">
            <x-slot name="headerAction">
                <a href="{{ route('breastfeeding.index') }}" class="btn btn-info btn-sm shadow-sm">
                    <i class="fas fa-cog mr-1"></i> إدارة الرضاعة
                </a>
            </x-slot>

            @php
                $nursingRelationships = $person->nursingRelationships()->with('breastfedChild')->get();
                $breastfedRelationships = $person->breastfedRelationships()->with('nursingMother')->get();
            @endphp

            @if ($nursingRelationships->isNotEmpty() || $breastfedRelationships->isNotEmpty())
                <div class="row">
                    {{-- إذا كان الشخص أم مرضعة --}}
                    @if ($nursingRelationships->isNotEmpty())
                        <div class="col-md-6 mb-4 mb-md-0">
                            <h6 class="text-success mb-3 font-weight-bold d-flex align-items-center">
                                <i class="fas fa-female mr-2"></i> كأم مرضعة
                            </h6>
                            <div class="table-responsive" style="border-radius: 12px; overflow: hidden; border: 1px solid rgba(255,255,255,0.05);">
                                <table class="table table-hover align-middle mb-0" style="background: rgba(255,255,255,0.01);">
                                    <thead style="background: rgba(255,255,255,0.03);">
                                        <tr>
                                            <th class="text-white border-0 py-3">الطفل المرتضع</th>
                                            <th class="text-white border-0 py-3">المدة</th>
                                            <th class="text-white border-0 py-3">الحالة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($nursingRelationships as $relationship)
                                            <tr style="transition: background-color 0.2s ease;">
                                                <td class="border-bottom-0 py-3">
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $relationship->breastfedChild->avatar }}"
                                                            alt="{{ $relationship->breastfedChild->first_name }}"
                                                            class="rounded-circle mr-3 border border-light" width="40" height="40" style="object-fit: cover;">
                                                        <a href="{{ route('people.show', $relationship->breastfedChild->id) }}" class="font-weight-bold text-white text-decoration-none hover-primary">
                                                            {{ $relationship->breastfedChild->full_name }}
                                                        </a>
                                                    </div>
                                                </td>
                                                <td class="border-bottom-0 py-3 text-muted">
                                                    @if ($relationship->duration_in_months)
                                                        <span class="badge badge-pill px-3 py-2" style="background: rgba(54, 185, 204, 0.15); color: #36b9cc;">
                                                            {{ $relationship->duration_in_months }} شهر
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="border-bottom-0 py-3">
                                                    @if ($relationship->is_active)
                                                        <span class="badge badge-pill px-3 py-2" style="background: rgba(40,167,69,0.15); color: #28a745;">نشط</span>
                                                    @else
                                                        <span class="badge badge-pill px-3 py-2" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #ccc;">غير نشط</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    {{-- إذا كان الشخص طفل مرتضع --}}
                    @if ($breastfedRelationships->isNotEmpty())
                        <div class="col-md-6">
                            <h6 class="text-info mb-3 font-weight-bold d-flex align-items-center">
                                <i class="fas fa-child mr-2"></i> كطفل مرتضع
                            </h6>
                            <div class="table-responsive" style="border-radius: 12px; overflow: hidden; border: 1px solid rgba(255,255,255,0.05);">
                                <table class="table table-hover align-middle mb-0" style="background: rgba(255,255,255,0.01);">
                                    <thead style="background: rgba(255,255,255,0.03);">
                                        <tr>
                                            <th class="text-white border-0 py-3">الأم المرضعة</th>
                                            <th class="text-white border-0 py-3">المدة</th>
                                            <th class="text-white border-0 py-3">الحالة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($breastfedRelationships as $relationship)
                                            <tr style="transition: background-color 0.2s ease;">
                                                <td class="border-bottom-0 py-3">
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $relationship->nursingMother->avatar }}"
                                                            alt="{{ $relationship->nursingMother->first_name }}"
                                                            class="rounded-circle mr-3 border border-light" width="40" height="40" style="object-fit: cover;">
                                                        <a href="{{ route('people.show', $relationship->nursingMother->id) }}" class="font-weight-bold text-white text-decoration-none hover-primary">
                                                            {{ $relationship->nursingMother->full_name }}
                                                        </a>
                                                    </div>
                                                </td>
                                                <td class="border-bottom-0 py-3 text-muted">
                                                    @if ($relationship->duration_in_months)
                                                        <span class="badge badge-pill px-3 py-2" style="background: rgba(54, 185, 204, 0.15); color: #36b9cc;">
                                                            {{ $relationship->duration_in_months }} شهر
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="border-bottom-0 py-3">
                                                    @if ($relationship->is_active)
                                                        <span class="badge badge-pill px-3 py-2" style="background: rgba(40,167,69,0.15); color: #28a745;">نشط</span>
                                                    @else
                                                        <span class="badge badge-pill px-3 py-2" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #ccc;">غير نشط</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center p-4 rounded-lg" style="background: rgba(255,255,255,0.01); border: 1px dashed rgba(255,255,255,0.1);">
                    <i class="fas fa-baby fa-2x text-muted mb-3" style="opacity: 0.3;"></i>
                    <p class="mb-2 text-muted">لا توجد علاقات رضاعة مسجلة لهذا الشخص.</p>
                    <a href="{{ route('breastfeeding.index') }}" class="btn btn-primary btn-sm mt-2 shadow-sm" style="border-radius: 8px;">
                        <i class="fas fa-plus mr-1"></i> إضافة علاقة رضاعة
                    </a>
                </div>
            @endif
        </x-dashboard.card>
    </div>

    {{-- مودال إضافة ابن/ابنة جديد --}}
    @include('dashboard.people.modals.add_child')

    {{-- مودال إضافة زواج جديد --}}
    @include('dashboard.people.modals.add_marriage')



    {{-- تضمين مودالات التعديل لكل الأشخاص المعروضين في الصفحة --}}
    {{-- @foreach ($spouses as $spouse)
        @include('dashboard.people.modals.edit', ['person' => $spouse])
    @endforeach --}}

@endsection

@push('scripts')
    {{-- ✅ الخطوة 1: تضمين مكتبة SortableJS --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    {{-- جلب زوجات الأب عند اختيار الأب (مودال إضافة ابن/ابنة وغيره) --}}
    @include('dashboard.people.partials.father-wives-script')

    <script>
        // Script لتحديث اسم الملف عند اختياره في مودالات التعديل والإضافة
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
        });
        $('[data-toggle="tooltip"]').tooltip();
    </script>

    {{-- ✅ الخطوة 4: كود JavaScript الخاص بـ SortableJS (يعمل مع أي جدول بنفس الطريقة) --}}
    <script>
        const sortableElement = document.getElementById('children-sortable');

        // التحقق من وجود العنصر قبل تهيئة SortableJS
        if (sortableElement) {
            const sortable = Sortable.create(sortableElement, {
                animation: 150,
                handle: '.fa-arrows-alt', // المقبض
                onEnd: function(evt) {
                    const order = Array.from(evt.to.children).map((row, index) => ({
                        id: row.dataset.id,
                        order: index + 1
                    }));
                    // استدعاء دالة الإرسال
                    updateOrder(order, sortableElement.dataset.url);
                }
            });
        }

        function updateOrder(order, url) {
            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        order: order
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('فشل تحديث الترتيب');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Success:', data);
                    // يمكنك إظهار رسالة نجاح للمستخدم هنا
                })
                .catch((error) => {
                    console.error('Error:', error);
                    // يمكنك إظهار رسالة خطأ للمستخدم هنا
                });
        }
    </script>
@endpush
