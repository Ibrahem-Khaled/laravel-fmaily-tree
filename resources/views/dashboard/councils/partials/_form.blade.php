{{--
    Shared council form used by both create and edit pages.

    Required variables:
      - $action          Form action URL (store/update)
      - $submitLabel     Label for the submit button
      - $submitIcon      Font Awesome icon class for the submit button
      - $council         (optional) FamilyCouncil instance when editing
--}}
@php
    /** @var \App\Models\FamilyCouncil|null $council */
    $council ??= null;
    $isEdit = (bool) $council;

    $nameValue             = old('name', $council?->name);
    $descriptionValue      = old('description', $council?->description);
    $addressValue          = old('address', $council?->address);
    $googleMapUrlValue     = old('google_map_url', $council?->google_map_url);
    $workingDaysFromValue  = old('working_days_from', $council?->working_days_from);
    $workingDaysToValue    = old('working_days_to', $council?->working_days_to);
    $isActiveValue         = (bool) old('is_active', $council?->is_active ?? true);
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
                        <label for="name" class="font-weight-bold">
                            اسم المكان <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" id="name"
                               class="form-control form-control-lg @error('name') is-invalid @enderror"
                               value="{{ $nameValue }}"
                               placeholder="مثال: مجلس عائلة السريع الرئيسي..."
                               required maxlength="255" autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <x-rich-text-editor
                        name="description"
                        id="description"
                        label="وصف المكان (اختياري)"
                        placeholder="اكتب وصفاً تفصيلياً للمكان، يمكنك إضافة الصور والروابط والتنسيقات..."
                        :value="$descriptionValue"
                        :height="280"
                        help="يدعم الوصف التنسيق المنسّق والصور والروابط."
                    />
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 font-weight-bold text-primary">
                        <i class="fas fa-map-marker-alt mr-2"></i>الموقع والاتصال
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="form-group">
                        <label for="address" class="font-weight-bold">عنوان المكان (اختياري)</label>
                        <input type="text" name="address" id="address"
                               class="form-control @error('address') is-invalid @enderror"
                               value="{{ $addressValue }}"
                               placeholder="مثال: حي الملك فهد، الرياض"
                               maxlength="500">
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-0">
                        <label for="google_map_url" class="font-weight-bold">رابط جوجل ماب (اختياري)</label>
                        <input type="url" name="google_map_url" id="google_map_url"
                               class="form-control @error('google_map_url') is-invalid @enderror"
                               value="{{ $googleMapUrlValue }}"
                               placeholder="https://maps.google.com/..."
                               maxlength="1000">
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            رابط الموقع على Google Maps يُفتح في نافذة جديدة عند النقر.
                        </small>
                        @error('google_map_url')
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
                        <i class="fas fa-clock mr-2"></i>ساعات العمل
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="form-group">
                        <label for="working_days_from" class="font-weight-bold">أيام العمل - من (اختياري)</label>
                        <input type="text" name="working_days_from" id="working_days_from"
                               class="form-control @error('working_days_from') is-invalid @enderror"
                               value="{{ $workingDaysFromValue }}"
                               placeholder="مثال: السبت"
                               maxlength="50">
                        @error('working_days_from')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-0">
                        <label for="working_days_to" class="font-weight-bold">أيام العمل - إلى (اختياري)</label>
                        <input type="text" name="working_days_to" id="working_days_to"
                               class="form-control @error('working_days_to') is-invalid @enderror"
                               value="{{ $workingDaysToValue }}"
                               placeholder="مثال: الخميس"
                               maxlength="50">
                        @error('working_days_to')
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
                            المجالس غير النشطة لا تظهر للزوار.
                        </small>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <button type="submit" class="btn btn-primary btn-block shadow-sm">
                        <i class="{{ $submitIcon }} mr-2"></i>{{ $submitLabel }}
                    </button>
                    <a href="{{ route('dashboard.councils.index') }}"
                       class="btn btn-light btn-block shadow-sm mt-2">
                        <i class="fas fa-times mr-2"></i>إلغاء
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
