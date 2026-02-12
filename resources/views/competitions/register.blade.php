<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التسجيل في المسابقة - {{ $competition->title }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Tajawal', 'sans-serif'],
                        'serif': ['Amiri', 'serif'],
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0) rotate(0deg)'
                            },
                            '50%': {
                                transform: 'translateY(-18px) rotate(3deg)'
                            },
                        },
                        'pulse-soft': {
                            '0%, 100%': {
                                opacity: '0.25'
                            },
                            '50%': {
                                opacity: '0.55'
                            },
                        },
                        fadeIn: {
                            from: {
                                opacity: '0',
                                transform: 'scale(0.97)'
                            },
                            to: {
                                opacity: '1',
                                transform: 'scale(1)'
                            },
                        },
                        slideUp: {
                            from: {
                                opacity: '0',
                                transform: 'translateY(20px)'
                            },
                            to: {
                                opacity: '1',
                                transform: 'translateY(0)'
                            },
                        },
                    },
                    animation: {
                        'float': 'float 9s ease-in-out infinite',
                        'pulse-soft': 'pulse-soft 6s ease-in-out infinite',
                        'fade-in': 'fadeIn 0.3s ease-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                    },
                    boxShadow: {
                        'emerald-glow': '0 0 35px rgba(16, 185, 129, 0.25)',
                    }
                }
            }
        };
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Amiri:wght@400;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 45%, #f8fafc 100%);
        }

        h1,
        h2,
        h3,
        h4 {
            font-family: 'Amiri', serif;
            letter-spacing: -0.015em;
        }

        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #ecfdf5;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #10b981, #059669);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #059669, #047857);
        }
    </style>
</head>

