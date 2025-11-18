{{-- مودال إضافة حساب تواصل --}}
<div class="modal fade" id="addContactAccountModal{{ $person->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة حساب تواصل</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('people.contact-accounts.store', $person->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>النوع</label>
                        <select name="type" class="form-control" required>
                            <option value="phone">هاتف</option>
                            <option value="whatsapp">واتساب</option>
                            <option value="email">بريد إلكتروني</option>
                            <option value="facebook">فيسبوك</option>
                            <option value="instagram">إنستجرام</option>
                            <option value="twitter">تويتر</option>
                            <option value="linkedin">لينكد إن</option>
                            <option value="telegram">تيليجرام</option>
                            <option value="other">أخرى</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>القيمة</label>
                        <input type="text" name="value" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>التسمية (اختياري)</label>
                        <input type="text" name="label" class="form-control" placeholder="مثل: رقم العمل، رقم المنزل">
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

