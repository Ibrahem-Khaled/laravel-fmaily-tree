{{-- مودال إضافة موقع --}}
<div class="modal fade" id="addPersonLocationModal{{ $person->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة موقع</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('people.locations.store', $person->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>الموقع</label>
                        <select name="location_id" class="form-control location-select" required>
                            <option value="">-- اختر موقع --</option>
                            @foreach(\App\Models\Location::orderBy('name')->get() as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>التسمية (اختياري)</label>
                        <input type="text" name="label" class="form-control" placeholder="مثل: مكان الإقامة، مكان العمل">
                    </div>
                    <div class="form-group">
                        <label>رابط الموقع (اختياري)</label>
                        <input type="url" name="url" class="form-control" placeholder="https://maps.google.com/...">
                        <small class="form-text text-muted">رابط جوجل ماب أو أي رابط آخر للموقع</small>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="is_primary" value="1" class="form-check-input" id="is_primary_add{{ $person->id }}">
                            <label class="form-check-label" for="is_primary_add{{ $person->id }}">
                                موقع أساسي
                            </label>
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

