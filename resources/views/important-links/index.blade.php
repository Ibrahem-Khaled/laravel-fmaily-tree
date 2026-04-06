<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تطبيقات تهمك</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['Amiri', 'serif'],
                        sans: ['Tajawal', 'sans-serif'],
                    },
                    animation: {
                        'float-slow': 'float 8s ease-in-out infinite',
                        'float-delayed': 'float 10s ease-in-out infinite 2s',
                        'shimmer': 'shimmer 12s linear infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0) rotate(0deg)' },
                            '50%': { transform: 'translateY(-18px) rotate(2deg)' },
                        },
                        shimmer: {
                            '0%': { backgroundPosition: '200% 0' },
                            '100%': { backgroundPosition: '-200% 0' },
                        },
                    },
                },
            },
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .il-page {
            font-family: 'Tajawal', sans-serif;
            min-height: 100vh;
            background: #ffffff;
            background-image:
                radial-gradient(ellipse 100% 70% at 100% 0%, rgba(34, 197, 94, 0.08), transparent 55%),
                radial-gradient(ellipse 80% 60% at 0% 100%, rgba(16, 185, 129, 0.06), transparent 50%),
                radial-gradient(circle at 50% 40%, rgba(240, 253, 244, 0.9), #ffffff 65%);
        }
        .il-page h1, .il-page h2, .il-page h3, .il-font-display { font-family: 'Amiri', serif; }
        .il-mesh {
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.025'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
        }
        .il-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.28;
            pointer-events: none;
        }
        .il-card {
            position: relative;
            border-radius: 1.25rem;
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow:
                0 4px 20px rgba(15, 23, 42, 0.06),
                0 1px 3px rgba(15, 23, 42, 0.04);
            transition: transform 0.45s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.45s ease, border-color 0.3s ease;
            overflow: hidden;
        }
        @media (min-width: 1024px) {
            .il-card:hover {
                transform: translateY(-10px) scale(1.02);
                border-color: rgba(34, 197, 94, 0.45);
                box-shadow:
                    0 24px 48px rgba(15, 23, 42, 0.1),
                    0 0 0 1px rgba(34, 197, 94, 0.15),
                    0 12px 40px rgba(34, 197, 94, 0.12);
            }
            .il-card:hover .il-card-shine { opacity: 1; }
            .il-card:hover .il-card-cta { opacity: 1; transform: translateY(0); }
        }
        .il-card-shine {
            position: absolute;
            inset: 0;
            background: linear-gradient(105deg, transparent 35%, rgba(34, 197, 94, 0.06) 50%, transparent 65%);
            background-size: 200% 100%;
            opacity: 0;
            transition: opacity 0.5s ease;
            pointer-events: none;
            animation: il-shimmer 14s linear infinite;
        }
        .il-card-cta {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 0.75rem 1rem;
            background: linear-gradient(to top, rgba(255,255,255,0.97), rgba(255,255,255,0));
            opacity: 0;
            transform: translateY(8px);
            transition: opacity 0.35s ease, transform 0.35s ease;
            pointer-events: none;
        }
        @media (max-width: 1023px) {
            .il-card-cta { display: none; }
        }
        .il-card-img {
            aspect-ratio: 1;
            background: linear-gradient(160deg, #ecfdf5 0%, #d1fae5 45%, #bbf7d0 100%);
        }
        @media (min-width: 1024px) {
            .il-card-img { aspect-ratio: 1 / 1; min-height: 200px; max-height: 220px; }
        }
        .il-hero-title {
            font-size: clamp(2.25rem, 5vw, 4rem);
            line-height: 1.15;
            background: linear-gradient(135deg, #14532d 0%, #166534 25%, #15803d 55%, #16a34a 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .il-btn-primary {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 50%, #15803d 100%);
            box-shadow: 0 4px 20px rgba(34, 197, 94, 0.4), inset 0 1px 0 rgba(255,255,255,0.2);
        }
        .il-btn-primary:hover {
            box-shadow: 0 8px 32px rgba(34, 197, 94, 0.55);
            transform: translateY(-2px);
        }
        .il-modal-panel {
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.1);
            box-shadow: 0 25px 80px rgba(15, 23, 42, 0.12), 0 0 0 1px rgba(255, 255, 255, 0.8) inset;
        }
        .il-scrollbar::-webkit-scrollbar { width: 8px; height: 8px; }
        .il-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
        .il-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #22c55e, #15803d);
            border-radius: 4px;
        }
        .link-card { opacity: 0; transform: translateY(28px); transition: opacity 0.7s cubic-bezier(0.22, 1, 0.36, 1), transform 0.7s cubic-bezier(0.22, 1, 0.36, 1); }
        .link-card.visible { opacity: 1; transform: translateY(0); }
        @keyframes il-shimmer {
            0% { background-position: 200% center; }
            100% { background-position: -200% center; }
        }
    </style>
