<div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel{{ $category->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel{{ $category->id }}">تعديل الصنف: {{ $category->name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name-{{ $category->id }}">اسم الصنف <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name-{{ $category->id }}" name="name" value="{{ $category->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="description-{{ $category->id }}">الوصف</label>
                        <textarea class="form-control" id="description-{{ $category->id }}" name="description" rows="3">{{ $category->description }}</textarea>
                    </div>
                    <div class="form-row">
                         <div class="form-group col-md-6">
                            <label for="parent_id-{{ $category->id }}">الصنف الأب</label>
                            <select class="form-control" id="parent_id-{{ $category->id }}" name="parent_id">
                                <option value="">-- صنف رئيسي --</option>
                                @foreach($allCategories as $cat)
                                     @if($cat->id !== $category->id) {{-- منع اختيار الصنف نفسه كأب --}}
                                        <option value="{{ $cat->id }}" {{ $category->parent_id == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="sort_order-{{ $category->id }}">ترتيب الفرز <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="sort_order-{{ $category->id }}" name="sort_order" value="{{ $category->sort_order }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active-{{ $category->id }}" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active-{{ $category->id }}">مفعل</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>الصورة الحالية:</label>
                        <img src="{{ $category->image ? asset('storage/' . $category->image) : asset('img/default-category.png') }}" alt="{{ $category->name }}" class="img-thumbnail" width="100">
                    </div>
                    <div class="form-group">
                        <label for="image-{{ $category->id }}">تغيير الصورة</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="image-{{ $category->id }}" name="image">
                            <label class="custom-file-label" for="image-{{ $category->id }}">اختر ملف جديد...</label>
                        </div>
                    </div>

                    {{-- قسم القائمون على البرنامج (للفئات القرآن فقط) --}}
                    <hr class="my-4">
                    <div class="form-group">
                        <label class="font-weight-bold">
                            <i class="fas fa-users text-primary"></i>
                            القائمون على البرنامج
                            <small class="text-muted">(للفئات القرآن فقط)</small>
                        </label>
                        
                        {{-- قائمة القائمين الحاليين --}}
                        <div id="managers-list-{{ $category->id }}" class="mb-3">
                            @php
                                $currentManagers = $category->managers()->with('person')->orderBy('sort_order')->get();
                            @endphp
                            @if($currentManagers->count() > 0)
                                <div class="list-group" id="sortable-managers-{{ $category->id }}">
                                    @foreach($currentManagers as $manager)
                                        <div class="list-group-item d-flex justify-content-between align-items-center" data-manager-id="{{ $manager->id }}">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-grip-vertical text-muted mr-2" style="cursor: move;"></i>
                                                <img src="{{ $manager->person->avatar }}" alt="{{ $manager->person->full_name }}" class="rounded-circle mr-2" width="32" height="32">
                                                <span>{{ $manager->person->full_name }}</span>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-danger remove-manager-btn" data-manager-id="{{ $manager->id }}" data-category-id="{{ $category->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    لا يوجد قائمون على البرنامج حالياً
                                </div>
                            @endif
                        </div>

                        {{-- إضافة قائم جديد --}}
                        <div class="input-group">
                            <select class="form-control" id="person-select-{{ $category->id }}">
                                <option value="">-- اختر شخص --</option>
                                @if(isset($persons))
                                    @foreach($persons as $person)
                                        @php
                                            $isManager = $category->managers()->where('person_id', $person->id)->exists();
                                        @endphp
                                        @if(!$isManager)
                                            <option value="{{ $person->id }}">{{ $person->full_name }}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-primary add-manager-btn" data-category-id="{{ $category->id }}">
                                    <i class="fas fa-plus"></i> إضافة
                                </button>
                            </div>
                        </div>
                        <small class="text-muted">يمكنك سحب العناصر لتغيير الترتيب</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    <button type="submit" class="btn btn-primary">تحديث</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JavaScript لإدارة القائمين على البرنامج --}}
<script>
$(document).ready(function() {
    const categoryId = {{ $category->id }};
    
    // إضافة قائم جديد
    $(document).on('click', '.add-manager-btn[data-category-id="' + categoryId + '"]', function() {
        const personId = $('#person-select-' + categoryId).val();
        if (!personId) {
            alert('يرجى اختيار شخص');
            return;
        }

        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> جاري الإضافة...');

        $.ajax({
            url: '{{ route("categories.add-manager", $category->id) }}',
            method: 'POST',
            data: {
                person_id: personId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    // إعادة تحميل القائمة
                    location.reload();
                }
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'حدث خطأ أثناء الإضافة';
                alert(message);
                btn.prop('disabled', false).html('<i class="fas fa-plus"></i> إضافة');
            }
        });
    });

    // حذف قائم
    $(document).on('click', '.remove-manager-btn[data-category-id="' + categoryId + '"]', function() {
        const managerId = $(this).data('manager-id');
        if (!confirm('هل أنت متأكد من حذف هذا القائم على البرنامج؟')) {
            return;
        }

        const btn = $(this);
        btn.prop('disabled', true);

        $.ajax({
            url: '/dashboard/categories/managers/' + managerId,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    // إعادة تحميل القائمة
                    location.reload();
                }
            },
            error: function(xhr) {
                alert('حدث خطأ أثناء الحذف');
                btn.prop('disabled', false);
            }
        });
    });

    // ترتيب القائمين (Sortable) - يحتاج jQuery UI
    @if(isset($currentManagers) && $currentManagers->count() > 0)
    if (typeof $.fn.sortable !== 'undefined') {
        $('#sortable-managers-' + categoryId).sortable({
            handle: '.fa-grip-vertical',
            update: function(event, ui) {
                const items = [];
                $('#sortable-managers-' + categoryId + ' > div').each(function(index) {
                    items.push({
                        id: $(this).data('manager-id'),
                        sort_order: index + 1
                    });
                });

            $.ajax({
                url: '{{ route("categories.update-manager-order", $category->id) }}',
                method: 'POST',
                    data: {
                        items: items,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // يمكن إضافة رسالة نجاح هنا
                        }
                    },
                    error: function() {
                        alert('حدث خطأ أثناء تحديث الترتيب');
                    }
                });
            }
        });
    }
    @endif
});
</script>
