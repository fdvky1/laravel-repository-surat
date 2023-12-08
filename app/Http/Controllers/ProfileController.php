<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('profile');
    }

    public function updateProfilePhoto(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'profile_photo' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:5120', // Increased to 5MB (5 * 1024 = 5120 KB)
                'dimensions:max_width=3000,max_height=3000',
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('profile_photo')) {
            $image = $request->file('profile_photo');

            // Check if the uploaded file is unique (avoiding duplicates)
            $existingPhoto = $user->profile_photo;
            if ($existingPhoto && Storage::exists('public/profiles/' . $existingPhoto)) {
                Storage::delete('public/profiles/' . $existingPhoto);
            }

            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/profiles', $imageName);

            $user->profile_photo = $imageName;
            $user->save();
        }

        return redirect()->back()->with('success', 'Profile photo updated successfully');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::user()->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|max:12|required_with:current_password',
            'password_confirmation' => 'nullable|min:8|max:12|required_with:new_password|same:new_password'
        ]);


        $user = User::findOrFail(Auth::user()->id);
        $user->name = $request->input('name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');

        if (!is_null($request->input('current_password'))) {
            if (Hash::check($request->input('current_password'), $user->password)) {
                $user->password = $request->input('new_password');
            } else {
                return redirect()->back()->withInput();
            }
        }

        $user->save();

        return redirect()->route('profile')->withSuccess('Profile updated successfully.');
    }
}
