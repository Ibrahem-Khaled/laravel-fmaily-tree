@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة --}}
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">ملف {{ $person->full_name }}</h1>
            {{-- ✅ الزر الجديد لفتح النافذة المنبثقة --}}
            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#addPersonsOutsideTheFamilyTreeModal">
                <i class="fas fa-user-plus"></i> إضافة شخص من خارج العائلة
            </button>
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
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <img src="{{ $person->avatar }}" alt="{{ $person->full_name }}"
                            class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
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
                                <th>المهنة</th>
                                <td>{{ $person->occupation ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>المكان</th>
                                <td>{{ $person->location ?? '-' }}</td>
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
                @if ($spouses->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>الاسم</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($spouses as $spouse)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $spouse->avatar }}" alt="{{ $spouse->full_name }}"
                                                    class="rounded-circle mr-2" width="40" height="40">
                                                <a
                                                    href="{{ route('people.show', $spouse->id) }}">{{ $spouse->full_name }}</a>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-circle btn-primary"
                                                data-toggle="modal" data-target="#editPersonModal{{ $spouse->id }}"
                                                title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </td>
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


        {{-- بطاقة الأبناء --}}
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
                                    <th>الاسم</th>
                                    <th>الجنس</th>
                                    <th>العمر</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($children as $child)
                                    <tr>
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
                                        <td>
                                            <button type="button" class="btn btn-sm btn-circle btn-primary"
                                                data-toggle="modal" data-target="#editPersonModal{{ $child->id }}"
                                                title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </button>
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

    </div>

    {{-- مودال إضافة ابن/ابنة جديد --}}
    @include('dashboard.people.modals.add_child')

    {{-- مودال إضافة زواج جديد --}}
    @include('dashboard.people.modals.add_marriage')

    {{-- ✅ تضمين المودال الجديد الذي أنشأناه --}}
    @include('dashboard.people.modals.add-outside-the-family')

    {{-- تضمين مودالات التعديل لكل الأشخاص المعروضين في الصفحة --}}
    @include('dashboard.people.modals.edit', ['person' => $person])
    @foreach ($spouses as $spouse)
        @include('dashboard.people.modals.edit', ['person' => $spouse])
    @endforeach
    @foreach ($children as $child)
        @include('dashboard.people.modals.edit', ['person' => $child])
    @endforeach

@endsection

@push('scripts')
    <script>
        // Script لتحديث اسم الملف عند اختياره في مودالات التعديل والإضافة
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
        });
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

                    // إذا لم يتم العثور على قائمة أم، توقف
                    if (!motherSelect) return;

                    motherSelect.innerHTML = '<option value="">-- جار التحميل --</option>';

                    if (!fatherId) {
                        motherSelect.innerHTML = '<option value="">-- اختر الأم --</option>';
                        return;
                    }

                    // إرسال الطلب بنفس الطريقة السابقة
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
@endpush
