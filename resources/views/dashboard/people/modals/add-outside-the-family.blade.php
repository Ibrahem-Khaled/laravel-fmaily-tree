<div class="modal fade" id="addPersonsOutsideTheFamilyTreeModal" tabindex="-1" role="dialog"
    aria-labelledby="addPersonsOutsideModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPersonsOutsideModalLabel">إضافة زوج/زوجة من خارج العائلة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{-- Make sure the route name 'persons.store.outside' is defined in your routes file --}}
            <form action="{{ route('persons.store.outside') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    {{-- Name Fields --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="outside_first_name">الاسم الأول</label>
                                <input type="text" class="form-control" id="outside_first_name" name="first_name"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="outside_last_name">الاسم الأخير</label>
                                <input type="text" class="form-control" id="outside_last_name" name="last_name">
                            </div>
                        </div>
                    </div>

                    {{-- Birth and Death Dates --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="outside_birth_date">تاريخ الميلاد</label>
                                <input type="date" class="form-control" id="outside_birth_date" name="birth_date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="outside_death_date">تاريخ الوفاة (إن وجد)</label>
                                <input type="date" class="form-control" id="outside_death_date" name="death_date">
                            </div>
                        </div>
                    </div>

                    {{-- Gender and Partner Selection --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="outside_gender">الجنس</label>
                                <select class="form-control no-search" id="outside_gender" name="gender" required>
                                    <option value="">-- اختر الجنس --</option>
                                    <option value="male">ذكر</option>
                                    <option value="female">أنثى</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="marrying_person_id">ربط كـ زوج/زوجة لـ</label>
                                {{-- This dropdown will be populated dynamically by JavaScript --}}
                                <select class="form-control" id="marrying_person_id" name="marrying_person_id">
                                    <option value="">-- اختر أولاً جنس الشخص الجديد --</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Other Fields (Occupation, Location, Biography, Photo) --}}
                    <div class="form-group">
                        <label for="outside_occupation">المهنة</label>
                        <input type="text" class="form-control" id="outside_occupation" name="occupation">
                    </div>

                    <div class="form-group">
                        <label for="outside_location">مكان الإقامة</label>
                        <input type="text" class="form-control" id="outside_location" name="location">
                    </div>

                    <div class="form-group">
                        <label for="outside_biography">سيرة ذاتية</label>
                        <textarea class="form-control" id="outside_biography" name="biography" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="outside_photo">صورة الشخص</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="outside_photo" name="photo">
                            <label class="custom-file-label" for="outside_photo">اختر ملف</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Add this script at the end of your view, or in a dedicated JS file --}}
<script>
    // Ensure the controller passes $males and $females to the view
    const males = @json($males ?? []);
    const females = @json($females ?? []);

    document.addEventListener('DOMContentLoaded', function() {
        const genderSelect = document.getElementById('outside_gender');
        const partnerSelect = document.getElementById('marrying_person_id');

        genderSelect.addEventListener('change', function() {
            const selectedGender = this.value;

            // Clear current options
            partnerSelect.innerHTML = '';

            // Add a default option
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = '-- اختياري --';
            partnerSelect.appendChild(defaultOption);

            let optionsList = [];
            if (selectedGender === 'male') {
                // If the new person is male, show females as potential partners
                optionsList = females;
            } else if (selectedGender === 'female') {
                // If the new person is female, show males as potential partners
                optionsList = males;
            } else {
                // If no gender is selected, reset the placeholder text
                defaultOption.textContent = '-- اختر أولاً جنس الشخص الجديد --';
            }

            // Populate the partner dropdown
            optionsList.forEach(function(person) {
                const option = document.createElement('option');
                option.value = person.id;
                // This relies on the `full_name` accessor in your Person model
                option.textContent = person.full_name;
                partnerSelect.appendChild(option);
            });
            $('#marrying_person_id').trigger('change');

        });
    });
</script>
