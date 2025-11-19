<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Friendship;
use App\Models\Person;
use Illuminate\Http\Request;

class FriendshipController extends Controller
{
    /**
     * عرض قائمة الأصدقاء
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $personId = $request->query('person_id');

        $query = Friendship::with([
            'person:id,first_name,last_name,photo_url,parent_id',
            'person.parent:id,first_name,gender,parent_id',
            'friend:id,first_name,last_name,photo_url,parent_id',
            'friend.parent:id,first_name,gender,parent_id'
        ]);

        if ($search) {
            $query->whereHas('person', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            })->orWhereHas('friend', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        if ($personId) {
            $query->where('person_id', $personId);
        }

        $friendships = $query->latest()->paginate(20)->appends($request->query());
        $persons = Person::with(['parent:id,first_name,gender,parent_id', 'parent.parent:id,first_name,gender,parent_id'])
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get(['id', 'first_name', 'last_name', 'parent_id']);

        $stats = [
            'total' => Friendship::count(),
        ];

        return view('dashboard.friendships.index', compact('friendships', 'persons', 'stats', 'search', 'personId'));
    }

    /**
     * عرض نموذج إضافة صداقة جديدة
     */
    public function create()
    {
        // جلب الأشخاص داخل العائلة فقط للشخص الرئيسي
        $familyMembers = Person::with(['parent:id,first_name,gender,parent_id', 'parent.parent:id,first_name,gender,parent_id'])
            ->where(function($query) {
                $query->where('from_outside_the_family', false)
                      ->orWhereNull('from_outside_the_family');
            })
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get(['id', 'first_name', 'last_name', 'parent_id']);

        // جلب جميع الأشخاص للصديق
        $allPersons = Person::with(['parent:id,first_name,gender,parent_id', 'parent.parent:id,first_name,gender,parent_id'])
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get(['id', 'first_name', 'last_name', 'parent_id']);

        return view('dashboard.friendships.create', compact('familyMembers', 'allPersons'));
    }

    /**
     * حفظ صداقة جديدة
     */
    public function store(Request $request)
    {
        $request->validate([
            'person_id' => 'required|exists:persons,id',
            'friend_id' => 'required|exists:persons,id|different:person_id',
            'description' => 'nullable|string|max:1000',
            'friendship_story' => 'nullable|string|max:5000',
        ], [
            'friend_id.different' => 'يجب اختيار شخص مختلف عن الشخص الأول',
        ]);

        // التحقق من عدم وجود صداقة مكررة
        $existing = Friendship::where('person_id', $request->person_id)
            ->where('friend_id', $request->friend_id)
            ->first();

        if ($existing) {
            return back()->withInput()->withErrors(['friend_id' => 'هذه الصداقة موجودة بالفعل']);
        }

        Friendship::create($request->only(['person_id', 'friend_id', 'description', 'friendship_story']));

        return redirect()->route('friendships.index')
            ->with('success', 'تم إضافة الصداقة بنجاح');
    }

    /**
     * عرض نموذج تعديل صداقة
     */
    public function edit(Friendship $friendship)
    {
        $friendship->load([
            'person:id,first_name,last_name,photo_url,parent_id',
            'person.parent:id,first_name,gender,parent_id',
            'friend:id,first_name,last_name,photo_url,parent_id',
            'friend.parent:id,first_name,gender,parent_id'
        ]);

        $persons = Person::with(['parent:id,first_name,gender,parent_id', 'parent.parent:id,first_name,gender,parent_id'])
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get(['id', 'first_name', 'last_name', 'parent_id']);
        return view('dashboard.friendships.edit', compact('friendship', 'persons'));
    }

    /**
     * تحديث صداقة
     */
    public function update(Request $request, Friendship $friendship)
    {
        $request->validate([
            'person_id' => 'required|exists:persons,id',
            'friend_id' => 'required|exists:persons,id|different:person_id',
            'description' => 'nullable|string|max:1000',
            'friendship_story' => 'nullable|string|max:5000',
        ], [
            'friend_id.different' => 'يجب اختيار شخص مختلف عن الشخص الأول',
        ]);

        // التحقق من عدم وجود صداقة مكررة (باستثناء الصداقة الحالية)
        $existing = Friendship::where('person_id', $request->person_id)
            ->where('friend_id', $request->friend_id)
            ->where('id', '!=', $friendship->id)
            ->first();

        if ($existing) {
            return back()->withInput()->withErrors(['friend_id' => 'هذه الصداقة موجودة بالفعل']);
        }

        $friendship->update($request->only(['person_id', 'friend_id', 'description', 'friendship_story']));

        return redirect()->route('friendships.index')
            ->with('success', 'تم تحديث الصداقة بنجاح');
    }

    /**
     * حذف صداقة
     */
    public function destroy(Friendship $friendship)
    {
        $friendship->delete();

        return redirect()->route('friendships.index')
            ->with('success', 'تم حذف الصداقة بنجاح');
    }
}
