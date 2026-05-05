<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'passport_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle Passport Removal
        if ($request->has('remove_passport')) {
            if ($user->passport_photo) {
                Storage::disk('public')->delete($user->passport_photo);
                $user->passport_photo = null;
            }
        }

        // Handle Passport Upload
        if ($request->hasFile('passport_photo')) {
            // Delete old passport photo if exists
            if ($user->passport_photo) {
                Storage::disk('public')->delete($user->passport_photo);
            }
            
            $passportPath = $request->file('passport_photo')->store('passports', 'public');
            $user->passport_photo = $passportPath;
        }

        $user->update([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Profile updated successfully!');
    }
    
    public function show($id)
    {
        $user = \App\Models\User::with(['skills', 'jobsPosted'])->findOrFail($id);
        
        // Get user's active skills with reviews
        $skills = $user->skills()->where('status', 'active')->withCount(['reviews', 'orders'])->latest()->get();
        
        // Get user's posted jobs
        $jobs = $user->jobsPosted()->where('status', 'active')->latest()->get();
        
        // Calculate user's overall rating
        $totalReviews = $user->skills()->withCount('reviews')->get()->sum('reviews_count');
        $avgRating = 0;
        if ($totalReviews > 0) {
            $totalRating = $user->skills()->with('reviews')->get()->flatMap->reviews->sum('rating');
            $avgRating = $totalRating / $totalReviews;
        }
        
        // Get recent reviews for the user's skills
        $reviews = \App\Models\Review::whereIn('skill_id', $skills->pluck('id'))
            ->with(['skill', 'reviewer'])
            ->latest()
            ->take(10)
            ->get();
        
        return view('profile.show', compact('user', 'skills', 'jobs', 'avgRating', 'totalReviews', 'reviews'));
    }
}
