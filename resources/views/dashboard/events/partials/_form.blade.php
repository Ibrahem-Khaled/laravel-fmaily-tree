{{--
    Shared event form used by both create and edit pages.

    Required variables:
      - $action          Form action URL (store/update)
      - $submitLabel     Label for the submit button
      - $submitIcon      Font Awesome icon class for the submit button
      - $event           (optional) FamilyEvent instance when editing
--}}
@php
    /** @var \App\Models\FamilyEvent|null $event */
    $event ??= null;
    $isEdit = (bool) $event;

    $titleValue        = old('title', $event?->title);
    $descriptionValue  = old('description', $event?->description);
    $cityValue         = old('city', $event?->city);
    $locationValue     = old('location', $event?->location);
    $locationNameValue = old('location_name', $event?->location_name);
    $eventDateValue    = old(
        'event_date',
        $event?->event_date?->format('Y-m-d\TH:i'),
    );
    $showCountdownValue = (bool) old('show_countdown', $event?->show_countdown ?? false);
    $isActiveValue      = (bool) old('is_active', $event?->is_active ?? true);
@endphp

<form action="{{ $action }}" method="POST" novalidate>
    @csrf

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle mr-2"></i>المعلومات الأساسية
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="form-group">
                        <label for="title" class="font-weight-bold">
                            اسم المناسبة <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="title" id="title"
                               class="form-control form-control-lg @error('title') is-invalid @enderror"
                               value="{{ $titleValue }}"
                               placeholder="مثال: لقاء العائلة السنوي..."
                               required maxlength="255" autofocus>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <x-rich-text-editor
                        name="description"
                        id="description"
                        label="وصف المناسبة (اختياري)"
                        placeholder="اكتب وصفاً تفصيلياً للمناسبة، يمكنك إضافة الصور والروابط والتنسيقات..."
                        :value="$descriptionValue"
                        :height="280"
                        help="يدعم الوصف التنسيق المنسّق والصور والروابط."
                    />
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 font-weight-bold text-primary">
                        <i class="fas fa-map-marker-alt mr-2"></i>الموقع والمكان
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="city" class="font-weight-bold">المدينة (اختياري)</label>
                            <input type="text" name="city" id="city"
                                   class="form-control @error('city') is-invalid @enderror"
                                   value="{{ $cityValue }}"
                                   placeholder="مثال: الرياض"
                                   maxlength="255">
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="location_name" class="font-weight-bold">اسم المكان (اختياري)</label>
                            <input type="text" name="location_name" id="location_name"
                                   class="form-control @error('location_name') is-invalid @enderror"
                                   value="{{ $locationNameValue }}"
                                   placeholder="مثال: قاعة المؤتمرات - فندق الماريوت"
                                   maxlength="255">
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                سيظهر هذا الاسم بدلاً من "عرض الموقع" في الصفحة الرئيسية.
                            </small>
                            @error('location_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label for="location" class="font-weight-bold">رابط الموقع على الخريطة (اختياري)</label>
                        <input type="url" name="location" id="location"
                               class="form-control @error('location') is-invalid @enderror"
                               value="{{ $locationValue }}"
                               placeholder="https://maps.google.com/..."
                               maxlength="500">
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            ينصح برابط Google Maps المباشر.
                        </small>
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar-alt mr-2"></i>التوقيت
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="form-group mb-0">
                        <label for="event_date" class="font-weight-bold">
                            تاريخ المناسبة <span class="text-danger">*</span>
                        </label>
                        <input type="datetime-local" name="event_date" id="event_date"
                               class="form-control @error('event_date') is-invalid @enderror"
                               value="{{ $eventDateValue }}"
                               required>
                        @error('event_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 font-weight-bold text-primary">
                        <i class="fas fa-cog mr-2"></i>الخيارات
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input"
                                   id="show_countdown" name="show_countdown" value="1"
                                   {{ $showCountdownValue ? 'checked' : '' }}>
                            <label class="custom-control-label font-weight-bold" for="show_countdown">
                                إظهار العداد التنازلي
                            </label>
                        </div>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            يُعرض عداد تنازلي للمناسبة في الصفحة الرئيسية.
                        </small>
                    </div>

                    <div class="form-group mb-0">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input"
                                   id="is_active" name="is_active" value="1"
                                   {{ $isActiveValue ? 'checked' : '' }}>
                            <label class="custom-control-label font-weight-bold" for="is_active">
                                نشط
                            </label>
                        </div>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            المناسبات غير النشطة لا تظهر للزوار.
                        </small>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <button type="submit" class="btn btn-primary btn-block shadow-sm">
                        <i class="{{ $submitIcon }} mr-2"></i>{{ $submitLabel }}
                    </button>
                    <a href="{{ route('dashboard.events.index') }}"
                       class="btn btn-light btn-block shadow-sm mt-2">
                        <i class="fas fa-times mr-2"></i>إلغاء
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
