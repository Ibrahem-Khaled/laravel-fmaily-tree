{{--
    This modal is for adding a new child to the person currently being viewed.
    The relevant parent (father or mother) is pre-selected based on the current person's gender.
--}}
<div class="modal fade" id="addChildModal" tabindex="-1" role="dialog" aria-labelledby="addChildModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addChildModalLabel">إضافة ابن/ابنة لـ {{ $person->full_name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('people.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    {{-- Name Fields --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="child_first_name">الاسم الأول</label>
                                <input type="text" class="form-control" id="child_first_name" name="first_name"
                                    value="{{ old('first_name') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="child_last_name">الاسم الأخير</label>
                                <input type="text" class="form-control" id="child_last_name" name="last_name"
                                    value="{{ old('last_name', $person->last_name) }}">
                                <small class="form-text text-muted">سيتم استخدام اسم عائلة الأب/الأم بشكل
                                    افتراضي.</small>
                            </div>
                        </div>
                    </div>

                    {{-- Gender --}}
                    <div class="form-group">
                        <label for="child_gender">الجنس</label>
                        <select class="form-control" id="child_gender" name="gender" required>
                            <option value="male" @selected(old('gender') == 'male')>ذكر</option>
                            <option value="female" @selected(old('gender') == 'female')>أنثى</option>
                        </select>
                    </div>

                    {{-- Parent Selection with Pre-filled value --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="child_parent_id">الأب</label>
                                @if ($person->gender == 'male')
                                    {{-- Pre-fill father and disable selection --}}
                                    <input type="hidden" name="parent_id" value="{{ $person->id }}">
                                    <input type="text" class="form-control" value="{{ $person->full_name }}"
                                        disabled>
                                @else
                                    {{-- Allow selecting a father (triggers the JS) --}}
                                    <select class="form-control js-father-select" id="child_parent_id" name="parent_id">
                                        <option value="">-- اختر الأب --</option>
                                        @foreach ($males as $father)
                                            <option value="{{ $father->id }}" @selected(old('parent_id') == $father->id)>
                                                {{ $father->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="child_mother_id">الأم</label>
                                @if ($person->gender == 'female')
                                    {{-- Pre-fill mother and disable selection --}}
                                    <input type="hidden" name="mother_id" value="{{ $person->id }}">
                                    <input type="text" class="form-control" value="{{ $person->full_name }}"
                                        disabled>
                                @else
                                    {{--
                                        ### BEGIN: MODIFIED SECTION ###
                                        If the person is male, HE is the father.
                                        Therefore, this dropdown should list HIS wives.
                                    --}}
                                    <select class="form-control js-mother-select" id="child_mother_id" name="mother_id"
                                        required>
                                        <option value="">-- اختر الأم (من زوجات الأب) --</option>
                                        {{-- We iterate over the current person's wives --}}
                                        @foreach ($person->wives as $wife)
                                            <option value="{{ $wife->id }}" @selected(old('mother_id') == $wife->id)>
                                                {{ $wife->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    {{-- ### END: MODIFIED SECTION ### --}}
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Other fields like birth date, photo etc. remain the same --}}
                    <div class="form-group">
                        <label for="child_birth_date">تاريخ الميلاد</label>
                        <input type="date" class="form-control" id="child_birth_date" name="birth_date"
                            value="{{ old('birth_date') }}">
                    </div>

                    <div class="form-group">
                        <label for="child_photo">صورة الشخص</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="child_photo" name="photo">
                            <label class="custom-file-label" for="child_photo">اختر ملف</label>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ الابن/الابنة</button>
                </div>
            </form>
        </div>
    </div>
</div>
