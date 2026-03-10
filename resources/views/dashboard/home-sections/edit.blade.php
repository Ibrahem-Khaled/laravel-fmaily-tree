@extends('layouts.app')

@section('title', 'بناء قسم: ' . $homeSection->title)

@push('styles')
<style>
    /* ============================================ */
    /* Page Background                               */
    /* ============================================ */
    .content-wrapper, #content { background: #f0f2f8; }

    /* ============================================ */
    /* Hero Header                                    */
    /* ============================================ */
    .builder-hero {
        background: linear-gradient(135deg, #4e54c8 0%, #8f94fb 50%, #667eea 100%);
        border-radius: 20px;
        padding: 28px 32px;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 28px;
    }
    .builder-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
        border-radius: 50%;
    }
    .builder-hero::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: 10%;
        width: 250px;
        height: 250px;
        background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
        border-radius: 50%;
    }
    .builder-hero .hero-content { position: relative; z-index: 1; }
    .hero-title { font-size: 1.5rem; font-weight: 800; margin-bottom: 6px; }
    .hero-meta { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 14px;
        border-radius: 50px;
        font-size: 0.78rem;
        font-weight: 700;
        backdrop-filter: blur(10px);
    }
    .hero-badge-type { background: rgba(255,255,255,0.2); color: #fff; }
    .hero-badge-active { background: rgba(28,200,138,0.3); color: #a7f3d0; }
    .hero-badge-inactive { background: rgba(255,255,255,0.15); color: rgba(255,255,255,0.7); }
    .hero-badge-count { background: rgba(255,255,255,0.15); color: rgba(255,255,255,0.9); }
    .hero-actions { display: flex; gap: 10px; }
    .hero-actions .btn {
        border-radius: 12px;
        font-weight: 700;
        padding: 9px 20px;
        font-size: 0.85rem;
        border: 2px solid rgba(255,255,255,0.3);
        transition: all 0.3s ease;
    }
    .hero-actions .btn-hero-light { background: rgba(255,255,255,0.15); color: #fff; backdrop-filter: blur(10px); }
    .hero-actions .btn-hero-light:hover { background: rgba(255,255,255,0.3); color: #fff; transform: translateY(-2px); }
    .hero-actions .btn-hero-primary { background: #fff; color: #4e54c8; border-color: #fff; }
    .hero-actions .btn-hero-primary:hover { background: #f0f2f8; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.15); }

    /* ============================================ */
    /* Builder Layout                                */
    /* ============================================ */
    .builder-container { display: flex; gap: 24px; min-height: 70vh; }
    .builder-sidebar { width: 360px; flex-shrink: 0; }
    .builder-main { flex: 1; min-width: 0; }

    @media (max-width: 1200px) {
        .builder-sidebar { width: 320px; }
    }
    @media (max-width: 992px) {
        .builder-container { flex-direction: column; }
        .builder-sidebar { width: 100%; }
    }

    /* ============================================ */
    /* Sidebar Tabs                                   */
    /* ============================================ */
    .sidebar-wrapper {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 4px 25px rgba(0,0,0,0.06);
        overflow: hidden;
        position: sticky;
        top: 80px;
    }
    .sidebar-tabs {
        display: flex;
        background: #f8f9fc;
        border-bottom: 2px solid #e8ecf1;
        padding: 0;
        margin: 0;
        list-style: none;
    }
    .sidebar-tabs li { flex: 1; }
    .sidebar-tabs li a {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 14px 8px 12px;
        color: #8a94a7;
        font-weight: 700;
        font-size: 0.72rem;
        text-decoration: none;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
        gap: 5px;
    }
    .sidebar-tabs li a i { font-size: 1.05rem; }
    .sidebar-tabs li a:hover { color: #4e54c8; background: rgba(78,84,200,0.04); }
    .sidebar-tabs li a.active {
        color: #4e54c8;
        border-bottom-color: #4e54c8;
        background: #fff;
    }
    .sidebar-tab-content { padding: 20px; }
    .sidebar-tab-pane { display: none; }
    .sidebar-tab-pane.active { display: block; }

    /* ============================================ */
    /* Sidebar Form Styling                          */
    /* ============================================ */
    .setting-group {
        margin-bottom: 20px;
        padding-bottom: 18px;
        border-bottom: 1px solid #f0f2f5;
    }
    .setting-group:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .setting-group-title {
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #a0aec0;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .setting-group-title i { font-size: 0.65rem; }
    .s-label {
        font-weight: 600;
        font-size: 0.82rem;
        color: #4a5568;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .s-label .label-icon {
        width: 22px;
        height: 22px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
    }
    .s-input {
        border: 2px solid #e8ecf1;
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 0.85rem;
        transition: all 0.2s ease;
        background: #fafbfd;
    }
    .s-input:focus {
        border-color: #4e54c8;
        box-shadow: 0 0 0 3px rgba(78,84,200,0.1);
        background: #fff;
    }
    select.s-input { padding-right: 32px; }
    .s-hint {
        font-size: 0.72rem;
        color: #a0aec0;
        margin-top: 4px;
    }

    /* Toggle Switches */
    .toggle-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 14px;
        background: #fafbfd;
        border-radius: 10px;
        margin-bottom: 8px;
        border: 1px solid #edf0f5;
    }
    .toggle-row:last-child { margin-bottom: 0; }
    .toggle-row .toggle-label {
        font-size: 0.82rem;
        font-weight: 600;
        color: #4a5568;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .toggle-row .toggle-label i { color: #8a94a7; font-size: 0.85rem; }

    /* Color Pickers */
    .color-pair {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        background: #fafbfd;
        border-radius: 10px;
        border: 2px solid #e8ecf1;
    }
    .color-pair input[type="color"] {
        width: 34px;
        height: 34px;
        border: 2px solid #e3e6f0;
        border-radius: 8px;
        padding: 2px;
        cursor: pointer;
        flex-shrink: 0;
    }
    .color-pair input[type="text"] {
        border: none;
        background: transparent;
        font-size: 0.85rem;
        font-weight: 600;
        color: #4a5568;
        font-family: 'Courier New', monospace;
        direction: ltr;
        text-align: left;
        width: 90px;
    }
    .color-pair input[type="text"]:focus { outline: none; }

    /* Number Pair */
    .number-pair {
        display: flex;
        gap: 10px;
    }
    .number-pair .number-field {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 6px;
        background: #fafbfd;
        border: 2px solid #e8ecf1;
        border-radius: 10px;
        padding: 6px 10px;
    }
    .number-pair .number-field input {
        border: none;
        background: transparent;
        width: 50px;
        text-align: center;
        font-weight: 600;
        font-size: 0.85rem;
    }
    .number-pair .number-field input:focus { outline: none; }
    .number-pair .number-field .unit {
        font-size: 0.72rem;
        color: #a0aec0;
        font-weight: 600;
    }
    .number-pair .number-field .field-label {
        font-size: 0.72rem;
        color: #8a94a7;
        white-space: nowrap;
    }

    /* Source Mode Cards */
    .source-mode-cards {
        display: flex;
        gap: 8px;
        margin-bottom: 14px;
    }
    .source-mode-card {
        flex: 1;
        padding: 12px 10px;
        border-radius: 12px;
        border: 2px solid #e8ecf1;
        background: #fafbfd;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .source-mode-card:hover { border-color: #b8bfea; }
    .source-mode-card.active-mode {
        border-color: #4e54c8;
        background: linear-gradient(135deg, #f0f0ff 0%, #e8e8ff 100%);
        box-shadow: 0 2px 8px rgba(78,84,200,0.15);
    }
    .source-mode-card i { font-size: 1.2rem; margin-bottom: 4px; display: block; }
    .source-mode-card .mode-name { font-size: 0.78rem; font-weight: 700; }
    .source-mode-card.active-mode i { color: #4e54c8; }

    /* ============================================ */
    /* Add Item Toolbar                              */
    /* ============================================ */
    .add-toolbar-wrapper {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 25px rgba(0,0,0,0.06);
        padding: 20px;
        margin-bottom: 24px;
    }
    .add-toolbar-title {
        font-size: 0.78rem;
        font-weight: 700;
        color: #a0aec0;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .add-toolbar-title i { color: #4e54c8; }
    .add-toolbar-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
        gap: 10px;
    }
    .add-item-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 14px 8px;
        border-radius: 14px;
        border: 2px solid #edf0f5;
        background: #fafbfd;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        color: inherit;
        gap: 6px;
    }
    .add-item-btn:hover {
        border-color: #4e54c8;
        background: #f0f0ff;
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(78,84,200,0.15);
        text-decoration: none;
        color: inherit;
    }
    .add-item-btn .btn-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        transition: transform 0.3s ease;
    }
    .add-item-btn:hover .btn-icon { transform: scale(1.15); }
    .add-item-btn span { font-size: 0.72rem; font-weight: 700; color: #4a5568; }

    /* ============================================ */
    /* Items Builder                                  */
    /* ============================================ */
    .items-wrapper {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 25px rgba(0,0,0,0.06);
        padding: 20px;
        min-height: 200px;
    }
    .items-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        padding-bottom: 14px;
        border-bottom: 2px solid #f0f2f5;
    }
    .items-header-title {
        font-size: 0.95rem;
        font-weight: 800;
        color: #2d3748;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .items-header-title .count-badge {
        background: #4e54c8;
        color: #fff;
        font-size: 0.7rem;
        padding: 2px 10px;
        border-radius: 50px;
    }
    .item-card {
        border: 2px solid #edf0f5;
        border-radius: 14px;
        margin-bottom: 12px;
        background: #fff;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .item-card:hover {
        border-color: #c5cae8;
        box-shadow: 0 4px 18px rgba(78,84,200,0.1);
    }
    .item-card.collapsed-item .item-body { display: none; }
    .item-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        background: #fafbfd;
        cursor: pointer;
        border-bottom: 1px solid #edf0f5;
    }
    .item-header-left { display: flex; align-items: center; gap: 12px; flex: 1; }
    .item-drag-handle {
        color: #c5cae8;
        cursor: grab;
        font-size: 1rem;
        transition: color 0.2s;
    }
    .item-drag-handle:hover { color: #4e54c8; }
    .item-drag-handle:active { cursor: grabbing; }
    .item-type-badge {
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 0.72rem;
        font-weight: 700;
        white-space: nowrap;
    }
    .item-header-actions { display: flex; gap: 4px; }
    .item-header-actions .btn {
        padding: 5px 10px;
        font-size: 0.78rem;
        border-radius: 8px;
        border: none;
    }
    .item-body { padding: 16px; }

    /* Empty State */
    .empty-items-state {
        text-align: center;
        padding: 50px 20px;
    }
    .empty-state-icon {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        background: linear-gradient(135deg, #f0f0ff 0%, #e8e8ff 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }
    .empty-state-icon i { font-size: 2rem; color: #4e54c8; opacity: 0.6; }
    .empty-items-state h5 { color: #4a5568; font-weight: 700; margin-bottom: 6px; }
    .empty-items-state p { color: #a0aec0; font-size: 0.85rem; }

    /* ============================================ */
    /* Table Editor                                   */
    /* ============================================ */
    .table-editor-container { overflow-x: auto; }
    .table-editor { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
    .table-editor th, .table-editor td {
        border: 1px solid #d1d9e6;
        padding: 6px 8px;
        min-width: 80px;
        position: relative;
    }
    .table-editor th { background: #f0f2f8; font-weight: 700; text-align: center; }
    .table-editor td[contenteditable] { outline: none; cursor: text; }
    .table-editor td[contenteditable]:focus {
        background: #eef2ff;
        box-shadow: inset 0 0 0 2px #4e54c8;
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
        border: 2px dashed #d1d9e6;
        border-radius: 14px;
        padding: 28px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #fafbfd;
    }
    .upload-zone:hover, .upload-zone.dragover { border-color: #4e54c8; background: #f0f0ff; }
    .upload-zone i { font-size: 2rem; color: #b8bfea; }
    .upload-zone p { margin: 8px 0 0; color: #718096; font-size: 0.85rem; }
    .image-preview {
        max-width: 100%;
        max-height: 200px;
        border-radius: 10px;
        object-fit: cover;
        margin-top: 8px;
    }

    /* ============================================ */
    /* TinyMCE Override                               */
    /* ============================================ */
    .tox-tinymce { border-radius: 12px !important; border: 2px solid #e8ecf1 !important; }

    /* HTML Preview */
    #htmlPreviewFrame .html-preview-editable {
        outline: 1px dashed transparent;
        border-radius: 4px;
        transition: outline-color 0.15s, background-color 0.15s;
    }
    #htmlPreviewFrame .html-preview-editable:hover {
        outline-color: #4e54c8;
        background-color: rgba(78, 84, 200, 0.06);
    }
    #htmlPreviewFrame .html-preview-editable:focus {
        outline: 2px solid #4e54c8;
        outline-offset: 2px;
        background-color: rgba(78, 84, 200, 0.08);
    }

    /* ============================================ */
    /* Save Button Floating                          */
    /* ============================================ */
    .save-float {
        position: fixed;
        bottom: 24px;
        left: 24px;
        z-index: 1050;
    }
    .save-float .btn {
        border-radius: 14px;
        padding: 12px 28px;
        font-weight: 800;
        font-size: 0.9rem;
        box-shadow: 0 8px 30px rgba(78,84,200,0.4);
        transition: all 0.3s ease;
    }
    .save-float .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(78,84,200,0.5);
    }

    /* ============================================ */
    /* Modal Overrides                                */
    /* ============================================ */
    .modal-content { border-radius: 20px !important; overflow: hidden; border: none; }
    .custom-switch .custom-control-label::before { border-radius: 20px; }

    /* ============================================ */
    /* Animations                                     */
    /* ============================================ */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-in { animation: fadeInUp 0.4s ease forwards; }
    .animate-delay-1 { animation-delay: 0.1s; opacity: 0; }
    .animate-delay-2 { animation-delay: 0.2s; opacity: 0; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    @php
        $ss = $homeSection->settings ?? [];
        $sectionTypes = [
            'rich_text'=>'نص غني','gallery'=>'معرض صور','cards'=>'بطاقات','table'=>'جدول',
            'text_with_image'=>'نص مع صورة','video_section'=>'فيديو','hero'=>'بانر رئيسي',
            'buttons'=>'أزرار','stats'=>'إحصائيات','divider'=>'فاصل','custom'=>'HTML مخصص','mixed'=>'مختلط'
        ];
    @endphp

    <!-- ============================================ -->
    <!-- Hero Header                                    -->
    <!-- ============================================ -->
    <div class="builder-hero animate-in">
        <div class="hero-content d-flex align-items-center justify-content-between flex-wrap" style="gap: 16px;">
            <div>
                <div class="d-flex align-items-center mb-2" style="gap: 10px;">
                    <a href="{{ route('dashboard.home-sections.index') }}"
                       class="btn btn-sm btn-hero-light" style="padding: 6px 12px; border-radius: 10px;">
                        <i class="fas fa-arrow-right"></i>
                    </a>
                    <h1 class="hero-title mb-0">{{ $homeSection->title }}</h1>
                </div>
                <div class="hero-meta">
                    <span class="hero-badge hero-badge-type">
                        <i class="fas fa-layer-group"></i>{{ $sectionTypes[$homeSection->section_type] ?? $homeSection->section_type }}
                    </span>
                    <span class="hero-badge {{ $homeSection->is_active ? 'hero-badge-active' : 'hero-badge-inactive' }}">
                        <i class="fas {{ $homeSection->is_active ? 'fa-check-circle' : 'fa-pause-circle' }}"></i>
                        {{ $homeSection->is_active ? 'نشط' : 'معطل' }}
                    </span>
                    <span class="hero-badge hero-badge-count">
                        <i class="fas fa-cubes"></i>{{ $homeSection->items->count() }} عنصر
                    </span>
                </div>
            </div>
            <div class="hero-actions">
                <button type="button" class="btn btn-hero-light" onclick="togglePreview()">
                    <i class="fas fa-eye mr-1"></i>معاينة
                </button>
                <button type="submit" form="sectionSettingsForm" class="btn btn-hero-primary">
                    <i class="fas fa-save mr-1"></i>حفظ الإعدادات
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert" style="border-radius: 14px; border: none; background: linear-gradient(135deg, #d4edda, #c3e6cb);">
            <i class="fas fa-check-circle mr-2 text-success"></i><strong>{{ session('success') }}</strong>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert" style="border-radius: 14px; border: none;">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <!-- ============================================ -->
    <!-- Builder Container                              -->
    <!-- ============================================ -->
    <div class="builder-container">
        <!-- ========== Sidebar ========== -->
        <div class="builder-sidebar animate-in animate-delay-1">
            <div class="sidebar-wrapper">
                <ul class="sidebar-tabs" id="sidebarTabs">
                    <li><a href="#" class="active" data-tab="tab-basic"><i class="fas fa-sliders-h"></i>أساسي</a></li>
                    <li><a href="#" data-tab="tab-source"><i class="fas fa-database"></i>المصدر</a></li>
                    <li><a href="#" data-tab="tab-design"><i class="fas fa-palette"></i>التصميم</a></li>
                </ul>

                <form id="sectionSettingsForm" action="{{ route('dashboard.home-sections.update', $homeSection) }}" method="POST">
                    @csrf
                    <div class="sidebar-tab-content">

                        <!-- ===== Tab: Basic ===== -->
                        <div class="sidebar-tab-pane active" id="tab-basic">
                            <div class="setting-group">
                                <div class="setting-group-title"><i class="fas fa-circle"></i>معلومات القسم</div>

                                <div class="form-group mb-3">
                                    <label class="s-label">
                                        <span class="label-icon" style="background:#eef0ff;color:#4e54c8;"><i class="fas fa-heading"></i></span>
                                        العنوان <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="title" class="form-control s-input"
                                           value="{{ old('title', $homeSection->title) }}" required>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="s-label">
                                        <span class="label-icon" style="background:#e8f8f0;color:#1cc88a;"><i class="fas fa-font"></i></span>
                                        العنوان الفرعي
                                    </label>
                                    <input type="text" name="settings[subtitle]" class="form-control s-input"
                                           value="{{ old('settings.subtitle', $ss['subtitle'] ?? '') }}"
                                           placeholder="يظهر تحت العنوان الرئيسي">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="s-label">
                                        <span class="label-icon" style="background:#fff5e6;color:#f6c23e;"><i class="fas fa-align-right"></i></span>
                                        الوصف
                                    </label>
                                    <textarea name="settings[description]" class="form-control s-input" rows="2"
                                              placeholder="وصف تفصيلي (اختياري)">{{ old('settings.description', $ss['description'] ?? '') }}</textarea>
                                </div>
                            </div>

                            <div class="setting-group">
                                <div class="setting-group-title"><i class="fas fa-circle"></i>النوع والأيقونة</div>

                                <div class="form-group mb-3">
                                    <label class="s-label">
                                        <span class="label-icon" style="background:#f0e8ff;color:#6f42c1;"><i class="fas fa-shapes"></i></span>
                                        نوع القسم
                                    </label>
                                    <select name="section_type" class="form-control s-input no-search">
                                        @foreach($sectionTypes as $val => $label)
                                            <option value="{{ $val }}" {{ $homeSection->section_type == $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mb-0">
                                    <label class="s-label">
                                        <span class="label-icon" style="background:#fff5e6;color:#f6c23e;"><i class="fas fa-icons"></i></span>
                                        الأيقونة
                                    </label>
                                    <input type="text" name="settings[icon]" class="form-control s-input"
                                           value="{{ old('settings.icon', $ss['icon'] ?? '') }}"
                                           placeholder="fas fa-star" style="direction:ltr;text-align:left;">
                                </div>
                            </div>

                            <div class="setting-group">
                                <div class="setting-group-title"><i class="fas fa-circle"></i>الحالة</div>
                                <div class="toggle-row">
                                    <span class="toggle-label"><i class="fas fa-eye"></i>تفعيل القسم</span>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" name="is_active" id="edit_is_active"
                                               class="custom-control-input" value="1"
                                               {{ old('is_active', $homeSection->is_active) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="edit_is_active"></label>
                                    </div>
                                </div>
                                <div class="toggle-row">
                                    <span class="toggle-label"><i class="fas fa-heading"></i>إظهار العنوان</span>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" name="settings[show_title]" id="edit_show_title"
                                               class="custom-control-input" value="1"
                                               {{ old('settings.show_title', $ss['show_title'] ?? true) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="edit_show_title"></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ===== Tab: Content Source ===== -->
                        <div class="sidebar-tab-pane" id="tab-source">
                            <div class="setting-group">
                                <div class="setting-group-title"><i class="fas fa-circle"></i>مصدر المحتوى</div>

                                <div class="form-group mb-3">
                                    <label class="s-label">
                                        <span class="label-icon" style="background:#fee;color:#e74a3b;"><i class="fas fa-database"></i></span>
                                        نوع المصدر
                                    </label>
                                    <select name="content_source_type" id="content_source_type" class="form-control s-input no-search" onchange="toggleSourceOptions()">
                                        <option value="">بدون (عناصر يدوية)</option>
                                        @foreach(\App\Models\HomeSection::ALLOWED_SOURCES as $class => $label)
                                            <option value="{{ $class }}" {{ $homeSection->content_source_type === $class ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <p class="s-hint">اختر مصدراً لعرض قائمة تلقائية بدلاً من العناصر اليدوية</p>
                                </div>
                            </div>

                            <div id="sourceOptionsGroup" style="{{ $homeSection->content_source_type ? '' : 'display:none;' }}">
                                <div class="setting-group">
                                    <div class="setting-group-title"><i class="fas fa-circle"></i>طريقة الاختيار</div>

                                    <input type="hidden" name="settings[source_mode]" id="source_mode_hidden" value="{{ $ss['source_mode'] ?? 'all' }}">

                                    <div class="source-mode-cards">
                                        <div class="source-mode-card {{ ($ss['source_mode'] ?? 'all') === 'all' ? 'active-mode' : '' }}"
                                             onclick="setSourceMode('all')">
                                            <i class="fas fa-globe"></i>
                                            <div class="mode-name">الكل</div>
                                        </div>
                                        <div class="source-mode-card {{ ($ss['source_mode'] ?? 'all') === 'selected' ? 'active-mode' : '' }}"
                                             onclick="setSourceMode('selected')">
                                            <i class="fas fa-hand-pointer"></i>
                                            <div class="mode-name">اختيار محدد</div>
                                        </div>
                                    </div>

                                    <!-- Mode: All -->
                                    <div id="sourceModeAll" style="{{ ($ss['source_mode'] ?? 'all') === 'selected' ? 'display:none;' : '' }}">
                                        <div class="form-group mb-3">
                                            <label class="s-label">
                                                <span class="label-icon" style="background:#e8f8f0;color:#1cc88a;"><i class="fas fa-sort-numeric-down"></i></span>
                                                حد العدد
                                            </label>
                                            <input type="number" name="settings[source_limit]" class="form-control s-input"
                                                   value="{{ old('settings.source_limit', $ss['source_limit'] ?? 10) }}" min="1" max="100">
                                        </div>
                                        <div class="form-group mb-0">
                                            <label class="s-label">
                                                <span class="label-icon" style="background:#eef0ff;color:#4e54c8;"><i class="fas fa-sort"></i></span>
                                                الترتيب
                                            </label>
                                            <select name="settings[source_order]" class="form-control s-input no-search">
                                                @foreach(['latest'=>'الأحدث أولاً','oldest'=>'الأقدم أولاً','name_asc'=>'الاسم (أ-ي)','name_desc'=>'الاسم (ي-أ)'] as $v => $l)
                                                    <option value="{{ $v }}" {{ ($ss['source_order'] ?? 'latest') === $v ? 'selected' : '' }}>{{ $l }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Mode: Selected -->
                                    <div id="sourceModeSelected" style="{{ ($ss['source_mode'] ?? 'all') === 'selected' ? '' : 'display:none;' }}">
                                        <div class="form-group mb-0">
                                            <label class="s-label">
                                                <span class="label-icon" style="background:#f0e8ff;color:#6f42c1;"><i class="fas fa-user-check"></i></span>
                                                اختر العناصر
                                            </label>
                                            <select name="settings[source_ids][]" id="source_ids_select" class="form-control no-search" multiple="multiple">
                                                @if(($ss['source_mode'] ?? 'all') === 'selected' && !empty($ss['source_ids']))
                                                    @php
                                                        $selectedIds = $ss['source_ids'];
                                                        $sourceClass = $homeSection->content_source_type;
                                                        $selectedEntities = $sourceClass ? $sourceClass::whereIn('id', $selectedIds)->get() : collect();
                                                    @endphp
                                                    @foreach($selectedEntities as $ent)
                                                        @if($homeSection->content_source_type === 'App\\Models\\Person')
                                                            <option value="{{ $ent->id }}" selected>{{ $ent->full_name }}</option>
                                                        @else
                                                            <option value="{{ $ent->id }}" selected>{{ $ent->name }}{{ $ent->email ? " ({$ent->email})" : '' }}</option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                            <p class="s-hint">ابحث بالاسم واختر العناصر المراد عرضها</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="noSourceMessage" style="{{ $homeSection->content_source_type ? 'display:none;' : '' }}">
                                <div class="text-center py-4">
                                    <div style="width:60px;height:60px;border-radius:16px;background:#f8f9fc;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                                        <i class="fas fa-hand-pointer" style="font-size:1.4rem;color:#c5cae8;"></i>
                                    </div>
                                    <p class="text-muted small mb-0">اختر مصدراً أعلاه لعرض<br>محتوى تلقائي في هذا القسم</p>
                                </div>
                            </div>
                        </div>

                        <!-- ===== Tab: Design ===== -->
                        <div class="sidebar-tab-pane" id="tab-design">

                            {{-- Typography Settings --}}
                            <div class="setting-group">
                                <div class="setting-group-title"><i class="fas fa-text-height"></i>الخطوط والنصوص</div>
                                @include('dashboard.partials.section-typography-settings', [
                                    'ss'       => $ss,
                                    'idPrefix' => 'edit',
                                    'compact'  => true,
                                ])
                            </div>

                            <div class="setting-group">
                                <div class="setting-group-title"><i class="fas fa-circle"></i>الألوان</div>

                                <div class="form-group mb-3">
                                    <label class="s-label mb-2">لون الخلفية</label>
                                    <div class="color-pair">
                                        <input type="color" name="settings[background_color]" id="bg_color"
                                               value="{{ old('settings.background_color', $ss['background_color'] ?? '#ffffff') }}">
                                        <input type="text" id="bg_color_text"
                                               value="{{ old('settings.background_color', $ss['background_color'] ?? '#ffffff') }}">
                                        <span style="flex:1;text-align:left;font-size:0.72rem;color:#a0aec0;">خلفية</span>
                                    </div>
                                </div>

                                <div class="form-group mb-0">
                                    <label class="s-label mb-2">لون النص</label>
                                    <div class="color-pair">
                                        <input type="color" name="settings[text_color]" id="text_color"
                                               value="{{ old('settings.text_color', $ss['text_color'] ?? '#333333') }}">
                                        <input type="text" id="text_color_text"
                                               value="{{ old('settings.text_color', $ss['text_color'] ?? '#333333') }}">
                                        <span style="flex:1;text-align:left;font-size:0.72rem;color:#a0aec0;">نص</span>
                                    </div>
                                </div>
                            </div>

                            <div class="setting-group">
                                <div class="setting-group-title"><i class="fas fa-circle"></i>المسافات</div>
                                <div class="number-pair">
                                    <div class="number-field">
                                        <span class="field-label"><i class="fas fa-arrow-up"></i></span>
                                        <input type="number" name="settings[padding_top]"
                                               value="{{ old('settings.padding_top', $ss['padding_top'] ?? 40) }}" min="0" max="200">
                                        <span class="unit">px</span>
                                    </div>
                                    <div class="number-field">
                                        <span class="field-label"><i class="fas fa-arrow-down"></i></span>
                                        <input type="number" name="settings[padding_bottom]"
                                               value="{{ old('settings.padding_bottom', $ss['padding_bottom'] ?? 40) }}" min="0" max="200">
                                        <span class="unit">px</span>
                                    </div>
                                </div>
                            </div>

                            <div class="setting-group">
                                <div class="setting-group-title"><i class="fas fa-circle"></i>التخطيط</div>

                                <div class="form-group mb-3">
                                    <label class="s-label">
                                        <span class="label-icon" style="background:#eef0ff;color:#4e54c8;"><i class="fas fa-th-list"></i></span>
                                        نمط العرض
                                    </label>
                                    <select name="settings[layout_style]" class="form-control s-input no-search">
                                        @foreach(['grid'=>'شبكة','horizontal'=>'أفقي (بالعرض)','vertical'=>'عمودي (بالطول)'] as $v => $l)
                                            <option value="{{ $v }}" {{ ($ss['layout_style'] ?? 'grid') === $v ? 'selected' : '' }}>{{ $l }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mb-0">
                                    <label class="s-label">
                                        <span class="label-icon" style="background:#e8f8f0;color:#1cc88a;"><i class="fas fa-columns"></i></span>
                                        عدد الأعمدة
                                    </label>
                                    <select name="settings[columns]" class="form-control s-input no-search">
                                        @foreach([1=>'1 عمود',2=>'2 أعمدة',3=>'3 أعمدة',4=>'4 أعمدة',6=>'6 أعمدة'] as $v => $l)
                                            <option value="{{ $v }}" {{ ($ss['columns'] ?? 3) == $v ? 'selected' : '' }}>{{ $l }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="setting-group">
                                <div class="setting-group-title"><i class="fas fa-circle"></i>متقدم</div>
                                <div class="form-group mb-0">
                                    <label class="s-label">
                                        <span class="label-icon" style="background:#f5f5f5;color:#5a5c69;"><i class="fas fa-code"></i></span>
                                        CSS مخصص
                                    </label>
                                    <input type="text" name="css_classes" class="form-control s-input"
                                           value="{{ old('css_classes', $homeSection->css_classes) }}"
                                           placeholder="أضف فئات CSS إضافية" style="direction:ltr;text-align:left;">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- ========== Main: Content Builder ========== -->
        <div class="builder-main animate-in animate-delay-2">
            <!-- Add Item Toolbar -->
            <div class="add-toolbar-wrapper">
                <div class="add-toolbar-title">
                    <i class="fas fa-plus-circle"></i>إضافة عنصر جديد
                </div>
                <div class="add-toolbar-grid">
                    <a href="javascript:void(0)" class="add-item-btn" onclick="openAddItemModal('rich_text')">
                        <div class="btn-icon" style="background:#eef0ff;color:#4e54c8;"><i class="fas fa-align-right"></i></div>
                        <span>نص غني</span>
                    </a>
                    <a href="javascript:void(0)" class="add-item-btn" onclick="openAddItemModal('image')">
                        <div class="btn-icon" style="background:#e8f8f0;color:#1cc88a;"><i class="fas fa-image"></i></div>
                        <span>صورة</span>
                    </a>
                    <a href="javascript:void(0)" class="add-item-btn" onclick="openAddItemModal('video')">
                        <div class="btn-icon" style="background:#fee;color:#e74a3b;"><i class="fas fa-play-circle"></i></div>
                        <span>فيديو</span>
                    </a>
                    <a href="javascript:void(0)" class="add-item-btn" onclick="openAddItemModal('table')">
                        <div class="btn-icon" style="background:#fff5e6;color:#f6c23e;"><i class="fas fa-table"></i></div>
                        <span>جدول</span>
                    </a>
                    <a href="javascript:void(0)" class="add-item-btn" onclick="openAddItemModal('card')">
                        <div class="btn-icon" style="background:#e8fafe;color:#36b9cc;"><i class="fas fa-id-card"></i></div>
                        <span>بطاقة</span>
                    </a>
                    <a href="javascript:void(0)" class="add-item-btn" onclick="openAddItemModal('button')">
                        <div class="btn-icon" style="background:#f0e8ff;color:#6f42c1;"><i class="fas fa-mouse-pointer"></i></div>
                        <span>زر</span>
                    </a>
                    <a href="javascript:void(0)" class="add-item-btn" onclick="openAddItemModal('html')">
                        <div class="btn-icon" style="background:#f5f5f5;color:#5a5c69;"><i class="fas fa-code"></i></div>
                        <span>HTML</span>
                    </a>
                    <a href="javascript:void(0)" class="add-item-btn" onclick="openAddItemModal('spacer')">
                        <div class="btn-icon" style="background:#f5f5f5;color:#858796;"><i class="fas fa-arrows-alt-v"></i></div>
                        <span>فاصل</span>
                    </a>
                </div>
            </div>

            <!-- Items List -->
            <div class="items-wrapper">
                <div class="items-header">
                    <div class="items-header-title">
                        <i class="fas fa-layer-group" style="color:#4e54c8;"></i>
                        عناصر القسم
                        <span class="count-badge">{{ $homeSection->items->count() }}</span>
                    </div>
                </div>
                <div id="items-list">
                    @forelse($homeSection->items as $item)
                        @include('dashboard.home-sections._item-card', ['item' => $item, 'homeSection' => $homeSection])
                    @empty
                        <div class="empty-items-state" id="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-cubes"></i>
                            </div>
                            <h5>ابدأ البناء!</h5>
                            <p>اختر نوع العنصر من الشريط أعلاه لبدء بناء محتوى القسم</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Floating Save -->
<div class="save-float d-none d-lg-block">
    <button type="submit" form="sectionSettingsForm" class="btn btn-primary">
        <i class="fas fa-save mr-2"></i>حفظ
    </button>
</div>

<!-- ============================================ -->
<!-- Preview Modal                                  -->
<!-- ============================================ -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #2d3436 0%, #636e72 100%); color: #fff;">
                <h5 class="modal-title"><i class="fas fa-eye mr-2"></i>معاينة القسم</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-0" id="previewContent" style="min-height: 300px;"></div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- Add/Edit Item Modal                            -->
<!-- ============================================ -->
<div class="modal fade" id="itemModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #4e54c8 0%, #8f94fb 100%); color: #fff;">
                <h5 class="modal-title" id="itemModalTitle">
                    <i class="fas fa-plus-circle mr-2"></i>إضافة عنصر
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="itemForm" action="" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="item_type" id="modal_item_type">
                <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
                    <div id="modal-content-area"></div>
                </div>
                <div class="modal-footer" style="border-top: 2px solid #f0f2f5;">
                    <button type="button" class="btn btn-light" data-dismiss="modal" style="border-radius:10px;">إلغاء</button>
                    <button type="submit" class="btn btn-primary" id="itemFormSubmit" style="border-radius:10px;padding:8px 24px;">
                        <i class="fas fa-save mr-1"></i>حفظ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>

<script>
const CSRF_TOKEN = '{{ csrf_token() }}';
const SECTION_ID = {{ $homeSection->id }};
const STORE_URL = '{{ route("dashboard.home-section-items.store", $homeSection) }}';
const REORDER_URL = '{{ route("dashboard.home-section-items.reorder", $homeSection) }}';
const ITEM_SHOW_URL_BASE = "{{ url(route('dashboard.home-section-items.show', [$homeSection, 0])) }}";
const ITEM_SHOW_URL_TEMPLATE = ITEM_SHOW_URL_BASE.substring(0, ITEM_SHOW_URL_BASE.lastIndexOf('/') + 1);

const ITEM_TYPES = {
    'rich_text': { name: 'نص غني', icon: 'fa-align-right', color: '#4e54c8', badge: 'primary' },
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
// Sidebar Tabs
// ============================================
document.querySelectorAll('#sidebarTabs a').forEach(function(tab) {
    tab.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelectorAll('#sidebarTabs a').forEach(function(t) { t.classList.remove('active'); });
        this.classList.add('active');
        document.querySelectorAll('.sidebar-tab-pane').forEach(function(p) { p.classList.remove('active'); });
        document.getElementById(this.dataset.tab).classList.add('active');
    });
});

// ============================================
// Sortable Drag & Drop
// ============================================
var itemsList = document.getElementById('items-list');
if (itemsList) {
    new Sortable(itemsList, {
        handle: '.item-drag-handle',
        animation: 300,
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        onEnd: function() {
            var items = document.querySelectorAll('#items-list .item-card[data-id]');
            var orders = Array.from(items).map(function(item) { return item.dataset.id; });
            fetch(REORDER_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                body: JSON.stringify({ orders: orders })
            }).then(function(r) { return r.json(); }).then(function(data) {
                if (data.success) showToast('تم حفظ الترتيب');
            });
        }
    });
}

// ============================================
// Open Add Item Modal
// ============================================
function openAddItemModal(type) {
    var typeInfo = ITEM_TYPES[type] || { name: type, icon: 'fa-cube', color: '#333' };
    document.getElementById('itemModalTitle').innerHTML =
        '<i class="fas ' + typeInfo.icon + ' mr-2"></i>إضافة ' + typeInfo.name;
    document.getElementById('modal_item_type').value = type;
    document.getElementById('itemForm').action = STORE_URL;
    var oldMethod = document.getElementById('itemForm').querySelector('input[name="_method"]');
    if (oldMethod) oldMethod.remove();
    renderModalContent(type, {});
    $('#itemModal').modal('show');
}

// ============================================
// Open Edit Item Modal
// ============================================
function editItem(itemId) {
    var form = document.getElementById('itemForm');
    var submitBtn = document.getElementById('itemFormSubmit');
    var itemUrl = ITEM_SHOW_URL_TEMPLATE + itemId;
    form.action = itemUrl;
    var oldMethod = form.querySelector('input[name="_method"]');
    if (oldMethod) oldMethod.remove();
    document.getElementById('itemModalTitle').innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>جاري التحميل...';
    document.getElementById('modal-content-area').innerHTML = '<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x" style="color:#4e54c8;"></i><p class="mt-3 text-muted">جاري تحميل بيانات العنصر</p></div>';
    if (submitBtn) submitBtn.disabled = true;
    $('#itemModal').modal('show');
    fetch(itemUrl, {
        method: 'GET',
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin'
    })
    .then(function(r) {
        if (!r.ok) return r.text().then(function(txt) { throw new Error('HTTP ' + r.status + ': ' + txt.substring(0, 200)); });
        return r.json();
    })
    .then(function(data) {
        var type = data.item_type || 'text';
        var typeInfo = ITEM_TYPES[type] || { name: type, icon: 'fa-cube', color: '#333' };
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
        console.error('editItem fetch error:', err, 'URL:', itemUrl);
        document.getElementById('itemModalTitle').innerHTML = '<i class="fas fa-exclamation-triangle text-warning mr-2"></i>خطأ';
        document.getElementById('modal-content-area').innerHTML = '<div class="alert alert-danger"><i class="fas fa-times-circle mr-2"></i>لم يتم تحميل العنصر.<br><small class="text-muted">' + err.message + '</small></div>';
        if (submitBtn) submitBtn.disabled = false;
    });
}

// ============================================
// Render Modal Content by Type
// ============================================
function renderModalContent(type, data) {
    var area = document.getElementById('modal-content-area');
    var content = data.content || {};
    var settings = data.settings || {};
    if (typeof tinymce !== 'undefined') tinymce.remove('#modal_rich_editor');
    var html = '';

    switch(type) {
        case 'rich_text':
            html = '<div class="form-group"><label class="font-weight-bold"><i class="fas fa-align-right text-primary mr-1"></i>المحتوى</label><textarea id="modal_rich_editor" name="content[html]" rows="15">' + (content.html || '') + '</textarea></div><div class="row"><div class="col-md-6"><div class="form-group"><label class="font-weight-bold small">محاذاة النص</label><select name="settings[text_align]" class="form-control no-search"><option value="right" ' + ((settings.text_align||'right')==='right'?'selected':'') + '>يمين</option><option value="center" ' + (settings.text_align==='center'?'selected':'') + '>وسط</option><option value="left" ' + (settings.text_align==='left'?'selected':'') + '>يسار</option></select></div></div></div>';
            break;
        case 'text':
            html = '<div class="form-group"><label class="font-weight-bold">النص</label><textarea name="content[text]" class="form-control" rows="6">' + (content.text || '') + '</textarea></div>';
            break;
        case 'image':
            html = '<div class="form-group"><label class="font-weight-bold"><i class="fas fa-image text-success mr-1"></i>الصورة</label>' + (data.mediaUrl ? '<div class="mb-3"><img src="'+data.mediaUrl+'" class="image-preview"></div>' : '') + '<div class="upload-zone" onclick="this.querySelector(\'input\').click()" id="imageUploadZone"><input type="file" name="media" accept="image/*" style="display:none" onchange="previewUpload(this)"><i class="fas fa-cloud-upload-alt"></i><p>اسحب الصورة هنا أو انقر للاختيار</p><div id="uploadPreview"></div></div></div><div class="row"><div class="col-md-6"><div class="form-group"><label class="font-weight-bold small">النص البديل</label><input type="text" name="content[alt]" class="form-control" value="' + (content.alt || '') + '" placeholder="وصف الصورة"></div></div><div class="col-md-6"><div class="form-group"><label class="font-weight-bold small">رابط عند النقر</label><input type="url" name="content[link]" class="form-control" value="' + (content.link || '') + '" placeholder="https://..."></div></div></div><div class="form-group"><label class="font-weight-bold small">تعليق / وصف</label><input type="text" name="content[caption]" class="form-control" value="' + (content.caption || '') + '" placeholder="تعليق يظهر تحت الصورة"></div><hr><div class="row"><div class="col-md-4"><div class="form-group"><label class="font-weight-bold small"><i class="fas fa-expand-arrows-alt mr-1"></i>حجم العرض</label><select name="settings[media_size]" class="form-control no-search form-control-sm"><option value="full" ' + ((settings.media_size||'full')==='full'?'selected':'') + '>كامل العرض</option><option value="large" ' + (settings.media_size==='large'?'selected':'') + '>كبير</option><option value="medium" ' + (settings.media_size==='medium'?'selected':'') + '>متوسط</option><option value="small" ' + (settings.media_size==='small'?'selected':'') + '>صغير</option></select></div></div><div class="col-md-4"><div class="form-group"><label class="font-weight-bold small">أقصى عرض (px)</label><input type="number" name="settings[media_max_width]" class="form-control form-control-sm" value="' + (settings.media_max_width || '') + '" min="50" max="2000" placeholder="تلقائي"></div></div><div class="col-md-4"><div class="form-group"><label class="font-weight-bold small">أقصى ارتفاع (px)</label><input type="number" name="settings[media_max_height]" class="form-control form-control-sm" value="' + (settings.media_max_height || '') + '" min="50" max="2000" placeholder="تلقائي"></div></div></div>';
            break;
        case 'video':
            html = '<div class="form-group"><label class="font-weight-bold"><i class="fas fa-play-circle text-danger mr-1"></i>رابط يوتيوب</label><input type="url" name="youtube_url" class="form-control" value="' + (data.youtubeUrl || '') + '" placeholder="https://www.youtube.com/watch?v=..."><small class="form-text text-muted">أو ارفع فيديو من جهازك</small></div><div class="form-group"><label class="font-weight-bold">أو ارفع فيديو</label><input type="file" name="media" class="form-control-file" accept="video/*"></div><div class="form-group"><label class="font-weight-bold small">عنوان الفيديو</label><input type="text" name="content[title]" class="form-control" value="' + (content.title || '') + '"></div><hr><div class="row"><div class="col-md-4"><div class="form-group"><label class="font-weight-bold small"><i class="fas fa-expand-arrows-alt mr-1"></i>حجم العرض</label><select name="settings[media_size]" class="form-control no-search form-control-sm"><option value="full" ' + ((settings.media_size||'full')==='full'?'selected':'') + '>كامل العرض</option><option value="large" ' + (settings.media_size==='large'?'selected':'') + '>كبير</option><option value="medium" ' + (settings.media_size==='medium'?'selected':'') + '>متوسط</option><option value="small" ' + (settings.media_size==='small'?'selected':'') + '>صغير</option></select></div></div><div class="col-md-4"><div class="form-group"><label class="font-weight-bold small">أقصى عرض (px)</label><input type="number" name="settings[media_max_width]" class="form-control form-control-sm" value="' + (settings.media_max_width || '') + '" min="50" max="2000" placeholder="تلقائي"></div></div><div class="col-md-4"><div class="form-group"><label class="font-weight-bold small">أقصى ارتفاع (px)</label><input type="number" name="settings[media_max_height]" class="form-control form-control-sm" value="' + (settings.media_max_height || '') + '" min="50" max="2000" placeholder="تلقائي"></div></div></div>';
            break;
        case 'table':
            var tableData = content.table_data || [['العنوان 1','العنوان 2','العنوان 3'],['','',''],['','','']];
            html = '<div class="form-group"><label class="font-weight-bold"><i class="fas fa-table text-warning mr-1"></i>بيانات الجدول</label><div class="table-toolbar"><button type="button" class="btn btn-sm btn-outline-success" onclick="tableAddRow()"><i class="fas fa-plus mr-1"></i>صف</button><button type="button" class="btn btn-sm btn-outline-info" onclick="tableAddCol()"><i class="fas fa-plus mr-1"></i>عمود</button><button type="button" class="btn btn-sm btn-outline-danger" onclick="tableRemoveRow()"><i class="fas fa-minus mr-1"></i>صف</button><button type="button" class="btn btn-sm btn-outline-warning" onclick="tableRemoveCol()"><i class="fas fa-minus mr-1"></i>عمود</button><div class="custom-control custom-switch ml-auto"><input type="checkbox" name="settings[has_header]" id="table_has_header" class="custom-control-input" value="1" ' + ((settings.has_header !== false)?'checked':'') + '><label class="custom-control-label small" for="table_has_header">صف عناوين</label></div><div class="custom-control custom-switch"><input type="checkbox" name="settings[striped]" id="table_striped" class="custom-control-input" value="1" ' + (settings.striped?'checked':'') + '><label class="custom-control-label small" for="table_striped">مخطط</label></div></div><div class="table-editor-container"><table class="table-editor" id="tableEditor"><tbody>' + tableData.map(function(row, ri) { return '<tr>' + row.map(function(cell) { return (ri === 0 ? '<th contenteditable="true">' + (cell||'') + '</th>' : '<td contenteditable="true">' + (cell||'') + '</td>'); }).join('') + '</tr>'; }).join('') + '</tbody></table></div><input type="hidden" name="content[table_data]" id="tableDataInput"><small class="form-text text-muted mt-2"><i class="fas fa-info-circle mr-1"></i>انقر على أي خلية للتعديل.</small></div><div class="form-group"><label class="font-weight-bold small">عنوان الجدول</label><input type="text" name="content[title]" class="form-control" value="' + (content.title || '') + '"></div>';
            break;
        case 'card':
            html = '<div class="row"><div class="col-md-6"><div class="form-group"><label class="font-weight-bold"><i class="fas fa-heading text-info mr-1"></i>عنوان البطاقة</label><input type="text" name="content[title]" class="form-control" value="' + (content.title || '') + '"></div><div class="form-group"><label class="font-weight-bold">الوصف</label><textarea name="content[description]" class="form-control" rows="4">' + (content.description || '') + '</textarea></div><div class="form-group"><label class="font-weight-bold small">الرابط</label><input type="url" name="content[link]" class="form-control" value="' + (content.link || '') + '"></div><div class="form-group"><label class="font-weight-bold small">نص الزر</label><input type="text" name="content[button_text]" class="form-control" value="' + (content.button_text || 'اقرأ المزيد') + '"></div><div class="form-group"><label class="font-weight-bold small">الأيقونة</label><input type="text" name="content[icon]" class="form-control" value="' + (content.icon || '') + '" placeholder="fas fa-star"></div></div><div class="col-md-6"><div class="form-group"><label class="font-weight-bold">صورة البطاقة</label>' + (data.mediaUrl ? '<div class="mb-2"><img src="'+data.mediaUrl+'" class="image-preview"></div>' : '') + '<input type="file" name="media" class="form-control-file" accept="image/*"></div><div class="form-group"><label class="font-weight-bold small">لون البطاقة</label><select name="settings[card_color]" class="form-control no-search"><option value="white" ' + ((settings.card_color||'white')==='white'?'selected':'') + '>أبيض</option><option value="primary" ' + (settings.card_color==='primary'?'selected':'') + '>أزرق</option><option value="success" ' + (settings.card_color==='success'?'selected':'') + '>أخضر</option><option value="info" ' + (settings.card_color==='info'?'selected':'') + '>سماوي</option><option value="warning" ' + (settings.card_color==='warning'?'selected':'') + '>أصفر</option><option value="danger" ' + (settings.card_color==='danger'?'selected':'') + '>أحمر</option></select></div></div></div>';
            break;
        case 'button':
            html = '<div class="row"><div class="col-md-6"><div class="form-group"><label class="font-weight-bold">نص الزر</label><input type="text" name="content[text]" class="form-control" value="' + (content.text || '') + '" placeholder="اضغط هنا"></div><div class="form-group"><label class="font-weight-bold">الرابط</label><input type="url" name="content[url]" class="form-control" value="' + (content.url || '') + '" placeholder="https://..."></div><div class="form-group"><label class="font-weight-bold small">الأيقونة</label><input type="text" name="content[icon]" class="form-control" value="' + (content.icon || '') + '" placeholder="fas fa-arrow-left"></div></div><div class="col-md-6"><div class="form-group"><label class="font-weight-bold">لون الزر</label><select name="settings[btn_color]" class="form-control no-search"><option value="primary" ' + ((settings.btn_color||'primary')==='primary'?'selected':'') + '>أزرق</option><option value="success" ' + (settings.btn_color==='success'?'selected':'') + '>أخضر</option><option value="info" ' + (settings.btn_color==='info'?'selected':'') + '>سماوي</option><option value="warning" ' + (settings.btn_color==='warning'?'selected':'') + '>أصفر</option><option value="danger" ' + (settings.btn_color==='danger'?'selected':'') + '>أحمر</option><option value="dark" ' + (settings.btn_color==='dark'?'selected':'') + '>أسود</option><option value="light" ' + (settings.btn_color==='light'?'selected':'') + '>فاتح</option></select></div><div class="form-group"><label class="font-weight-bold small">الحجم</label><select name="settings[btn_size]" class="form-control no-search"><option value="md" ' + ((settings.btn_size||'md')==='md'?'selected':'') + '>متوسط</option><option value="sm" ' + (settings.btn_size==='sm'?'selected':'') + '>صغير</option><option value="lg" ' + (settings.btn_size==='lg'?'selected':'') + '>كبير</option></select></div><div class="custom-control custom-switch"><input type="checkbox" name="settings[btn_block]" id="btn_block" class="custom-control-input" value="1" ' + (settings.btn_block?'checked':'') + '><label class="custom-control-label" for="btn_block">عرض كامل</label></div><div class="custom-control custom-switch mt-2"><input type="checkbox" name="settings[new_tab]" id="new_tab" class="custom-control-input" value="1" ' + (settings.new_tab?'checked':'') + '><label class="custom-control-label" for="new_tab">فتح في تبويب جديد</label></div></div></div>';
            break;
        case 'html':
            var rawHtml = content.html || '';
            var escaped = rawHtml.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/\\/g, '\\\\').replace(/`/g, '\\`').replace(/\$/g, '\\$');
            html = '<ul class="nav nav-tabs mb-3" role="tablist"><li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#html-code-pane" role="tab"><i class="fas fa-code mr-1"></i>كود HTML</a></li><li class="nav-item"><a class="nav-link" data-toggle="tab" href="#html-preview-pane" role="tab"><i class="fas fa-eye mr-1"></i>معاينة</a></li></ul><div class="tab-content"><div class="tab-pane fade show active" id="html-code-pane" role="tabpanel"><div class="form-group"><label class="font-weight-bold"><i class="fas fa-code text-dark mr-1"></i>كود HTML</label><textarea name="content[html]" id="modal_html_content" class="form-control" rows="12" style="font-family:monospace;direction:ltr;text-align:left;">' + escaped + '</textarea><small class="form-text text-muted">استخدم تبويب المعاينة لرؤية النتيجة.</small></div></div><div class="tab-pane fade" id="html-preview-pane" role="tabpanel"><div class="form-group"><label class="font-weight-bold"><i class="fas fa-eye text-info mr-1"></i>معاينة مباشرة</label><div id="htmlPreviewFrame" style="min-height:280px;border:1px solid #d1d9e6;border-radius:12px;padding:16px;background:#fff;direction:rtl;overflow:auto;"></div><small class="form-text text-muted"><i class="fas fa-info-circle mr-1"></i>انقر على أي نص في المعاينة لتعديله مباشرة.</small></div></div></div>';
            break;
        case 'spacer':
            html = '<div class="form-group"><label class="font-weight-bold"><i class="fas fa-arrows-alt-v text-muted mr-1"></i>ارتفاع الفاصل</label><div class="input-group"><input type="number" name="settings[height]" class="form-control" value="' + (settings.height || 40) + '" min="10" max="300"><div class="input-group-append"><span class="input-group-text">px</span></div></div></div><div class="form-group"><div class="custom-control custom-switch"><input type="checkbox" name="settings[show_line]" id="show_line" class="custom-control-input" value="1" ' + (settings.show_line?'checked':'') + '><label class="custom-control-label" for="show_line">إظهار خط فاصل</label></div></div>';
            break;
    }

    area.innerHTML = html;
    if (type === 'rich_text') setTimeout(initTinyMCE, 200);
    if (type === 'html') setTimeout(initHtmlPreview, 100);
}

// ============================================
// TinyMCE
// ============================================
function initTinyMCE() {
    if (typeof tinymce === 'undefined') return;
    tinymce.init({
        selector: '#modal_rich_editor',
        directionality: 'rtl',
        height: 400,
        menubar: 'file edit view insert format table tools',
        plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount directionality',
        toolbar: 'undo redo | blocks | bold italic underline strikethrough forecolor backcolor | alignright aligncenter alignleft alignjustify | bullist numlist outdent indent | table | link image media | removeformat | code fullscreen | ltr rtl',
        content_style: "@import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap');body{font-family:'Tajawal',sans-serif;direction:rtl;text-align:right;font-size:15px;line-height:1.8;padding:12px;}table{border-collapse:collapse;width:100%;margin:12px 0;}table td,table th{border:1px solid #ddd;padding:8px 12px;}table th{background-color:#f0f2f8;font-weight:700;}img{max-width:100%;height:auto;border-radius:8px;}h1,h2,h3,h4,h5,h6{color:#2d3748;}a{color:#4e54c8;}blockquote{border-right:4px solid #4e54c8;padding:12px 20px;margin:12px 0;background:#f8f9fc;border-radius:0 8px 8px 0;}",
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
// HTML Live Preview
// ============================================
function initHtmlPreview() {
    var textarea = document.getElementById('modal_html_content');
    var frame = document.getElementById('htmlPreviewFrame');
    if (!textarea || !frame) return;
    var EDITABLE_SELECTOR = 'p, h1, h2, h3, h4, h5, h6, li, td, th, span, a, figcaption, label, blockquote';
    function stripScripts(h) { return h ? h.replace(/<script\b[\s\S]*?<\/script>/gi, '') : ''; }
    function makeEditable() {
        frame.querySelectorAll(EDITABLE_SELECTOR).forEach(function(el) {
            el.setAttribute('contenteditable', 'true');
            el.classList.add('html-preview-editable');
        });
    }
    function getClean() {
        var c = frame.cloneNode(true);
        c.querySelectorAll('*').forEach(function(el) { el.removeAttribute('contenteditable'); el.classList.remove('html-preview-editable'); });
        return c.innerHTML;
    }
    function updatePreview() {
        frame.innerHTML = stripScripts(textarea.value) || '<span class="text-muted">لا يوجد محتوى</span>';
        makeEditable();
    }
    function syncBack() { textarea.value = getClean(); }
    var t1, t2;
    textarea.addEventListener('input', function() { clearTimeout(t1); t1 = setTimeout(updatePreview, 300); });
    frame.addEventListener('input', function() { clearTimeout(t2); t2 = setTimeout(syncBack, 400); });
    frame.addEventListener('blur', syncBack, true);
    updatePreview();
}

// ============================================
// Table Editor
// ============================================
function tableAddRow() { var t = document.getElementById('tableEditor'); if (!t) return; var c = t.rows[0] ? t.rows[0].cells.length : 3; var r = t.insertRow(-1); for (var i = 0; i < c; i++) { var cell = r.insertCell(-1); cell.contentEditable = 'true'; } }
function tableAddCol() { var t = document.getElementById('tableEditor'); if (!t) return; for (var i = 0; i < t.rows.length; i++) { var c = i === 0 ? document.createElement('th') : document.createElement('td'); c.contentEditable = 'true'; c.textContent = i === 0 ? 'عنوان' : ''; t.rows[i].appendChild(c); } }
function tableRemoveRow() { var t = document.getElementById('tableEditor'); if (!t || t.rows.length <= 1) return; t.deleteRow(-1); }
function tableRemoveCol() { var t = document.getElementById('tableEditor'); if (!t) return; if (t.rows[0] && t.rows[0].cells.length <= 1) return; for (var i = 0; i < t.rows.length; i++) t.rows[i].deleteCell(-1); }
function getTableData() { var t = document.getElementById('tableEditor'); if (!t) return null; var d = []; for (var i = 0; i < t.rows.length; i++) { var r = []; for (var j = 0; j < t.rows[i].cells.length; j++) r.push(t.rows[i].cells[j].textContent.trim()); d.push(r); } return d; }

// ============================================
// Form Submit
// ============================================
document.getElementById('itemForm').addEventListener('submit', function() {
    var type = document.getElementById('modal_item_type').value;
    if (type === 'rich_text' && typeof tinymce !== 'undefined') { var e = tinymce.get('modal_rich_editor'); if (e) e.save(); }
    if (type === 'table') { var ti = document.getElementById('tableDataInput'); if (ti) ti.value = JSON.stringify(getTableData()); }
});

// ============================================
// Utilities
// ============================================
function previewUpload(input) {
    var preview = document.getElementById('uploadPreview');
    if (!preview || !input.files || !input.files[0]) return;
    var reader = new FileReader();
    reader.onload = function(e) { preview.innerHTML = '<img src="' + e.target.result + '" class="image-preview mt-2">'; };
    reader.readAsDataURL(input.files[0]);
}

function deleteItem(itemId) {
    if (!confirm('هل أنت متأكد من حذف هذا العنصر؟')) return;
    var form = document.createElement('form');
    form.method = 'POST';
    form.action = '/dashboard/home-sections/' + SECTION_ID + '/items/' + itemId;
    form.innerHTML = '<input type="hidden" name="_token" value="' + CSRF_TOKEN + '"><input type="hidden" name="_method" value="DELETE">';
    document.body.appendChild(form);
    form.submit();
}

function toggleItem(el) { el.closest('.item-card').classList.toggle('collapsed-item'); }

function togglePreview() {
    var pc = document.getElementById('previewContent');
    pc.innerHTML = '<div class="text-center p-5"><i class="fas fa-spinner fa-spin fa-2x" style="color:#4e54c8;"></i><p class="mt-3 text-muted">جاري تحميل المعاينة...</p></div>';
    $('#previewModal').modal('show');
    setTimeout(function() {
        var html = '<div style="padding:40px;direction:rtl;font-family:Tajawal,sans-serif;">';
        document.querySelectorAll('#items-list .item-card[data-id]').forEach(function(card) {
            var type = card.dataset.type;
            html += '<div style="margin-bottom:20px;padding:15px;border:1px solid #eee;border-radius:10px;">';
            html += '<small style="color:#999;display:block;margin-bottom:8px;">[' + (ITEM_TYPES[type] ? ITEM_TYPES[type].name : type) + ']</small>';
            var bodyEl = card.querySelector('.item-body');
            if (bodyEl) { var pe = bodyEl.querySelector('.item-preview-content'); if (pe) html += pe.innerHTML; }
            html += '</div>';
        });
        html += '</div>';
        pc.innerHTML = html || '<div class="text-center p-5"><p class="text-muted">لا يوجد محتوى للمعاينة</p></div>';
    }, 500);
}

function showToast(message) {
    var toast = document.createElement('div');
    toast.style.cssText = 'position:fixed;bottom:24px;left:24px;background:linear-gradient(135deg,#1cc88a,#17a673);color:#fff;padding:14px 28px;border-radius:14px;z-index:9999;font-weight:700;box-shadow:0 8px 25px rgba(28,200,138,0.4);animation:fadeInUp 0.3s ease;';
    toast.innerHTML = '<i class="fas fa-check-circle mr-2"></i>' + message;
    document.body.appendChild(toast);
    setTimeout(function() { toast.style.opacity = '0'; toast.style.transition = 'opacity 0.3s'; setTimeout(function() { toast.remove(); }, 300); }, 2500);
}

// ============================================
// Color Pickers Sync
// ============================================
document.querySelectorAll('.color-pair').forEach(function(pair) {
    var ci = pair.querySelector('input[type="color"]');
    var ti = pair.querySelector('input[type="text"]');
    if (ci && ti) {
        ci.addEventListener('input', function() { ti.value = ci.value; });
        ti.addEventListener('input', function() { ci.value = ti.value; });
    }
});

// ============================================
// Content Source
// ============================================
function toggleSourceOptions() {
    var sel = document.getElementById('content_source_type');
    var grp = document.getElementById('sourceOptionsGroup');
    var msg = document.getElementById('noSourceMessage');
    if (sel && grp) grp.style.display = sel.value ? '' : 'none';
    if (msg) msg.style.display = sel.value ? 'none' : '';
    initSourceIdsSelect2();
}

function setSourceMode(mode) {
    document.getElementById('source_mode_hidden').value = mode;
    document.querySelectorAll('.source-mode-card').forEach(function(c) { c.classList.remove('active-mode'); });
    event.currentTarget.classList.add('active-mode');
    document.getElementById('sourceModeAll').style.display = mode === 'all' ? '' : 'none';
    document.getElementById('sourceModeSelected').style.display = mode === 'selected' ? '' : 'none';
    if (mode === 'selected') initSourceIdsSelect2();
}

function initSourceIdsSelect2() {
    var $sel = $('#source_ids_select');
    if (!$sel.length) return;
    var type = document.getElementById('content_source_type').value;
    if (!type) return;
    if ($sel.data('select2')) $sel.select2('destroy');
    $sel.select2({
        theme: 'bootstrap4',
        language: 'ar',
        width: '100%',
        placeholder: 'ابحث واختر...',
        allowClear: true,
        minimumInputLength: 0,
        ajax: {
            url: '{{ route("dashboard.home-sections.search-source") }}',
            dataType: 'json',
            delay: 300,
            data: function(params) { return { type: type, q: params.term || '' }; },
            processResults: function(data) { return { results: data }; },
            cache: true
        },
        templateResult: function(item) {
            if (item.loading) return item.text;
            var $el = $('<span style="display:flex;align-items:center;gap:8px;"></span>');
            if (item.avatar) $el.append('<img src="' + item.avatar + '" style="width:30px;height:30px;border-radius:8px;object-fit:cover;">');
            $el.append('<span>' + item.text + '</span>');
            return $el;
        },
        templateSelection: function(item) { return item.text; }
    });
}

$(function() {
    if ($('#content_source_type').val() && $('#source_mode_hidden').val() === 'selected') {
        initSourceIdsSelect2();
    }
});
</script>
@endpush
