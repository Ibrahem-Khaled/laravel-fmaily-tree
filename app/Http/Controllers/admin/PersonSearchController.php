<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PersonSelectResource;
use App\Models\Person;
use Illuminate\Http\Request;

class PersonSearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $term = trim((string) $request->input('term', ''));

        // Select2 UX: لا نبحث قبل إدخال حرفين على الأقل
        if (mb_strlen($term, 'UTF-8') < 2) {
            return response()->json(['results' => []]);
        }

        $query = Person::query()->with(['parent.parent.parent']);

        $gender = $request->input('gender');
        if (in_array($gender, ['male', 'female'], true)) {
            $query->where('gender', $gender);
        }

        // افتراضيًا: نضمّن الجميع (داخل/خارج العائلة)، ويمكن التحكم بذلك عند الاستخدام.
        $includeOutside = $request->has('include_outside') ? $request->boolean('include_outside') : true;
        if (!$includeOutside) {
            $query->where('from_outside_the_family', false);
        }

        $query->where(function ($q) use ($term) {
            $q->where('first_name', 'LIKE', "%{$term}%")
                ->orWhere('last_name', 'LIKE', "%{$term}%")
                ->orWhereRaw("CONCAT(COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) LIKE ?", ["%{$term}%"]);
        });

        $people = $query->limit(50)->get();

        return response()->json([
            'results' => PersonSelectResource::collection($people)->resolve($request),
        ]);
    }
}

