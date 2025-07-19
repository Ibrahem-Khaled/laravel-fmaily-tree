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

                    {{-- Birth and Death Dates --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="child_birth_date">تاريخ الميلاد</label>
                                <input type="date" class="form-control" id="child_birth_date" name="birth_date"
                                    value="{{ old('birth_date') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="child_death_date">تاريخ الوفاة (إن وجد)</label>
                                <input type="date" class="form-control" id="child_death_date" name="death_date"
                                    value="{{ old('death_date') }}">
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
                                    {{-- Allow selecting a father --}}
                                    <select class="form-control" id="child_parent_id" name="parent_id">
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
                                    {{-- Allow selecting a mother --}}
                                    <select class="form-control" id="child_mother_id" name="mother_id">
                                        <option value="">-- اختر الأم --</option>
                                        @foreach ($females as $mother)
                                            <option value="{{ $mother->id }}" @selected(old('mother_id') == $mother->id)>
                                                {{ $mother->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Photo Upload --}}
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
