<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;

class HomePersonController extends Controller
{
    public function personsWhereHasBadges(Request $request)
    {
        // تم استخدام whereHas لفلترة الأشخاص الذين لديهم أوسمة فقط
        // ثم استخدمنا with('padges') لتحميل هذه الأوسمة مسبقًا في استعلام واحد فقط
        $persons = Person::whereHas('padges') // تأكد من أن اسم العلاقة هنا 'padges' وليس 'badges'
            ->with('padges')
            ->get();

        return view('persons_badges', ['persons' => $persons]);
    }

    public function show(Person $person)
    {
        // استخدام Route Model Binding يجلب الشخص تلقائيًا
        // نقوم بتحميل العلاقات الأساسية لتحسين الأداء
        $person->load([
            'padges' // الأوسمة
        ]);

        // إرسال بيانات الشخص إلى الـ view
        return view('person_show', [
            'person' => $person,
        ]);
    }
}
