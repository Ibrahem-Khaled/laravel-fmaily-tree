<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة نفسك إلى تواصل العائلة</title>

    <!-- Tailwind CSS for styling -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts (Cairo for Arabic) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- jQuery for Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 for enhanced dropdowns -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        /* Custom styles to enhance the design */
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f0f4f8; /* Light blue-gray background */
        }

        /* Select2 RTL and custom styling - Enhanced */
        .select2-container--default .select2-selection--single {
            height: 56px !important;
            border: 2px solid #e2e8f0 !important;
            border-radius: 12px !important;
            background-color: #f8fafc !important;
            padding: 12px 16px !important;
            transition: all 0.3s ease !important;
        }

        .select2-container--default .select2-selection--single:hover {
            border-color: #cbd5e1 !important;
            background-color: #ffffff !important;
        }

        .select2-container--default .select2-selection--single:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 32px !important;
            padding-right: 0 !important;
            padding-left: 0 !important;
            color: #374151 !important;
            font-size: 16px !important;
            font-weight: 500 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #9ca3af !important;
            font-size: 16px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 52px !important;
            right: 12px !important;
            width: 20px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #6b7280 transparent transparent transparent !important;
            border-width: 6px 6px 0 6px !important;
            margin-top: -3px !important;
        }

        .select2-dropdown {
            border: 2px solid #e2e8f0 !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
            margin-top: 4px !important;
        }

        .select2-container--default .select2-results__option {
            padding: 12px 16px !important;
            font-size: 16px !important;
            color: #374151 !important;
            border-bottom: 1px solid #f3f4f6 !important;
            transition: all 0.2s ease !important;
        }

        .select2-container--default .select2-results__option:last-child {
            border-bottom: none !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #3b82f6 !important;
            color: white !important;
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #eff6ff !important;
            color: #1d4ed8 !important;
            font-weight: 600 !important;
        }

        .select2-search--dropdown {
            padding: 12px !important;
            background-color: #f9fafb !important;
            border-bottom: 1px solid #e5e7eb !important;
        }

        .select2-search--dropdown .select2-search__field {
            border: 2px solid #e2e8f0 !important;
            border-radius: 8px !important;
            padding: 10px 14px !important;
            font-size: 16px !important;
            background-color: white !important;
            transition: all 0.2s ease !important;
        }

        .select2-search--dropdown .select2-search__field:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
            outline: none !important;
        }

        .select2-results__message {
            padding: 16px !important;
            text-align: center !important;
            color: #6b7280 !important;
            font-size: 16px !important;
        }

        /* Additional enhancements for better appearance */
        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__clear {
            color: #6b7280 !important;
            font-size: 18px !important;
            font-weight: bold !important;
            margin-top: -2px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__clear:hover {
            color: #dc2626 !important;
        }

        /* Custom styling for the dropdown results */
        .select2-results {
            max-height: 300px !important;
        }

        .select2-results__options {
            padding: 8px 0 !important;
        }

        /* Loading state styling */
        .select2-results__option[role=group] {
            padding: 8px 16px !important;
            font-weight: 600 !important;
            color: #374151 !important;
            background-color: #f9fafb !important;
            border-bottom: 1px solid #e5e7eb !important;
        }

        /* Better focus states */
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        }

        /* Improved mobile responsiveness for Select2 */
        @media (max-width: 768px) {
            .select2-container--default .select2-selection--single {
                height: 52px !important;
                padding: 10px 14px !important;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                font-size: 15px !important;
                line-height: 30px !important;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 48px !important;
                right: 10px !important;
            }

            .select2-dropdown {
                border-radius: 10px !important;
            }

            .select2-container--default .select2-results__option {
                padding: 10px 14px !important;
                font-size: 15px !important;
            }
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            .form-container {
                margin: 1rem;
                padding: 1rem;
                border-radius: 15px;
            }

            .progress-bar {
                margin-bottom: 1.5rem;
            }

            .progress-step {
                width: 35px;
                height: 35px;
                font-size: 0.9rem;
            }

            .grid.grid-cols-1.md\\:grid-cols-2 {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .btn-primary, .btn-secondary {
                padding: 10px 20px;
                font-size: 0.9rem;
            }

            .form-input {
                padding: 10px 14px;
            }

            .upload-area {
                padding: 1.5rem;
            }

            #image-preview {
                width: 100px;
                height: 100px;
            }
        }

        @media (max-width: 480px) {
            .form-container {
                margin: 0.5rem;
                padding: 0.75rem;
            }

            h1 {
                font-size: 1.5rem;
            }

            .progress-step {
                width: 30px;
                height: 30px;
                font-size: 0.8rem;
            }

            .btn-primary, .btn-secondary {
                padding: 8px 16px;
                font-size: 0.85rem;
            }
        }
        .form-container {
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease-in-out;
        }
        .form-step {
            display: none;
            animation: fadeIn 0.5s;
        }
        .form-step.active {
            display: block;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .form-input {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 16px;
            transition: all 0.2s ease;
            width: 100%;
        }
        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        .form-label {
            font-weight: 600;
            color: #475569;
            margin-bottom: 8px;
            display: block;
        }
        .btn-primary {
            background-color: #2563eb;
            color: white;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 10px;
            transition: all 0.2s ease;
            border: none;
        }
        .btn-primary:hover {
            background-color: #1d4ed8;
            transform: translateY(-2px);
        }
        .btn-secondary {
            background-color: #e2e8f0;
            color: #475569;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 10px;
            transition: all 0.2s ease;
            border: none;
        }
        .btn-secondary:hover {
            background-color: #cbd5e1;
        }
        .progress-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            position: relative;
        }
        .progress-bar::before {
            content: '';
            position: absolute;
            top: 50%;
            right: 0;
            left: 0;
            height: 3px;
            background-color: #e2e8f0;
            transform: translateY(-50%);
            z-index: 1;
        }
        .progress-bar-line {
            position: absolute;
            top: 50%;
            right: 0;
            height: 3px;
            background-color: #3b82f6;
            transform: translateY(-50%);
            z-index: 2;
            transition: width 0.4s ease;
        }
        .progress-step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e2e8f0;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            border: 3px solid #e2e8f0;
            transition: all 0.4s ease;
            z-index: 3;
        }
        .progress-step.active {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }
        .gender-option {
            border: 2px solid #e2e8f0;
            padding: 1rem;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center;
        }
        .gender-option.selected {
            border-color: #3b82f6;
            background-color: #eff6ff;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        .upload-area {
            border: 2px dashed #cbd5e1;
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .upload-area:hover {
            border-color: #3b82f6;
            background-color: #f8fafc;
        }
        #image-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen" dir="rtl">
    @include('partials.main-header')

    <div class="container mx-auto px-4 py-4 sm:py-8">
        <div class="form-container w-full max-w-4xl mx-auto p-4 sm:p-6 lg:p-10">

        <!-- Form Header -->
        <div class="text-center mb-8">
            <i class="fas fa-tree text-4xl text-blue-600 mb-2"></i>
            <h1 class="text-3xl font-bold text-gray-800">أضف نفسك إلى تواصل العائلة</h1>
            <p class="text-gray-500 mt-2">املأ الحقول التالية لإضافة بياناتك وتحديد مكانك في العائلة.</p>
        </div>

        <!-- Progress Bar -->
        <div class="progress-bar">
            <div class="progress-bar-line" id="progressBarLine"></div>
            <div class="progress-step active" data-step="1"><i class="fas fa-user"></i></div>
            <div class="progress-step" data-step="2"><i class="fas fa-users"></i></div>
            <div class="progress-step" data-step="3"><i class="fas fa-briefcase"></i></div>
            <div class="progress-step" data-step="4"><i class="fas fa-camera"></i></div>
        </div>

        <!-- Form -->
        <form action="{{ route('people.store') }}" method="POST" enctype="multipart/form-data" id="multiStepForm">
            @csrf
            {{-- ✨ This hidden input identifies the form's source --}}
            <input type="hidden" name="source_page" value="add_self">

            <!-- Step 1: Basic Information -->
            <div class="form-step active" id="step1">
                <h2 class="text-xl font-bold text-center mb-6 text-gray-700">1. المعلومات الأساسية</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="first_name" class="form-label">الاسم الأول <span class="text-red-500">*</span></label>
                        <input type="text" class="form-input" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                    </div>
                    <div>
                        <label for="last_name" class="form-label">الاسم الأخير</label>
                        <input type="text" class="form-input" id="last_name" name="last_name" value="{{ old('last_name') }}">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="birth_date" class="form-label">تاريخ الميلاد</label>
                        <input type="date" class="form-input" id="birth_date" name="birth_date" value="{{ old('birth_date') }}">
                    </div>
                    <div>
                        <label for="death_date" class="form-label">تاريخ الوفاة (إن وجد)</label>
                        <input type="date" class="form-input" id="death_date" name="death_date" value="{{ old('death_date') }}">
                    </div>
                </div>
                 <div>
                    <label class="form-label">الجنس <span class="text-red-500">*</span></label>
                    <input type="hidden" name="gender" id="gender_input" value="{{ old('gender', 'male') }}" required>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="gender-option" data-value="male">
                            <i class="fas fa-mars text-3xl text-blue-500 mb-2"></i>
                            <span class="font-semibold text-gray-700">ذكر</span>
                        </div>
                        <div class="gender-option" data-value="female">
                            <i class="fas fa-venus text-3xl text-pink-500 mb-2"></i>
                            <span class="font-semibold text-gray-700">أنثى</span>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-6 sm:mt-8">
                    <button type="button" class="btn-primary next-btn w-full sm:w-auto">التالي <i class="fas fa-arrow-left mr-2"></i></button>
                </div>
            </div>

            <!-- Step 2: Family Links -->
            <div class="form-step" id="step2">
                <h2 class="text-xl font-bold text-center mb-6 text-gray-700">2. روابط العائلة</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="parent_id" class="form-label">الأب <span class="text-gray-500">(ذكور فقط)</span></label>
                        <select class="form-input" id="parent_id" name="parent_id">
                            <option value="">-- اختر الأب --</option>
                            @foreach ($males as $father)
                                <option value="{{ $father->id }}" @selected(old('parent_id') == $father->id)>
                                    {{ $father->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="mother_id" class="form-label">الأم <span class="text-gray-500">(إناث فقط)</span></label>
                        <select class="form-input" id="mother_id" name="mother_id">
                            <option value="">-- اختر الأب أولاً --</option>
                        </select>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row justify-between gap-4 mt-6 sm:mt-8">
                    <button type="button" class="btn-secondary prev-btn w-full sm:w-auto"><i class="fas fa-arrow-right ml-2"></i> السابق</button>
                    <button type="button" class="btn-primary next-btn w-full sm:w-auto">التالي <i class="fas fa-arrow-left mr-2"></i></button>
                </div>
            </div>

            <!-- Step 3: Additional Information -->
            <div class="form-step" id="step3">
                <h2 class="text-xl font-bold text-center mb-6 text-gray-700">3. معلومات إضافية</h2>
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="occupation" class="form-label">المهنة</label>
                        <input type="text" class="form-input" id="occupation" name="occupation" value="{{ old('occupation') }}">
                    </div>
                    <div>
                        <label for="location" class="form-label">مكان الإقامة</label>
                        <input type="text" class="form-input location-autocomplete" id="location" name="location" value="{{ old('location') }}" autocomplete="off" placeholder="ابدأ الكتابة للبحث...">
                        <input type="hidden" id="location_id" name="location_id" value="{{ old('location_id') }}">
                        <small class="form-text text-muted mt-1 block">سيتم البحث تلقائياً في الأماكن الموجودة</small>
                        <div id="location_suggestions" class="list-group mt-2" style="display: none; position: absolute; z-index: 1000; max-height: 200px; overflow-y: auto; width: 100%; background: white; border: 1px solid #ddd; border-radius: 4px;"></div>
                    </div>
                </div>
                <div>
                    <label for="biography" class="form-label">سيرة ذاتية</label>
                    <textarea class="form-input" id="biography" name="biography" rows="4" placeholder="اكتب نبذة قصيرة عنك...">{{ old('biography') }}</textarea>
                </div>
                <div class="flex flex-col sm:flex-row justify-between gap-4 mt-6 sm:mt-8">
                    <button type="button" class="btn-secondary prev-btn w-full sm:w-auto"><i class="fas fa-arrow-right ml-2"></i> السابق</button>
                    <button type="button" class="btn-primary next-btn w-full sm:w-auto">التالي <i class="fas fa-arrow-left mr-2"></i></button>
                </div>
            </div>

            <!-- Step 4: Profile Picture -->
            <div class="form-step" id="step4">
                <h2 class="text-xl font-bold text-center mb-6 text-gray-700">4. صورتك الشخصية</h2>
                <div class="flex flex-col items-center">
                    <div class="upload-area w-full" id="uploadArea">
                        <input type="file" id="photo" name="photo" class="hidden" accept="image/*">
                        <div id="upload-prompt">
                            <i class="fas fa-cloud-upload-alt text-5xl text-gray-400 mb-4"></i>
                            <p class="font-semibold text-gray-700">اسحب الصورة وأفلتها هنا، أو انقر للاختيار</p>
                            <p class="text-sm text-gray-500 mt-1">PNG, JPG, GIF up to 2MB</p>
                        </div>
                        <div id="preview-container" class="hidden flex-col items-center">
                            <img id="image-preview" src="#" alt="Preview">
                            <p id="file-name" class="mt-4 font-semibold text-gray-700"></p>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row justify-between gap-4 mt-6 sm:mt-8">
                    <button type="button" class="btn-secondary prev-btn w-full sm:w-auto"><i class="fas fa-arrow-right ml-2"></i> السابق</button>
                    <button type="submit" class="btn-primary w-full sm:w-auto">حفظ وإضافة للشجرة <i class="fas fa-check ml-2"></i></button>
                </div>
            </div>
        </form>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('multiStepForm');
    const steps = Array.from(form.querySelectorAll('.form-step'));
    const nextButtons = form.querySelectorAll('.next-btn');
    const prevButtons = form.querySelectorAll('.prev-btn');
    const progressSteps = Array.from(document.querySelectorAll('.progress-step'));
    const progressBarLine = document.getElementById('progressBarLine');

    let currentStep = 0;

    function updateProgress() {
        progressSteps.forEach((step, index) => {
            if (index < currentStep + 1) {
                step.classList.add('active');
            } else {
                step.classList.remove('active');
            }
        });
        const progressWidth = (currentStep / (steps.length - 1)) * 100;
        progressBarLine.style.width = progressWidth + '%';
    }

    function showStep(stepIndex) {
        steps.forEach((step, index) => {
            step.classList.toggle('active', index === stepIndex);
        });
        currentStep = stepIndex;
        updateProgress();
    }

    nextButtons.forEach(button => {
        button.addEventListener('click', () => {
            const currentStepFields = steps[currentStep].querySelectorAll('[required]');
            let isValid = true;
            currentStepFields.forEach(field => {
                if (!field.value.trim()) {
                    field.style.borderColor = 'red';
                    isValid = false;
                } else {
                    field.style.borderColor = '';
                }
            });

            if (isValid && currentStep < steps.length - 1) {
                showStep(currentStep + 1);
            }
        });
    });

    prevButtons.forEach(button => {
        button.addEventListener('click', () => {
            if (currentStep > 0) {
                showStep(currentStep - 1);
            }
        });
    });

    // Gender selection logic
    const genderOptions = document.querySelectorAll('.gender-option');
    const genderInput = document.getElementById('gender_input');

    const currentGender = genderInput.value;
    genderOptions.forEach(opt => {
         opt.classList.toggle('selected', opt.dataset.value === currentGender);
    });

    genderOptions.forEach(option => {
        option.addEventListener('click', () => {
            genderOptions.forEach(opt => opt.classList.remove('selected'));
            option.classList.add('selected');
            genderInput.value = option.dataset.value;
        });
    });

    // File upload logic
    const uploadArea = document.getElementById('uploadArea');
    const photoInput = document.getElementById('photo');
    const uploadPrompt = document.getElementById('upload-prompt');
    const previewContainer = document.getElementById('preview-container');
    const imagePreview = document.getElementById('image-preview');
    const fileName = document.getElementById('file-name');

    uploadArea.addEventListener('click', () => photoInput.click());
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('border-blue-500', 'bg-gray-50');
    });
    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('border-blue-500', 'bg-gray-50');
    });
    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('border-blue-500', 'bg-gray-50');
        const file = e.dataTransfer.files[0];
        if (file) {
            photoInput.files = e.dataTransfer.files;
            handleFile(file);
        }
    });
    photoInput.addEventListener('change', () => {
        const file = photoInput.files[0];
        if (file) {
            handleFile(file);
        }
    });

    function handleFile(file) {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                fileName.textContent = file.name;
                uploadPrompt.classList.add('hidden');
                previewContainer.classList.remove('hidden');
                previewContainer.classList.add('flex');
            }
            reader.readAsDataURL(file);
        }
    }

    // Initialize Select2 for father dropdown with enhanced settings
    $('#parent_id').select2({
        placeholder: 'ابحث عن الأب...',
        allowClear: true,
        width: '100%',
        dropdownAutoWidth: true,
        language: {
            noResults: function() {
                return "لا توجد نتائج";
            },
            searching: function() {
                return "جاري البحث...";
            },
            inputTooShort: function() {
                return "اكتب حرف واحد على الأقل للبحث";
            },
            inputTooLong: function() {
                return "النص طويل جداً";
            },
            loadingMore: function() {
                return "جاري تحميل المزيد...";
            },
            maximumSelected: function() {
                return "لا يمكنك اختيار أكثر من عنصر واحد";
            }
        },
        templateResult: function(person) {
            if (person.loading) {
                return person.text;
            }
            return $('<div class="select2-result-item">' + person.text + '</div>');
        },
        templateSelection: function(person) {
            return $('<div class="select2-selection-item">' + person.text + '</div>');
        }
    });

    // Initialize Select2 for mother dropdown with enhanced settings
    $('#mother_id').select2({
        placeholder: 'اختر الأب أولاً...',
        allowClear: true,
        width: '100%',
        dropdownAutoWidth: true,
        language: {
            noResults: function() {
                return "لا توجد نتائج";
            },
            searching: function() {
                return "جاري البحث...";
            },
            inputTooShort: function() {
                return "اكتب حرف واحد على الأقل للبحث";
            },
            inputTooLong: function() {
                return "النص طويل جداً";
            },
            loadingMore: function() {
                return "جاري تحميل المزيد...";
            },
            maximumSelected: function() {
                return "لا يمكنك اختيار أكثر من عنصر واحد";
            }
        },
        templateResult: function(person) {
            if (person.loading) {
                return person.text;
            }
            return $('<div class="select2-result-item">' + person.text + '</div>');
        },
        templateSelection: function(person) {
            return $('<div class="select2-selection-item">' + person.text + '</div>');
        }
    });

    // =================================================================
    //  START: Wives Fetching Logic (Corrected and Integrated)
    // =================================================================
    const fatherSelect = document.getElementById('parent_id');
    const motherSelect = document.getElementById('mother_id');

    if (fatherSelect && motherSelect) {
        fatherSelect.addEventListener('change', function () {
            const fatherId = this.value;

            // Reset mother dropdown and show loading state
            $('#mother_id').empty().append('<option value="">-- جار التحميل --</option>');
            $('#mother_id').select2('destroy').select2({
                placeholder: 'جار التحميل...',
                allowClear: true,
                width: '100%',
                dropdownAutoWidth: true,
                language: {
                    noResults: function() {
                        return "لا توجد نتائج";
                    },
                    searching: function() {
                        return "جاري البحث...";
                    },
                    inputTooShort: function() {
                        return "اكتب حرف واحد على الأقل للبحث";
                    },
                    inputTooLong: function() {
                        return "النص طويل جداً";
                    },
                    loadingMore: function() {
                        return "جاري تحميل المزيد...";
                    },
                    maximumSelected: function() {
                        return "لا يمكنك اختيار أكثر من عنصر واحد";
                    }
                }
            });

            if (!fatherId) {
                $('#mother_id').empty().append('<option value="">-- اختر الأب أولاً --</option>');
                $('#mother_id').select2('destroy').select2({
                    placeholder: 'اختر الأب أولاً...',
                    allowClear: true,
                    width: '100%',
                    dropdownAutoWidth: true,
                    language: {
                        noResults: function() {
                            return "لا توجد نتائج";
                        },
                        searching: function() {
                            return "جاري البحث...";
                        },
                        inputTooShort: function() {
                            return "اكتب حرف واحد على الأقل للبحث";
                        },
                        inputTooLong: function() {
                            return "النص طويل جداً";
                        },
                        loadingMore: function() {
                            return "جاري تحميل المزيد...";
                        },
                        maximumSelected: function() {
                            return "لا يمكنك اختيار أكثر من عنصر واحد";
                        }
                    }
                });
                return;
            }

            // Fetch wives for the selected father using the correct route
            fetch(`/api/person/${fatherId}/wives`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok.');
                    }
                    return response.json();
                })
                .then(wives => {
                    $('#mother_id').empty().append('<option value="">-- اختر الأم --</option>');
                    if (wives.length > 0) {
                        wives.forEach(wife => {
                            $('#mother_id').append(`<option value="${wife.id}">${wife.full_name}</option>`);
                        });
                    } else {
                        $('#mother_id').append('<option value="">-- لا يوجد زوجات لهذا الأب --</option>');
                    }

                    // Reinitialize Select2 with new options
                    $('#mother_id').select2('destroy').select2({
                        placeholder: 'ابحث عن الأم...',
                        allowClear: true,
                        width: '100%',
                        dropdownAutoWidth: true,
                        language: {
                            noResults: function() {
                                return "لا توجد نتائج";
                            },
                            searching: function() {
                                return "جاري البحث...";
                            },
                            inputTooShort: function() {
                                return "اكتب حرف واحد على الأقل للبحث";
                            },
                            inputTooLong: function() {
                                return "النص طويل جداً";
                            },
                            loadingMore: function() {
                                return "جاري تحميل المزيد...";
                            },
                            maximumSelected: function() {
                                return "لا يمكنك اختيار أكثر من عنصر واحد";
                            }
                        },
                        templateResult: function(person) {
                            if (person.loading) {
                                return person.text;
                            }
                            return $('<div class="select2-result-item">' + person.text + '</div>');
                        },
                        templateSelection: function(person) {
                            return $('<div class="select2-selection-item">' + person.text + '</div>');
                        }
                    });
                })
                .catch(error => {
                    console.error('Error fetching wives:', error);
                    $('#mother_id').empty().append('<option value="">-- حدث خطأ في التحميل --</option>');
                    $('#mother_id').select2('destroy').select2({
                        placeholder: 'حدث خطأ في التحميل...',
                        allowClear: true,
                        width: '100%',
                        dropdownAutoWidth: true,
                        language: {
                            noResults: function() {
                                return "لا توجد نتائج";
                            },
                            searching: function() {
                                return "جاري البحث...";
                            },
                            inputTooShort: function() {
                                return "اكتب حرف واحد على الأقل للبحث";
                            },
                            inputTooLong: function() {
                                return "النص طويل جداً";
                            },
                            loadingMore: function() {
                                return "جاري تحميل المزيد...";
                            },
                            maximumSelected: function() {
                                return "لا يمكنك اختيار أكثر من عنصر واحد";
                            }
                        }
                    });
                });
        });
    }
    // =================================================================
    //  END: Wives Fetching Logic
    // =================================================================

    // Initialize first step
    showStep(0);

    // Location Autocomplete
    const locationInput = document.getElementById('location');
    const locationIdInput = document.getElementById('location_id');
    const suggestionsDiv = document.getElementById('location_suggestions');
    let searchTimeout;

    if (locationInput && locationIdInput && suggestionsDiv) {
        locationInput.addEventListener('input', function() {
            const query = this.value.trim();

            clearTimeout(searchTimeout);
            
            if (query.length < 2) {
                suggestionsDiv.style.display = 'none';
                locationIdInput.value = '';
                return;
            }

            searchTimeout = setTimeout(function() {
                fetch('{{ route("locations.autocomplete") }}?q=' + encodeURIComponent(query))
                    .then(response => response.json())
                    .then(data => {
                        suggestionsDiv.innerHTML = '';
                        
                        if (data.length === 0) {
                            suggestionsDiv.innerHTML = '<div class="list-group-item text-muted">لا توجد نتائج</div>';
                            suggestionsDiv.style.display = 'block';
                            return;
                        }

                        data.forEach(function(location) {
                            const item = document.createElement('div');
                            item.className = 'list-group-item list-group-item-action';
                            item.style.cursor = 'pointer';
                            item.style.padding = '10px';
                            item.style.borderBottom = '1px solid #eee';
                            item.innerHTML = `
                                <strong>${location.name}</strong>
                                ${location.persons_count > 0 ? `<small class="text-muted">(${location.persons_count} شخص)</small>` : ''}
                            `;
                            
                            item.addEventListener('click', function() {
                                locationInput.value = location.name;
                                locationIdInput.value = location.id;
                                suggestionsDiv.style.display = 'none';
                            });
                            
                            suggestionsDiv.appendChild(item);
                        });
                        
                        suggestionsDiv.style.display = 'block';
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }, 300);
        });

        // إخفاء الاقتراحات عند النقر خارجها
        document.addEventListener('click', function(e) {
            if (!locationInput.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                suggestionsDiv.style.display = 'none';
            }
        });
    }
});
</script>

        </div>
    </div>
</body>
</html>
