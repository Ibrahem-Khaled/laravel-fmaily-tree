<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;

class FriendshipController extends Controller
{
    /**
     * عرض صفحة أصدقاء شخص معين
     */
    public function index(Person $person)
    {
        // تحميل العلاقات لضمان عمل full_name accessor
        $person->load([
            'parent:id,first_name,gender,parent_id',
            'parent.parent:id,first_name,gender,parent_id'
        ]);

        $friendships = $person->friendships()->with([
            'friend:id,first_name,last_name,photo_url,parent_id,gender,birth_date,death_date,occupation',
            'friend.parent:id,first_name,gender,parent_id',
            'friend.parent.parent:id,first_name,gender,parent_id'
        ])->latest()->get();

        return view('friendships.index', compact('person', 'friendships'));
    }

}
