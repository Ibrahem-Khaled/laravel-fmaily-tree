<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\Marriage;
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
            'padges', // الأوسمة
            'parent:id,first_name,last_name,gender,birth_date,death_date,photo_url,parent_id', // الأب
            'mother:id,first_name,last_name,gender,birth_date,death_date,photo_url,parent_id', // الأم
            'location:id,name', // الموقع
            'locations:id,name', // المواقع المتعددة
            'contactAccounts:id,person_id,type,value,label,sort_order', // حسابات التواصل
            'articles:id,title,person_id,category_id', // المقالات
            'articles.category:id,name', // فئات المقالات
        ]);

        // جلب الزوجات/الزوج
        $spouses = collect();
        if ($person->gender === 'male') {
            // جلب الزوجات باستخدام Marriage model لتجنب مشكلة ambiguous column
            $wifeIds = Marriage::where('husband_id', $person->id)
                ->pluck('wife_id');
            
            if ($wifeIds->isNotEmpty()) {
                $spouses = Person::whereIn('id', $wifeIds)
                    ->select('id', 'first_name', 'last_name', 'gender', 'birth_date', 'death_date', 'photo_url', 'parent_id')
                    ->get();
            }
        } else {
            // جلب الزوج
            $marriage = Marriage::where('wife_id', $person->id)->first();
            if ($marriage && $marriage->husband_id) {
                $husband = Person::where('id', $marriage->husband_id)
                    ->select('id', 'first_name', 'last_name', 'gender', 'birth_date', 'death_date', 'photo_url', 'parent_id')
                    ->first();
                if ($husband) {
                    $spouses = collect([$husband]);
                }
            }
        }

        // جلب الأخوة (الأبناء المشتركون مع نفس الأب أو الأم)
        $siblings = collect();
        if ($person->parent_id) {
            $siblings = Person::where('parent_id', $person->parent_id)
                ->where('id', '!=', $person->id)
                ->select('id', 'first_name', 'last_name', 'gender', 'birth_date', 'death_date', 'photo_url', 'parent_id')
                ->get();
        }
        if ($person->mother_id) {
            $motherSiblings = Person::where('mother_id', $person->mother_id)
                ->where('id', '!=', $person->id)
                ->select('id', 'first_name', 'last_name', 'gender', 'birth_date', 'death_date', 'photo_url', 'parent_id')
                ->get();
            $siblings = $siblings->merge($motherSiblings)->unique('id');
        }

        // جلب الأبناء
        $children = collect();
        if ($person->gender === 'male') {
            $children = Person::where('parent_id', $person->id)
                ->select('id', 'first_name', 'last_name', 'gender', 'birth_date', 'death_date', 'photo_url', 'parent_id')
                ->get();
        } else {
            $children = Person::where('mother_id', $person->id)
                ->select('id', 'first_name', 'last_name', 'gender', 'birth_date', 'death_date', 'photo_url', 'parent_id')
                ->get();
        }

        // إرسال بيانات الشخص إلى الـ view
        return view('person_show', [
            'person' => $person,
            'siblings' => $siblings,
            'children' => $children,
            'spouses' => $spouses,
        ]);
    }
}
