<?php

namespace HasinHayder\TyroDashboard\Http\Controllers;

use HasinHayder\Tyro\Support\TyroAudit;
use HasinHayder\TyroDashboard\Support\DashboardRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends BaseController {
    /**
     * Display the profile page.
     */
    public function index(Request $request) {
        return view('tyro-dashboard::profile.index', $this->getViewData());
    }

    /**
     * Update profile information.
     */
    public function update(Request $request) {
        $user = $request->user();
        $oldEmail = $user->email;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'profile_photo_url' => ['nullable', 'string', 'max:2048'],
            'use_gravatar' => ['boolean'],
        ]);

        if (method_exists($user, 'hasProfilePhotoColumn') && $user->hasProfilePhotoColumn()) {
            if (array_key_exists('profile_photo_url', $validated)) {
                $photoUrl = $validated['profile_photo_url'];
                if ($photoUrl) {
                    $appUrl = url('/');
                    if (str_starts_with($photoUrl, $appUrl)) {
                        $photoUrl = substr($photoUrl, strlen($appUrl));
                    }
                    $storageUrl = config('filesystems.disks.public.url', '/storage');
                    $storagePath = rtrim(parse_url($storageUrl, PHP_URL_PATH) ?: '/storage', '/');
                    if (str_starts_with($photoUrl, $storagePath)) {
                        $photoUrl = substr($photoUrl, strlen($storagePath));
                    }
                }
                $user->profile_photo_path = $photoUrl ?: null;
            }
        }

        if (method_exists($user, 'hasGravatarColumn') && $user->hasGravatarColumn()) {
            if (array_key_exists('use_gravatar', $validated)) {
                $user->use_gravatar = $validated['use_gravatar'];
            } else {
                $user->use_gravatar = false;
            }
        }

        $user->fill(collect($validated)->except(['profile_photo_url', 'use_gravatar'])->toArray());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($oldEmail !== $user->email) {
            $this->auditSafely('user.email_changed', $user, ['email' => $oldEmail], ['email' => $user->email]);
        }

        return redirect()
            ->route(DashboardRoute::name('profile'))
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request) {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route(DashboardRoute::name('profile'))
            ->with('success', 'Password updated successfully.');
    }

    /**
     * Reset 2FA.
     */
    public function reset2FA(Request $request) {
        $user = $request->user();

        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        return redirect()
            ->route(DashboardRoute::name('profile'))
            ->with('success', 'Two-factor authentication has been reset.');
    }

    /**
     * Initiate 2FA setup from the profile page.
     */
    public function setup2FA(Request $request) {
        $user = Auth::user();

        if ($user->two_factor_secret || $user->two_factor_confirmed_at) {
            $user->forceFill([
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
                'two_factor_confirmed_at' => null,
            ])->save();
        }

        $cookieName = 'tyro_2fa_ignore_' . $user->id;

        $request->session()->put('url.intended', route(DashboardRoute::name('profile')));

        return redirect()->to(route('tyro-login.two-factor.setup'))
            ->withCookie(Cookie::forget($cookieName));
    }

    /**
     * Delete profile photo.
     */
    public function deletePhoto(Request $request) {
        $user = $request->user();
        $user->deleteProfilePhoto();

        return back()->with('success', 'Profile photo removed.');
    }

    /**
     * Delete another user's profile photo (Admin).
     */
    public function deleteUserPhoto(Request $request, $id) {
        $userModel = config('tyro-dashboard.user_model', 'App\Models\User');
        $user = $userModel::findOrFail($id);

        if (method_exists($user, 'deleteProfilePhoto')) {
            $user->deleteProfilePhoto();
        } else {
            if ($user->profile_photo_path) {
                $user->profile_photo_path = null;
                $user->save();
            }
        }

        return back()->with('success', "{$user->name}'s profile photo removed.");
    }

    /**
     * Write an audit entry without breaking profile actions.
     */
    protected function auditSafely(string $event, $auditable = null, ?array $oldValues = null, ?array $newValues = null): void {
        try {
            TyroAudit::log($event, $auditable, $oldValues, $newValues);
        } catch (\Throwable $e) {
            // Intentionally ignore audit failures for dashboard stability.
        }
    }
}
