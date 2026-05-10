@extends('layouts.app')

@section('title', 'إدارة صفحة الدعم الفني')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1 text-gray-800">
                <i class="fas fa-life-ring text-primary mr-2"></i>صفحة الدعم الفني
            </h1>
            <p class="text-muted mb-0 small">إدارة النصوص، قنوات التواصل، والأسئلة الشائعة كما تظهر للزوار في الموقع العام.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('support.index') }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm shadow-sm">
                <i class="fas fa-external-link-alt mr-1"></i> معاينة الصفحة العامة
            </a>
            @can('site-content.update')
            <a href="{{ route('dashboard.support.channels.create') }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-plus mr-1"></i> قناة تواصل
            </a>
            <a href="{{ route('dashboard.support.faqs.create') }}" class="btn btn-success btn-sm shadow-sm">
                <i class="fas fa-plus mr-1"></i> سؤال شائع
            </a>
            @endcan
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- إعدادات الصفحة --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-cog mr-2"></i>إعدادات الصفحة</h6>
        </div>
        <div class="card-body">
            @can('site-content.update')
            <form action="{{ route('dashboard.support.settings.update') }}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="page_title">عنوان الصفحة</label>
                        <input type="text" name="page_title" id="page_title" required maxlength="255"
                            class="form-control @error('page_title') is-invalid @enderror"
                            value="{{ old('page_title', $settings->page_title) }}">
                        @error('page_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="page_subtitle">عنوان فرعي (اختياري)</label>
                        <input type="text" name="page_subtitle" id="page_subtitle" maxlength="500"
                            class="form-control @error('page_subtitle') is-invalid @enderror"
                            value="{{ old('page_subtitle', $settings->page_subtitle) }}">
                        @error('page_subtitle')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="intro_html">مقدمة (HTML اختياري)</label>
                    <textarea name="intro_html" id="intro_html" rows="5"
                        class="form-control @error('intro_html') is-invalid @enderror"
                        placeholder="يمكن إدراج فقرات أو روابط بسيطة تظهر أسفل الهيدر">{{ old('intro_html', $settings->intro_html) }}</textarea>
                    @error('intro_html')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="form-text text-muted">يُعرض للزائر أسفل منطقة العنوان؛ استخدمه لشرح أوقات الاستجابة أو إرشادات عامة.</small>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> حفظ الإعدادات</button>
            </form>
            @else
                <p class="mb-0 text-muted">ليس لديك صلاحية تعديل المحتوى.</p>
            @endcan
        </div>
    </div>

    {{-- قنوات التواصل --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-comments mr-2"></i>قنوات التواصل</h6>
            @can('site-content.update')
            <a href="{{ route('dashboard.support.channels.create') }}" class="btn btn-sm btn-primary">إضافة قناة</a>
            @endcan
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>الترتيب</th>
                            <th>التسمية</th>
                            <th>النوع</th>
                            <th>القيمة</th>
                            <th>الحالة</th>
                            <th class="text-left">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($channels as $ch)
                            <tr>
                                <td>{{ $ch->sort_order }}</td>
                                <td class="font-weight-bold">{{ $ch->label }}</td>
                                <td>{{ \App\Models\SupportChannel::typeLabels()[$ch->type] ?? $ch->type }}</td>
                                <td><small class="text-muted">{{ Str::limit($ch->value, 48) }}</small></td>
                                <td>
                                    @if($ch->is_active)
                                        <span class="badge badge-success">نشط</span>
                                    @else
                                        <span class="badge badge-secondary">معطّل</span>
                                    @endif
                                </td>
                                <td class="text-left whitespace-nowrap">
                                    @can('site-content.update')
                                    <form action="{{ route('dashboard.support.channels.toggle', $ch) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary" title="تبديل">تفعيل/تعطيل</button>
                                    </form>
                                    <a href="{{ route('dashboard.support.channels.edit', $ch) }}" class="btn btn-sm btn-info">تعديل</a>
                                    <form action="{{ route('dashboard.support.channels.destroy', $ch) }}" method="POST" class="d-inline" onsubmit="return confirm('حذف هذه القناة؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">حذف</button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">لا توجد قنوات بعد. أضف قناة تواصل لعرضها للزوار.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- الأسئلة الشائعة --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-question-circle mr-2"></i>الأسئلة الشائعة</h6>
            @can('site-content.update')
            <a href="{{ route('dashboard.support.faqs.create') }}" class="btn btn-sm btn-success">إضافة سؤال</a>
            @endcan
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>الترتيب</th>
                            <th>السؤال</th>
                            <th>الحالة</th>
                            <th class="text-left">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($faqs as $faq)
                            <tr>
                                <td>{{ $faq->sort_order }}</td>
                                <td>{{ Str::limit($faq->question, 70) }}</td>
                                <td>
                                    @if($faq->is_active)
                                        <span class="badge badge-success">نشط</span>
                                    @else
                                        <span class="badge badge-secondary">معطّل</span>
                                    @endif
                                </td>
                                <td class="text-left whitespace-nowrap">
                                    @can('site-content.update')
                                    <form action="{{ route('dashboard.support.faqs.toggle', $faq) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">تفعيل/تعطيل</button>
                                    </form>
                                    <a href="{{ route('dashboard.support.faqs.edit', $faq) }}" class="btn btn-sm btn-info">تعديل</a>
                                    <form action="{{ route('dashboard.support.faqs.destroy', $faq) }}" method="POST" class="d-inline" onsubmit="return confirm('حذف هذا السؤال؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">حذف</button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">لا توجد أسئلة بعد.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
