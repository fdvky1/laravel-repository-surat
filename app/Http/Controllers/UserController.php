<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('users.index', ['users' => $users]);
    }

    public function show($id)
    {
        $user = User::find($id);

        if ($user) {
            return view('users.show', ['user' => $user]);
        } else {
            return redirect()->route('users.index')->with('error', 'User not found.');
        }
    }

    // public function create()
    // {
    //     return view('users.create');
    // }

    public function store(StoreUserRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::create([
            'name' => $validatedData['name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'role' => $validatedData['role'],
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }
    // public function edit(User $user)
    // {
    //     return view('users.edit', compact('user'));
    // }

    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $validatedData = $request->validated();

            $user->update([
                'name' => $validatedData['name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'role' => $validatedData['role'],
            ]);

            return redirect()->route('users.index')->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            \Log::error('User update error: ' . $e->getMessage());

            return redirect()->route('users.index')->with('error', 'Failed to update user');
        }
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }


}
