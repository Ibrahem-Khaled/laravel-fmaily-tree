{{--
    This modal provides a form to create a new person.
    It includes separate dropdowns for selecting a father and a mother,
    populated by data passed from the controller ($males and $females).
--}}
<div class="modal fade" id="createPersonModal" tabindex="-1" role="dialog" aria-labelledby="createPersonModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPersonModalLabel">إضافة شخص جديد</h5>
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
                                <label for="first_name">الاسم الأول</label>
                                <input type="text" class="form-control" id="first_name" name="first_name"
                                    value="{{ old('first_name') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="last_name">الاسم الأخير</label>
                                <input type="text" class="form-control" id="last_name" name="last_name"
                                    value="{{ old('last_name') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Birth and Death Dates --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="birth_date">تاريخ الميلاد</label>
                                <input type="date" class="form-control" id="birth_date" name="birth_date"
                                    value="{{ old('birth_date') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="death_date">تاريخ الوفاة (إن وجد)</label>
                                <input type="date" class="form-control" id="death_date" name="death_date"
                                    value="{{ old('death_date') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Gender --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="gender">الجنس</label>
                                <select class="form-control" id="gender" name="gender" required>
                                    <option value="male" @selected(old('gender') == 'male')>ذكر</option>
                                    <option value="female" @selected(old('gender') == 'female')>أنثى</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- CORRECTED PARENT SELECTION --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="parent_id">الأب</label>
                                {{-- The $males variable is passed from PersonController --}}
                                <select class="form-control js-father-select" id="parent_id" name="parent_id">
                                    <option value="">-- اختر الأب --</option>
                                    @foreach ($males as $father)
                                        <option value="{{ $father->id }}" @selected(old('parent_id') == $father->id)>
                                            {{ $father->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mother_id">الأم</label>
                                {{-- The $females variable is passed from PersonController --}}
                                <select class="form-control js-mother-select" id="mother_id" name="mother_id">
                                    <option value="">-- اختر الأم --</option>
                                    @if ($person->parent)
                                        @foreach ($person->parent->wives as $wife)
                                            <option value="{{ $wife->id }}" @selected(old('mother_id', $person->mother_id) == $wife->id)>
                                                {{ $wife->full_name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Other Fields --}}
                    <div class="form-group">
                        <label for="occupation">المهنة</label>
                        <input type="text" class="form-control" id="occupation" name="occupation"
                            value="{{ old('occupation') }}">
                    </div>

                    <div class="form-group">
                        <label for="location">المكان</label>
                        <input type="text" class="form-control" id="location" name="location"
                            value="{{ old('location') }}">
                    </div>

                    <div class="form-group">
                        <label for="biography">سيرة ذاتية</label>
                        <textarea class="form-control" id="biography" name="biography" rows="3">{{ old('biography') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="photo">صورة الشخص</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="photo" name="photo">
                            <label class="custom-file-label" for="photo">اختر ملف</label>
                        </div>
                        <small class="form-text text-muted">الصور المسموح بها: jpeg, png, jpg, gif بحجم أقصى
                            2MB</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ الشخص</button>
                </div>
            </form>
        </div>
    </div>
</div>
