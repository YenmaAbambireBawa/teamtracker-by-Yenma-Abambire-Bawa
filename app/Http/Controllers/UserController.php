<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.form', ['member' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|string|min:6|confirmed',
            'role'        => 'required|in:admin,member',
            'employee_id' => 'nullable|string|max:50|unique:users,employee_id',
            'department'  => 'nullable|string|max:100',
            'phone'       => 'nullable|string|max:30',
        ]);

        User::create([
            'name'        => trim($request->name),
            'email'       => strtolower(trim($request->email)),
            'password'    => Hash::make($request->password),
            'role'        => $request->role,
            'employee_id' => trim($request->employee_id) ?: null,
            'department'  => trim($request->department) ?: null,
            'phone'       => trim($request->phone) ?: null,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Team member account created.');
    }

    public function edit($id)
    {
        $member = User::findOrFail($id);
        return view('users.form', compact('member'));
    }

    public function update(Request $request, $id)
    {
        $member = User::findOrFail($id);

        $request->validate([
            'name'       => 'required|string|max:255',
            'role'       => 'required|in:admin,member',
            'password'   => 'nullable|string|min:6|confirmed',
            'department' => 'nullable|string|max:100',
            'phone'      => 'nullable|string|max:30',
        ]);

        $data = [
            'name'       => trim($request->name),
            'role'       => $request->role,
            'department' => trim($request->department) ?: null,
            'phone'      => trim($request->phone) ?: null,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $member->update($data);

        return redirect()->route('users.index')
            ->with('success', 'User details updated.');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('users.index')
            ->with('success', 'User removed.');
    }
}
