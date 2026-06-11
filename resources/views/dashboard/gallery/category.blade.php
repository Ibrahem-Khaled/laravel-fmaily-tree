@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        
        {{-- مكون الترويسة الجاهز --}}
        <x-dashboard.page-header 
            :title="$parentCategory ? ($parentCategory->name . ' ➔ ' . $category->name) : $category->name"
            :description="'عرض وإدارة جميع الصور والمستندات ومقاطع اليوتيوب الخاصة بفئة: ' . $category->name">
            <x-slot:actions>
                <a href="{{ route('dashboard.images.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-right"></i> العودة للمعرض
                </a>
                <button class="btn btn-outline-warning" id="bulkMoveBtn">
                    <i class="fas fa-exchange-alt"></i> نقل المحدد
                </button>
                <button class="btn btn-outline-danger" id="bulkDeleteBtn">
                    <i class="fas fa-trash"></i> حذف المحدد
                </button>
                <button class="btn btn-primary" data-toggle="modal" data-target="#uploadImagesModal">
                    <i class="fas fa-upload"></i> رفع ملفات
                </button>
            </x-slot:actions>
        </x-dashboard.page-header>

        @include('components.alerts')

        {{-- إحصائيات --}}
        <div class="row mb-4">
            <x-stats-card icon="fas fa-images" title="عدد الصور في هذه الفئة" :value="$categoryImagesCount" color="primary" />
            @if($parentCategory)
                <x-stats-card icon="fas fa-folder" title="الفئة الرئيسية" :value="$parentCategory->name" color="info" />
            @endif
            @if(count($subcategories) > 0)
                <x-stats-card icon="fas fa-folder-tree" title="الفئات الفرعية" :value="count($subcategories)" color="warning" />
            @endif
        </div>

        {{-- بطاقة المعرض باستخدام مكون الكارد الجاهز --}}
        <x-dashboard.card :title="$category->name" icon="fe-folder">
            <x-slot:headerAction>
                <span class="badge badge-secondary p-2">{{ $categoryImagesCount }} صورة</span>
            </x-slot:headerAction>

            {{-- شريط اختيار الكل --}}
            <div class="mb-4 p-3 rounded d-flex align-items-center justify-content-between select-all-bar">
                <label class="mb-0 d-flex align-items-center cursor-pointer">
                    <input type="checkbox" id="checkAll" class="mr-2 custom-check-all">
                    <strong class="text-muted pr-2">تحديد جميع عناصر الصفحة</strong>
                </label>
            </div>

            {{-- فلاتر + بحث --}}
            <form action="{{ route('dashboard.gallery.category', $category->id) }}" method="GET" class="mb-4 search-filter-form">
                <div class="form-row align-items-end">
                    <div class="form-group col-md-4">
                        <label class="font-weight-bold">بحث بالاسم</label>
                        <input type="text" name="search" value="{{ $search }}" class="form-control"
                            placeholder="ابحث باسم الملف...">
                    </div>

                    <div class="form-group col-md-4">
                        <label class="font-weight-bold">بحث عن شخص مذكور</label>
                        <select name="person_search" id="personSearchSelect" class="form-control">
                            <option value="">— اختر شخص —</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label>&nbsp;</label>
                        <div>
                            <button class="btn btn-primary px-4" type="submit"><i class="fas fa-search"></i> تطبيق الفلاتر</button>
                            @if($search || $personSearch)
                                <a href="{{ route('dashboard.gallery.category', $category->id) }}" class="btn btn-secondary px-4">
                                    <i class="fas fa-times"></i> إلغاء الفلاتر
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>

            {{-- عرض شبكة الصور --}}
            <form action="{{ route('images.bulk-destroy') }}" method="POST" id="bulkDeleteForm">
                @csrf @method('DELETE')

                @if($images->count() > 0)
                    <div class="row" id="mediaGrid">
                        @foreach($images as $img)
                            <x-dashboard.media-card :img="$img" />
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $images->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="alert alert-info text-center p-5">
                        <i class="fas fa-info-circle fa-2x mb-3 d-block"></i> لا توجد صور لعرضها في هذه الفئة
                    </div>
                @endif
            </form>
        </x-dashboard.card>
    </div>

    {{-- شريط الإجراءات الجماعية العائم --}}
    <div class="bulk-actions-floating-bar" id="bulkFloatingBar">
        <div class="d-flex align-items-center gap-3">
            <span class="text-white font-weight-bold ml-3" id="selectedCountText">0 عنصر محدد</span>
            <button class="btn btn-sm btn-warning mr-2" id="bulkMoveBtnFloating">
                <i class="fas fa-exchange-alt"></i> نقل المحدد
            </button>
            <button class="btn btn-sm btn-danger mr-2" id="bulkDeleteBtnFloating">
                <i class="fas fa-trash"></i> حذف المحدد
            </button>
            <button class="btn btn-sm btn-outline-secondary text-white mr-2" id="clearSelectionBtn">
                إلغاء
            </button>
        </div>
    </div>

    {{-- مودال رفع الصور --}}
    @include('dashboard.gallery.modals.upload')

    {{-- مودال تعديل الصورة --}}
    @include('dashboard.gallery.modals.edit')

    {{-- مودال إنشاء فئة سريع --}}
    @include('dashboard.gallery.modals.quick-category')

    {{-- مودال النقل الجماعي --}}
    <div class="modal fade" id="bulkMoveModal" tabindex="-1" role="dialog" aria-labelledby="bulkMoveModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkMoveModalLabel">
                        <i class="fas fa-exchange-alt"></i> نقل الصور المحددة
                    </h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form id="bulkMoveForm">
                    @csrf
                    <div class="modal-body">
                        <p class="mb-3">
                            <strong id="selectedImagesCount">0</strong> صورة محددة للنقل
                        </p>
                        <div class="form-group">
                            <label for="targetCategorySelect">اختر الفئة الهدف:</label>
                            <select name="target_category_id" id="targetCategorySelect" class="form-control" required>
                                <option value="">— اختر فئة —</option>
                                @foreach($allCategoriesForMove ?? [] as $cat)
                                    @if($cat->parent)
                                        <option value="{{ $cat->id }}">
                                            &nbsp;&nbsp;&nbsp;└─ {{ $cat->parent->name }} → {{ $cat->name }}
                                        </option>
                                    @else
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <small class="form-text text-muted">يمكنك اختيار فئة رئيسية أو فئة فرعية</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-exchange-alt"></i> نقل الصور
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- مودال عرض الصورة مع التكبير --}}
    <div class="modal fade" id="imageViewerModal" tabindex="-1" role="dialog" aria-labelledby="imageViewerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageViewerModalLabel">عرض الصورة</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body text-center">
                    <div class="image-container" style="position: relative; overflow: hidden;">
                        <img id="viewerImage" src="" alt="" style="max-width: 100%; max-height: 70vh; transition: transform 0.3s ease;">
                    </div>
                    <div class="mt-3">
                        <button type="button" class="btn btn-outline-secondary" onclick="zoomOut()">
                            <i class="fas fa-search-minus"></i> تصغير
                        </button>
                        <button type="button" class="btn btn-outline-primary" onclick="resetZoom()">
                            <i class="fas fa-expand-arrows-alt"></i> حجم طبيعي
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="zoomIn()">
                            <i class="fas fa-search-plus"></i> تكبير
                        </button>
                        <button type="button" class="btn btn-outline-success" onclick="downloadCurrentImage()">
                            <i class="fas fa-download"></i> تحميل
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- مودال عرض فيديو يوتيوب --}}
    <div class="modal fade" id="youtubeViewerModal" tabindex="-1" role="dialog" aria-labelledby="youtubeViewerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="youtubeViewerModalLabel">مشاهدة فيديو يوتيوب</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body text-center">
                    <div class="video-container" style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
                        <iframe id="youtubeViewerIframe" src="" frameborder="0" allowfullscreen
                                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></iframe>
                    </div>
                    <div class="mt-3">
                        <a id="youtubeViewerLink" href="" target="_blank" class="btn btn-outline-danger">
                            <i class="fab fa-youtube"></i> مشاهدة على يوتيوب
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Dark mode override for Select2 */
    body.dark .select2-container--bootstrap4 .select2-selection {
        background-color: #2b3035 !important;
        border-color: #495057 !important;
        color: #fff !important;
    }
    body.dark .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
        background-color: #343a40 !important;
        border-color: #495057 !important;
        color: #fff !important;
    }
    body.dark .select2-dropdown {
        background-color: #2b3035 !important;
        border-color: #495057 !important;
        color: #fff !important;
        z-index: 9999;
    }
    body.dark .select2-results__option {
        color: #fff !important;
    }
    body.dark .select2-results__option--highlighted[aria-selected] {
        background-color: #007bff !important;
    }
    body.dark .select2-search__field {
        background-color: #343a40 !important;
        color: #fff !important;
        border-color: #495057 !important;
    }

    /* Premium redesign for media cards */
    .media-card {
        background-color: #ffffff;
        border: 1px solid #e3e6f0;
        border-radius: 12px;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        position: relative;
    }
    body.dark .media-card {
        background-color: #1f2229;
        border-color: #2e3440;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    }
    .media-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }
    body.dark .media-card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
    }
    
    .media-card-preview {
        position: relative;
        padding-bottom: 60%;
        background-color: #000;
        overflow: hidden;
        cursor: pointer;
    }
    .media-card-preview img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    .media-card:hover .media-card-preview img {
        transform: scale(1.06);
    }

    .play-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 2.5rem;
        transition: all 0.3s ease;
    }
    .media-card:hover .play-overlay {
        background: rgba(0,0,0,0.1);
        color: #ff0000;
    }

    .media-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 2;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: bold;
        background: rgba(0, 0, 0, 0.65);
        color: #fff;
        display: flex;
        align-items: center;
        gap: 4px;
        backdrop-filter: blur(4px);
    }
    .media-badge.image { border-left: 3px solid #007bff; }
    .media-badge.youtube { border-left: 3px solid #dc3545; }
    .media-badge.pdf { border-left: 3px solid #fd7e14; }

    .media-checkbox-wrapper {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 2;
    }
    .media-checkbox {
        width: 20px;
        height: 20px;
        cursor: pointer;
        border-radius: 4px;
        border: 2px solid #fff;
        background: rgba(0, 0, 0, 0.4);
        appearance: none;
        -webkit-appearance: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    .media-checkbox:checked {
        background-color: #28a745 !important;
        border-color: #28a745 !important;
    }
    .media-checkbox:checked::after {
        content: "\f00c";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        color: #fff;
        font-size: 11px;
    }
    
    .media-card-body {
        padding: 15px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .media-card-title {
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 8px;
        color: #333;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 2.8em;
    }
    body.dark .media-card-title {
        color: #f8f9fa;
    }

    .media-card-mentions {
        margin-top: 10px;
        min-height: 45px;
    }
    
    .mentioned-person-item {
        background-color: #e9ecef;
        color: #495057;
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: 20px;
        margin: 2px;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }
    body.dark .mentioned-person-item {
        background-color: #2b3035;
        color: #dee2e6;
        border: 1px solid #3d4349;
    }
    .btn-remove-mention {
        border: none;
        background: none;
        padding: 0;
        color: #dc3545;
        font-size: 10px;
        cursor: pointer;
        line-height: 1;
    }
    .btn-remove-mention:hover {
        color: #a71d2a;
    }
    
    /* Floating action bar styling */
    .bulk-actions-floating-bar {
        position: fixed;
        bottom: -100px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(31, 34, 41, 0.95);
        border: 1px solid #3d4349;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        border-radius: 50px;
        padding: 12px 24px;
        z-index: 1050;
        display: flex;
        align-items: center;
        transition: bottom 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        backdrop-filter: blur(8px);
    }
    body.light .bulk-actions-floating-bar {
        background: rgba(255, 255, 255, 0.95);
        border-color: #dee2e6;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    .bulk-actions-floating-bar.active {
        bottom: 30px;
    }

    /* select-all bar dark/light */
    .select-all-bar {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
    }
    body.dark .select-all-bar {
        background-color: #2b3035;
        border-color: #3d4349;
    }
    .custom-check-all {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    /* Forms dark mode styling */
    body.dark .card-body {
        background-color: #1e2229;
        color: #f8f9fa;
    }
    body.dark .form-control {
        background-color: #2b3035;
        border-color: #3d4349;
        color: #fff;
    }
    body.dark .form-control:focus {
        background-color: #343a40;
        color: #fff;
    }
    .search-filter-form {
        padding: 15px;
        border-radius: 12px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
    }
    body.dark .search-filter-form {
        background-color: #2b3035;
        border-color: #3d4349;
    }

    /* Modal styling */
    body.dark .modal-content {
        background-color: #1f2229;
        color: #fff;
        border-color: #3d4349;
    }
    body.dark .modal-header, body.dark .modal-footer {
        border-color: #3d4349;
    }

    .badge {
        font-size: 0.75em;
        margin: 2px;
    }

    .select2-container {
        width: 100% !important;
    }

    /* Image zoom viewer */
    .image-container {
        cursor: grab;
    }
    .image-container:active {
        cursor: grabbing;
    }
    #viewerImage {
        cursor: zoom-in;
    }

    /* SortableJS drag & drop */
    .sortable-ghost {
        opacity: 0.4;
        background: #007bff !important;
    }
    .sortable-chosen {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }
    .reorder-mode .mentioned-person-item {
        cursor: move;
        border: 1px dashed #007bff;
    }
    .reorder-mode .btn-remove-mention {
        display: none !important;
    }
    .pagination {
        justify-content: center;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    // helper for initializing AJAX-based Select2 for people search
    function initAjaxSelect2(element) {
        $(element).select2({
            placeholder: 'ابحث عن شخص لذكر اسمه...',
            allowClear: true,
            theme: 'bootstrap4',
            language: {
                noResults: function() { return "لا توجد نتائج"; },
                searching: function() { return "جاري البحث..."; }
            },
            ajax: {
                url: "{{ route('people.search') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        term: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            },
            minimumInputLength: 2
        });
    }

    // تهيئة Select2 للبحث عن الأشخاص
    $(document).ready(function() {
        // فلتر البحث بالرئيسية
        $('#personSearchSelect').select2({
            placeholder: 'ابحث عن شخص...',
            allowClear: true,
            theme: 'bootstrap4',
            language: {
                noResults: function() { return "لا توجد نتائج"; },
                searching: function() { return "جاري البحث..."; }
            },
            ajax: {
                url: "{{ route('people.search') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        term: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            },
            minimumInputLength: 2
        });

        // تهيئة select2 للمودالات عند التحميل
        initAjaxSelect2('.select2-ajax-people');
    });

    // تحديث حالة الأزرار العائمة وتظليل الكروت عند تحديدها
    function updateSelectionState() {
        const checkboxes = document.querySelectorAll('input.image-checkbox');
        const checkedCheckboxes = document.querySelectorAll('input.image-checkbox:checked');
        const checkedCount = checkedCheckboxes.length;
        
        // تحديث النص في الشريط العائم
        document.getElementById('selectedCountText').textContent = `${checkedCount} ملف محدد`;
        
        // إظهار/إخفاء الشريط العائم
        const floatingBar = document.getElementById('bulkFloatingBar');
        if (checkedCount > 0) {
            floatingBar.classList.add('active');
        } else {
            floatingBar.classList.remove('active');
        }

        // تحديث كلاسات الكروت المحددة لتظليلها
        checkboxes.forEach(cb => {
            const card = cb.closest('.media-card');
            if (cb.checked) {
                card.classList.add('border-primary');
                card.style.boxShadow = '0 0 10px rgba(0, 123, 255, 0.25)';
            } else {
                card.classList.remove('border-primary');
                card.style.boxShadow = '';
            }
        });
        
        // مزامنة checkbox "اختيار الكل"
        const checkAll = document.getElementById('checkAll');
        if (checkAll) {
            checkAll.checked = checkboxes.length === checkedCount;
        }
    }

    // الاستماع لتغييرات الـ checkboxes
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('image-checkbox')) {
            updateSelectionState();
        }
    });

    // اختيار الكل
    document.addEventListener('change', function(e) {
        if (e.target.id === 'checkAll') {
            const isChecked = e.target.checked;
            document.querySelectorAll('input.image-checkbox').forEach(cb => {
                cb.checked = isChecked;
            });
            updateSelectionState();
        }
    });

    // إلغاء تحديد الكل
    document.getElementById('clearSelectionBtn').addEventListener('click', function() {
        document.querySelectorAll('input.image-checkbox').forEach(cb => {
            cb.checked = false;
        });
        updateSelectionState();
    });

    // حذف جماعي
    function submitBulkDelete() {
        const selectedCount = document.querySelectorAll('input.image-checkbox:checked').length;
        if (selectedCount === 0) {
            alert('يرجى تحديد صور للحذف');
            return;
        }
        if (confirm(`هل أنت متأكد من رغبتك في حذف ${selectedCount} صورة محددة؟`)) {
            document.getElementById('bulkDeleteForm').submit();
        }
    }
    document.getElementById('bulkDeleteBtn').addEventListener('click', submitBulkDelete);
    document.getElementById('bulkDeleteBtnFloating').addEventListener('click', submitBulkDelete);

    // نقل جماعي
    function openBulkMoveModal() {
        const selectedCheckboxes = document.querySelectorAll('input.image-checkbox:checked');
        const selectedCount = selectedCheckboxes.length;

        if (selectedCount === 0) {
            alert('يرجى تحديد صور للنقل');
            return;
        }

        // تحديث عدد الصور المحددة في المودال
        document.getElementById('selectedImagesCount').textContent = selectedCount;

        // فتح المودال
        $('#bulkMoveModal').modal('show');
    }
    document.getElementById('bulkMoveBtn').addEventListener('click', openBulkMoveModal);
    document.getElementById('bulkMoveBtnFloating').addEventListener('click', openBulkMoveModal);

    // معالج نموذج النقل الجماعي
    document.getElementById('bulkMoveForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const selectedCheckboxes = document.querySelectorAll('input.image-checkbox:checked');
        const ids = Array.from(selectedCheckboxes).map(cb => cb.value);
        const targetCategoryId = document.getElementById('targetCategorySelect').value;

        if (!targetCategoryId) {
            alert('يرجى اختيار فئة هدف');
            return;
        }

        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> جارٍ النقل...');

        $.ajax({
            url: "{{ route('images.bulk-move') }}",
            method: 'POST',
            data: {
                ids: ids,
                target_category_id: targetCategoryId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('#bulkMoveModal').modal('hide');
                    alert(response.message);
                    location.reload();
                } else {
                    alert(response.message || 'تعذر نقل الصور');
                    submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors;
                if (errors) {
                    let errorMessage = 'يرجى تصحيح الأخطاء التالية:\n';
                    Object.values(errors).forEach(error => {
                        errorMessage += '- ' + error[0] + '\n';
                    });
                    alert(errorMessage);
                } else {
                    alert('خطأ في الاتصال بالخادم');
                }
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // حذف فردي
    function deleteSingleImage(imageId) {
        if (confirm('هل أنت متأكد من رغبتك في حذف هذا الملف نهائياً؟')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/dashboard/images/${imageId}`;
            form.innerHTML = `
                <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                <input type="hidden" name="_method" value="DELETE">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }

    // تعديل صورة
    function editImage(imageId) {
        $.ajax({
            url: `/dashboard/images/${imageId}/edit`,
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    const image = response.image;

                    // ملء البيانات في المودال
                    $('#editImageName').val(image.name || '');
                    $('#editImageCategory').val(image.category_id || '');
                    $('#editImageDescription').val(image.description || '');

                    // تحديث الأشخاص المذكورين
                    $('#editMentionedPersons').empty();
                    if (image.mentioned_persons) {
                        image.mentioned_persons.forEach(function(person) {
                            var option = new Option(person.full_name, person.id, true, true);
                            $('#editMentionedPersons').append(option);
                        });
                    }
                    $('#editMentionedPersons').trigger('change');

                    // عرض المحتوى الحالي
                    if (image.media_type === 'youtube' && image.youtube_url) {
                        $('#currentImage').hide();
                        $('#currentYoutubePreview').show();

                        // استخراج معرف الفيديو
                        let videoId = '';
                        const patterns = [
                            /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/,
                            /youtube\.com\/watch\?.*v=([a-zA-Z0-9_-]{11})/
                        ];

                        for (let pattern of patterns) {
                            const match = image.youtube_url.match(pattern);
                            if (match) {
                                videoId = match[1];
                                break;
                            }
                        }

                        if (videoId) {
                            const embedUrl = `https://www.youtube.com/embed/${videoId}`;
                            $('#currentYoutubeIframe').attr('src', embedUrl);
                            $('#currentYoutubeLink').attr('href', image.youtube_url);
                        }

                        $('#edit_media_type_youtube').prop('checked', true);
                        $('#edit-image-section').hide();
                        $('#edit-youtube-section').show();
                        $('#edit-pdf-section').hide();
                        $('#editYoutubeUrl').val(image.youtube_url);

                        if (image.thumbnail_path) {
                            $('#editYoutubeThumbnailFile').next('.custom-file-label').text('صورة مصغرة موجودة');
                        }
                    } else {
                        $('#currentYoutubePreview').hide();
                        $('#currentImage').show();

                        let imageUrl;
                        if (image.thumbnail_path) {
                            imageUrl = `{{ asset('storage/') }}/${image.thumbnail_path}`;
                        } else if (image.path) {
                            imageUrl = `{{ asset('storage/') }}/${image.path}`;
                        } else {
                            imageUrl = '{{ asset('img/no-image.png') }}';
                        }

                        $('#currentImage').attr('src', imageUrl);

                        if (image.media_type === 'pdf') {
                            $('#edit_media_type_pdf').prop('checked', true);
                            $('#edit-image-section').hide();
                            $('#edit-pdf-section').show();
                            $('#edit-youtube-section').hide();

                            if (image.thumbnail_path) {
                                $('#editThumbnailFile').next('.custom-file-label').text('صورة مصغرة موجودة');
                            }
                        } else {
                            $('#edit_media_type_image').prop('checked', true);
                            $('#edit-youtube-section').hide();
                            $('#edit-image-section').show();
                            $('#edit-pdf-section').hide();
                        }
                    }

                    $('#editImageForm').attr('action', `/dashboard/images/${imageId}`);
                    $('#editImageModal').modal('show');
                } else {
                    alert('تعذر جلب بيانات الصورة');
                }
            },
            error: function() {
                alert('خطأ في الاتصال بالخادم');
            }
        });
    }

    // إظهار أسماء الملفات
    $(document).on('change', '.custom-file-input', function() {
        const names = Array.from(this.files || []).map(f => f.name).join(', ');
        $(this).next('.custom-file-label').html(names || 'اختر ملفات...');
    });

    // معالج نموذج تعديل الصورة
    $('#editImageForm').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        const submitBtn = $(form).find('button[type="submit"]');

        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> جارٍ الحفظ...');

        $.ajax({
            url: $(form).attr('action'),
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('#editImageModal').modal('hide');
                    location.reload();
                } else {
                    alert(response.message || 'تعذر حفظ التعديلات');
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors;
                if (errors) {
                    let errorMessage = 'يرجى تصحيح الأخطاء التالية:\n';
                    Object.values(errors).forEach(error => {
                        errorMessage += '- ' + error[0] + '\n';
                    });
                    alert(errorMessage);
                } else {
                    alert('خطأ في الاتصال بالخادم');
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('<i class="fas fa-save"></i> حفظ التعديلات');
            }
        });
    });

    // حذف شخص من صورة
    function removePersonFromImage(imageId, personId) {
        if (confirm('هل تريد حذف هذا الشخص من الصورة؟\nسيتم إزالة العلاقة بين الشخص والصورة فقط.')) {
            $.ajax({
                url: `/dashboard/images/${imageId}/remove-person/${personId}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message || 'تعذر حذف الشخص من الصورة');
                    }
                },
                error: function() {
                    alert('خطأ في الاتصال بالخادم');
                }
            });
        }
    }

    // إنشاء فئة سريع (AJAX)
    $('#quickCategoryForm').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        const fd = new FormData(form);
        const btn = $('#quickCategorySubmit');
        btn.prop('disabled', true).text('جارٍ الحفظ...');
        $.ajax({
            url: "{{ route('categories.quick-store') }}",
            method: "POST",
            data: fd,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(resp) {
                if (resp.ok && resp.category) {
                    $('#uploadCategorySelect').append(new Option(resp.category.name, resp.category.id, true, true));
                    $('#quickCategoryModal').modal('hide');
                    form.reset();
                }
            },
            error: function() {
                alert('تعذر إنشاء الفئة.');
            },
            complete: function() {
                btn.prop('disabled', false).text('حفظ الفئة');
            }
        });
    });

    // متغيرات التكبير
    let currentZoom = 1;
    let currentImageId = null;
    let currentImageName = '';
    let currentX = 0;
    let currentY = 0;

    // عرض الميديا المناسبة عند الضغط
    function triggerMediaView(id, type, url, path, name) {
        if (type === 'youtube' && url) {
            viewYouTube(id, url, name);
        } else {
            viewImage(id, path, name);
        }
    }

    // عرض الصورة في المودال
    function viewImage(imageId, imageSrc, imageName) {
        currentImageId = imageId;
        currentImageName = imageName;
        currentZoom = 1;
        currentX = 0;
        currentY = 0;

        $('#viewerImage').attr('src', imageSrc).attr('alt', imageName);
        $('#imageViewerModalLabel').text('عرض الصورة: ' + imageName);
        $('#viewerImage').css('transform', 'scale(1)');
        $('#imageViewerModal').modal('show');
    }

    // عرض فيديو يوتيوب في المودال
    function viewYouTube(imageId, youtubeUrl, videoName) {
        let videoId = '';
        const patterns = [
            /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/,
            /youtube\.com\/watch\?.*v=([a-zA-Z0-9_-]{11})/
        ];

        for (let pattern of patterns) {
            const match = youtubeUrl.match(pattern);
            if (match) {
                videoId = match[1];
                break;
            }
        }

        if (videoId) {
            const embedUrl = `https://www.youtube.com/embed/${videoId}`;
            $('#youtubeViewerIframe').attr('src', embedUrl);
            $('#youtubeViewerLink').attr('href', youtubeUrl);
            $('#youtubeViewerModalLabel').text('مشاهدة: ' + videoName);
            $('#youtubeViewerModal').modal('show');
        } else {
            alert('رابط يوتيوب غير صحيح');
        }
    }

    // تكبير الصورة
    function zoomIn() {
        currentZoom += 0.25;
        $('#viewerImage').css('transform', `scale(${currentZoom}) translate(${currentX}px, ${currentY}px)`);
    }

    // تصغير الصورة
    function zoomOut() {
        if (currentZoom > 0.25) {
            currentZoom -= 0.25;
            $('#viewerImage').css('transform', `scale(${currentZoom}) translate(${currentX}px, ${currentY}px)`);
        }
    }

    // إعادة تعيين التكبير
    function resetZoom() {
        currentZoom = 1;
        currentX = 0;
        currentY = 0;
        $('#viewerImage').css('transform', 'scale(1)');
    }

    // تحميل الصورة الحالية
    function downloadCurrentImage() {
        if (currentImageId) {
            downloadImage(currentImageId, currentImageName);
        }
    }

    // تحميل الصورة
    function downloadImage(imageId, imageName) {
        const link = document.createElement('a');
        link.href = `/dashboard/images/${imageId}/download`;
        link.download = imageName || 'صورة';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // تكبير بالماوس
    $('#viewerImage').on('click', function() {
        zoomIn();
    });

    // سحب الصورة
    let isDragging = false;
    let startX = 0;
    let startY = 0;

    $('#viewerImage').on('mousedown', function(e) {
        if (currentZoom > 1) {
            isDragging = true;
            startX = e.clientX - currentX;
            startY = e.clientY - currentY;
            $(this).css('cursor', 'grabbing');
        }
    });

    $(document).on('mousemove', function(e) {
        if (isDragging && currentZoom > 1) {
            currentX = e.clientX - startX;
            currentY = e.clientY - startY;
            $('#viewerImage').css('transform', `scale(${currentZoom}) translate(${currentX}px, ${currentY}px)`);
        }
    });

    $(document).on('mouseup', function() {
        isDragging = false;
        $('#viewerImage').css('cursor', 'zoom-in');
    });

    // تكبير بعجلة الماوس
    $('#viewerImage').on('wheel', function(e) {
        e.preventDefault();
        if (e.originalEvent.deltaY < 0) {
            zoomIn();
        } else {
            zoomOut();
        }
    });

    // تكبير بالكيبورد
    $(document).on('keydown', function(e) {
        if ($('#imageViewerModal').hasClass('show')) {
            if (e.key === '+' || e.key === '=') {
                e.preventDefault();
                zoomIn();
            } else if (e.key === '-') {
                e.preventDefault();
                zoomOut();
            } else if (e.key === '0') {
                e.preventDefault();
                resetZoom();
            }
        }
    });

    // إعادة ترتيب الأشخاص باستخدام SortableJS
    let sortableInstances = {};
    let reorderMode = false;

    function toggleReorderMode(imageId) {
        const container = $(`.mentioned-persons-container[data-image-id="${imageId}"]`);
        const sortableList = container.find('.sortable-list');

        if (reorderMode) {
            container.removeClass('reorder-mode');
            container.find('.reorder-controls').remove();
            container.find('.drag-handle').hide();

            if (sortableInstances[imageId]) {
                sortableInstances[imageId].destroy();
                delete sortableInstances[imageId];
            }
            reorderMode = false;
        } else {
            container.addClass('reorder-mode');
            container.find('.drag-handle').show();

            const controls = `
                <div class="reorder-controls mt-2 d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-xs btn-success px-2 py-1" onclick="saveReorder(${imageId})">
                        <i class="fas fa-check"></i> حفظ
                    </button>
                    <button type="button" class="btn btn-xs btn-secondary px-2 py-1" onclick="cancelReorder(${imageId})">
                        <i class="fas fa-times"></i> إلغاء
                    </button>
                </div>
            `;
            container.append(controls);

            sortableInstances[imageId] = new Sortable(sortableList[0], {
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                handle: '.drag-handle'
            });

            reorderMode = true;
        }
    }

    function saveReorder(imageId) {
        const container = $(`.mentioned-persons-container[data-image-id="${imageId}"]`);
        const personIds = [];

        container.find('.mentioned-person-item').each(function() {
            personIds.push($(this).data('person-id'));
        });

        const saveBtn = container.find('.reorder-controls .btn-success');
        const originalText = saveBtn.html();
        saveBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> ...');

        $.ajax({
            url: `/dashboard/images/${imageId}/reorder-persons`,
            method: 'POST',
            data: {
                person_ids: personIds,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    alert('تم حفظ الترتيب بنجاح');
                    location.reload();
                } else {
                    alert(response.message || 'تعذر حفظ الترتيب');
                    saveBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr) {
                let errorMessage = 'خطأ في الاتصال بالخادم';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                alert(errorMessage);
                saveBtn.prop('disabled', false).html(originalText);
            }
        });
    }

    function cancelReorder(imageId) {
        const container = $(`.mentioned-persons-container[data-image-id="${imageId}"]`);
        container.removeClass('reorder-mode');
        container.find('.reorder-controls').remove();
        container.find('.drag-handle').hide();

        if (sortableInstances[imageId]) {
            sortableInstances[imageId].destroy();
            delete sortableInstances[imageId];
        }

        reorderMode = false;
        location.reload();
    }

    // التحكم في نوع المحتوى في نموذج التعديل
    $(document).ready(function() {
        $('#edit_media_type_image').on('change', function() {
            if ($(this).is(':checked')) {
                $('#edit-image-section').show();
                $('#edit-youtube-section').hide();
            }
        });

        $('#edit_media_type_youtube').on('change', function() {
            if ($(this).is(':checked')) {
                $('#edit-image-section').hide();
                $('#edit-youtube-section').show();
            }
        });
    });
</script>
@endpush
