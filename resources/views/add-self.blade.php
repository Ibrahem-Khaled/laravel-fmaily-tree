<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة نفسك إلى شجرة العائلة</title>

    <!-- Tailwind CSS for styling -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts (Cairo for Arabic) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Custom styles to enhance the design */
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f0f4f8; /* Light blue-gray background */
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
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4" dir="rtl">

    <div class="form-container w-full max-w-3xl p-6 sm:p-10">

        <!-- Form Header -->
        <div class="text-center mb-8">
            <i class="fas fa-tree text-4xl text-blue-600 mb-2"></i>
            <h1 class="text-3xl font-bold text-gray-800">أضف نفسك إلى شجرة العائلة</h1>
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
                <div class="flex justify-end mt-8">
                    <button type="button" class="btn-primary next-btn">التالي <i class="fas fa-arrow-left mr-2"></i></button>
                </div>
            </div>

            <!-- Step 2: Family Links -->
            <div class="form-step" id="step2">
                <h2 class="text-xl font-bold text-center mb-6 text-gray-700">2. روابط العائلة</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="parent_id" class="form-label">الأب</label>
                        <select class="form-input" id="parent_id" name="parent_id">
                            <option value="">-- اختر الأب --</option>
                            {{-- This part should be populated by Laravel --}}
                            @foreach ($males as $father)
                                <option value="{{ $father->id }}" @selected(old('parent_id') == $father->id)>
                                    {{ $father->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="mother_id" class="form-label">الأم</label>
                        <select class="form-input" id="mother_id" name="mother_id">
                            <option value="">-- اختر الأب أولاً --</option>
                             {{-- This part will be populated by JavaScript --}}
                        </select>
                    </div>
                </div>
                <div class="flex justify-between mt-8">
                    <button type="button" class="btn-secondary prev-btn"><i class="fas fa-arrow-right ml-2"></i> السابق</button>
                    <button type="button" class="btn-primary next-btn">التالي <i class="fas fa-arrow-left mr-2"></i></button>
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
                        <input type="text" class="form-input" id="location" name="location" value="{{ old('location') }}">
                    </div>
                </div>
                <div>
                    <label for="biography" class="form-label">سيرة ذاتية</label>
                    <textarea class="form-input" id="biography" name="biography" rows="4" placeholder="اكتب نبذة قصيرة عنك...">{{ old('biography') }}</textarea>
                </div>
                <div class="flex justify-between mt-8">
                    <button type="button" class="btn-secondary prev-btn"><i class="fas fa-arrow-right ml-2"></i> السابق</button>
                    <button type="button" class="btn-primary next-btn">التالي <i class="fas fa-arrow-left mr-2"></i></button>
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
                <div class="flex justify-between mt-8">
                    <button type="button" class="btn-secondary prev-btn"><i class="fas fa-arrow-right ml-2"></i> السابق</button>
                    <button type="submit" class="btn-primary">حفظ وإضافة للشجرة <i class="fas fa-check ml-2"></i></button>
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

    // =================================================================
    //  START: Wives Fetching Logic (Corrected and Integrated)
    // =================================================================
    const fatherSelect = document.getElementById('parent_id');
    const motherSelect = document.getElementById('mother_id');

    if (fatherSelect && motherSelect) {
        fatherSelect.addEventListener('change', function () {
            const fatherId = this.value;

            // Reset mother dropdown and show loading state
            motherSelect.innerHTML = '<option value="">-- جار التحميل --</option>';

            if (!fatherId) {
                motherSelect.innerHTML = '<option value="">-- اختر الأب أولاً --</option>';
                return;
            }

            // Fetch wives for the selected father
            // Make sure you have a route like: Route::get('/people/{person}/wives', [YourController::class, 'getWives']);
            fetch(`/people/${fatherId}/wives`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok.');
                    }
                    return response.json();
                })
                .then(wives => {
                    motherSelect.innerHTML = '<option value="">-- اختر الأم --</option>';
                    if (wives.length > 0) {
                        wives.forEach(wife => {
                            const option = document.createElement('option');
                            option.value = wife.id;
                            option.textContent = wife.full_name; // Assuming the JSON response has 'id' and 'full_name'
                            motherSelect.appendChild(option);
                        });
                    } else {
                        motherSelect.innerHTML = '<option value="">-- لا يوجد زوجات لهذا الأب --</option>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching wives:', error);
                    motherSelect.innerHTML = '<option value="">-- حدث خطأ في التحميل --</option>';
                });
        });
    }
    // =================================================================
    //  END: Wives Fetching Logic
    // =================================================================

    // Initialize first step
    showStep(0);
});
</script>

</body>
</html>
