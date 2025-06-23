@foreach ($people as $person)
    <div class="modal fade" id="editPersonModal{{ $person->id }}" tabindex="-1" role="dialog"
        aria-labelledby="editPersonModalLabel{{ $person->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPersonModalLabel{{ $person->id }}">تعديل الشخص:
                        {{ $person->full_name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('people.update', $person->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name_edit{{ $person->id }}">الاسم الأول</label>
                                    <input type="text" class="form-control" id="first_name_edit{{ $person->id }}"
                                        name="first_name" value="{{ $person->first_name }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name_edit{{ $person->id }}">الاسم الأخير</label>
                                    <input type="text" class="form-control" id="last_name_edit{{ $person->id }}"
                                        name="last_name" value="{{ $person->last_name }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="birth_date_edit{{ $person->id }}">تاريخ الميلاد</label>
                                    <input type="date" class="form-control" id="birth_date_edit{{ $person->id }}"
                                        name="birth_date"
                                        value="{{ $person->birth_date ? $person->birth_date->format('Y-m-d') : '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="death_date_edit{{ $person->id }}">تاريخ الوفاة (إن وجد)</label>
                                    <input type="date" class="form-control" id="death_date_edit{{ $person->id }}"
                                        name="death_date"
                                        value="{{ $person->death_date ? $person->death_date->format('Y-m-d') : '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gender_edit{{ $person->id }}">الجنس</label>
                                    <select class="form-control" id="gender_edit{{ $person->id }}" name="gender"
                                        required>
                                        <option value="male" {{ $person->gender == 'male' ? 'selected' : '' }}>ذكر
                                        </option>
                                        <option value="female" {{ $person->gender == 'female' ? 'selected' : '' }}>أنثى
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="parent_id_edit{{ $person->id }}">الأب/الأم</label>
                                    <select class="form-control" id="parent_id_edit{{ $person->id }}"
                                        name="parent_id">
                                        <option value="">-- اختر الأب أو الأم --</option>
                                        @foreach (App\Models\Person::where('id', '!=', $person->id)->get() as $parent)
                                            <option value="{{ $parent->id }}"
                                                {{ $person->parent_id == $parent->id ? 'selected' : '' }}>
                                                {{ $parent->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="occupation_edit{{ $person->id }}">المهنة</label>
                            <input type="text" class="form-control" id="occupation_edit{{ $person->id }}"
                                name="occupation" value="{{ $person->occupation }}">
                        </div>

                        <div class="form-group">
                            <label for="location_edit{{ $person->id }}">المكان</label>
                            <input type="text" class="form-control" id="location_edit{{ $person->id }}"
                                name="location" value="{{ $person->location }}">
                        </div>

                        <div class="form-group">
                            <label for="biography_edit{{ $person->id }}">سيرة ذاتية</label>
                            <textarea class="form-control" id="biography_edit{{ $person->id }}" name="biography" rows="3">{{ $person->biography }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="photo_edit{{ $person->id }}">صورة الشخص</label>
                            @if ($person->photo_url)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $person->photo_url) }}"
                                        alt="{{ $person->full_name }}" class="img-thumbnail"
                                        style="max-height: 100px;">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox"
                                            id="delete_photo_edit{{ $person->id }}" name="delete_photo"
                                            value="1">
                                        <label class="form-check-label text-danger"
                                            for="delete_photo_edit{{ $person->id }}">
                                            حذف الصورة الحالية
                                        </label>
                                    </div>
                                </div>
                            @endif
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="photo_edit{{ $person->id }}"
                                    name="photo">
                                <label class="custom-file-label" for="photo_edit{{ $person->id }}">اختر ملف</label>
                            </div>
                            <small class="form-text text-muted">الصور المسموح بها: jpeg, png, jpg, gif بحجم أقصى
                                2MB</small>
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
@endforeach
