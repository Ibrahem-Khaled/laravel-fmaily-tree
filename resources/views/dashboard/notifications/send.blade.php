@extends('layouts.app')

@section('title', 'إرسال دعوة واتساب')

@push('styles')
<style>
    .step-card {
        border-right: 4px solid #4e73df;
        transition: all 0.3s ease;
    }
    .step-card:hover {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58,59,69,.15) !important;
    }
    .step-number {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #4e73df;
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
    }
    .var-btn {
        border: 1px dashed #4e73df;
        color: #4e73df;
        background: #f8f9fc;
        cursor: pointer;
        font-family: monospace;
        font-size: 13px;
        padding: 4px 10px;
        border-radius: 4px;
        transition: all 0.2s;
    }
    .var-btn:hover {
        background: #4e73df;
        color: #fff;
    }
    .media-upload-box {
        background: #f8f9fc;
        border: 2px dashed #d1d3e2;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s;
    }
    .media-upload-box:hover {
        border-color: #4e73df;
    }
    .media-upload-box .upload-icon {
        font-size: 2.5rem;
        color: #b7b9cc;
        margin-bottom: 10px;
    }
    .preview-box {
        background: #e8f5e9;
        border: 1px solid #a5d6a7;
        border-radius: 8px;
        padding: 12px 16px;
        white-space: pre-wrap;
        direction: rtl;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">
                <i class="fab fa-whatsapp text-success mr-2"></i>إرسال دعوة واتساب
            </h1>
            <p class="text-muted mb-0">أرسل رسائل مخصصة عبر واتساب لأفراد أو مجموعات</p>
        </div>
        <a href="{{ route('dashboard.notifications.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right mr-1"></i>العودة
        </a>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-1"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-1"></i>{{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <form action="{{ route('dashboard.notifications.send.submit') }}" method="post" enctype="multipart/form-data" id="sendForm">
        @csrf

        {{-- Step 1: Recipients --}}
        <div class="card shadow-sm mb-4 step-card">
            <div class="card-body">
                <h5 class="mb-3"><span class="step-number">1</span> <span class="mr-2">اختر المستلمين</span></h5>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="font-weight-bold">طريقة الاختيار</label>
                        <div class="mt-2">
                            <div class="custom-control custom-radio mb-2">
                                <input type="radio" id="typePersons" name="recipient_type" value="persons" class="custom-control-input" checked>
                                <label class="custom-control-label" for="typePersons">
                                    <i class="fas fa-user-plus text-primary mr-1"></i>أشخاص محددون
                                </label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="typeGroup" name="recipient_type" value="group" class="custom-control-input">
                                <label class="custom-control-label" for="typeGroup">
                                    <i class="fas fa-users text-success mr-1"></i>مجموعة جاهزة
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        {{-- persons select --}}
                        <div id="personsWrap">
                            <label class="font-weight-bold">ابحث واختر الأشخاص <small class="text-muted">(لديهم واتساب)</small></label>
                            <select name="person_ids[]" id="person_ids" class="no-search" multiple style="width:100%;"></select>
                        </div>
                        {{-- group select --}}
                        <div id="groupWrap" style="display:none;">
                            <label class="font-weight-bold">اختر المجموعة</label>
                            <select name="group_id" id="group_id" class="no-search" style="width:100%;">
                                <option value="">-- اختر مجموعة --</option>
                                @foreach($groups as $g)
                                    <option value="{{ $g->id }}">{{ $g->name }} ({{ $g->persons_count }} شخص)</option>
                                @endforeach
                            </select>
                            @if($groups->isEmpty())
                                <small class="text-danger d-block mt-1">لا توجد مجموعات. <a href="{{ route('dashboard.notification-groups.create') }}">أنشئ واحدة</a></small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Step 2: Message --}}
        <div class="card shadow-sm mb-4 step-card">
            <div class="card-body">
                <h5 class="mb-3"><span class="step-number">2</span> <span class="mr-2">اكتب الرسالة</span></h5>

                <div class="form-group">
                    <label class="font-weight-bold" for="title">عنوان <small class="text-muted">(اختياري - للسجل فقط)</small></label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" placeholder="مثال: دعوة مناسبة عائلية">
                </div>

                <div class="form-group">
                    <label class="font-weight-bold" for="body">نص الرسالة</label>
                    <textarea name="body" id="body" class="form-control" rows="5" placeholder="السلام عليكم {name}، يسرنا دعوتكم لحضور...">{{ old('body') }}</textarea>
                </div>

                <div class="mb-3">
                    <span class="text-muted small ml-2">إدراج متغير في مكان المؤشر:</span>
                    <button type="button" class="var-btn insert-var" data-var="{name}"><i class="fas fa-user fa-xs mr-1"></i>{name}</button>
                    <button type="button" class="var-btn insert-var" data-var="{first_name}"><i class="fas fa-user fa-xs mr-1"></i>{first_name}</button>
                    <button type="button" class="var-btn insert-var" data-var="{full_name}"><i class="fas fa-id-card fa-xs mr-1"></i>{full_name}</button>
                </div>

                <div class="form-group">
                    <button type="button" class="btn btn-outline-info btn-sm" id="previewBtn">
                        <i class="fas fa-eye mr-1"></i>معاينة الرسالة
                    </button>
                    <div id="previewResult" class="mt-2" style="display:none;"></div>
                </div>
            </div>
        </div>

        {{-- Step 3: Media --}}
        <div class="card shadow-sm mb-4 step-card">
            <div class="card-body">
                <h5 class="mb-3"><span class="step-number">3</span> <span class="mr-2">مرفق <small class="text-muted">(اختياري)</small></span></h5>

                <div class="row mb-3">
                    <div class="col-md-3 col-6 mb-2">
                        <label class="btn btn-outline-secondary btn-block media-type-btn active" data-value="">
                            <input type="radio" name="media_type" value="" class="d-none" checked>
                            <i class="fas fa-font d-block mb-1" style="font-size:1.3rem;"></i>
                            نص فقط
                        </label>
                    </div>
                    <div class="col-md-3 col-6 mb-2">
                        <label class="btn btn-outline-primary btn-block media-type-btn" data-value="image">
                            <input type="radio" name="media_type" value="image" class="d-none">
                            <i class="fas fa-image d-block mb-1" style="font-size:1.3rem;"></i>
                            صورة
                        </label>
                    </div>
                    <div class="col-md-3 col-6 mb-2">
                        <label class="btn btn-outline-danger btn-block media-type-btn" data-value="video">
                            <input type="radio" name="media_type" value="video" class="d-none">
                            <i class="fas fa-video d-block mb-1" style="font-size:1.3rem;"></i>
                            فيديو
                        </label>
                    </div>
                    <div class="col-md-3 col-6 mb-2">
                        <label class="btn btn-outline-warning btn-block media-type-btn" data-value="voice">
                            <input type="radio" name="media_type" value="voice" class="d-none">
                            <i class="fas fa-microphone d-block mb-1" style="font-size:1.3rem;"></i>
                            صوت
                        </label>
                    </div>
                </div>

                <div id="mediaFileWrap" style="display:none;">
                    <div class="media-upload-box">
                        <div class="upload-icon"><i class="fas fa-cloud-upload-alt" id="mediaIcon"></i></div>
                        <p class="font-weight-bold mb-1" id="mediaFileLabel">ارفع الملف</p>
                        <input type="file" name="media" id="media" class="form-control-file d-inline-block" style="max-width:320px;" accept="">
                        <p class="text-muted small mt-2 mb-0">الحد الأقصى 16 ميجابايت</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="text-center mb-5">
            <button type="submit" class="btn btn-success btn-lg px-5 shadow">
                <i class="fab fa-whatsapp mr-2"></i>إرسال الآن
            </button>
            <a href="{{ route('dashboard.notifications.index') }}" class="btn btn-light btn-lg px-4 mr-2">إلغاء</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
$(function() {

    // ===== Step 1: Toggle Recipients =====
    $('input[name="recipient_type"]').on('change', function() {
        var isGroup = $(this).val() === 'group';
        $('#personsWrap').toggle(!isGroup);
        $('#groupWrap').toggle(isGroup);
    });

    // Init person search with Select2 Ajax
    var $personIds = $('#person_ids');
    if ($personIds.data('select2')) $personIds.select2('destroy');
    $personIds.select2({
        placeholder: 'ابحث بالاسم...',
        dir: 'rtl',
        width: '100%',
        ajax: {
            url: '{{ route("dashboard.notifications.persons-with-whatsapp") }}',
            dataType: 'json',
            delay: 300,
            data: function(params) { return { q: params.term || '' }; },
            processResults: function(data) {
                var list = (data && data.persons) ? data.persons : [];
                return { results: list.map(function(p) { return { id: p.id, text: p.full_name }; }) };
            },
            cache: true
        },
        minimumInputLength: 0
    });

    // Init group select as plain Select2 (no ajax)
    var $groupId = $('#group_id');
    if ($groupId.data('select2')) $groupId.select2('destroy');
    $groupId.select2({ placeholder: '-- اختر مجموعة --', dir: 'rtl', width: '100%' });

    // ===== Step 2: Insert variable buttons =====
    $('.insert-var').on('click', function() {
        var ta = document.getElementById('body');
        if (!ta) return;
        var v = $(this).data('var') || '';
        var start = ta.selectionStart, end = ta.selectionEnd;
        ta.value = ta.value.slice(0, start) + v + ta.value.slice(end);
        ta.selectionStart = ta.selectionEnd = start + v.length;
        ta.focus();
    });

    // Preview
    $('#previewBtn').on('click', function() {
        var personId = ($personIds.val() && $personIds.val().length) ? $personIds.val()[0] : null;
        var $result = $('#previewResult');
        if (!personId) {
            $result.html('<div class="alert alert-warning py-2"><i class="fas fa-info-circle mr-1"></i>اختر شخصاً أولاً لمعاينة الرسالة.</div>').show();
            return;
        }
        $.ajax({
            url: '{{ route("dashboard.notifications.preview-message") }}',
            method: 'POST',
            data: JSON.stringify({ body: $('#body').val(), person_id: parseInt(personId) }),
            contentType: 'application/json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(d) {
                $result.html('<div class="preview-box"><i class="fab fa-whatsapp text-success mr-1"></i>' + (d.preview || '(فارغ)') + '</div>').show();
            },
            error: function() {
                $result.html('<div class="alert alert-danger py-2">فشلت المعاينة.</div>').show();
            }
        });
    });

    // ===== Step 3: Media type toggle =====
    var mediaLabels = { image: 'ارفع صورة (jpg, png, gif, webp)', video: 'ارفع فيديو (mp4)', voice: 'ارفع ملف صوت (ogg, mp3)' };
    var mediaAccepts = { image: 'image/*', video: 'video/*', voice: 'audio/*' };
    var mediaIcons = { image: 'fa-image', video: 'fa-video', voice: 'fa-microphone' };

    $('.media-type-btn').on('click', function() {
        // Update active style
        $('.media-type-btn').removeClass('active');
        $(this).addClass('active');

        var v = $(this).data('value') || '';
        if (v) {
            $('#mediaFileWrap').slideDown(200);
            $('#mediaFileLabel').text(mediaLabels[v] || 'ارفع الملف');
            $('#media').attr('accept', mediaAccepts[v] || '');
            $('#mediaIcon').attr('class', 'fas ' + (mediaIcons[v] || 'fa-cloud-upload-alt'));
        } else {
            $('#mediaFileWrap').slideUp(200);
            $('#media').val('').attr('accept', '');
        }
    });
});
</script>
@endpush
@endsection
