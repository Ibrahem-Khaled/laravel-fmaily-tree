{{-- ================================================================
IMPORTANT LINKS – تطبيقات تهمك (مصنفة ومحدثة)
================================================================ --}}
@if(isset($importantLinks) && $importantLinks->count() > 0)
    <style>
        .il-section-card {
            position: relative;
            border-radius: 1.25rem;
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.05);
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            overflow: hidden;
            cursor: pointer;
        }

        .il-section-card:hover {
            transform: translateY(-5px);
            border-color: rgba(16, 185, 129, 0.2);
            box-shadow: 0 12px 24px rgba(16, 185, 129, 0.08), 0 0 0 1px rgba(16, 185, 129, 0.08);
        }

        .il-section-card-img {
            aspect-ratio: 1;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            transition: all 0.4s ease;
        }

        .il-section-card:hover .il-section-card-img {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        }

        .il-section-modal {
            transition: opacity 0.3s ease;
        }

        .il-scrollbar-home::-webkit-scrollbar {
            width: 6px;
        }

        .il-scrollbar-home::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        .il-scrollbar-home::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #10b981, #047857);
            border-radius: 3px;
        }
    </style>

    <section
        class="py-4 md:py-6 lg:py-8 bg-gradient-to-br from-slate-50 via-emerald-50/20 to-slate-50 relative overflow-hidden"
        id="important-links-section">
        {{-- Decorative Background Elements --}}
        <div class="absolute top-0 right-0 w-80 h-80 bg-emerald-100/30 rounded-full blur-3xl opacity-60 -mr-20 -mt-20">
        </div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-green-100/30 rounded-full blur-3xl opacity-60 -ml-20 -mb-20">
        </div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">

            {{-- Section Header --}}
            <!-- <div class="text-right mb-8 md:mb-10">
                <div class="flex items-center justify-between flex-wrap gap-4" dir="rtl">
                    <div>
                        <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-slate-900 leading-tight mb-2">تطبيقات ومواقع تهمك</h2>
                        <p class="text-slate-500 text-sm md:text-base">دليل متكامل للروابط والمنصات الهامة لعائلة السريع</p>
                    </div>
                    <button type="button" onclick="openIlSuggestModal()"
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white px-5 py-2.5 rounded-2xl text-sm font-bold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                        <i class="fas fa-plus-circle"></i>
                        اقترح تطبيقاً
                    </button>
                </div>
            </div> -->

            @if(session('success') && request()->is('/'))
                <div class="mb-6 p-4 rounded-2xl border border-emerald-200 bg-emerald-50/80 backdrop-blur text-emerald-900 text-right text-sm shadow-sm"
                    dir="rtl">
                    <i class="fas fa-check-circle text-emerald-600 ml-2"></i>{{ session('success') }}
                </div>
            @endif

            @php
                $uncategorizedLinks = $importantLinks->whereNull('category_id');
            @endphp

            <div class="space-y-4 md:space-y-6">
                {{-- Loop Through Categories --}}
                @foreach($importantLinkCategories as $category)
                    @php
                        $categoryLinks = $importantLinks->where('category_id', $category->id);
                    @endphp

                    @if($categoryLinks->isNotEmpty())
                        <div class="p-4 sm:p-6">
                            <h3 class="text-base md:text-lg font-bold text-slate-900 mb-4 flex items-center gap-3 border-b border-slate-100 pb-3"
                                dir="rtl">
                                <span
                                    class="flex items-center justify-center w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 shadow-sm border border-emerald-100/50">
                                    <i class="{{ $category->icon ?: 'fas fa-folder' }}"></i>
                                </span>
                                <span>{{ $category->name }}</span>
                                @if($category->description)
                                    <span
                                        class="text-xs text-slate-400 font-normal mr-2 hidden sm:inline">{{ $category->description }}</span>
                                @endif
                            </h3>

                            <div
                                class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 xl:grid-cols-7 gap-2 md:gap-3">
                                @foreach($categoryLinks as $ilLink)
                                    <div class="il-section-card group" data-il-id="{{ $ilLink->id }}"
                                        onclick="openIlDetailModal({{ $ilLink->id }})" role="button" tabindex="0"
                                        onkeydown="if(event.key==='Enter')openIlDetailModal({{ $ilLink->id }})">
                                        <div class="il-section-card-img">
                                            <img src="{{ $ilLink->image_url }}" alt="{{ $ilLink->title }}"
                                                class="max-w-full max-h-full object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-500"
                                                onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22 fill=%22%2310b981%22%3E%3Cpath d=%22M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm3.9-2.53c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z%22/%3E%3C/svg%3E'; this.onerror=null;">
                                        </div>
                                        <div class="px-3 py-3 border-t border-slate-50 text-right">
                                            <h4
                                                class="font-bold text-slate-800 text-xs md:text-sm leading-snug line-clamp-2 group-hover:text-emerald-600 transition-colors">
                                                {{ $ilLink->title }}</h4>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach

                {{-- Uncategorized Links --}}
                @if($uncategorizedLinks->isNotEmpty())
                    <div class="p-4 sm:p-6">
                        <h3 class="text-base md:text-lg font-bold text-slate-900 mb-4 flex items-center gap-3 border-b border-slate-100 pb-3"
                            dir="rtl">
                            <span
                                class="flex items-center justify-center w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 shadow-sm border border-emerald-100/50">
                                <i class="fas fa-link"></i>
                            </span>
                            <span>روابط عامة</span>
                        </h3>

                        <div
                            class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 xl:grid-cols-7 gap-2 md:gap-3">
                            @foreach($uncategorizedLinks as $ilLink)
                                <div class="il-section-card group" data-il-id="{{ $ilLink->id }}"
                                    onclick="openIlDetailModal({{ $ilLink->id }})" role="button" tabindex="0"
                                    onkeydown="if(event.key==='Enter')openIlDetailModal({{ $ilLink->id }})">
                                    <div class="il-section-card-img">
                                        <img src="{{ $ilLink->image_url }}" alt="{{ $ilLink->title }}"
                                            class="max-w-full max-h-full object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-500"
                                            onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22 fill=%22%2310b981%22%3E%3Cpath d=%22M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm3.9-2.53c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z%22/%3E%3C/svg%3E'; this.onerror=null;">
                                    </div>
                                    <div class="px-3 py-3 border-t border-slate-50 text-right">
                                        <h4
                                            class="font-bold text-slate-800 text-xs md:text-sm leading-snug line-clamp-2 group-hover:text-emerald-600 transition-colors">
                                            {{ $ilLink->title }}</h4>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Detail Modal --}}
    <div id="ilDetailModal" class="fixed inset-0 z-50 hidden overflow-y-auto il-scrollbar-home il-section-modal"
        aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeIlDetailModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4 lg:p-10">
            <div
                class="relative bg-white rounded-3xl w-full max-w-lg lg:max-w-4xl max-h-[90vh] overflow-hidden flex flex-col shadow-2xl border border-slate-100">
                <button type="button" onclick="closeIlDetailModal()"
                    class="absolute left-4 top-4 z-20 p-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200/50 transition-colors"
                    aria-label="إغلاق">
                    <i class="fas fa-times"></i>
                </button>
                <div id="ilDetailBody" class="overflow-y-auto il-scrollbar-home p-5 lg:p-8 flex-1"></div>
            </div>
        </div>
    </div>

    {{-- Suggest Modal --}}
    <div id="ilSuggestModal" class="fixed inset-0 z-50 hidden overflow-y-auto il-scrollbar-home il-section-modal"
        aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeIlSuggestModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4 lg:p-10">
            <div
                class="relative bg-white rounded-3xl w-full max-w-lg max-h-[90vh] overflow-y-auto il-scrollbar-home p-6 lg:p-8 shadow-2xl border border-slate-100">
                <button type="button" onclick="closeIlSuggestModal()"
                    class="absolute left-4 top-4 p-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200/50 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
                <h3 class="text-xl lg:text-2xl font-bold text-slate-900 mb-1 text-right">اقتراح تطبيق أو موقع</h3>
                <p class="text-slate-500 text-sm mb-6 text-right">بعد المراجعة من الإدارة سيظهر اقتراحك للجميع.</p>
                <form action="{{ route('important-links.suggest') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-4" dir="rtl">
                    @csrf
                    @if(!auth()->check())
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">اسمك <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">رقم هاتفك <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required
                                class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm transition-all">
                        </div>
                    @endif
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">العنوان <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">الفئة</label>
                        <select name="category_id"
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm transition-all">
                            <option value="">-- اختر الفئة (اختياري) --</option>
                            @foreach($importantLinkCategories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <p class="text-xs text-slate-500 mt-2">أدخل <span class="text-emerald-600 font-bold">رابطاً واحداً على
                            الأقل</span> من الخيارات التالية.</p>
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 gap-3 border border-slate-100 p-3 rounded-2xl bg-slate-50/50">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">رابط عام</label>
                            <input type="url" name="url" value="{{ old('url') }}" placeholder="https://..."
                                class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-slate-900 focus:ring-2 focus:ring-emerald-500 text-xs transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">رابط iOS (App Store)</label>
                            <input type="url" name="url_ios" value="{{ old('url_ios') }}" placeholder="App Store"
                                class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-slate-900 focus:ring-2 focus:ring-emerald-500 text-xs transition-all">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1">رابط أندرويد (Google Play)</label>
                            <input type="url" name="url_android" value="{{ old('url_android') }}" placeholder="Google Play"
                                class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-slate-900 focus:ring-2 focus:ring-emerald-500 text-xs transition-all">
                        </div>
                    </div>
                    <input type="hidden" name="type" value="app">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">الوصف</label>
                        <textarea name="description" rows="3"
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm transition-all">{{ old('description') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">صورة غلاف (أيقونة التطبيق)</label>
                        <input type="file" name="image" accept="image/*"
                            class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-all">
                    </div>
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 py-3 rounded-2xl text-white font-bold shadow-md hover:shadow-lg transition-all duration-300">
                        إرسال الاقتراح للمراجعة
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const ilLinksData = @json($importantLinks->keyBy('id'));

            function escIl(s) {
                if (s == null || s === '') return '';
                return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
            }

            window.openIlDetailModal = function (id) {
                const link = ilLinksData[id];
                if (!link) return;
                const isApp = link.type === 'app';
                const newTab = link.open_in_new_tab ? ' target="_blank" rel="noopener noreferrer"' : '';
                const btnBase = 'inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl font-bold text-white shadow-md transition-all hover:scale-[1.02] hover:shadow-lg text-sm';

                const media = Array.isArray(link.media) ? link.media : [];
                let mediaHtml = '';
                if (media.length > 0) {
                    media.forEach(function (m) {
                        const t = m.title ? '<p class="text-sm font-bold text-slate-900 mt-3">' + escIl(m.title) + '</p>' : '';
                        const d = m.description ? '<p class="text-xs text-slate-500 mt-1 leading-relaxed">' + escIl(m.description) + '</p>' : '';
                        if (m.kind === 'video') {
                            mediaHtml += '<div class="mb-6 last:mb-0 rounded-2xl overflow-hidden border border-slate-150 bg-slate-950"><video controls class="w-full max-h-80" src="' + escIl(m.file_url || '') + '"></video></div>' + t + d;
                        } else {
                            mediaHtml += '<div class="mb-6 last:mb-0 rounded-2xl overflow-hidden border border-slate-150 bg-slate-50"><img src="' + escIl(m.file_url || '') + '" alt="" class="w-full object-contain max-h-72 mx-auto" loading="lazy"></div>' + t + d;
                        }
                    });
                } else {
                    mediaHtml = '<div class="rounded-2xl overflow-hidden border border-slate-150 bg-slate-50 mb-4"><img src="' + escIl(link.image_url) + '" alt="" class="w-full object-contain max-h-60 mx-auto py-4" onerror="this.style.display=\'none\'"></div>';
                }

                let actionsHtml = '';
                if (isApp) {
                    const parts = [];
                    if (link.url_ios) parts.push('<a href="' + escIl(link.url_ios) + '"' + newTab + ' class="' + btnBase + ' bg-gradient-to-br from-slate-800 to-slate-950"><i class="fab fa-apple text-base"></i> App Store</a>');
                    if (link.url_android) parts.push('<a href="' + escIl(link.url_android) + '"' + newTab + ' class="' + btnBase + ' bg-gradient-to-br from-emerald-500 to-green-700"><i class="fab fa-google-play"></i> Google Play</a>');
                    if (link.url) parts.push('<a href="' + escIl(link.url) + '"' + newTab + ' class="' + btnBase + ' bg-gradient-to-br from-teal-500 to-emerald-700"><i class="fas fa-link"></i> رابط عام</a>');
                    if (parts.length) actionsHtml = '<div class="flex flex-wrap gap-3 mt-5">' + parts.join('') + '</div>';
                } else if (link.url) {
                    actionsHtml = '<div class="mt-5"><a href="' + escIl(link.url) + '"' + newTab + ' class="' + btnBase + ' bg-gradient-to-br from-sky-500 to-blue-700"><i class="fas fa-external-link-alt"></i> زيارة الموقع</a></div>';
                }

                const submitterHtml = link.submitter ? '<p class="text-xs text-slate-500 mt-3 pt-3 border-t border-slate-100"><span class="text-emerald-600 font-bold">من أضافه:</span> ' + escIl(link.submitter.name) + '</p>' : '';
                const catHtml = link.category ? '<span class="inline-block bg-slate-100 text-slate-700 text-xs px-2.5 py-1 rounded-lg font-bold mb-2">' + escIl(link.category.name) + '</span>' : '';

                document.getElementById('ilDetailBody').innerHTML =
                    '<div class="lg:grid lg:grid-cols-5 lg:gap-8 items-start">' +
                    '<div class="lg:col-span-3 order-2 lg:order-1 mt-4 lg:mt-0">' + mediaHtml + '</div>' +
                    '<div class="lg:col-span-2 order-1 lg:order-2">' +
                    catHtml +
                    '<h2 class="text-xl lg:text-2xl font-bold text-slate-900 mt-1 leading-tight text-right">' + escIl(link.title) + '</h2>' +
                    (link.description ? '<p class="text-slate-600 text-sm mt-3 leading-relaxed text-right">' + escIl(link.description) + '</p>' : '') +
                    submitterHtml +
                    actionsHtml +
                    '</div>' +
                    '</div>';

                document.getElementById('ilDetailModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            };

            window.closeIlDetailModal = function () {
                document.getElementById('ilDetailModal').classList.add('hidden');
                document.body.style.overflow = '';
            };

            window.openIlSuggestModal = function () {
                document.getElementById('ilSuggestModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            };

            window.closeIlSuggestModal = function () {
                document.getElementById('ilSuggestModal').classList.add('hidden');
                document.body.style.overflow = '';
            };

            // Keyboard support for cards
            document.querySelectorAll('.il-section-card').forEach(function (card) {
                card.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') card.click();
                });
            });

            // Auto-open suggest modal if there's a validation error for suggest form
            @if($errors->any() && old('title'))
                window.addEventListener('DOMContentLoaded', function () { openIlSuggestModal(); });
            @endif
    })();
    </script>
@endif