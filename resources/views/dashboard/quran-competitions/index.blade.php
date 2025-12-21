@extends('layouts.app')

@section('title', 'إدارة مسابقات القرآن الكريم')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-quran text-primary mr-2"></i>إدارة مسابقات القرآن الكريم
            </h1>
            <p class="text-muted mb-0">قم بإدارة مسابقات القرآن الكريم وإضافة مسابقات جديدة</p>
        </div>
        <a href="{{ route('dashboard.quran-competitions.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle mr-2"></i>إضافة مسابقة جديدة
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Competitions Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 font-weight-bold text-primary">
                <i class="fas fa-list mr-2"></i>المسابقات ({{ $competitions->count() }})
            </h6>
        </div>
        <div class="card-body">
            @if($competitions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>العنوان</th>
                                <th>السنة الهجرية</th>
                                <th>تاريخ البداية</th>
                                <th>تاريخ النهاية</th>
                                <th>الفائزون</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($competitions as $competition)
                                <tr>
                                    <td>
                                        <strong>{{ $competition->title }}</strong>
                                        @if($competition->cover_image)
                                            <br><small class="text-muted"><i class="fas fa-image"></i> يوجد صورة</small>
                                        @endif
                                    </td>
                                    <td>{{ $competition->hijri_year }} هـ</td>
                                    <td>{{ $competition->start_date ? $competition->start_date->format('Y-m-d') : '-' }}</td>
                                    <td>{{ $competition->end_date ? $competition->end_date->format('Y-m-d') : '-' }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $competition->winners->count() }} فائز</span>
                                    </td>
                                    <td>
                                        @if($competition->is_active)
                                            <span class="badge badge-success">نشط</span>
                                        @else
                                            <span class="badge badge-secondary">معطل</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('dashboard.quran-competitions.show', $competition) }}" class="btn btn-sm btn-info" title="عرض التفاصيل">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('dashboard.quran-competitions.edit', $competition) }}" class="btn btn-sm btn-primary" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('dashboard.quran-competitions.destroy', $competition) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه المسابقة؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="حذف">
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
                <div class="text-center py-5">
                    <i class="fas fa-quran fa-3x text-muted mb-3"></i>
                    <p class="text-muted">لا توجد مسابقات حالياً</p>
                    <a href="{{ route('dashboard.quran-competitions.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle mr-2"></i>إضافة مسابقة جديدة
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

