<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $person->full_name }} - الملف الشخصي</title>

    {{-- استخدام نفس إعدادات Tailwind والخطوط --}}
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
                                transform: 'translateY(0px)'
                            },
                            '50%': {
                                transform: 'translateY(-10px)'
                            },
                        },
                        fadeInUp: {
                            'from': {
                                opacity: '0',
                                transform: 'translateY(20px)'
                            },
                            'to': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            },
                        }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'fade-in-up': 'fadeInUp 0.5s ease-out forwards',
                    },
                    boxShadow: {
                        'green-glow': '0 0 40px rgba(34, 197, 94, 0.2)',
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Amiri:wght@400;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
        }

        h1,
        h2,
        h3,
        h4 {
            font-family: 'Amiri', serif;
        }

        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f0fdf4;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #22c55e, #16a34a);
            border-radius: 5px;
        }

        .animate-delay-100 {
            animation-delay: 100ms;
        }

        .animate-delay-200 {
            animation-delay: 200ms;
        }

        .animate-delay-300 {
            animation-delay: 300ms;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-green-50 to-emerald-50 text-gray-800">
    @include('partials.main-header')
    <main class="container mx-auto px-4 py-8 lg:py-12 relative z-10 max-w-7xl">
        <div class="mb-8">
            <a href="{{ url()->previous() }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white/60 backdrop-blur-md border border-white/30 rounded-full hover:bg-white/90 transition-all duration-300 group shadow-sm hover:shadow-lg">
                <svg class="w-5 h-5 text-green-600 transition-transform group-hover:-translate-x-1" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="font-medium text-gray-700">العودة للخلف</span>
            </a>
        </div>

        <div class="lg:grid lg:grid-cols-3 lg:gap-12">

            {{-- العمود الرئيسي: الصورة والمعلومات الأساسية --}}
            <div class="lg:col-span-2">
                <article
                    class="bg-white/80 backdrop-blur-md border border-white/30 rounded-3xl overflow-hidden shadow-green-glow">
                    <header class="p-6 lg:p-10 bg-gradient-to-br from-green-50/50 to-emerald-50/20">
                        <div class="flex flex-col sm:flex-row items-center text-center sm:text-right gap-6">
                            <img src="{{ $person->avatar }}" alt="{{ $person->first_name }}"
                                class="flex-shrink-0 w-32 h-32 lg:w-40 lg:h-40 rounded-full border-4 border-white shadow-lg object-cover">
                            <div class="flex-grow">
                                <h1
                                    class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4 leading-relaxed font-serif bg-gradient-to-r from-green-600 to-emerald-500 text-transparent bg-clip-text">
                                    {{ $person->full_name }}
                                </h1>
                                {{-- حاوية المعلومات المختصرة --}}
                                <div
                                    class="flex flex-wrap items-center justify-center sm:justify-start gap-x-6 gap-y-3 text-base text-gray-600">
                                    {{-- العمر وتاريخ الميلاد للرجال فقط --}}
                                    @if ($person->gender === 'male')
                                        @if ($person->age)
                                            <div class="flex items-center gap-2">
                                                <span>(العمر: {{ $person->age }} عاماً)</span>
                                            </div>
                                        @endif
                                        @if ($person->birth_date)
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-birthday-cake text-green-500"></i>
                                                <span>{{ $person->birth_date }}</span>
                                            </div>
                                        @endif
                                    @endif

                                    {{-- عرض الجنس --}}
                                    @if ($person->gender)
                                        <div class="flex items-center gap-2">
                                            <i class="fas {{ $person->gender == 'male' ? 'fa-mars text-blue-500' : 'fa-venus text-pink-500' }}"></i>
                                            <span>{{ $person->gender == 'male' ? 'ذكر' : 'أنثى' }}</span>
                                        </div>
                                    @endif

                                    {{-- حالة الوفاة --}}
                                    @if ($person->death_date)
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-dove text-gray-600"></i>
                                            <span>{{ $person->gender == 'female' ? 'رحمها الله' : 'رحمه الله' }}</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- زر عرض في شجرة العائلة --}}
                                <div class="mt-4">
                                    <a href="{{ route('sila') }}#person-{{ $person->id }}"
                                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 hover:scale-105">
                                        <i class="fas fa-sitemap"></i>
                                        <span>عرض في شجرة العائلة</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </header>

                    @if ($person->biography)
                        <div class="p-6 lg:p-10 border-t border-green-200/60">
                            <div
                                class="prose prose-lg max-w-none prose-p:leading-relaxed prose-headings:font-serif prose-headings:text-green-700">
                                <h2>نبذة تعريفية</h2>
                                <p>{{ $person->biography }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- معلومات إضافية --}}
                    <div class="p-6 lg:p-10 border-t border-green-200/60">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- تاريخ الميلاد (للرجال فقط) --}}
                            @if ($person->gender === 'male' && $person->birth_date)
                                <div class="bg-green-50 rounded-xl p-4">
                                    <div class="flex items-center gap-3 mb-2">
                                        <i class="fas fa-birthday-cake text-green-600 text-xl"></i>
                                        <h3 class="font-bold text-gray-800">تاريخ الميلاد</h3>
                                    </div>
                                    <p class="text-gray-700">{{ $person->birth_date }}</p>
                                </div>
                            @endif

                            {{-- العمر (للرجال فقط) --}}
                            @if ($person->gender === 'male' && $person->age)
                                <div class="bg-green-50 rounded-xl p-4">
                                    <div class="flex items-center gap-3 mb-2">
                                        <i class="fas fa-calendar-alt text-green-600 text-xl"></i>
                                        <h3 class="font-bold text-gray-800">العمر</h3>
                                    </div>
                                    <p class="text-gray-700">{{ $person->age }} سنة</p>
                                </div>
                            @endif

                            {{-- مكان الميلاد --}}
                            @if ($person->birth_place)
                                <div class="bg-green-50 rounded-xl p-4">
                                    <div class="flex items-center gap-3 mb-2">
                                        <i class="fas fa-map-marker-alt text-green-600 text-xl"></i>
                                        <h3 class="font-bold text-gray-800">مكان الميلاد</h3>
                                    </div>
                                    <p class="text-gray-700">{{ $person->birth_place }}</p>
                                </div>
                            @endif

                            {{-- مكان الإقامة --}}
                            @if ($person->location)
                                <div class="bg-green-50 rounded-xl p-4">
                                    <div class="flex items-center gap-3 mb-2">
                                        <i class="fas fa-home text-green-600 text-xl"></i>
                                        <h3 class="font-bold text-gray-800">مكان الإقامة</h3>
                                    </div>
                                    <p class="text-gray-700">{{ $person->location->name }}</p>
                                </div>
                            @endif

                            {{-- المهنة --}}
                            @if ($person->occupation)
                                <div class="bg-green-50 rounded-xl p-4">
                                    <div class="flex items-center gap-3 mb-2">
                                        <i class="fas fa-briefcase text-green-600 text-xl"></i>
                                        <h3 class="font-bold text-gray-800">المهنة</h3>
                                    </div>
                                    <p class="text-gray-700">{{ $person->occupation }}</p>
                                </div>
                            @endif

                            {{-- تاريخ الوفاة --}}
                            @if ($person->death_date)
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <div class="flex items-center gap-3 mb-2">
                                        <i class="fas fa-dove text-gray-600 text-xl"></i>
                                        <h3 class="font-bold text-gray-800">تاريخ الوفاة</h3>
                                    </div>
                                    <p class="text-gray-700">{{ $person->death_date }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- الأب والأم --}}
                    @if ($person->parent || $person->mother)
                        <div class="p-6 lg:p-10 border-t border-green-200/60">
                            <h2 class="text-2xl font-bold font-serif mb-6 text-green-700">الوالدين</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if ($person->parent)
                                    <a href="{{ route('people.profile.show', $person->parent->id) }}"
                                        class="group bg-white rounded-xl p-4 shadow-md hover:shadow-lg transition-all duration-300 border border-green-200 hover:border-green-400">
                                        <div class="flex items-center gap-4">
                                            <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-green-300 flex-shrink-0">
                                                @if ($person->parent->photo_url)
                                                    <img src="{{ asset('storage/' . $person->parent->photo_url) }}" alt="{{ $person->parent->full_name }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center">
                                                        <i class="fas fa-male text-green-600 text-2xl"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow">
                                                <h3 class="font-bold text-gray-800 group-hover:text-green-600 transition-colors">{{ $person->parent->full_name }}</h3>
                                                <p class="text-sm text-gray-600">الأب</p>
                                                @if ($person->parent->death_date)
                                                    <p class="text-xs text-gray-500 mt-1">رحمه الله</p>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                @endif

                                @if ($person->mother)
                                    <a href="{{ route('people.profile.show', $person->mother->id) }}"
                                        class="group bg-white rounded-xl p-4 shadow-md hover:shadow-lg transition-all duration-300 border border-green-200 hover:border-green-400">
                                        <div class="flex items-center gap-4">
                                            <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-pink-300 flex-shrink-0">
                                                @if ($person->mother->photo_url)
                                                    <img src="{{ asset('storage/' . $person->mother->photo_url) }}" alt="{{ $person->mother->full_name }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full bg-gradient-to-br from-pink-100 to-pink-200 flex items-center justify-center">
                                                        <i class="fas fa-female text-pink-600 text-2xl"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow">
                                                <h3 class="font-bold text-gray-800 group-hover:text-green-600 transition-colors">{{ $person->mother->full_name }}</h3>
                                                <p class="text-sm text-gray-600">الأم</p>
                                                @if ($person->mother->death_date)
                                                    <p class="text-xs text-gray-500 mt-1">رحمها الله</p>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- الأخوة --}}
                    @if ($siblings && $siblings->count() > 0)
                        <div class="p-6 lg:p-10 border-t border-green-200/60">
                            <h2 class="text-2xl font-bold font-serif mb-6 text-green-700">الإخوة والأخوات</h2>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                @foreach ($siblings as $sibling)
                                    <a href="{{ route('people.profile.show', $sibling->id) }}"
                                        class="group bg-white rounded-xl p-4 shadow-md hover:shadow-lg transition-all duration-300 border border-green-200 hover:border-green-400 text-center">
                                        <div class="w-20 h-20 rounded-full overflow-hidden border-2 {{ $sibling->gender == 'male' ? 'border-blue-300' : 'border-pink-300' }} mx-auto mb-3">
                                            @if ($sibling->photo_url)
                                                <img src="{{ asset('storage/' . $sibling->photo_url) }}" alt="{{ $sibling->full_name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br {{ $sibling->gender == 'male' ? 'from-blue-100 to-blue-200' : 'from-pink-100 to-pink-200' }} flex items-center justify-center">
                                                    <i class="fas {{ $sibling->gender == 'male' ? 'fa-male text-blue-600' : 'fa-female text-pink-600' }} text-3xl"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <h3 class="font-bold text-sm text-gray-800 group-hover:text-green-600 transition-colors line-clamp-2">{{ $sibling->full_name }}</h3>
                                        @if ($sibling->death_date)
                                            <p class="text-xs text-gray-500 mt-1">{{ $sibling->gender == 'male' ? 'رحمه الله' : 'رحمها الله' }}</p>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- الزوجات/الزوج --}}
                    @if ($spouses && $spouses->count() > 0)
                        <div class="p-6 lg:p-10 border-t border-green-200/60">
                            <h2 class="text-2xl font-bold font-serif mb-6 text-green-700">{{ $person->gender == 'male' ? 'الزوجات' : 'الزوج' }}</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach ($spouses as $spouse)
                                    <a href="{{ route('people.profile.show', $spouse->id) }}"
                                        class="group bg-white rounded-xl p-4 shadow-md hover:shadow-lg transition-all duration-300 border border-green-200 hover:border-green-400">
                                        <div class="flex items-center gap-4">
                                            <div class="w-16 h-16 rounded-full overflow-hidden border-2 {{ $spouse->gender == 'male' ? 'border-blue-300' : 'border-pink-300' }} flex-shrink-0">
                                                @if ($spouse->photo_url)
                                                    <img src="{{ asset('storage/' . $spouse->photo_url) }}" alt="{{ $spouse->full_name }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full bg-gradient-to-br {{ $spouse->gender == 'male' ? 'from-blue-100 to-blue-200' : 'from-pink-100 to-pink-200' }} flex items-center justify-center">
                                                        <i class="fas {{ $spouse->gender == 'male' ? 'fa-male text-blue-600' : 'fa-female text-pink-600' }} text-2xl"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow">
                                                <h3 class="font-bold text-gray-800 group-hover:text-green-600 transition-colors">{{ $spouse->full_name }}</h3>
                                                <p class="text-sm text-gray-600">{{ $spouse->gender == 'male' ? 'زوج' : 'زوجة' }}</p>
                                                @if ($spouse->death_date)
                                                    <p class="text-xs text-gray-500 mt-1">{{ $spouse->gender == 'male' ? 'رحمه الله' : 'رحمها الله' }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- الأبناء --}}
                    @if ($children && $children->count() > 0)
                        <div class="p-6 lg:p-10 border-t border-green-200/60">
                            <h2 class="text-2xl font-bold font-serif mb-6 text-green-700">الأبناء</h2>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                @foreach ($children as $child)
                                    <a href="{{ route('people.profile.show', $child->id) }}"
                                        class="group bg-white rounded-xl p-4 shadow-md hover:shadow-lg transition-all duration-300 border border-green-200 hover:border-green-400 text-center">
                                        <div class="w-20 h-20 rounded-full overflow-hidden border-2 {{ $child->gender == 'male' ? 'border-blue-300' : 'border-pink-300' }} mx-auto mb-3">
                                            @if ($child->photo_url)
                                                <img src="{{ asset('storage/' . $child->photo_url) }}" alt="{{ $child->full_name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br {{ $child->gender == 'male' ? 'from-blue-100 to-blue-200' : 'from-pink-100 to-pink-200' }} flex items-center justify-center">
                                                    <i class="fas {{ $child->gender == 'male' ? 'fa-male text-blue-600' : 'fa-female text-pink-600' }} text-3xl"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <h3 class="font-bold text-sm text-gray-800 group-hover:text-green-600 transition-colors line-clamp-2">{{ $child->full_name }}</h3>
                                        @if ($child->death_date)
                                            <p class="text-xs text-gray-500 mt-1">{{ $child->gender == 'male' ? 'رحمه الله' : 'رحمها الله' }}</p>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </article>
            </div>

            {{-- العمود الجانبي: الأوسمة والتكريمات --}}
            <aside class="lg:col-span-1 mt-12 lg:mt-0">
                <div class="sticky top-12">
                    <h2
                        class="text-3xl font-bold font-serif mb-6 bg-gradient-to-r from-green-600 to-emerald-500 text-transparent bg-clip-text flex items-center gap-3">
                        <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M12.963 2.286a.75.75 0 00-1.071 1.052A6.75 6.75 0 0115.75 12c0 2.56-1.42 4.823-3.568 5.952a.75.75 0 00.568 1.402A8.25 8.25 0 0017.25 12c0-3.36-2.02-6.22-4.932-7.564a.75.75 0 00-.355-.15z"
                                clip-rule="evenodd" />
                            <path
                                d="M12 2.25a.75.75 0 000 1.5v16.5a.75.75 0 000 1.5h.75a.75.75 0 000-1.5H12V3.75a.75.75 0 00-.75-.75h-.75a.75.75 0 00.75-.75zM8.25 12a3.75 3.75 0 117.5 0 3.75 3.75 0 01-7.5 0zM12 14.25a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z" />
                        </svg>
                        الأوسمة والتكريمات
                    </h2>

                    @if ($person->padges->isNotEmpty())
                        <div class="space-y-4">
                            @foreach ($person->padges as $index => $padge)
                                <div class="opacity-0 animate-fade-in-up"
                                    style="animation-delay: {{ $index * 100 }}ms;">
                                    <div
                                        class="group flex items-start gap-4 p-4 bg-white/90 backdrop-blur-md rounded-2xl border border-transparent hover:border-green-300 hover:shadow-lg transition-all duration-300">
                                        <div
                                            class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 rounded-lg flex items-center justify-center transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                                            @if (isset($padge->image))
                                                <img src="{{ asset('storage/' . $padge->image) }}" class="w-7 h-7">
                                            @else
                                                <svg class="w-7 h-7 text-green-600" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M15.188 8.42a.75.75 0 01.293.972l-3.033 7.076a.75.75 0 01-1.341-.575l.386-1.714a3.75 3.75 0 00-.83-2.324l-3.14-4.593a.75.75 0 111.233-.842l2.378 3.481a3.75 3.75 0 004.47 1.293l1.587-.68a.75.75 0 01.972.293z"
                                                        clip-rule="evenodd" />
                                                    <path
                                                        d="M9.919 4.31a.75.75 0 01.782.646l.704 4.223a.75.75 0 01-1.48.248l-.704-4.223a.75.75 0 01.7- .894zM10 2a.75.75 0 01.75.75v.008a.75.75 0 01-1.5 0V2.75A.75.75 0 0110 2zM5.992 5.514a.75.75 0 01.884-.462l4.223.704a.75.75 0 01-.248 1.48l-4.223-.704a.75.75 0 01-.413-1.018zM3.204 9.11a.75.75 0 011.018.413l.704 4.223a.75.75 0 11-1.48.248l-.704-4.223a.75.75 0 01.462-.884z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="flex-grow">
                                            <h4 class="font-bold text-gray-800 text-lg">{{ $padge->name }}</h4>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center p-6 bg-white/60 rounded-2xl">
                            <p class="text-gray-500">لم يحصل على أي أوسمة بعد.</p>
                        </div>
                    @endif
                </div>
            </aside>

        </div>
    </main>
</body>

</html>
