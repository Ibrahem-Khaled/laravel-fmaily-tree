<div class="modal fade" id="addFriendOutsideFamilyModal" tabindex="-1" role="dialog"
    aria-labelledby="addFriendOutsideFamilyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFriendOutsideFamilyModalLabel">إضافة صديق من خارج العائلة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addFriendOutsideFamilyForm" action="{{ route('persons.store.outside') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="source_page" value="friendships">
                <div class="modal-body">
                    {{-- Name Fields --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="friend_outside_first_name">الاسم الأول <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                    id="friend_outside_first_name" name="first_name" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="friend_outside_last_name">الاسم الأخير</label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                    id="friend_outside_last_name" name="last_name">
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Birth and Death Dates --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="friend_outside_birth_date">تاريخ الميلاد</label>
                                <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                    id="friend_outside_birth_date" name="birth_date">
                                @error('birth_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="friend_outside_death_date">تاريخ الوفاة (إن وجد)</label>
                                <input type="date" class="form-control @error('death_date') is-invalid @enderror" 
                                    id="friend_outside_death_date" name="death_date">
                                @error('death_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Gender --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="friend_outside_gender">الجنس <span class="text-danger">*</span></label>
                                <select class="form-control @error('gender') is-invalid @enderror" 
                                    id="friend_outside_gender" name="gender" required>
                                    <option value="">-- اختر الجنس --</option>
                                    <option value="male">ذكر</option>
                                    <option value="female">أنثى</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="friend_outside_occupation">المهنة</label>
                                <input type="text" class="form-control @error('occupation') is-invalid @enderror" 
                                    id="friend_outside_occupation" name="occupation">
                                @error('occupation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Location --}}
                    <div class="form-group">
                        <label for="friend_outside_location">مكان الإقامة</label>
                        <input type="text" class="form-control location-autocomplete @error('location') is-invalid @enderror" 
                            id="friend_outside_location" name="location" autocomplete="off" 
                            placeholder="ابدأ الكتابة للبحث...">
                        <input type="hidden" id="friend_outside_location_id" name="location_id">
                        <small class="form-text text-muted">سيتم البحث تلقائياً في الأماكن الموجودة</small>
                        <div id="friend_outside_location_suggestions" class="list-group mt-2" 
                            style="display: none; position: absolute; z-index: 1000; max-height: 200px; overflow-y: auto; width: 100%;"></div>
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Biography --}}
                    <div class="form-group">
                        <label for="friend_outside_biography">سيرة ذاتية</label>
                        <textarea class="form-control @error('biography') is-invalid @enderror" 
                            id="friend_outside_biography" name="biography" rows="3"></textarea>
                        @error('biography')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Photo --}}
                    <div class="form-group">
                        <label for="friend_outside_photo">صورة الشخص</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input @error('photo') is-invalid @enderror" 
                                id="friend_outside_photo" name="photo" accept="image/*">
                            <label class="custom-file-label" for="friend_outside_photo">اختر ملف</label>
                        </div>
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> حفظ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Location Autocomplete
        const friendLocationInput = document.getElementById('friend_outside_location');
        const friendLocationIdInput = document.getElementById('friend_outside_location_id');
        const friendSuggestionsDiv = document.getElementById('friend_outside_location_suggestions');
        let friendSearchTimeout;

        if (friendLocationInput && friendLocationIdInput && friendSuggestionsDiv) {
            friendLocationInput.addEventListener('input', function() {
                const query = this.value.trim();

                clearTimeout(friendSearchTimeout);
                
                if (query.length < 2) {
                    friendSuggestionsDiv.style.display = 'none';
                    friendLocationIdInput.value = '';
                    return;
                }

                friendSearchTimeout = setTimeout(function() {
                    fetch('{{ route("locations.autocomplete") }}?q=' + encodeURIComponent(query))
                        .then(response => response.json())
                        .then(data => {
                            friendSuggestionsDiv.innerHTML = '';
                            
                            if (data.length === 0) {
                                friendSuggestionsDiv.innerHTML = '<div class="list-group-item text-muted">لا توجد نتائج</div>';
                                friendSuggestionsDiv.style.display = 'block';
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
                                    friendLocationInput.value = location.name;
                                    friendLocationIdInput.value = location.id;
                                    friendSuggestionsDiv.style.display = 'none';
                                });
                                
                                friendSuggestionsDiv.appendChild(item);
                            });
                            
                            friendSuggestionsDiv.style.display = 'block';
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                }, 300);
            });

            // إخفاء الاقتراحات عند النقر خارجها
            document.addEventListener('click', function(e) {
                if (!friendLocationInput.contains(e.target) && !friendSuggestionsDiv.contains(e.target)) {
                    friendSuggestionsDiv.style.display = 'none';
                }
            });
        }

        // Handle form submission via AJAX
        const addFriendForm = document.getElementById('addFriendOutsideFamilyForm');
        if (addFriendForm) {
            addFriendForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;
                
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw data;
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // إضافة الشخص الجديد إلى قائمة الأصدقاء
                        const friendSelect = document.getElementById('friend_id');
                        if (friendSelect && data.person) {
                            const option = document.createElement('option');
                            option.value = data.person.id;
                            option.textContent = data.person.full_name;
                            option.selected = true;
                            friendSelect.appendChild(option);
                        }

                        // إغلاق المودال
                        $('#addFriendOutsideFamilyModal').modal('hide');
                        
                        // إعادة تعيين النموذج
                        addFriendForm.reset();
                        if (friendLocationIdInput) friendLocationIdInput.value = '';
                        if (friendSuggestionsDiv) friendSuggestionsDiv.style.display = 'none';
                        
                        // عرض رسالة نجاح
                        Swal.fire({
                            icon: 'success',
                            title: 'نجح',
                            text: data.message || 'تم إضافة الشخص بنجاح!',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            text: data.message || 'حدث خطأ أثناء إضافة الشخص',
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    let errorMessage = 'حدث خطأ أثناء إضافة الشخص';
                    
                    if (error.errors) {
                        // عرض أخطاء التحقق
                        const errorList = Object.values(error.errors).flat().join('<br>');
                        errorMessage = errorList;
                    } else if (error.message) {
                        errorMessage = error.message;
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        html: errorMessage,
                    });
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                });
            });
        }

        // Update custom file input label
        const friendPhotoInput = document.getElementById('friend_outside_photo');
        if (friendPhotoInput) {
            friendPhotoInput.addEventListener('change', function() {
                const label = this.nextElementSibling;
                if (this.files && this.files.length > 0) {
                    label.textContent = this.files[0].name;
                } else {
                    label.textContent = 'اختر ملف';
                }
            });
        }
    });
</script>