<body class="text-gray-800 overflow-x-hidden relative">
    @include('partials.main-header')

    <div class="fixed top-16 left-12 w-96 h-96 opacity-20 z-0 pointer-events-none animate-float hidden xl:block">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#34d399"
                d="M43.8,-75.6C58.8,-69.4,74.4,-59.1,79.4,-44.8C84.4,-30.4,78.8,-12.1,74.9,5.4C71,22.9,69,39.7,59.7,50.3C50.5,60.9,41.3,61.8,28.1,66.8C14.9,71.9,0.7,73,-13.5,72.2C-27.7,71.5,-41.9,68.8,-54.7,61.4C-67.5,54,-79,42,-84.6,27.8C-90.2,13.5,-90,-2.9,-83.6,-17.1C-77.1,-31.4,-64.5,-43.5,-50.9,-52.3C-37.4,-61,-22.9,-66.4,-8.4,-65.7C6.2,-65.1,12.3,-58.6,33.9,-58.2Z"
                transform="translate(100 100)" />
        </svg>
    </div>
    <div
        class="fixed bottom-16 right-16 w-96 h-96 opacity-10 z-0 pointer-events-none animate-pulse-soft hidden xl:block">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#22d3ee"
                d="M33.9,-58.2C46.8,-50.5,61.6,-46.1,68.9,-36.5C76.3,-26.8,76.3,-12,74.6,5.7C72.9,22.9,69.4,39.7,61.5,48.6C53.6,57.8,41.3,61.8,28.1,66.8C14.9,71.9,0.7,73,-13.5,72.2C-27.7,71.5,-41.9,68.8,-54.7,61.4C-67.5,54,-79,42,-84.6,27.8C-90.2,13.5,-90,-2.9,-83.6,-17.1C-77.1,-31.4,-64.5,-43.5,-50.9,-52.3C-37.4,-61,-22.9,-66.4,-8.4,-65.7C6.2,-65.1,12.3,-58.6,33.9,-58.2Z"
                transform="translate(100 100)" />
        </svg>
    </div>

    <main class="relative z-10 max-w-4xl mx-auto px-4 lg:px-6 py-10 lg:py-14">
        <div class="mb-6">
            <a href="{{ route('home') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/70 backdrop-blur-md border border-white/40 rounded-full hover:bg-white/95 transition-all duration-300 group shadow-sm hover:shadow-lg">
                <svg class="w-5 h-5 text-emerald-600 transition-transform group-hover:-translate-x-1" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="font-medium text-gray-700">العودة للصفحة الرئيسية</span>
            </a>
        </div>

        <article
            class="bg-white/85 backdrop-blur-md border border-white/50 rounded-3xl overflow-hidden shadow-emerald-glow animate-fade-in">
            <header class="relative">
                <div class="px-3 py-4 sm:px-6 sm:py-6 lg:px-10 lg:py-8 relative z-10">
                    <div class="bg-white rounded-2xl sm:rounded-3xl p-4 sm:p-6 lg:p-8 border border-gray-200 shadow-lg relative overflow-hidden">
                        <div class="relative z-10">
                            <div class="inline-flex items-center gap-2 px-2.5 py-1 sm:px-3 bg-emerald-600 text-white rounded-full text-xs font-bold mb-3 sm:mb-4">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                                    </path>
                                </svg>
                                مسابقة
                            </div>
                            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-3 sm:mb-4 leading-relaxed font-serif text-gray-800">
                                {{ $competition->title }}
                            </h1>
                            @if($competition->description)
                                <div class="text-gray-600 leading-relaxed text-sm sm:text-base mb-4">
                                    {{ $competition->description }}
                                </div>
                            @endif

                            <div class="mt-4 sm:mt-6 flex flex-wrap items-center gap-3 sm:gap-4 text-xs sm:text-sm">
                                <div class="flex items-center gap-2 text-gray-600">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="font-medium">{{ $competition->game_type }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-600">
                                    <div class="w-8 h-8 rounded-lg bg-teal-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                    </div>
                                    <span class="font-medium">{{ $competition->team_size }} عضو</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="px-3 py-4 sm:px-6 sm:py-6 lg:px-10 lg:py-8">
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl shadow-sm">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl shadow-sm">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl shadow-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('competitions.register.store', $competition->registration_token) }}" method="POST"
                    class="space-y-6">
                    @csrf

                    @if($competition->categories->isNotEmpty())
                        <div class="bg-white/50 backdrop-blur-sm rounded-2xl p-4 sm:p-6 border border-emerald-100/50">
                            <h3 class="text-lg sm:text-xl font-bold font-serif text-emerald-700 mb-4 flex items-center gap-2">
                                <div class="w-1 h-6 bg-emerald-600 rounded-full"></div>
                                اختيار التصنيفات <span class="text-red-500">*</span>
                            </h3>

                            <div class="space-y-3">
                                <p class="text-sm text-gray-600 mb-4">يرجى اختيار تصنيف واحد على الأقل من التصنيفات المتاحة:</p>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="categories_container">
                                    @foreach($competition->categories as $category)
                                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-emerald-400 hover:bg-emerald-50 transition-all duration-200 group">
                                            <input type="checkbox"
                                                name="category_ids[]"
                                                value="{{ $category->id }}"
                                                class="w-5 h-5 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500 focus:ring-2"
                                                {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}
                                                required>
                                            <span class="mr-3 text-gray-700 font-medium group-hover:text-emerald-700">{{ $category->name }}</span>
                                        </label>
                                    @endforeach
                                </div>

                                @error('category_ids')
                                    <div class="text-red-600 text-sm mt-2">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    @endif

                    <div class="bg-white/50 backdrop-blur-sm rounded-2xl p-4 sm:p-6 border border-emerald-100/50">
                        <h3 class="text-lg sm:text-xl font-bold font-serif text-emerald-700 mb-4 flex items-center gap-2">
                            <div class="w-1 h-6 bg-emerald-600 rounded-full"></div>
                            البيانات الشخصية
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2 text-sm sm:text-base">
                                    الاسم <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-white">
                            </div>

                            <div>
                                <label class="block text-gray-700 font-semibold mb-2 text-sm sm:text-base">
                                    رقم الهاتف <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="phone" value="{{ old('phone') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-white">
                            </div>
                        </div>
                    </div>

                    @if($competition->team_size > 1)
                        <div class="bg-white/50 backdrop-blur-sm rounded-2xl p-4 sm:p-6 border border-emerald-100/50">
                            <h3 class="text-lg sm:text-xl font-bold font-serif text-emerald-700 mb-4 flex items-center gap-2">
                                <div class="w-1 h-6 bg-emerald-600 rounded-full"></div>
                                هل معك أحد؟
                            </h3>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <button type="button" id="btn_with_brother" onclick="toggleBrotherForm(true)"
                                    class="px-6 py-4 border-2 border-emerald-300 rounded-xl font-semibold transition-all duration-300 hover:scale-105 bg-white hover:bg-emerald-50 hover:border-emerald-500 text-gray-700">
                                    <i class="fas fa-user-friends mr-2 text-emerald-600"></i>
                                    معي خوي
                                </button>
                                <button type="button" id="btn_alone" onclick="toggleBrotherForm(false)"
                                    class="px-6 py-4 border-2 border-gray-300 rounded-xl font-semibold transition-all duration-300 hover:scale-105 bg-white hover:bg-gray-50 hover:border-gray-400 text-gray-700">
                                    <i class="fas fa-user mr-2 text-gray-600"></i>
                                    ما عندي خوي
                                </button>
                            </div>

                            <div id="brother_form_section" style="display: none;" class="space-y-4 animate-slide-up">
                                <div class="bg-emerald-50 border-2 border-emerald-200 rounded-xl p-4">
                                    <h4 class="font-bold text-emerald-700 mb-4 flex items-center gap-2">
                                        <i class="fas fa-user-friends"></i>
                                        بيانات خوي
                                    </h4>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-gray-700 font-semibold mb-2 text-sm sm:text-base">
                                                اسم خوي <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="brother_name" id="brother_name" value="{{ old('brother_name') }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-white">
                                        </div>
                                        <div>
                                            <label class="block text-gray-700 font-semibold mb-2 text-sm sm:text-base">
                                                رقم هاتف خوي <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="brother_phone" id="brother_phone" value="{{ old('brother_phone') }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-white">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="team_name_section" class="bg-white/50 backdrop-blur-sm rounded-2xl p-4 sm:p-6 border border-emerald-100/50" style="display: none;">
                            <h3 class="text-lg sm:text-xl font-bold font-serif text-emerald-700 mb-4 flex items-center gap-2">
                                <div class="w-1 h-6 bg-emerald-600 rounded-full"></div>
                                معلومات الفريق
                            </h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2 text-sm sm:text-base">
                                        اسم الفريق <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="team_name" id="team_name" value="{{ old('team_name') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-white">
                                    <p class="text-xs text-gray-500 mt-2">إذا كان معك خوي، يمكنك إنشاء فريق وتسميته</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 shadow-lg hover:shadow-emerald-glow hover:scale-[1.02] flex items-center justify-center gap-2">
                        <i class="fas fa-paper-plane"></i>
                        <span>تسجيل</span>
                    </button>
                </form>
            </div>
        </article>
    </main>

    <script>
        let hasBrother = false;

        // التحقق من اختيار تصنيف واحد على الأقل
        document.addEventListener('DOMContentLoaded', function() {
            const categoryCheckboxes = document.querySelectorAll('input[name="category_ids[]"]');
            const form = document.querySelector('form');

            if (categoryCheckboxes.length > 0) {
                form.addEventListener('submit', function(e) {
                    const checkedCategories = Array.from(categoryCheckboxes).filter(cb => cb.checked);

                    if (checkedCategories.length === 0) {
                        e.preventDefault();
                        alert('يجب اختيار تصنيف واحد على الأقل');
                        return false;
                    }
                });

                // إزالة required من checkboxes الفردية واستخدام validation مخصص
                categoryCheckboxes.forEach(cb => {
                    cb.removeAttribute('required');
                });
            }
        });

        function toggleBrotherForm(withBrother) {
            hasBrother = withBrother;
            const brotherSection = document.getElementById('brother_form_section');
            const teamNameSection = document.getElementById('team_name_section');
            const btnWithBrother = document.getElementById('btn_with_brother');
            const btnAlone = document.getElementById('btn_alone');
            const brotherNameInput = document.getElementById('brother_name');
            const brotherPhoneInput = document.getElementById('brother_phone');
            const teamNameInput = document.getElementById('team_name');

            // التحقق من وجود العناصر (قد لا تكون موجودة في المسابقات الفردية)
            if (!brotherSection || !teamNameSection || !btnWithBrother || !btnAlone) {
                return;
            }

            if (withBrother) {
                brotherSection.style.display = 'block';
                teamNameSection.style.display = 'block';
                btnWithBrother.classList.add('bg-emerald-100', 'border-emerald-500', 'text-emerald-700');
                btnWithBrother.classList.remove('bg-white', 'border-emerald-300', 'text-gray-700');
                btnAlone.classList.remove('bg-gray-50', 'border-gray-400');
                btnAlone.classList.add('bg-white', 'border-gray-300', 'text-gray-700');
                if (brotherNameInput) brotherNameInput.setAttribute('required', 'required');
                if (brotherPhoneInput) brotherPhoneInput.setAttribute('required', 'required');
                if (teamNameInput) teamNameInput.setAttribute('required', 'required');
            } else {
                brotherSection.style.display = 'none';
                teamNameSection.style.display = 'none';
                btnWithBrother.classList.remove('bg-emerald-100', 'border-emerald-500', 'text-emerald-700');
                btnWithBrother.classList.add('bg-white', 'border-emerald-300', 'text-gray-700');
                btnAlone.classList.add('bg-gray-50', 'border-gray-400');
                btnAlone.classList.remove('bg-white', 'border-gray-300', 'text-gray-700');
                if (brotherNameInput) brotherNameInput.removeAttribute('required');
                if (brotherPhoneInput) brotherPhoneInput.removeAttribute('required');
                if (teamNameInput) teamNameInput.removeAttribute('required');
            }
        }

        // Initialize on page load (فقط إذا كانت العناصر موجودة)
        const btnAlone = document.getElementById('btn_alone');
        if (btnAlone) {
            toggleBrotherForm(false);
        }
    </script>
</body>

</html>
