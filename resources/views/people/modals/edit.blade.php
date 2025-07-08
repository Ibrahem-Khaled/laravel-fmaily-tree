{{--
    This modal is generated for each person in the list to allow editing.
    It's dynamically populated with the person's data.
--}}
<div class="modal fade" id="editPersonModal{{ $person->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editPersonModalLabel{{ $person->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPersonModalLabel{{ $person->id }}">تعديل بيانات:
                    {{ $person->full_name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('people.update', $person->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    {{-- Name Fields --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="first_name_edit{{ $person->id }}">الاسم الأول</label>
                                <input type="text" class="form-control" id="first_name_edit{{ $person->id }}"
                                    name="first_name" value="{{ old('first_name', $person->first_name) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="last_name_edit{{ $person->id }}">الاسم الأخير</label>
                                <input type="text" class="form-control" id="last_name_edit{{ $person->id }}"
                                    name="last_name" value="{{ old('last_name', $person->last_name) }}">
                            </div>
                        </div>
                    </div>

                    {{-- Birth and Death Dates --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="birth_date_edit{{ $person->id }}">تاريخ الميلاد</label>
                                <input type="date" class="form-control" id="birth_date_edit{{ $person->id }}"
                                    name="birth_date"
                                    value="{{ old('birth_date', $person->birth_date ? $person->birth_date->format('Y-m-d') : '') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="death_date_edit{{ $person->id }}">تاريخ الوفاة (إن وجد)</label>
                                <input type="date" class="form-control" id="death_date_edit{{ $person->id }}"
                                    name="death_date"
                                    value="{{ old('death_date', $person->death_date ? $person->death_date->format('Y-m-d') : '') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Gender and Parents --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="gender_edit{{ $person->id }}">الجنس</label>
                                <select class="form-control" id="gender_edit{{ $person->id }}" name="gender"
                                    required>
                                    <option value="male" @selected(old('gender', $person->gender) == 'male')>ذكر</option>
                                    <option value="female" @selected(old('gender', $person->gender) == 'female')>أنثى</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- CORRECTED PARENT SELECTION --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="parent_id_edit{{ $person->id }}">الأب</label>
                                <select class="form-control" id="parent_id_edit{{ $person->id }}" name="parent_id">
                                    <option value="">-- اختر الأب --</option>
                                    {{-- Loop through males passed from controller, excluding the person being edited --}}
                                    @foreach ($males->where('id', '!=', $person->id) as $father)
                                        <option value="{{ $father->id }}" @selected(old('parent_id', $person->parent_id) == $father->id)>
                                            {{ $father->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mother_id_edit{{ $person->id }}">الأم</label>
                                <select class="form-control" id="mother_id_edit{{ $person->id }}" name="mother_id">
                                    <option value="">-- اختر الأم --</option>
                                    {{-- Loop through females passed from controller --}}
                                    @foreach ($females as $mother)
                                        <option value="{{ $mother->id }}" @selected(old('mother_id', $person->mother_id) == $mother->id)>
                                            {{ $mother->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Other Fields --}}
                    <div class="form-group">
                        <label for="occupation_edit{{ $person->id }}">المهنة</label>
                        <input type="text" class="form-control" id="occupation_edit{{ $person->id }}"
                            name="occupation" value="{{ old('occupation', $person->occupation) }}">
                    </div>

                    <div class="form-group">
                        <label for="location_edit{{ $person->id }}">المكان</label>
                        <input type="text" class="form-control" id="location_edit{{ $person->id }}"
                            name="location" value="{{ old('location', $person->location) }}">
                    </div>

                    <div class="form-group">
                        <label for="biography_edit{{ $person->id }}">سيرة ذاتية</label>
                        <textarea class="form-control" id="biography_edit{{ $person->id }}" name="biography" rows="3">{{ old('biography', $person->biography) }}</textarea>
                    </div>

                    {{-- Photo Upload and Deletion --}}
                    <div class="form-group">
                        <label for="photo_edit{{ $person->id }}">صورة الشخص</label>
                        @if ($person->photo_url)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $person->photo_url) }}"
                                    alt="{{ $person->full_name }}" class="img-thumbnail" style="max-height: 100px;">
                            </div>
                        @endif
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="photo_edit{{ $person->id }}"
                                name="photo">
                            <label class="custom-file-label" for="photo_edit{{ $person->id }}">تغيير
                                الصورة...</label>
                        </div>
                        <small class="form-text text-muted">اترك الحقل فارغاً للإبقاء على الصورة الحالية.</small>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                </div>
            </form>
        </div>
    </div>
</div>
