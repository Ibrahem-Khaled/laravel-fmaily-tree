<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>معرض صور {{ $person->full_name }} - تواصل عائلة السريِّع</title>

    {{-- 🎨 Stylesheets --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #37a05c;
            --light-green: #DCF2DD;
            --dark-green: #145147;
            --light-gray: #f8f9fa;
            --border-color: #dee2e6;
            --mourning: #1b1b1b;
        }

        body {
            background: var(--light-gray);
            font-family: 'Alexandria', sans-serif;
        }

        /* Header Styles */
        .gallery-header {
            background: linear-gradient(135deg, var(--dark-green) 0%, var(--primary-color) 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .gallery-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.1)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.1)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .gallery-header .container {
            position: relative;
            z-index: 2;
        }

        .person-info {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .person-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.3);
            object-fit: cover;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .person-photo-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: rgba(255, 255, 255, 0.8);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .person-details h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .person-details .subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 1rem;
        }

        .stats-row {
            display: flex;
            gap: 2rem;
            margin-top: 1rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            display: block;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        /* Gallery Grid */
        .gallery-container {
            padding: 2rem 0;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .gallery-item {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            background: white;
            cursor: pointer;
        }

        .gallery-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 16px 48px rgba(0, 0, 0, 0.2);
        }

        .gallery-item img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.05);
        }

        .gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, transparent 0%, rgba(0, 0, 0, 0.7) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 1.5rem;
        }

        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }

        .gallery-info {
            color: white;
        }

        .gallery-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .gallery-description {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 0.5rem;
        }

        .gallery-meta {
            font-size: 0.8rem;
            opacity: 0.8;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .gallery-actions {
            position: absolute;
            top: 1rem;
            right: 1rem;
            display: flex;
            gap: 0.5rem;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .gallery-item:hover .gallery-actions {
            opacity: 1;
        }

        .gallery-action-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark-green);
            font-size: 1rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .gallery-action-btn:hover {
            background: white;
            transform: scale(1.1);
        }

        /* Image Modal */
        .image-modal {
            background: rgba(0, 0, 0, 0.95);
        }

        .image-modal .modal-dialog {
            max-width: 90vw;
            max-height: 90vh;
            margin: 5vh auto;
        }

        .image-modal .modal-content {
            background: transparent;
            border: none;
            border-radius: 0;
        }

        .image-modal .modal-header {
            background: rgba(0, 0, 0, 0.8);
            border: none;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 10;
            padding: 1rem 2rem;
        }

        .image-modal .modal-body {
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 80vh;
        }

        .image-modal .modal-image {
            max-width: 100%;
            max-height: 80vh;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
        }

        .image-modal .image-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .image-modal .image-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .image-modal .image-description {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 1rem;
        }

        .image-modal .image-meta {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        /* Zoom Controls */
        .zoom-controls {
            position: absolute;
            bottom: 1rem;
            right: 1rem;
            display: flex;
            gap: 0.5rem;
            z-index: 10;
        }

        .zoom-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark-green);
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .zoom-btn:hover {
            background: white;
            transform: scale(1.1);
        }

        /* Loading Animation */
        .loading-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 200px;
        }

        .loading-spinner {
            width: 3rem;
            height: 3rem;
            border: 4px solid var(--light-green);
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Masonry Layout for better image arrangement */
        .gallery-grid {
            column-count: 3;
            column-gap: 2rem;
        }

        .gallery-item {
            break-inside: avoid;
            margin-bottom: 2rem;
            display: inline-block;
            width: 100%;
        }

        /* Filter and Search */
        .gallery-filters {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .filter-group {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-btn {
            background: var(--light-gray);
            border: 1px solid var(--border-color);
            color: var(--dark-green);
            padding: 0.5rem 1rem;
            border-radius: 25px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .filter-btn:hover, .filter-btn.active {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        /* Search */
        .search-box {
            position: relative;
            flex: 1;
            min-width: 250px;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 3rem;
            border: 1px solid var(--border-color);
            border-radius: 25px;
            background: var(--light-gray);
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            background: white;
            box-shadow: 0 0 0 3px rgba(55, 160, 92, 0.1);
        }

        .search-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--light-green);
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            color: var(--dark-green);
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #6c757d;
            margin-bottom: 0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .person-info {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .person-photo, .person-photo-placeholder {
                width: 100px;
                height: 100px;
            }

            .person-details h1 {
                font-size: 2rem;
            }

            .stats-row {
                justify-content: center;
                gap: 1rem;
            }

            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 1rem;
            }

            .gallery-item img {
                height: 200px;
            }

            .image-modal .modal-dialog {
                max-width: 95vw;
                margin: 2.5vh auto;
            }

            .image-modal .modal-image {
                max-height: 70vh;
            }
        }

        /* Back Button */
        .back-button {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .back-button:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }
    </style>
</head>

<body>
    {{-- تضمين الهيدر من الملف المنفصل --}}
    @include('partials.main-header')

    <main>
        {{-- Gallery Header --}}
        <section class="gallery-header">
            <div class="container">
                <a href="{{ url()->previous() }}" class="back-button">
                    <i class="fas fa-arrow-right me-2"></i> رجوع
                </a>

                <div class="person-info">
                    @if($person->avatar)
                        <img src="{{ $person->avatar }}" alt="{{ $person->full_name }}" class="person-photo">
                    @else
                        <div class="person-photo-placeholder">
                            <i class="fas {{ $person->gender === 'female' ? 'fa-female' : 'fa-male' }}"></i>
                        </div>
                    @endif

                    <div class="person-details">
                        <h1>{{ $person->full_name }}</h1>
                        <div class="subtitle">معرض الصور الشخصية</div>

                        <div class="stats-row">
                            <div class="stat-item">
                                <span class="stat-number">{{ $images->count() }}</span>
                                <span class="stat-label">صورة</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">{{ $person->age ?? 'غير محدد' }}</span>
                                <span class="stat-label">العمر</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">{{ $person->gender === 'male' ? 'ذكر' : 'أنثى' }}</span>
                                <span class="stat-label">الجنس</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Gallery Content --}}
        <section class="gallery-container">
            <div class="container">
                @if($images->count() > 0)
                    {{-- Filters and Search --}}
                    <div class="gallery-filters">
                        <div class="filter-group">
                            <div class="search-box">
                                <input type="text" class="search-input" id="searchInput" placeholder="البحث في الصور...">
                                <i class="fas fa-search search-icon"></i>
                            </div>
                            <button class="filter-btn active" data-filter="all">الكل ({{ $images->count() }})</button>
                            @php
                                $articles = $images->pluck('article.title')->filter()->unique();
                            @endphp
                            @foreach($articles as $articleTitle)
                                <button class="filter-btn" data-filter="{{ Str::slug($articleTitle) }}">{{ $articleTitle }}</button>
                            @endforeach
                        </div>
                    </div>

                    <div class="gallery-grid" id="galleryGrid">
                        @foreach($images as $image)
                            <div class="gallery-item"
                                 data-image-id="{{ $image->id }}"
                                 data-filter="{{ $image->article ? Str::slug($image->article->title) : 'no-article' }}"
                                 data-search="{{ strtolower($image->name . ' ' . ($image->description ?? '') . ' ' . ($image->article->title ?? '')) }}">
                                <img src="{{ asset('storage/' . $image->path) }}"
                                     alt="{{ $image->name }}"
                                     loading="lazy"
                                     onclick="openImageModal({{ $image->id }})">

                                <div class="gallery-overlay">
                                    <div class="gallery-info">
                                        <div class="gallery-title">{{ $image->name }}</div>
                                        @if($image->description)
                                            <div class="gallery-description">{{ Str::limit($image->description, 100) }}</div>
                                        @endif
                                        <div class="gallery-meta">
                                            <i class="fas fa-calendar-alt"></i>
                                            <span>{{ $image->created_at->format('Y/m/d') }}</span>
                                            @if($image->article)
                                                <i class="fas fa-book ms-2"></i>
                                                <span>{{ $image->article->title }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="gallery-actions">
                                    <button class="gallery-action-btn" onclick="openImageModal({{ $image->id }})" title="عرض الصورة">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="gallery-action-btn" onclick="downloadImage('{{ asset('storage/' . $image->path) }}', '{{ $image->name }}')" title="تحميل الصورة">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-images"></i>
                        <h3>لا توجد صور متاحة</h3>
                        <p>لم يتم العثور على أي صور مرتبطة بهذا الشخص.</p>
                    </div>
                @endif
            </div>
        </section>
    </main>

    {{-- Image Modal --}}
    <div class="modal fade image-modal" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalTitle">عرض الصورة</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="modalImage" class="modal-image" src="" alt="">

                    <div class="zoom-controls">
                        <button class="zoom-btn" onclick="zoomOut()" title="تصغير">
                            <i class="fas fa-search-minus"></i>
                        </button>
                        <button class="zoom-btn" onclick="resetZoom()" title="إعادة تعيين">
                            <i class="fas fa-expand-arrows-alt"></i>
                        </button>
                        <button class="zoom-btn" onclick="zoomIn()" title="تكبير">
                            <i class="fas fa-search-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="image-info">
                    <div class="image-title" id="modalImageTitle"></div>
                    <div class="image-description" id="modalImageDescription"></div>
                    <div class="image-meta" id="modalImageMeta"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let currentZoom = 1;
        const maxZoom = 3;
        const minZoom = 0.5;
        const zoomStep = 0.2;

        // Image data for modal
        const imageData = {
            @foreach($images as $image)
                {{ $image->id }}: {
                    id: {{ $image->id }},
                    name: "{{ $image->name }}",
                    description: "{{ $image->description ?? '' }}",
                    path: "{{ asset('storage/' . $image->path) }}",
                    created_at: "{{ $image->created_at->format('Y/m/d') }}",
                    article_title: "{{ $image->article->title ?? '' }}"
                },
            @endforeach
        };

        function openImageModal(imageId) {
            const image = imageData[imageId];
            if (!image) return;

            // Set modal content
            document.getElementById('imageModalTitle').textContent = image.name;
            document.getElementById('modalImage').src = image.path;
            document.getElementById('modalImage').alt = image.name;
            document.getElementById('modalImageTitle').textContent = image.name;
            document.getElementById('modalImageDescription').textContent = image.description;

            // Set meta information
            let metaText = `تاريخ الإضافة: ${image.created_at}`;
            if (image.article_title) {
                metaText += ` | المقال: ${image.article_title}`;
            }
            document.getElementById('modalImageMeta').textContent = metaText;

            // Reset zoom
            currentZoom = 1;
            updateImageZoom();

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('imageModal'));
            modal.show();
        }

        function zoomIn() {
            if (currentZoom < maxZoom) {
                currentZoom += zoomStep;
                updateImageZoom();
            }
        }

        function zoomOut() {
            if (currentZoom > minZoom) {
                currentZoom -= zoomStep;
                updateImageZoom();
            }
        }

        function resetZoom() {
            currentZoom = 1;
            updateImageZoom();
        }

        function updateImageZoom() {
            const image = document.getElementById('modalImage');
            image.style.transform = `scale(${currentZoom})`;
            image.style.transition = 'transform 0.3s ease';
        }

        function downloadImage(imageUrl, imageName) {
            const link = document.createElement('a');
            link.href = imageUrl;
            link.download = imageName;
            link.target = '_blank';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            const modal = document.getElementById('imageModal');
            if (modal.classList.contains('show')) {
                switch(e.key) {
                    case 'Escape':
                        bootstrap.Modal.getInstance(modal).hide();
                        break;
                    case '+':
                    case '=':
                        e.preventDefault();
                        zoomIn();
                        break;
                    case '-':
                        e.preventDefault();
                        zoomOut();
                        break;
                    case '0':
                        e.preventDefault();
                        resetZoom();
                        break;
                }
            }
        });

        // Lazy loading for images
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src || img.src;
                        img.classList.remove('lazy');
                        observer.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[loading="lazy"]').forEach(img => {
                imageObserver.observe(img);
            });
        }

        // Smooth scroll for better UX
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Filter and Search functionality
        let currentFilter = 'all';
        let currentSearch = '';

        // Filter buttons
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Update active button
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                // Update filter
                currentFilter = this.dataset.filter;
                filterImages();
            });
        });

        // Search input
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                currentSearch = this.value.toLowerCase();
                filterImages();
            });
        }

        function filterImages() {
            const images = document.querySelectorAll('.gallery-item');
            let visibleCount = 0;

            images.forEach(image => {
                const filterMatch = currentFilter === 'all' || image.dataset.filter === currentFilter;
                const searchMatch = currentSearch === '' || image.dataset.search.includes(currentSearch);

                if (filterMatch && searchMatch) {
                    image.style.display = 'block';
                    image.style.animation = 'fadeIn 0.3s ease';
                    visibleCount++;
                } else {
                    image.style.display = 'none';
                }
            });

            // Update filter button counts
            updateFilterCounts();
        }

        function updateFilterCounts() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            filterButtons.forEach(btn => {
                const filter = btn.dataset.filter;
                if (filter === 'all') {
                    const visibleImages = document.querySelectorAll('.gallery-item[style*="block"], .gallery-item:not([style*="none"])');
                    btn.textContent = `الكل (${visibleImages.length})`;
                } else {
                    const filteredImages = document.querySelectorAll(`[data-filter="${filter}"]`);
                    btn.textContent = btn.textContent.split(' (')[0] + ` (${filteredImages.length})`;
                }
            });
        }

        // Add fadeIn animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>

</html>
