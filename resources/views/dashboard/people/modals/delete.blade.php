@foreach ($people as $person)
    <div class="modal fade" id="deletePersonModal{{ $person->id }}" tabindex="-1" role="dialog"
        aria-labelledby="deletePersonModalLabel{{ $person->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content text-right" style="background: #1e1e2d; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 16px; color: #fff;">
                <div class="modal-header border-bottom-0 pb-0" style="border-bottom: 1px solid rgba(255, 255, 255, 0.05); padding: 20px;">
                    <h5 class="modal-title text-danger d-flex align-items-center" id="deletePersonModalLabel{{ $person->id }}">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span>تأكيد الحذف النهائي</span>
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.5;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('people.destroy', $person->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body text-center pt-4" style="padding: 20px 30px;">
                        <div class="position-relative d-inline-block mb-3">
                            <img src="{{ $person->avatar }}" alt="{{ $person->full_name }}"
                                class="rounded-circle border border-danger" 
                                style="width: 100px; height: 100px; object-fit: cover; border: 3px solid rgba(231, 74, 59, 0.3); box-shadow: 0 0 20px rgba(231, 74, 59, 0.2);">
                        </div>
                        
                        <h4 class="font-weight-bold text-white mb-2">{{ $person->full_name }}</h4>
                        
                        <span class="badge badge-pill badge-{{ $person->gender == 'male' ? 'primary' : 'pink' }} px-3 py-2 mb-3">
                            {{ $person->gender == 'male' ? 'ذكر' : 'أنثى' }}
                        </span>

                        <div class="p-3 rounded mb-3" style="background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(255, 255, 255, 0.03);">
                            @if ($person->birth_date)
                                <div class="mb-2 text-muted" style="font-size: 0.9rem;">
                                    <span class="ml-1">تاريخ الميلاد:</span>
                                    <span class="text-white font-weight-bold ml-1">{{ format_gregorian_in_arabic($person->birth_date) }}</span>
                                    <span class="badge badge-pill px-2 py-1" style="font-size: 0.75rem; background: rgba(54, 185, 204, 0.15); border: 1px solid rgba(54, 185, 204, 0.3); color: #36b9cc;">
                                        {{ gregorian_to_hijri($person->birth_date) }}
                                    </span>
                                </div>
                            @endif
                            @if ($person->death_date)
                                <div class="text-muted" style="font-size: 0.9rem;">
                                    <span class="ml-1">تاريخ الوفاة:</span>
                                    <span class="text-white font-weight-bold ml-1">{{ format_gregorian_in_arabic($person->death_date) }}</span>
                                    <span class="badge badge-pill px-2 py-1" style="font-size: 0.75rem; background: rgba(231, 74, 59, 0.15); border: 1px solid rgba(231, 74, 59, 0.3); color: #e74a3b;">
                                        {{ gregorian_to_hijri($person->death_date) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        @php
                            $descendantsCount = $person->children()->count();
                        @endphp
                        @if ($descendantsCount > 0)
                            <div class="alert alert-danger border-0 text-right" style="background: rgba(231, 74, 59, 0.15); border-right: 4px solid #e74a3b !important; border-radius: 8px; color: #ff8578;">
                                <h6 class="font-weight-bold mb-1"><i class="fas fa-skull-crossbones mr-1"></i> تحذير حرج: حذف متسلسل!</h6>
                                هذا العضو لديه <strong>{{ $descendantsCount }}</strong> من الأبناء المباشرين. حذف هذا الحساب سيؤدي إلى <strong>حذف كافة التابعين له تلقائياً</strong> من شجرة العائلة بشكل نهائي!
                            </div>
                        @else
                            <div class="alert alert-warning border-0 text-right" style="background: rgba(255, 193, 7, 0.1); border-right: 4px solid #ffc107 !important; border-radius: 8px; color: #ffe082;">
                                <i class="fas fa-exclamation-triangle mr-1"></i> تنبيه: هذا الإجراء لا يمكن التراجع عنه وسيتم إزالة العضو نهائياً من شجرة العائلة.
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer border-top-0 pt-0" style="border-top: 1px solid rgba(255, 255, 255, 0.05); padding: 20px;">
                        <button type="button" class="btn btn-light btn-sm px-4" data-dismiss="modal" style="border-radius: 8px; background: rgba(255,255,255,0.08); border: none; color: #fff;">إلغاء</button>
                        <button type="submit" class="btn btn-danger btn-sm px-4" style="border-radius: 8px; background: #e74a3b; border: none; box-shadow: 0 4px 12px rgba(231, 74, 59, 0.3);">تأكيد الحذف النهائي</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
