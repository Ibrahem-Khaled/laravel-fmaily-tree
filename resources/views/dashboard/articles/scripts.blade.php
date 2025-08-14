<script>
    $(document).ready(function() {
        let imageRowIndex = 0;

        // ----- Dynamic Image Uploader -----
        function addImageRow() {
            const newRow = `
            <div class="input-group mb-2" id="image-row-${imageRowIndex}">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="images[${imageRowIndex}][file]" required>
                    <label class="custom-file-label">اختر صورة...</label>
                </div>
                <input type="text" class="form-control col-4" name="images[${imageRowIndex}][name]" placeholder="عنوان اختياري للصورة">
                <div class="input-group-append">
                    <button class="btn btn-outline-danger remove-image-btn" type="button" data-row-id="${imageRowIndex}">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>`;
            $('#image-uploader-container').append(newRow);
            imageRowIndex++;
        }

        // Add first row on modal open
        $('#createArticleModal').on('shown.bs.modal', function() {
            if ($('#image-uploader-container').children().length === 0) {
                addImageRow();
            }
        });

        // Add more rows
        $('#add-image-btn').on('click', function() {
            addImageRow();
        });

        // Remove a row
        $('#image-uploader-container').on('click', '.remove-image-btn', function() {
            const rowId = $(this).data('row-id');
            $('#image-row-' + rowId).remove();
        });

        // Update file name in label
        $('#image-uploader-container').on('change', '.custom-file-input', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
        });

        // ----- AJAX for Creating Category -----
        // Populate parent category options in the create category modal
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
                        // Add the new category to the main select dropdown
                        const option = new Option(newCategory.name, newCategory.id);
                        $('#category_id_select').append(option);
                        // Select it
                        $('#category_id_select').val(newCategory.id);
                        // Close the modal
                        $('#createCategoryModal').modal('hide');
                        // Clear fields for next time
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
