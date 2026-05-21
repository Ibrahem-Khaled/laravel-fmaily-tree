@php
    /** @var \App\Models\Person|null $person */
    /** @var \Illuminate\Support\Collection|\Illuminate\Database\Eloquent\Collection $males */

    $isEdit = isset($person) && $person && $person->exists;
    $selectedFatherId = old('parent_id', $isEdit ? $person->parent_id : '');
    $selectedMotherId = old('mother_id', $isEdit ? $person->mother_id : '');

    $father = $isEdit ? $person->parent : null;
    $wives = $father ? ($father->wives ?? collect()) : collect();
@endphp

<x-dashboard-card :title="$isEdit ? 'تعديل بيانات الشخص' : 'إضافة شخص جديد'" icon="fe-user">
        {{-- Name Fields --}}
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="first_name">الاسم الأول</label>
                    <input type="text" class="form-control" id="first_name" name="first_name"
                        value="{{ old('first_name', $isEdit ? $person->first_name : '') }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="last_name">الاسم الأخير</label>
                    <input type="text" class="form-control" id="last_name" name="last_name"
                        value="{{ old('last_name', $isEdit ? $person->last_name : '') }}">
                </div>
            </div>
        </div>

        {{-- Birth and Death Dates --}}
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="birth_date">تاريخ الميلاد</label>
                    <input type="date" class="form-control" id="birth_date" name="birth_date"
                        value="{{ old('birth_date', $isEdit && $person->birth_date ? $person->birth_date->format('Y-m-d') : '') }}">
                    <div id="birth_date_hijri_container" class="mt-2" style="display: none;">
                        <span class="badge badge-pill badge-info py-2 px-3" style="font-size: 0.85rem; border-radius: 50px;">
                            <i class="fas fa-calendar-alt mr-1"></i> التاريخ الهجري: <span id="birth_date_hijri_val"></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="death_date">تاريخ الوفاة (إن وجد)</label>
                    <input type="date" class="form-control" id="death_date" name="death_date"
                        value="{{ old('death_date', $isEdit && $person->death_date ? $person->death_date->format('Y-m-d') : '') }}">
                    <div id="death_date_hijri_container" class="mt-2" style="display: none;">
                        <span class="badge badge-pill badge-info py-2 px-3" style="font-size: 0.85rem; border-radius: 50px;">
                            <i class="fas fa-calendar-alt mr-1"></i> التاريخ الهجري: <span id="death_date_hijri_val"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Gender + Family Status --}}
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="gender">الجنس</label>
                    <select class="form-control" id="gender" name="gender" required>
                        <option value="male" @selected(old('gender', $isEdit ? $person->gender : 'male') == 'male')>ذكر</option>
                        <option value="female" @selected(old('gender', $isEdit ? $person->gender : '') == 'female')>أنثى</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="from_outside_the_family">الحالة العائلية</label>
                    <select class="form-control" id="from_outside_the_family" name="from_outside_the_family">
                        <option value="0" @selected(old('from_outside_the_family', $isEdit ? (int) $person->from_outside_the_family : 0) == 0)>
                            داخل العائلة
                        </option>
                        <option value="1" @selected(old('from_outside_the_family', $isEdit ? (int) $person->from_outside_the_family : 0) == 1)>
                            خارج العائلة
                        </option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Father / Mother --}}
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="parent_id">الأب</label>
                    <select class="form-control js-father-select" id="parent_id" name="parent_id">
                        <option value="">-- اختر الأب --</option>
                        @foreach ($males as $fatherOption)
                            <option value="{{ $fatherOption->id }}" @selected((string) $selectedFatherId === (string) $fatherOption->id)>
                                {{ $fatherOption->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="mother_id">الأم</label>
                    <select class="form-control js-mother-select" id="mother_id" name="mother_id">
                        @if ($selectedFatherId)
                            <option value="">-- اختر الأم --</option>
                            @foreach ($wives as $wife)
                                <option value="{{ $wife->id }}" @selected((string) $selectedMotherId === (string) $wife->id)>
                                    {{ $wife->full_name }}
                                </option>
                            @endforeach
                        @else
                            <option value="">-- اختر الأب أولاً --</option>
                        @endif
                    </select>
                </div>
            </div>
        </div>

        {{-- Other Fields --}}
        <div class="form-group">
            <label for="birth_place">مكان الميلاد</label>
            <input type="text" class="form-control" id="birth_place" name="birth_place"
                value="{{ old('birth_place', $isEdit ? $person->birth_place : '') }}">
        </div>

        <div class="form-group">
            <label for="occupation">المهنة</label>
            <input type="text" class="form-control" id="occupation" name="occupation"
                value="{{ old('occupation', $isEdit ? $person->occupation : '') }}">
        </div>

        {{-- Location (text + id) --}}
        <div class="form-group position-relative">
            <label for="location">مكان الإقامة</label>
            <input type="text" class="form-control js-location-autocomplete" id="location" name="location"
                value="{{ old('location', $isEdit ? ($person->location ? $person->location->display_name : '') : '') }}"
                autocomplete="off" placeholder="ابدأ الكتابة للبحث...">
            <input type="hidden" id="location_id" name="location_id"
                value="{{ old('location_id', $isEdit ? $person->location_id : '') }}">
            <div id="location_suggestions" class="list-group mt-2"
                style="display: none; position: absolute; z-index: 1000; max-height: 200px; overflow-y: auto; width: 100%;">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="death_place">مكان الوفاة</label>
                    <input type="text" class="form-control" id="death_place" name="death_place"
                        value="{{ old('death_place', $isEdit ? $person->death_place : '') }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="cemetery">المقبرة</label>
                    <input type="text" class="form-control" id="cemetery" name="cemetery"
                        value="{{ old('cemetery', $isEdit ? $person->cemetery : '') }}">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="cemetery_location">لوكيشن القبر</label>
                    <input type="text" class="form-control" id="cemetery_location" name="cemetery_location"
                        value="{{ old('cemetery_location', $isEdit ? $person->cemetery_location : '') }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="grave_number">رقم القبر</label>
                    <input type="text" class="form-control" id="grave_number" name="grave_number"
                        value="{{ old('grave_number', $isEdit ? $person->grave_number : '') }}">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="biography">سيرة ذاتية</label>
            <textarea class="form-control" id="biography" name="biography" rows="4">{{ old('biography', $isEdit ? $person->biography : '') }}</textarea>
        </div>

        <div class="form-group">
            <label for="photo">صورة الشخص</label>
            @if ($isEdit && $person->photo_url)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $person->photo_url) }}" alt="{{ $person->full_name }}"
                        class="img-thumbnail" style="max-height: 120px;">
                </div>
            @endif
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="photo" name="photo">
                <label class="custom-file-label" for="photo">اختر ملف</label>
            </div>
        </div>
