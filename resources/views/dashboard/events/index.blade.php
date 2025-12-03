@extends('layouts.app')

@section('title', 'إدارة مناسبات العائلة')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-calendar-alt text-primary mr-2"></i>إدارة مناسبات العائلة
            </h1>
            <p class="text-muted mb-0">قم بإدارة مناسبات العائلة المعروضة في الموقع</p>
        </div>
        <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#addModal">
            <i class="fas fa-plus-circle mr-2"></i>إضافة مناسبة جديدة
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2" style="border-left: 0.25rem solid #4e73df !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                إجمالي المناسبات
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2" style="border-left: 0.25rem solid #1cc88a !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                مناسبات نشطة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2" style="border-left: 0.25rem solid #f6c23e !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                مناسبات قادمة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['upcoming'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2" style="border-left: 0.25rem solid #36b9cc !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                مع عداد تنازلي
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['with_countdown'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Events Grid -->
    @if($events->count() > 0)
        <div class="mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 font-weight-bold text-primary">
                            <i class="fas fa-calendar-alt mr-2"></i>المناسبات ({{ $events->count() }})
                        </h6>
                        <button type="button" class="btn btn-sm btn-success" onclick="saveOrder()" id="saveOrderBtn" style="display: none;">
                            <i class="fas fa-save mr-1"></i>حفظ الترتيب
                        </button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div id="events-grid" class="row">
                        @foreach($events as $event)
                            <div class="col-md-6 col-lg-4 mb-4" data-id="{{ $event->id }}">
                                <div class="card h-100 shadow-sm border-0 event-card" 
                                     style="transition: all 0.3s ease; border-radius: 12px; overflow: hidden;">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title font-weight-bold mb-0 text-dark" style="font-size: 1rem;">
                                                <i class="fas fa-calendar-alt text-primary mr-2"></i>
                                                {{ $event->title }}
                                            </h6>
                                            <div>
                                                <i class="fas fa-grip-vertical text-muted" 
                                                   style="cursor: move; opacity: 0.7; font-size: 0.9rem;" 
                                                   title="اسحب لإعادة الترتيب"></i>
                                            </div>
                                        </div>
                                        
                                        @if($event->description)
                                            <p class="card-text text-muted small mb-2" style="font-size: 0.85rem; line-height: 1.5;">
                                                {{ Str::limit($event->description, 100) }}
                                            </p>
                                        @endif

                                        <div class="mb-2">
                                            @if($event->city)
                                                <p class="card-text text-muted small mb-1" style="font-size: 0.8rem;">
                                                    <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                                    {{ $event->city }}
                                                </p>
                                            @endif
                                            @if($event->location)
                                                <p class="card-text text-muted small mb-1" style="font-size: 0.8rem;">
                                                    <i class="fas fa-location-dot text-info mr-1"></i>
                                                    <a href="{{ $event->location }}" target="_blank" rel="noopener noreferrer" class="text-info">
                                                        عرض الموقع على الخريطة
                                                        <i class="fas fa-external-link-alt mr-1" style="font-size: 0.7rem;"></i>
                                                    </a>
                                                </p>
                                            @endif
                                            <p class="card-text text-muted small mb-1" style="font-size: 0.8rem;">
                                                <i class="fas fa-clock text-warning mr-1"></i>
                                                {{ $event->event_date->format('Y-m-d H:i') }}
                                            </p>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                <span class="badge badge-{{ $event->is_active ? 'success' : 'secondary' }} shadow-sm px-2 py-1" style="font-size: 0.75rem;">
                                                    {{ $event->is_active ? 'نشط' : 'غير نشط' }}
                                                </span>
                                                <span class="badge badge-dark shadow-sm px-2 py-1 ml-1" style="font-size: 0.75rem;">
                                                    #{{ $event->display_order }}
                                                </span>
                                                @if($event->show_countdown)
                                                    <span class="badge badge-info shadow-sm px-2 py-1 ml-1" style="font-size: 0.75rem;">
                                                        <i class="fas fa-hourglass-half mr-1"></i>عداد
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                            <div class="btn-group btn-group-sm shadow-sm">
                                                <button type="button" 
                                                        class="btn btn-outline-info border-0" 
                                                        onclick="editEvent({{ $event->id }})" 
                                                        title="تعديل"
                                                        style="border-radius: 6px 0 0 6px;">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('dashboard.events.destroy', $event) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('هل أنت متأكد من حذف هذه المناسبة؟')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-outline-danger border-0" 
                                                            title="حذف"
                                                            style="border-radius: 0 6px 6px 0;">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <i class="fas fa-calendar-alt text-muted" style="font-size: 4rem;"></i>
                <h5 class="mt-3 text-muted">لا توجد مناسبات حالياً</h5>
                <p class="text-muted mb-4">ابدأ بإضافة مناسبة جديدة</p>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addModal">
                    <i class="fas fa-plus-circle mr-2"></i>إضافة مناسبة جديدة
                </button>
            </div>
        </div>
    @endif
</div>

<!-- Modal إضافة مناسبة جديدة -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title" id="addModalLabel">
                    <i class="fas fa-plus-circle mr-2"></i>إضافة مناسبة جديدة
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('dashboard.events.store') }}" method="POST" id="addForm">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label for="title" class="font-weight-bold">
                            اسم المناسبة <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="title" id="title" 
                               class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title') }}" 
                               placeholder="أدخل اسم المناسبة..."
                               required maxlength="255">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="font-weight-bold">وصف المناسبة (اختياري)</label>
                        <textarea name="description" id="description" rows="3" 
                                  class="form-control @error('description') is-invalid @enderror" 
                                  placeholder="أدخل وصف للمناسبة..."
                                  maxlength="10000">{{ old('description') }}</textarea>
                        <small class="form-text text-muted">
                            <span id="charCount">0</span> / 10000 حرف
                        </small>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="city" class="font-weight-bold">المدينة (اختياري)</label>
                            <input type="text" name="city" id="city" 
                                   class="form-control @error('city') is-invalid @enderror" 
                                   value="{{ old('city') }}" 
                                   placeholder="أدخل المدينة..."
                                   maxlength="255">
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="location" class="font-weight-bold">رابط الموقع (اختياري)</label>
                            <input type="url" name="location" id="location" 
                                   class="form-control @error('location') is-invalid @enderror" 
                                   value="{{ old('location') }}" 
                                   placeholder="https://maps.google.com/..."
                                   maxlength="500">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                أدخل رابط الموقع (مثل رابط Google Maps)
                            </small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="event_date" class="font-weight-bold">
                            تاريخ المناسبة <span class="text-danger">*</span>
                        </label>
                        <input type="datetime-local" name="event_date" id="event_date" 
                               class="form-control @error('event_date') is-invalid @enderror" 
                               value="{{ old('event_date') }}" 
                               required>
                        @error('event_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="show_countdown" name="show_countdown" value="1">
                            <label class="custom-control-label" for="show_countdown">إظهار العداد التنازلي</label>
                        </div>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            سيتم عرض عداد تنازلي للمناسبة في الصفحة الرئيسية
                        </small>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" checked>
                            <label class="custom-control-label" for="is_active">نشط</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>إلغاء
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus-circle mr-1"></i>إضافة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal تعديل مناسبة -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white border-0">
                <h5 class="modal-title" id="editModalLabel">
                    <i class="fas fa-edit mr-2"></i>تعديل المناسبة
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label for="edit_title" class="font-weight-bold">
                            اسم المناسبة <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="title" id="edit_title" 
                               class="form-control" 
                               placeholder="أدخل اسم المناسبة..."
                               required maxlength="255">
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_description" class="font-weight-bold">وصف المناسبة (اختياري)</label>
                        <textarea name="description" id="edit_description" rows="3" 
                                  class="form-control" 
                                  placeholder="أدخل وصف للمناسبة..."
                                  maxlength="10000"></textarea>
                        <small class="form-text text-muted">
                            <span id="editCharCount">0</span> / 10000 حرف
                        </small>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="edit_city" class="font-weight-bold">المدينة (اختياري)</label>
                            <input type="text" name="city" id="edit_city" 
                                   class="form-control" 
                                   placeholder="أدخل المدينة..."
                                   maxlength="255">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="edit_location" class="font-weight-bold">رابط الموقع (اختياري)</label>
                            <input type="url" name="location" id="edit_location" 
                                   class="form-control" 
                                   placeholder="https://maps.google.com/..."
                                   maxlength="500">
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                أدخل رابط الموقع (مثل رابط Google Maps)
                            </small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_event_date" class="font-weight-bold">
                            تاريخ المناسبة <span class="text-danger">*</span>
                        </label>
                        <input type="datetime-local" name="event_date" id="edit_event_date" 
                               class="form-control" 
                               required>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="edit_show_countdown" name="show_countdown" value="1">
                            <label class="custom-control-label" for="edit_show_countdown">إظهار العداد التنازلي</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="edit_is_active" name="is_active" value="1">
                            <label class="custom-control-label" for="edit_is_active">نشط</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>إلغاء
                    </button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-save mr-1"></i>حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    // Character counter
    document.getElementById('description')?.addEventListener('input', function() {
        document.getElementById('charCount').textContent = this.value.length;
    });

    document.getElementById('edit_description')?.addEventListener('input', function() {
        document.getElementById('editCharCount').textContent = this.value.length;
    });

    // Edit Event
    function editEvent(eventId) {
        fetch(`/dashboard/events/${eventId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_title').value = data.title;
                document.getElementById('edit_description').value = data.description || '';
                document.getElementById('edit_city').value = data.city || '';
                document.getElementById('edit_location').value = data.location || '';
                document.getElementById('edit_event_date').value = data.event_date;
                document.getElementById('edit_show_countdown').checked = data.show_countdown;
                document.getElementById('edit_is_active').checked = data.is_active;
                
                document.getElementById('editForm').action = `/dashboard/events/${eventId}/update`;
                document.getElementById('editCharCount').textContent = (data.description || '').length;
                
                $('#editModal').modal('show');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء جلب بيانات المناسبة');
            });
    }

    // Sortable for events
    let sortable = null;
    @if($events->count() > 0)
        sortable = Sortable.create(document.getElementById('events-grid'), {
            animation: 150,
            handle: '.fa-grip-vertical',
            onEnd: function() {
                document.getElementById('saveOrderBtn').style.display = 'block';
            }
        });
    @endif

    // Save order
    function saveOrder() {
        const order = Array.from(document.getElementById('events-grid').children).map((el, index) => ({
            id: parseInt(el.getAttribute('data-id')),
            order: index + 1
        }));

        fetch('{{ route("dashboard.events.reorder") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ orders: order.map(o => o.id) })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('saveOrderBtn').style.display = 'none';
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء حفظ الترتيب');
        });
    }
</script>
@endsection

