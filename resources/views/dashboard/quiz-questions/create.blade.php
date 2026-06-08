@extends('layouts.app')

@section('title', 'إضافة بند / سؤال')

@push('styles')
@include('dashboard.quiz-competitions._styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <span class="badge badge-light border px-2 py-1 text-secondary mb-2">استطلاع الرأي الحالي</span>
            <h1 class="h2 mb-1 text-gray-900 font-weight-bold">
                <i class="fas fa-plus-circle text-teal mr-2"></i>إضافة بند / سؤال جديد
            </h1>
            <p class="text-muted mb-0">استطلاع: <strong>{{ $quizCompetition->title }}</strong></p>
        </div>
        <a href="{{ route('dashboard.quiz-competitions.show', $quizCompetition) }}" class="modern-btn modern-btn-secondary text-decoration-none">
            <i class="fas fa-arrow-right"></i>
            <span>العودة للاستطلاع</span>
        </a>
    </div>

    <!-- Main Card -->
    <div class="card modern-card">
        <div class="card-body p-4 p-lg-5">
            <form action="{{ route('dashboard.quiz-questions.store', $quizCompetition) }}" method="POST" id="questionForm" enctype="multipart/form-data">
                @csrf

                <div class="row justify-content-center">
                    <div class="col-lg-10">

                        <!-- Question Text -->
                        <div class="form-group mb-4">
                            <label for="question_text" class="modern-label">نص البند / السؤال <span class="text-danger">*</span></label>
                            <textarea class="form-control modern-input @error('question_text') is-invalid @enderror" id="question_text"
                                name="question_text" rows="3" required placeholder="اكتب نص السؤال أو العبارة هنا...">{{ old('question_text') }}</textarea>
                            @error('question_text')
                                <div class="invalid-feedback font-weight-bold mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description (Summernote) -->
                        <div class="form-group mb-4">
                            <label for="description" class="modern-label">شرح إضافي أو تلميح (اختياري)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                                name="description" rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback font-weight-bold mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Dynamic Prizes Container -->
                        <div class="form-group mb-4 p-4 rounded-lg bg-light" id="prizesContainerGroup" style="border-right: 4px solid #eab308;">
                            <label class="modern-label text-warning mb-2">
                                <i class="fas fa-gift mr-1"></i> جوائز الفائزين (حسب مراكز السحب)
                            </label>
                            <div id="prizesContainer" class="mb-3"></div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle mr-1"></i> سيتم توليد حقول الجوائز ديناميكياً بناءً على حقل "عدد الفائزين" في الأسفل.
                            </small>
                        </div>

                        <!-- Answer Type (Pill Selectable) -->
                        <div class="form-group mb-4">
                            <label for="answer_type" class="modern-label">نوع البند / طريقة التصويت والرد <span class="text-danger">*</span></label>
                            <div class="answer-type-container mt-2">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="type_multiple_choice" name="answer_type"
                                        class="custom-control-input answer-type-radio" value="multiple_choice"
                                        {{ old('answer_type', 'multiple_choice') == 'multiple_choice' ? 'checked' : '' }} required>
                                    <label class="custom-control-label" for="type_multiple_choice">
                                        <i class="fas fa-list-ul mb-1" style="font-size:1.2rem;"></i>
                                        <span>اختيار من متعدد</span>
                                    </label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="type_ordering" name="answer_type"
                                        class="custom-control-input answer-type-radio" value="ordering"
                                        {{ old('answer_type') == 'ordering' ? 'checked' : '' }} required>
                                    <label class="custom-control-label" for="type_ordering">
                                        <i class="fas fa-sort-amount-down mb-1" style="font-size:1.2rem;"></i>
                                        <span>ترتيب العناصر</span>
                                    </label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="type_true_false" name="answer_type"
                                        class="custom-control-input answer-type-radio" value="true_false"
                                        {{ old('answer_type') == 'true_false' ? 'checked' : '' }} required>
                                    <label class="custom-control-label" for="type_true_false">
                                        <i class="fas fa-check-double mb-1" style="font-size:1.2rem;"></i>
                                        <span>صح أم خطأ</span>
                                    </label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="type_custom_text" name="answer_type"
                                        class="custom-control-input answer-type-radio" value="custom_text"
                                        {{ old('answer_type') == 'custom_text' ? 'checked' : '' }} required>
                                    <label class="custom-control-label" for="type_custom_text">
                                        <i class="fas fa-keyboard mb-1" style="font-size:1.2rem;"></i>
                                        <span>إجابة حرة (نص)</span>
                                    </label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="type_vote" name="answer_type"
                                        class="custom-control-input answer-type-radio" value="vote"
                                        {{ old('answer_type') == 'vote' ? 'checked' : '' }} required>
                                    <label class="custom-control-label text-primary" for="type_vote">
                                        <i class="fas fa-vote-yea mb-1" style="font-size:1.2rem;"></i>
                                        <span>تصويت للرأي</span>
                                    </label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="type_fill_blank" name="answer_type"
                                        class="custom-control-input answer-type-radio" value="fill_blank"
                                        {{ old('answer_type') == 'fill_blank' ? 'checked' : '' }} required>
                                    <label class="custom-control-label text-warning" for="type_fill_blank">
                                        <i class="fas fa-pen-nib mb-1" style="font-size:1.2rem;"></i>
                                        <span>أملأ الفراغ</span>
                                    </label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="type_survey" name="answer_type"
                                        class="custom-control-input answer-type-radio" value="survey"
                                        {{ old('answer_type') == 'survey' ? 'checked' : '' }} required>
                                    <label class="custom-control-label text-success" for="type_survey">
                                        <i class="fas fa-poll mb-1" style="font-size:1.2rem;"></i>
                                        <span>تقييم استبيان</span>
                                    </label>
                                </div>
                            </div>
                            @error('answer_type')
                                <div class="invalid-feedback d-block font-weight-bold mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Vote Specific Config -->
                        <div class="form-group mb-4" id="voteOptionsGroup" style="display:none;">
                            <div class="card border-teal p-3" style="border-radius:16px; background-color:#f0fdf4;">
                                <label class="modern-label text-teal mb-2"><i class="fas fa-cog"></i> إعدادات خيارات التصويت</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="vote_max_selections" class="small font-weight-bold text-gray-700">الحد الأقصى للخيارات المسموح بالتصويت عليها</label>
                                        <input type="number" class="form-control modern-input @error('vote_max_selections') is-invalid @enderror"
                                            id="vote_max_selections" name="vote_max_selections"
                                            value="{{ old('vote_max_selections', 1) }}" min="1">
                                        <small class="text-muted mt-1 d-block">(1 = اختيار خيار واحد فقط، أكثر = إمكانية تحديد متعدد)</small>
                                        @error('vote_max_selections')
                                            <div class="invalid-feedback font-weight-bold mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Participation condition -->
                        <div class="form-group mb-4">
                            <div class="card p-3 border" style="border-radius:16px; background-color:#f8fafc;">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="require_prior_registration"
                                        name="require_prior_registration" value="1"
                                        {{ old('require_prior_registration') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="require_prior_registration">
                                        اشتراط تسجيل مسبق في شجرة العائلة للمشاركة
                                    </label>
                                </div>
                                <small class="text-muted mt-2 d-block">
                                    <i class="fas fa-info-circle mr-1"></i> عند تفعيله، لن يتم قبول الرد إلا إذا كان رقم هاتف المستخدم مسجلاً مسبقاً في قاعدة بيانات العائلة.
                                </small>
                            </div>
                        </div>

                        <!-- Ordering: Groups Count -->
                        <div class="form-group mb-4" id="groupsCountGroup" style="display:none;">
                            <label for="groups_count" class="modern-label">عدد مجموعات التصنيف والترتيب</label>
                            <input type="number" class="form-control modern-input @error('groups_count') is-invalid @enderror"
                                id="groups_count" name="groups_count" value="{{ old('groups_count') }}" min="0" placeholder="اتركه 0 أو فارغاً لترتيب خطي بسيط...">
                            <small class="form-text text-muted mt-1">يستخدم لتقسيم العناصر إلى مجموعات تصنيف بالسحب والإفلات.</small>
                            @error('groups_count')
                                <div class="invalid-feedback font-weight-bold mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Dynamic Survey Builder Section -->
                        <div class="form-group mb-4" id="surveySection" style="display:none;">
                            <div class="card border-teal shadow-sm overflow-hidden" style="border-radius:20px;">
                                <div class="card-header bg-teal text-white py-3">
                                    <h6 class="mb-0 font-weight-bold"><i class="fas fa-stream ml-2"></i>بناء بنود الاستبيان التقييمي</h6>
                                </div>
                                <div class="card-body p-4 bg-light">
                                    <p class="text-muted small mb-3">يمكنك إضافة بنود متعددة (نص، صورة، أو فيديو) وتحديد نوع استجابة المشارك لكل بند (تقييم بالنجوم، إدخال رقم، أو كتابة نص).</p>
                                    <div id="surveyItemsContainer"></div>
                                    <button type="button" class="modern-btn modern-btn-primary mt-2" id="addSurveyItem">
                                        <i class="fas fa-plus"></i>إضافة بند استبيان جديد
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Multiple choices selection switch -->
                        <div class="form-group mb-4" id="multipleSelectionsGroup">
                            <div class="card p-3 border" style="border-radius:16px;">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_multiple_selections"
                                        name="is_multiple_selections" value="1" {{ old('is_multiple_selections') ? 'checked' : '' }}>
                                    <label class="custom-control-label text-teal font-weight-bold" for="is_multiple_selections">السؤال يقبل إجابات صحيحة متعددة؟</label>
                                </div>
                            </div>
                        </div>

                        <!-- Choices List Builder -->
                        <div id="choicesSection" class="form-group mb-4">
                            <label class="modern-label">الخيارات وبنود الإجابة <span class="text-danger">*</span></label>
                            <div class="alert alert-info border-0 py-2 px-3 small mb-3" style="border-radius: 12px;" id="choicesHint">حدد الخيار الصحيح بعلامة ✓</div>
                            <div class="alert alert-warning border-0 py-2 px-3 small mb-3" id="singleChoiceHint" style="display:none; border-radius:12px;">
                                <i class="fas fa-info-circle mr-1"></i> عند الاكتفاء بخيار واحد، سيظهر للمستخدم كزر تأكيد للمشاركة.
                            </div>
                            
                            <!-- Choices Container -->
                            <div id="choicesContainer">
                                @for($i = 0; $i < 4; $i++)
                                    <div class="choice-row">
                                        <!-- Selector for Correct Choice -->
                                        <div class="correct-choice-container">
                                            <input type="radio" class="correct-choice-input" name="correct_choice"
                                                value="{{ $i }}" {{ $i == 0 ? 'checked' : '' }} title="الإجابة الصحيحة">
                                        </div>
                                        
                                        <!-- Choice Text -->
                                        <input type="text" class="form-control modern-input" name="choices[{{ $i }}][text]"
                                            placeholder="نص الخيار {{ $i + 1 }}" value="{{ old("choices.{$i}.text") }}" style="flex-grow:2;">
                                        
                                        <!-- Group Name (ordering only) -->
                                        <input type="text" class="form-control modern-input choice-group-name" name="choices[{{ $i }}][group_name]"
                                            placeholder="المجموعة" value="{{ old("choices.{$i}.group_name") }}" style="display:none; max-width: 20%;">
                                        
                                        <!-- Media upload buttons -->
                                        <div class="d-flex gap-1">
                                            <label class="btn btn-outline-info media-btn-label" title="رفع صورة للخيارات">
                                                <i class="fas fa-image"></i>
                                                <input type="file" name="choices[{{ $i }}][image]" class="choice-image-input d-none" accept="image/*">
                                            </label>
                                            <label class="btn btn-outline-info media-btn-label" title="رفع فيديو للخيارات">
                                                <i class="fas fa-video"></i>
                                                <input type="file" name="choices[{{ $i }}][video]" class="choice-video-input d-none" accept="video/*">
                                            </label>
                                        </div>
                                        
                                        <!-- Hidden values -->
                                        <input type="hidden" name="choices[{{ $i }}][is_correct]" value="{{ $i == 0 ? '1' : '0' }}" class="choice-correct">
                                        
                                        <!-- Delete row button -->
                                        <div>
                                            <button type="button" class="btn-remove-choice remove-choice" title="إزالة هذا الخيار"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </div>
                                    <div class="choice-media-preview mb-3 px-3 small text-muted font-weight-bold" id="preview-{{ $i }}"></div>
                                @endfor
                            </div>
                            
                            <button type="button" class="modern-btn modern-btn-secondary mt-2" id="addChoice">
                                <i class="fas fa-plus"></i>إضافة خيار جديد
                            </button>
                        </div>

                        <div class="alert alert-info border-0 py-2 px-3 small d-flex align-items-center mb-4" style="border-radius:12px; background-color:#eff6ff; color:#1d4ed8;">
                            <i class="fas fa-info-circle mr-2" style="font-size:1.1rem;"></i>
                            <span>وقت بدء ونهاية بنود الاستبيان يتبع صلاحية وقت الاستطلاع ككل. لتعديله انتقل إلى <a href="{{ route('dashboard.quiz-competitions.edit', $quizCompetition) }}" class="alert-link font-weight-bold">إعدادات الاستطلاع</a>.</span>
                        </div>

                        <!-- Bottom Config Inputs -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="form-group">
                                    <label for="winners_count" class="modern-label">عدد الفائزين المطلوب سحبهم (في حال القرعة) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control modern-input @error('winners_count') is-invalid @enderror"
                                        id="winners_count" name="winners_count" value="{{ old('winners_count', 1) }}" min="1" required>
                                    @error('winners_count')
                                        <div class="invalid-feedback font-weight-bold mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="display_order" class="modern-label">ترتيب عرض السؤال</label>
                                    <input type="number" class="form-control modern-input @error('display_order') is-invalid @enderror"
                                        id="display_order" name="display_order" value="{{ old('display_order', 0) }}" min="0">
                                    @error('display_order')
                                        <div class="invalid-feedback font-weight-bold mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex align-items-center gap-3 pt-3 border-top mt-4">
                            <button type="submit" class="modern-btn modern-btn-primary">
                                <i class="fas fa-check-circle"></i>
                                <span>حفظ البند / السؤال</span>
                            </button>
                            <a href="{{ route('dashboard.quiz-competitions.show', $quizCompetition) }}" class="modern-btn modern-btn-secondary text-decoration-none">
                                <i class="fas fa-times"></i>
                                <span>إلغاء وتراجع</span>
                            </a>
                        </div>

                    </div>
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
            // Initialize Summernote Description Editor
            if ($('#description').length) {
                $('#description').summernote({
                    placeholder: 'اكتب نصاً اختيارياً يوضح تفاصيل البند أو السؤال...',
                    height: 150,
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

            var surveySection = document.getElementById('surveySection');
            var surveyItemsContainer = document.getElementById('surveyItemsContainer');
            var surveyItemSeq = 0;

            function surveyRowTemplate(idx) {
                return '<div class="survey-item-row mb-3 p-3 border rounded bg-white" style="border-radius: 14px !important;">' +
                    '<input type="hidden" name="survey_items[' + idx + '][id]" value="">' +
                    '<div class="form-row">' +
                    '<div class="col-md-3 mb-2"><label class="small font-weight-bold">نوع العنصر</label>' +
                    '<select name="survey_items[' + idx + '][block_type]" class="form-control form-control-sm survey-block-type">' +
                    '<option value="text">نص / عبارة استبيان</option><option value="image">صورة</option><option value="video">فيديو</option></select></div>' +
                    '<div class="col-md-4 mb-2 survey-media-col" style="display:none"><label class="small font-weight-bold">رفع ملف</label>' +
                    '<input type="file" name="survey_items[' + idx + '][media]" class="form-control-file survey-media-input" accept="image/*,video/*">' +
                    '<div class="survey-youtube-col mt-2" style="display:none">' +
                    '<label class="small font-weight-bold">رابط فيديو يوتيوب URL</label>' +
                    '<input type="text" name="survey_items[' + idx + '][youtube_url]" class="form-control form-control-sm survey-youtube-input" placeholder="https://youtube.com/watch?v=...">' +
                    '</div>' +
                    '<div class="survey-youtube-preview mt-2 small text-muted" style="display:none"></div>' +
                    '</div>' +
                    '<div class="col-md-5 mb-2 survey-body-col"><label class="small font-weight-bold">نص البند (إلزامي للنصوص، اختياري للصور/الفيديو)</label>' +
                    '<textarea name="survey_items[' + idx + '][body_text]" class="form-control form-control-sm" rows="2"></textarea></div></div>' +
                    '<div class="form-row align-items-end">' +
                    '<div class="col-md-3 mb-2"><label class="small font-weight-bold">طريقة تقييم المشارك</label>' +
                    '<select name="survey_items[' + idx + '][response_kind]" class="form-control form-control-sm survey-response-kind">' +
                    '<option value="rating">تقييم بالنجوم (من 1 إلى N)</option><option value="number">رقم عددي (ضمن نطاق)</option><option value="text">نص حر تعبيري</option></select></div>' +
                    '<div class="col-md-2 mb-2 survey-rating-wrap"><label class="small">الحد الأقصى للنجوم</label>' +
                    '<input type="number" name="survey_items[' + idx + '][rating_max]" value="10" min="2" max="100" class="form-control form-control-sm"></div>' +
                    '<div class="col-md-4 mb-2 survey-number-wrap" style="display:none"><label class="small">النطاق المسموح (من — إلى)</label>' +
                    '<div class="input-group input-group-sm"><input type="number" name="survey_items[' + idx + '][number_min]" class="form-control" placeholder="من" value="0">' +
                    '<input type="number" name="survey_items[' + idx + '][number_max]" class="form-control" placeholder="إلى" value="100"></div></div>' +
                    '<div class="col-md-2 mb-2 text-left"><button type="button" class="btn btn-sm btn-outline-danger remove-survey-item mt-3" style="border-radius: 8px;"><i class="fas fa-trash"></i> إزالة</button></div></div></div>';
            }

            function bindSurveyRow(row) {
                var block = row.querySelector('.survey-block-type');
                var resp = row.querySelector('.survey-response-kind');
                var youtubeCol = row.querySelector('.survey-youtube-col');
                var youtubeInput = row.querySelector('.survey-youtube-input');
                var youtubePreview = row.querySelector('.survey-youtube-preview');

                function updBlock() {
                    var v = block.value;
                    row.querySelector('.survey-media-col').style.display = (v === 'text') ? 'none' : 'block';
                    if (youtubeCol) youtubeCol.style.display = (v === 'video') ? 'block' : 'none';
                    if (youtubePreview) youtubePreview.style.display = 'none';
                    if (v === 'video' && youtubeInput) youtubeInput.dispatchEvent(new Event('input'));
                }
                function updResp() {
                    var r = resp.value;
                    row.querySelector('.survey-rating-wrap').style.display = r === 'rating' ? 'block' : 'none';
                    row.querySelector('.survey-number-wrap').style.display = r === 'number' ? 'block' : 'none';
                }

                function extractYouTubeId(url) {
                    var pattern = /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/|youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/;
                    var m = (url || '').match(pattern);
                    return m ? m[1] : null;
                }

                if (youtubeInput) {
                    youtubeInput.addEventListener('input', function () {
                        if (!youtubePreview) return;
                        var id = extractYouTubeId(this.value.trim());
                        if (!id) {
                            youtubePreview.style.display = 'none';
                            youtubePreview.innerHTML = '';
                            return;
                        }
                        youtubePreview.style.display = 'block';
                        youtubePreview.innerHTML =
                            '<img src="https://img.youtube.com/vi/' + id + '/maxresdefault.jpg"' +
                            ' style="max-height:90px; max-width:220px; object-fit:contain"' +
                            ' class="img-thumbnail bg-white p-1 mt-2 d-block" alt="صورة يوتيوب">';
                    });
                }

                block.addEventListener('change', updBlock);
                resp.addEventListener('change', updResp);
                row.querySelector('.remove-survey-item').addEventListener('click', function () {
                    if (surveyItemsContainer.querySelectorAll('.survey-item-row').length <= 1) return;
                    row.remove();
                });
                updBlock();
                updResp();
                if (youtubeInput) youtubeInput.dispatchEvent(new Event('input'));
            }

            function appendSurveyRow() {
                var idx = surveyItemSeq++;
                var div = document.createElement('div');
                div.innerHTML = surveyRowTemplate(idx);
                var row = div.firstElementChild;
                surveyItemsContainer.appendChild(row);
                bindSurveyRow(row);
            }

            function toggleChoices() {
                var type = getSelectedAnswerType();
                var isChoice = type === 'multiple_choice';
                var isOrdering = type === 'ordering';
                var isTrueFalse = type === 'true_false';
                var isVote = type === 'vote';
                var isFillBlank = type === 'fill_blank';
                var isSurvey = type === 'survey';

                if (surveySection) {
                    surveySection.style.display = isSurvey ? 'block' : 'none';
                    if (!isSurvey && surveyItemsContainer) {
                        surveyItemsContainer.innerHTML = '';
                        surveyItemSeq = 0;
                    } else if (isSurvey && surveyItemsContainer && surveyItemsContainer.querySelectorAll('.survey-item-row').length === 0) {
                        appendSurveyRow();
                    }
                }

                choicesSection.style.display = (isChoice || isOrdering || isTrueFalse || isVote || isFillBlank) && !isSurvey ? 'block' : 'none';
                multipleSelectionsGroup.style.display = isChoice && !isSurvey ? 'block' : 'none';

                // Vote options
                var voteOptionsGroup = document.getElementById('voteOptionsGroup');
                if (voteOptionsGroup) voteOptionsGroup.style.display = (isVote && !isSurvey) ? 'block' : 'none';

                var hint = document.getElementById('choicesHint');
                if (isOrdering) {
                    hint.textContent = 'أدخل الخيارات بالترتيب الصحيح، ستظهر للمستخدم بشكل عشوائي ليرتبها بسحبها وإفلاتها.';
                } else if (isTrueFalse) {
                    hint.textContent = 'حدد صح أم خطأ لكل جملة وخيار مع إمكانية إرفاق صورة مع العبارة.';
                } else if (isVote) {
                    hint.textContent = 'أدخل بنود وخيارات التصويت والترشيح (لا توجد إجابات صحيحة في هذا النوع).';
                } else if (isFillBlank) {
                    hint.innerHTML = 'أدخل خيارات الكلمات لملء الفراغ، وحدد الكلمة <strong>الصحيحة ✓</strong>. ضع <code>___</code> (ثلاثة خطوط سفلية) في نص السؤال مكان الفراغ.';
                } else {
                    hint.textContent = 'حدد الخيار الصحيح للمشارك بعلامة ✓';
                }

                var groupsCountGroup = document.getElementById('groupsCountGroup');
                if (groupsCountGroup) groupsCountGroup.style.display = (isOrdering && !isSurvey) ? 'block' : 'none';

                var groupNameInputs = document.querySelectorAll('.choice-group-name');
                groupNameInputs.forEach(function(el) {
                    el.style.display = isOrdering ? 'block' : 'none';
                });

                updateChoiceInputsType();
                updateSingleChoiceHint();
            }

            function updateChoiceInputsType() {
                var type = getSelectedAnswerType();
                var isOrdering = type === 'ordering';
                var isTrueFalse = type === 'true_false';
                var isVote = type === 'vote';
                var isFillBlank = type === 'fill_blank';
                var isMultiple = isMultipleSwitch.checked && !isOrdering && !isTrueFalse && !isVote && !isFillBlank;
                var rows = choicesContainer.querySelectorAll('.choice-row');

                rows.forEach(function (row, idx) {
                    var container = row.querySelector('.correct-choice-container');
                    var hidden = row.querySelector('.choice-correct');
                    var wasChecked = hidden.value === '1' || hidden.value === 'true';

                    if (isOrdering) {
                        container.innerHTML = '<i class="fas fa-grip-lines drag-handle text-muted mr-2" style="cursor:move;" title="اسحب للترتيب"></i><span class="badge badge-info">' + (idx + 1) + '</span>';
                        hidden.value = '1';
                        row.classList.add('is-ordering');
                    } else {
                        row.classList.remove('is-ordering');
                        if (isTrueFalse) {
                            var checkedTrue = wasChecked ? 'checked' : '';
                            var checkedFalse = !wasChecked ? 'checked' : '';
                            container.innerHTML = '<div class="d-flex flex-column gap-1" style="min-width:60px;"><label class="mb-0 text-success small"><input type="radio" class="correct-choice-input" name="tf_choice_' + idx + '" value="1" ' + checkedTrue + '> صح</label><label class="mb-0 text-danger small"><input type="radio" class="correct-choice-input" name="tf_choice_' + idx + '" value="0" ' + checkedFalse + '> خطأ</label></div>';
                        } else if (isVote) {
                            container.innerHTML = '<span class="badge badge-primary"><i class="fas fa-poll"></i> ' + (idx + 1) + '</span>';
                            hidden.value = '0';
                        } else if (isMultiple) {
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
                var isTrueFalse = type === 'true_false';
                var isMultiple = isMultipleSwitch.checked && !isOrdering && !isTrueFalse;
                var rows = choicesContainer.querySelectorAll('.choice-row');

                rows.forEach(function (row, idx) {
                    var hidden = row.querySelector('.choice-correct');
                    if (isOrdering) {
                        hidden.value = '1';
                    } else if (isTrueFalse) {
                        var tfRadio = row.querySelector('input[type="radio"]:checked');
                        if (tfRadio) hidden.value = tfRadio.value;
                    } else if (isMultiple) {
                        var input = row.querySelector('.correct-choice-input');
                        if (input) hidden.value = input.checked ? '1' : '0';
                    }
                });

                if (!isMultiple && !isOrdering && !isTrueFalse) {
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
                var isVote = getSelectedAnswerType() === 'vote';
                rows.forEach(function (row, idx) {
                    var input = row.querySelector('.correct-choice-input');
                    var container = row.querySelector('.correct-choice-container');
                    var hidden = row.querySelector('.choice-correct');
                    var textInput = row.querySelector('input[type="text"]:not(.choice-group-name)');
                    var groupNameInput = row.querySelector('.choice-group-name');
                    var imageInput = row.querySelector('.choice-image-input');
                    var videoInput = row.querySelector('.choice-video-input');

                    if (isOrdering) {
                        container.innerHTML = '<i class="fas fa-grip-lines drag-handle text-muted mr-2" style="cursor:move;" title="اسحب للترتيب"></i><span class="badge badge-info">' + (idx + 1) + '</span>';
                        hidden.value = '1';
                    } else if (isVote) {
                        container.innerHTML = '<span class="badge badge-primary"><i class="fas fa-poll"></i> ' + (idx + 1) + '</span>';
                        hidden.value = '0';
                    } else if (getSelectedAnswerType() === 'true_false') {
                        var radios = container.querySelectorAll('.correct-choice-input');
                        radios.forEach(function (radio) {
                            radio.name = 'tf_choice_' + idx;
                        });
                    } else if (input) {
                        input.value = idx;
                    }

                    textInput.name = 'choices[' + idx + '][text]';
                    if (groupNameInput) groupNameInput.name = 'choices[' + idx + '][group_name]';
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
                            preview.className = 'choice-media-preview mb-2 px-3 small text-muted font-weight-bold';
                            row.after(preview);
                        }
                        if (this.files && this.files[0]) {
                            var fileName = this.files[0].name;
                            var mediaType = this.classList.contains('choice-image-input') ? 'صورة' : 'فيديو';
                            var icon = mediaType === 'صورة' ? 'image' : 'video';
                            preview.innerHTML = '<span class="badge badge-light border"><i class="fas fa-' + icon + ' mr-1 text-teal"></i> ' + mediaType + ': ' + fileName + '</span>';
                        } else {
                            preview.innerHTML = '';
                        }
                    });
                });
            }

            var addSurveyItemBtn = document.getElementById('addSurveyItem');
            if (addSurveyItemBtn) {
                addSurveyItemBtn.addEventListener('click', function () { appendSurveyRow(); });
            }

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

            choicesContainer.querySelectorAll('.remove-choice').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    removeChoiceRow(btn.closest('.choice-row'));
                });
            });

            addChoiceBtn.addEventListener('click', function () {
                var idx = choicesContainer.querySelectorAll('.choice-row').length;
                var type = getSelectedAnswerType();
                var isOrdering = type === 'ordering';
                var isTrueFalse = type === 'true_false';
                var isFillBlank = type === 'fill_blank';
                var isMultiple = isMultipleSwitch.checked && !isOrdering && !isTrueFalse && !isFillBlank;

                var inputHtml = '';
                if (isOrdering) {
                    inputHtml = '<i class="fas fa-grip-lines drag-handle text-muted mr-2" style="cursor:move;" title="اسحب للترتيب"></i><span class="badge badge-info">' + (idx + 1) + '</span>';
                } else if (isTrueFalse) {
                    inputHtml = '<div class="d-flex flex-column gap-1" style="min-width:60px;"><label class="mb-0 text-success small"><input type="radio" class="correct-choice-input" name="tf_choice_' + idx + '" value="1" checked> صح</label><label class="mb-0 text-danger small"><input type="radio" class="correct-choice-input" name="tf_choice_' + idx + '" value="0"> خطأ</label></div>';
                } else if (type === 'vote') {
                    inputHtml = '<span class="badge badge-primary"><i class="fas fa-poll"></i> ' + (idx + 1) + '</span>';
                } else if (isMultiple) {
                    inputHtml = '<input type="checkbox" class="correct-choice-input" name="correct_choices[]" value="' + idx + '" title="إجابة صحيحة">';
                } else {
                    inputHtml = '<input type="radio" class="correct-choice-input" name="correct_choice" value="' + idx + '" title="الإجابة الصحيحة">';
                }

                var div = document.createElement('div');
                div.className = 'choice-row';
                div.innerHTML =
                    '<div class="correct-choice-container">' + inputHtml + '</div>' +
                    '<input type="text" class="form-control modern-input" name="choices[' + idx + '][text]" placeholder="نص الخيار" style="flex-grow:2;">' +
                    '<input type="text" class="form-control modern-input choice-group-name" name="choices[' + idx + '][group_name]" placeholder="المجموعة" style="' + (isOrdering ? 'display:block;' : 'display:none;') + ' max-width: 20%;">' +
                    '<div class="d-flex gap-1">' +
                        '<label class="btn btn-outline-info media-btn-label" title="رفع صورة"><i class="fas fa-image"></i><input type="file" name="choices[' + idx + '][image]" class="choice-image-input d-none" accept="image/*"></label>' +
                        '<label class="btn btn-outline-info media-btn-label" title="رفع فيديو"><i class="fas fa-video"></i><input type="file" name="choices[' + idx + '][video]" class="choice-video-input d-none" accept="video/*"></label>' +
                    '</div>' +
                    '<input type="hidden" name="choices[' + idx + '][is_correct]" value="0" class="choice-correct">' +
                    '<div><button type="button" class="btn-remove-choice remove-choice" title="إزالة هذا الخيار"><i class="fas fa-trash"></i></button></div>';

                choicesContainer.appendChild(div);

                var mediaPreviewDiv = document.createElement('div');
                mediaPreviewDiv.className = 'choice-media-preview mb-3 px-3 small text-muted font-weight-bold';
                div.after(mediaPreviewDiv);

                attachChoiceListeners();
                attachMediaListeners(div);
                div.querySelector('.remove-choice').addEventListener('click', function () {
                    removeChoiceRow(div);
                });
                updateSingleChoiceHint();
            });

            // Dynamic Prizes
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
                    div.innerHTML = '<div class="input-group-prepend"><span class="input-group-text font-weight-bold" style="border-top-right-radius: 10px; border-bottom-right-radius: 10px; min-width: 100px;">' + label + '</span></div>' +
                        '<input type="text" name="prize[]" class="form-control modern-input" style="border-top-right-radius: 0 !important; border-bottom-right-radius: 0 !important;" placeholder="' + placeholder + '" value="' + value + '">';
                    prizesContainer.appendChild(div);
                }
            }

            winnersCountInput.addEventListener('input', updatePrizeInputs);
            updatePrizeInputs();

            // Submit logic
            document.getElementById('questionForm').addEventListener('submit', function () {
                updateHiddenValues();
            });

            // Initial calls
            attachChoiceListeners();
            attachMediaListeners();
            toggleChoices();
            toggleSortable();
        });
    </script>
@endpush
