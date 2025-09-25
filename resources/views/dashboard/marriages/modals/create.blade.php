<div class="modal fade" id="createMarriageModal" tabindex="-1" role="dialog" aria-labelledby="createMarriageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createMarriageModalLabel">إضافة سجل زواج جديد</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createMarriageForm" action="{{ route('marriages.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="husband_id">الزوج <span class="text-danger">*</span></label>
                                <select name="husband_id" id="husband_id" class="form-control select2" required>
                                    <option value="">اختر الزوج</option>
                                    @foreach($persons->where('gender', 'male') as $person)
                                        <option value="{{ $person->id }}" {{ old('husband_id') == $person->id ? 'selected' : '' }}>
                                            {{ $person->full_name }} ({{ $person->age }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="wife_id">الزوجة <span class="text-danger">*</span></label>
                                <select name="wife_id" id="wife_id" class="form-control select2" required>
                                    <option value="">اختر الزوجة</option>
                                    @foreach($persons->where('gender', 'female') as $person)
                                        <option value="{{ $person->id }}" {{ old('wife_id') == $person->id ? 'selected' : '' }}>
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
                                <label for="married_at">تاريخ الزواج</label>
                                <input type="date" name="married_at" id="married_at"
                                       class="form-control" value="{{ old('married_at') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="divorced_at">تاريخ الانفصال</label>
                                <input type="date" name="divorced_at" id="divorced_at"
                                       class="form-control" value="{{ old('divorced_at') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" name="is_divorced" id="is_divorced"
                                           class="form-check-input" value="1" {{ old('is_divorced') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_divorced">
                                        منفصل (بدون تاريخ محدد)
                                    </label>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>
