@extends('layouts.app')

@section('title', 'إضافة قسم جديد')

@push('styles')
<style>
    .section-type-card {
        border: 2px solid #e3e6f0;
        border-radius: 16px;
        padding: 24px 16px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: #fff;
        position: relative;
        overflow: hidden;
    }
    .section-type-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 30px rgba(78, 115, 223, 0.2);
        border-color: #4e73df;
    }
    .section-type-card.selected {
        border-color: #4e73df;
        background: linear-gradient(135deg, #f0f4ff 0%, #e8eeff 100%);
        box-shadow: 0 8px 25px rgba(78, 115, 223, 0.25);
    }
    .section-type-card.selected::after {
        content: '\f058';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        top: 10px;
        left: 10px;
        color: #4e73df;
        font-size: 1.2rem;
    }
    .section-type-card .type-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        font-size: 1.5rem;
        transition: all 0.3s ease;
    }
    .section-type-card:hover .type-icon {
        transform: scale(1.1);
    }
    .section-type-card .type-name {
        font-weight: 700;
        font-size: 0.95rem;
        color: #2d3748;
        margin-bottom: 4px;
    }
    .section-type-card .type-desc {
        font-size: 0.8rem;
        color: #718096;
        line-height: 1.4;
    }
    .settings-panel {
        background: #f8f9fc;
        border-radius: 16px;
        padding: 24px;
        border: 1px solid #e3e6f0;
    }
    .step-indicator {
        display: flex;
        align-items: center;
        margin-bottom: 2rem;
    }
    .step-item {
        display: flex;
        align-items: center;
        color: #a0aec0;
        font-weight: 600;
    }
    .step-item.active {
        color: #4e73df;
    }
    .step-item.completed {
        color: #1cc88a;
    }
    .step-number {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        margin-left: 8px;
        background: #e3e6f0;
        color: #a0aec0;
        transition: all 0.3s ease;
    }
    .step-item.active .step-number {
        background: #4e73df;
        color: #fff;
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.3);
    }
    .step-item.completed .step-number {
        background: #1cc88a;
        color: #fff;
    }
    .step-line {
        flex: 1;
        height: 2px;
        background: #e3e6f0;
        margin: 0 16px;
    }
    .step-line.active {
        background: #4e73df;
    }
    .color-picker-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .color-picker-wrapper input[type="color"] {
        width: 42px;
        height: 42px;
        border: 2px solid #e3e6f0;
        border-radius: 10px;
        padding: 2px;
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-magic text-primary mr-2"></i>إنشاء قسم جديد
            </h1>
            <p class="text-muted mb-0">اختر نوع القسم وقم بتخصيصه كما تريد</p>
        </div>
        <a href="{{ route('dashboard.home-sections.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-right mr-2"></i>العودة للأقسام
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <form action="{{ route('dashboard.home-sections.store') }}" method="POST" id="createSectionForm">
        @csrf

        <!-- Step Indicator -->
        <div class="step-indicator">
            <div class="step-item active" id="step1-indicator">
                <span class="step-number">1</span>
                <span>اختر نوع القسم</span>
            </div>
            <div class="step-line" id="step-line-1"></div>
            <div class="step-item" id="step2-indicator">
                <span class="step-number">2</span>
                <span>الإعدادات</span>
            </div>
        </div>

        <!-- Step 1: Section Type Selection -->
        <div id="step1-content">
            <div class="row">
                @php
                $sectionTypes = [
                    ['value' => 'rich_text', 'name' => 'نص غني', 'desc' => 'محرر نصوص متقدم مع تنسيق كامل وجداول', 'icon' => 'fa-align-right', 'color' => '#4e73df', 'bg' => '#eef2ff'],
                    ['value' => 'gallery', 'name' => 'معرض صور', 'desc' => 'كاروسيل / شبكة صور جميلة', 'icon' => 'fa-images', 'color' => '#1cc88a', 'bg' => '#eafff5'],
                    ['value' => 'cards', 'name' => 'بطاقات', 'desc' => 'شبكة بطاقات مع صور وعناوين', 'icon' => 'fa-th-large', 'color' => '#36b9cc', 'bg' => '#eafcff'],
                    ['value' => 'table', 'name' => 'جدول بيانات', 'desc' => 'جدول تفاعلي شبيه بالإكسل', 'icon' => 'fa-table', 'color' => '#f6c23e', 'bg' => '#fffbea'],
                    ['value' => 'text_with_image', 'name' => 'نص مع صورة', 'desc' => 'نص بجانب صورة بتصميم جذاب', 'icon' => 'fa-newspaper', 'color' => '#e74a3b', 'bg' => '#fff0ee'],
                    ['value' => 'video_section', 'name' => 'فيديو', 'desc' => 'يوتيوب أو فيديو مرفوع', 'icon' => 'fa-play-circle', 'color' => '#fd7e14', 'bg' => '#fff5ec'],
                    ['value' => 'hero', 'name' => 'بانر رئيسي', 'desc' => 'قسم بارز مع خلفية وعنوان كبير', 'icon' => 'fa-star', 'color' => '#6f42c1', 'bg' => '#f5eeff'],
                    ['value' => 'buttons', 'name' => 'أزرار وروابط', 'desc' => 'مجموعة أزرار وروابط سريعة', 'icon' => 'fa-external-link-alt', 'color' => '#20c997', 'bg' => '#eafff8'],
                    ['value' => 'stats', 'name' => 'إحصائيات', 'desc' => 'عدادات وأرقام مميزة', 'icon' => 'fa-chart-bar', 'color' => '#17a2b8', 'bg' => '#eaf8ff'],
                    ['value' => 'divider', 'name' => 'فاصل', 'desc' => 'فاصل تزييني أو مسافة فارغة', 'icon' => 'fa-minus', 'color' => '#858796', 'bg' => '#f5f5f5'],
                    ['value' => 'custom', 'name' => 'HTML مخصص', 'desc' => 'كود HTML حر لتخصيص كامل', 'icon' => 'fa-code', 'color' => '#5a5c69', 'bg' => '#f0f0f0'],
                    ['value' => 'mixed', 'name' => 'محتوى مختلط', 'desc' => 'مزيج من أنواع مختلفة', 'icon' => 'fa-puzzle-piece', 'color' => '#e83e8c', 'bg' => '#fff0f6'],
                ];
                @endphp

                @foreach($sectionTypes as $type)
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="section-type-card {{ old('section_type') == $type['value'] ? 'selected' : '' }}"
                             onclick="selectType('{{ $type['value'] }}', this)">
                            <div class="type-icon" style="background: {{ $type['bg'] }}; color: {{ $type['color'] }};">
                                <i class="fas {{ $type['icon'] }}"></i>
                            </div>
                            <div class="type-name">{{ $type['name'] }}</div>
                            <div class="type-desc">{{ $type['desc'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
            <input type="hidden" name="section_type" id="section_type" value="{{ old('section_type') }}" required>
        </div>

        <!-- Step 2: Settings -->
        <div id="step2-content" style="display: {{ old('section_type') ? 'block' : 'none' }};">
            <div class="row">
                <div class="col-lg-7">
                    <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px;">
                        <div class="card-header bg-white border-bottom py-3" style="border-radius: 16px 16px 0 0;">
                            <h6 class="mb-0 font-weight-bold text-primary">
                                <i class="fas fa-cog mr-2"></i>الإعدادات الأساسية
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="form-group">
                                <label for="title" class="font-weight-bold">
                                    <i class="fas fa-heading text-primary mr-1"></i>عنوان القسم <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="title" id="title" 
                                       class="form-control @error('title') is-invalid @enderror" 
                                       value="{{ old('title') }}" 
                                       placeholder="مثال: من نحن، الأخبار، معرض الصور..."
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="subtitle" class="font-weight-bold">
                                    <i class="fas fa-font text-info mr-1"></i>العنوان الفرعي
                                </label>
                                <input type="text" name="settings[subtitle]" id="subtitle" 
                                       class="form-control" 
                                       value="{{ old('settings.subtitle') }}" 
                                       placeholder="وصف مختصر يظهر تحت العنوان">
                            </div>

                            <div class="form-group">
                                <label for="description" class="font-weight-bold">
                                    <i class="fas fa-align-right text-secondary mr-1"></i>الوصف
                                </label>
                                <textarea name="settings[description]" id="description" 
                                          class="form-control" rows="3"
                                          placeholder="وصف تفصيلي للقسم (اختياري)">{{ old('settings.description') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="icon" class="font-weight-bold">
                                    <i class="fas fa-icons text-warning mr-1"></i>أيقونة القسم
                                </label>
                                <div class="input-group">
                                    <input type="text" name="settings[icon]" id="icon" 
                                           class="form-control" 
                                           value="{{ old('settings.icon') }}"
                                           placeholder="fas fa-star">
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="icon-preview">
                                            <i class="fas fa-star"></i>
                                        </span>
                                    </div>
                                </div>
                                <small class="form-text text-muted">
                                    استخدم أيقونات <a href="https://fontawesome.com/v5/search" target="_blank">Font Awesome 5</a>
                                </small>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" name="is_active" id="is_active" 
                                                   class="custom-control-input" value="1" 
                                                   {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="custom-control-label font-weight-bold" for="is_active">
                                                <i class="fas fa-eye text-success mr-1"></i>تفعيل القسم
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" name="settings[show_title]" id="show_title" 
                                                   class="custom-control-input" value="1" 
                                                   {{ old('settings.show_title', true) ? 'checked' : '' }}>
                                            <label class="custom-control-label font-weight-bold" for="show_title">
                                                <i class="fas fa-heading text-info mr-1"></i>إظهار العنوان
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px;">
                        <div class="card-header bg-white border-bottom py-3" style="border-radius: 16px 16px 0 0;">
                            <h6 class="mb-0 font-weight-bold text-primary">
                                <i class="fas fa-palette mr-2"></i>التصميم والمظهر
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="form-group">
                                <label class="font-weight-bold">
                                    <i class="fas fa-fill-drip text-primary mr-1"></i>لون الخلفية
                                </label>
                                <div class="color-picker-wrapper">
                                    <input type="color" name="settings[background_color]" 
                                           value="{{ old('settings.background_color', '#ffffff') }}"
                                           id="bg_color">
                                    <input type="text" class="form-control form-control-sm" 
                                           value="{{ old('settings.background_color', '#ffffff') }}"
                                           id="bg_color_text"
                                           onchange="document.getElementById('bg_color').value = this.value">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">
                                    <i class="fas fa-font text-dark mr-1"></i>لون النص
                                </label>
                                <div class="color-picker-wrapper">
                                    <input type="color" name="settings[text_color]" 
                                           value="{{ old('settings.text_color', '#333333') }}"
                                           id="text_color">
                                    <input type="text" class="form-control form-control-sm" 
                                           value="{{ old('settings.text_color', '#333333') }}"
                                           id="text_color_text"
                                           onchange="document.getElementById('text_color').value = this.value">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            <i class="fas fa-arrow-up text-muted mr-1"></i>مسافة علوية
                                        </label>
                                        <div class="input-group">
                                            <input type="number" name="settings[padding_top]" 
                                                   class="form-control" 
                                                   value="{{ old('settings.padding_top', 40) }}" min="0" max="200">
                                            <div class="input-group-append">
                                                <span class="input-group-text">px</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            <i class="fas fa-arrow-down text-muted mr-1"></i>مسافة سفلية
                                        </label>
                                        <div class="input-group">
                                            <input type="number" name="settings[padding_bottom]" 
                                                   class="form-control" 
                                                   value="{{ old('settings.padding_bottom', 40) }}" min="0" max="200">
                                            <div class="input-group-append">
                                                <span class="input-group-text">px</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" id="columns-setting" style="display: none;">
                                <label class="font-weight-bold">
                                    <i class="fas fa-columns text-info mr-1"></i>عدد الأعمدة
                                </label>
                                <select name="settings[columns]" class="form-control no-search">
                                    <option value="2" {{ old('settings.columns') == '2' ? 'selected' : '' }}>2 أعمدة</option>
                                    <option value="3" {{ old('settings.columns', '3') == '3' ? 'selected' : '' }}>3 أعمدة</option>
                                    <option value="4" {{ old('settings.columns') == '4' ? 'selected' : '' }}>4 أعمدة</option>
                                    <option value="6" {{ old('settings.columns') == '6' ? 'selected' : '' }}>6 أعمدة</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="css_classes" class="font-weight-bold">
                                    <i class="fas fa-code text-secondary mr-1"></i>CSS مخصص
                                </label>
                                <input type="text" name="css_classes" id="css_classes" 
                                       class="form-control @error('css_classes') is-invalid @enderror" 
                                       value="{{ old('css_classes') }}" 
                                       placeholder="فئات CSS إضافية">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mb-4">
                <button type="button" class="btn btn-light btn-lg mr-2" onclick="goToStep(1)">
                    <i class="fas fa-arrow-right mr-2"></i>تغيير النوع
                </button>
                <button type="submit" class="btn btn-primary btn-lg shadow">
                    <i class="fas fa-magic mr-2"></i>إنشاء القسم والبدء بالبناء
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function selectType(type, card) {
        // إزالة التحديد من الكل
        document.querySelectorAll('.section-type-card').forEach(c => c.classList.remove('selected'));
        // تحديد الكارد المختار
        card.classList.add('selected');
        // تحديث الحقل المخفي
        document.getElementById('section_type').value = type;
        
        // إظهار/إخفاء إعدادات الأعمدة
        const columnsTypes = ['cards', 'gallery', 'stats', 'buttons'];
        document.getElementById('columns-setting').style.display = columnsTypes.includes(type) ? 'block' : 'none';
        
        // الانتقال للخطوة التالية بعد لحظة
        setTimeout(() => goToStep(2), 300);
    }

    function goToStep(step) {
        if (step === 2 && !document.getElementById('section_type').value) {
            alert('يرجى اختيار نوع القسم أولاً');
            return;
        }
        
        if (step === 1) {
            document.getElementById('step1-content').style.display = 'block';
            document.getElementById('step2-content').style.display = 'none';
            document.getElementById('step1-indicator').classList.add('active');
            document.getElementById('step1-indicator').classList.remove('completed');
            document.getElementById('step2-indicator').classList.remove('active');
            document.getElementById('step-line-1').classList.remove('active');
        } else {
            document.getElementById('step1-content').style.display = 'none';
            document.getElementById('step2-content').style.display = 'block';
            document.getElementById('step1-indicator').classList.remove('active');
            document.getElementById('step1-indicator').classList.add('completed');
            document.getElementById('step2-indicator').classList.add('active');
            document.getElementById('step-line-1').classList.add('active');
            
            // تركيز على حقل العنوان
            setTimeout(() => document.getElementById('title').focus(), 100);
        }
    }

    // تحديث معاينة الأيقونة
    document.getElementById('icon').addEventListener('input', function() {
        const preview = document.getElementById('icon-preview');
        preview.innerHTML = '<i class="' + (this.value || 'fas fa-star') + '"></i>';
    });

    // ربط color pickers مع text inputs
    document.getElementById('bg_color').addEventListener('input', function() {
        document.getElementById('bg_color_text').value = this.value;
    });
    document.getElementById('text_color').addEventListener('input', function() {
        document.getElementById('text_color_text').value = this.value;
    });

    // إذا كان هناك old section_type، انتقل للخطوة 2
    @if(old('section_type'))
        goToStep(2);
    @endif
</script>
@endpush
