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

        // فلترة النساء المولودات بعد عام 2005
        $filteredPersons = $persons->filter(function ($person) {
            if ($person->gender === 'female' && $person->birth_date && $person->birth_date->year > 2005) {
                return false;
            }
            return true;
        });

        return view('persons_badges', ['persons' => $filteredPersons]);
    }

    public function show(Person $person)
    {
        // التحقق من أن الشخص ليس امرأة مولودة بعد عام 2005
        if ($person->gender === 'female' && $person->birth_date && $person->birth_date->year > 2005) {
            abort(404, 'الصفحة غير موجودة');
        }

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
