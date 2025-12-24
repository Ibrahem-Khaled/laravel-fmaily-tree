<div class="modal fade" id="quickCategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="quickCategoryForm" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-folder-plus mr-2"></i>إنشاء فئة جديدة</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label>الاسم <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required maxlength="255">
                </div>

                <div class="form-group">
                    <label>الوصف</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>أصل/فئة أب (اختياري)</label>
                            <select name="parent_id" class="form-control">
                                <option value="">— بدون —</option>
                                @foreach (\App\Models\Category::ordered()->get() as $pc)
                                    <option value="{{ $pc->id }}">{{ $pc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>الترتيب (اختياري)</label>
                            <input type="number" name="sort_order" class="form-control" min="0" value="0">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>صورة (اختياري)</label>
                    <div class="custom-file">
                        <input type="file" name="image" class="custom-file-input" id="catImgInput">
                        <label class="custom-file-label" for="catImgInput">اختر صورة...</label>
                    </div>
                </div>

                {{-- قسم القائمون على البرنامج --}}
                <hr class="my-3">
                <div class="form-group">
                    <label class="font-weight-bold">
                        <i class="fas fa-users text-primary"></i>
                        القائمون على البرنامج
                        <small class="text-muted">(اختياري)</small>
                    </label>
                    
                    {{-- قائمة القائمين المضافة --}}
                    <div id="quick-managers-list" class="mb-3" style="max-height: 200px; overflow-y: auto;">
                        <div class="list-group" id="quick-sortable-managers">
                            <!-- سيتم إضافة القائمين هنا ديناميكياً -->
                        </div>
                    </div>

                    {{-- إضافة قائم جديد --}}
                    <div class="input-group">
                        @php
                            $allPersons = \App\Models\Person::orderBy('first_name')->orderBy('last_name')->get();
                        @endphp
                        <select class="form-control" id="quick-person-select">
                            <option value="">-- اختر شخص للإضافة --</option>
                            @foreach($allPersons as $person)
                                <option value="{{ $person->id }}" 
                                        data-name="{{ $person->full_name }}" 
                                        data-avatar="{{ $person->avatar }}">
                                    {{ $person->full_name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-success" id="quick-add-manager-btn">
                                <i class="fas fa-plus"></i> إضافة
                            </button>
                        </div>
                    </div>
                    <small class="text-muted">اختر من القائمة ثم اضغط إضافة</small>
                </div>

            </div>
            <div class="modal-footer">
                @csrf
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                <button id="quickCategorySubmit" type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> حفظ الفئة
                </button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    // قائمة القائمين المضافة
    let quickManagers = [];

    // إضافة قائم جديد
    $('#quick-add-manager-btn').on('click', function() {
        const select = $('#quick-person-select');
        const personId = select.val();
        const selectedOption = select.find('option:selected');
        const personName = selectedOption.data('name') || selectedOption.text();
        const personAvatar = selectedOption.data('avatar') || "{{ asset('assets/img/avatar-male.png') }}";
        
        if (!personId) {
            alert('يرجى اختيار شخص من القائمة');
            return;
        }

        // التحقق من عدم التكرار
        if (quickManagers.includes(personId)) {
            alert('هذا الشخص موجود بالفعل في القائمة');
            return;
        }

        // إضافة للقائمة
        quickManagers.push(personId);

        // إضافة للعرض
        const managerHtml = `
            <div class="list-group-item d-flex justify-content-between align-items-center py-2" data-person-id="${personId}">
                <div class="d-flex align-items-center">
                    <img src="${personAvatar}" alt="${personName}" class="rounded-circle mr-2" width="36" height="36" style="object-fit: cover;" onerror="this.src='{{ asset('assets/img/avatar-male.png') }}'">
                    <span class="font-weight-medium">${personName}</span>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger quick-remove-manager-btn" data-person-id="${personId}">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;

        $('#quick-sortable-managers').append(managerHtml);
        select.val('').trigger('change');
    });

    // حذف قائم
    $(document).on('click', '.quick-remove-manager-btn', function() {
        const personId = $(this).data('person-id').toString();
        quickManagers = quickManagers.filter(id => id.toString() !== personId);
        $(this).closest('.list-group-item').remove();
    });

    // إنشاء فئة سريع via AJAX
    $('#quickCategoryForm').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        const fd = new FormData(form);
        
        // إضافة القائمين على البرنامج
        quickManagers.forEach((personId, index) => {
            fd.append('managers[]', personId);
        });
        
        const btn = $('#quickCategorySubmit');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> جارٍ الحفظ...');

        $.ajax({
            url: "{{ route('categories.quick-store') }}",
            method: "POST",
            data: fd,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(resp) {
                if (resp.ok && resp.category) {
                    // إضافة الفئة الجديدة للقائمة المنسدلة
                    const $select = $('select[name="category_id"]');
                    $select.append(new Option(resp.category.name, resp.category.id, true, true));
                    
                    // إغلاق الـ modal وإعادة تعيين النموذج
                    $('#quickCategoryModal').modal('hide');
                    form.reset();
                    $('.custom-file-label').text('اختر صورة...');
                    quickManagers = [];
                    $('#quick-sortable-managers').empty();
                    
                    // رسالة نجاح
                    if (typeof toastr !== 'undefined') {
                        toastr.success('تم إنشاء الفئة بنجاح');
                    } else {
                        alert('تم إنشاء الفئة بنجاح');
                    }
                } else {
                    alert('حدث خطأ أثناء إنشاء الفئة');
                }
            },
            error: function(xhr) {
                let message = 'تعذر إنشاء الفئة. تحقق من المدخلات.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    message = Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                alert(message);
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> حفظ الفئة');
            }
        });
    });

    // إعادة تعيين عند إغلاق الـ modal
    $('#quickCategoryModal').on('hidden.bs.modal', function() {
        quickManagers = [];
        $('#quick-sortable-managers').empty();
        $('#quickCategoryForm')[0].reset();
        $('.custom-file-label').text('اختر صورة...');
        $('#quick-person-select').val('').trigger('change');
    });

    // عرض أسماء الملفات المختارة
    $(document).on('change', '.custom-file-input', function() {
        const fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName || 'اختر صورة...');
    });
});
</script>
