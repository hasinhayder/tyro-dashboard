<?php

use HasinHayder\TyroDashboard\Http\Controllers\SystemSettingsController;
use HasinHayder\TyroDashboard\Tests\TestCase;

class SystemSettings2faRolesTest extends TestCase {
    protected function defaultSettings(): array {
        $controller = app(SystemSettingsController::class);
        $method = new ReflectionMethod($controller, 'defaultValues');
        $method->setAccessible(true);

        return $method->invoke($controller);
    }

    protected function renderRoles(array $overrides, array $options): string {
        return view('tyro-dashboard::settings.partials._tab-login-auth-advanced', [
            'settings' => array_merge($this->defaultSettings(), $overrides),
            'roleOptions' => $options,
        ])->render();
    }

    public function test_forced_and_skip_role_checkboxes_render_in_order(): void {
        $html = $this->renderRoles([], ['user' => 'User', 'editor' => 'Editor']);

        $this->assertStringContainsString('name="TYRO_LOGIN_2FA_FORCED_ROLES[]"', $html);
        $this->assertStringContainsString('name="TYRO_LOGIN_2FA_SKIP_ROLES[]"', $html);
        $this->assertStringContainsString('value="user"', $html);
        $this->assertStringContainsString('value="editor"', $html);

        $this->assertLessThan(
            strpos($html, 'name="TYRO_LOGIN_2FA_SKIP_ROLES[]"'),
            strpos($html, 'name="TYRO_LOGIN_2FA_FORCED_ROLES[]"'),
        );
    }

    public function test_configured_roles_are_prechecked(): void {
        $html = $this->renderRoles(
            [
                'TYRO_LOGIN_2FA_FORCED_ROLES' => 'admin',
                'TYRO_LOGIN_2FA_SKIP_ROLES' => 'user, editor',
            ],
            ['user' => 'User', 'editor' => 'Editor', 'admin' => 'Administrator'],
        );

        $this->assertStringContainsString('name="TYRO_LOGIN_2FA_FORCED_ROLES[]" value="admin" checked', $html);
        $this->assertStringContainsString('name="TYRO_LOGIN_2FA_SKIP_ROLES[]" value="user" checked', $html);
        $this->assertStringContainsString('name="TYRO_LOGIN_2FA_SKIP_ROLES[]" value="editor" checked', $html);
        $this->assertStringNotContainsString('name="TYRO_LOGIN_2FA_FORCED_ROLES[]" value="user" checked', $html);
    }

    public function test_empty_roles_render_no_selection(): void {
        $html = $this->renderRoles(
            ['TYRO_LOGIN_2FA_FORCED_ROLES' => '', 'TYRO_LOGIN_2FA_SKIP_ROLES' => ''],
            ['user' => 'User'],
        );

        $this->assertStringNotContainsString('name="TYRO_LOGIN_2FA_FORCED_ROLES[]" value="user" checked', $html);
        $this->assertStringNotContainsString('name="TYRO_LOGIN_2FA_SKIP_ROLES[]" value="user" checked', $html);
    }

    public function test_normalize_role_list_handles_arrays_and_strings(): void {
        $method = new ReflectionMethod(SystemSettingsController::class, 'normalizeRoleList');
        $method->setAccessible(true);

        $this->assertSame('user,editor', $method->invoke(null, ['user', ' editor', '', 'user']));
        $this->assertSame('user,editor', $method->invoke(null, 'user, editor'));
        $this->assertSame('', $method->invoke(null, ['', '  ']));
    }

    public function test_role_options_merge_configured_slugs_when_no_table(): void {
        $controller = app(SystemSettingsController::class);
        $method = new ReflectionMethod($controller, 'roleOptions');
        $method->setAccessible(true);

        $options = $method->invoke($controller, ['user,ghost', 'ghost']);

        $this->assertArrayHasKey('user', $options);
        $this->assertArrayHasKey('ghost', $options);
        $this->assertSame('ghost', $options['ghost']);
    }
}
