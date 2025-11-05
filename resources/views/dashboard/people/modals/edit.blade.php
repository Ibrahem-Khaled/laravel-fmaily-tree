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

                    {{-- Gender --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gender_edit{{ $person->id }}">الجنس</label>
                                <select class="form-control" id="gender_edit{{ $person->id }}" name="gender"
                                    required>
                                    <option value="male" @selected(old('gender', $person->gender) == 'male')>ذكر</option>
                                    <option value="female" @selected(old('gender', $person->gender) == 'female')>أنثى</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="family_status_edit{{ $person->id }}">الحالة العائلية</label>
                                <select class="form-control" id="family_status_edit{{ $person->id }}" name="from_outside_the_family">
                                    <option value="0" @selected(old('from_outside_the_family', $person->from_outside_the_family) == '0')>
                                        داخل العائلة
                                    </option>
                                    <option value="1" @selected(old('from_outside_the_family', $person->from_outside_the_family) == '1')>
                                        خارج العائلة
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- PARENT SELECTION (WITH DYNAMIC MOTHER LIST) --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="parent_id_edit{{ $person->id }}">الأب</label>
                                <select class="form-control js-father-select" id="parent_id_edit{{ $person->id }}" name="parent_id">
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
                                {{--
                                    ### BEGIN: MODIFIED SECTION ###
                                    This select dropdown is now populated correctly on initial load.
                                    The JavaScript code you provided will handle dynamic changes.
                                --}}
                                <select class="form-control js-mother-select" id="mother_id_edit{{ $person->id }}" name="mother_id">
                                    <option value="">-- اختر الأب أولاً --</option>

                                    {{-- If the person already has a father selected, list his wives --}}
                                    @if ($person->parent)
                                        @foreach ($person->parent->wives as $wife)
                                            <option value="{{ $wife->id }}" @selected(old('mother_id', $person->mother_id) == $wife->id)>
                                                {{ $wife->full_name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                {{-- ### END: MODIFIED SECTION ### --}}
                            </div>
                        </div>
                    </div>

                    {{-- Other Fields --}}
                    <div class="form-group">
                        <label for="birth_place_edit{{ $person->id }}">مكان الميلاد</label>
                        <input type="text" class="form-control" id="birth_place_edit{{ $person->id }}"
                            name="birth_place" value="{{ old('birth_place', $person->birth_place) }}">
                    </div>

                    <div class="form-group">
                        <label for="occupation_edit{{ $person->id }}">المهنة</label>
                        <input type="text" class="form-control" id="occupation_edit{{ $person->id }}"
                            name="occupation" value="{{ old('occupation', $person->occupation) }}">
                    </div>

                    <div class="form-group">
                        <label for="location_edit{{ $person->id }}">مكان الإقامة</label>
                        <input type="text" class="form-control location-autocomplete" id="location_edit{{ $person->id }}"
                            name="location" value="{{ old('location', $person->location ? $person->location->display_name : '') }}" autocomplete="off" placeholder="ابدأ الكتابة للبحث...">
                        <input type="hidden" id="location_id_edit{{ $person->id }}" name="location_id" value="{{ old('location_id', $person->location_id) }}">
                        <small class="form-text text-muted">سيتم البحث تلقائياً في الأماكن الموجودة</small>
                        <div id="location_suggestions_edit{{ $person->id }}" class="list-group mt-2" style="display: none; position: absolute; z-index: 1000; max-height: 200px; overflow-y: auto; width: 100%;"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="death_place_edit{{ $person->id }}">مكان الوفاة</label>
                                <input type="text" class="form-control" id="death_place_edit{{ $person->id }}"
                                    name="death_place" value="{{ old('death_place', $person->death_place) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cemetery_edit{{ $person->id }}">المقبرة</label>
                                <input type="text" class="form-control" id="cemetery_edit{{ $person->id }}"
                                    name="cemetery" value="{{ old('cemetery', $person->cemetery) }}">
                            </div>
                        </div>
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

{{--
    ==================================================================
    IMPORTANT: This JavaScript code should be placed in a global JS file
    or at the bottom of your main layout file, NOT inside the loop.
    The code you provided is correct and does not need changes.
    ==================================================================
--}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // This code block should only be included ONCE on the page.
        const allFatherSelects = document.querySelectorAll('.js-father-select');

        allFatherSelects.forEach(fatherSelect => {
            fatherSelect.addEventListener('change', function() {
                const fatherId = this.value;

                // Find the mother's select list within the same modal
                const modal = this.closest('.modal-content');
                const motherSelect = modal.querySelector('.js-mother-select');

                if (!motherSelect) return;

                motherSelect.innerHTML = '<option value="">-- جار التحميل --</option>';

                if (!fatherId) {
                    motherSelect.innerHTML = '<option value="">-- اختر الأب أولاً --</option>';
                    return;
                }

                // Fetch the wives for the selected father
                fetch(`{{ url('/people') }}/${fatherId}/wives`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(wives => {
                        motherSelect.innerHTML =
                            '<option value="">-- اختر الأم --</option>';
                        wives.forEach(wife => {
                            const option = document.createElement('option');
                            option.value = wife.id;
                            option.textContent = wife.full_name;
                            motherSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching wives:', error);
                        motherSelect.innerHTML = '<option value="">-- حدث خطأ --</option>';
                    });
            });
        });
    });
</script>

{{-- JavaScript للـ Autocomplete للأماكن في نموذج التعديل --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // تهيئة Autocomplete لكل نموذج تعديل
        document.querySelectorAll('.location-autocomplete').forEach(function(locationInput) {
            const locationIdInput = document.getElementById(locationInput.id.replace('location_edit', 'location_id_edit').replace('create_location', 'create_location_id'));
            const suggestionsDiv = document.getElementById(locationInput.id.replace('location_edit', 'location_suggestions_edit').replace('create_location', 'create_location_suggestions'));
            let searchTimeout;

            if (locationInput && locationIdInput && suggestionsDiv) {
                locationInput.addEventListener('input', function() {
                    const query = this.value.trim();

                    clearTimeout(searchTimeout);
                    
                    if (query.length < 2) {
                        suggestionsDiv.style.display = 'none';
                        if (locationIdInput) locationIdInput.value = '';
                        return;
                    }

                    searchTimeout = setTimeout(function() {
                        fetch('{{ route("locations.autocomplete") }}?q=' + encodeURIComponent(query))
                            .then(response => response.json())
                            .then(data => {
                                suggestionsDiv.innerHTML = '';
                                
                                if (data.length === 0) {
                                    suggestionsDiv.innerHTML = '<div class="list-group-item text-muted">لا توجد نتائج</div>';
                                    suggestionsDiv.style.display = 'block';
                                    return;
                                }

                                data.forEach(function(location) {
                                    const item = document.createElement('div');
                                    item.className = 'list-group-item list-group-item-action';
                                    item.style.cursor = 'pointer';
                                    item.innerHTML = `
                                        <strong>${location.name}</strong>
                                        ${location.persons_count > 0 ? `<small class="text-muted">(${location.persons_count} شخص)</small>` : ''}
                                    `;
                                    
                                    item.addEventListener('click', function() {
                                        locationInput.value = location.name;
                                        if (locationIdInput) locationIdInput.value = location.id;
                                        suggestionsDiv.style.display = 'none';
                                    });
                                    
                                    suggestionsDiv.appendChild(item);
                                });
                                
                                suggestionsDiv.style.display = 'block';
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    }, 300);
                });

                // إخفاء الاقتراحات عند النقر خارجها
                document.addEventListener('click', function(e) {
                    if (!locationInput.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                        suggestionsDiv.style.display = 'none';
                    }
                });
            }
        });
    });
</script>
