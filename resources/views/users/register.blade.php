<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل بيانات شخص - منصة برامج عائلة السريع</title>

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
                        shimmer: {
                            '0%': {
                                backgroundPosition: '-1000px 0'
                            },
                            '100%': {
                                backgroundPosition: '1000px 0'
                            },
                        },
                    },
                    animation: {
                        'float': 'float 9s ease-in-out infinite',
                        'pulse-soft': 'pulse-soft 6s ease-in-out infinite',
                        'fade-in': 'fadeIn 0.3s ease-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'shimmer': 'shimmer 2s linear infinite',
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

        /* تأثيرات الكروت */
        .stat-card {
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: right 0.5s;
        }

        .stat-card:hover::before {
            right: 100%;
        }

        /* تحسينات حقول النموذج */
        .form-input {
            transition: all 0.3s ease;
        }

        .form-input:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .form-input:focus-visible {
            outline: none;
        }

        /* تحسينات زر الرفع */
        .file-input-wrapper {
            position: relative;
            overflow: hidden;
        }

        .file-input-wrapper input[type=file] {
            position: absolute;
            left: -9999px;
        }

        .file-input-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 1.5rem;
            border: 2px dashed #d1d5db;
            border-radius: 1rem;
            background: #f9fafb;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-input-label:hover {
            border-color: #10b981;
            background: #f0fdf4;
        }

        .file-input-label.has-file {
            border-color: #10b981;
            background: #ecfdf5;
        }

        /* تحسينات زر الإرسال */
        .submit-btn {
            transition: all 0.3s ease;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        /* رسائل التنبيه */
        .alert {
            animation: slideUp 0.5s ease-out;
        }
    </style>
</head>

<body class="text-gray-800 overflow-x-hidden relative">
    @include('partials.main-header')

    <div id="readingProgress"
        class="fixed top-0 right-0 h-1 bg-gradient-to-r from-emerald-400 via-green-500 to-emerald-400 z-50 transition-all duration-300">
    </div>

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

    <main class="relative z-10 max-w-4xl mx-auto px-4 lg:px-6 py-10 lg:py-14 space-y-10 lg:space-y-12">
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
            <div class="px-6 py-8 lg:px-10 lg:py-12">
                <!-- العنوان -->
                <div class="text-center mb-8">
                    <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-emerald-400 to-teal-400 rounded-full flex items-center justify-center shadow-lg">
                        <i class="fas fa-user-plus text-white text-3xl"></i>
                    </div>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold font-serif text-gray-800 mb-3">
                        تسجيل بيانات شخص
                    </h1>
                    <p class="text-gray-600 text-lg leading-relaxed">
                        يرجى ملء البيانات التالية لتسجيل الشخص
                    </p>
                </div>

                <!-- رسائل النجاح/الخطأ -->
                @if (session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border-r-4 border-emerald-500 rounded-lg alert">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
                            <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 p-4 bg-red-50 border-r-4 border-red-500 rounded-lg alert">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
                            <p class="text-red-800 font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-r-4 border-red-500 rounded-lg alert">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-exclamation-triangle text-red-600 text-xl mt-0.5"></i>
                            <div class="flex-1">
                                <p class="text-red-800 font-bold mb-2">يرجى تصحيح الأخطاء التالية:</p>
                                <ul class="list-disc list-inside space-y-1 text-red-700">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- نموذج التسجيل -->
                <form action="{{ route('users.register.store') }}" method="POST" enctype="multipart/form-data"
                    id="registrationForm" class="space-y-6">
                    @csrf

                    <div class="bg-white rounded-2xl p-6 lg:p-8 border border-gray-100 shadow-md">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- الاسم الكامل -->
                            <div class="md:col-span-2">
                                <label for="name" class="block text-sm font-bold text-gray-700 mb-2">
                                    الاسم الكامل <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl text-right focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="أدخل الاسم الكامل">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- العمر -->
                            <div>
                                <label for="age" class="block text-sm font-bold text-gray-700 mb-2">
                                    العمر <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="age" id="age" value="{{ old('age') }}" required min="1"
                                    max="150"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl text-right focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="أدخل العمر">
                                @error('age')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- الصورة الشخصية -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    الصورة الشخصية <span class="text-gray-500 text-xs">(اختياري)</span>
                                </label>
                                <div class="file-input-wrapper">
                                    <input type="file" name="avatar" id="avatar" accept="image/*"
                                        class="hidden">
                                    <label for="avatar" id="avatarLabel"
                                        class="file-input-label flex flex-col items-center justify-center gap-3 cursor-pointer">
                                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>
                                        <div class="text-center">
                                            <p class="text-gray-700 font-medium">اختر صورة شخصية</p>
                                            <p class="text-xs text-gray-500 mt-1">الحجم الأقصى: 2 ميجابايت</p>
                                            <p class="text-xs text-gray-500">الصيغ المدعومة: JPEG, PNG, JPG, GIF, WEBP</p>
                                        </div>
                                    </label>
                                </div>
                                <div id="avatarPreview" class="hidden mt-4">
                                    <div class="relative inline-block">
                                        <img id="previewImage" src="" alt="معاينة الصورة"
                                            class="w-32 h-32 object-cover rounded-xl border-2 border-emerald-500">
                                        <button type="button" id="removeAvatar"
                                            class="absolute -top-2 -left-2 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors">
                                            <i class="fas fa-times text-sm"></i>
                                        </button>
                                    </div>
                                    <p id="fileName" class="mt-2 text-sm text-gray-600"></p>
                                </div>
                                @error('avatar')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- زر الإرسال -->
                    <div class="flex justify-center">
                        <button type="submit"
                            class="submit-btn w-full md:w-auto px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center gap-2">
                            <i class="fas fa-save"></i>
                            <span>حفظ البيانات</span>
                        </button>
                    </div>
                </form>
            </div>
        </article>
    </main>

    <script>
        // معاينة الصورة
        const avatarInput = document.getElementById('avatar');
        const avatarLabel = document.getElementById('avatarLabel');
        const avatarPreview = document.getElementById('avatarPreview');
        const previewImage = document.getElementById('previewImage');
        const fileName = document.getElementById('fileName');
        const removeAvatarBtn = document.getElementById('removeAvatar');

        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // التحقق من حجم الملف (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('حجم الصورة كبير جداً. الحد الأقصى هو 2 ميجابايت.');
                    avatarInput.value = '';
                    return;
                }

                // التحقق من نوع الملف
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    alert('نوع الملف غير مدعوم. يرجى اختيار صورة بصيغة JPEG, PNG, JPG, GIF, أو WEBP.');
                    avatarInput.value = '';
                    return;
                }

                // عرض المعاينة
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    fileName.textContent = `الملف: ${file.name} (${(file.size / 1024).toFixed(2)} KB)`;
                    avatarPreview.classList.remove('hidden');
                    avatarLabel.classList.add('has-file');
                    avatarLabel.innerHTML = `
                        <i class="fas fa-check-circle text-emerald-600 text-2xl"></i>
                        <span class="text-emerald-700 font-medium">تم اختيار الصورة</span>
                    `;
                };
                reader.readAsDataURL(file);
            }
        });

        // إزالة الصورة
        removeAvatarBtn.addEventListener('click', function() {
            avatarInput.value = '';
            avatarPreview.classList.add('hidden');
            avatarLabel.classList.remove('has-file');
            avatarLabel.innerHTML = `
                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>
                <div class="text-center">
                    <p class="text-gray-700 font-medium">اختر صورة شخصية</p>
                    <p class="text-xs text-gray-500 mt-1">الحجم الأقصى: 2 ميجابايت</p>
                    <p class="text-xs text-gray-500">الصيغ المدعومة: JPEG, PNG, JPG, GIF, WEBP</p>
                </div>
            `;
        });

        // شريط التقدم
        const progressBar = document.getElementById('readingProgress');
        window.addEventListener('scroll', () => {
            const scrollTop = window.scrollY;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            progressBar.style.width = docHeight > 0 ? `${(scrollTop / docHeight) * 100}%` : '0%';
        });

        // منع إرسال النموذج مرتين
        const form = document.getElementById('registrationForm');
        let isSubmitting = false;

        form.addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return;
            }
            isSubmitting = true;
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                جارٍ الحفظ...
            `;
        });
    </script>
</body>

</html>
