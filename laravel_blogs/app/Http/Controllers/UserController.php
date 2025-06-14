<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'in:admin,customer',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = uniqid() . '_' . $avatar->getClientOriginalName();
            $avatarPath = 'storage/avatars';

            if (!File::exists(public_path($avatarPath))) {
                File::makeDirectory(public_path($avatarPath), 0755, true);
            }

            $avatar->move(public_path($avatarPath), $avatarName);
            $data['avatar'] = $avatarPath . '/' . $avatarName;
        }

        $data['password'] = bcrypt($data['password']);
        User::create($data);

        return redirect()->route('users.index');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'role' => 'in:admin,customer',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar && file_exists(public_path($user->avatar))) {
                unlink(public_path($user->avatar));
            }

            $avatar = $request->file('avatar');
            $avatarName = uniqid() . '_' . $avatar->getClientOriginalName();
            $avatarPath = 'storage/avatars';

            if (!File::exists(public_path($avatarPath))) {
                File::makeDirectory(public_path($avatarPath), 0755, true);
            }

            $avatar->move(public_path($avatarPath), $avatarName);
            $data['avatar'] = $avatarPath . '/' . $avatarName;
        }

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index');
    }

    public function destroy(User $user)
    {
        if ($user->avatar && file_exists(public_path($user->avatar))) {
            unlink(public_path($user->avatar));
        }

        $user->delete();

        return redirect()->route('users.index');
    }

    public function profile()
    {
        $user = auth()->user();
        return view('users.profile', compact('user'));
    }

}
