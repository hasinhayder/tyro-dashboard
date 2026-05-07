<?php

namespace HasinHayder\TyroDashboard\Http\Controllers;

use HasinHayder\TyroDashboard\Support\DashboardColors;
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

            'TYRO_CACHE_ENABLED' => 'nullable|boolean',
            'TYRO_CACHE_TTL' => 'nullable|integer|min:0|max:86400',
            'TYRO_CACHE_STORE' => 'nullable|string|max:50',
            'DEFAULT_ROLE_SLUG' => 'nullable|string|max:100',
            'DELETE_PREVIOUS_ACCESS_TOKENS_ON_LOGIN' => 'nullable|boolean',
            'TYRO_DISABLE_API' => 'nullable|boolean',
            'TYRO_DISABLE_COMMANDS' => 'nullable|boolean',
            'TYRO_ROUTE_PREFIX' => 'nullable|string|max:50',
            'TYRO_AUDIT_ENABLED' => 'nullable|boolean',
            'TYRO_AUDIT_RETENTION_DAYS' => 'nullable|integer|min:1|max:3650',
            'TYRO_PASSWORD_MIN_LENGTH' => 'nullable|integer|min:4|max:100',
            'TYRO_PASSWORD_MAX_LENGTH' => 'nullable|integer|min:0|max:500',
            'TYRO_PASSWORD_REQUIRE_CONFIRMATION' => 'nullable|boolean',
            'TYRO_PASSWORD_DISALLOW_USER_INFO' => 'nullable|boolean',
            'TYRO_PASSWORD_REQUIRE_UPPERCASE' => 'nullable|boolean',
            'TYRO_PASSWORD_REQUIRE_LOWERCASE' => 'nullable|boolean',
            'TYRO_PASSWORD_REQUIRE_NUMBERS' => 'nullable|boolean',
            'TYRO_PASSWORD_REQUIRE_SPECIAL_CHARS' => 'nullable|boolean',
            'TYRO_PASSWORD_CHECK_COMMON' => 'nullable|boolean',

            'TYRO_LOGIN_LAYOUT' => 'nullable|in:centered,split-left,split-right,fullscreen,card',
            'TYRO_LOGIN_APP_NAME' => 'nullable|string|max:255',
            'TYRO_LOGIN_BACKGROUND_IMAGE' => 'nullable|string|max:500',
            'TYRO_LOGIN_LOGO' => 'nullable|string|max:500',
            'TYRO_LOGIN_LOGO_DARK' => 'nullable|string|max:500',
            'TYRO_LOGIN_LOGO_HEIGHT' => 'nullable|string|max:20',
            'TYRO_LOGIN_REDIRECT_AFTER_LOGIN' => 'nullable|string|max:255|regex:/^(\/.*)?$/',
            'TYRO_LOGIN_REDIRECT_AFTER_LOGOUT' => 'nullable|string|max:255|regex:/^(\/.*)?$/',
            'TYRO_LOGIN_REDIRECT_AFTER_REGISTER' => 'nullable|string|max:255|regex:/^(\/.*)?$/',
            'TYRO_LOGIN_REDIRECT_AFTER_EMAIL_VERIFICATION' => 'nullable|string|max:255|regex:/^(\/.*)?$/',
            'TYRO_LOGIN_REGISTRATION_ENABLED' => 'nullable|boolean',
            'TYRO_LOGIN_REQUIRE_EMAIL_VERIFICATION' => 'nullable|boolean',
            'TYRO_LOGIN_REGISTRATION_AUTO_LOGIN' => 'nullable|boolean',
            'TYRO_LOGIN_DISABLE_PASSWORD' => 'nullable|boolean',
            'TYRO_LOGIN_REMEMBER_ME' => 'nullable|boolean',
            'TYRO_LOGIN_FORGOT_PASSWORD' => 'nullable|boolean',
            'TYRO_LOGIN_FIELD' => 'nullable|in:email,username,both',
            'TYRO_LOGIN_CAPTCHA_LOGIN' => 'nullable|boolean',
            'TYRO_LOGIN_CAPTCHA_REGISTER' => 'nullable|boolean',
            'TYRO_LOGIN_OTP_ENABLED' => 'nullable|boolean',
            'TYRO_LOGIN_OTP_LENGTH' => 'nullable|integer|min:4|max:8',
            'TYRO_LOGIN_OTP_EXPIRE' => 'nullable|integer|min:1|max:60',
            'TYRO_LOGIN_OTP_MAX_RESEND' => 'nullable|integer|min:1|max:20',
            'TYRO_LOGIN_OTP_RESEND_COOLDOWN' => 'nullable|integer|min:10|max:600',
            'TYRO_LOGIN_2FA_ENABLED' => 'nullable|boolean',
            'TYRO_LOGIN_2FA_ALLOW_SKIP' => 'nullable|boolean',
            'TYRO_LOGIN_2FA_IGNORE_COOKIE_DAYS' => 'nullable|integer|min:1|max:365',
            'TYRO_LOGIN_2FA_FORCED_ROLES' => 'nullable|string|max:255',
            'TYRO_LOGIN_ENABLE_MAGIC_LINKS' => 'nullable|boolean',
            'TYRO_LOGIN_EMAIL_MAGIC_LINK_SUBJECT' => 'nullable|string|max:255',
            'TYRO_LOGIN_MAGIC_LINK_EXPIRE' => 'nullable|integer|min:1|max:60',
            'TYRO_LOGIN_SOCIAL_ENABLED' => 'nullable|boolean',
            'TYRO_LOGIN_SOCIAL_AUTO_REGISTER' => 'nullable|boolean',
            'TYRO_LOGIN_SOCIAL_LINK_EXISTING' => 'nullable|boolean',
            'TYRO_LOGIN_SOCIAL_AUTO_VERIFY_EMAIL' => 'nullable|boolean',
            'TYRO_LOGIN_LOCKOUT_ENABLED' => 'nullable|boolean',
            'TYRO_LOGIN_LOCKOUT_MAX_ATTEMPTS' => 'nullable|integer|min:1|max:50',
            'TYRO_LOGIN_LOCKOUT_DURATION' => 'nullable|integer|min:1|max:1440',
            'TYRO_LOGIN_SHOW_ATTEMPTS_LEFT' => 'nullable|boolean',
            'TYRO_LOGIN_VERIFICATION_EXPIRE' => 'nullable|integer|min:1|max:1440',
            'TYRO_LOGIN_PASSWORD_RESET_EXPIRE' => 'nullable|integer|min:1|max:1440',
            'TYRO_LOGIN_EMAIL_OTP' => 'nullable|boolean',
            'TYRO_LOGIN_EMAIL_PASSWORD_RESET' => 'nullable|boolean',
            'TYRO_LOGIN_EMAIL_VERIFY' => 'nullable|boolean',
            'TYRO_LOGIN_EMAIL_WELCOME' => 'nullable|boolean',
            'TYRO_LOGIN_EMAIL_MAGIC_LINK' => 'nullable|boolean',

            'TYRO_LOGIN_CAPTCHA_LABEL' => 'nullable|string|max:255',
            'TYRO_LOGIN_CAPTCHA_PLACEHOLDER' => 'nullable|string|max:255',
            'TYRO_LOGIN_CAPTCHA_ERROR' => 'nullable|string|max:255',
            'TYRO_LOGIN_CAPTCHA_MIN' => 'nullable|integer|min:0|max:100',
            'TYRO_LOGIN_CAPTCHA_MAX' => 'nullable|integer|min:1|max:1000',

            'TYRO_LOGIN_OTP_TITLE' => 'nullable|string|max:255',
            'TYRO_LOGIN_OTP_SUBTITLE' => 'nullable|string|max:500',
            'TYRO_LOGIN_OTP_LABEL' => 'nullable|string|max:255',
            'TYRO_LOGIN_OTP_PLACEHOLDER' => 'nullable|string|max:255',
            'TYRO_LOGIN_OTP_SUBMIT_BUTTON' => 'nullable|string|max:100',
            'TYRO_LOGIN_OTP_RESEND_BUTTON' => 'nullable|string|max:100',
            'TYRO_LOGIN_OTP_ERROR' => 'nullable|string|max:500',
            'TYRO_LOGIN_OTP_RESEND_SUCCESS' => 'nullable|string|max:500',
            'TYRO_LOGIN_OTP_MAX_RESEND_ERROR' => 'nullable|string|max:500',
            'TYRO_LOGIN_OTP_BG_TITLE' => 'nullable|string|max:255',
            'TYRO_LOGIN_OTP_BG_DESCRIPTION' => 'nullable|string|max:500',

            'TYRO_LOGIN_2FA_SETUP_TITLE' => 'nullable|string|max:255',
            'TYRO_LOGIN_2FA_SETUP_SUBTITLE' => 'nullable|string|max:500',
            'TYRO_LOGIN_2FA_CHALLENGE_TITLE' => 'nullable|string|max:255',
            'TYRO_LOGIN_2FA_CHALLENGE_SUBTITLE' => 'nullable|string|max:500',

            'TYRO_LOGIN_SOCIAL_GOOGLE' => 'nullable|boolean',
            'TYRO_LOGIN_SOCIAL_FACEBOOK' => 'nullable|boolean',
            'TYRO_LOGIN_SOCIAL_GITHUB' => 'nullable|boolean',
            'TYRO_LOGIN_SOCIAL_TWITTER' => 'nullable|boolean',
            'TYRO_LOGIN_SOCIAL_LINKEDIN' => 'nullable|boolean',
            'TYRO_LOGIN_SOCIAL_BITBUCKET' => 'nullable|boolean',
            'TYRO_LOGIN_SOCIAL_GITLAB' => 'nullable|boolean',
            'TYRO_LOGIN_SOCIAL_SLACK' => 'nullable|boolean',
            'TYRO_LOGIN_SOCIAL_DIVIDER' => 'nullable|string|max:255',

            'TYRO_LOGIN_LOCKOUT_AUTO_REDIRECT' => 'nullable|boolean',
            'TYRO_LOGIN_LOCKOUT_MESSAGE' => 'nullable|string|max:500',
            'TYRO_LOGIN_LOCKOUT_TITLE' => 'nullable|string|max:255',
            'TYRO_LOGIN_LOCKOUT_SUBTITLE' => 'nullable|string|max:500',

            'TYRO_LOGIN_EMAIL_OTP_SUBJECT' => 'nullable|string|max:255',
            'TYRO_LOGIN_EMAIL_PASSWORD_RESET_SUBJECT' => 'nullable|string|max:255',
            'TYRO_LOGIN_EMAIL_VERIFY_SUBJECT' => 'nullable|string|max:255',
            'TYRO_LOGIN_EMAIL_WELCOME_SUBJECT' => 'nullable|string|max:255',

            'dashboard_colors' => 'nullable|array',
            'dashboard_colors.light' => 'nullable|array',
            'dashboard_colors.dark' => 'nullable|array',
            'dashboard_colors.light.*.hex' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'dashboard_colors.light.*.alpha' => 'nullable|integer|min:0|max:100',
            'dashboard_colors.dark.*.hex' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'dashboard_colors.dark.*.alpha' => 'nullable|integer|min:0|max:100',

            'TYRO_LOGIN_BG_TITLE' => 'nullable|string|max:255',
            'TYRO_LOGIN_BG_DESCRIPTION' => 'nullable|string|max:500',
            'TYRO_LOGIN_REGISTER_BG_TITLE' => 'nullable|string|max:255',
            'TYRO_LOGIN_REGISTER_BG_DESCRIPTION' => 'nullable|string|max:500',
            'TYRO_LOGIN_VERIFY_EMAIL_TITLE' => 'nullable|string|max:255',
            'TYRO_LOGIN_VERIFY_EMAIL_SUBTITLE' => 'nullable|string|max:500',
            'TYRO_LOGIN_VERIFY_EMAIL_BG_TITLE' => 'nullable|string|max:255',
            'TYRO_LOGIN_VERIFY_EMAIL_BG_DESCRIPTION' => 'nullable|string|max:500',
            'TYRO_LOGIN_EMAIL_NOT_VERIFIED_TITLE' => 'nullable|string|max:255',
            'TYRO_LOGIN_EMAIL_NOT_VERIFIED_SUBTITLE' => 'nullable|string|max:500',
            'TYRO_LOGIN_EMAIL_NOT_VERIFIED_BG_TITLE' => 'nullable|string|max:255',
            'TYRO_LOGIN_EMAIL_NOT_VERIFIED_BG_DESCRIPTION' => 'nullable|string|max:500',
            'TYRO_LOGIN_FORGOT_PASSWORD_TITLE' => 'nullable|string|max:255',
            'TYRO_LOGIN_FORGOT_PASSWORD_SUBTITLE' => 'nullable|string|max:500',
            'TYRO_LOGIN_FORGOT_PASSWORD_BG_TITLE' => 'nullable|string|max:255',
            'TYRO_LOGIN_FORGOT_PASSWORD_BG_DESCRIPTION' => 'nullable|string|max:500',
            'TYRO_LOGIN_RESET_PASSWORD_TITLE' => 'nullable|string|max:255',
            'TYRO_LOGIN_RESET_PASSWORD_SUBTITLE' => 'nullable|string|max:500',
            'TYRO_LOGIN_RESET_PASSWORD_BG_TITLE' => 'nullable|string|max:255',
            'TYRO_LOGIN_RESET_PASSWORD_BG_DESCRIPTION' => 'nullable|string|max:500',
        ]);

        $booleans = $this->booleanKeys();

        if (array_key_exists('dashboard_colors', $validated)) {
            $submitted = $validated['dashboard_colors'] ?? [];
            $cleaned = [];

            foreach (['light', 'dark'] as $mode) {
                if (! isset($submitted[$mode])) {
                    continue;
                }
                foreach ($submitted[$mode] as $var => $config) {
                    if (is_array($config) && isset($config['hex'], $config['alpha'])) {
                        $cleaned[$mode][$var] = [
                            'hex' => $config['hex'],
                            'alpha' => (int) $config['alpha'],
                        ];
                    }
                }
            }

            DashboardColors::save(empty($cleaned) ? ['light' => [], 'dark' => []] : $cleaned);
            unset($validated['dashboard_colors']);
        }

        $envPath = base_path('.env');

        if (! file_exists($envPath)) {
            return response()->json(['success' => false, 'message' => '.env file not found.'], 500);
        }

        $content = file_get_contents($envPath);

        foreach ($validated as $key => $value) {
            $envValue = in_array($key, $booleans, true)
                ? ($value ? 'true' : 'false')
                : (string) $value;

            $escaped = str_replace("'", "\\'", $envValue);
            $serialized = "'{$escaped}'";

            if (preg_match("/^{$key}=/m", $content)) {
                $content = preg_replace("/^{$key}=.*/m", "{$key}={$serialized}", $content);
            } else {
                $content = rtrim($content)."\n{$key}={$serialized}\n";
            }
        }

        file_put_contents($envPath, $content);

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
            'TYRO_AUDIT_ENABLED',
            'DELETE_PREVIOUS_ACCESS_TOKENS_ON_LOGIN',
            'TYRO_DISABLE_API',
            'TYRO_DISABLE_COMMANDS',
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
            'TYRO_PASSWORD_REQUIRE_CONFIRMATION',
            'TYRO_PASSWORD_DISALLOW_USER_INFO',
            'TYRO_LOGIN_REGISTRATION_AUTO_LOGIN',
            'TYRO_LOGIN_DISABLE_PASSWORD',
            'TYRO_LOGIN_SOCIAL_LINK_EXISTING',
            'TYRO_LOGIN_SOCIAL_AUTO_VERIFY_EMAIL',
            'TYRO_LOGIN_SOCIAL_GOOGLE',
            'TYRO_LOGIN_SOCIAL_FACEBOOK',
            'TYRO_LOGIN_SOCIAL_GITHUB',
            'TYRO_LOGIN_SOCIAL_TWITTER',
            'TYRO_LOGIN_SOCIAL_LINKEDIN',
            'TYRO_LOGIN_SOCIAL_BITBUCKET',
            'TYRO_LOGIN_SOCIAL_GITLAB',
            'TYRO_LOGIN_SOCIAL_SLACK',
            'TYRO_LOGIN_LOCKOUT_AUTO_REDIRECT',
            'TYRO_LOGIN_SHOW_ATTEMPTS_LEFT',
        ];
    }

    protected function gatherSettings(): array {
        return [
            'TYRO_DASHBOARD_APP_NAME' => config('tyro-dashboard.branding.app_name'),
            'TYRO_DASHBOARD_LOGO_HEIGHT' => config('tyro-dashboard.branding.logo_height'),
            'TYRO_DASHBOARD_SIDEBAR_BG' => config('tyro-dashboard.branding.sidebar_bg'),
            'TYRO_DASHBOARD_SIDEBAR_TEXT' => config('tyro-dashboard.branding.sidebar_text'),
            'TYRO_DASHBOARD_SIDEBAR_PRIMARY' => config('tyro-dashboard.branding.sidebar_primary'),
            'TYRO_DASHBOARD_SIDEBAR_ACCENT' => config('tyro-dashboard.branding.sidebar_accent'),
            'TYRO_DASHBOARD_SIDEBAR_ACCENT_FOREGROUND' => config('tyro-dashboard.branding.sidebar_accent_foreground'),
            'TYRO_DASHBOARD_SIDEBAR_HEADER_BORDER' => config('tyro-dashboard.branding.sidebar_header_border'),
            'TYRO_DASHBOARD_SIDEBAR_ACCORDION_COMPACT' => config('tyro-dashboard.branding.sidebar_accordion_compact'),
            'TYRO_DASHBOARD_COLLAPSIBLE_SIDEBAR' => config('tyro-dashboard.collapsible_sidebar'),
            'TYRO_DASHBOARD_DISABLE_EXAMPLES' => config('tyro-dashboard.disable_examples'),
            'TYRO_DASHBOARD_ENABLE_INVITATION' => config('tyro-dashboard.features.invitation_system'),
            'TYRO_DASHBOARD_ENABLE_AUDIT_LOGS' => config('tyro-dashboard.features.audit_logs'),
            'TYRO_DASHBOARD_NOTIFICATION_STYLE' => config('tyro-dashboard.notifications.notification_style'),
            'TYRO_DASHBOARD_TOAST_POSITION' => config('tyro-dashboard.notifications.toast_position'),
            'TYRO_DASHBOARD_ADMIN_BAR_ENABLED' => config('tyro-dashboard.admin_bar.enabled'),
            'TYRO_DASHBOARD_ADMIN_BAR_MESSAGE' => config('tyro-dashboard.admin_bar.message'),
            'TYRO_DASHBOARD_ADMIN_BAR_BG_COLOR' => config('tyro-dashboard.admin_bar.bg_color'),
            'TYRO_DASHBOARD_ADMIN_BAR_TEXT_COLOR' => config('tyro-dashboard.admin_bar.text_color'),
            'TYRO_DASHBOARD_ADMIN_BAR_ALIGN' => config('tyro-dashboard.admin_bar.align'),
            'TYRO_DASHBOARD_ADMIN_BAR_HEIGHT' => config('tyro-dashboard.admin_bar.height'),

            'TYRO_CACHE_ENABLED' => config('tyro.cache.enabled'),
            'TYRO_CACHE_TTL' => config('tyro.cache.ttl'),
            'TYRO_CACHE_STORE' => config('tyro.cache.store'),
            'DEFAULT_ROLE_SLUG' => config('tyro.default_user_role_slug'),
            'DELETE_PREVIOUS_ACCESS_TOKENS_ON_LOGIN' => config('tyro.delete_previous_access_tokens_on_login'),
            'TYRO_DISABLE_API' => config('tyro.disable_api'),
            'TYRO_DISABLE_COMMANDS' => config('tyro.disable_commands'),
            'TYRO_ROUTE_PREFIX' => config('tyro.route_prefix'),
            'TYRO_AUDIT_ENABLED' => config('tyro.audit.enabled'),
            'TYRO_AUDIT_RETENTION_DAYS' => config('tyro.audit.retention_days'),
            'TYRO_PASSWORD_MIN_LENGTH' => config('tyro.password.min_length'),
            'TYRO_PASSWORD_MAX_LENGTH' => config('tyro.password.max_length'),
            'TYRO_PASSWORD_REQUIRE_CONFIRMATION' => config('tyro.password.require_confirmation'),
            'TYRO_PASSWORD_DISALLOW_USER_INFO' => config('tyro.password.disallow_user_info'),
            'TYRO_PASSWORD_REQUIRE_UPPERCASE' => config('tyro.password.complexity.require_uppercase'),
            'TYRO_PASSWORD_REQUIRE_LOWERCASE' => config('tyro.password.complexity.require_lowercase'),
            'TYRO_PASSWORD_REQUIRE_NUMBERS' => config('tyro.password.complexity.require_numbers'),
            'TYRO_PASSWORD_REQUIRE_SPECIAL_CHARS' => config('tyro.password.complexity.require_special_chars'),
            'TYRO_PASSWORD_CHECK_COMMON' => config('tyro.password.check_common_passwords'),

            'TYRO_LOGIN_LAYOUT' => config('tyro-login.layout'),
            'TYRO_LOGIN_APP_NAME' => config('tyro-login.branding.app_name'),
            'TYRO_LOGIN_BACKGROUND_IMAGE' => config('tyro-login.background_image'),
            'TYRO_LOGIN_LOGO' => config('tyro-login.branding.logo'),
            'TYRO_LOGIN_LOGO_DARK' => config('tyro-login.branding.logo_dark'),
            'TYRO_LOGIN_LOGO_HEIGHT' => config('tyro-login.branding.logo_height'),
            'TYRO_LOGIN_REDIRECT_AFTER_LOGIN' => config('tyro-login.redirects.after_login'),
            'TYRO_LOGIN_REDIRECT_AFTER_LOGOUT' => config('tyro-login.redirects.after_logout'),
            'TYRO_LOGIN_REDIRECT_AFTER_REGISTER' => config('tyro-login.redirects.after_register'),
            'TYRO_LOGIN_REDIRECT_AFTER_EMAIL_VERIFICATION' => config('tyro-login.redirects.after_email_verification'),
            'TYRO_LOGIN_REGISTRATION_ENABLED' => config('tyro-login.registration.enabled'),
            'TYRO_LOGIN_REQUIRE_EMAIL_VERIFICATION' => config('tyro-login.registration.require_email_verification'),
            'TYRO_LOGIN_REGISTRATION_AUTO_LOGIN' => config('tyro-login.registration.auto_login'),
            'TYRO_LOGIN_DISABLE_PASSWORD' => config('tyro-login.features.disable_password'),
            'TYRO_LOGIN_REMEMBER_ME' => config('tyro-login.features.remember_me'),
            'TYRO_LOGIN_FORGOT_PASSWORD' => config('tyro-login.features.forgot_password'),
            'TYRO_LOGIN_FIELD' => config('tyro-login.login_field'),
            'TYRO_LOGIN_CAPTCHA_LOGIN' => config('tyro-login.captcha.enabled_login'),
            'TYRO_LOGIN_CAPTCHA_REGISTER' => config('tyro-login.captcha.enabled_register'),
            'TYRO_LOGIN_OTP_ENABLED' => config('tyro-login.otp.enabled'),
            'TYRO_LOGIN_OTP_LENGTH' => config('tyro-login.otp.length'),
            'TYRO_LOGIN_OTP_EXPIRE' => config('tyro-login.otp.expire'),
            'TYRO_LOGIN_OTP_MAX_RESEND' => config('tyro-login.otp.max_resend'),
            'TYRO_LOGIN_OTP_RESEND_COOLDOWN' => config('tyro-login.otp.resend_cooldown'),
            'TYRO_LOGIN_OTP_TITLE' => config('tyro-login.otp.title'),
            'TYRO_LOGIN_OTP_SUBTITLE' => config('tyro-login.otp.subtitle'),
            'TYRO_LOGIN_OTP_LABEL' => config('tyro-login.otp.label'),
            'TYRO_LOGIN_OTP_PLACEHOLDER' => config('tyro-login.otp.placeholder'),
            'TYRO_LOGIN_OTP_SUBMIT_BUTTON' => config('tyro-login.otp.submit_button'),
            'TYRO_LOGIN_OTP_RESEND_BUTTON' => config('tyro-login.otp.resend_button'),
            'TYRO_LOGIN_OTP_ERROR' => config('tyro-login.otp.error_message'),
            'TYRO_LOGIN_OTP_RESEND_SUCCESS' => config('tyro-login.otp.resend_success'),
            'TYRO_LOGIN_OTP_MAX_RESEND_ERROR' => config('tyro-login.otp.max_resend_error'),
            'TYRO_LOGIN_OTP_BG_TITLE' => config('tyro-login.otp.background_title'),
            'TYRO_LOGIN_OTP_BG_DESCRIPTION' => config('tyro-login.otp.background_description'),
            'TYRO_LOGIN_2FA_ENABLED' => config('tyro-login.two_factor.enabled'),
            'TYRO_LOGIN_2FA_ALLOW_SKIP' => config('tyro-login.two_factor.allow_skip'),
            'TYRO_LOGIN_2FA_IGNORE_COOKIE_DAYS' => config('tyro-login.two_factor.ignore_cookie_days'),
            'TYRO_LOGIN_2FA_FORCED_ROLES' => config('tyro-login.two_factor.forced_roles'),
            'TYRO_LOGIN_2FA_SETUP_TITLE' => config('tyro-login.two_factor.setup_title'),
            'TYRO_LOGIN_2FA_SETUP_SUBTITLE' => config('tyro-login.two_factor.setup_subtitle'),
            'TYRO_LOGIN_2FA_CHALLENGE_TITLE' => config('tyro-login.two_factor.challenge_title'),
            'TYRO_LOGIN_2FA_CHALLENGE_SUBTITLE' => config('tyro-login.two_factor.challenge_subtitle'),
            'TYRO_LOGIN_ENABLE_MAGIC_LINKS' => config('tyro-login.features.magic_links_enabled'),
            'TYRO_LOGIN_EMAIL_MAGIC_LINK_SUBJECT' => config('tyro-login.emails.magic_link.subject'),
            'TYRO_LOGIN_MAGIC_LINK_EXPIRE' => config('tyro-login.emails.magic_link.expire'),
            'TYRO_LOGIN_SOCIAL_ENABLED' => config('tyro-login.social.enabled'),
            'TYRO_LOGIN_SOCIAL_AUTO_REGISTER' => config('tyro-login.social.auto_register'),
            'TYRO_LOGIN_SOCIAL_LINK_EXISTING' => config('tyro-login.social.link_existing_accounts'),
            'TYRO_LOGIN_SOCIAL_AUTO_VERIFY_EMAIL' => config('tyro-login.social.auto_verify_email'),
            'TYRO_LOGIN_SOCIAL_GOOGLE' => config('tyro-login.social.providers.google.enabled'),
            'TYRO_LOGIN_SOCIAL_FACEBOOK' => config('tyro-login.social.providers.facebook.enabled'),
            'TYRO_LOGIN_SOCIAL_GITHUB' => config('tyro-login.social.providers.github.enabled'),
            'TYRO_LOGIN_SOCIAL_TWITTER' => config('tyro-login.social.providers.twitter.enabled'),
            'TYRO_LOGIN_SOCIAL_LINKEDIN' => config('tyro-login.social.providers.linkedin.enabled'),
            'TYRO_LOGIN_SOCIAL_BITBUCKET' => config('tyro-login.social.providers.bitbucket.enabled'),
            'TYRO_LOGIN_SOCIAL_GITLAB' => config('tyro-login.social.providers.gitlab.enabled'),
            'TYRO_LOGIN_SOCIAL_SLACK' => config('tyro-login.social.providers.slack.enabled'),
            'TYRO_LOGIN_SOCIAL_DIVIDER' => config('tyro-login.social.divider_text'),
            'TYRO_LOGIN_LOCKOUT_ENABLED' => config('tyro-login.lockout.enabled'),
            'TYRO_LOGIN_LOCKOUT_MAX_ATTEMPTS' => config('tyro-login.lockout.max_attempts'),
            'TYRO_LOGIN_LOCKOUT_DURATION' => config('tyro-login.lockout.duration_minutes'),
            'TYRO_LOGIN_SHOW_ATTEMPTS_LEFT' => config('tyro-login.lockout.show_attempts_left'),
            'TYRO_LOGIN_LOCKOUT_AUTO_REDIRECT' => config('tyro-login.lockout.auto_redirect'),
            'TYRO_LOGIN_LOCKOUT_MESSAGE' => config('tyro-login.lockout.message'),
            'TYRO_LOGIN_LOCKOUT_TITLE' => config('tyro-login.lockout.title'),
            'TYRO_LOGIN_LOCKOUT_SUBTITLE' => config('tyro-login.lockout.subtitle'),
            'TYRO_LOGIN_VERIFICATION_EXPIRE' => config('tyro-login.verification.expire'),
            'TYRO_LOGIN_PASSWORD_RESET_EXPIRE' => config('tyro-login.password_reset.expire'),
            'TYRO_LOGIN_EMAIL_OTP' => config('tyro-login.emails.otp.enabled'),
            'TYRO_LOGIN_EMAIL_PASSWORD_RESET' => config('tyro-login.emails.password_reset.enabled'),
            'TYRO_LOGIN_EMAIL_VERIFY' => config('tyro-login.emails.verify_email.enabled'),
            'TYRO_LOGIN_EMAIL_WELCOME' => config('tyro-login.emails.welcome.enabled'),
            'TYRO_LOGIN_EMAIL_MAGIC_LINK' => config('tyro-login.emails.magic_link.enabled'),
            'TYRO_LOGIN_EMAIL_OTP_SUBJECT' => config('tyro-login.emails.otp.subject'),
            'TYRO_LOGIN_EMAIL_PASSWORD_RESET_SUBJECT' => config('tyro-login.emails.password_reset.subject'),
            'TYRO_LOGIN_EMAIL_VERIFY_SUBJECT' => config('tyro-login.emails.verify_email.subject'),
            'TYRO_LOGIN_EMAIL_WELCOME_SUBJECT' => config('tyro-login.emails.welcome.subject'),

            'TYRO_LOGIN_CAPTCHA_LABEL' => config('tyro-login.captcha.label'),
            'TYRO_LOGIN_CAPTCHA_PLACEHOLDER' => config('tyro-login.captcha.placeholder'),
            'TYRO_LOGIN_CAPTCHA_ERROR' => config('tyro-login.captcha.error_message'),
            'TYRO_LOGIN_CAPTCHA_MIN' => config('tyro-login.captcha.min_number'),
            'TYRO_LOGIN_CAPTCHA_MAX' => config('tyro-login.captcha.max_number'),

            'TYRO_LOGIN_BG_TITLE' => config('tyro-login.pages.login.background_title'),
            'TYRO_LOGIN_BG_DESCRIPTION' => config('tyro-login.pages.login.background_description'),
            'TYRO_LOGIN_REGISTER_BG_TITLE' => config('tyro-login.pages.register.background_title'),
            'TYRO_LOGIN_REGISTER_BG_DESCRIPTION' => config('tyro-login.pages.register.background_description'),
            'TYRO_LOGIN_VERIFY_EMAIL_TITLE' => config('tyro-login.pages.verify_email.title'),
            'TYRO_LOGIN_VERIFY_EMAIL_SUBTITLE' => config('tyro-login.pages.verify_email.subtitle'),
            'TYRO_LOGIN_VERIFY_EMAIL_BG_TITLE' => config('tyro-login.pages.verify_email.background_title'),
            'TYRO_LOGIN_VERIFY_EMAIL_BG_DESCRIPTION' => config('tyro-login.pages.verify_email.background_description'),
            'TYRO_LOGIN_EMAIL_NOT_VERIFIED_TITLE' => config('tyro-login.pages.email_not_verified.title'),
            'TYRO_LOGIN_EMAIL_NOT_VERIFIED_SUBTITLE' => config('tyro-login.pages.email_not_verified.subtitle'),
            'TYRO_LOGIN_EMAIL_NOT_VERIFIED_BG_TITLE' => config('tyro-login.pages.email_not_verified.background_title'),
            'TYRO_LOGIN_EMAIL_NOT_VERIFIED_BG_DESCRIPTION' => config('tyro-login.pages.email_not_verified.background_description'),
            'TYRO_LOGIN_FORGOT_PASSWORD_TITLE' => config('tyro-login.pages.forgot_password.title'),
            'TYRO_LOGIN_FORGOT_PASSWORD_SUBTITLE' => config('tyro-login.pages.forgot_password.subtitle'),
            'TYRO_LOGIN_FORGOT_PASSWORD_BG_TITLE' => config('tyro-login.pages.forgot_password.background_title'),
            'TYRO_LOGIN_FORGOT_PASSWORD_BG_DESCRIPTION' => config('tyro-login.pages.forgot_password.background_description'),
            'TYRO_LOGIN_RESET_PASSWORD_TITLE' => config('tyro-login.pages.reset_password.title'),
            'TYRO_LOGIN_RESET_PASSWORD_SUBTITLE' => config('tyro-login.pages.reset_password.subtitle'),
            'TYRO_LOGIN_RESET_PASSWORD_BG_TITLE' => config('tyro-login.pages.reset_password.background_title'),
            'TYRO_LOGIN_RESET_PASSWORD_BG_DESCRIPTION' => config('tyro-login.pages.reset_password.background_description'),
        ];
    }
}
