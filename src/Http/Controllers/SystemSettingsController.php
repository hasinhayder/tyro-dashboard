<?php

namespace HasinHayder\TyroDashboard\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SystemSettingsController extends BaseController {
    public function index() {
        $settings = $this->gatherSettings();

        return view('tyro-dashboard::settings.system', $this->getViewData([
            'settings' => $settings,
        ]));
    }

    public function update(Request $request): JsonResponse {
        abort_unless($request->ajax() && $request->wantsJson(), 403);

        $validated = $request->validate([
            // Dashboard
            'TYRO_DASHBOARD_APP_NAME' => 'nullable|string|max:255',
            'TYRO_DASHBOARD_LOGO_HEIGHT' => 'nullable|string|max:20',
            'TYRO_DASHBOARD_SIDEBAR_BG' => 'nullable|string|max:50',
            'TYRO_DASHBOARD_SIDEBAR_TEXT' => 'nullable|string|max:50',
            'TYRO_DASHBOARD_SIDEBAR_PRIMARY' => 'nullable|string|max:50',
            'TYRO_DASHBOARD_SIDEBAR_ACCENT' => 'nullable|string|max:50',
            'TYRO_DASHBOARD_SIDEBAR_ACCENT_FOREGROUND' => 'nullable|string|max:50',
            'TYRO_DASHBOARD_SIDEBAR_HEADER_BORDER' => 'nullable|string|max:50',
            'TYRO_DASHBOARD_SIDEBAR_ACCORDION_COMPACT' => 'nullable|boolean',
            'TYRO_DASHBOARD_COLLAPSIBLE_SIDEBAR' => 'nullable|boolean',
            'TYRO_DASHBOARD_DISABLE_EXAMPLES' => 'nullable|boolean',
            'TYRO_DASHBOARD_ENABLE_INVITATION' => 'nullable|boolean',
            'TYRO_DASHBOARD_ENABLE_AUDIT_LOGS' => 'nullable|boolean',
            'TYRO_DASHBOARD_NOTIFICATION_STYLE' => 'nullable|in:legacy,toast',
            'TYRO_DASHBOARD_TOAST_POSITION' => 'nullable|in:top-right,bottom-right',
            'TYRO_DASHBOARD_ADMIN_BAR_ENABLED' => 'nullable|boolean',
            'TYRO_DASHBOARD_ADMIN_BAR_MESSAGE' => 'nullable|string|max:500',
            'TYRO_DASHBOARD_ADMIN_BAR_BG_COLOR' => 'nullable|string|max:50',
            'TYRO_DASHBOARD_ADMIN_BAR_TEXT_COLOR' => 'nullable|string|max:50',
            'TYRO_DASHBOARD_ADMIN_BAR_ALIGN' => 'nullable|in:left,center,right',
            'TYRO_DASHBOARD_ADMIN_BAR_HEIGHT' => 'nullable|string|max:20',

            // Tyro RBAC
            'TYRO_CACHE_ENABLED' => 'nullable|boolean',
            'TYRO_CACHE_TTL' => 'nullable|integer|min:0|max:86400',
            'DEFAULT_ROLE_SLUG' => 'nullable|string|max:100',
            'DELETE_PREVIOUS_ACCESS_TOKENS_ON_LOGIN' => 'nullable|boolean',
            'TYRO_DISABLE_API' => 'nullable|boolean',
            'TYRO_PASSWORD_MIN_LENGTH' => 'nullable|integer|min:4|max:100',
            'TYRO_PASSWORD_REQUIRE_UPPERCASE' => 'nullable|boolean',
            'TYRO_PASSWORD_REQUIRE_LOWERCASE' => 'nullable|boolean',
            'TYRO_PASSWORD_REQUIRE_NUMBERS' => 'nullable|boolean',
            'TYRO_PASSWORD_REQUIRE_SPECIAL_CHARS' => 'nullable|boolean',
            'TYRO_PASSWORD_CHECK_COMMON' => 'nullable|boolean',

            // Login Auth
            'TYRO_LOGIN_LAYOUT' => 'nullable|in:centered,split-left,split-right,fullscreen,card',
            'TYRO_LOGIN_APP_NAME' => 'nullable|string|max:255',
            'TYRO_LOGIN_BACKGROUND_IMAGE' => 'nullable|string|max:500',
            'TYRO_LOGIN_REDIRECT_AFTER_LOGIN' => 'nullable|string|max:255|regex:/^(\/.*)?$/',
            'TYRO_LOGIN_REDIRECT_AFTER_LOGOUT' => 'nullable|string|max:255|regex:/^(\/.*)?$/',
            'TYRO_LOGIN_REGISTRATION_ENABLED' => 'nullable|boolean',
            'TYRO_LOGIN_REQUIRE_EMAIL_VERIFICATION' => 'nullable|boolean',
            'TYRO_LOGIN_REMEMBER_ME' => 'nullable|boolean',
            'TYRO_LOGIN_FORGOT_PASSWORD' => 'nullable|boolean',
            'TYRO_LOGIN_FIELD' => 'nullable|in:email,username,both',
            'TYRO_LOGIN_CAPTCHA_LOGIN' => 'nullable|boolean',
            'TYRO_LOGIN_CAPTCHA_REGISTER' => 'nullable|boolean',
            'TYRO_LOGIN_OTP_ENABLED' => 'nullable|boolean',
            'TYRO_LOGIN_OTP_LENGTH' => 'nullable|integer|min:4|max:8',
            'TYRO_LOGIN_OTP_EXPIRE' => 'nullable|integer|min:1|max:60',
            'TYRO_LOGIN_OTP_MAX_RESEND' => 'nullable|integer|min:1|max:20',
            'TYRO_LOGIN_2FA_ENABLED' => 'nullable|boolean',
            'TYRO_LOGIN_2FA_ALLOW_SKIP' => 'nullable|boolean',
            'TYRO_LOGIN_ENABLE_MAGIC_LINKS' => 'nullable|boolean',
            'TYRO_LOGIN_EMAIL_MAGIC_LINK_SUBJECT' => 'nullable|string|max:255',
            'TYRO_LOGIN_MAGIC_LINK_EXPIRE' => 'nullable|integer|min:1|max:60',
            'TYRO_LOGIN_SOCIAL_ENABLED' => 'nullable|boolean',
            'TYRO_LOGIN_SOCIAL_AUTO_REGISTER' => 'nullable|boolean',
            'TYRO_LOGIN_LOCKOUT_ENABLED' => 'nullable|boolean',
            'TYRO_LOGIN_LOCKOUT_MAX_ATTEMPTS' => 'nullable|integer|min:1|max:50',
            'TYRO_LOGIN_LOCKOUT_DURATION' => 'nullable|integer|min:1|max:1440',
            'TYRO_LOGIN_VERIFICATION_EXPIRE' => 'nullable|integer|min:1|max:1440',
            'TYRO_LOGIN_PASSWORD_RESET_EXPIRE' => 'nullable|integer|min:1|max:1440',
            'TYRO_LOGIN_EMAIL_OTP' => 'nullable|boolean',
            'TYRO_LOGIN_EMAIL_PASSWORD_RESET' => 'nullable|boolean',
            'TYRO_LOGIN_EMAIL_VERIFY' => 'nullable|boolean',
            'TYRO_LOGIN_EMAIL_WELCOME' => 'nullable|boolean',
            'TYRO_LOGIN_EMAIL_MAGIC_LINK' => 'nullable|boolean',
        ]);

        $booleans = $this->booleanKeys();

        $data = [];
        foreach ($validated as $key => $value) {
            $data[$key] = in_array($key, $booleans, true)
                ? ($value ? 'true' : 'false')
                : (string) $value;
        }

        $configPath = $this->configFilePath();
        $export = var_export($data, true);
        file_put_contents($configPath, "<?php\n\nreturn {$export};\n");

        $envPath = base_path('.env');

        if (file_exists($envPath)) {
            $content = file_get_contents($envPath);

            foreach ($data as $key => $value) {
                $escaped = str_replace("'", "\\'", $value);
                $serialized = "'{$escaped}'";

                if (preg_match("/^{$key}=/m", $content)) {
                    $content = preg_replace("/^{$key}=.*/m", "{$key}={$serialized}", $content);
                } else {
                    $content = rtrim($content)."\n{$key}={$serialized}\n";
                }
            }

            file_put_contents($envPath, $content);
        }

        return response()->json(['success' => true, 'message' => 'System settings saved successfully.']);
    }

