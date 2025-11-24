{{-- مودال تعديل حساب تواصل --}}
<div class="modal fade" id="editContactAccountModal{{ $account->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تعديل حساب تواصل</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('people.contact-accounts.update', ['person' => $person->id, 'contactAccountId' => $account->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>النوع</label>
                        <select name="type" class="form-control" required>
                            <option value="phone" @selected($account->type == 'phone')>هاتف</option>
                            <option value="whatsapp" @selected($account->type == 'whatsapp')>واتساب</option>
                            <option value="email" @selected($account->type == 'email')>بريد إلكتروني</option>
                            <option value="facebook" @selected($account->type == 'facebook')>فيسبوك</option>
                            <option value="instagram" @selected($account->type == 'instagram')>إنستجرام</option>
                            <option value="twitter" @selected($account->type == 'twitter')>تويتر</option>
                            <option value="linkedin" @selected($account->type == 'linkedin')>لينكد إن</option>
                            <option value="telegram" @selected($account->type == 'telegram')>تيليجرام</option>
                            <option value="other" @selected($account->type == 'other')>أخرى</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>القيمة</label>
                        <input type="text" name="value" class="form-control" value="{{ $account->value }}" required>
                    </div>
                    <div class="form-group">
                        <label>التسمية (اختياري)</label>
                        <input type="text" name="label" class="form-control" value="{{ $account->label }}" placeholder="مثل: رقم العمل، رقم المنزل">
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

