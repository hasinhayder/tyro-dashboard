<?php

namespace HasinHayder\TyroDashboard\Http\Controllers;

use HasinHayder\Tyro\Support\TyroAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

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
        $oldEmail = $user->email;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'photo' => ['nullable', 'image', 'max:'.config('tyro-dashboard.profile_photo.max_size', 10240)],
            'use_gravatar' => ['boolean'],
        ]);

        if (isset($validated['photo']) && method_exists($user, 'hasProfilePhotoColumn') && $user->hasProfilePhotoColumn()) {
            $user->updateProfilePhoto($validated['photo']);
        }

        if (method_exists($user, 'hasGravatarColumn') && $user->hasGravatarColumn()) {
            if (array_key_exists('use_gravatar', $validated)) {
                $user->use_gravatar = $validated['use_gravatar'];
            } else {
                // Handle unchecked checkbox (it won't be in request)
                $user->use_gravatar = false;
            }
        }

        $user->fill(collect($validated)->except(['photo', 'use_gravatar'])->toArray());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($oldEmail !== $user->email) {
            $this->auditSafely('user.email_changed', $user, ['email' => $oldEmail], ['email' => $user->email]);
        }

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

    /**
     * Delete another user's profile photo (Admin).
     */
    public function deleteUserPhoto(Request $request, $id)
    {
        $userModel = config('tyro-dashboard.user_model', 'App\Models\User');
        $user = $userModel::findOrFail($id);

        if (method_exists($user, 'deleteProfilePhoto')) {
            $user->deleteProfilePhoto();
        } else {
            // Fallback if trait is missing for some reason
            if ($user->profile_photo_path) {
                Storage::disk(config('tyro-dashboard.profile_photo.disk', 'public'))->delete($user->profile_photo_path);
                $user->profile_photo_path = null;
                $user->save();
            }
        }

        return back()->with('success', "{$user->name}'s profile photo removed.");
    }

    /**
     * Write an audit entry without breaking profile actions.
     */
    protected function auditSafely(string $event, $auditable = null, ?array $oldValues = null, ?array $newValues = null): void
    {
        try {
            TyroAudit::log($event, $auditable, $oldValues, $newValues);
        } catch (\Throwable $e) {
            // Intentionally ignore audit failures for dashboard stability.
        }
    }
}
