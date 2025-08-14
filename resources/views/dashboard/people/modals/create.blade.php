{{--
    This modal provides a form to create a new person.
    It includes separate dropdowns for selecting a father and a mother.
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
                                <label for="create_first_name">الاسم الأول</label>
                                <input type="text" class="form-control" id="create_first_name" name="first_name"
                                    value="{{ old('first_name') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="create_last_name">الاسم الأخير</label>
                                <input type="text" class="form-control" id="create_last_name" name="last_name"
                                    value="{{ old('last_name') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Birth and Death Dates --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="create_birth_date">تاريخ الميلاد</label>
                                <input type="date" class="form-control" id="create_birth_date" name="birth_date"
                                    value="{{ old('birth_date') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="create_death_date">تاريخ الوفاة (إن وجد)</label>
                                <input type="date" class="form-control" id="create_death_date" name="death_date"
                                    value="{{ old('death_date') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Gender --}}
                    <div class="form-group">
                        <label for="create_gender">الجنس</label>
                        <select class="form-control" id="create_gender" name="gender" required>
                            <option value="male" @selected(old('gender') == 'male')>ذكر</option>
                            <option value="female" @selected(old('gender') == 'female')>أنثى</option>
                        </select>
                    </div>

                    {{-- PARENT SELECTION --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="create_parent_id">الأب</label>
                                {{-- This dropdown triggers the JavaScript --}}
                                <select class="form-control js-father-select" id="create_parent_id" name="parent_id">
                                    <option value="">-- اختر الأب --</option>
                                    {{-- The $males variable is passed from the controller --}}
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
                                <label for="create_mother_id">الأم</label>
                                {{--
                                    ### CORRECTED SECTION ###
                                    This dropdown now starts empty. The JavaScript will populate it
                                    based on the father selection. The previous logic using a non-existent
                                    '$person' variable has been removed.
                                --}}
                                <select class="form-control js-mother-select" id="create_mother_id" name="mother_id">
                                    <option value="">-- اختر الأب أولاً --</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Other Fields --}}
                    <div class="form-group">
                        <label for="create_occupation">المهنة</label>
                        <input type="text" class="form-control" id="create_occupation" name="occupation"
                            value="{{ old('occupation') }}">
                    </div>

                    <div class="form-group">
                        <label for="create_location">المكان</label>
                        <input type="text" class="form-control" id="create_location" name="location"
                            value="{{ old('location') }}">
                    </div>

                    <div class="form-group">
                        <label for="create_biography">سيرة ذاتية</label>
                        <textarea class="form-control" id="create_biography" name="biography" rows="3">{{ old('biography') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="create_photo">صورة الشخص</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="create_photo" name="photo">
                            <label class="custom-file-label" for="create_photo">اختر ملف</label>
                        </div>
                        <small class="form-text text-muted">الصور المسموح بها: jpeg, png, jpg, gif بحجم أقصى 2MB</small>
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
