<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /** Roles that can be assigned to staff from this screen. */
    private function roleOptions(): array
    {
        return User::STAFF_ROLES;
    }

    public function index()
    {
        $users = User::whereIn('role', User::STAFF_ROLES)
            ->orderBy('name')
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create', [
            'user' => new User(['role' => User::ROLE_MANAGER]),
            'roles' => $this->roleOptions(),
        ]);
    }

    public function store(UserRequest $request)
    {
        $data = $request->validated();
        $data['is_admin'] = true; // staff flag retained for backward compatibility

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'Staff member created successfully.');
    }

    public function edit(User $user)
    {
        abort_if($user->isCustomer(), 404);

        return view('admin.users.edit', [
            'user' => $user,
            'roles' => $this->roleOptions(),
        ]);
    }

    public function update(UserRequest $request, User $user)
    {
        abort_if($user->isCustomer(), 404);

        $data = $request->validated();

        // Don't overwrite the password when the field is left blank.
        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        // Prevent an admin from demoting themselves and being locked out.
        if ($user->id === Auth::id() && $data['role'] !== User::ROLE_ADMIN) {
            return back()->with('error', 'You cannot change your own role.');
        }

        $data['is_admin'] = true;
        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Staff member updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return back()->with('success', 'Staff member deleted successfully.');
    }
}
