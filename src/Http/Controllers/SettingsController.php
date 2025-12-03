<?php

namespace HasinHayder\TyroDashboard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class SettingsController extends BaseController
{
    /**
     * Display the Tyro settings page.
     */
    public function tyroSettings()
    {
        $config = config('tyro', []);

        return view('tyro-dashboard::settings.tyro', $this->getViewData([
            'config' => $config,
        ]));
    }

    /**
     * Update Tyro settings.
     */
    public function updateTyroSettings(Request $request)
    {
        $validated = $request->validate([
            'user_model' => ['required', 'string', 'max:255'],
            'user_primary_key' => ['required', 'string', 'max:50'],
            'user_foreign_key' => ['required', 'string', 'max:50'],
            'tables' => ['required', 'array'],
            'tables.roles' => ['required', 'string', 'max:50'],
            'tables.privileges' => ['required', 'string', 'max:50'],
            'tables.role_user' => ['required', 'string', 'max:50'],
            'tables.role_privilege' => ['required', 'string', 'max:50'],
            'cache_enabled' => ['nullable', 'boolean'],
            'cache_duration' => ['required', 'integer', 'min:0'],
            'cache_prefix' => ['required', 'string', 'max:50'],
            'middleware' => ['required', 'array'],
            'middleware.role' => ['required', 'string', 'max:50'],
            'middleware.privilege' => ['required', 'string', 'max:50'],
            'suspension_enabled' => ['nullable', 'boolean'],
            'suspension_column' => ['required', 'string', 'max:50'],
        ]);

        $envUpdates = [
            'TYRO_USER_MODEL' => $validated['user_model'],
            'TYRO_USER_PRIMARY_KEY' => $validated['user_primary_key'],
            'TYRO_USER_FOREIGN_KEY' => $validated['user_foreign_key'],
            'TYRO_TABLE_ROLES' => $validated['tables']['roles'],
            'TYRO_TABLE_PRIVILEGES' => $validated['tables']['privileges'],
            'TYRO_TABLE_ROLE_USER' => $validated['tables']['role_user'],
            'TYRO_TABLE_ROLE_PRIVILEGE' => $validated['tables']['role_privilege'],
            'TYRO_CACHE_ENABLED' => isset($validated['cache_enabled']) ? 'true' : 'false',
            'TYRO_CACHE_DURATION' => $validated['cache_duration'],
            'TYRO_CACHE_PREFIX' => $validated['cache_prefix'],
            'TYRO_MIDDLEWARE_ROLE' => $validated['middleware']['role'],
            'TYRO_MIDDLEWARE_PRIVILEGE' => $validated['middleware']['privilege'],
            'TYRO_SUSPENSION_ENABLED' => isset($validated['suspension_enabled']) ? 'true' : 'false',
            'TYRO_SUSPENSION_COLUMN' => $validated['suspension_column'],
        ];

        $this->updateEnvFile($envUpdates);

        // Clear config cache
        Artisan::call('config:clear');

        return redirect()
            ->route('tyro-dashboard.settings.tyro')
            ->with('success', 'Tyro settings updated successfully.');
    }

    /**
     * Display the Tyro Login settings page.
     */
    public function tyroLoginSettings()
    {
        $config = config('tyro-login', []);

        return view('tyro-dashboard::settings.tyro-login', $this->getViewData([
            'config' => $config,
        ]));
    }

