<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إرسال دعوة واتساب</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/ar.js"></script>

    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 50%, #a7f3d0 100%);
            min-height: 100vh;
        }

        h1, h2, h3, h4 {
            font-family: 'Amiri', serif;
        }

        .section-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .section-card:hover {
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.12);
        }

        .step-badge {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-center;
            color: white;
            font-weight: bold;
            font-size: 18px;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .var-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 2px solid #86efac;
            border-radius: 10px;
            color: #065f46;
            font-family: 'Courier New', monospace;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .var-badge:hover {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border-color: #059669;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
        }

        .media-option {
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .media-option input[type="radio"] {
            position: absolute;
            opacity: 0;
        }

        .media-option .option-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            padding: 20px;
            background: white;
            border: 3px solid #e5e7eb;
            border-radius: 16px;
            transition: all 0.3s ease;
        }

        .media-option input[type="radio"]:checked + .option-content {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border-color: #10b981;
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.2);
        }

        .media-option:hover .option-content {
            transform: translateY(-4px);
            border-color: #10b981;
        }

        .upload-zone {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 3px dashed #86efac;
            border-radius: 16px;
            padding: 32px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .upload-zone:hover {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            border-color: #10b981;
        }

        .btn-submit {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(16, 185, 129, 0.3);
        }

        .select2-container--default .select2-selection--multiple {
            border: 2px solid #d1d5db !important;
            border-radius: 12px !important;
            min-height: 50px !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #10b981 !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #10b981 !important;
            border-color: #059669 !important;
            color: white !important;
            padding: 6px 12px !important;
            border-radius: 8px !important;
        }

        .radio-card {
            position: relative;
            cursor: pointer;
        }

        .radio-card input[type="radio"] {
            position: absolute;
            opacity: 0;
        }

        .radio-card label {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 20px;
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .radio-card input[type="radio"]:checked + label {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border-color: #10b981;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.15);
        }

        .radio-card:hover label {
            border-color: #10b981;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>
</head>

<body class="antialiased">
    @include('partials.main-header')

    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            {{-- Header --}}
            <div class="text-center mb-8 animate-fade-in">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-green-400 to-green-600 shadow-2xl mb-4">
                    <i class="fab fa-whatsapp text-4xl text-white"></i>
                </div>
                <h1 class="text-4xl font-extrabold text-gray-900 mb-2">
                    إرسال دعوة واتساب
                </h1>
                <p class="text-lg text-gray-600">
                    أرسل رسائل مخصصة عبر واتساب بكل سهولة
                </p>
                <div class="mt-4 flex flex-wrap items-center justify-center gap-4 text-sm">
                    <span class="flex items-center gap-2 text-gray-700">
                        <i class="fas fa-user-circle text-green-600"></i>
                        <strong>{{ Auth::user()->name }}</strong>
                    </span>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('invitations.dashboard') }}" class="flex items-center gap-2 text-gray-700 hover:text-green-600 font-medium">
                        <i class="fas fa-home"></i>
                        الرئيسية
                    </a>
                    <a href="{{ route('invitations.groups.index') }}" class="flex items-center gap-2 text-gray-700 hover:text-blue-600 font-medium">
                        <i class="fas fa-users"></i>
                        المجموعات
                    </a>
                    <a href="{{ route('invitations.logs') }}" class="flex items-center gap-2 text-gray-700 hover:text-purple-600 font-medium">
                        <i class="fas fa-history"></i>
                        السجل
                    </a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('invitations.logout') }}" class="flex items-center gap-2 text-red-600 hover:text-red-700 font-medium">
                        <i class="fas fa-sign-out-alt"></i>
                        خروج
                    </a>
                </div>
            </div>

            {{-- Alerts --}}
            @if(session('success'))
                <div class="mb-6 rounded-xl bg-green-50 border-l-4 border-green-400 p-4 animate-fade-in">
                    <div class="flex">
                        <i class="fas fa-check-circle text-green-400 text-xl mr-3"></i>
                        <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 rounded-xl bg-red-50 border-l-4 border-red-400 p-4 animate-fade-in">
                    <div class="flex">
                        <i class="fas fa-exclamation-circle text-red-400 text-xl mr-3"></i>
                        <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 rounded-xl bg-red-50 border-l-4 border-red-400 p-4 animate-fade-in">
                    <ul class="list-disc list-inside space-y-1 text-sm text-red-700">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('invitations.send.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Step 1: Recipients --}}
                <div class="section-card rounded-2xl p-6 lg:p-8 mb-6 animate-fade-in">
                    <div class="flex items-center gap-4 mb-6">
                        <span class="step-badge">1</span>
                        <h2 class="text-2xl font-bold text-gray-800">اختر المستلمين</h2>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <label class="block text-sm font-bold text-gray-700 mb-3">
                                <i class="fas fa-users-cog text-green-600 mr-2"></i>
                                طريقة الاختيار
                            </label>

                            <div class="radio-card">
                                <input type="radio" id="typePersons" name="recipient_type" value="persons" checked>
                                <label for="typePersons">
                                    <i class="fas fa-user-plus text-green-600 text-xl"></i>
                                    <span class="font-medium">اختيار أشخاص محددين</span>
                                </label>
                            </div>

                            <div class="radio-card">
                                <input type="radio" id="typeGroup" name="recipient_type" value="group">
                                <label for="typeGroup">
                                    <i class="fas fa-users text-green-600 text-xl"></i>
                                    <span class="font-medium">اختيار مجموعة جاهزة</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            {{-- Persons Select --}}
                            <div id="personsWrap">
                                <label class="block text-sm font-bold text-gray-700 mb-3">
                                    <i class="fas fa-search text-green-600 mr-2"></i>
                                    ابحث واختر الأشخاص
                                    <span class="text-xs text-gray-500 font-normal mr-2">(لديهم واتساب)</span>
                                </label>
                                <select name="person_ids[]" id="person_ids" multiple style="width:100%;"></select>
                            </div>

                            {{-- Group Select --}}
                            <div id="groupWrap" style="display:none;">
                                <label class="block text-sm font-bold text-gray-700 mb-3">
                                    <i class="fas fa-layer-group text-green-600 mr-2"></i>
                                    اختر المجموعة
                                </label>
                                <select name="group_id" id="group_id" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    <option value="">-- اختر مجموعة --</option>
                                    @foreach($groups as $g)
                                        <option value="{{ $g->id }}">{{ $g->name }} ({{ $g->persons_count }} شخص)</option>
                                    @endforeach
                                </select>
                                @if($groups->isEmpty())
                                    <p class="text-sm text-amber-600 mt-2 flex items-center gap-2">
                                        <i class="fas fa-info-circle"></i>
                                        لا توجد مجموعات. يمكنك إنشائها من لوحة التحكم.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Step 2: Message --}}
                <div class="section-card rounded-2xl p-6 lg:p-8 mb-6 animate-fade-in" style="animation-delay: 0.1s;">
                    <div class="flex items-center gap-4 mb-6">
                        <span class="step-badge">2</span>
                        <h2 class="text-2xl font-bold text-gray-800">اكتب الرسالة</h2>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                <i class="fas fa-file-alt text-green-600 mr-2"></i>
                                اختر قالب
                                <span class="text-xs text-gray-500 font-normal mr-2">(يمكنك تعديل النص بعد الاختيار)</span>
                            </label>
                            <select id="template_select" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">— بدون قالب —</option>
                                @foreach($templates as $index => $tpl)
                                    <option value="{{ $index }}">{{ $tpl['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                <i class="fas fa-tag text-green-600 mr-2"></i>
                                عنوان
                                <span class="text-xs text-gray-500 font-normal mr-2">(اختياري - للسجل فقط)</span>
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="مثال: دعوة مناسبة عائلية">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                <i class="fas fa-comment-dots text-green-600 mr-2"></i>
                                نص الرسالة
                            </label>
                            <textarea name="body" id="body" rows="6"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="السلام عليكم {name}، يسرنا دعوتكم لحضور...">{{ old('body') }}</textarea>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-3">
                                <i class="fas fa-magic text-green-600 mr-2"></i>
                                إدراج متغير:
                            </p>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" class="var-badge insert-var" data-var="{name}">
                                    <i class="fas fa-user text-xs"></i>
                                    {name}
                                </button>
                                <button type="button" class="var-badge insert-var" data-var="{first_name}">
                                    <i class="fas fa-user text-xs"></i>
                                    {first_name}
                                </button>
                                <button type="button" class="var-badge insert-var" data-var="{full_name}">
                                    <i class="fas fa-id-card text-xs"></i>
                                    {full_name}
                                </button>
                            </div>
                        </div>

                        <div>
                            <button type="button" id="previewBtn"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-blue-50 text-blue-700 font-medium rounded-xl hover:bg-blue-100 transition-all">
                                <i class="fas fa-eye"></i>
                                معاينة الرسالة
                            </button>
                            <div id="previewResult" class="mt-4" style="display:none;"></div>
                        </div>
                    </div>
                </div>

                {{-- Step 3: Media --}}
                <div class="section-card rounded-2xl p-6 lg:p-8 mb-6 animate-fade-in" style="animation-delay: 0.2s;">
                    <div class="flex items-center gap-4 mb-6">
                        <span class="step-badge">3</span>
                        <h2 class="text-2xl font-bold text-gray-800">
                            مرفق
                            <span class="text-lg text-gray-500 font-normal mr-2">(اختياري)</span>
                        </h2>
                    </div>

                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <div class="media-option">
                            <input type="radio" name="media_type" value="" id="mediaText" checked>
                            <div class="option-content">
                                <i class="fas fa-font text-3xl text-gray-400"></i>
                                <span class="font-medium text-gray-700">نص فقط</span>
                            </div>
                        </div>

                        <div class="media-option">
                            <input type="radio" name="media_type" value="image" id="mediaImage">
                            <div class="option-content">
                                <i class="fas fa-image text-3xl text-blue-500"></i>
                                <span class="font-medium text-gray-700">صورة</span>
                            </div>
                        </div>

                        <div class="media-option">
                            <input type="radio" name="media_type" value="video" id="mediaVideo">
                            <div class="option-content">
                                <i class="fas fa-video text-3xl text-red-500"></i>
                                <span class="font-medium text-gray-700">فيديو</span>
                            </div>
                        </div>

                        <div class="media-option">
                            <input type="radio" name="media_type" value="voice" id="mediaVoice">
                            <div class="option-content">
                                <i class="fas fa-microphone text-3xl text-yellow-500"></i>
                                <span class="font-medium text-gray-700">صوت</span>
                            </div>
                        </div>
                    </div>

                    <div id="mediaFileWrap" style="display:none;">
                        <div class="upload-zone">
                            <i class="fas fa-cloud-upload-alt text-5xl text-green-500 mb-3" id="mediaIcon"></i>
                            <p class="text-lg font-bold text-gray-700 mb-2" id="mediaFileLabel">ارفع الملف</p>
                            <input type="file" name="media" id="media" accept=""
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 cursor-pointer">
                            <p class="text-sm text-gray-500 mt-3">
                                <i class="fas fa-info-circle mr-1"></i>
                                الحد الأقصى: 16 ميجابايت
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="text-center animate-fade-in" style="animation-delay: 0.3s;">
                    <button type="submit"
                        class="btn-submit inline-flex items-center gap-3 px-10 py-5 text-white text-xl font-bold rounded-2xl shadow-2xl">
                        <i class="fab fa-whatsapp text-3xl"></i>
                        إرسال الآن
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    var invitationTemplates = @json($templates ?? []);
    $(function() {
        // Template selector: fill title and body when a template is chosen
        $('#template_select').on('change', function() {
            var val = $(this).val();
            if (val === '') return;
            var t = invitationTemplates[parseInt(val, 10)];
            if (t) {
                $('#title').val(t.title || '');
                $('#body').val(t.body || '');
            }
        });

        // Toggle Recipients
        $('input[name="recipient_type"]').on('change', function() {
            var isGroup = $(this).val() === 'group';
            $('#personsWrap').toggle(!isGroup);
            $('#groupWrap').toggle(isGroup);
        });

        // Init Select2 for persons
        var $personIds = $('#person_ids');
        if ($personIds.data('select2')) $personIds.select2('destroy');
        $personIds.select2({
            placeholder: 'ابحث بالاسم...',
            dir: 'rtl',
            width: '100%',
            language: 'ar',
            ajax: {
                url: '{{ route("invitations.persons-with-whatsapp") }}',
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

        // Insert variables
        $('.insert-var').on('click', function() {
            var ta = document.getElementById('body');
            if (!ta) return;
            var v = $(this).data('var') || '';
            var start = ta.selectionStart, end = ta.selectionEnd;
            ta.value = ta.value.slice(0, start) + v + ta.value.slice(end);
            ta.selectionStart = ta.selectionEnd = start + v.length;
            ta.focus();
        });

        // Preview message
        $('#previewBtn').on('click', function() {
            var personId = ($personIds.val() && $personIds.val().length) ? $personIds.val()[0] : null;
            var $result = $('#previewResult');
            if (!personId) {
                $result.html('<div class="rounded-xl bg-yellow-50 border-l-4 border-yellow-400 p-4"><p class="text-sm text-yellow-700"><i class="fas fa-info-circle mr-2"></i>اختر شخصاً أولاً لمعاينة الرسالة.</p></div>').show();
                return;
            }
            $.ajax({
                url: '{{ route("invitations.preview-message") }}',
                method: 'POST',
                data: JSON.stringify({ body: $('#body').val(), person_id: parseInt(personId) }),
                contentType: 'application/json',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(d) {
                    $result.html('<div class="rounded-xl bg-green-50 border-l-4 border-green-400 p-4"><p class="text-sm text-green-700 whitespace-pre-wrap"><i class="fab fa-whatsapp mr-2"></i>' + (d.preview || '(فارغ)') + '</p></div>').show();
                },
                error: function() {
                    $result.html('<div class="rounded-xl bg-red-50 border-l-4 border-red-400 p-4"><p class="text-sm text-red-700">فشلت المعاينة.</p></div>').show();
                }
            });
        });

        // Media type toggle
        var mediaLabels = { 
            image: 'ارفع صورة (jpg, png, gif, webp)', 
            video: 'ارفع فيديو (mp4)', 
            voice: 'ارفع ملف صوت (ogg, mp3)' 
        };
        var mediaAccepts = { 
            image: 'image/*', 
            video: 'video/*', 
            voice: 'audio/*' 
        };

        // Handle media option clicks
        $('.media-option').on('click', function() {
            var $radio = $(this).find('input[type="radio"]');
            $radio.prop('checked', true).trigger('change');
        });

        // Handle media type change
        $('input[name="media_type"]').on('change', function() {
            var v = $(this).val();
            console.log('Media type changed to:', v); // Debug
            
            if (v && v !== '') {
                $('#mediaFileWrap').slideDown(300);
                $('#mediaFileLabel').text(mediaLabels[v] || 'ارفع الملف');
                $('#media').attr('accept', mediaAccepts[v] || '');
            } else {
                $('#mediaFileWrap').slideUp(300);
                $('#media').val('').attr('accept', '');
            }
        });
    });
    </script>
</body>
</html>
