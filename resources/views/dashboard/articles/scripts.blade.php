<script>
$(document).ready(function() {

    /**
     * ==================================================================
     * المنطق الخاص برفع الصور في مودال (الإنشاء) و (التعديل)
     * ==================================================================
     */

    // دالة لإضافة حقل جديد لرفع صورة
    function addImageRow(container) {
        // نستخدم التوقيت الحالي لضمان أن يكون الفهرس فريداً
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

    // --- معالجات الأحداث لمودال الإنشاء والتعديل ---

    // عند فتح مودال "إنشاء مقال"
    $('#createArticleModal').on('shown.bs.modal', function() {
        const container = $(this).find('.image-uploader-container');
        if (container.children().length === 0) {
            addImageRow(container);
        }
    });

    // عند فتح أي مودال "تعديل مقال"
    $('.edit-article-modal').on('shown.bs.modal', function() {
        const container = $(this).find('.image-uploader-container');
        if (container.children().length === 0) {
            addImageRow(container);
        }
    });

    // عند إغلاق أي مودال (إنشاء أو تعديل)، يتم إفراغ الحقول الديناميكية
    // هذا لا يؤثر على مودال إضافة الصور الجديد لأنه يستخدم class مختلف للحاوية
    $('#createArticleModal, .edit-article-modal').on('hidden.bs.modal', function () {
        $(this).find('.image-uploader-container').empty();
    });

    // عند الضغط على زر "إضافة صورة أخرى" في مودالات (الإنشاء والتعديل)
    $('.modal').on('click', '.add-image-btn', function() {
        const container = $(this).closest('.modal-body').find('.image-uploader-container');
        addImageRow(container);
    });

    // عند الضغط على زر حذف حقل صورة تمت إضافته ديناميكياً
    $(document).on('click', '.remove-image-btn', function() {
        const rowId = $(this).data('row-id');
        $('#image-row-' + rowId).remove();
    });

    // تحديث اسم الملف في الحقل بعد اختياره (يعمل لكل حقول رفع الملفات)
    $(document).on('change', '.custom-file-input', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName || 'اختر صورة...');
    });


    /**
     * ==================================================================
     * المنطق الخاص بـ "مودال إضافة الصور لمقال موجود" (الكود الجديد)
     * ==================================================================
     */

    // دالة لإضافة حقل جديد لرفع صورة (خاصة بمودال إضافة الصور فقط)
    function addImageRowForModal(container) {
        const rowIndex = 'modal-' + Date.now();
        const newRowHtml = `
            <div class="input-group mb-2" id="image-row-${rowIndex}">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="images[${rowIndex}][file]" required>
                    <label class="custom-file-label">اختر صورة...</label>
                </div>
                <input type="text" class="form-control col-4" name="images[${rowIndex}][name]" placeholder="عنوان اختياري للصورة">
                <div class="input-group-append">
                    <button class="btn btn-outline-danger remove-image-btn-modal" type="button" data-row-id="${rowIndex}">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>`;
        container.append(newRowHtml);
    }

    // عند فتح مودال "إضافة الصور"
    $('#addImagesModal').on('shown.bs.modal', function() {
        const container = $(this).find('.add-images-uploader-container');
        if (container.children().length === 0) {
            addImageRowForModal(container);
        }
    });

    // عند إغلاق مودال "إضافة الصور"، يتم إفراغ الحقول وإعادة تعيين القائمة
    $('#addImagesModal').on('hidden.bs.modal', function () {
        $(this).find('.add-images-uploader-container').empty();
        $(this).find('#article_id_modal').val('');
    });

    // عند الضغط على زر "إضافة حقل صورة آخر" داخل مودال إضافة الصور
    $('#addImagesModal').on('click', '.add-new-image-btn', function() {
        const container = $(this).closest('.form-group').find('.add-images-uploader-container');
        addImageRowForModal(container);
    });

    // عند الضغط على زر الحذف لحقل معين داخل مودال إضافة الصور
    $('#addImagesModal').on('click', '.remove-image-btn-modal', function() {
        if ($('.add-images-uploader-container .input-group').length > 1) {
            const rowId = $(this).data('row-id');
            $('#image-row-' + rowId).remove();
        } else {
            alert('يجب إضافة صورة واحدة على الأقل.');
        }
    });


    /**
     * ==================================================================
     * عمليات AJAX (حذف صورة، إضافة فئة)
     * ==================================================================
     */

    // AJAX لحذف صورة موجودة بالفعل في مقال
    $('.modal').on('click', '.delete-image-btn', function() {
        const imageId = $(this).data('id');
        if (!confirm('هل أنت متأكد من حذف هذه الصورة؟')) return;

        $.ajax({
            url: '/articles/image/' + imageId, // تأكد من أن هذا الراوت معرف في web.php
            type: 'DELETE',
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.success) {
                    // ===== تم إصلاح الخطأ هنا =====
                    // الكود القديم كان .parent().remove() وهو يحذف كل الصور
                    // الكود الصحيح يحذف فقط الـ div الخاص بالصورة
                    $('#image-' + imageId).remove();
                }
            },
            error: function() {
                alert('حدث خطأ أثناء حذف الصورة.');
            }
        });
    });

    // AJAX لإنشاء فئة جديدة من داخل مودال المقال
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
            url: "{{ route('categories.store.ajax') }}", // يفترض وجود راوت بهذا الاسم
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                name: name,
                parent_id: parent_id,
            },
            success: function(response) {
                if (response.success) {
                    const newCategory = response.category;
                    const option = new Option(newCategory.name, newCategory.id, true, true);
                    $('#category_id_select').append(option).trigger('change');

                    // أضف الفئة الجديدة إلى كل قوائم التعديل أيضاً
                    $('.edit-article-modal select[name="category_id"]').append(new Option(newCategory.name, newCategory.id));

                    $('#createCategoryModal').modal('hide');
                    $('#new_category_name').val('');
                    $('#new_category_parent').val('');
                }
            },
            error: function(xhr) {
                // عرض أخطاء التحقق
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
