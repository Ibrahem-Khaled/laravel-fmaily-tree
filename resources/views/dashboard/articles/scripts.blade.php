<script>
$(document).ready(function() {

    // ----- Dynamic Image Uploader (Refactored for multiple modals) -----
    function addImageRow(container) {
        // نستخدم الوقت الحالي لضمان أن الفهرس فريد تماماً في كل مرة
        const rowIndex = Date.now();
        const newRow = `
            <div class="input-group mb-2" id="image-row-${rowIndex}">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="images[${rowIndex}][file]" required>
                    <label class="custom-file-label">اختر صورة...</label>
                </div>
                <input type="text" class="form-control col-4" name="images[${rowIndex}][name]" placeholder="عنوان اختياري للصورة">
                <div class="input-group-append">
                    <button class="btn btn-outline-danger remove-image-btn" type="button" data-row-id="${rowIndex}">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>`;
        container.append(newRow);
    }

    // -- Event Listeners for MODALS --

    // عند فتح أي مودال "إنشاء"
    $('#createArticleModal').on('shown.bs.modal', function() {
        const container = $(this).find('.image-uploader-container');
        if (container.children().length === 0) {
            addImageRow(container);
        }
    });

    // عند فتح أي مودال "تعديل"
    $('.edit-article-modal').on('shown.bs.modal', function() {
        // 'this' هنا يشير إلى المودال الذي تم فتحه
        const container = $(this).find('.image-uploader-container');
        // نضيف أول حقل فقط إذا كان فارغاً
        if (container.children().length === 0) {
            addImageRow(container);
        }
    });

    // عند إغلاق أي مودال تعديل، قم بإفراغ حقول الصور المضافة ديناميكياً
    $('.edit-article-modal').on('hidden.bs.modal', function () {
        $(this).find('.image-uploader-container').empty();
    });

    // استخدام "event delegation" لزر إضافة صورة داخل أي مودال
    $('.modal').on('click', '.add-image-btn', function() {
        // ابحث عن الحاوية الأقرب داخل المودال الحالي
        const container = $(this).closest('.modal-body').find('.image-uploader-container');
        addImageRow(container);
    });

    // استخدام "event delegation" لزر حذف صف صورة
    $(document).on('click', '.remove-image-btn', function() {
        const rowId = $(this).data('row-id');
        $('#image-row-' + rowId).remove();
    });

    // استخدام "event delegation" لتحديث اسم الملف في الـ label
    $(document).on('change', '.custom-file-input', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName || 'اختر صورة...');
    });


    // ----- AJAX for Deleting Existing Images -----
    $('.modal').on('click', '.delete-image-btn', function() {
        const imageId = $(this).data('id');
        if (!confirm('هل أنت متأكد من حذف هذه الصورة؟')) return;

        $.ajax({
            url: '/articles/image/' + imageId, // تأكد أن هذا المسار صحيح
            type: 'DELETE',
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.success) {
                    $('#image-' + imageId).remove(); // إزالة العنصر من الواجهة
                }
            },
            error: function() {
                alert('حدث خطأ أثناء الحذف');
            }
        });
    });

    // ----- AJAX for Creating Category (No changes needed here) -----
    // ... الكود الخاص بإنشاء فئة جديدة يبقى كما هو ...
    $('#createCategoryModal').on('shown.bs.modal', function() {
        const mainCategorySelect = $('#category_id_select');
        const parentCategorySelect = $('#new_category_parent');
        parentCategorySelect.html('<option value="">-- بدون فئة رئيسية --</option>'); // Reset
        mainCategorySelect.find('option').each(function() {
            if ($(this).val() !== "") {
                parentCategorySelect.append($(this).clone());
            }
        });
    });

    $('#save-category-ajax-btn').on('click', function() {
        const name = $('#new_category_name').val();
        const parent_id = $('#new_category_parent').val();

        $.ajax({
            url: "{{ route('categories.store.ajax') }}",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                name: name,
                parent_id: parent_id,
            },
            success: function(response) {
                if (response.success) {
                    const newCategory = response.category;
                    const option = new Option(newCategory.name, newCategory.id, true, true); // Create and select
                    $('#category_id_select').append(option).trigger('change');
                    // Add to all edit modals category dropdowns as well
                    $('.edit-article-modal select[name="category_id"]').append(new Option(newCategory.name, newCategory.id));
                    $('#createCategoryModal').modal('hide');
                    $('#new_category_name').val('');
                    $('#new_category_parent').val('');
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON.errors;
                let alertHtml = '<div class="alert alert-danger"><ul>';
                $.each(errors, function(key, value) {
                    alertHtml += '<li>' + value[0] + '</li>';
                });
                alertHtml += '</ul></div>';
                $('#category-ajax-alert').html(alertHtml);
            }
        });
    });
});
</script>