</head>

<body class="il-page text-gray-800 relative overflow-x-hidden">
    <div class="il-mesh" aria-hidden="true"></div>
    <div class="il-orb w-96 h-96 bg-emerald-500 -top-32 -right-32 animate-float-slow hidden lg:block" aria-hidden="true"></div>
    <div class="il-orb w-[28rem] h-[28rem] bg-teal-600 bottom-0 left-0 translate-y-1/3 -translate-x-1/4 animate-float-delayed hidden lg:block" aria-hidden="true"></div>

    @include('partials.main-header')

    <main class="relative z-10 max-w-[1400px] mx-auto px-5 sm:px-8 lg:px-12 xl:px-16 py-8 lg:py-14 xl:py-20">
        {{-- Hero: مصمَّم للشاشات الواسعة --}}
        <header class="lg:grid lg:grid-cols-12 lg:gap-12 xl:gap-16 lg:items-center mb-12 lg:mb-20 xl:mb-24">
            <div class="lg:col-span-7 xl:col-span-6 text-center lg:text-right">
                <p class="text-emerald-700 text-sm lg:text-base font-bold tracking-wide mb-3 lg:mb-4 flex items-center justify-center lg:justify-start gap-2">
                    <span class="inline-flex h-px w-8 lg:w-12 bg-gradient-to-l from-emerald-500 to-transparent"></span>
                    مكتبة العائلة الرقمية
                    <span class="inline-flex h-px w-8 lg:w-12 bg-gradient-to-r from-emerald-500 to-transparent"></span>
                </p>
                <h1 class="il-hero-title il-font-display font-bold mb-4 lg:mb-6">تطبيقات تهمك</h1>
                <p class="text-gray-600 text-base lg:text-xl xl:text-2xl leading-relaxed max-w-2xl mx-auto lg:mx-0 lg:max-w-none font-medium">
                    تطبيقات وروابط يختارها أفراد العائلة لكم جميعاً. تصفّح البطاقات، شاهد الصور والفيديوهات، ثم انتقل لمتجر التطبيقات أو الموقع بنقرة.
                </p>
                @if(session('success'))
                    <div class="mt-6 lg:mt-8 p-4 lg:p-5 rounded-2xl border border-emerald-200 bg-emerald-50 text-emerald-900 text-right max-w-xl mx-auto lg:mx-0" role="alert">
                        <i class="fas fa-check-circle text-emerald-600 ml-2"></i>{{ session('success') }}
                    </div>
                @endif
                <div class="mt-8 lg:mt-10 flex flex-col sm:flex-row gap-4 justify-center lg:justify-start items-stretch sm:items-center">
                    <button type="button" onclick="openSuggestModal()" class="il-btn-primary inline-flex items-center justify-center gap-3 px-8 lg:px-10 py-4 lg:py-4 rounded-2xl text-white font-bold text-lg transition-all duration-300">
                        <i class="fas fa-plus-circle text-xl"></i>
                        <span>اقترح تطبيقاً</span>
                    </button>
                    @if($links->count() > 0)
                        <span class="text-gray-500 text-sm lg:text-base flex items-center justify-center gap-2 py-2">
                            <i class="fas fa-layer-group text-emerald-600"></i>
                            {{ $links->count() }} {{ $links->count() === 1 ? 'عنصر' : 'عناصر' }} في المجموعة
                        </span>
                    @endif
                </div>
            </div>
            <div class="hidden lg:flex lg:col-span-5 xl:col-span-6 justify-center items-center mt-0">
                <div class="relative w-full max-w-md xl:max-w-lg aspect-square">
                    <div class="absolute inset-8 rounded-[2rem] border border-emerald-200/80 rotate-6 bg-gradient-to-br from-emerald-50 to-transparent"></div>
                    <div class="absolute inset-0 rounded-[2rem] border border-gray-200 -rotate-3 flex items-center justify-center bg-white/90 backdrop-blur-sm shadow-lg shadow-gray-200/50">
                        <div class="text-center p-8">
                            <div class="w-24 h-24 xl:w-28 xl:h-28 mx-auto mb-6 rounded-2xl bg-gradient-to-br from-emerald-500 to-green-700 flex items-center justify-center shadow-xl shadow-emerald-500/25">
                                <i class="fas fa-mobile-screen-button text-4xl xl:text-5xl text-white"></i>
                            </div>
                            <p class="il-font-display text-2xl xl:text-3xl text-gray-900 font-bold">جاهز للاستكشاف</p>
                            <p class="text-gray-500 mt-2 text-sm xl:text-base">اختر بطاقة من الشبكة أدناه</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        @if($links->count() > 0)
            <section aria-label="قائمة التطبيقات">
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 2xl:grid-cols-5 gap-4 md:gap-6 lg:gap-8 xl:gap-10" id="linksGrid">
                    @foreach($links as $link)
                        <article class="link-card il-card cursor-pointer group max-w-none" data-link-id="{{ $link->id }}" onclick="openDetailModal({{ $link->id }})" role="button" tabindex="0" onkeydown="if(event.key==='Enter')openDetailModal({{ $link->id }})">
                            <div class="il-card-shine" aria-hidden="true"></div>
                            <div class="il-card-img relative overflow-hidden flex items-center justify-center p-4 lg:p-6">
                                <img src="{{ $link->image_url }}" alt="{{ $link->title }}" class="max-w-full max-h-full object-contain drop-shadow-lg group-hover:scale-105 transition-transform duration-500" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22 fill=%22%234ade80%22%3E%3Cpath d=%22M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm3.9-2.53c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z%22/%3E%3C/svg%3E'; this.onerror=null;">
                                <span class="absolute top-3 right-3 px-2.5 py-1 rounded-lg text-xs font-bold backdrop-blur-md {{ $link->type === 'app' ? 'bg-emerald-600/80 text-white' : 'bg-sky-600/80 text-white' }}">
                                    {{ $link->type === 'app' ? 'تطبيق' : 'موقع' }}
                                </span>
                            </div>
                            <div class="il-card-cta text-center">
                                <span class="text-emerald-800 text-sm font-bold"><i class="fas fa-arrow-up-left ml-1"></i> عرض التفاصيل والوسائط</span>
                            </div>
                            <div class="px-4 lg:px-5 py-4 lg:py-5 border-t border-gray-100">
                                <h3 class="il-font-display font-bold text-gray-900 text-base lg:text-lg xl:text-xl leading-snug line-clamp-2 group-hover:text-emerald-700 transition-colors">{{ $link->title }}</h3>
                                @if($link->description)
                                    <p class="text-gray-500 text-xs lg:text-sm mt-2 line-clamp-2 leading-relaxed hidden lg:block">{{ $link->description }}</p>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @else
            <div class="il-card rounded-3xl p-12 lg:p-20 xl:p-24 text-center max-w-3xl mx-auto">
                <div class="w-20 h-20 lg:w-24 lg:h-24 mx-auto mb-8 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center">
                    <i class="fas fa-inbox text-4xl lg:text-5xl text-emerald-600"></i>
                </div>
                <h3 class="il-font-display text-2xl lg:text-4xl font-bold text-gray-900 mb-4">لا توجد عناصر معتمدة بعد</h3>
                <p class="text-gray-600 text-lg lg:text-xl mb-10">كن أول من يقترح تطبيقاً يفيد العائلة.</p>
                <button type="button" onclick="openSuggestModal()" class="il-btn-primary inline-flex items-center gap-3 px-10 py-4 rounded-2xl text-white font-bold text-lg transition-all">
                    <i class="fas fa-plus-circle"></i>
                    اقترح تطبيقاً
                </button>
            </div>
        @endif
    </main>

    {{-- مودال التفاصيل — عريض على الشاشات الكبيرة --}}
    <div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto il-scrollbar" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeDetailModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4 lg:p-10 xl:p-14">
            <div id="detailModalContent" class="relative il-modal-panel rounded-3xl w-full max-w-lg lg:max-w-5xl xl:max-w-6xl max-h-[92vh] overflow-hidden flex flex-col">
                <button type="button" onclick="closeDetailModal()" class="absolute left-4 top-4 z-20 p-3 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-200 transition-colors" aria-label="إغلاق">
                    <i class="fas fa-times text-lg"></i>
                </button>
                <div id="detailBody" class="overflow-y-auto il-scrollbar p-6 lg:p-10 xl:p-12 flex-1"></div>
            </div>
        </div>
    </div>

    {{-- مودال الاقتراح --}}
    <div id="suggestModal" class="fixed inset-0 z-50 hidden overflow-y-auto il-scrollbar" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeSuggestModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4 lg:p-10">
            <div class="relative il-modal-panel rounded-3xl w-full max-w-lg lg:max-w-2xl max-h-[90vh] overflow-y-auto il-scrollbar p-6 lg:p-10 xl:p-12">
                <button type="button" onclick="closeSuggestModal()" class="absolute left-4 top-4 p-3 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-200">
                    <i class="fas fa-times"></i>
                </button>
                <h3 class="text-2xl lg:text-3xl font-bold il-font-display mb-2 text-transparent bg-clip-text bg-gradient-to-l from-emerald-700 to-green-600">اقتراح تطبيق</h3>
                <p class="text-gray-500 text-sm lg:text-base mb-8">بعد المراجعة قد يظهر اقتراحك في الصفحة للجميع.</p>
                <form action="{{ route('important-links.suggest') }}" method="POST" enctype="multipart/form-data" class="space-y-5 lg:space-y-6">
                    @csrf
                    @if(!auth()->check())
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">اسمك <span class="text-red-600">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">رقم هاتفك <span class="text-red-600">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-emerald-500">
                            @error('phone')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    @endif
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">العنوان <span class="text-red-600">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" required class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 text-gray-900 focus:ring-2 focus:ring-emerald-500">
                        @error('title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <p class="text-sm text-gray-600">أدخل <span class="text-emerald-700 font-bold">رابطاً واحداً على الأقل</span> من الخيارات التالية.</p>
                    <div class="lg:grid lg:grid-cols-2 lg:gap-5 space-y-4 lg:space-y-0">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">رابط عام</label>
                            <input type="url" name="url" value="{{ old('url') }}" placeholder="https://..." class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 text-gray-900 focus:ring-2 focus:ring-emerald-500">
                            @error('url')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">رابط iOS</label>
                            <input type="url" name="url_ios" value="{{ old('url_ios') }}" placeholder="App Store" class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 text-gray-900 focus:ring-2 focus:ring-emerald-500">
                            @error('url_ios')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">رابط أندرويد</label>
                            <input type="url" name="url_android" value="{{ old('url_android') }}" placeholder="Google Play" class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 text-gray-900 focus:ring-2 focus:ring-emerald-500">
                            @error('url_android')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <input type="hidden" name="type" value="app">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">الوصف</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 text-gray-900 focus:ring-2 focus:ring-emerald-500">{{ old('description') }}</textarea>
                        @error('description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">صور وفيديوهات</label>
                        <div id="suggest_media_container" class="space-y-3"></div>
                        <button type="button" id="suggestAddMediaBtn" class="mt-3 text-emerald-700 font-bold hover:text-emerald-800 text-sm">
                            <i class="fas fa-plus-circle ml-1"></i> إضافة ملف
                        </button>
                        @error('media_files.*')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">صورة غلاف إضافية</label>
                        <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-emerald-600 file:text-white">
                        @error('image')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="w-full il-btn-primary py-4 rounded-2xl text-white font-bold text-lg transition-all">
                        إرسال الاقتراح
                    </button>
                </form>
            </div>
        </div>
    </div>

    <template id="suggestMediaRowTpl">
        <div class="suggest-media-row border border-gray-200 rounded-xl p-4 bg-gray-50">
            <div class="flex justify-between items-center mb-2">
                <span class="text-xs font-bold text-gray-600">ملف</span>
                <button type="button" class="text-red-600 text-sm suggest-remove-row" tabindex="-1"><i class="fas fa-times"></i></button>
            </div>
            <input type="file" name="media_files[]" class="w-full text-sm text-gray-600 mb-2">
            <label class="block text-xs font-bold text-gray-600 mb-1">النوع</label>
            <select name="media_kinds[]" class="w-full px-3 py-2 rounded-lg bg-white border border-gray-200 text-gray-900 text-sm mb-2">
                <option value="image">صورة</option>
                <option value="video">فيديو</option>
            </select>
            <label class="block text-xs font-bold text-gray-600 mb-1">عنوان</label>
            <input type="text" name="media_titles[]" maxlength="255" class="w-full px-3 py-2 rounded-lg bg-white border border-gray-200 text-gray-900 text-sm mb-2" placeholder="عنوان">
            <label class="block text-xs font-bold text-gray-600 mb-1">وصف</label>
            <textarea name="media_descriptions[]" rows="2" maxlength="2000" class="w-full px-3 py-2 rounded-lg bg-white border border-gray-200 text-gray-900 text-sm" placeholder="وصف"></textarea>
        </div>
    </template>

    <script>
        const linksData = @json($links->keyBy('id'));

        function escapeDetailHtml(s) {
            if (s == null || s === '') return '';
            return String(s)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }

        function openDetailModal(id) {
            const link = linksData[id];
            if (!link) return;
            const isApp = link.type === 'app';
            const submitterHtml = link.submitter
                ? '<p class="text-sm text-gray-600 mt-4 pt-4 border-t border-gray-200"><span class="text-emerald-700 font-bold">من أضافه:</span> ' + escapeDetailHtml(link.submitter.name) + '</p>'
                : '';

            const media = Array.isArray(link.media) ? link.media : [];
            let mediaHtml = '';
            if (media.length > 0) {
                media.forEach(function(m) {
                    const t = m.title ? '<p class="text-base font-bold text-gray-900 mt-3 il-font-display">' + escapeDetailHtml(m.title) + '</p>' : '';
                    const d = m.description ? '<p class="text-sm text-gray-600 mt-1 leading-relaxed">' + escapeDetailHtml(m.description) + '</p>' : '';
                    if (m.kind === 'video') {
                        const src = m.file_url || '';
                        mediaHtml += '<div class="mb-8 last:mb-0 rounded-2xl overflow-hidden border border-gray-200 bg-gray-900 ring-1 ring-gray-200">' +
                            '<video controls class="w-full max-h-[min(420px,50vh)] lg:max-h-[480px] mx-auto" src="' + escapeDetailHtml(src) + '"></video></div>' + t + d;
                    } else {
                        const src = m.file_url || '';
                        mediaHtml += '<div class="mb-8 last:mb-0 rounded-2xl overflow-hidden border border-gray-200 bg-gray-50">' +
                            '<img src="' + escapeDetailHtml(src) + '" alt="" class="w-full object-contain max-h-[min(400px,45vh)] lg:max-h-[440px] mx-auto" loading="lazy"></div>' + t + d;
                    }
                });
            } else {
                mediaHtml = '<div class="rounded-2xl overflow-hidden border border-gray-200 bg-gray-50 mb-6">' +
                    '<img src="' + escapeDetailHtml(link.image_url) + '" alt="" class="w-full object-contain max-h-80 mx-auto py-6" onerror="this.style.display=\'none\'"></div>';
            }

            const newTab = link.open_in_new_tab ? ' target="_blank" rel="noopener noreferrer"' : '';
            let actionsHtml = '';
            const btnBase = 'inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl font-bold text-white shadow-lg transition-all hover:scale-[1.02] hover:shadow-xl';
            if (isApp) {
                const parts = [];
                if (link.url_ios) {
                    parts.push('<a href="' + escapeDetailHtml(link.url_ios) + '"' + newTab + ' class="' + btnBase + ' bg-gradient-to-br from-gray-700 to-gray-900 border border-white/10"><i class="fab fa-apple text-xl"></i> App Store</a>');
                }
                if (link.url_android) {
                    parts.push('<a href="' + escapeDetailHtml(link.url_android) + '"' + newTab + ' class="' + btnBase + ' bg-gradient-to-br from-emerald-600 to-green-800 border border-emerald-400/30"><i class="fab fa-google-play"></i> Google Play</a>');
                }
                if (link.url) {
                    parts.push('<a href="' + escapeDetailHtml(link.url) + '"' + newTab + ' class="' + btnBase + ' bg-gradient-to-br from-teal-500 to-emerald-700 border border-white/10"><i class="fas fa-link"></i> رابط عام</a>');
                }
                actionsHtml = parts.length
                    ? '<div class="flex flex-wrap gap-4 mt-8">' + parts.join('') + '</div>'
                    : '';
            } else if (link.url) {
                actionsHtml = '<div class="mt-8"><a href="' + escapeDetailHtml(link.url) + '"' + newTab + ' class="' + btnBase + ' bg-gradient-to-br from-sky-600 to-blue-800 w-full sm:w-auto"><i class="fas fa-external-link-alt"></i> زيارة الموقع</a></div>';
            }

            const badgeClass = isApp ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-sky-100 text-sky-800 border-sky-200';
            const sideHtml =
                '<div class="lg:sticky lg:top-4 space-y-6">' +
                '<div>' +
                '<span class="inline-block px-4 py-1.5 rounded-full text-sm font-bold border ' + badgeClass + '">' + (isApp ? 'تطبيق' : 'موقع') + '</span>' +
                '<h2 class="il-font-display text-3xl lg:text-4xl xl:text-5xl font-bold text-gray-900 mt-4 leading-tight">' + escapeDetailHtml(link.title) + '</h2>' +
                (link.description ? '<p class="text-gray-600 text-base lg:text-lg mt-5 leading-relaxed">' + escapeDetailHtml(link.description) + '</p>' : '') +
                submitterHtml +
                '</div>' +
                actionsHtml +
                '</div>';

            const mediaCol = '<div class="min-w-0">' + mediaHtml + '</div>';

            document.getElementById('detailBody').innerHTML =
                '<div class="lg:grid lg:grid-cols-12 lg:gap-12 xl:gap-16 items-start">' +
                '<div class="lg:col-span-7 xl:col-span-8 order-2 lg:order-1">' + mediaCol + '</div>' +
                '<div class="lg:col-span-5 xl:col-span-4 order-1 lg:order-2 mb-10 lg:mb-0">' + sideHtml + '</div>' +
                '</div>';

            document.getElementById('detailModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function openSuggestModal() {
            document.getElementById('suggestModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSuggestModal() {
            document.getElementById('suggestModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function suggestAppendMediaRow() {
            const tpl = document.getElementById('suggestMediaRowTpl');
            const container = document.getElementById('suggest_media_container');
            if (!tpl || !container) return;
            const node = tpl.content.cloneNode(true);
            container.appendChild(node);
            const row = container.lastElementChild;
            row.querySelector('.suggest-remove-row').addEventListener('click', function() {
                row.remove();
            });
        }

        document.getElementById('suggestAddMediaBtn')?.addEventListener('click', suggestAppendMediaRow);

        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.link-card');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => entry.target.classList.add('visible'), index * 60);
                        observer.unobserve(entry.target);
                    }
                });
            }, { rootMargin: '0px 0px -80px 0px' });
            cards.forEach(card => observer.observe(card));
        });
    </script>
</body>

</html>
