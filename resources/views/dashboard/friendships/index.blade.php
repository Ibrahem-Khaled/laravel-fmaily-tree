@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">إدارة الأصدقاء</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">لوحة التحكم</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">الأصدقاء</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts')

        {{-- إحصائيات الأصدقاء --}}
        <div class="row mb-4">
            <x-stats-card icon="fas fa-user-friends" title="إجمالي الصداقات" :value="$stats['total']" color="primary" />
        </div>

        {{-- بطاقة قائمة الأصدقاء --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">قائمة الصداقات</h6>
                <a href="{{ route('friendships.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> إضافة صداقة جديدة
                </a>
            </div>
            <div class="card-body">
                {{-- نموذج البحث والفلترة --}}
                <form action="{{ route('friendships.index') }}" method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="search">بحث:</label>
                                <input type="text" id="search" name="search" class="form-control" 
                                       value="{{ $search }}" placeholder="ابحث بالاسم...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="person_id">فلترة حسب الشخص:</label>
                                <select id="person_id" name="person_id" class="form-control">
                                    <option value="">الكل</option>
                                    @foreach($persons as $person)
                                        <option value="{{ $person->id }}" {{ $personId == $person->id ? 'selected' : '' }}>
                                            {{ $person->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> بحث
                                    </button>
                                    <a href="{{ route('friendships.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-redo"></i> إعادة تعيين
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- جدول الأصدقاء --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="friendshipsTable">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>الشخص</th>
                                <th>الصديق</th>
                                <th>الوصف</th>
                                <th>تاريخ الإنشاء</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($friendships as $friendship)
                                <tr>
                                    <td>{{ $friendships->firstItem() + $loop->index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($friendship->person->photo_url)
                                                <img src="{{ $friendship->person->photo_url }}" 
                                                     alt="{{ $friendship->person->first_name }}" 
                                                     class="rounded-circle mr-2" width="40" height="40">
                                            @else
                                                <div class="rounded-circle mr-2 bg-light d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $friendship->person->full_name }}</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($friendship->friend->photo_url)
                                                <img src="{{ $friendship->friend->photo_url }}" 
                                                     alt="{{ $friendship->friend->first_name }}" 
                                                     class="rounded-circle mr-2" width="40" height="40">
                                            @else
                                                <div class="rounded-circle mr-2 bg-light d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $friendship->friend->full_name }}</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($friendship->description)
                                            <span class="text-muted">{{ Str::limit($friendship->description, 50) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $friendship->created_at->format('Y/m/d') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('friendships.edit', $friendship) }}" 
                                               class="btn btn-sm btn-info" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('friendships.destroy', $friendship) }}" 
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذه الصداقة؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">لا توجد صداقات مسجلة</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- الترقيم --}}
                <div class="d-flex justify-content-center">
                    {{ $friendships->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // تفعيل DataTable
            $('#friendshipsTable').DataTable({
                paging: false,
                searching: false,
                info: false,
                ordering: true,
                language: {
                    url: "{{ asset('js/arabic.json') }}"
                }
            });
        });
    </script>
@endpush

