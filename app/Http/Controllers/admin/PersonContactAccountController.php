<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\PersonContactAccount;
use Illuminate\Http\Request;

class PersonContactAccountController extends Controller
{
    public function __construct()
    {
        // التحقق من الصلاحيات في الـ constructor
        $this->middleware('permission:people.update');
    }

    public function store(Request $request, Person $person)
    {
        $validated = $request->validate([
            'type' => 'required|in:phone,whatsapp,email,facebook,instagram,twitter,linkedin,telegram,other',
            'value' => 'required|string|max:255',
            'label' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['person_id'] = $person->id;
        $maxOrder = $person->contactAccounts()->max('sort_order');
        $validated['sort_order'] = $validated['sort_order'] ?? ($maxOrder ? $maxOrder + 1 : 0);

        PersonContactAccount::create($validated);

        return redirect()->back()->with('success', 'تم إضافة حساب التواصل بنجاح');
    }

    public function update(Request $request, Person $person, $contactAccountId)
    {
        // البحث عن حساب التواصل
        $contactAccount = PersonContactAccount::findOrFail($contactAccountId);

        // التأكد من أن الحساب يخص الشخص المحدد
        if ($contactAccount->person_id !== $person->id) {
            abort(403, 'هذا الحساب لا يخص هذا الشخص');
        }

        $validated = $request->validate([
            'type' => 'required|in:phone,whatsapp,email,facebook,instagram,twitter,linkedin,telegram,other',
            'value' => 'required|string|max:255',
            'label' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $contactAccount->update($validated);

        return redirect()->back()->with('success', 'تم تحديث حساب التواصل بنجاح');
    }

    public function destroy(Person $person, $contactAccountId)
    {
        // البحث عن حساب التواصل
        $contactAccount = PersonContactAccount::findOrFail($contactAccountId);

        // التأكد من أن الحساب يخص الشخص المحدد
        if ($contactAccount->person_id !== $person->id) {
            abort(403, 'هذا الحساب لا يخص هذا الشخص');
        }

        $contactAccount->delete();

        return redirect()->back()->with('success', 'تم حذف حساب التواصل بنجاح');
    }
}
