@extends('layouts.app')

@section('content')
    <div class="container-fluid" dir="rtl">
        {{-- عنوان ومسار تنقل --}}
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">الأشخاص المرتبطون بـ: {{ $padge->name }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('padges.index') }}">الشارات</a></li>
                    <li class="breadcrumb-item active" aria-current="page">الأشخاص</li>
                </ol>
            </nav>
        </div>

        @include('components.alerts')

        <div class="row">
            {{-- عمود إضافة أشخاص --}}
            <div class="col-lg-5 mb-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">إضافة أشخاص إلى الشارة</h6>
                    </div>
                    <div class="card-body">
                        {{-- فورم فلترة السيرفر لقائمة المتاحين --}}
                        <form action="{{ route('padges.people.index', $padge) }}" method="GET" class="mb-3">
                            <div class="form-group">
                                <label>فلترة بالاسم/الإيميل</label>
                                <input type="text" name="q" class="form-control" value="{{ $q }}"
                                    placeholder="ابحث...">
                            </div>
                            <button class="btn btn-outline-secondary">تحديث القائمة</button>
                        </form>

                        {{-- فورم الإضافة --}}
                        <form action="{{ route('padges.people.attach', $padge) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>اختر الأشخاص (يمكن اختيار متعدد)</label>
                                <select name="people[]" class="form-control" multiple size="12" required>
                                    @forelse($availablePeople as $p)
                                        <option value="{{ $p->id }}">{{ $p->full_name }}
                                        </option>
                                    @empty
                                        <option disabled>لا يوجد أشخاص متاحون (أضف فلتر مختلف أو أضف أشخاصًا جددًا).
                                        </option>
                                    @endforelse
                                </select>
                                <small class="text-muted d-block mt-1">
                                    الأشخاص المرتبطون بالفعل بالشارة لا يظهرون في هذه القائمة.
                                </small>
                            </div>
                            <button type="submit" class="btn btn-primary">إضافة المحددين</button>
                        </form>

                        {{-- ترقيم المتاحين --}}
                        <div class="mt-3">
                            {{ $availablePeople->links() }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- عمود الأشخاص المرتبطين حالياً --}}
            <div class="col-lg-7 mb-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">الأشخاص المرتبطون حالياً</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width:60px">#</th>
                                        <th>الاسم</th>
                                        <th style="width:140px">الحالة</th>
                                        <th style="width:180px">إجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($people as $i => $person)
                                        <tr>
                                            <td>{{ $people->firstItem() + $i }}</td>
                                            <td>
                                                {{ $person->full_name }}

                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $person->pivot->is_active ? 'success' : 'secondary' }}">
                                                    {{ $person->pivot->is_active ? 'مُفعّل' : 'مُعطّل' }}
                                                </span>
                                            </td>
                                            <td class="d-flex">
                                                <form action="{{ route('padges.people.toggle', [$padge, $person]) }}"
                                                    method="POST" class="mr-2">
                                                    @csrf @method('PATCH')
                                                    <button class="btn btn-sm btn-warning">تبديل الحالة</button>
                                                </form>

                                                <form action="{{ route('padges.people.detach', [$padge, $person]) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('متأكد من إزالة هذا الشخص من الشارة؟')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-danger">إزالة</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">لا يوجد أشخاص مرتبطون بعد.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- ترقيم المرتبطين --}}
                        <div class="d-flex justify-content-center">
                            {{ $people->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
