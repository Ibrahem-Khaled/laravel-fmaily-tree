@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">إدارة القصص</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item active" aria-current="page">القصص</li>
                </ol>
            </nav>
        </div>

        @include('components.alerts')

        {{-- إحصائيات القصص --}}
        <div class="row mb-4">
            <x-stats-card icon="fas fa-book" title="إجمالي القصص" :value="$storiesCount" color="primary" />
            <x-stats-card icon="fas fa-file-alt" title="قصص بالنص" :value="$storiesWithContent" color="info" />
            <x-stats-card icon="fas fa-headphones" title="قصص بالصوت" :value="$storiesWithAudio" color="success" />
            <x-stats-card icon="fas fa-video" title="قصص بالفيديو" :value="$storiesWithVideo" color="warning" />
        </div>

        {{-- بطاقة قائمة القصص --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">قائمة القصص</h6>
                <button class="btn btn-primary" data-toggle="modal" data-target="#createStoryModal">
                    <i class="fas fa-plus fa-sm text-white-50"></i> إضافة قصة جديدة
                </button>
            </div>
            <div class="card-body">
                {{-- نموذج البحث --}}
                <form action="{{ route('stories.index') }}" method="GET" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="ابحث بالعنوان أو المحتوى أو صاحب القصة أو الراوي..."
                            value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </form>

                {{-- جدول القصص --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="storiesTable">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>العنوان</th>
                                <th>صاحب القصة</th>
                                <th>الرواة</th>
                                <th>المحتوى</th>
                                <th>الصوت/الفيديو</th>
                                <th>تاريخ الإنشاء</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stories as $story)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $story->title }}</strong>
                                    </td>
                                    <td>
                                        @if($story->storyOwner)
                                            {{ $story->storyOwner->full_name }}
                                        @else
                                            <span class="text-muted">غير محدد</span>
                                        @endif
                                    </td>
                                    <td>
                                        @forelse($story->narrators as $narrator)
                                            <span class="badge badge-info mb-1">{{ $narrator->full_name }}</span>
                                        @empty
                                            <span class="text-muted">لا يوجد</span>
                                        @endforelse
                                    </td>
                                    <td>
                                        @if($story->hasContent())
                                            <span class="badge badge-success">نص</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($story->hasAudio())
                                            <span class="badge badge-primary mb-1"><i class="fas fa-headphones"></i> صوت</span>
                                        @endif
                                        @if($story->hasExternalVideo())
                                            <span class="badge badge-danger mb-1"><i class="fab fa-youtube"></i> يوتيوب</span>
                                        @endif
                                        @if($story->hasUploadedVideo())
                                            <span class="badge badge-warning mb-1"><i class="fas fa-video"></i> فيديو</span>
                                        @endif
                                        @if(!$story->hasAudio() && !$story->hasVideo())
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $story->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" data-toggle="modal"
                                            data-target="#editStoryModal{{ $story->id }}" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#deleteStoryModal{{ $story->id }}" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @include('dashboard.stories.modals.edit', ['story' => $story])
                                @include('dashboard.stories.modals.delete', ['story' => $story])
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">لا توجد قصص لعرضها حالياً.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- الترقيم --}}
                <div class="d-flex justify-content-center">
                    {{ $stories->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- مودال إضافة قصة جديدة --}}
    @include('dashboard.stories.modals.create')
@endsection

@push('scripts')
    <script>
        // لعرض اسم الملف المختار في حقل رفع الملفات
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
        });

        // للتبديل بين رابط يوتيوب ورفع ملف
        $('#videoType').on('change', function() {
            const type = $(this).val();
            if (type === 'url') {
                $('#videoUrlGroup').show();
                $('#videoFileGroup').hide();
            } else if (type === 'file') {
                $('#videoUrlGroup').hide();
                $('#videoFileGroup').show();
            } else {
                $('#videoUrlGroup').hide();
                $('#videoFileGroup').hide();
            }
        });
    </script>
@endpush

