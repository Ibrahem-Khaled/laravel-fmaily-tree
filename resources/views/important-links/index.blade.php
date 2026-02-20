<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الروابط المهمة - تطبيقات ومواقع</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Tajawal', sans-serif; background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 50%, #bbf7d0 100%); min-height: 100vh; }
        h1, h2, h3 { font-family: 'Amiri', serif; }
        @keyframes slideIn { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .slide-in { animation: slideIn 0.6s ease-out forwards; }
        .glass-effect { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.3); }
        .green-glow { box-shadow: 0 0 40px rgba(34, 197, 94, 0.3); }
        .green-glow-hover:hover { box-shadow: 0 0 60px rgba(34, 197, 94, 0.5); transform: translateY(-5px); }
        .gradient-text { background: linear-gradient(135deg, #16a34a 0%, #22c55e 50%, #4ade80 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .link-card { transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); opacity: 0; transform: translateY(20px); }
        .link-card.visible { opacity: 1; transform: translateY(0); }
        .link-card:hover { transform: translateY(-6px) scale(1.02); }
        .link-card-image-wrap { aspect-ratio: 1 / 1; width: 100%; max-height: 140px; position: relative; overflow: hidden; background: linear-gradient(to bottom right, #dcfce7, #bbf7d0); }
        .link-card-image-wrap img { width: 100%; height: 100%; object-fit: contain; object-position: center; display: block; }
        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: #f0fdf4; }
        ::-webkit-scrollbar-thumb { background: linear-gradient(180deg, #22c55e, #16a34a); border-radius: 5px; }
    </style>
</head>

<body class="text-gray-800 relative overflow-x-hidden">
    @include('partials.main-header')

    <div class="container mx-auto px-4 py-6 lg:py-8 relative z-10">
        <div class="mb-8">
            <div class="glass-effect p-4 lg:p-6 rounded-3xl green-glow">
                <h1 class="text-2xl lg:text-3xl font-bold gradient-text mb-2">الروابط المهمة</h1>
                <p class="text-gray-600">تطبيقات ومواقع مقترحة من العائلة. اضغط على أي بطاقة لعرض التفاصيل ثم التحميل أو زيارة الموقع.</p>
                @if(session('success'))
                    <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl" role="alert">
                        <i class="fas fa-check-circle ml-2"></i>{{ session('success') }}
                    </div>
                @endif
                <div class="mt-4">
                    <button type="button" onclick="openSuggestModal()" class="inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all">
                        <i class="fas fa-plus-circle"></i>
                        <span>اقترح رابطاً أو تطبيقاً</span>
                    </button>
                </div>
            </div>
        </div>

        @if($links->count() > 0)
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-3 lg:gap-4 max-w-5xl mx-auto" id="linksGrid">
                @foreach($links as $link)
                    <div class="link-card glass-effect rounded-xl lg:rounded-2xl overflow-hidden green-glow-hover cursor-pointer max-w-[180px] mx-auto w-full" data-link-id="{{ $link->id }}" onclick="openDetailModal({{ $link->id }})">
                        <div class="link-card-image-wrap relative">
                            <img src="{{ $link->image_url }}" alt="{{ $link->title }}" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22 fill=%22%2322c55e%22%3E%3Cpath d=%22M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm3.9-2.53c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z%22/%3E%3C/svg%3E'; this.onerror=null;">
                            <div class="absolute bottom-1 right-1 px-1.5 py-0.5 rounded bg-black/50 text-white text-[10px]">
                                {{ $link->type === 'app' ? 'تطبيق' : 'موقع' }}
                            </div>
                        </div>
                        <div class="p-2.5">
                            <div class="flex items-center gap-1.5 min-h-[2.5rem]">
                                @if($link->icon)
                                    <i class="{{ $link->icon }} text-green-600 text-sm flex-shrink-0"></i>
                                @else
                                    <i class="fas fa-link text-green-600 text-sm flex-shrink-0"></i>
                                @endif
                                <h2 class="text-xs lg:text-sm font-bold text-gray-800 truncate">{{ $link->title }}</h2>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center glass-effect p-8 lg:p-16 rounded-3xl green-glow">
                <i class="fas fa-link text-6xl text-green-400 mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-800">لا توجد روابط معتمدة حالياً</h3>
                <p class="mt-2 text-gray-600">يمكنك اقتراح تطبيق أو موقع باستخدام الزر أعلاه.</p>
            </div>
        @endif
    </div>

    {{-- مودال التفاصيل --}}
    <div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeDetailModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div id="detailModalContent" class="relative glass-effect rounded-3xl green-glow max-w-lg w-full max-h-[90vh] overflow-y-auto p-6 lg:p-8">
                <button type="button" onclick="closeDetailModal()" class="absolute left-4 top-4 p-2 rounded-full bg-gray-200 hover:bg-gray-300 text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
                <div id="detailBody"><!-- يُملأ بالجافاسكربت --></div>
            </div>
        </div>
    </div>

    {{-- مودال اقتراح رابط --}}
    <div id="suggestModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeSuggestModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative glass-effect rounded-3xl green-glow max-w-lg w-full max-h-[90vh] overflow-y-auto p-6 lg:p-8">
                <button type="button" onclick="closeSuggestModal()" class="absolute left-4 top-4 p-2 rounded-full bg-gray-200 hover:bg-gray-300 text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
                <h3 class="text-xl font-bold gradient-text mb-6">اقتراح رابط أو تطبيق</h3>
                <form action="{{ route('important-links.suggest') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(!auth()->check())
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1">اسمك <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1">رقم هاتفك <span class="text-red-500">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            @error('phone')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    @endif
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">العنوان (اسم التطبيق أو الموقع) <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        @error('title')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">الرابط (تحميل أو زيارة) <span class="text-red-500">*</span></label>
                        <input type="url" name="url" value="{{ old('url') }}" required placeholder="https://..." class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        @error('url')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">النوع <span class="text-red-500">*</span></label>
                        <select name="type" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="website" {{ old('type') === 'website' ? 'selected' : '' }}>موقع</option>
                            <option value="app" {{ old('type') === 'app' ? 'selected' : '' }}>تطبيق</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">الوصف (اختياري)</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ old('description') }}</textarea>
                        @error('description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-1">صورة (اختياري)</label>
                        <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-xl">
                        @error('image')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="w-full py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all">
                        إرسال الاقتراح
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const linksData = @json($links->keyBy('id'));

        function openDetailModal(id) {
            const link = linksData[id];
            if (!link) return;
            const isApp = link.type === 'app';
            const submitterHtml = link.submitter ? `<p class="text-sm text-gray-600 mt-2"><span class="font-bold">من أضافه:</span> ${link.submitter.name}</p>` : '';
            document.getElementById('detailBody').innerHTML = `
                <div class="mb-4 rounded-2xl overflow-hidden bg-gray-100" style="aspect-ratio:1/1;max-height:280px;">
                    <img src="${link.image_url}" alt="${link.title}" class="w-full h-full object-contain object-center" style="display:block;" onerror="this.style.display='none'">
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">${link.title}</h2>
                <span class="inline-block px-3 py-1 rounded-full text-sm ${isApp ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'}">${isApp ? 'تطبيق' : 'موقع'}</span>
                ${link.description ? `<p class="text-gray-600 mt-4">${link.description}</p>` : ''}
                ${submitterHtml}
                <div class="mt-6 flex gap-3">
                    <a href="${link.url}" ${link.open_in_new_tab ? 'target="_blank" rel="noopener noreferrer"' : ''} class="flex-1 py-3 rounded-xl font-bold text-center text-white shadow-lg transition-all ${isApp ? 'bg-green-500 hover:bg-green-600' : 'bg-blue-500 hover:bg-blue-600'}">
                        ${isApp ? '<i class="fas fa-download ml-2"></i> تحميل' : '<i class="fas fa-external-link-alt ml-2"></i> زيارة الموقع'}
                    </a>
                </div>
            `;
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

        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.link-card');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => entry.target.classList.add('visible'), index * 80);
                        observer.unobserve(entry.target);
                    }
                });
            }, { rootMargin: '0px 0px -50px 0px' });
            cards.forEach(card => observer.observe(card));
        });
    </script>
</body>

</html>
