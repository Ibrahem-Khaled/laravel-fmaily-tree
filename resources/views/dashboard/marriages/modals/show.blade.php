<div class="modal fade" id="showMarriageModal{{ $marriage->id }}" tabindex="-1" role="dialog"
    aria-labelledby="showMarriageModalLabel{{ $marriage->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showMarriageModalLabel{{ $marriage->id }}">تفاصيل سجل الزواج</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">معلومات الزوج</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ $marriage->husband->photo_url ?? asset('img/default-avatar.png') }}"
                                        class="rounded-circle mr-3" width="80" height="80" alt="صورة الزوج">
                                    <div>
                                        <h5>{{ $marriage->husband->full_name }}</h5>
                                        <p class="mb-1">رقم الهوية: {{ $marriage->husband->birth_date }}</p>
                                        <p class="mb-1">تاريخ الميلاد:
                                            {{ $marriage->husband->birth_date ? $marriage->husband->birth_date->format('Y-m-d') : 'غير معروف' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>الجنسية:</strong> {{ $marriage->husband->nationality ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>الهاتف:</strong> {{ $marriage->husband->phone ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">معلومات الزوجة</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ $marriage->wife->photo_url ?? asset('img/default-avatar.png') }}"
                                        class="rounded-circle mr-3" width="80" height="80" alt="صورة الزوجة">
                                    <div>
                                        <h5>{{ $marriage->wife->full_name }}</h5>
                                        <p class="mb-1">رقم الهوية: {{ $marriage->wife->birth_date }}</p>
                                        <p class="mb-1">تاريخ الميلاد:
                                            {{ $marriage->wife->birth_date ? $marriage->wife->birth_date->format('Y-m-d') : 'غير معروف' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>الجنسية:</strong> {{ $marriage->wife->nationality ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>الهاتف:</strong> {{ $marriage->wife->phone ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">معلومات الزواج</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <p><strong>تاريخ الزواج:</strong>
                                    {{ $marriage->married_at ? $marriage->married_at->format('Y-m-d') : 'غير معروف' }}
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>تاريخ الانفصال:</strong>
                                    {{ $marriage->divorced_at ? $marriage->divorced_at->format('Y-m-d') : ($marriage->is_divorced ? '✅ منفصل' : '-') }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>الحالة:</strong>
                                    @if ($marriage->isDivorced())
                                        <span class="badge badge-danger">{{ $marriage->status_text }}</span>
                                    @elseif($marriage->married_at)
                                        <span class="badge badge-success">{{ $marriage->status_text }}</span>
                                    @else
                                        <span class="badge badge-warning">{{ $marriage->status_text }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <p><strong>مدة الزواج:</strong>
                                    @if ($marriage->married_at)
                                        @if ($marriage->isDivorced())
                                            {{ $marriage->married_at->diffForHumans($marriage->divorced_at ?? now(), true) }}
                                        @else
                                            {{ $marriage->married_at->diffForHumans(now(), true) }}
                                        @endif
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
