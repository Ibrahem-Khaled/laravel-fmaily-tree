<div class="modal fade" id="editMarriageModal{{ $marriage->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editMarriageModalLabel{{ $marriage->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMarriageModalLabel{{ $marriage->id }}">تعديل سجل الزواج</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="edit-marriage-form" action="{{ route('marriages.update', $marriage->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="husband_id_edit{{ $marriage->id }}">الزوج <span
                                        class="text-danger">*</span></label>
                                <select name="husband_id" id="husband_id_edit{{ $marriage->id }}"
                                    class="form-control select2" required>
                                    <option value="">اختر الزوج</option>
                                    @foreach ($persons->where('gender', 'male') as $person)
                                        <option value="{{ $person->id }}"
                                            {{ $marriage->husband_id == $person->id ? 'selected' : '' }}>
                                            {{ $person->full_name }} ({{ $person->age }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="wife_id_edit{{ $marriage->id }}">الزوجة <span
                                        class="text-danger">*</span></label>
                                <select name="wife_id" id="wife_id_edit{{ $marriage->id }}"
                                    class="form-control select2" required>
                                    <option value="">اختر الزوجة</option>
                                    @foreach ($persons->where('gender', 'female') as $person)
                                        <option value="{{ $person->id }}"
                                            {{ $marriage->wife_id == $person->id ? 'selected' : '' }}>
                                            {{ $person->full_name }} ({{ $person->age }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="married_at_edit{{ $marriage->id }}">تاريخ الزواج</label>
                                <input type="date" name="married_at" id="married_at_edit{{ $marriage->id }}"
                                    class="form-control"
                                    value="{{ $marriage->married_at ? $marriage->married_at->format('Y-m-d') : '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="divorced_at_edit{{ $marriage->id }}">تاريخ الطلاق</label>
                                <input type="date" name="divorced_at" id="divorced_at_edit{{ $marriage->id }}"
                                    class="form-control"
                                    value="{{ $marriage->divorced_at ? $marriage->divorced_at->format('Y-m-d') : '' }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>
