@extends('layouts.app')

@section('title', 'إضافة سؤال')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 mb-2 text-gray-800">
                    <i class="fas fa-plus-circle text-primary mr-2"></i>إضافة سؤال - {{ $quizCompetition->title }}
                </h1>
                <p class="text-muted mb-0">قم بإضافة سؤال جديد للمسابقة</p>
            </div>
            <a href="{{ route('dashboard.quiz-competitions.show', $quizCompetition) }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-right mr-2"></i>العودة للمسابقة
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="{{ route('dashboard.quiz-questions.store', $quizCompetition) }}" method="POST"
                    id="questionForm" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="question_text">نص السؤال <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('question_text') is-invalid @enderror" id="question_text"
                            name="question_text" rows="4" required>{{ old('question_text') }}</textarea>
                        @error('question_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">الوصف</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                            name="description" rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group" id="prizesContainerGroup">
                        <label>الجوائز (تظهر لكل مركز حسب الترتيب)</label>
                        <div id="prizesContainer" class="mb-3"></div>
                        <p class="text-muted small">سيتم إنشاء حقول الجوائز تلقائياً بناءً على "عدد الفائزين".</p>
                    </div>

                    <div class="form-group">
                        <label for="answer_type">نوع الإجابة <span class="text-danger">*</span></label>
                        <div class="d-flex gap-4">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="type_multiple_choice" name="answer_type"
                                    class="custom-control-input answer-type-radio" value="multiple_choice"
                                    {{ old('answer_type', 'multiple_choice') == 'multiple_choice' ? 'checked' : '' }} required>
                                <label class="custom-control-label" for="type_multiple_choice">اختيار من متعدد</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="type_ordering" name="answer_type"
                                    class="custom-control-input answer-type-radio" value="ordering"
                                    {{ old('answer_type') == 'ordering' ? 'checked' : '' }} required>
                                <label class="custom-control-label" for="type_ordering">ترتيب (سحب وإفلات)</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="type_custom_text" name="answer_type"
                                    class="custom-control-input answer-type-radio" value="custom_text"
                                    {{ old('answer_type') == 'custom_text' ? 'checked' : '' }} required>
                                <label class="custom-control-label" for="type_custom_text">إجابة حرة (نص)</label>
                            </div>
                        </div>
                        @error('answer_type')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group" id="multipleSelectionsGroup">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_multiple_selections"
                                name="is_multiple_selections" value="1" {{ old('is_multiple_selections') ? 'checked' : '' }}>
                            <label class="custom-control-label font-weight-bold text-primary"
                                for="is_multiple_selections">السؤال يقبل إجابات صحيحة متعددة؟</label>
                        </div>
                    </div>

                    <div id="choicesSection" class="form-group">
                        <label>الخيارات <span class="text-danger">*</span></label>
                        <p class="text-muted small" id="choicesHint">حدد الخيار الصحيح بعلامة ✓</p>
                        <p class="text-info small" id="singleChoiceHint" style="display:none;">
                            <i class="fas fa-info-circle mr-1"></i>
                            عند ترك خيار واحد فقط، سيظهر للمستخدم كزر واحد في صفحة السؤال والرئيسية.
                        </p>
                        <div id="choicesContainer">
                            @for($i = 0; $i < 4; $i++)
                                <div class="input-group mb-2 choice-row">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text correct-choice-container">
                                            <input type="radio" class="correct-choice-input" name="correct_choice"
                                                value="{{ $i }}" {{ $i == 0 ? 'checked' : '' }} title="الإجابة الصحيحة">
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="choices[{{ $i }}][text]"
                                        placeholder="نص الخيار {{ $i + 1 }}" value="{{ old("choices.{$i}.text") }}">
                                    <div class="input-group-append">
                                        <label class="btn btn-outline-info mb-0" title="رفع صورة">
                                            <i class="fas fa-image"></i>
                                            <input type="file" name="choices[{{ $i }}][image]" class="choice-image-input d-none" accept="image/*">
                                        </label>
                                        <label class="btn btn-outline-info mb-0" title="رفع فيديو">
                                            <i class="fas fa-video"></i>
                                            <input type="file" name="choices[{{ $i }}][video]" class="choice-video-input d-none" accept="video/*">
                                        </label>
                                    </div>
                                    <input type="hidden" name="choices[{{ $i }}][is_correct]" value="{{ $i == 0 ? '1' : '0' }}" class="choice-correct">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-danger remove-choice"><i class="fas fa-trash"></i></button>
                                    </div>
                                </div>
                                <div class="choice-media-preview mb-2 px-3 small text-muted" id="preview-{{ $i }}"></div>
                            @endfor
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="addChoice">
                            <i class="fas fa-plus mr-1"></i>إضافة خيار
                        </button>
                    </div>

                    <div class="alert alert-info small">
                        <i class="fas fa-info-circle mr-2"></i>وقت بداية ونهاية الأسئلة يحدد من المسابقة نفسها في
                        <a href="{{ route('dashboard.quiz-competitions.edit', $quizCompetition) }}" class="alert-link">تعديل المسابقة</a>.
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="winners_count">عدد الفائزين <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('winners_count') is-invalid @enderror"
                                    id="winners_count" name="winners_count" value="{{ old('winners_count', 1) }}" min="1" required>
                                @error('winners_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="display_order">ترتيب العرض</label>
                                <input type="number" class="form-control @error('display_order') is-invalid @enderror"
                                    id="display_order" name="display_order" value="{{ old('display_order', 0) }}" min="0">
                                @error('display_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>حفظ السؤال
                        </button>
                        <a href="{{ route('dashboard.quiz-competitions.show', $quizCompetition) }}" class="btn btn-secondary">
                            <i class="fas fa-times mr-2"></i>إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.js"></script>
    <script>
        $(document).ready(function () {
            // Summernote
            if ($('#description').length) {
                $('#description').summernote({
                    height: 200,
                    direction: 'rtl',
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ]
                });
            }

            var choicesContainer = document.getElementById('choicesContainer');
            var choicesSection = document.getElementById('choicesSection');
            var multipleSelectionsGroup = document.getElementById('multipleSelectionsGroup');
            var isMultipleSwitch = document.getElementById('is_multiple_selections');
            var addChoiceBtn = document.getElementById('addChoice');
            var singleChoiceHint = document.getElementById('singleChoiceHint');
            var sortableInstance = null;

            function getSelectedAnswerType() {
                var checked = document.querySelector('.answer-type-radio:checked');
                return checked ? checked.value : 'multiple_choice';
            }

            function updateSingleChoiceHint() {
                var rows = choicesContainer.querySelectorAll('.choice-row');
                var type = getSelectedAnswerType();
                if (singleChoiceHint) {
                    singleChoiceHint.style.display = (type === 'multiple_choice' && !isMultipleSwitch.checked && rows.length === 1) ? 'block' : 'none';
                }
            }

            function toggleChoices() {
                var type = getSelectedAnswerType();
                var isChoice = type === 'multiple_choice';
                var isOrdering = type === 'ordering';

                choicesSection.style.display = (isChoice || isOrdering) ? 'block' : 'none';
                multipleSelectionsGroup.style.display = isChoice ? 'block' : 'none';

                var hint = document.getElementById('choicesHint');
                hint.textContent = isOrdering
                    ? 'أدخل الخيارات بالترتيب الصحيح، ستظهر للمستخدم بشكل عشوائي ليرتبها.'
                    : 'حدد الخيار الصحيح بعلامة ✓';

                updateChoiceInputsType();
                updateSingleChoiceHint();
            }

            function updateChoiceInputsType() {
                var type = getSelectedAnswerType();
                var isOrdering = type === 'ordering';
                var isMultiple = isMultipleSwitch.checked && !isOrdering;
                var rows = choicesContainer.querySelectorAll('.choice-row');

                rows.forEach(function (row, idx) {
                    var container = row.querySelector('.correct-choice-container');
                    var hidden = row.querySelector('.choice-correct');
                    var wasChecked = hidden.value === '1';

                    if (isOrdering) {
                        container.innerHTML = '<i class="fas fa-grip-lines drag-handle text-muted mr-2" style="cursor:move;" title="اسحب للترتيب"></i><span class="badge badge-info">' + (idx + 1) + '</span>';
                        hidden.value = '1';
                        row.classList.add('is-ordering');
                    } else {
                        row.classList.remove('is-ordering');
                        if (isMultiple) {
                            container.innerHTML = '<input type="checkbox" class="correct-choice-input" name="correct_choices[]" value="' + idx + '" ' + (wasChecked ? 'checked' : '') + ' title="إجابة صحيحة">';
                        } else {
                            container.innerHTML = '<input type="radio" class="correct-choice-input" name="correct_choice" value="' + idx + '" ' + (wasChecked ? 'checked' : '') + ' title="الإجابة الصحيحة">';
                        }
                    }
                });

                if (!isOrdering) attachChoiceListeners();
            }

            function attachChoiceListeners() {
                choicesContainer.querySelectorAll('.correct-choice-input').forEach(function (input) {
                    input.addEventListener('change', updateHiddenValues);
                });
            }

            function updateHiddenValues() {
                var type = getSelectedAnswerType();
                var isOrdering = type === 'ordering';
                var isMultiple = isMultipleSwitch.checked && !isOrdering;
                var rows = choicesContainer.querySelectorAll('.choice-row');

                rows.forEach(function (row, idx) {
                    var hidden = row.querySelector('.choice-correct');
                    if (isOrdering) {
                        hidden.value = '1';
                    } else if (isMultiple) {
                        var input = row.querySelector('.correct-choice-input');
                        if (input) hidden.value = input.checked ? '1' : '0';
                    }
                });

                if (!isMultiple && !isOrdering) {
                    document.querySelectorAll('.choice-correct').forEach(function (h, i) {
                        var r = document.querySelector('input[name="correct_choice"][value="' + i + '"]');
                        if (r) h.value = r.checked ? '1' : '0';
                    });
                }
            }

            function toggleSortable() {
                if (getSelectedAnswerType() === 'ordering') {
                    if (!sortableInstance) {
                        sortableInstance = new Sortable(choicesContainer, {
                            animation: 150,
                            handle: '.drag-handle',
                            ghostClass: 'bg-light',
                            onEnd: function () { reindexChoices(); }
                        });
                    }
                } else {
                    if (sortableInstance) { sortableInstance.destroy(); sortableInstance = null; }
                }
            }

            function removeChoiceRow(row) {
                if (choicesContainer.querySelectorAll('.choice-row').length <= 1) return;
                var preview = row.nextElementSibling;
                row.remove();
                if (preview && preview.classList.contains('choice-media-preview')) preview.remove();
                reindexChoices();
                updateSingleChoiceHint();
            }

            function reindexChoices() {
                var rows = choicesContainer.querySelectorAll('.choice-row');
                var isOrdering = getSelectedAnswerType() === 'ordering';
                rows.forEach(function (row, idx) {
                    var input = row.querySelector('.correct-choice-input');
                    var container = row.querySelector('.correct-choice-container');
                    var hidden = row.querySelector('.choice-correct');
                    var textInput = row.querySelector('input[type="text"]');
                    var imageInput = row.querySelector('.choice-image-input');
                    var videoInput = row.querySelector('.choice-video-input');

                    if (isOrdering) {
                        container.innerHTML = '<i class="fas fa-grip-lines drag-handle text-muted mr-2" style="cursor:move;" title="اسحب للترتيب"></i><span class="badge badge-info">' + (idx + 1) + '</span>';
                        hidden.value = '1';
                    } else if (input) {
                        input.value = idx;
                    }

                    textInput.name = 'choices[' + idx + '][text]';
                    hidden.name = 'choices[' + idx + '][is_correct]';
                    if (imageInput) imageInput.name = 'choices[' + idx + '][image]';
                    if (videoInput) videoInput.name = 'choices[' + idx + '][video]';
                });

                if (!isOrdering) attachChoiceListeners();
            }

            function attachMediaListeners(container) {
                container = container || choicesContainer;
                container.querySelectorAll('.choice-image-input, .choice-video-input').forEach(function (input) {
                    input.addEventListener('change', function () {
                        var row = this.closest('.choice-row');
                        var preview = row.nextElementSibling;
                        if (!preview || !preview.classList.contains('choice-media-preview')) {
                            preview = document.createElement('div');
                            preview.className = 'choice-media-preview mb-2 px-3 small text-muted';
                            row.after(preview);
                        }
                        if (this.files && this.files[0]) {
                            var fileName = this.files[0].name;
                            var mediaType = this.classList.contains('choice-image-input') ? 'صورة' : 'فيديو';
                            var icon = mediaType === 'صورة' ? 'image' : 'video';
                            preview.innerHTML = '<span class="badge badge-light border"><i class="fas fa-' + icon + ' mr-1"></i> ' + mediaType + ': ' + fileName + '</span>';
                        } else {
                            preview.innerHTML = '';
                        }
                    });
                });
            }

            // Bind answer type change
            document.querySelectorAll('.answer-type-radio').forEach(function (radio) {
                radio.addEventListener('change', function () {
                    toggleChoices();
                    toggleSortable();
                });
            });
            isMultipleSwitch.addEventListener('change', function () {
                updateChoiceInputsType();
                updateSingleChoiceHint();
            });

            // Bind existing remove buttons
            choicesContainer.querySelectorAll('.remove-choice').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    removeChoiceRow(btn.closest('.choice-row'));
                });
            });

            // Add choice button
            addChoiceBtn.addEventListener('click', function () {
                var idx = choicesContainer.querySelectorAll('.choice-row').length;
                var type = getSelectedAnswerType();
                var isOrdering = type === 'ordering';
                var isMultiple = isMultipleSwitch.checked && !isOrdering;

                var inputHtml = '';
                if (isOrdering) {
                    inputHtml = '<i class="fas fa-grip-lines drag-handle text-muted mr-2" style="cursor:move;" title="اسحب للترتيب"></i><span class="badge badge-info">' + (idx + 1) + '</span>';
                } else if (isMultiple) {
                    inputHtml = '<input type="checkbox" class="correct-choice-input" name="correct_choices[]" value="' + idx + '" title="إجابة صحيحة">';
                } else {
                    inputHtml = '<input type="radio" class="correct-choice-input" name="correct_choice" value="' + idx + '" title="الإجابة الصحيحة">';
                }

                var div = document.createElement('div');
                div.className = 'input-group mb-2 choice-row';
                div.innerHTML =
                    '<div class="input-group-prepend"><div class="input-group-text correct-choice-container">' + inputHtml + '</div></div>' +
                    '<input type="text" class="form-control" name="choices[' + idx + '][text]" placeholder="نص الخيار">' +
                    '<div class="input-group-append">' +
                        '<label class="btn btn-outline-info mb-0" title="رفع صورة"><i class="fas fa-image"></i><input type="file" name="choices[' + idx + '][image]" class="choice-image-input d-none" accept="image/*"></label>' +
                        '<label class="btn btn-outline-info mb-0" title="رفع فيديو"><i class="fas fa-video"></i><input type="file" name="choices[' + idx + '][video]" class="choice-video-input d-none" accept="video/*"></label>' +
                    '</div>' +
                    '<input type="hidden" name="choices[' + idx + '][is_correct]" value="0" class="choice-correct">' +
                    '<div class="input-group-append"><button type="button" class="btn btn-outline-danger remove-choice"><i class="fas fa-trash"></i></button></div>';

                choicesContainer.appendChild(div);

                var mediaPreviewDiv = document.createElement('div');
                mediaPreviewDiv.className = 'choice-media-preview mb-2 px-3 small text-muted';
                div.after(mediaPreviewDiv);

                attachChoiceListeners();
                attachMediaListeners(div);
                div.querySelector('.remove-choice').addEventListener('click', function () {
                    removeChoiceRow(div);
                });
                updateSingleChoiceHint();
            });

            // Prizes
            var winnersCountInput = document.getElementById('winners_count');
            var prizesContainer = document.getElementById('prizesContainer');
            var oldPrizes = @json(old('prize', []));

            function updatePrizeInputs() {
                var count = parseInt(winnersCountInput.value) || 0;
                var existingValues = [];
                prizesContainer.querySelectorAll('input').forEach(function (input) {
                    existingValues.push(input.value);
                });
                prizesContainer.innerHTML = '';
                for (var i = 0; i < count; i++) {
                    var label = i === 0 ? 'المركز الأول' : (i === 1 ? 'المركز الثاني' : (i === 2 ? 'المركز الثالث' : 'المركز ' + (i + 1)));
                    var placeholder = i === 0 ? 'مثال: آيفون 15' : 'الجائزة';
                    var value = (existingValues[i] !== undefined) ? existingValues[i] : (oldPrizes[i] || '');
                    var div = document.createElement('div');
                    div.className = 'input-group mb-2';
                    div.innerHTML = '<div class="input-group-prepend"><span class="input-group-text">' + label + '</span></div>' +
                        '<input type="text" name="prize[]" class="form-control" placeholder="' + placeholder + '" value="' + value + '">';
                    prizesContainer.appendChild(div);
                }
            }

            winnersCountInput.addEventListener('input', updatePrizeInputs);
            updatePrizeInputs();

            // Submit
            document.getElementById('questionForm').addEventListener('submit', function () {
                updateHiddenValues();
            });

            // Initial state
            attachChoiceListeners();
            attachMediaListeners();
            toggleChoices();
            toggleSortable();
        });
    </script>
@endpush
