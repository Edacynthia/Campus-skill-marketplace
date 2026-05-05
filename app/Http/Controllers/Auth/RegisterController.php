<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $email = strtolower($request->email);
        $isUniversityEmail = str_ends_with($email, '@edouniversity.edu.ng'); // ← Change to your real domain

        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ];

        if (!$isUniversityEmail) {
            $rules['passport_photo'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
        }

        $request->validate($rules);

        // Handle Passport Upload
        $passportPath = null;
        if ($request->hasFile('passport_photo')) {
            $passportPath = $request->file('passport_photo')->store('passports', 'public');
        }

        $user = User::create([
            'first_name'     => $request->first_name,
            'last_name'      => $request->last_name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'role'           => 'user',
            'is_approved'    => $isUniversityEmail,
            'passport_photo' => $passportPath,
        ]);

        Auth::login($user);

        if ($user->is_approved) {
            return redirect()->route('dashboard')
                ->with('success', 'Account created successfully! Welcome aboard.');
        } else {
            return redirect()->route('dashboard')
                ->with('warning', 'Account created successfully. Awaiting admin approval.');
        }
    }
}