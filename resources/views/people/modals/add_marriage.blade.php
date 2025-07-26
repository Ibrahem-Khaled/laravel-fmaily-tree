{{--
    This modal is for creating a new marriage record for the person currently being viewed.
    The relevant person (husband or wife) is pre-selected based on the current person's gender.
--}}
<div class="modal fade" id="addMarriageModal" tabindex="-1" role="dialog" aria-labelledby="addMarriageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMarriageModalLabel">إضافة زواج لـ {{ $person->full_name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{-- Make sure the route 'marriages.store' exists in your web.php --}}
            <form action="{{ route('marriages.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="marriage_husband_id">الزوج <span class="text-danger">*</span></label>
                                @if($person->gender == 'male')
                                    {{-- Pre-fill husband and disable selection --}}
                                    <input type="hidden" name="husband_id" value="{{ $person->id }}">
                                    <input type="text" class="form-control" value="{{ $person->full_name }}" disabled>
                                @else
                                    {{-- Allow selecting a husband from the list of males --}}
                                    <select name="husband_id" id="marriage_husband_id" class="form-control select2-searchable" required>
                                        <option value="">اختر الزوج</option>
                                        @foreach($males as $male)
                                            <option value="{{ $male->id }}">
                                                {{ $male->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="marriage_wife_id">الزوجة <span class="text-danger">*</span></label>
                                @if($person->gender == 'female')
                                    {{-- Pre-fill wife and disable selection --}}
                                    <input type="hidden" name="wife_id" value="{{ $person->id }}">
                                    <input type="text" class="form-control" value="{{ $person->full_name }}" disabled>
                                @else
                                    {{-- Allow selecting a wife from the list of females --}}
                                    <select name="wife_id" id="marriage_wife_id" class="form-control select2-searchable" required>
                                        <option value="">اختر الزوجة</option>
                                        @foreach($females as $female)
                                            <option value="{{ $female->id }}">
                                                {{ $female->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="married_at">تاريخ الزواج</label>
                                <input type="date" name="married_at" id="married_at" class="form-control" value="{{ old('married_at') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="divorced_at">تاريخ الطلاق</label>
                                <input type="date" name="divorced_at" id="divorced_at" class="form-control" value="{{ old('divorced_at') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ الزواج</button>
                </div>
            </form>
        </div>
    </div>
</div>
