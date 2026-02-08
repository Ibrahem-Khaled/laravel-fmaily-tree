@extends('layouts.app')

@section('title', 'إضافة سؤال')

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
            <form action="{{ route('dashboard.quiz-questions.store', $quizCompetition) }}" method="POST" id="questionForm">
                @csrf

                <div class="form-group">
                    <label for="question_text">نص السؤال <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('question_text') is-invalid @enderror" id="question_text" name="question_text" rows="4" required>{{ old('question_text') }}</textarea>
                    @error('question_text')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="answer_type">نوع الإجابة <span class="text-danger">*</span></label>
                    <select class="form-control @error('answer_type') is-invalid @enderror" id="answer_type" name="answer_type" required>
                        <option value="multiple_choice" {{ old('answer_type', 'multiple_choice') == 'multiple_choice' ? 'selected' : '' }}>اختيار من متعدد</option>
                        <option value="custom_text" {{ old('answer_type') == 'custom_text' ? 'selected' : '' }}>إجابة حرة (نص)</option>
                    </select>
                    @error('answer_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div id="choicesSection" class="form-group">
                    <label>الخيارات <span class="text-danger">*</span></label>
                    <p class="text-muted small">حدد الخيار الصحيح بعلامة ✓</p>
                    <div id="choicesContainer">
                        @for($i = 0; $i < 4; $i++)
                            <div class="input-group mb-2 choice-row">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <input type="radio" name="correct_choice" value="{{ $i }}" {{ $i == 0 ? 'checked' : '' }} title="الإجابة الصحيحة">
                                    </div>
                                </div>
                                <input type="text" class="form-control" name="choices[{{ $i }}][text]" placeholder="نص الخيار {{ $i + 1 }}" value="{{ old("choices.{$i}.text") }}">
                                <input type="hidden" name="choices[{{ $i }}][is_correct]" value="{{ $i == 0 ? '1' : '0' }}" class="choice-correct">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-danger remove-choice" style="display:none;"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        @endfor
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="addChoice"><i class="fas fa-plus mr-1"></i>إضافة خيار</button>
                </div>

                <div class="alert alert-info small">
                    <i class="fas fa-info-circle mr-2"></i>وقت بداية ونهاية الأسئلة يحدد من المسابقة نفسها في <a href="{{ route('dashboard.quiz-competitions.edit', $quizCompetition) }}" class="alert-link">تعديل المسابقة</a>.
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="winners_count">عدد الفائزين <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('winners_count') is-invalid @enderror" id="winners_count" name="winners_count" value="{{ old('winners_count', 1) }}" min="1" required>
                            @error('winners_count')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="display_order">ترتيب العرض</label>
                            <input type="number" class="form-control @error('display_order') is-invalid @enderror" id="display_order" name="display_order" value="{{ old('display_order', 0) }}" min="0">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const answerType = document.getElementById('answer_type');
    const choicesSection = document.getElementById('choicesSection');
    const choicesContainer = document.getElementById('choicesContainer');
    const addChoiceBtn = document.getElementById('addChoice');

    function toggleChoices() {
        choicesSection.style.display = answerType.value === 'multiple_choice' ? 'block' : 'none';
    }

    answerType.addEventListener('change', toggleChoices);
    toggleChoices();

    function updateCorrectRadios() {
        const rows = choicesContainer.querySelectorAll('.choice-row');
        rows.forEach((row, idx) => {
            const radio = row.querySelector('input[type="radio"]');
            const hidden = row.querySelector('.choice-correct');
            radio.value = idx;
            radio.name = 'correct_choice';
            hidden.name = 'choices[' + idx + '][is_correct]';
            hidden.value = radio.checked ? '1' : '0';
        });
        document.querySelectorAll('input[name="correct_choice"]').forEach(r => {
            r.addEventListener('change', function() {
                document.querySelectorAll('.choice-correct').forEach((h, i) => {
                    h.value = document.querySelectorAll('input[name="correct_choice"]')[i].checked ? '1' : '0';
                });
            });
        });
    }

    addChoiceBtn.addEventListener('click', function() {
        const idx = choicesContainer.querySelectorAll('.choice-row').length;
        const div = document.createElement('div');
        div.className = 'input-group mb-2 choice-row';
        div.innerHTML = `
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <input type="radio" name="correct_choice" value="${idx}" title="الإجابة الصحيحة">
                </div>
            </div>
            <input type="text" class="form-control" name="choices[${idx}][text]" placeholder="نص الخيار">
            <input type="hidden" name="choices[${idx}][is_correct]" value="0" class="choice-correct">
            <div class="input-group-append">
                <button type="button" class="btn btn-outline-danger remove-choice"><i class="fas fa-trash"></i></button>
            </div>
        `;
        choicesContainer.appendChild(div);
        updateCorrectRadios();
        div.querySelector('.remove-choice').addEventListener('click', function() {
            div.remove();
            updateCorrectRadios();
        });
    });

    choicesContainer.querySelectorAll('.remove-choice').forEach(btn => {
        if (btn.style.display !== 'none') {
            btn.addEventListener('click', function() {
                const row = btn.closest('.choice-row');
                if (choicesContainer.querySelectorAll('.choice-row').length > 1) {
                    row.remove();
                    updateCorrectRadios();
                }
            });
        }
    });

    choicesContainer.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.choice-correct').forEach((h, i) => {
                const r = document.querySelectorAll('input[name="correct_choice"]')[i];
                if (r) h.value = r.checked ? '1' : '0';
            });
        });
    });

    document.getElementById('questionForm').addEventListener('submit', function() {
        const correctIdx = document.querySelector('input[name="correct_choice"]:checked');
        if (correctIdx && answerType.value === 'multiple_choice') {
            document.querySelectorAll('.choice-correct').forEach((h, i) => {
                h.value = parseInt(correctIdx.value) === i ? '1' : '0';
            });
        }
    });
});
</script>
@endsection
