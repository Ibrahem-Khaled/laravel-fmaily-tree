<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>معرض صور العائلة</title>

    {{-- قم بتضمين ملف Tailwind CSS الخاص بمشروعك --}}
    {{-- مثال: <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <script src="https://cdn.tailwindcss.com"></script> {{-- للاختبار السريع فقط --}}

    {{-- استيراد خطوط جميلة من Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Amiri:wght@400;700&display=swap"
        rel="stylesheet">

    <style>
        /* --- الأنماط الأساسية (نفس الأنماط الأصلية) --- */
        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 50%, #bbf7d0 100%);
            min-height: 100vh;
        }

        h1,
        h2,
        h3 {
            font-family: 'Amiri', serif;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .green-glow {
            box-shadow: 0 0 40px rgba(34, 197, 94, 0.3);
        }

        .green-glow-hover:hover {
            box-shadow: 0 0 60px rgba(34, 197, 94, 0.5);
            transform: translateY(-5px);
        }

        .gradient-text {
            background: linear-gradient(135deg, #16a34a 0%, #22c55e 50%, #4ade80 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
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

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #16a34a, #15803d);
        }

        .modal-backdrop {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            animation: slideUp 0.3s ease-out;
        }

        @keyframes slideUp {
            from {
                transform: translateY(100%);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .fullscreen-image {
            max-height: 90vh;
            max-width: 90vw;
            object-fit: contain;
        }

        /* --- نهاية الأنماط الأساسية --- */

        /* --- أنماط جديدة لعرض المجلدات --- */
        .folder {
            position: relative;
            padding-top: 75%;
            /* Aspect ratio 4:3 */
            background-color: #a7f3d0;
            border-radius: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .folder:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 10px 20px rgba(34, 197, 94, 0.3);
        }

        .folder::before {
            content: '';
            position: absolute;
            top: -5%;
            left: 5%;
            width: 30%;
            height: 10%;
            background-color: #6ee7b7;
            border-radius: 0.375rem 0.375rem 0 0;
        }

        .folder-preview {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            padding: 15% 5% 5% 5%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .folder-preview-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: repeat(2, 1fr);
            gap: 4px;
            width: 100%;
            height: 100%;
            background: #dcfce7;
            border-radius: 0.25rem;
            overflow: hidden;
        }

        .folder-preview-grid img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }

        .folder:hover .folder-preview-grid img {
            opacity: 1;
        }

        .folder-name {
            margin-top: 0.75rem;
            font-weight: 700;
            color: #14532d;
            text-align: center;
            transition: color 0.3s ease;
            font-family: 'Amiri', serif;
        }

        .folder-item:hover .folder-name {
            color: #16a34a;
        }

        /* أيقونة المجلد الفارغ */
        .folder-empty-icon {
            color: #34d399;
        }

        /* أنماط شاشة عرض الصور */
        #image-view-container {
            display: none;
            /* مخفي بشكل افتراضي */
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
</head>

<body class="text-gray-800 relative overflow-x-hidden">
    @include('partials.main-header')

    <div class="container mx-auto px-4 py-4 lg:py-8 relative z-10">

        <div class="flex items-center justify-between mb-4 lg:hidden">
            <h2 class="text-xl font-bold gradient-text">المعرض</h2>
            <button id="toggle-filters-btn"
                class="bg-white/80 p-2 rounded-full shadow-md hover:bg-white transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-700" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 4h13M3 8h9m-9 4h6m4 0l4 4m0 0l4-4m-4 4v-8" />
                </svg>
            </button>
        </div>
        <div class="flex flex-col lg:flex-row gap-8">

            <div id="filters-backdrop" class="fixed inset-0 bg-black/40 z-20 hidden lg:hidden"></div>
            <aside id="filters-sidebar"
                class="fixed lg:static inset-y-0 right-0 z-30 w-full max-w-sm lg:max-w-none lg:w-1/4 h-full lg:h-auto bg-white/50 lg:bg-transparent backdrop-blur-lg lg:backdrop-blur-none p-4 lg:p-0 transform transition-transform duration-300 ease-in-out translate-x-full lg:translate-x-0">
                <div class="glass-effect p-4 lg:p-6 rounded-3xl green-glow h-full lg:card-hover">
                    <div class="flex items-center justify-between lg:block border-b border-green-200 pb-4 mb-4">
                        <h3 class="text-xl lg:text-2xl font-bold gradient-text">الأقسام</h3>
                         <button id="close-filters-btn" class="p-2 rounded-full bg-gray-100 hover:bg-gray-200 transition lg:hidden">
                             <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                             </svg>
                         </button>
                    </div>

                    <ul class="space-y-2">
                        <li>
                            <a href="javascript:void(0);" onclick="showFolderView()"
                                class="block px-3 lg:px-4 py-2 lg:py-3 rounded-xl lg:rounded-2xl transition-all duration-300 font-medium text-sm lg:text-base bg-white/70 hover:bg-green-50 hover:scale-105 hover:shadow-md">
                                <span class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                                    </svg>
                                    كل المجلدات
                                </span>
                            </a>
                        </li>
                        @foreach ($categories as $category)
                            <li>
                                <a href="javascript:void(0);" onclick="openCategoryView({{ $category->id }})"
                                    class="block px-3 lg:px-4 py-2 lg:py-3 rounded-xl lg:rounded-2xl transition-all duration-300 font-medium text-sm lg:text-base bg-white/70 hover:bg-green-50 hover:scale-105 hover:shadow-md">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </aside>
            <main class="w-full lg:w-3/4">

                <div id="folder-view-container">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-4 gap-6">
                        @foreach ($categories as $category)
                            <div class="folder-item" onclick="openCategoryView({{ $category->id }})">
                                <div class="folder">
                                    <div class="folder-preview">
                                        @if ($category->images->isNotEmpty())
                                            <div class="folder-preview-grid">
                                                @foreach ($category->images->take(4) as $image)
                                                    <img src="{{ asset('storage/' . $image->path) }}" alt="Preview">
                                                @endforeach
                                            </div>
                                        @else
                                            <div
                                                class="w-full h-full flex items-center justify-center bg-green-200/50 rounded-md">
                                                <svg class="w-1/2 h-1/2 folder-empty-icon"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <h3 class="folder-name">{{ $category->name }} ({{ $category->images_count }})</h3>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div id="image-view-container">
                    <div class="flex items-center justify-between mb-6">
                        <h2 id="category-title" class="text-3xl font-bold gradient-text"></h2>
                        <button onclick="showFolderView()"
                            class="bg-white/80 hover:bg-white transition-all shadow-md hover:shadow-lg text-green-700 font-bold py-2 px-4 rounded-full flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                            العودة للمجلدات
                        </button>
                    </div>
                    <div id="image-grid"
                        class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4 lg:gap-6">
                    </div>
                    <div id="no-images-message"
                        class="hidden flex flex-col items-center justify-center min-h-[400px] glass-effect rounded-2xl p-8">
                        <svg class="w-20 h-20 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <h3 class="text-2xl font-bold gradient-text mt-6">هذا المجلد فارغ</h3>
                        <p class="text-gray-600 mt-3 text-center">لا توجد صور في هذه الفئة حاليًا.</p>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <div id="imageOptionsModal" class="fixed inset-0 z-[100] hidden items-center justify-center">
        <div class="modal-backdrop absolute inset-0" onclick="closeImageOptions()"></div>
        <div
            class="modal-content relative bg-white rounded-t-3xl md:rounded-3xl p-6 md:p-8 w-full max-w-md mx-auto mt-20 md:mt-0">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl md:text-2xl font-bold gradient-text">خيارات الصورة</h3>
                <button onclick="closeImageOptions()" class="p-2 rounded-full bg-gray-100 hover:bg-gray-200 transition">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div class="mb-6"><img id="modalImagePreview" src="" alt=""
                    class="w-full h-48 md:h-64 object-cover rounded-2xl"></div>
            <div class="mb-6 space-y-2">
                <h4 id="modalImageTitle" class="font-bold text-lg text-gray-800"></h4>
                <div id="modalImageAuthor" class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                            clip-rule="evenodd" />
                    </svg>
                    <span></span>
                </div>
                <div id="modalImageCategory"
                    class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium"></div>
            </div>
            <div class="flex flex-col gap-3">
                <button onclick="viewFullscreen()"
                    class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white font-bold py-3 px-4 rounded-2xl hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-lg transform hover:scale-105 active:scale-95 flex items-center justify-center gap-2">عرض
                    بالحجم الكامل</button>
                <button id="viewArticleBtn" onclick="viewArticle()"
                    class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white font-bold py-3 px-4 rounded-2xl hover:from-green-600 hover:to-green-700 transition-all duration-300 shadow-lg transform hover:scale-105 active:scale-95 flex items-center justify-center gap-2">عرض
                    تفاصيل الصورة</button>
            </div>
        </div>
    </div>

    <div id="fullscreenModal" class="fixed inset-0 z-[110] hidden">
        <div class="modal-backdrop absolute inset-0 flex items-center justify-center p-4" onclick="closeFullscreen()">
            <button onclick="closeFullscreen()"
                class="absolute top-4 right-4 z-10 p-3 rounded-full bg-white/90 hover:bg-white transition">
                <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
            <img id="fullscreenImage" src="" alt="" class="fullscreen-image rounded-lg shadow-2xl"
                onclick="event.stopPropagation()">
        </div>
    </div>


    <script>
        // قم بتمرير بيانات الفئات والصور من Laravel Controller إلى هنا
        const galleryData = @json($categoriesWithImages);

        let currentImageData = null;
        const folderView = document.getElementById('folder-view-container');
        const imageView = document.getElementById('image-view-container');
        const imageGrid = document.getElementById('image-grid');
        const categoryTitle = document.getElementById('category-title');
        const noImagesMessage = document.getElementById('no-images-message');


        // ===== التعديل 4: إضافة كود JavaScript جديد للتحكم في القائمة الجانبية =====
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('filters-sidebar');
            const backdrop = document.getElementById('filters-backdrop');
            const openBtn = document.getElementById('toggle-filters-btn');
            const closeBtn = document.getElementById('close-filters-btn');

            function openSidebar() {
                sidebar.classList.remove('translate-x-full');
                backdrop.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // لمنع التمرير في الخلفية
            }

            function closeSidebar() {
                sidebar.classList.add('translate-x-full');
                backdrop.classList.add('hidden');
                document.body.style.overflow = ''; // استعادة التمرير
            }

            // عند الضغط على زر الفتح
            if(openBtn) {
                 openBtn.addEventListener('click', openSidebar);
            }

            // عند الضغط على زر الإغلاق
            if(closeBtn) {
                closeBtn.addEventListener('click', closeSidebar);
            }

            // عند الضغط على الخلفية المعتمة
            if(backdrop) {
                backdrop.addEventListener('click', closeSidebar);
            }
        });
        // ===== نهاية التعديل 4 =====


        // دالة لإنشاء كود HTML لبطاقة الصورة
        function createImageCard(image) {
            const imagePath = `{{ asset('storage') }}/${image.path}`;
            const title = image.article ? image.article.title : 'بدون عنوان';
            const author = image.article && image.article.person ? image.article.person.name : '';
            const categoryName = image.article && image.article.category ? image.article.category.name : '';
            const articleId = image.article ? image.article.id : null;

            const imageData = JSON.stringify({
                id: image.id,
                path: imagePath,
                title: title,
                author: author,
                category: categoryName,
                article_id: articleId
            });

            return `
                <div onclick='showImageOptions(${imageData})'
                    class="group relative overflow-hidden rounded-2xl shadow-lg cursor-pointer green-glow-hover transition-all duration-500">
                    <div class="aspect-square overflow-hidden bg-gradient-to-br from-green-100 to-green-200">
                        <img src="${imagePath}" alt="${title}" class="w-full h-full object-cover transition-all duration-700 group-hover:scale-110">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-3 text-white transform translate-y-full group-hover:translate-y-0 transition-all duration-500">
                        <h4 class="font-bold text-sm line-clamp-1">${title}</h4>
                        ${author ? `<span class="text-xs text-green-200">${author}</span>` : ''}
                    </div>
                </div>
            `;
        }

        // دالة لفتح مجلد وعرض الصور
        function openCategoryView(categoryId) {
            const category = galleryData.find(cat => cat.id === categoryId);
            if (!category) return;

            // تحديث العنوان
            categoryTitle.textContent = category.name;

            // مسح الشبكة القديمة
            imageGrid.innerHTML = '';

            if (category.images && category.images.length > 0) {
                imageGrid.classList.remove('hidden');
                noImagesMessage.classList.add('hidden');
                category.images.forEach(image => {
                    imageGrid.innerHTML += createImageCard(image);
                });
            } else {
                imageGrid.classList.add('hidden');
                noImagesMessage.classList.remove('hidden');
            }

            // تبديل الواجهات
            folderView.style.display = 'none';
            imageView.style.display = 'block';
        }

        // دالة للعودة إلى واجهة المجلدات
        function showFolderView() {
            imageView.style.display = 'none';
            folderView.style.display = 'block';
        }

        /* --- الدوال الأصلية للنافذة المنبثقة (Modal) --- */
        function showImageOptions(imageData) {
            currentImageData = imageData;
            const modal = document.getElementById('imageOptionsModal');
            document.getElementById('modalImagePreview').src = imageData.path;
            document.getElementById('modalImageTitle').textContent = imageData.title;
            const authorSpan = document.getElementById('modalImageAuthor').querySelector('span');
            authorSpan.textContent = imageData.author || 'غير محدد';
            authorSpan.parentElement.style.display = imageData.author ? 'flex' : 'none';

            const categoryElement = document.getElementById('modalImageCategory');
            if (imageData.category) {
                categoryElement.textContent = '#' + imageData.category;
                categoryElement.style.display = 'inline-block';
            } else {
                categoryElement.style.display = 'none';
            }

            const articleBtn = document.getElementById('viewArticleBtn');
            articleBtn.style.display = imageData.article_id ? 'flex' : 'none';

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeImageOptions() {
            const modal = document.getElementById('imageOptionsModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function viewFullscreen() {
            if (currentImageData) {
                document.getElementById('fullscreenImage').src = currentImageData.path;
                document.getElementById('fullscreenModal').classList.remove('hidden');
                document.getElementById('fullscreenModal').classList.add('flex');
                closeImageOptions();
            }
        }

        function closeFullscreen() {
            document.getElementById('fullscreenModal').classList.add('hidden');
            document.getElementById('fullscreenModal').classList.remove('flex');
        }

        function viewArticle() {
            if (currentImageData && currentImageData.article_id) {
                window.location.href = `{{ url('/article') }}/${currentImageData.article_id}`;
            }
        }
    </script>

</body>

</html>
