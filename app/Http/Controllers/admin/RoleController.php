<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        // الإحصائيات
        $totalRoles = Role::count();
        $activeRoles = Role::where('is_active', true)->count();
        $inactiveRoles = $totalRoles - $activeRoles;

        // الاستعلام الأساسي
        $query = Role::query();

        // فلترة حسب الحالة (التبويبات)
        $statusFilter = $request->query('status', 'all');
        if ($statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        // بحث
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $roles = $query->latest()->paginate(10);

        return view('dashboard.roles.index', compact(
            'roles',
            'totalRoles',
            'activeRoles',
            'inactiveRoles',
            'statusFilter'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string|max:500',
        ]);

        Role::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => true, // الأدوار الجديدة تكون نشطة افتراضياً
        ]);

        return redirect()->route('roles.index')->with('success', 'تمت إضافة الدور بنجاح.');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($role->id)],
            'description' => 'nullable|string|max:500',
            'is_active' => 'required|boolean',
        ]);

        $role->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('roles.index')->with('success', 'تم تعديل الدور بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        // يمكنك إضافة منطق هنا لمنع حذف أدوار معينة (مثل مدير)
        // if ($role->name === 'admin') {
        //     return redirect()->route('roles.index')->with('error', 'لا يمكن حذف دور المدير.');
        // }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'تم حذف الدور بنجاح.');
    }
}
