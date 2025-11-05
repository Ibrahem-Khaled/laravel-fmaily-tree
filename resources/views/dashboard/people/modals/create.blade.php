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

                    {{-- Family Status --}}
                    <div class="form-group">
                        <label for="create_family_status">الحالة العائلية</label>
                        <select class="form-control" id="create_family_status" name="from_outside_the_family">
                            <option value="0" @selected(old('from_outside_the_family', '0') == '0')>
                                داخل العائلة
                            </option>
                            <option value="1" @selected(old('from_outside_the_family') == '1')>
                                خارج العائلة
                            </option>
                        </select>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i>
                            الأشخاص خارج العائلة: الأصدقاء، الجيران، أو أي شخص ليس من أفراد العائلة المباشرين
                        </small>
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
                        <label for="create_birth_place">مكان الميلاد</label>
                        <input type="text" class="form-control" id="create_birth_place" name="birth_place"
                            value="{{ old('birth_place') }}">
                    </div>

                    <div class="form-group">
                        <label for="create_occupation">المهنة</label>
                        <input type="text" class="form-control" id="create_occupation" name="occupation"
                            value="{{ old('occupation') }}">
                    </div>

                    <div class="form-group">
                        <label for="create_location">مكان الإقامة</label>
                        <input type="text" class="form-control location-autocomplete" id="create_location" name="location"
                            value="{{ old('location') }}" autocomplete="off" placeholder="ابدأ الكتابة للبحث...">
                        <input type="hidden" id="create_location_id" name="location_id" value="{{ old('location_id') }}">
                        <small class="form-text text-muted">سيتم البحث تلقائياً في الأماكن الموجودة</small>
                        <div id="create_location_suggestions" class="list-group mt-2" style="display: none; position: absolute; z-index: 1000; max-height: 200px; overflow-y: auto; width: 100%;"></div>
                    </div>

                    {{-- Death Place and Cemetery --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="create_death_place">مكان الوفاة</label>
                                <input type="text" class="form-control" id="create_death_place" name="death_place"
                                    value="{{ old('death_place') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="create_cemetery">المقبرة</label>
                                <input type="text" class="form-control" id="create_cemetery" name="cemetery"
                                    value="{{ old('cemetery') }}">
                            </div>
                        </div>
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

{{-- JavaScript للـ Autocomplete للأماكن --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const locationInput = document.getElementById('create_location');
        const locationIdInput = document.getElementById('create_location_id');
        const suggestionsDiv = document.getElementById('create_location_suggestions');
        let searchTimeout;

        if (locationInput && locationIdInput && suggestionsDiv) {
            locationInput.addEventListener('input', function() {
                const query = this.value.trim();

                clearTimeout(searchTimeout);
                
                if (query.length < 2) {
                    suggestionsDiv.style.display = 'none';
                    locationIdInput.value = '';
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
                                    locationIdInput.value = location.id;
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
</script>
