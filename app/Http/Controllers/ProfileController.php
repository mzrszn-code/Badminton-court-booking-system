<?php

namespace App\Http\Controllers;

use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show()
    {
        $user = auth()->user();
        $recentActivity = UserActivityLog::where('user_id', $user->id)
            ->orderBy('activity_time', 'desc')
            ->take(10)
            ->get();

        return view('profile.show', compact('user', 'recentActivity'));
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'current_password' => ['nullable', 'required_with:new_password'],
            'new_password' => ['nullable', 'min:8', 'confirmed'],
        ]);

        // Update basic info
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];

        // Update password if provided
        if (!empty($validated['current_password'])) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        // Log activity
        UserActivityLog::log($user->id, 'profile_updated', 'Profile information updated');

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Display the user's activity log.
     */
    public function activity()
    {
        $activities = UserActivityLog::where('user_id', auth()->id())
            ->orderBy('activity_time', 'desc')
            ->paginate(20);

        return view('profile.activity', compact('activities'));
    }
}
