@extends('layouts.app')

@section('title', 'تعديل سؤال')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 mb-2 text-gray-800">
                    <i class="fas fa-edit text-primary mr-2"></i>تعديل سؤال - {{ $quizCompetition->title }}
                </h1>
                <p class="text-muted mb-0">قم بتعديل بيانات السؤال</p>
            </div>
            <a href="{{ route('dashboard.quiz-competitions.show', $quizCompetition) }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-right mr-2"></i>العودة للمسابقة
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="{{ route('dashboard.quiz-questions.update', [$quizCompetition, $quizQuestion]) }}"
                    method="POST" id="questionForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="question_text">نص السؤال <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('question_text') is-invalid @enderror" id="question_text"
                            name="question_text" rows="4"
                            required>{{ old('question_text', $quizQuestion->question_text) }}</textarea>
                        @error('question_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">الوصف</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                            name="description" rows="4">{{ old('description', $quizQuestion->description) }}</textarea>
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
                                    {{ old('answer_type', $quizQuestion->answer_type) == 'multiple_choice' ? 'checked' : '' }} required>
                                <label class="custom-control-label" for="type_multiple_choice">اختيار من متعدد</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="type_ordering" name="answer_type"
                                    class="custom-control-input answer-type-radio" value="ordering"
                                    {{ old('answer_type', $quizQuestion->answer_type) == 'ordering' ? 'checked' : '' }} required>
                                <label class="custom-control-label" for="type_ordering">ترتيب (سحب وإفلات)</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="type_true_false" name="answer_type"
                                    class="custom-control-input answer-type-radio" value="true_false"
                                    {{ old('answer_type', $quizQuestion->answer_type) == 'true_false' ? 'checked' : '' }} required>
                                <label class="custom-control-label" for="type_true_false">صح / خطأ</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="type_custom_text" name="answer_type"
                                    class="custom-control-input answer-type-radio" value="custom_text"
                                    {{ old('answer_type', $quizQuestion->answer_type) == 'custom_text' ? 'checked' : '' }} required>
                                <label class="custom-control-label" for="type_custom_text">إجابة حرة (نص)</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="type_vote" name="answer_type"
                                    class="custom-control-input answer-type-radio" value="vote"
                                    {{ old('answer_type', $quizQuestion->answer_type) == 'vote' ? 'checked' : '' }} required>
                                <label class="custom-control-label text-primary" for="type_vote"><i class="fas fa-poll mr-1"></i>تصويت</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="type_fill_blank" name="answer_type"
                                    class="custom-control-input answer-type-radio" value="fill_blank"
                                    {{ old('answer_type', $quizQuestion->answer_type) == 'fill_blank' ? 'checked' : '' }} required>
                                <label class="custom-control-label text-warning" for="type_fill_blank"><i class="fas fa-pen-nib mr-1"></i>أملأ الفراغ</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="type_survey" name="answer_type"
                                    class="custom-control-input answer-type-radio" value="survey"
                                    {{ old('answer_type', $quizQuestion->answer_type) == 'survey' ? 'checked' : '' }} required>
                                <label class="custom-control-label text-success" for="type_survey"><i class="fas fa-stream mr-1"></i>استبيان ديناميكي</label>
                            </div>
                        </div>
                        @error('answer_type')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Vote-specific options --}}
                    <div class="form-group" id="voteOptionsGroup" style="display:none;">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white py-2">
                                <i class="fas fa-poll mr-1"></i> إعدادات التصويت
                            </div>
                            <div class="card-body py-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="vote_max_selections">الحد الأقصى للخيارات <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('vote_max_selections') is-invalid @enderror"
                                            id="vote_max_selections" name="vote_max_selections"
                                            value="{{ old('vote_max_selections', $quizQuestion->vote_max_selections ?? 1) }}" min="1">
                                        <small class="text-muted">1 = تصويت على خيار واحد فقط</small>
                                        @error('vote_max_selections')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="d-block mb-2">شرط المشاركة</label>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="require_prior_registration"
                                                name="require_prior_registration" value="1"
                                                {{ old('require_prior_registration', $quizQuestion->require_prior_registration) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="require_prior_registration">
                                                يشترط تسجيل مسبق (يظهر حقل الهاتف فقط للتحقق)
                                            </label>
                                        </div>
                                        <small class="text-muted">إذا كان مفعلاً، لا يمكن التصويت إلا لمن سبق تسجيله برقم هاتفه.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" id="groupsCountGroup" style="display:none;">
                        <label for="groups_count">عدد المجموعات للسحب والإفلات (اتركه فارغاً أو 0 لعدم وجود مجموعات)</label>
                        <input type="number" class="form-control @error('groups_count') is-invalid @enderror"
                            id="groups_count" name="groups_count" value="{{ old('groups_count', $quizQuestion->groups_count) }}" min="0">
                        @error('groups_count')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group" id="surveySection" style="{{ old('answer_type', $quizQuestion->answer_type) === 'survey' ? '' : 'display:none' }};">
                        <div class="card border-success shadow-sm">
                            <div class="card-header bg-success text-white py-2">
                                <i class="fas fa-stream mr-1"></i> عناصر الاستبيان
                            </div>
                            <div class="card-body">
                                <div id="surveyItemsContainer">
                                    @if(old('answer_type', $quizQuestion->answer_type) === 'survey')
                                        @foreach($quizQuestion->surveyItems as $idx => $si)
                                            <div class="survey-item-row mb-3 p-3 border rounded bg-white">
                                                <input type="hidden" name="survey_items[{{ $idx }}][id]" value="{{ $si->id }}">
                                                <div class="form-row">
                                                    <div class="col-md-3 mb-2">
                                                        <label class="small font-weight-bold">نوع العنصر</label>
                                                        <select name="survey_items[{{ $idx }}][block_type]" class="form-control form-control-sm survey-block-type">
                                                            <option value="text" @selected($si->block_type === 'text')>نص / سؤال</option>
                                                            <option value="image" @selected($si->block_type === 'image')>صورة</option>
                                                            <option value="video" @selected($si->block_type === 'video')>فيديو</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 mb-2 survey-media-col" style="{{ $si->block_type === 'text' ? 'display:none' : '' }}">
                                                        <label class="small font-weight-bold">استبدال الملف (اختياري)</label>
                                                        <input type="file" name="survey_items[{{ $idx }}][media]" class="form-control-file survey-media-input" accept="image/*,video/*">
                                                        @if($si->media_path)
                                                            <small class="text-success d-block mt-1"><i class="fas fa-check"></i> يوجد ملف محفوظ</small>
                                                        @endif
                                                        <div class="survey-media-preview mt-2 small text-muted">
                                                            @if($si->media_path)
                                                                @if($si->block_type === 'image')
                                                                    <img src="{{ asset('storage/' . $si->media_path) }}"
                                                                        alt="صورة عنصر الاستبيان"
                                                                        style="max-height:90px; max-width:220px; object-fit:contain;"
                                                                        class="img-thumbnail bg-white p-1">
                                                                @elseif($si->block_type === 'video')
                                                                    <video controls
                                                                        src="{{ asset('storage/' . $si->media_path) }}"
                                                                        style="max-height:110px; max-width:260px; object-fit:contain;"
                                                                        class="border rounded bg-white"></video>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5 mb-2 survey-body-col">
                                                        <label class="small font-weight-bold">نص</label>
                                                        <textarea name="survey_items[{{ $idx }}][body_text]" class="form-control form-control-sm" rows="2">{{ old("survey_items.$idx.body_text", $si->body_text) }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="form-row align-items-end">
                                                    <div class="col-md-3 mb-2">
                                                        <label class="small font-weight-bold">إجابة المشارك</label>
                                                        <select name="survey_items[{{ $idx }}][response_kind]" class="form-control form-control-sm survey-response-kind">
                                                            <option value="rating" @selected($si->response_kind === 'rating')>تقييم</option>
                                                            <option value="number" @selected($si->response_kind === 'number')>رقم</option>
                                                            <option value="text" @selected($si->response_kind === 'text')>نص حر</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2 mb-2 survey-rating-wrap" style="{{ $si->response_kind === 'rating' ? '' : 'display:none' }}">
                                                        <label class="small">حتى</label>
                                                        <input type="number" name="survey_items[{{ $idx }}][rating_max]" value="{{ old("survey_items.$idx.rating_max", $si->rating_max) }}" min="2" max="100" class="form-control form-control-sm">
                                                    </div>
                                                    <div class="col-md-4 mb-2 survey-number-wrap" style="{{ $si->response_kind === 'number' ? '' : 'display:none' }}">
                                                        <label class="small">من — إلى</label>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" name="survey_items[{{ $idx }}][number_min]" class="form-control" value="{{ old("survey_items.$idx.number_min", $si->number_min ?? 0) }}">
                                                            <input type="number" name="survey_items[{{ $idx }}][number_max]" class="form-control" value="{{ old("survey_items.$idx.number_max", $si->number_max ?? 100) }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 mb-2 text-left">
                                                        <button type="button" class="btn btn-sm btn-outline-danger remove-survey-item mt-3"><i class="fas fa-trash"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-success mt-2" id="addSurveyItem">
                                    <i class="fas fa-plus mr-1"></i>إضافة عنصر
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" id="multipleSelectionsGroup">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_multiple_selections"
                                name="is_multiple_selections" value="1"
                                {{ old('is_multiple_selections', $quizQuestion->is_multiple_selections) ? 'checked' : '' }}>
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
                            @php
                                $choices = old('choices', $quizQuestion->choices->map(fn($c) => [
                                    'id' => $c->id,
                                    'text' => $c->choice_text,
                                    'is_correct' => $c->is_correct,
                                    'image' => $c->image,
                                    'video' => $c->video,
                                    'group_name' => $c->group_name
                                ])->values()->all());
                            @endphp
                            @if(count($choices) > 0)
                                @foreach($choices as $i => $choice)
                                    <div class="input-group mb-2 choice-row">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text correct-choice-container">
                                                <input type="radio" class="correct-choice-input" name="correct_choice"
                                                    value="{{ $i }}" {{ !empty($choice['is_correct']) ? 'checked' : '' }}
                                                    title="الإجابة الصحيحة">
                                            </div>
                                        </div>
                                        @if(isset($choice['id']))
                                            <input type="hidden" name="choices[{{ $i }}][id]" value="{{ $choice['id'] }}">
                                        @endif
                                        <input type="text" class="form-control" name="choices[{{ $i }}][text]"
                                            placeholder="نص الخيار {{ $i + 1 }}" value="{{ $choice['text'] ?? '' }}">
                                        <input type="text" class="form-control choice-group-name" name="choices[{{ $i }}][group_name]"
                                            placeholder="اسم المجموعة (للسحب والإفلات)" value="{{ $choice['group_name'] ?? '' }}" style="display:none; max-width: 25%;">
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
                                        <input type="hidden" name="choices[{{ $i }}][is_correct]"
                                            value="{{ !empty($choice['is_correct']) && $choice['is_correct'] != 'false' && $choice['is_correct'] != '0' ? '1' : '0' }}" class="choice-correct">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-danger remove-choice"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </div>
                                    <div class="choice-media-preview mb-2 px-3 small text-muted">
                                        @if(!empty($choice['image']))
                                            <span class="badge badge-info mr-1" title="صورة موجودة"><i class="fas fa-image"></i> تم الرفع</span>
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $choice['image']) }}"
                                                    alt="صورة الخيار"
                                                    style="max-height:90px; max-width:220px; object-fit:contain;"
                                                    class="img-thumbnail bg-white p-1">
                                            </div>
                                        @endif
                                        @if(!empty($choice['video']))
                                            <span class="badge badge-info mr-1" title="فيديو موجود"><i class="fas fa-video"></i> تم الرفع</span>
                                            <div class="mt-2">
                                                <video controls
                                                    src="{{ asset('storage/' . $choice['video']) }}"
                                                    style="max-height:110px; max-width:260px; object-fit:contain;"
                                                    class="border rounded bg-white">
                                                </video>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                @for($i = 0; $i < 4; $i++)
                                    <div class="input-group mb-2 choice-row">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text correct-choice-container">
                                                <input type="radio" class="correct-choice-input" name="correct_choice"
                                                    value="{{ $i }}" {{ $i == 0 ? 'checked' : '' }} title="الإجابة الصحيحة">
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" name="choices[{{ $i }}][text]"
                                            placeholder="نص الخيار {{ $i + 1 }}">
                                        <input type="text" class="form-control choice-group-name" name="choices[{{ $i }}][group_name]"
                                            placeholder="اسم المجموعة (للسحب والإفلات)" style="display:none; max-width: 25%;">
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
                                    <div class="choice-media-preview mb-2 px-3 small text-muted"></div>
                                @endfor
                            @endif
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
                                    id="winners_count" name="winners_count"
                                    value="{{ old('winners_count', $quizQuestion->winners_count) }}" min="1" required>
                                @error('winners_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="display_order">ترتيب العرض</label>
                                <input type="number" class="form-control @error('display_order') is-invalid @enderror"
                                    id="display_order" name="display_order"
                                    value="{{ old('display_order', $quizQuestion->display_order) }}" min="0">
                                @error('display_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>حفظ التعديلات
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

            var surveySection = document.getElementById('surveySection');
            var surveyItemsContainer = document.getElementById('surveyItemsContainer');
            var surveyItemSeq = surveyItemsContainer ? surveyItemsContainer.querySelectorAll('.survey-item-row').length : 0;

            function surveyRowTemplate(idx) {
                return '<div class="survey-item-row mb-3 p-3 border rounded bg-white">' +
                    '<input type="hidden" name="survey_items[' + idx + '][id]" value="">' +
                    '<div class="form-row">' +
                    '<div class="col-md-3 mb-2"><label class="small font-weight-bold">نوع العنصر</label>' +
                    '<select name="survey_items[' + idx + '][block_type]" class="form-control form-control-sm survey-block-type">' +
                    '<option value="text">نص / سؤال</option><option value="image">صورة</option><option value="video">فيديو</option></select></div>' +
                    '<div class="col-md-4 mb-2 survey-media-col" style="display:none"><label class="small font-weight-bold">رفع ملف</label>' +
                    '<input type="file" name="survey_items[' + idx + '][media]" class="form-control-file survey-media-input" accept="image/*,video/*">' +
                    '<div class="survey-media-preview mt-2 small text-muted"></div></div>' +
                    '<div class="col-md-5 mb-2 survey-body-col"><label class="small font-weight-bold">نص</label>' +
                    '<textarea name="survey_items[' + idx + '][body_text]" class="form-control form-control-sm" rows="2"></textarea></div></div>' +
                    '<div class="form-row align-items-end">' +
                    '<div class="col-md-3 mb-2"><label class="small font-weight-bold">إجابة المشارك</label>' +
                    '<select name="survey_items[' + idx + '][response_kind]" class="form-control form-control-sm survey-response-kind">' +
                    '<option value="rating">تقييم</option><option value="number">رقم</option><option value="text">نص حر</option></select></div>' +
                    '<div class="col-md-2 mb-2 survey-rating-wrap"><label class="small">حتى</label>' +
                    '<input type="number" name="survey_items[' + idx + '][rating_max]" value="10" min="2" max="100" class="form-control form-control-sm"></div>' +
                    '<div class="col-md-4 mb-2 survey-number-wrap" style="display:none"><label class="small">من — إلى</label>' +
                    '<div class="input-group input-group-sm"><input type="number" name="survey_items[' + idx + '][number_min]" class="form-control" value="0">' +
                    '<input type="number" name="survey_items[' + idx + '][number_max]" class="form-control" value="100"></div></div>' +
                    '<div class="col-md-2 mb-2 text-left"><button type="button" class="btn btn-sm btn-outline-danger remove-survey-item mt-3"><i class="fas fa-trash"></i></button></div></div></div>';
            }

            function bindSurveyRow(row) {
                var block = row.querySelector('.survey-block-type');
                var resp = row.querySelector('.survey-response-kind');
                var mediaInput = row.querySelector('.survey-media-input');
                function updBlock() {
                    row.querySelector('.survey-media-col').style.display = (block.value === 'text') ? 'none' : 'block';
                }
                function updResp() {
                    var r = resp.value;
                    row.querySelector('.survey-rating-wrap').style.display = r === 'rating' ? 'block' : 'none';
                    row.querySelector('.survey-number-wrap').style.display = r === 'number' ? 'block' : 'none';
                }
                block.addEventListener('change', updBlock);
                resp.addEventListener('change', updResp);

                // Render image/video preview for uploaded survey media
                if (mediaInput) {
                    mediaInput.addEventListener('change', function () {
                        var preview = row.querySelector('.survey-media-preview');
                        if (!preview) return;
                        if (!this.files || !this.files[0]) return;

                        if (preview.dataset.objectUrl) {
                            try { URL.revokeObjectURL(preview.dataset.objectUrl); } catch (e) {}
                        }
                        preview.dataset.objectUrl = '';

                        var file = this.files[0];
                        var objectUrl = URL.createObjectURL(file);
                        preview.dataset.objectUrl = objectUrl;

                        // Clear and render
                        preview.innerHTML = '';
                        var isImage = file.type && file.type.startsWith('image/');

                        var fileName = file.name || '';
                        var badge = document.createElement('span');
                        badge.className = 'badge badge-light border d-inline-flex align-items-center';
                        badge.innerHTML = '<i class="fas fa-' + (isImage ? 'image' : 'video') + ' mr-1"></i> ' + (isImage ? 'صورة' : 'فيديو') + (fileName ? (': ' + fileName) : '');
                        preview.appendChild(badge);

                        if (isImage) {
                            var img = document.createElement('img');
                            img.src = objectUrl;
                            img.alt = 'صورة عنصر الاستبيان';
                            img.style.maxHeight = '90px';
                            img.style.maxWidth = '220px';
                            img.style.objectFit = 'contain';
                            img.className = 'img-thumbnail bg-white p-1 mt-2 d-block';
                            preview.appendChild(img);
                        } else {
                            var vid = document.createElement('video');
                            vid.controls = true;
                            vid.src = objectUrl;
                            vid.style.maxHeight = '110px';
                            vid.style.maxWidth = '260px';
                            vid.style.objectFit = 'contain';
                            vid.className = 'border rounded bg-white mt-2 d-block';
                            preview.appendChild(vid);
                        }
                    });
                }

                row.querySelector('.remove-survey-item').addEventListener('click', function () {
                    if (surveyItemsContainer.querySelectorAll('.survey-item-row').length <= 1) return;
                    row.remove();
                });
                updBlock();
                updResp();
            }

            function appendSurveyRow() {
                if (!surveyItemsContainer) return;
                var idx = surveyItemSeq++;
                var div = document.createElement('div');
                div.innerHTML = surveyRowTemplate(idx);
                var row = div.firstElementChild;
                surveyItemsContainer.appendChild(row);
                bindSurveyRow(row);
            }

            if (surveyItemsContainer) {
                surveyItemsContainer.querySelectorAll('.survey-item-row').forEach(function (row) { bindSurveyRow(row); });
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

                var voteOptionsGroup = document.getElementById('voteOptionsGroup');
                if (voteOptionsGroup) voteOptionsGroup.style.display = (isVote && !isSurvey) ? 'block' : 'none';

                var hint = document.getElementById('choicesHint');
                if (isOrdering) {
                    hint.textContent = 'أدخل الخيارات بالترتيب الصحيح، ستظهر للمستخدم بشكل عشوائي ليرتبها.';
                } else if (isTrueFalse) {
                    hint.textContent = 'أدخل الجملة وحدد هل هي صحيحة أم خاطئة، يمكنك إرفاق صورة مع كل جملة.';
                } else if (isVote) {
                    hint.textContent = 'أدخل خيارات التصويت (لا توجد إجابة صحيحة)، يمكنك إرفاق صورة أو فيديو.';
                } else if (isFillBlank) {
                    hint.innerHTML = 'أدخل كلمات الاختيار وحدد الكلمة <strong>الصحيدة ✓</strong>. ضع <code>___</code> في نص السؤال مكان الفراغ.';
                } else {
                    hint.textContent = 'حدد الخيار الصحيح بعلامة ✓';
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

                    var idInput = row.querySelector('input[name$="[id]"]');
                    if (idInput) {
                        idInput.name = 'choices[' + idx + '][id]';
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
                            preview.className = 'choice-media-preview mb-2 px-3 small text-muted';
                            row.after(preview);
                        }
                        if (!this.files || !this.files[0]) return;

                        // Revoke previous object URL (if any)
                        if (preview.dataset.objectUrl) {
                            try { URL.revokeObjectURL(preview.dataset.objectUrl); } catch (e) {}
                        }
                        preview.dataset.objectUrl = '';

                        var file = this.files[0];
                        var objectUrl = URL.createObjectURL(file);
                        preview.dataset.objectUrl = objectUrl;

                        preview.innerHTML = '';

                        var isImage = this.classList.contains('choice-image-input') || (file.type && file.type.startsWith('image/'));
                        var mediaType = isImage ? 'صورة' : 'فيديو';
                        var icon = isImage ? 'image' : 'video';
                        var fileName = file.name || '';

                        var badge = document.createElement('span');
                        badge.className = 'badge badge-light border d-inline-flex align-items-center';
                        badge.innerHTML = '<i class="fas fa-' + icon + ' mr-1"></i> ' + mediaType + (fileName ? (': ' + fileName) : '');
                        preview.appendChild(badge);

                        if (isImage) {
                            var img = document.createElement('img');
                            img.src = objectUrl;
                            img.alt = 'صورة الخيار';
                            img.style.maxHeight = '90px';
                            img.style.maxWidth = '220px';
                            img.style.objectFit = 'contain';
                            img.className = 'img-thumbnail bg-white p-1 mt-2 d-block';
                            preview.appendChild(img);
                        } else {
                            var vid = document.createElement('video');
                            vid.controls = true;
                            vid.src = objectUrl;
                            vid.style.maxHeight = '110px';
                            vid.style.maxWidth = '260px';
                            vid.style.objectFit = 'contain';
                            vid.className = 'border rounded bg-white mt-2 d-block';
                            preview.appendChild(vid);
                        }
                    });
                });
            }

            var addSurveyItemBtn = document.getElementById('addSurveyItem');
            if (addSurveyItemBtn) {
                addSurveyItemBtn.addEventListener('click', function () {
                    appendSurveyRow();
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
                div.className = 'input-group mb-2 choice-row';
                div.innerHTML =
                    '<div class="input-group-prepend"><div class="input-group-text correct-choice-container">' + inputHtml + '</div></div>' +
                    '<input type="text" class="form-control" name="choices[' + idx + '][text]" placeholder="نص الخيار">' +
                    '<input type="text" class="form-control choice-group-name" name="choices[' + idx + '][group_name]" placeholder="اسم المجموعة (للسحب والإفلات)" style="' + (isOrdering ? 'display:block;' : 'display:none;') + ' max-width: 25%;">' +
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
            var existingPrizes = @json(old('prize', $quizQuestion->prize ?? []));
            if (typeof existingPrizes === 'string') existingPrizes = [existingPrizes];

            function updatePrizeInputs() {
                var count = parseInt(winnersCountInput.value) || 0;
                var currentInputValues = [];
                prizesContainer.querySelectorAll('input').forEach(function (input) {
                    currentInputValues.push(input.value);
                });
                prizesContainer.innerHTML = '';
                for (var i = 0; i < count; i++) {
                    var label = i === 0 ? 'المركز الأول' : (i === 1 ? 'المركز الثاني' : (i === 2 ? 'المركز الثالث' : 'المركز ' + (i + 1)));
                    var placeholder = i === 0 ? 'مثال: آيفون 15' : 'الجائزة';
                    var val = (currentInputValues[i] !== undefined) ? currentInputValues[i] : (existingPrizes[i] || '');
                    var div = document.createElement('div');
                    div.className = 'input-group mb-2';
                    div.innerHTML = '<div class="input-group-prepend"><span class="input-group-text">' + label + '</span></div>' +
                        '<input type="text" name="prize[]" class="form-control" placeholder="' + placeholder + '" value="' + val + '">';
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
