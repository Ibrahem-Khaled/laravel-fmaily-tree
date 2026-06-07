{{-- ================================================================
GALLERY MODAL
================================================================ --}}
<div id="galleryModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/95 backdrop-blur-sm"
    style="display:none;">
    <div class="relative w-full h-full flex items-center justify-center p-2 md:p-4">

        {{-- Close button --}}
        <button id="closeGalleryModal"
            class="absolute top-2 right-2 md:top-4 md:right-4 z-50 text-white bg-red-600/90 hover:bg-red-600 rounded-full p-2.5 md:p-3 shadow-2xl hover:scale-110 active:scale-95 transition-all">
            <i class="fas fa-times text-lg md:text-2xl"></i>
        </button>

        <div
            class="md:hidden absolute top-2 left-2 z-40 bg-black/60 text-white text-[10px] px-2 py-1 rounded-lg backdrop-blur-sm">
            <i class="fas fa-hand-pointer mr-1"></i> اضغط للخروج
        </div>

        <div class="relative max-w-7xl w-full h-full flex items-center justify-center">
            <div id="galleryModalImageContainer" class="hidden w-full h-full flex items-center justify-center">
                <img id="galleryModalImage" src="" alt=""
                    class="max-w-full max-h-full object-contain rounded-lg cursor-pointer" onclick="Gallery.close()">
            </div>
            <div id="galleryModalVideoContainer" class="hidden w-full h-full flex items-center justify-center">
                <div class="w-full" style="max-width:90vw; aspect-ratio:16/9;">
                    <iframe id="galleryModalVideo" src="" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen class="w-full h-full rounded-lg"></iframe>
                </div>
            </div>
        </div>

        <div id="galleryModalInfo"
            class="absolute bottom-2 md:bottom-4 right-2 left-2 md:right-4 md:left-4 text-white text-center bg-black/70 backdrop-blur-md rounded-lg p-3 md:p-4">
            <h3 id="galleryModalTitle" class="text-base md:text-xl font-bold mb-1"></h3>
            <p id="galleryModalCategory" class="text-xs md:text-sm text-gray-300 mb-2"></p>
            <button onclick="Gallery.close()"
                class="md:hidden mt-2 bg-red-600 hover:bg-red-700 text-white text-xs px-4 py-2 rounded-lg font-semibold">
                <i class="fas fa-times mr-1"></i> إغلاق
            </button>
            <p class="hidden md:block text-xs text-gray-400 mt-2">
                <i class="fas fa-info-circle mr-1"></i> اضغط على X أو اضغط ESC للخروج
            </p>
        </div>

    </div>
</div>