    public function clearConfigCache(): JsonResponse {
        try {
            Artisan::call('config:clear');

            return response()->json(['success' => true, 'message' => 'Config cache cleared.']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Config clear skipped.'], 200);
        }
    }

    protected function booleanKeys(): array {
        return [
            'TYRO_DASHBOARD_SIDEBAR_ACCORDION_COMPACT',
            'TYRO_DASHBOARD_COLLAPSIBLE_SIDEBAR',
            'TYRO_DASHBOARD_DISABLE_EXAMPLES',
            'TYRO_DASHBOARD_ENABLE_INVITATION',
            'TYRO_DASHBOARD_ENABLE_AUDIT_LOGS',
            'TYRO_DASHBOARD_ADMIN_BAR_ENABLED',
            'TYRO_CACHE_ENABLED',
            'DELETE_PREVIOUS_ACCESS_TOKENS_ON_LOGIN',
            'TYRO_DISABLE_API',
            'TYRO_PASSWORD_REQUIRE_UPPERCASE',
            'TYRO_PASSWORD_REQUIRE_LOWERCASE',
            'TYRO_PASSWORD_REQUIRE_NUMBERS',
            'TYRO_PASSWORD_REQUIRE_SPECIAL_CHARS',
            'TYRO_PASSWORD_CHECK_COMMON',
            'TYRO_LOGIN_REGISTRATION_ENABLED',
            'TYRO_LOGIN_REQUIRE_EMAIL_VERIFICATION',
            'TYRO_LOGIN_REMEMBER_ME',
            'TYRO_LOGIN_FORGOT_PASSWORD',
            'TYRO_LOGIN_CAPTCHA_LOGIN',
            'TYRO_LOGIN_CAPTCHA_REGISTER',
            'TYRO_LOGIN_OTP_ENABLED',
            'TYRO_LOGIN_2FA_ENABLED',
            'TYRO_LOGIN_2FA_ALLOW_SKIP',
            'TYRO_LOGIN_ENABLE_MAGIC_LINKS',
            'TYRO_LOGIN_SOCIAL_ENABLED',
            'TYRO_LOGIN_SOCIAL_AUTO_REGISTER',
            'TYRO_LOGIN_LOCKOUT_ENABLED',
            'TYRO_LOGIN_EMAIL_OTP',
            'TYRO_LOGIN_EMAIL_PASSWORD_RESET',
            'TYRO_LOGIN_EMAIL_VERIFY',
            'TYRO_LOGIN_EMAIL_WELCOME',
            'TYRO_LOGIN_EMAIL_MAGIC_LINK',
        ];
    }

    protected function configFilePath(): string {
        return config_path('system-settings.php');
    }

    protected function readConfigFile(): array {
        $path = $this->configFilePath();
        if (! file_exists($path)) {
            return [];
        }

        return (array) include $path;
    }

    protected function gatherSettings(): array {
        $saved = $this->readConfigFile();
        $get = fn (string $key, string $config) => $saved[$key] ?? config($config);

        return [
            'TYRO_DASHBOARD_APP_NAME' => $get('TYRO_DASHBOARD_APP_NAME', 'tyro-dashboard.branding.app_name'),
            'TYRO_DASHBOARD_LOGO_HEIGHT' => $get('TYRO_DASHBOARD_LOGO_HEIGHT', 'tyro-dashboard.branding.logo_height'),
            'TYRO_DASHBOARD_SIDEBAR_BG' => $get('TYRO_DASHBOARD_SIDEBAR_BG', 'tyro-dashboard.branding.sidebar_bg'),
            'TYRO_DASHBOARD_SIDEBAR_TEXT' => $get('TYRO_DASHBOARD_SIDEBAR_TEXT', 'tyro-dashboard.branding.sidebar_text'),
            'TYRO_DASHBOARD_SIDEBAR_PRIMARY' => $get('TYRO_DASHBOARD_SIDEBAR_PRIMARY', 'tyro-dashboard.branding.sidebar_primary'),
            'TYRO_DASHBOARD_SIDEBAR_ACCENT' => $get('TYRO_DASHBOARD_SIDEBAR_ACCENT', 'tyro-dashboard.branding.sidebar_accent'),
            'TYRO_DASHBOARD_SIDEBAR_ACCENT_FOREGROUND' => $get('TYRO_DASHBOARD_SIDEBAR_ACCENT_FOREGROUND', 'tyro-dashboard.branding.sidebar_accent_foreground'),
            'TYRO_DASHBOARD_SIDEBAR_HEADER_BORDER' => $get('TYRO_DASHBOARD_SIDEBAR_HEADER_BORDER', 'tyro-dashboard.branding.sidebar_header_border'),
            'TYRO_DASHBOARD_SIDEBAR_ACCORDION_COMPACT' => $get('TYRO_DASHBOARD_SIDEBAR_ACCORDION_COMPACT', 'tyro-dashboard.branding.sidebar_accordion_compact'),
            'TYRO_DASHBOARD_COLLAPSIBLE_SIDEBAR' => $get('TYRO_DASHBOARD_COLLAPSIBLE_SIDEBAR', 'tyro-dashboard.collapsible_sidebar'),
            'TYRO_DASHBOARD_DISABLE_EXAMPLES' => $get('TYRO_DASHBOARD_DISABLE_EXAMPLES', 'tyro-dashboard.disable_examples'),
            'TYRO_DASHBOARD_ENABLE_INVITATION' => $get('TYRO_DASHBOARD_ENABLE_INVITATION', 'tyro-dashboard.features.invitation_system'),
            'TYRO_DASHBOARD_ENABLE_AUDIT_LOGS' => $get('TYRO_DASHBOARD_ENABLE_AUDIT_LOGS', 'tyro-dashboard.features.audit_logs'),
            'TYRO_DASHBOARD_NOTIFICATION_STYLE' => $get('TYRO_DASHBOARD_NOTIFICATION_STYLE', 'tyro-dashboard.notifications.notification_style'),
            'TYRO_DASHBOARD_TOAST_POSITION' => $get('TYRO_DASHBOARD_TOAST_POSITION', 'tyro-dashboard.notifications.toast_position'),
            'TYRO_DASHBOARD_ADMIN_BAR_ENABLED' => $get('TYRO_DASHBOARD_ADMIN_BAR_ENABLED', 'tyro-dashboard.admin_bar.enabled'),
            'TYRO_DASHBOARD_ADMIN_BAR_MESSAGE' => $get('TYRO_DASHBOARD_ADMIN_BAR_MESSAGE', 'tyro-dashboard.admin_bar.message'),
            'TYRO_DASHBOARD_ADMIN_BAR_BG_COLOR' => $get('TYRO_DASHBOARD_ADMIN_BAR_BG_COLOR', 'tyro-dashboard.admin_bar.bg_color'),
            'TYRO_DASHBOARD_ADMIN_BAR_TEXT_COLOR' => $get('TYRO_DASHBOARD_ADMIN_BAR_TEXT_COLOR', 'tyro-dashboard.admin_bar.text_color'),
            'TYRO_DASHBOARD_ADMIN_BAR_ALIGN' => $get('TYRO_DASHBOARD_ADMIN_BAR_ALIGN', 'tyro-dashboard.admin_bar.align'),
            'TYRO_DASHBOARD_ADMIN_BAR_HEIGHT' => $get('TYRO_DASHBOARD_ADMIN_BAR_HEIGHT', 'tyro-dashboard.admin_bar.height'),

            'TYRO_CACHE_ENABLED' => $get('TYRO_CACHE_ENABLED', 'tyro.cache.enabled'),
            'TYRO_CACHE_TTL' => $get('TYRO_CACHE_TTL', 'tyro.cache.ttl'),
            'DEFAULT_ROLE_SLUG' => $get('DEFAULT_ROLE_SLUG', 'tyro.default_user_role_slug'),
            'DELETE_PREVIOUS_ACCESS_TOKENS_ON_LOGIN' => $get('DELETE_PREVIOUS_ACCESS_TOKENS_ON_LOGIN', 'tyro.delete_previous_access_tokens_on_login'),
            'TYRO_DISABLE_API' => $get('TYRO_DISABLE_API', 'tyro.disable_api'),
            'TYRO_PASSWORD_MIN_LENGTH' => $get('TYRO_PASSWORD_MIN_LENGTH', 'tyro.password.min_length'),
            'TYRO_PASSWORD_REQUIRE_UPPERCASE' => $get('TYRO_PASSWORD_REQUIRE_UPPERCASE', 'tyro.password.complexity.require_uppercase'),
            'TYRO_PASSWORD_REQUIRE_LOWERCASE' => $get('TYRO_PASSWORD_REQUIRE_LOWERCASE', 'tyro.password.complexity.require_lowercase'),
            'TYRO_PASSWORD_REQUIRE_NUMBERS' => $get('TYRO_PASSWORD_REQUIRE_NUMBERS', 'tyro.password.complexity.require_numbers'),
            'TYRO_PASSWORD_REQUIRE_SPECIAL_CHARS' => $get('TYRO_PASSWORD_REQUIRE_SPECIAL_CHARS', 'tyro.password.complexity.require_special_chars'),
            'TYRO_PASSWORD_CHECK_COMMON' => $get('TYRO_PASSWORD_CHECK_COMMON', 'tyro.password.check_common_passwords'),

            'TYRO_LOGIN_LAYOUT' => $get('TYRO_LOGIN_LAYOUT', 'tyro-login.layout'),
            'TYRO_LOGIN_APP_NAME' => $get('TYRO_LOGIN_APP_NAME', 'tyro-login.branding.app_name'),
            'TYRO_LOGIN_BACKGROUND_IMAGE' => $get('TYRO_LOGIN_BACKGROUND_IMAGE', 'tyro-login.background_image'),
            'TYRO_LOGIN_REDIRECT_AFTER_LOGIN' => $get('TYRO_LOGIN_REDIRECT_AFTER_LOGIN', 'tyro-login.redirects.after_login'),
            'TYRO_LOGIN_REDIRECT_AFTER_LOGOUT' => $get('TYRO_LOGIN_REDIRECT_AFTER_LOGOUT', 'tyro-login.redirects.after_logout'),
            'TYRO_LOGIN_REGISTRATION_ENABLED' => $get('TYRO_LOGIN_REGISTRATION_ENABLED', 'tyro-login.registration.enabled'),
            'TYRO_LOGIN_REQUIRE_EMAIL_VERIFICATION' => $get('TYRO_LOGIN_REQUIRE_EMAIL_VERIFICATION', 'tyro-login.registration.require_email_verification'),
            'TYRO_LOGIN_REMEMBER_ME' => $get('TYRO_LOGIN_REMEMBER_ME', 'tyro-login.features.remember_me'),
            'TYRO_LOGIN_FORGOT_PASSWORD' => $get('TYRO_LOGIN_FORGOT_PASSWORD', 'tyro-login.features.forgot_password'),
            'TYRO_LOGIN_FIELD' => $get('TYRO_LOGIN_FIELD', 'tyro-login.login_field'),
            'TYRO_LOGIN_CAPTCHA_LOGIN' => $get('TYRO_LOGIN_CAPTCHA_LOGIN', 'tyro-login.captcha.enabled_login'),
            'TYRO_LOGIN_CAPTCHA_REGISTER' => $get('TYRO_LOGIN_CAPTCHA_REGISTER', 'tyro-login.captcha.enabled_register'),
            'TYRO_LOGIN_OTP_ENABLED' => $get('TYRO_LOGIN_OTP_ENABLED', 'tyro-login.otp.enabled'),
            'TYRO_LOGIN_OTP_LENGTH' => $get('TYRO_LOGIN_OTP_LENGTH', 'tyro-login.otp.length'),
            'TYRO_LOGIN_OTP_EXPIRE' => $get('TYRO_LOGIN_OTP_EXPIRE', 'tyro-login.otp.expire'),
            'TYRO_LOGIN_OTP_MAX_RESEND' => $get('TYRO_LOGIN_OTP_MAX_RESEND', 'tyro-login.otp.max_resend'),
            'TYRO_LOGIN_2FA_ENABLED' => $get('TYRO_LOGIN_2FA_ENABLED', 'tyro-login.two_factor.enabled'),
            'TYRO_LOGIN_2FA_ALLOW_SKIP' => $get('TYRO_LOGIN_2FA_ALLOW_SKIP', 'tyro-login.two_factor.allow_skip'),
            'TYRO_LOGIN_ENABLE_MAGIC_LINKS' => $get('TYRO_LOGIN_ENABLE_MAGIC_LINKS', 'tyro-login.features.magic_links_enabled'),
            'TYRO_LOGIN_EMAIL_MAGIC_LINK_SUBJECT' => $get('TYRO_LOGIN_EMAIL_MAGIC_LINK_SUBJECT', 'tyro-login.emails.magic_link.subject'),
            'TYRO_LOGIN_MAGIC_LINK_EXPIRE' => $get('TYRO_LOGIN_MAGIC_LINK_EXPIRE', 'tyro-login.emails.magic_link.expire'),
            'TYRO_LOGIN_SOCIAL_ENABLED' => $get('TYRO_LOGIN_SOCIAL_ENABLED', 'tyro-login.social.enabled'),
            'TYRO_LOGIN_SOCIAL_AUTO_REGISTER' => $get('TYRO_LOGIN_SOCIAL_AUTO_REGISTER', 'tyro-login.social.auto_register'),
            'TYRO_LOGIN_LOCKOUT_ENABLED' => $get('TYRO_LOGIN_LOCKOUT_ENABLED', 'tyro-login.lockout.enabled'),
            'TYRO_LOGIN_LOCKOUT_MAX_ATTEMPTS' => $get('TYRO_LOGIN_LOCKOUT_MAX_ATTEMPTS', 'tyro-login.lockout.max_attempts'),
            'TYRO_LOGIN_LOCKOUT_DURATION' => $get('TYRO_LOGIN_LOCKOUT_DURATION', 'tyro-login.lockout.duration_minutes'),
            'TYRO_LOGIN_VERIFICATION_EXPIRE' => $get('TYRO_LOGIN_VERIFICATION_EXPIRE', 'tyro-login.verification.expire'),
            'TYRO_LOGIN_PASSWORD_RESET_EXPIRE' => $get('TYRO_LOGIN_PASSWORD_RESET_EXPIRE', 'tyro-login.password_reset.expire'),
            'TYRO_LOGIN_EMAIL_OTP' => $get('TYRO_LOGIN_EMAIL_OTP', 'tyro-login.emails.otp.enabled'),
            'TYRO_LOGIN_EMAIL_PASSWORD_RESET' => $get('TYRO_LOGIN_EMAIL_PASSWORD_RESET', 'tyro-login.emails.password_reset.enabled'),
            'TYRO_LOGIN_EMAIL_VERIFY' => $get('TYRO_LOGIN_EMAIL_VERIFY', 'tyro-login.emails.verify_email.enabled'),
            'TYRO_LOGIN_EMAIL_WELCOME' => $get('TYRO_LOGIN_EMAIL_WELCOME', 'tyro-login.emails.welcome.enabled'),
            'TYRO_LOGIN_EMAIL_MAGIC_LINK' => $get('TYRO_LOGIN_EMAIL_MAGIC_LINK', 'tyro-login.emails.magic_link.enabled'),
        ];
    }
}
