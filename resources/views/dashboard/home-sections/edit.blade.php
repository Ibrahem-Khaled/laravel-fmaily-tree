@extends('layouts.app')

@section('title', 'بناء قسم: ' . $homeSection->title)

@push('styles')
<style>
    /* ============================================ */
    /* Builder Layout                                */
    /* ============================================ */
    .builder-container { display: flex; gap: 20px; min-height: 75vh; }
    .builder-sidebar { width: 320px; flex-shrink: 0; }
    .builder-main { flex: 1; min-width: 0; }
    
    @media (max-width: 992px) {
        .builder-container { flex-direction: column; }
        .builder-sidebar { width: 100%; }
    }

    /* ============================================ */
    /* Sidebar Panels                                */
    /* ============================================ */
    .settings-card { border-radius: 14px; border: none; overflow: hidden; }
    .settings-card .card-header { 
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff; padding: 12px 16px; cursor: pointer;
    }
    .settings-card .card-header h6 { margin: 0; font-size: 0.9rem; }
    .settings-card .card-body { padding: 16px; }
    
    .color-input-group { display: flex; align-items: center; gap: 8px; }
    .color-input-group input[type="color"] {
        width: 38px; height: 38px; border: 2px solid #e3e6f0; 
        border-radius: 8px; padding: 2px; cursor: pointer;
    }

    /* ============================================ */
    /* Items Builder                                  */
    /* ============================================ */
    .item-card {
        border: 2px solid #e8ecf1;
        border-radius: 14px;
        margin-bottom: 16px;
        background: #fff;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .item-card:hover { border-color: #4e73df; box-shadow: 0 4px 15px rgba(78,115,223,0.15); }
    .item-card.collapsed-item .item-body { display: none; }
    
    .item-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 12px 16px; background: #f8f9fc; cursor: pointer;
        border-bottom: 1px solid #e8ecf1;
    }
    .item-header-left { display: flex; align-items: center; gap: 12px; flex: 1; }
    .item-drag-handle { color: #a0aec0; cursor: grab; font-size: 1.1rem; }
    .item-drag-handle:active { cursor: grabbing; }
    .item-type-badge {
        padding: 4px 10px; border-radius: 20px; font-size: 0.75rem;
        font-weight: 700; white-space: nowrap;
    }
    .item-header-actions { display: flex; gap: 4px; }
    .item-header-actions .btn { padding: 4px 8px; font-size: 0.8rem; border-radius: 8px; }
    
    .item-body { padding: 16px; }

    /* ============================================ */
    /* Add Item Toolbar                              */
    /* ============================================ */
    .add-item-toolbar {
        display: flex; flex-wrap: wrap; gap: 10px; 
        padding: 20px; background: #f8f9fc;
        border: 2px dashed #d1d9e6; border-radius: 14px;
        justify-content: center; margin-bottom: 20px;
    }
    .add-item-btn {
        display: flex; flex-direction: column; align-items: center;
        padding: 12px 16px; border-radius: 12px; border: 2px solid #e3e6f0;
        background: #fff; cursor: pointer; transition: all 0.3s ease;
        min-width: 90px; text-decoration: none; color: inherit;
    }
    .add-item-btn:hover {
        border-color: #4e73df; transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(78,115,223,0.2);
        text-decoration: none; color: inherit;
    }
    .add-item-btn i { font-size: 1.3rem; margin-bottom: 6px; }
    .add-item-btn span { font-size: 0.75rem; font-weight: 600; }

    /* ============================================ */
    /* Table Editor                                   */
    /* ============================================ */
    .table-editor-container { overflow-x: auto; }
    .table-editor {
        width: 100%; border-collapse: collapse;
        font-size: 0.85rem;
    }
    .table-editor th, .table-editor td {
        border: 1px solid #d1d9e6; padding: 6px 8px;
        min-width: 80px; position: relative;
    }
    .table-editor th { background: #f0f2f8; font-weight: 700; text-align: center; }
    .table-editor td[contenteditable] {
        outline: none; cursor: text;
    }
    .table-editor td[contenteditable]:focus {
        background: #eef2ff; box-shadow: inset 0 0 0 2px #4e73df;
    }
    .table-toolbar { display: flex; gap: 6px; margin-bottom: 8px; flex-wrap: wrap; }
    .table-toolbar .btn { font-size: 0.75rem; padding: 4px 10px; }

    /* ============================================ */
    /* Sortable Ghost                                 */
    /* ============================================ */
    .sortable-ghost { opacity: 0.3; background: #e9ecef; border-radius: 14px; }
    .sortable-chosen { cursor: grabbing; }
    .sortable-drag { box-shadow: 0 10px 30px rgba(0,0,0,0.2); }

    /* ============================================ */
    /* Upload Zone                                    */
    /* ============================================ */
    .upload-zone {
        border: 2px dashed #d1d9e6; border-radius: 12px;
        padding: 24px; text-align: center; cursor: pointer;
        transition: all 0.3s ease; background: #fafbfd;
    }
    .upload-zone:hover, .upload-zone.dragover {
        border-color: #4e73df; background: #eef2ff;
    }
    .upload-zone i { font-size: 2rem; color: #a0aec0; }
    .upload-zone p { margin: 8px 0 0; color: #718096; font-size: 0.85rem; }
    
    .image-preview {
        max-width: 100%; max-height: 200px; border-radius: 8px;
        object-fit: cover; margin-top: 8px;
    }

    /* ============================================ */
    /* Quick Toggle                                   */
    /* ============================================ */
    .custom-switch .custom-control-label::before { border-radius: 20px; }
    .section-type-info {
        display: inline-flex; align-items: center; gap: 6px;
        background: #eef2ff; color: #4e73df; padding: 4px 12px;
        border-radius: 20px; font-size: 0.8rem; font-weight: 600;
    }

    /* ============================================ */
    /* TinyMCE Override                               */
    /* ============================================ */
    .tox-tinymce { border-radius: 10px !important; border: 1px solid #d1d9e6 !important; }
    
    /* HTML معاينة: عناصر قابلة للتعديل من المعاينة */
    #htmlPreviewFrame .html-preview-editable {
        outline: 1px dashed transparent;
        border-radius: 4px;
        transition: outline-color 0.15s, background-color 0.15s;
    }
    #htmlPreviewFrame .html-preview-editable:hover {
        outline-color: #4e73df;
        background-color: rgba(78, 115, 223, 0.06);
    }
    #htmlPreviewFrame .html-preview-editable:focus {
        outline: 2px solid #4e73df;
        outline-offset: 2px;
        background-color: rgba(78, 115, 223, 0.08);
    }
    
    /* Empty State */
    .empty-items-state {
        text-align: center; padding: 60px 20px;
        color: #a0aec0;
    }
    .empty-items-state i { font-size: 4rem; margin-bottom: 16px; opacity: 0.5; }
    .empty-items-state h5 { color: #718096; margin-bottom: 8px; }
    .empty-items-state p { color: #a0aec0; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Top Bar -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap" style="gap: 12px;">
        <div class="d-flex align-items-center flex-wrap" style="gap: 12px;">
            <a href="{{ route('dashboard.home-sections.index') }}" class="btn btn-light shadow-sm" style="border-radius: 10px;">
                <i class="fas fa-arrow-right"></i>
            </a>
            <div>
                <h1 class="h4 mb-0 text-gray-800 font-weight-bold">
                    <i class="fas fa-magic text-primary mr-1"></i>{{ $homeSection->title }}
                </h1>
                <div class="d-flex align-items-center mt-1" style="gap: 8px;">
                    <span class="section-type-info">
                        <i class="fas fa-layer-group"></i>{{ $homeSection->section_type }}
                    </span>
                    <span class="badge badge-{{ $homeSection->is_active ? 'success' : 'secondary' }}">
                        {{ $homeSection->is_active ? 'نشط' : 'معطل' }}
                    </span>
                    <small class="text-muted">{{ $homeSection->items->count() }} عنصر</small>
                </div>
            </div>
        </div>
        <div class="d-flex" style="gap: 8px;">
            <button type="button" class="btn btn-outline-info shadow-sm" style="border-radius: 10px;" 
                    onclick="togglePreview()">
                <i class="fas fa-eye mr-1"></i>معاينة
            </button>
            <button type="submit" form="sectionSettingsForm" class="btn btn-primary shadow-sm" style="border-radius: 10px;">
                <i class="fas fa-save mr-1"></i>حفظ الإعدادات
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 12px;">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 12px;">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="builder-container">
        <!-- ======================================== -->
        <!-- Sidebar: Section Settings                 -->
        <!-- ======================================== -->
        <div class="builder-sidebar">
            <form id="sectionSettingsForm" action="{{ route('dashboard.home-sections.update', $homeSection) }}" method="POST">
                @csrf

                <!-- Basic Settings -->
                <div class="card settings-card shadow-sm mb-3">
                    <div class="card-header" data-toggle="collapse" data-target="#basicSettings">
                        <h6><i class="fas fa-cog mr-2"></i>الإعدادات الأساسية</h6>
                    </div>
                    <div class="collapse show" id="basicSettings">
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold small">العنوان <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" 
                                       value="{{ old('title', $homeSection->title) }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label class="font-weight-bold small">العنوان الفرعي</label>
                                <input type="text" name="settings[subtitle]" class="form-control" 
                                       value="{{ old('settings.subtitle', $homeSection->settings['subtitle'] ?? '') }}">
                            </div>
                            <div class="form-group mb-3">
                                <label class="font-weight-bold small">نوع القسم</label>
                                <select name="section_type" class="form-control no-search">
                                    @foreach(['rich_text'=>'نص غني','gallery'=>'معرض صور','cards'=>'بطاقات','table'=>'جدول','text_with_image'=>'نص مع صورة','video_section'=>'فيديو','hero'=>'بانر رئيسي','buttons'=>'أزرار','stats'=>'إحصائيات','divider'=>'فاصل','custom'=>'HTML مخصص','mixed'=>'مختلط'] as $val => $label)
                                        <option value="{{ $val }}" {{ $homeSection->section_type == $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label class="font-weight-bold small">الأيقونة</label>
                                <input type="text" name="settings[icon]" class="form-control" 
                                       value="{{ old('settings.icon', $homeSection->settings['icon'] ?? '') }}"
                                       placeholder="fas fa-star">
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="custom-control custom-switch mb-2">
                                        <input type="checkbox" name="is_active" id="edit_is_active" 
                                               class="custom-control-input" value="1"
                                               {{ old('is_active', $homeSection->is_active) ? 'checked' : '' }}>
                                        <label class="custom-control-label small" for="edit_is_active">نشط</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="custom-control custom-switch mb-2">
                                        <input type="checkbox" name="settings[show_title]" id="edit_show_title" 
                                               class="custom-control-input" value="1"
                                               {{ old('settings.show_title', $homeSection->settings['show_title'] ?? true) ? 'checked' : '' }}>
                                        <label class="custom-control-label small" for="edit_show_title">إظهار العنوان</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Design Settings -->
                <div class="card settings-card shadow-sm mb-3">
                    <div class="card-header" data-toggle="collapse" data-target="#designSettings" 
                         style="background: linear-gradient(135deg, #36b9cc 0%, #1cc88a 100%);">
                        <h6><i class="fas fa-palette mr-2"></i>التصميم</h6>
                    </div>
                    <div class="collapse show" id="designSettings">
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold small">لون الخلفية</label>
                                <div class="color-input-group">
                                    <input type="color" name="settings[background_color]" 
                                           value="{{ old('settings.background_color', $homeSection->settings['background_color'] ?? '#ffffff') }}">
                                    <input type="text" class="form-control form-control-sm" 
                                           value="{{ old('settings.background_color', $homeSection->settings['background_color'] ?? '#ffffff') }}"
                                           style="flex: 1;">
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label class="font-weight-bold small">لون النص</label>
                                <div class="color-input-group">
                                    <input type="color" name="settings[text_color]" 
                                           value="{{ old('settings.text_color', $homeSection->settings['text_color'] ?? '#333333') }}">
                                    <input type="text" class="form-control form-control-sm" 
                                           value="{{ old('settings.text_color', $homeSection->settings['text_color'] ?? '#333333') }}"
                                           style="flex: 1;">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold small">مسافة علوية</label>
                                        <div class="input-group input-group-sm">
                                            <input type="number" name="settings[padding_top]" class="form-control" 
                                                   value="{{ old('settings.padding_top', $homeSection->settings['padding_top'] ?? 40) }}" min="0" max="200">
                                            <div class="input-group-append"><span class="input-group-text">px</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold small">مسافة سفلية</label>
                                        <div class="input-group input-group-sm">
                                            <input type="number" name="settings[padding_bottom]" class="form-control" 
                                                   value="{{ old('settings.padding_bottom', $homeSection->settings['padding_bottom'] ?? 40) }}" min="0" max="200">
                                            <div class="input-group-append"><span class="input-group-text">px</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label class="font-weight-bold small">عدد الأعمدة</label>
                                <select name="settings[columns]" class="form-control no-search form-control-sm">
                                    @foreach([1=>'1 عمود',2=>'2 أعمدة',3=>'3 أعمدة',4=>'4 أعمدة',6=>'6 أعمدة'] as $v => $l)
                                        <option value="{{ $v }}" {{ ($homeSection->settings['columns'] ?? 3) == $v ? 'selected' : '' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-0">
                                <label class="font-weight-bold small">CSS مخصص</label>
                                <input type="text" name="css_classes" class="form-control form-control-sm" 
                                       value="{{ old('css_classes', $homeSection->css_classes) }}"
                                       placeholder="classes إضافية">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- ======================================== -->
        <!-- Main: Content Builder                     -->
        <!-- ======================================== -->
        <div class="builder-main">
            <!-- Add Item Toolbar -->
            <div class="add-item-toolbar">
                <span class="text-muted font-weight-bold ml-2 align-self-center" style="font-size: 0.85rem;">
                    <i class="fas fa-plus-circle mr-1"></i>إضافة:
                </span>
                <a href="javascript:void(0)" class="add-item-btn" onclick="openAddItemModal('rich_text')">
                    <i class="fas fa-align-right text-primary"></i>
                    <span>نص غني</span>
                </a>
                <a href="javascript:void(0)" class="add-item-btn" onclick="openAddItemModal('image')">
                    <i class="fas fa-image text-success"></i>
                    <span>صورة</span>
                </a>
                <a href="javascript:void(0)" class="add-item-btn" onclick="openAddItemModal('video')">
                    <i class="fas fa-play-circle text-danger"></i>
                    <span>فيديو</span>
                </a>
                <a href="javascript:void(0)" class="add-item-btn" onclick="openAddItemModal('table')">
                    <i class="fas fa-table text-warning"></i>
                    <span>جدول</span>
                </a>
                <a href="javascript:void(0)" class="add-item-btn" onclick="openAddItemModal('card')">
                    <i class="fas fa-id-card text-info"></i>
                    <span>بطاقة</span>
                </a>
                <a href="javascript:void(0)" class="add-item-btn" onclick="openAddItemModal('button')">
                    <i class="fas fa-mouse-pointer text-purple" style="color: #6f42c1;"></i>
                    <span>زر</span>
                </a>
                <a href="javascript:void(0)" class="add-item-btn" onclick="openAddItemModal('html')">
                    <i class="fas fa-code text-secondary"></i>
                    <span>HTML</span>
                </a>
                <a href="javascript:void(0)" class="add-item-btn" onclick="openAddItemModal('spacer')">
                    <i class="fas fa-arrows-alt-v text-muted"></i>
                    <span>فاصل</span>
                </a>
            </div>

            <!-- Items List -->
            <div id="items-list">
                @forelse($homeSection->items as $item)
                    @include('dashboard.home-sections._item-card', ['item' => $item, 'homeSection' => $homeSection])
                @empty
                    <div class="empty-items-state" id="empty-state">
                        <i class="fas fa-cubes"></i>
                        <h5>ابدأ البناء!</h5>
                        <p>اختر نوع العنصر من شريط الأدوات أعلاه لبدء بناء محتوى القسم</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- ======================================== -->
<!-- Preview Modal                              -->
<!-- ======================================== -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="fas fa-eye mr-2"></i>معاينة القسم</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-0" id="previewContent" style="min-height: 300px;">
                <!-- Preview rendered here -->
            </div>
        </div>
    </div>
</div>

<!-- ======================================== -->
<!-- Add/Edit Item Modal                        -->
<!-- ======================================== -->
<div class="modal fade" id="itemModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff;">
                <h5 class="modal-title" id="itemModalTitle">
                    <i class="fas fa-plus-circle mr-2"></i>إضافة عنصر
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="itemForm" action="" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="item_type" id="modal_item_type">
                <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
                    <!-- Dynamic content based on type -->
                    <div id="modal-content-area"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary" id="itemFormSubmit">
                        <i class="fas fa-save mr-1"></i>حفظ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- SortableJS -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<!-- TinyMCE (Free CDN - no API key needed) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>

<script>
const CSRF_TOKEN = '{{ csrf_token() }}';
const SECTION_ID = {{ $homeSection->id }};
const STORE_URL = '{{ route("dashboard.home-section-items.store", $homeSection) }}';
const REORDER_URL = '{{ route("dashboard.home-section-items.reorder", $homeSection) }}';
// Use server-generated URL so path is correct (subdirectory, APP_URL); append item id in editItem
const ITEM_SHOW_URL_BASE = "{{ url(route('dashboard.home-section-items.show', [$homeSection, 0])) }}";
const ITEM_SHOW_URL_TEMPLATE = ITEM_SHOW_URL_BASE.substring(0, ITEM_SHOW_URL_BASE.lastIndexOf('/') + 1);

// ============================================
// Item Type Configurations
// ============================================
const ITEM_TYPES = {
    'rich_text': { name: 'نص غني', icon: 'fa-align-right', color: '#4e73df', badge: 'primary' },
    'text':      { name: 'نص', icon: 'fa-font', color: '#5a5c69', badge: 'secondary' },
    'image':     { name: 'صورة', icon: 'fa-image', color: '#1cc88a', badge: 'success' },
    'video':     { name: 'فيديو', icon: 'fa-play-circle', color: '#e74a3b', badge: 'danger' },
    'table':     { name: 'جدول', icon: 'fa-table', color: '#f6c23e', badge: 'warning' },
    'card':      { name: 'بطاقة', icon: 'fa-id-card', color: '#36b9cc', badge: 'info' },
    'button':    { name: 'زر', icon: 'fa-mouse-pointer', color: '#6f42c1', badge: 'purple' },
    'html':      { name: 'HTML', icon: 'fa-code', color: '#5a5c69', badge: 'dark' },
    'spacer':    { name: 'فاصل', icon: 'fa-arrows-alt-v', color: '#858796', badge: 'secondary' },
};

// ============================================
// Sortable Drag & Drop
// ============================================
const itemsList = document.getElementById('items-list');
if (itemsList) {
    new Sortable(itemsList, {
        handle: '.item-drag-handle',
        animation: 300,
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        onEnd: function() {
            const items = document.querySelectorAll('#items-list .item-card[data-id]');
            const orders = Array.from(items).map(item => item.dataset.id);
            
            fetch(REORDER_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                body: JSON.stringify({ orders: orders })
            }).then(r => r.json()).then(data => {
                if (data.success) showToast('تم حفظ الترتيب');
            });
        }
    });
}

// ============================================
// Open Add Item Modal
// ============================================
function openAddItemModal(type) {
    const typeInfo = ITEM_TYPES[type] || { name: type, icon: 'fa-cube', color: '#333' };
    
    document.getElementById('itemModalTitle').innerHTML = 
        '<i class="fas ' + typeInfo.icon + ' mr-2"></i>إضافة ' + typeInfo.name;
    document.getElementById('modal_item_type').value = type;
    document.getElementById('itemForm').action = STORE_URL;
    
    // Remove old _method if exists
    const oldMethod = document.getElementById('itemForm').querySelector('input[name="_method"]');
    if (oldMethod) oldMethod.remove();
    
    renderModalContent(type, {});
    $('#itemModal').modal('show');
}

// ============================================
// Open Edit Item Modal (load item via AJAX)
// ============================================
function editItem(itemId) {
    const form = document.getElementById('itemForm');
    const submitBtn = document.getElementById('itemFormSubmit');
    const modalBody = document.querySelector('#itemModal .modal-body');
    
    var itemUrl = ITEM_SHOW_URL_TEMPLATE + itemId;
    form.action = itemUrl;
    const oldMethod = form.querySelector('input[name="_method"]');
    if (oldMethod) oldMethod.remove();
    
    document.getElementById('itemModalTitle').innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>جاري التحميل...';
    document.getElementById('modal-content-area').innerHTML = '<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i><p class="mt-3 text-muted">جاري تحميل بيانات العنصر</p></div>';
    if (submitBtn) submitBtn.disabled = true;
    $('#itemModal').modal('show');
    
    fetch(itemUrl, {
        method: 'GET',
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin'
    })
    .then(function(r) {
        if (!r.ok) throw new Error('فشل تحميل العنصر');
        return r.json();
    })
    .then(function(data) {
        const type = data.item_type || 'text';
        const typeInfo = ITEM_TYPES[type] || { name: type, icon: 'fa-cube', color: '#333' };
        
        document.getElementById('itemModalTitle').innerHTML = 
            '<i class="fas ' + typeInfo.icon + ' mr-2"></i>تعديل ' + typeInfo.name;
        document.getElementById('modal_item_type').value = type;
        
        renderModalContent(type, {
            content: data.content || {},
            settings: data.settings || {},
            mediaUrl: data.media_url || '',
            youtubeUrl: data.youtube_url || ''
        });
        if (submitBtn) submitBtn.disabled = false;
    })
    .catch(function(err) {
        document.getElementById('itemModalTitle').innerHTML = '<i class="fas fa-exclamation-triangle text-warning mr-2"></i>خطأ';
        document.getElementById('modal-content-area').innerHTML = '<div class="alert alert-danger"><i class="fas fa-times-circle mr-2"></i>لم يتم تحميل العنصر. تحقق من الاتصال وحاول مرة أخرى.</div>';
        if (submitBtn) submitBtn.disabled = false;
    });
}

// ============================================
// Render Modal Content by Type
// ============================================
function renderModalContent(type, data) {
    const area = document.getElementById('modal-content-area');
    const content = data.content || {};
    const settings = data.settings || {};
    
    // Destroy existing TinyMCE
    if (typeof tinymce !== 'undefined') {
        tinymce.remove('#modal_rich_editor');
    }
    
    let html = '';
    
    switch(type) {
        case 'rich_text':
            html = `
                <div class="form-group">
                    <label class="font-weight-bold"><i class="fas fa-align-right text-primary mr-1"></i>المحتوى</label>
                    <textarea id="modal_rich_editor" name="content[html]" rows="15">${content.html || ''}</textarea>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold small">محاذاة النص</label>
                            <select name="settings[text_align]" class="form-control no-search">
                                <option value="right" ${(settings.text_align||'right')==='right'?'selected':''}>يمين</option>
                                <option value="center" ${settings.text_align==='center'?'selected':''}>وسط</option>
                                <option value="left" ${settings.text_align==='left'?'selected':''}>يسار</option>
                            </select>
                        </div>
                    </div>
                </div>
            `;
            break;
            
        case 'text':
            html = `
                <div class="form-group">
                    <label class="font-weight-bold">النص</label>
                    <textarea name="content[text]" class="form-control" rows="6">${content.text || ''}</textarea>
                </div>
            `;
            break;
            
        case 'image':
            html = `
                <div class="form-group">
                    <label class="font-weight-bold"><i class="fas fa-image text-success mr-1"></i>الصورة</label>
                    ${data.mediaUrl ? '<div class="mb-3"><img src="'+data.mediaUrl+'" class="image-preview"></div>' : ''}
                    <div class="upload-zone" onclick="this.querySelector('input').click()" id="imageUploadZone">
                        <input type="file" name="media" accept="image/*" style="display:none" onchange="previewUpload(this)">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>اسحب الصورة هنا أو انقر للاختيار</p>
                        <div id="uploadPreview"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold small">النص البديل</label>
                            <input type="text" name="content[alt]" class="form-control" value="${content.alt || ''}" placeholder="وصف الصورة">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold small">رابط عند النقر</label>
                            <input type="url" name="content[link]" class="form-control" value="${content.link || ''}" placeholder="https://...">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold small">تعليق / وصف</label>
                    <input type="text" name="content[caption]" class="form-control" value="${content.caption || ''}" placeholder="تعليق يظهر تحت الصورة">
                </div>
            `;
            break;
            
        case 'video':
            html = `
                <div class="form-group">
                    <label class="font-weight-bold"><i class="fas fa-play-circle text-danger mr-1"></i>رابط يوتيوب</label>
                    <input type="url" name="youtube_url" class="form-control" value="${data.youtubeUrl || ''}" placeholder="https://www.youtube.com/watch?v=...">
                    <small class="form-text text-muted">أو ارفع فيديو من جهازك</small>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">أو ارفع فيديو</label>
                    <input type="file" name="media" class="form-control-file" accept="video/*">
                </div>
                <div class="form-group">
                    <label class="font-weight-bold small">عنوان الفيديو</label>
                    <input type="text" name="content[title]" class="form-control" value="${content.title || ''}">
                </div>
            `;
            break;
            
        case 'table':
            const tableData = content.table_data || [['العنوان 1','العنوان 2','العنوان 3'],['','',''],['','','']];
            html = `
                <div class="form-group">
                    <label class="font-weight-bold"><i class="fas fa-table text-warning mr-1"></i>بيانات الجدول</label>
                    <div class="table-toolbar">
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="tableAddRow()"><i class="fas fa-plus mr-1"></i>صف</button>
                        <button type="button" class="btn btn-sm btn-outline-info" onclick="tableAddCol()"><i class="fas fa-plus mr-1"></i>عمود</button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="tableRemoveRow()"><i class="fas fa-minus mr-1"></i>صف</button>
                        <button type="button" class="btn btn-sm btn-outline-warning" onclick="tableRemoveCol()"><i class="fas fa-minus mr-1"></i>عمود</button>
                        <div class="custom-control custom-switch ml-auto">
                            <input type="checkbox" name="settings[has_header]" id="table_has_header" 
                                   class="custom-control-input" value="1" ${(settings.has_header !== false)?'checked':''}>
                            <label class="custom-control-label small" for="table_has_header">صف عناوين</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="settings[striped]" id="table_striped" 
                                   class="custom-control-input" value="1" ${settings.striped?'checked':''}>
                            <label class="custom-control-label small" for="table_striped">مخطط</label>
                        </div>
                    </div>
                    <div class="table-editor-container">
                        <table class="table-editor" id="tableEditor">
                            <tbody>
                                ${tableData.map((row, ri) => 
                                    '<tr>' + row.map((cell, ci) => 
                                        (ri === 0 ? '<th contenteditable="true">' + (cell||'') + '</th>' : '<td contenteditable="true">' + (cell||'') + '</td>')
                                    ).join('') + '</tr>'
                                ).join('')}
                            </tbody>
                        </table>
                    </div>
                    <input type="hidden" name="content[table_data]" id="tableDataInput">
                    <small class="form-text text-muted mt-2">
                        <i class="fas fa-info-circle mr-1"></i>انقر على أي خلية للتعديل. الصف الأول هو صف العناوين.
                    </small>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold small">عنوان الجدول</label>
                    <input type="text" name="content[title]" class="form-control" value="${content.title || ''}">
                </div>
            `;
            break;
            
        case 'card':
            html = `
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold"><i class="fas fa-heading text-info mr-1"></i>عنوان البطاقة</label>
                            <input type="text" name="content[title]" class="form-control" value="${content.title || ''}">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">الوصف</label>
                            <textarea name="content[description]" class="form-control" rows="4">${content.description || ''}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold small">الرابط</label>
                            <input type="url" name="content[link]" class="form-control" value="${content.link || ''}">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold small">نص الزر</label>
                            <input type="text" name="content[button_text]" class="form-control" value="${content.button_text || 'اقرأ المزيد'}">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold small">الأيقونة</label>
                            <input type="text" name="content[icon]" class="form-control" value="${content.icon || ''}" placeholder="fas fa-star">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">صورة البطاقة</label>
                            ${data.mediaUrl ? '<div class="mb-2"><img src="'+data.mediaUrl+'" class="image-preview"></div>' : ''}
                            <input type="file" name="media" class="form-control-file" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold small">لون البطاقة</label>
                            <select name="settings[card_color]" class="form-control no-search">
                                <option value="white" ${(settings.card_color||'white')==='white'?'selected':''}>أبيض</option>
                                <option value="primary" ${settings.card_color==='primary'?'selected':''}>أزرق</option>
                                <option value="success" ${settings.card_color==='success'?'selected':''}>أخضر</option>
                                <option value="info" ${settings.card_color==='info'?'selected':''}>سماوي</option>
                                <option value="warning" ${settings.card_color==='warning'?'selected':''}>أصفر</option>
                                <option value="danger" ${settings.card_color==='danger'?'selected':''}>أحمر</option>
                            </select>
                        </div>
                    </div>
                </div>
            `;
            break;
            
        case 'button':
            html = `
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">نص الزر</label>
                            <input type="text" name="content[text]" class="form-control" value="${content.text || ''}" placeholder="اضغط هنا">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">الرابط</label>
                            <input type="url" name="content[url]" class="form-control" value="${content.url || ''}" placeholder="https://...">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold small">الأيقونة</label>
                            <input type="text" name="content[icon]" class="form-control" value="${content.icon || ''}" placeholder="fas fa-arrow-left">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">لون الزر</label>
                            <select name="settings[btn_color]" class="form-control no-search">
                                <option value="primary" ${(settings.btn_color||'primary')==='primary'?'selected':''}>أزرق</option>
                                <option value="success" ${settings.btn_color==='success'?'selected':''}>أخضر</option>
                                <option value="info" ${settings.btn_color==='info'?'selected':''}>سماوي</option>
                                <option value="warning" ${settings.btn_color==='warning'?'selected':''}>أصفر</option>
                                <option value="danger" ${settings.btn_color==='danger'?'selected':''}>أحمر</option>
                                <option value="dark" ${settings.btn_color==='dark'?'selected':''}>أسود</option>
                                <option value="light" ${settings.btn_color==='light'?'selected':''}>فاتح</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold small">الحجم</label>
                            <select name="settings[btn_size]" class="form-control no-search">
                                <option value="md" ${(settings.btn_size||'md')==='md'?'selected':''}>متوسط</option>
                                <option value="sm" ${settings.btn_size==='sm'?'selected':''}>صغير</option>
                                <option value="lg" ${settings.btn_size==='lg'?'selected':''}>كبير</option>
                            </select>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="settings[btn_block]" id="btn_block" class="custom-control-input" value="1" ${settings.btn_block?'checked':''}>
                            <label class="custom-control-label" for="btn_block">عرض كامل</label>
                        </div>
                        <div class="custom-control custom-switch mt-2">
                            <input type="checkbox" name="settings[new_tab]" id="new_tab" class="custom-control-input" value="1" ${settings.new_tab?'checked':''}>
                            <label class="custom-control-label" for="new_tab">فتح في تبويب جديد</label>
                        </div>
                    </div>
                </div>
            `;
            break;
            
        case 'html':
            (function() {
                var rawHtml = content.html || '';
                var escaped = rawHtml.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/\\/g, '\\\\').replace(/`/g, '\\`').replace(/\$/g, '\\$');
                html = `
                <ul class="nav nav-tabs mb-3" id="htmlModalTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="html-code-tab" data-toggle="tab" href="#html-code-pane" role="tab"><i class="fas fa-code mr-1"></i>كود HTML</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="html-preview-tab" data-toggle="tab" href="#html-preview-pane" role="tab"><i class="fas fa-eye mr-1"></i>معاينة</a>
                    </li>
                </ul>
                <div class="tab-content" id="htmlModalTabContent">
                    <div class="tab-pane fade show active" id="html-code-pane" role="tabpanel">
                        <div class="form-group">
                            <label class="font-weight-bold"><i class="fas fa-code text-dark mr-1"></i>كود HTML</label>
                            <textarea name="content[html]" id="modal_html_content" class="form-control" rows="12" style="font-family: monospace; direction: ltr; text-align: left;">` + escaped + `</textarea>
                            <small class="form-text text-muted">يمكنك كتابة أي كود HTML مخصص هنا. استخدم تبويب المعاينة لرؤية النتيجة.</small>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="html-preview-pane" role="tabpanel">
                        <div class="form-group">
                            <label class="font-weight-bold"><i class="fas fa-eye text-info mr-1"></i>معاينة مباشرة</label>
                            <div id="htmlPreviewFrame" style="min-height: 280px; border: 1px solid #d1d9e6; border-radius: 10px; padding: 16px; background: #fff; direction: rtl; overflow: auto;"></div>
                            <small class="form-text text-muted"><i class="fas fa-info-circle mr-1"></i>يمكنك النقر على أي عنوان أو فقرة أو نص في المعاينة وتعديله مباشرة دون لمس الكود؛ التعديلات تُحفظ تلقائياً في الكود.</small>
                        </div>
                    </div>
                </div>
                `;
            })();
            break;
            
        case 'spacer':
            html = `
                <div class="form-group">
                    <label class="font-weight-bold"><i class="fas fa-arrows-alt-v text-muted mr-1"></i>ارتفاع الفاصل</label>
                    <div class="input-group">
                        <input type="number" name="settings[height]" class="form-control" value="${settings.height || 40}" min="10" max="300">
                        <div class="input-group-append"><span class="input-group-text">px</span></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="settings[show_line]" id="show_line" class="custom-control-input" value="1" ${settings.show_line?'checked':''}>
                        <label class="custom-control-label" for="show_line">إظهار خط فاصل</label>
                    </div>
                </div>
            `;
            break;
    }
    
    area.innerHTML = html;
    
    // Initialize TinyMCE for rich_text
    if (type === 'rich_text') {
        setTimeout(initTinyMCE, 200);
    }
    
    // Initialize live HTML preview
    if (type === 'html') {
        setTimeout(initHtmlPreview, 100);
    }
}

// ============================================
// TinyMCE Initialization
// ============================================
function initTinyMCE() {
    if (typeof tinymce === 'undefined') {
        console.warn('TinyMCE not loaded');
        return;
    }
    
    tinymce.init({
        selector: '#modal_rich_editor',
        directionality: 'rtl',
        height: 400,
        menubar: 'file edit view insert format table tools',
        plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount directionality',
        toolbar: 'undo redo | blocks | bold italic underline strikethrough forecolor backcolor | ' +
                 'alignright aligncenter alignleft alignjustify | ' +
                 'bullist numlist outdent indent | table | ' +
                 'link image media | removeformat | code fullscreen | ltr rtl',
        content_style: `
            @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap');
            body { font-family: 'Tajawal', sans-serif; direction: rtl; text-align: right; font-size: 15px; line-height: 1.8; padding: 12px; }
            table { border-collapse: collapse; width: 100%; margin: 12px 0; }
            table td, table th { border: 1px solid #ddd; padding: 8px 12px; }
            table th { background-color: #f0f2f8; font-weight: 700; }
            img { max-width: 100%; height: auto; border-radius: 8px; }
            h1,h2,h3,h4,h5,h6 { color: #2d3748; }
            a { color: #4e73df; }
            blockquote { border-right: 4px solid #4e73df; padding: 12px 20px; margin: 12px 0; background: #f8f9fc; border-radius: 0 8px 8px 0; }
        `,
        table_toolbar: 'tableprops tabledelete | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol',
        table_default_attributes: { border: '1' },
        table_default_styles: { 'border-collapse': 'collapse', 'width': '100%' },
        table_responsive_width: true,
        branding: false,
        promotion: false,
        relative_urls: false,
        remove_script_host: false,
        setup: function(editor) {
            editor.on('init', function() {
                editor.getBody().style.direction = 'rtl';
                editor.getBody().style.textAlign = 'right';
            });
        }
    });
}

// ============================================
// HTML Live Preview (safe: strip scripts) + تعديل من المعاينة
// ============================================
function initHtmlPreview() {
    var textarea = document.getElementById('modal_html_content');
    var frame = document.getElementById('htmlPreviewFrame');
    if (!textarea || !frame) return;
    
    var EDITABLE_SELECTOR = 'p, h1, h2, h3, h4, h5, h6, li, td, th, span, a, figcaption, label, blockquote';
    
    function stripScripts(html) {
        if (!html) return '';
        return html.replace(/<script\b[\s\S]*?<\/script>/gi, '');
    }
    
    function makePreviewEditable() {
        var nodes = frame.querySelectorAll(EDITABLE_SELECTOR);
        nodes.forEach(function(el) {
            el.setAttribute('contenteditable', 'true');
            el.classList.add('html-preview-editable');
        });
    }
    
    function updatePreview() {
        var raw = textarea.value || '';
        var safe = stripScripts(raw);
        frame.innerHTML = safe || '<span class="text-muted">لا يوجد محتوى للمعاينة</span>';
        makePreviewEditable();
    }
    
    function syncPreviewToTextarea() {
        textarea.value = frame.innerHTML;
    }
    
    var debounceTimer;
    function debouncedUpdate() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(updatePreview, 300);
    }
    
    var syncDebounceTimer;
    function debouncedSyncToTextarea() {
        clearTimeout(syncDebounceTimer);
        syncDebounceTimer = setTimeout(syncPreviewToTextarea, 400);
    }
    
    textarea.addEventListener('input', debouncedUpdate);
    textarea.addEventListener('keyup', debouncedUpdate);
    
    frame.addEventListener('input', debouncedSyncToTextarea);
    frame.addEventListener('blur', function() { syncPreviewToTextarea(); }, true);
    
    updatePreview();
}

// ============================================
// Table Editor Functions
// ============================================
function tableAddRow() {
    const table = document.getElementById('tableEditor');
    if (!table) return;
    const cols = table.rows[0] ? table.rows[0].cells.length : 3;
    const row = table.insertRow(-1);
    for (let i = 0; i < cols; i++) {
        const cell = row.insertCell(-1);
        cell.contentEditable = 'true';
        cell.textContent = '';
    }
}

function tableAddCol() {
    const table = document.getElementById('tableEditor');
    if (!table) return;
    for (let i = 0; i < table.rows.length; i++) {
        const cell = i === 0 ? 
            document.createElement('th') : 
            document.createElement('td');
        cell.contentEditable = 'true';
        cell.textContent = i === 0 ? 'عنوان' : '';
        table.rows[i].appendChild(cell);
    }
}

function tableRemoveRow() {
    const table = document.getElementById('tableEditor');
    if (!table || table.rows.length <= 1) return;
    table.deleteRow(-1);
}

function tableRemoveCol() {
    const table = document.getElementById('tableEditor');
    if (!table) return;
    const cols = table.rows[0] ? table.rows[0].cells.length : 0;
    if (cols <= 1) return;
    for (let i = 0; i < table.rows.length; i++) {
        table.rows[i].deleteCell(-1);
    }
}

function getTableData() {
    const table = document.getElementById('tableEditor');
    if (!table) return null;
    const data = [];
    for (let i = 0; i < table.rows.length; i++) {
        const row = [];
        for (let j = 0; j < table.rows[i].cells.length; j++) {
            row.push(table.rows[i].cells[j].textContent.trim());
        }
        data.push(row);
    }
    return data;
}

// ============================================
// Form Submit Handler
// ============================================
document.getElementById('itemForm').addEventListener('submit', function(e) {
    const type = document.getElementById('modal_item_type').value;
    
    // For rich_text, sync TinyMCE content
    if (type === 'rich_text' && typeof tinymce !== 'undefined') {
        const editor = tinymce.get('modal_rich_editor');
        if (editor) {
            editor.save();
        }
    }
    
    // For table, serialize data
    if (type === 'table') {
        const tableDataInput = document.getElementById('tableDataInput');
        if (tableDataInput) {
            tableDataInput.value = JSON.stringify(getTableData());
        }
    }
});

// ============================================
// Image Upload Preview
// ============================================
function previewUpload(input) {
    const preview = document.getElementById('uploadPreview');
    if (!preview) return;
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" class="image-preview mt-2">';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// ============================================
// Delete Item
// ============================================
function deleteItem(itemId) {
    if (!confirm('هل أنت متأكد من حذف هذا العنصر؟')) return;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/dashboard/home-sections/' + SECTION_ID + '/items/' + itemId;
    form.innerHTML = '<input type="hidden" name="_token" value="' + CSRF_TOKEN + '">' +
                     '<input type="hidden" name="_method" value="DELETE">';
    document.body.appendChild(form);
    form.submit();
}

// ============================================
// Toggle Item Collapse
// ============================================
function toggleItem(el) {
    const card = el.closest('.item-card');
    card.classList.toggle('collapsed-item');
}

// ============================================
// Preview
// ============================================
function togglePreview() {
    const previewContent = document.getElementById('previewContent');
    previewContent.innerHTML = '<div class="text-center p-5"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i><p class="mt-3 text-muted">جاري تحميل المعاينة...</p></div>';
    $('#previewModal').modal('show');
    
    // Simple preview - render items
    setTimeout(function() {
        let html = '<div style="padding: 40px; direction: rtl; font-family: Tajawal, sans-serif;">';
        document.querySelectorAll('#items-list .item-card[data-id]').forEach(function(card) {
            const type = card.dataset.type;
            html += '<div style="margin-bottom: 20px; padding: 15px; border: 1px solid #eee; border-radius: 8px;">';
            html += '<small style="color: #999; display: block; margin-bottom: 8px;">[' + (ITEM_TYPES[type]?.name || type) + ']</small>';
            
            const bodyEl = card.querySelector('.item-body');
            if (bodyEl) {
                const previewEl = bodyEl.querySelector('.item-preview-content');
                if (previewEl) {
                    html += previewEl.innerHTML;
                }
            }
            html += '</div>';
        });
        html += '</div>';
        previewContent.innerHTML = html || '<div class="text-center p-5"><p class="text-muted">لا يوجد محتوى للمعاينة</p></div>';
    }, 500);
}

// ============================================
// Toast Notification
// ============================================
function showToast(message) {
    const toast = document.createElement('div');
    toast.style.cssText = 'position:fixed;bottom:20px;left:20px;background:#1cc88a;color:#fff;padding:12px 24px;border-radius:10px;z-index:9999;font-weight:600;box-shadow:0 4px 12px rgba(0,0,0,0.15);animation:fadeIn 0.3s ease;';
    toast.innerHTML = '<i class="fas fa-check-circle mr-2"></i>' + message;
    document.body.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 2000);
}

// ============================================
// Sync Color Pickers
// ============================================
document.querySelectorAll('.color-input-group').forEach(function(group) {
    const colorInput = group.querySelector('input[type="color"]');
    const textInput = group.querySelector('input[type="text"]');
    if (colorInput && textInput) {
        colorInput.addEventListener('input', () => textInput.value = colorInput.value);
        textInput.addEventListener('input', () => colorInput.value = textInput.value);
    }
});
</script>
@endpush
