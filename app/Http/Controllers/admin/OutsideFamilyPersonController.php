<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Person; // تأكد من أن مسار المودل صحيح
use App\Models\Marriage; // **مهم: استيراد موديل الزواج**
use Illuminate\Support\Facades\Validator;

class OutsideFamilyPersonController extends Controller
{
    /**
     * Store a new person from outside the family and create a marriage link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. التحقق من صحة البيانات
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'death_date' => 'nullable|date|after_or_equal:birth_date',
            'gender' => 'required|in:male,female',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'biography' => 'nullable|string',
            'occupation' => 'nullable|string|max:255',
            'location_id' => 'nullable|exists:locations,id',
            // تم تغيير الاسم ليعبر عن الغرض بشكل أفضل
            'marrying_person_id' => 'nullable|exists:persons,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        // 2. إنشاء سجل الشخص الجديد
        try {
            $personData = $validator->validated();

            // **مهم جداً: تعيين القيمة بشكل إجباري هنا**
            $personData['from_outside_the_family'] = true;

            // التعامل مع رفع الصورة إن وجدت
            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('photos', 'public');
                $personData['photo_url'] = $path;
            }

            // معالجة location_id - إذا تم إرسال location_id، نستخدمه
            // إذا تم إرسال location كنص فقط، نستخدم findOrCreateByName
            if ($request->has('location_id') && $request->input('location_id')) {
                $personData['location_id'] = $request->input('location_id');
            } elseif ($request->has('location') && $request->input('location')) {
                $location = \App\Models\Location::findOrCreateByName($request->input('location'));
                $personData['location_id'] = $location->id;
            }

            // إزالة الحقول غير الموجودة في $fillable أو التي لا نريدها في الإنشاء المباشر
            $marryingPersonId = $personData['marrying_person_id'] ?? null;
            unset($personData['photo'], $personData['marrying_person_id'], $personData['location'] ?? null);

            $newlyAddedPerson = Person::create($personData);

            // 3. **المنطق الجديد: إنشاء رابط الزواج في جدول 'marriages'**
            if ($marryingPersonId) {
                $existingPerson = Person::find($marryingPersonId);

                // تأكد من أن الشخص الآخر موجود وأن جنسهما مختلف
                if ($existingPerson && $existingPerson->gender !== $newlyAddedPerson->gender) {

                    // تحديد من هو الزوج ومن هي الزوجة
                    if ($newlyAddedPerson->gender == 'male') {
                        $husband_id = $newlyAddedPerson->id;
                        $wife_id = $existingPerson->id;
                    } else { // إذا كان الشخص الجديد أنثى
                        $husband_id = $existingPerson->id;
                        $wife_id = $newlyAddedPerson->id;
                    }

                    // إنشاء سجل الزواج
                    Marriage::create([
                        'husband_id' => $husband_id,
                        'wife_id' => $wife_id,
                        // يمكنك إضافة تاريخ الزواج هنا إذا كان لديك حقل له في الفورم
                        // 'married_at' => $request->input('married_at'),
                    ]);
                }
            }

            return redirect()->back()->with('success', 'تمت إضافة الشخص ورابط الزواج بنجاح!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إضافة الشخص: ' . $e->getMessage())->withInput();
        }
    }
}