</x-dashboard-card>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const gregorianToHijri = (gregorianDate) => {
        if (!gregorianDate) return '';
        try {
            let trimmed = String(gregorianDate).trim().replace(/\//g, '-');
            if (!trimmed || trimmed.startsWith('0000')) return '';
            const normalized = trimmed.includes('T') ? trimmed : trimmed + 'T12:00:00Z';
            const date = new Date(normalized);
            if (isNaN(date.getTime())) return '';
            const formatter = new Intl.DateTimeFormat('ar-SA-u-ca-islamic-umalqura', {
                timeZone: 'UTC',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            const parts = formatter.formatToParts(date);
            const day   = parts.find(p => p.type === 'day').value;
            const month = parts.find(p => p.type === 'month').value;
            const year  = parts.find(p => p.type === 'year').value;
            return `${day} ${month} ${year} هـ`;
        } catch (e) {
            console.error('Error converting date:', e);
            return '';
        }
    };

    function updateHijriPreview(inputEl, containerEl, valEl) {
        const val = inputEl.value;
        const hijri = gregorianToHijri(val);
        if (hijri) {
            valEl.textContent = hijri;
            containerEl.style.display = 'inline-block';
        } else {
            containerEl.style.display = 'none';
        }
    }

    const birthInput = document.getElementById('birth_date');
    const birthContainer = document.getElementById('birth_date_hijri_container');
    const birthVal = document.getElementById('birth_date_hijri_val');

    const deathInput = document.getElementById('death_date');
    const deathContainer = document.getElementById('death_date_hijri_container');
    const deathVal = document.getElementById('death_date_hijri_val');

    if (birthInput && birthContainer && birthVal) {
        birthInput.addEventListener('input', () => updateHijriPreview(birthInput, birthContainer, birthVal));
        updateHijriPreview(birthInput, birthContainer, birthVal); // Initial check
    }

    if (deathInput && deathContainer && deathVal) {
        deathInput.addEventListener('input', () => updateHijriPreview(deathInput, deathContainer, deathVal));
        updateHijriPreview(deathInput, deathContainer, deathVal); // Initial check
    }
});
</script>