    /**
     * Update Tyro Login settings.
     */
    public function updateTyroLoginSettings(Request $request)
    {
        $validated = $request->validate([
            'branding' => ['required', 'array'],
            'branding.app_name' => ['nullable', 'string', 'max:100'],
            'branding.logo_url' => ['nullable', 'string', 'max:500'],
            'branding.favicon_url' => ['nullable', 'string', 'max:500'],
            'branding.primary_color' => ['nullable', 'string', 'max:20'],
            'routes' => ['required', 'array'],
            'routes.prefix' => ['nullable', 'string', 'max:50'],
            'routes.home' => ['required', 'string', 'max:100'],
            'routes.logout_redirect' => ['required', 'string', 'max:100'],
            'features' => ['nullable', 'array'],
            'password' => ['required', 'array'],
            'password.min_length' => ['required', 'integer', 'min:6', 'max:50'],
            'password.max_length' => ['required', 'integer', 'min:8', 'max:255'],
            'rate_limiting' => ['nullable', 'array'],
            'rate_limiting.max_attempts' => ['required', 'integer', 'min:1', 'max:100'],
            'rate_limiting.decay_minutes' => ['required', 'integer', 'min:1', 'max:60'],
            'social' => ['nullable', 'array'],
        ]);

        $envUpdates = [
            'TYRO_LOGIN_APP_NAME' => $validated['branding']['app_name'] ?? config('app.name'),
            'TYRO_LOGIN_LOGO_URL' => $validated['branding']['logo_url'] ?? '',
            'TYRO_LOGIN_FAVICON_URL' => $validated['branding']['favicon_url'] ?? '',
            'TYRO_LOGIN_PRIMARY_COLOR' => $validated['branding']['primary_color'] ?? '#6366f1',
            'TYRO_LOGIN_ROUTE_PREFIX' => $validated['routes']['prefix'] ?? '',
            'TYRO_LOGIN_HOME_PATH' => $validated['routes']['home'],
            'TYRO_LOGIN_LOGOUT_REDIRECT' => $validated['routes']['logout_redirect'],
            'TYRO_LOGIN_REGISTRATION' => isset($validated['features']['registration']) ? 'true' : 'false',
            'TYRO_LOGIN_PASSWORD_RESET' => isset($validated['features']['password_reset']) ? 'true' : 'false',
            'TYRO_LOGIN_EMAIL_VERIFICATION' => isset($validated['features']['email_verification']) ? 'true' : 'false',
            'TYRO_LOGIN_REMEMBER_ME' => isset($validated['features']['remember_me']) ? 'true' : 'false',
            'TYRO_LOGIN_PASSWORD_MIN' => $validated['password']['min_length'],
            'TYRO_LOGIN_PASSWORD_MAX' => $validated['password']['max_length'],
            'TYRO_LOGIN_PASSWORD_UPPERCASE' => isset($validated['password']['require_uppercase']) ? 'true' : 'false',
            'TYRO_LOGIN_PASSWORD_LOWERCASE' => isset($validated['password']['require_lowercase']) ? 'true' : 'false',
            'TYRO_LOGIN_PASSWORD_NUMBER' => isset($validated['password']['require_number']) ? 'true' : 'false',
            'TYRO_LOGIN_PASSWORD_SYMBOL' => isset($validated['password']['require_symbol']) ? 'true' : 'false',
            'TYRO_LOGIN_RATE_LIMITING' => isset($validated['rate_limiting']['enabled']) ? 'true' : 'false',
            'TYRO_LOGIN_MAX_ATTEMPTS' => $validated['rate_limiting']['max_attempts'],
            'TYRO_LOGIN_DECAY_MINUTES' => $validated['rate_limiting']['decay_minutes'],
            'TYRO_LOGIN_SOCIAL_GOOGLE' => isset($validated['social']['google']) ? 'true' : 'false',
            'TYRO_LOGIN_SOCIAL_GITHUB' => isset($validated['social']['github']) ? 'true' : 'false',
            'TYRO_LOGIN_SOCIAL_FACEBOOK' => isset($validated['social']['facebook']) ? 'true' : 'false',
            'TYRO_LOGIN_SOCIAL_TWITTER' => isset($validated['social']['twitter']) ? 'true' : 'false',
        ];

        $this->updateEnvFile($envUpdates);

        // Clear config cache
        Artisan::call('config:clear');

        return redirect()
            ->route('tyro-dashboard.settings.tyro-login')
            ->with('success', 'Tyro Login settings updated successfully.');
    }

    /**
     * Update the .env file with the given key-value pairs.
     */
    protected function updateEnvFile(array $data): void
    {
        $envPath = base_path('.env');

        if (!File::exists($envPath)) {
            return;
        }

        $envContent = File::get($envPath);

        foreach ($data as $key => $value) {
            // Escape value if it contains spaces or special characters
            $escapedValue = (strpos($value, ' ') !== false || strpos($value, '#') !== false) ? '"' . $value . '"' : $value;

            // Check if key exists
            if (preg_match("/^{$key}=.*/m", $envContent)) {
                // Update existing key
                $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$escapedValue}", $envContent);
            } else {
                // Add new key
                $envContent .= "\n{$key}={$escapedValue}";
            }
        }

        File::put($envPath, $envContent);
    }
}
