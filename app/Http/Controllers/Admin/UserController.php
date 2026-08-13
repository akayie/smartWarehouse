<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id', 'DESC')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(UserRequest $request)
    {
        $data = $request->all();

        // 1. CRITICAL: Hash the password for Laravel Auth
        $data['password'] = Hash::make($request->password);

        $user = User::create($data);

        // 2. Profile Image Upload
        if ($request->hasFile('profile')) {
            $file_name = time() . '.' . $request->profile->extension();
            $request->profile->move(public_path('images/users/'), $file_name);
            $user->profile = "/images/users/" . $file_name;
            $user->save();
        }

        return redirect()->route('backend.users.index')->with('success', 'User created successfully');
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(UserUpdateRequest $request, string $id)
    {
        $user = User::findOrFail($id);
        $data = $request->all();

        // 1. Password Handling: Only update if a new one is typed
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']); // Keep the current password
        }

        $user->update($data);

        // 2. Profile Image Update
        if ($request->hasFile('profile')) {
            // Delete old image if it exists to save space
            if ($user->profile && File::exists(public_path($user->profile))) {
                File::delete(public_path($user->profile));
            }

            $file_name = time() . '.' . $request->profile->extension();
            $request->profile->move(public_path('images/users/'), $file_name);
            $user->profile = "/images/users/" . $file_name;
            $user->save();
        }

        return redirect()->route('backend.users.index')->with('success', 'User updated successfully');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Delete profile image from folder before deleting user
        if ($user->profile && File::exists(public_path($user->profile))) {
            File::delete(public_path($user->profile));
        }

        $user->delete();
        return redirect()->route('backend.users.index')->with('success', 'User deleted successfully');
    }
}
