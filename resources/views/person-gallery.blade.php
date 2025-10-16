<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>معرض صور {{ $person->full_name }} - تواصل عائلة السريِّع</title>

    {{-- 🎨 Stylesheets --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#37a05c',
                        'primary-dark': '#2d7a47',
                        'light-green': '#DCF2DD',
                        'dark-green': '#145147',
                        'light-gray': '#f8f9fa',
                        'border-color': '#dee2e6',
                        mourning: '#1b1b1b'
                    },
                    fontFamily: {
                        'alexandria': ['Alexandria', 'sans-serif']
                    },
                    width: {
                        '30': '7.5rem',
                        '25': '6.25rem'
                    },
                    height: {
                        '30': '7.5rem',
                        '25': '6.25rem'
                    }
                }
            }
        }
    </script>

    <style>
        .text-shadow-lg {
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        @media (max-width: 768px) {
            .person-info {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .person-photo, .person-photo-placeholder {
                width: 6.25rem;
                height: 6.25rem;
            }

            .person-details h1 {
                font-size: 2rem;
            }

            .stats-row {
                justify-content: center;
                gap: 1rem;
            }
        }
    </style>
</head>

<body class="bg-light-gray font-alexandria">
    {{-- تضمين الهيدر من الملف المنفصل --}}
    @include('partials.main-header')

    <main>
        {{-- Gallery Header --}}
        <section class="bg-gradient-to-br from-dark-green to-primary text-white py-8 mb-8 relative overflow-hidden">

            <div class="container mx-auto px-4 relative z-10">
                <a href="{{ url()->previous() }}" class="absolute top-4 right-4 bg-white bg-opacity-20 border border-white border-opacity-30 text-white px-6 py-3 rounded-full hover:bg-opacity-30 hover:-translate-y-0.5 transition-all duration-300 backdrop-blur-sm">
                    <i class="fas fa-arrow-right ml-2"></i> رجوع
                </a>

                <div class="flex items-center gap-8 md:flex-row flex-col md:text-right text-center">
                    @if($person->avatar)
                        <img src="{{ $person->avatar }}" alt="{{ $person->full_name }}" class="w-30 h-30 md:w-30 md:h-30 w-25 h-25 rounded-full border-4 border-white border-opacity-30 object-cover shadow-2xl">
                    @else
                        <div class="w-30 h-30 md:w-30 md:h-30 w-25 h-25 rounded-full border-4 border-white border-opacity-30 bg-white bg-opacity-20 flex items-center justify-center text-5xl md:text-5xl text-4xl text-white text-opacity-80 shadow-2xl">
                            <i class="fas {{ $person->gender === 'female' ? 'fa-female' : 'fa-male' }}"></i>
                        </div>
                    @endif

                    <div class="person-details">
                        <h1 class="text-4xl md:text-4xl text-2xl font-bold mb-2 text-shadow-lg">{{ $person->full_name }}</h1>
                        <div class="text-xl md:text-xl text-lg opacity-90 mb-4">معرض الصور الشخصية</div>

                        <div class="flex gap-8 md:gap-8 gap-4 mt-4 md:justify-start justify-center">
                            <div class="text-center">
                                <span class="text-3xl md:text-3xl text-2xl font-bold block">{{ $images->count() }}</span>
                                <span class="text-sm opacity-80">صورة</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Gallery Content --}}
        <section class="py-8">
            <div class="container mx-auto px-4">
                @if($images->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8" id="galleryGrid">
                        @foreach($images as $image)
                            <div class="gallery-item relative rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 bg-white cursor-pointer hover:-translate-y-2"
                                 data-image-id="{{ $image->id }}"
                                 data-name="{{ $image->name }}"
                                 data-description="{{ $image->description ?? '' }}"
                                 data-date="{{ $image->created_at->format('Y/m/d') }}"
                                 data-article="{{ $image->article->title ?? '' }}"
                                 onclick="openImageModal({{ $image->id }})">
                                <img src="{{ asset('storage/' . $image->path) }}"
                                     alt="{{ $image->name }}"
                                     loading="lazy"
                                     class="w-full h-64 object-cover hover:scale-105 transition-transform duration-300">

                                {{-- Image Info Overlay (Hidden by default) --}}
                                <div class="image-info absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-0 transition-opacity duration-300 flex flex-col justify-end p-6">
                                    <div class="text-white">
                                        <div class="text-lg font-semibold mb-2">{{ $image->name }}</div>
                                        @if($image->description)
                                            <div class="text-sm opacity-90 mb-2">{{ Str::limit($image->description, 100) }}</div>
                                        @endif
                                        <div class="text-xs opacity-80 flex items-center gap-2">
                                            <i class="fas fa-calendar-alt"></i>
                                            <span>{{ $image->created_at->format('Y/m/d') }}</span>
                                            @if($image->article)
                                                <i class="fas fa-book mr-2"></i>
                                                <span>{{ $image->article->title }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="gallery-actions absolute top-4 right-4 flex gap-2 opacity-0 hover:opacity-100 transition-opacity duration-300">
                                    <button class="w-10 h-10 rounded-full bg-white bg-opacity-90 border-none flex items-center justify-center text-dark-green text-base hover:bg-white hover:scale-110 transition-all duration-300 cursor-pointer"
                                            onclick="event.stopPropagation(); toggleImageInfo(this.closest('.gallery-item'))"
                                            title="إظهار/إخفاء التفاصيل">
                                        <i class="fas fa-info"></i>
                                    </button>
                                    <button class="w-10 h-10 rounded-full bg-white bg-opacity-90 border-none flex items-center justify-center text-dark-green text-base hover:bg-white hover:scale-110 transition-all duration-300 cursor-pointer"
                                            onclick="event.stopPropagation(); downloadImage('{{ asset('storage/' . $image->path) }}', '{{ $image->name }}')"
                                            title="تحميل الصورة">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16 px-8 bg-white rounded-2xl shadow-sm">
                        <i class="fas fa-images text-6xl text-light-green mb-4"></i>
                        <h3 class="text-dark-green mb-2">لا توجد صور متاحة</h3>
                        <p class="text-gray-500">لم يتم العثور على أي صور مرتبطة بهذا الشخص.</p>
                    </div>
                @endif
            </div>
        </section>
    </main>

    {{-- Image Modal --}}
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-95 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative max-w-7xl max-h-full">
                {{-- Close Button --}}
                <button id="closeButton" onclick="closeImageModal()" class="absolute top-8 right-4 z-10 w-12 h-12 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full flex items-center justify-center text-white text-xl transition-all duration-300">
                    <i class="fas fa-times"></i>
                </button>

                {{-- Image Container --}}
                <div class="relative">
                    <img id="modalImage" src="" alt="" class="max-w-full max-h-screen object-contain rounded-lg shadow-2xl cursor-pointer" onclick="toggleElements()">

                    {{-- Hint Text --}}
                    <div id="hintText" class="absolute top-4 left-4 bg-black bg-opacity-50 text-white px-3 py-2 rounded-lg text-sm opacity-0 transition-opacity duration-300">
                        انقر على الصورة لإخفاء/إظهار العناصر
                    </div>

                    {{-- Image Info --}}
                    <div id="modalImageInfo" class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black via-black to-transparent text-white p-6 rounded-b-lg">
                        <h3 id="modalImageTitle" class="text-xl font-semibold mb-2"></h3>
                        <p id="modalImageDescription" class="text-sm opacity-90 mb-2"></p>
                        <div id="modalImageMeta" class="text-xs opacity-80"></div>
                    </div>
                </div>

                {{-- Controls --}}
                <div id="controlsContainer" class="absolute bottom-4 right-4 flex gap-2">
                    {{-- Zoom Controls --}}
                    <button onclick="zoomOut()" class="w-12 h-12 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full flex items-center justify-center text-white text-lg transition-all duration-300" title="تصغير">
                        <i class="fas fa-search-minus"></i>
                    </button>
                    <button onclick="resetZoom()" class="w-12 h-12 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full flex items-center justify-center text-white text-lg transition-all duration-300" title="إعادة تعيين">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </button>
                    <button onclick="zoomIn()" class="w-12 h-12 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full flex items-center justify-center text-white text-lg transition-all duration-300" title="تكبير">
                        <i class="fas fa-search-plus"></i>
                    </button>

                    {{-- Download Button --}}
                    <button onclick="downloadCurrentImage()" class="w-12 h-12 bg-primary hover:bg-primary-dark rounded-full flex items-center justify-center text-white text-lg transition-all duration-300" title="تحميل الصورة">
                        <i class="fas fa-download"></i>
                    </button>
                </div>

                {{-- Navigation Arrows --}}
                <button id="prevButton" onclick="previousImage()" class="absolute left-4 top-1/2 transform -translate-y-1/2 w-12 h-12 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full flex items-center justify-center text-white text-lg transition-all duration-300" title="الصورة السابقة">
                    <i class="fas fa-chevron-right"></i>
                </button>
                <button id="nextButton" onclick="nextImage()" class="absolute right-4 top-1/2 transform -translate-y-1/2 w-12 h-12 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full flex items-center justify-center text-white text-lg transition-all duration-300" title="الصورة التالية">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>
        </div>
    </div>

    <script>
        // Global variables
        let currentZoom = 1;
        const maxZoom = 3;
        const minZoom = 0.5;
        const zoomStep = 0.2;
        let currentImageIndex = 0;
        let images = [];
        let elementsHidden = false;

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

        // Initialize images array
        images = Object.values(imageData);

        // Modal functions
        function openImageModal(imageId) {
            const image = imageData[imageId];
            if (!image) return;

            // Find current image index
            currentImageIndex = images.findIndex(img => img.id === imageId);

            // Set modal content
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

            // Reset elements visibility
            elementsHidden = false;

            // Show modal
            document.getElementById('imageModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Show hint text briefly
            const hintText = document.getElementById('hintText');
            if (hintText) {
                hintText.style.opacity = '1';
                setTimeout(() => {
                    hintText.style.opacity = '0';
                }, 3000);
            }
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Zoom functions
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

        // Navigation functions
        function nextImage() {
            if (currentImageIndex < images.length - 1) {
                currentImageIndex++;
                loadImageAtIndex(currentImageIndex);
            }
        }

        function previousImage() {
            if (currentImageIndex > 0) {
                currentImageIndex--;
                loadImageAtIndex(currentImageIndex);
            }
        }

        function loadImageAtIndex(index) {
            const image = images[index];
            if (!image) return;

            // Reset zoom
            currentZoom = 1;

            // Update modal content
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

            updateImageZoom();
        }

        // Download functions
        function downloadImage(imageUrl, imageName) {
            const link = document.createElement('a');
            link.href = imageUrl;
            link.download = imageName;
            link.target = '_blank';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function downloadCurrentImage() {
            const currentImage = images[currentImageIndex];
            if (currentImage) {
                downloadImage(currentImage.path, currentImage.name);
            }
        }

        // Toggle elements visibility
        function toggleElements() {
            const elementsToToggle = [
                'closeButton',
                'modalImageInfo',
                'controlsContainer',
                'prevButton',
                'nextButton'
            ];

            elementsHidden = !elementsHidden;

            elementsToToggle.forEach(elementId => {
                const element = document.getElementById(elementId);
                if (element) {
                    if (elementsHidden) {
                        element.style.opacity = '0';
                        element.style.pointerEvents = 'none';
                    } else {
                        element.style.opacity = '1';
                        element.style.pointerEvents = 'auto';
                    }
                }
            });
        }

        // Toggle image info overlay
        function toggleImageInfo(element) {
            const overlay = element.querySelector('.image-info');
            if (overlay.style.opacity === '1') {
                overlay.style.opacity = '0';
            } else {
                overlay.style.opacity = '1';
            }
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            const modal = document.getElementById('imageModal');
            if (!modal.classList.contains('hidden')) {
                switch(e.key) {
                    case 'Escape':
                        closeImageModal();
                        break;
                    case 'ArrowRight':
                        e.preventDefault();
                        previousImage();
                        break;
                    case 'ArrowLeft':
                        e.preventDefault();
                        nextImage();
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
                    case 'h':
                    case 'H':
                        e.preventDefault();
                        toggleElements();
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
    </script>
</body>

</html>
