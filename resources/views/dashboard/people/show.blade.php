@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة --}}
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">ملف {{ $person->full_name }}</h1>
            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                data-target="#addPersonsOutsideTheFamilyTreeModal">
                <i class="fas fa-user-plus"></i> إضافة شخص من خارج العائلة
            </button>

            {{-- ✅ تضمين المودال الجديد الذي أنشأناه --}}
            @include('dashboard.people.modals.add-outside-the-family')
        </div>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">لوحة التحكم</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('people.index') }}">الأشخاص</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">ملف الشخص</li>
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
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user"></i>
                    المعلومات الأساسية
                </h6>
                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                    data-target="#editPersonModal{{ $person->id }}" title="تعديل">
                    <i class="fas fa-edit"></i> تعديل
                </button>
                @include('dashboard.people.modals.edit', ['person' => $person])

            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <img src="{{ $person->avatar }}" alt="{{ $person->full_name }}"
                            class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">

                        @if ($person->photo_url)
                            <form action="{{ route('people.removePhoto', $person->id) }}" method="POST"
                                onsubmit="return confirm('هل أنت متأكد أنك تريد حذف الصورة؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm mt-2">
                                    <i class="fas fa-trash"></i> حذف الصورة
                                </button>
                            </form>
                        @endif
                    </div>
                    <div class="col-md-9">
                        <table class="table table-bordered">
                            <tr>
                                <th>الاسم الكامل</th>
                                <td>{{ $person->full_name }}</td>
                            </tr>
                            <tr>
                                <th>الجنس</th>
                                <td>{{ $person->gender == 'male' ? 'ذكر' : 'أنثى' }}</td>
                            </tr>
                            <tr>
                                <th>الأب</th>
                                <td>
                                    @if ($person->parent)
                                        <a
                                            href="{{ route('people.show', $person->parent_id) }}">{{ $person->parent->full_name }}</a>
                                    @else
                                        غير معروف
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>الأم</th>
                                <td>
                                    @if ($person->mother)
                                        <a
                                            href="{{ route('people.show', $person->mother_id) }}">{{ $person->mother->full_name }}</a>
                                    @else
                                        غير معروف
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>فترة الحياة</th>
                                <td>{{ $person->life_span ?? 'غير معروف' }} (العمر: {{ $person->age ?? 'غير معروف' }})
                                </td>
                            </tr>
                            <tr>
                                <th>مكان الميلاد</th>
                                <td>{{ $person->birth_place ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>المهنة</th>
                                <td>{{ $person->occupation ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>مكان الإقامة</th>
                                <td>{{ $person->location_display ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>مكان الوفاة</th>
                                <td>{{ $person->death_place ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>المقبرة</th>
                                <td>{{ $person->cemetery ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>لوكيشن القبر</th>
                                <td>{{ $person->cemetery_location ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>رقم القبر</th>
                                <td>{{ $person->grave_number ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- بطاقة الزوجات/الزوج --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-venus-mars"></i>
                    {{ $person->gender == 'male' ? 'الزوجات' : 'الزوج' }}
                </h6>
                <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#addMarriageModal">
                    <i class="fas fa-plus"></i> إضافة زواج
                </button>
            </div>
            <div class="card-body">
                @if ($marriages->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ $person->gender == 'male' ? 'الزوجة' : 'الزوج' }}</th>
                                    <th>تاريخ الزواج</th>
                                    <th>تاريخ الانفصال</th>
                                    <th>الحالة</th>
                                    <th>المدة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($marriages as $marriage)
                                    @php
                                        $spouse = $person->gender == 'male' ? $marriage->wife : $marriage->husband;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $spouse->avatar }}" alt="{{ $spouse->full_name }}"
                                                    class="rounded-circle mr-2" width="40" height="40">
                                                <a
                                                    href="{{ route('people.show', $spouse->id) }}">{{ $spouse->full_name }}</a>
                                            </div>
                                        </td>
                                        <td>{{ $marriage->married_at ? $marriage->married_at->format('Y-m-d') : 'غير معروف' }}
                                        </td>
                                        <td>{{ $marriage->divorced_at ? $marriage->divorced_at->format('Y-m-d') : ($marriage->is_divorced ? '✅' : '-') }}
                                        </td>
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
                                                    {{ $marriage->married_at->diffInYears($marriage->divorced_at ?? now()) }}
                                                    سنة
                                                @else
                                                    {{ $marriage->married_at->diffInYears(now()) }} سنة
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-circle btn-info"
                                                data-toggle="modal" data-target="#showMarriageModal{{ $marriage->id }}"
                                                title="عرض تفاصيل الزواج">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-circle btn-primary"
                                                data-toggle="modal" data-target="#editMarriageModal{{ $marriage->id }}"
                                                title="تعديل الزواج">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-circle btn-danger"
                                                data-toggle="modal" data-target="#deleteMarriageModal{{ $marriage->id }}"
                                                title="حذف الزواج">
                                                <i class="fas fa-trash"></i>
                                            </button>
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
                    <div class="text-center p-3">
                        <p class="mb-0">لا يوجد سجلات زواج حالياً. يمكنك إضافة سجل باستخدام الزر أعلاه.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- بطاقة الأبناء (مع تطبيق إعادة الترتيب) --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-child"></i>
                    الأبناء ({{ $children->count() }})
                </h6>
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#addChildModal">
                    <i class="fas fa-plus"></i> إضافة ابن/ابنة
                </button>
            </div>
            <div class="card-body">
                @if ($children->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    {{-- ✅ الخطوة 3: إضافة عمود للترتيب --}}
                                    <th style="width: 50px;">ترتيب</th>
                                    <th>الاسم</th>
                                    <th>الجنس</th>
                                    <th>العمر</th>
                                    {{-- <th>رقم الترتيب لتجربة</th> --}}
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            {{-- ✅ الخطوة 2: إضافة `id` و `data-url` لجسم الجدول باستخدام نفس المسار المطلوب --}}
                            <tbody id="children-sortable" data-url="{{ route('people.reorder') }}">
                                @foreach ($children as $child)
                                    {{-- ✅ إضافة `data-id` لكل صف --}}
                                    <tr data-id="{{ $child->id }}">
                                        {{-- ✅ إضافة أيقونة السحب --}}
                                        <td class="text-center" style="cursor: move;">
                                            <i class="fas fa-arrows-alt"></i>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $child->avatar }}" alt="{{ $child->full_name }}"
                                                    class="rounded-circle mr-2" width="40" height="40">
                                                <a
                                                    href="{{ route('people.show', $child->id) }}">{{ $child->full_name }}</a>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $child->gender == 'male' ? 'primary' : 'pink' }}">
                                                {{ $child->gender == 'male' ? 'ذكر' : 'أنثى' }}
                                            </span>
                                        </td>
                                        <td>{{ $child->age ?? 'غير معروف' }}</td>
                                        {{-- <td>{{ $child->display_order ?? '-' }}</td> --}}
                                        <td>
                                            <button type="button" class="btn btn-sm btn-circle btn-primary"
                                                data-toggle="modal" data-target="#editPersonModal{{ $child->id }}"
                                                title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            {{-- داخل حلقة الأبناء --}}
                                            <button type="button" class="btn btn-sm btn-circle btn-danger"
                                                data-toggle="modal"
                                                data-target="#deletePersonModal{{ $child->getKey() }}" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>

                                            <div class="modal fade" id="deletePersonModal{{ $child->getKey() }}"
                                                tabindex="-1" role="dialog"
                                                aria-labelledby="deletePersonModalLabel{{ $child->getKey() }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title"
                                                                id="deletePersonModalLabel{{ $child->getKey() }}">تأكيد
                                                                الحذف</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close"><span
                                                                    aria-hidden="true">&times;</span></button>
                                                        </div>

                                                        <form action="{{ route('people.destroy', $child->getKey()) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <div class="modal-body">
                                                                <div class="text-center">
                                                                    <i
                                                                        class="fas fa-exclamation-triangle fa-4x text-danger mb-3"></i>
                                                                    <h4>هل أنت متأكد من حذف هذا الشخص؟</h4>
                                                                    <p class="lead">{{ $child->full_name }}</p>
                                                                    @if (($child->children_count ?? $child->children()->count()) > 0)
                                                                        <div class="alert alert-warning">
                                                                            <strong>تحذير!</strong> هذا الشخص لديه
                                                                            {{ $child->children_count ?? $child->children()->count() }}
                                                                            أبناء. سيتم حذفهم أيضاً.
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">إلغاء</button>
                                                                <button type="submit" class="btn btn-danger">نعم،
                                                                    احذف</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            @include('dashboard.people.modals.edit', ['person' => $child])

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center p-3">
                        <p class="mb-0">لا يوجد أبناء حالياً. يمكنك إضافة ابن/ابنة باستخدام الزر أعلاه.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- بطاقة حسابات التواصل --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-address-book"></i>
                    حسابات التواصل
                </h6>
                <button class="btn btn-success btn-sm" data-toggle="modal"
                    data-target="#addContactAccountModal{{ $person->id }}">
                    <i class="fas fa-plus"></i> إضافة حساب تواصل
                </button>
            </div>
            <div class="card-body">
                @if ($person->contactAccounts->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>النوع</th>
                                    <th>القيمة</th>
                                    <th>التسمية</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($person->contactAccounts as $account)
                                    <tr>
                                        <td>
                                            <i class="fas {{ $account->icon }}"></i>
                                            {{ ucfirst($account->type) }}
                                        </td>
                                        <td>
                                            <a href="{{ $account->url }}" target="_blank">{{ $account->value }}</a>
                                        </td>
                                        <td>{{ $account->label ?? '-' }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-circle btn-primary"
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
                                                <button type="submit" class="btn btn-sm btn-circle btn-danger"
                                                    title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center p-3">
                        <p class="mb-0">لا توجد حسابات تواصل حالياً. يمكنك إضافة حساب باستخدام الزر أعلاه.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- بطاقة المواقع المتعددة --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-map-marker-alt"></i>
                    المواقع
                </h6>
                <button class="btn btn-success btn-sm" data-toggle="modal"
                    data-target="#addPersonLocationModal{{ $person->id }}">
                    <i class="fas fa-plus"></i> إضافة موقع
                </button>
            </div>
            <div class="card-body">
                @if ($person->locations->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>الموقع</th>
                                    <th>التسمية</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($person->locations as $location)
                                    @php
                                        $personLocation = $person->personLocations
                                            ->where('location_id', $location->id)
                                            ->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $location->name }}</td>
                                        <td>{{ $personLocation->label ?? '-' }}</td>
                                        <td>
                                            @if ($personLocation && $personLocation->is_primary)
                                                <span class="badge badge-success">أساسي</span>
                                            @else
                                                <span class="badge badge-secondary">ثانوي</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($personLocation)
                                                <button type="button" class="btn btn-sm btn-circle btn-primary"
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
                                                    <button type="submit" class="btn btn-sm btn-circle btn-danger"
                                                        title="حذف">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center p-3">
                        <p class="mb-0">لا توجد مواقع حالياً. يمكنك إضافة موقع باستخدام الزر أعلاه.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- بطاقة الرضاعة --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-baby"></i>
                    علاقات الرضاعة
                </h6>
                <a href="{{ route('breastfeeding.index') }}" class="btn btn-info btn-sm">
                    <i class="fas fa-cog"></i> إدارة الرضاعة
                </a>
            </div>
            <div class="card-body">
                @php
                    $nursingRelationships = $person->nursingRelationships()->with('breastfedChild')->get();
                    $breastfedRelationships = $person->breastfedRelationships()->with('nursingMother')->get();
                @endphp

                @if ($nursingRelationships->isNotEmpty() || $breastfedRelationships->isNotEmpty())
                    <div class="row">
                        {{-- إذا كان الشخص أم مرضعة --}}
                        @if ($nursingRelationships->isNotEmpty())
                            <div class="col-md-6">
                                <h6 class="text-success mb-3">
                                    <i class="fas fa-female"></i> كأم مرضعة
                                </h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>الطفل المرتضع</th>
                                                <th>المدة</th>
                                                <th>الحالة</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($nursingRelationships as $relationship)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $relationship->breastfedChild->avatar }}"
                                                                alt="{{ $relationship->breastfedChild->first_name }}"
                                                                class="rounded-circle mr-2" width="30"
                                                                height="30">
                                                            <a
                                                                href="{{ route('people.show', $relationship->breastfedChild->id) }}">
                                                                {{ $relationship->breastfedChild->full_name }}
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if ($relationship->duration_in_months)
                                                            <span
                                                                class="badge badge-info">{{ $relationship->duration_in_months }}
                                                                شهر</span>
                                                        @else
                                                            <span class="text-muted">غير محدد</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($relationship->is_active)
                                                            <span class="badge badge-success">نشط</span>
                                                        @else
                                                            <span class="badge badge-secondary">غير نشط</span>
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
                                <h6 class="text-info mb-3">
                                    <i class="fas fa-child"></i> كطفل مرتضع
                                </h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>الأم المرضعة</th>
                                                <th>المدة</th>
                                                <th>الحالة</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($breastfedRelationships as $relationship)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $relationship->nursingMother->avatar }}"
                                                                alt="{{ $relationship->nursingMother->first_name }}"
                                                                class="rounded-circle mr-2" width="30"
                                                                height="30">
                                                            <a
                                                                href="{{ route('people.show', $relationship->nursingMother->id) }}">
                                                                {{ $relationship->nursingMother->full_name }}
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if ($relationship->duration_in_months)
                                                            <span
                                                                class="badge badge-info">{{ $relationship->duration_in_months }}
                                                                شهر</span>
                                                        @else
                                                            <span class="text-muted">غير محدد</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($relationship->is_active)
                                                            <span class="badge badge-success">نشط</span>
                                                        @else
                                                            <span class="badge badge-secondary">غير نشط</span>
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
                    <div class="text-center p-3">
                        <i class="fas fa-baby fa-2x text-muted mb-3"></i>
                        <p class="mb-0">لا توجد علاقات رضاعة مسجلة لهذا الشخص.</p>
                        <a href="{{ route('breastfeeding.index') }}" class="btn btn-primary btn-sm mt-2">
                            <i class="fas fa-plus"></i> إضافة علاقة رضاعة
                        </a>
                    </div>
                @endif
            </div>
        </div>
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

    <script>
        // Script لتحديث اسم الملف عند اختياره في مودالات التعديل والإضافة
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
        });
        $('[data-toggle="tooltip"]').tooltip();
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // استهداف جميع قوائم الآباء في الصفحة
            const allFatherSelects = document.querySelectorAll('.js-father-select');

            allFatherSelects.forEach(fatherSelect => {
                fatherSelect.addEventListener('change', function() {
                    const fatherId = this.value;

                    // البحث عن قائمة الأم المرتبطة بنفس النافذة
                    const modal = this.closest('.modal-content');
                    const motherSelect = modal.querySelector('.js-mother-select');

                    if (!motherSelect) return;

                    motherSelect.innerHTML = '<option value="">-- جار التحميل --</option>';

                    if (!fatherId) {
                        motherSelect.innerHTML = '<option value="">-- اختر الأم --</option>';
                        return;
                    }

                    fetch(`{{ url('/people') }}/${fatherId}/wives`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(wives => {
                            motherSelect.innerHTML =
                                '<option value="">-- اختر الأم --</option>';
                            wives.forEach(wife => {
                                const option = document.createElement('option');
                                option.value = wife.id;
                                option.textContent = wife.full_name;
                                motherSelect.appendChild(option);
                            });
                        })
                        .catch(error => {
                            console.error('Error fetching wives:', error);
                            motherSelect.innerHTML = '<option value="">-- حدث خطأ --</option>';
                        });
                });
            });
        });
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
