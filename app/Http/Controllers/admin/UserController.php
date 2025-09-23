<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->paginate(15);
        $roles = Role::all();
        return view('dashboard.users.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'password' => ['required','string','min:6'],
            'roles' => ['array'],
            'roles.*' => ['string', Rule::exists('roles','name')],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        if (!empty($validated['roles'])) {
            $user->syncRoles($validated['roles']);
        }

        return back()->with('success', 'تم إنشاء المستخدم وإسناد الأدوار بنجاح');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'password' => ['nullable','string','min:6'],
            'roles' => ['array'],
            'roles.*' => ['string', Rule::exists('roles','name')],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];
        if (!empty($validated['password'])) {
            $data['password'] = bcrypt($validated['password']);
        }
        $user->update($data);

        $user->syncRoles($validated['roles'] ?? []);

        return back()->with('success', 'تم تحديث المستخدم وأدواره بنجاح');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'تم حذف المستخدم بنجاح');
    }
}
