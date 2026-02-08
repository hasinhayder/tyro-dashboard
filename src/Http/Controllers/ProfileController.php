<?php

namespace HasinHayder\TyroDashboard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use HasinHayder\TyroDashboard\Traits\HasProfilePhoto;

class ProfileController extends BaseController
{
    /**
     * Display the profile page.
     */
    public function index(Request $request)
    {
        return view('tyro-dashboard::profile.index', $this->getViewData());
    }

    /**
     * Update profile information.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'photo' => ['nullable', 'image', 'max:'.config('tyro-dashboard.profile_photo.max_size', 10240)],
            'use_gravatar' => ['boolean'],
        ]);

        if (isset($validated['photo'])) {
            $user->updateProfilePhoto($validated['photo']);
        }

        if (array_key_exists('use_gravatar', $validated)) {
            $user->use_gravatar = $validated['use_gravatar'];
        } else {
             // Handle unchecked checkbox (it won't be in request)
             $user->use_gravatar = false;
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()
            ->route('tyro-dashboard.profile')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('tyro-dashboard.profile')
            ->with('success', 'Password updated successfully.');
    }

    /**
     * Reset 2FA.
     */
    public function reset2FA(Request $request)
    {
        $user = $request->user();

        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        return redirect()
            ->route('tyro-dashboard.profile')
            ->with('success', 'Two-factor authentication has been reset.');
    }

    /**
     * Delete profile photo.
     */
    public function deletePhoto(Request $request)
    {
        $user = $request->user();
        $user->deleteProfilePhoto();

        return back()->with('success', 'Profile photo removed.');
    }
}
