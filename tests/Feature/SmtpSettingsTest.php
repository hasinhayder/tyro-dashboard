<?php

namespace HasinHayder\TyroDashboard\Tests\Feature;

use HasinHayder\TyroDashboard\Models\SmtpPreset;
use HasinHayder\TyroDashboard\Support\DashboardRoute;
use HasinHayder\TyroDashboard\Tests\TestCase;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SmtpAdminUser extends Authenticatable {
    protected $table = 'users';
    protected $guarded = [];

    public function tyroRoleSlugs(): array {
        return ['admin'];
    }
}

class SmtpMemberUser extends Authenticatable {
    protected $table = 'users';
    protected $guarded = [];

    public function tyroRoleSlugs(): array {
        return ['user'];
    }
}

class SmtpSettingsTest extends TestCase {
    protected string $envPath;
    protected bool $envExisted = false;
    protected ?string $envBackup = null;

    protected function defineEnvironment($app) {
        parent::defineEnvironment($app);

        $app['config']->set('tyro-dashboard.user_model', SmtpAdminUser::class);
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite.database', ':memory:');
    }

    protected function defineRoutes($router) {
        parent::defineRoutes($router);

        // The admin topbar renders a logout form against tyro-login's route,
        // which does not exist without the tyro-login provider loaded.
        $router->post('logout', fn () => 'logout')->name('tyro-login.logout');
    }

    protected function setUp(): void {
        parent::setUp();

        // users table + package migrations (tyro_media, tyro_smtp_presets, ...).
        // The package migration path is already registered by the provider's
        // boot(); loadLaravelMigrations() adds the framework tables, and the
        // explicit migrate runs everything against the in-memory database.
        $this->loadLaravelMigrations();
        $this->artisan('migrate')->run();

        // Never leave a modified .env behind in the Testbench skeleton:
        // snapshot whatever exists and restore it after each test.
        $this->envPath = base_path('.env');
        $this->envExisted = file_exists($this->envPath);
        $this->envBackup = $this->envExisted ? file_get_contents($this->envPath) : null;
    }

    protected function tearDown(): void {
        if ($this->envExisted) {
            file_put_contents($this->envPath, $this->envBackup);
        } elseif (file_exists($this->envPath)) {
            unlink($this->envPath);
        }

        parent::tearDown();
    }

    protected function admin(): SmtpAdminUser {
        return SmtpAdminUser::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'secret',
        ]);
    }

    protected function member(): SmtpMemberUser {
        return SmtpMemberUser::create([
            'name' => 'Member User',
            'email' => 'member@example.com',
            'password' => 'secret',
        ]);
    }

    protected function seedEnv(string $content): void {
        file_put_contents($this->envPath, $content);
    }

    protected function envContents(): string {
        return (string) file_get_contents($this->envPath);
    }

    /**
     * Every SMTP endpoint requires both an AJAX request and a JSON response.
     */
    protected function ajaxHeaders(): array {
        return ['X-Requested-With' => 'XMLHttpRequest'];
    }

    public function test_guest_is_redirected_to_login(): void {
        $this->get(route(DashboardRoute::name('settings.smtp.index')))
            ->assertRedirect('/login');
    }

    public function test_non_admin_cannot_view_smtp_settings(): void {
        $this->actingAs($this->member())
            ->get(route(DashboardRoute::name('settings.smtp.index')))
            ->assertRedirect(route(DashboardRoute::name('index')));
    }

    public function test_non_admin_cannot_modify_smtp_settings_or_presets(): void {
        $member = $this->member();
        $this->seedEnv("APP_NAME=\"Tyro\"\n");

        $this->actingAs($member)
            ->postJson(route(DashboardRoute::name('settings.smtp.update')), [
                'MAIL_MAILER' => 'smtp',
                'MAIL_HOST' => 'evil.example.com',
                'MAIL_PORT' => 587,
            ], $this->ajaxHeaders())
            ->assertRedirect(route(DashboardRoute::name('index')));

        $this->actingAs($member)
            ->postJson(route(DashboardRoute::name('settings.smtp.presets.store')), [
                'name' => 'Rogue',
                'mailer' => 'smtp',
                'host' => 'evil.example.com',
                'port' => 587,
            ], $this->ajaxHeaders())
            ->assertRedirect(route(DashboardRoute::name('index')));

        expect($this->envContents())->not->toContain('evil.example.com');
        expect(SmtpPreset::count())->toBe(0);
    }

    public function test_admin_can_view_smtp_settings(): void {
        $this->actingAs($this->admin())
            ->get(route(DashboardRoute::name('settings.smtp.index')))
            ->assertOk()
            ->assertSee('SMTP Settings');
    }

    public function test_update_writes_mail_settings_to_env(): void {
        $this->seedEnv(implode("\n", [
            'APP_NAME="Tyro"',
            'MAIL_MAILER="log"',
            'MAIL_HOST="old.example.com"',
            'MAIL_USERNAME="old-user"',
            '',
        ]));

        $this->actingAs($this->admin())
            ->postJson(route(DashboardRoute::name('settings.smtp.update')), [
                'MAIL_MAILER' => 'smtp',
                'MAIL_HOST' => 'smtp.example.com',
                'MAIL_PORT' => 2525,
                'MAIL_SCHEME' => 'tls',
                'MAIL_USERNAME' => null,
                'MAIL_PASSWORD' => 'plain-secret',
                'MAIL_FROM_ADDRESS' => 'hello@example.com',
                'MAIL_FROM_NAME' => 'Example App',
            ], $this->ajaxHeaders())
            ->assertOk()
            ->assertJson(['success' => true]);

        $env = $this->envContents();

        expect($env)
            ->toContain('MAIL_MAILER="smtp"')
            ->toContain('MAIL_HOST="smtp.example.com"')
            ->toContain('MAIL_PORT="2525"')
            ->toContain('MAIL_SCHEME="tls"')
            ->toContain('MAIL_PASSWORD="plain-secret"')
            ->toContain('MAIL_FROM_ADDRESS="hello@example.com"')
            ->toContain('MAIL_FROM_NAME="Example App"');

        // Unrelated keys survive, replaced keys leave no stale value behind,
        // and blank optional values are removed from .env entirely.
        expect($env)->toContain('APP_NAME="Tyro"');
        expect($env)->not->toContain('old.example.com');
        expect($env)->not->toContain('MAIL_USERNAME');
    }

    public function test_update_rejects_invalid_values(): void {
        $this->seedEnv("APP_NAME=\"Tyro\"\n");

        $this->actingAs($this->admin())
            ->postJson(route(DashboardRoute::name('settings.smtp.update')), [
                'MAIL_MAILER' => 'carrier-pigeon',
                'MAIL_HOST' => 'smtp.example.com',
                'MAIL_PORT' => 2525,
            ], $this->ajaxHeaders())
            ->assertStatus(422)
            ->assertJsonValidationErrors(['MAIL_MAILER']);

        expect($this->envContents())->not->toContain('carrier-pigeon');
    }

    public function test_update_requires_ajax_json_request(): void {
        $this->seedEnv("APP_NAME=\"Tyro\"\n");

        // Missing X-Requested-With / Accept: application/json → 403, no write.
        $this->actingAs($this->admin())
            ->post(route(DashboardRoute::name('settings.smtp.update')), [
                'MAIL_MAILER' => 'smtp',
                'MAIL_HOST' => 'smtp.example.com',
                'MAIL_PORT' => 2525,
            ])
            ->assertForbidden();

        expect($this->envContents())->not->toContain('smtp.example.com');
    }

    public function test_store_preset_encrypts_password_and_hides_it_from_responses(): void {
        $response = $this->actingAs($this->admin())
            ->postJson(route(DashboardRoute::name('settings.smtp.presets.store')), [
                'name' => 'Production',
                'mailer' => 'smtp',
                'host' => 'smtp.prod.example',
                'port' => 587,
                'encryption' => 'tls',
                'username' => 'prod-user',
                'password' => 'super-secret',
                'from_address' => 'noreply@example.com',
                'from_name' => 'Example',
            ], $this->ajaxHeaders())
            ->assertOk()
            ->assertJson(['success' => true]);

        $preset = SmtpPreset::where('name', 'Production')->first();

        expect($preset)->not->toBeNull();
        expect($preset->password)->not->toBe('super-secret');
        expect(decrypt($preset->password))->toBe('super-secret');
        expect($response->json('preset'))->not->toHaveKey('password');
    }

    public function test_store_preset_allows_missing_host_for_non_smtp_mailers(): void {
        $this->actingAs($this->admin())
            ->postJson(route(DashboardRoute::name('settings.smtp.presets.store')), [
                'name' => 'Log Only',
                'mailer' => 'log',
            ], $this->ajaxHeaders())
            ->assertOk()
            ->assertJson(['success' => true]);

        $preset = SmtpPreset::where('name', 'Log Only')->first();

        expect($preset)->not->toBeNull();
        expect($preset->host)->toBe('');
        expect($preset->port)->toBe(587);
    }

    public function test_update_preset_keeps_password_when_field_is_omitted(): void {
        $preset = SmtpPreset::create([
            'name' => 'Staging',
            'mailer' => 'smtp',
            'host' => 'smtp.staging.example',
            'port' => 587,
            'encryption' => 'tls',
            'username' => 'staging-user',
            'password' => encrypt('original-secret'),
        ]);

        $this->actingAs($this->admin())
            ->putJson(route(DashboardRoute::name('settings.smtp.presets.update'), $preset->id), [
                'name' => 'Staging',
                'mailer' => 'smtp',
                'host' => 'smtp.staging2.example',
                'port' => 587,
                'encryption' => 'tls',
                'username' => 'staging-user',
            ], $this->ajaxHeaders())
            ->assertOk()
            ->assertJson(['success' => true]);

        $preset->refresh();

        expect($preset->host)->toBe('smtp.staging2.example');
        expect(decrypt($preset->password))->toBe('original-secret');
    }

    public function test_apply_preset_writes_decrypted_settings_to_env(): void {
        $this->seedEnv("APP_NAME=\"Tyro\"\nMAIL_MAILER=\"log\"\n");

        $preset = SmtpPreset::create([
            'name' => 'Staging',
            'mailer' => 'smtp',
            'host' => 'smtp.staging.example',
            'port' => 465,
            'encryption' => 'ssl',
            'username' => 'staging-user',
            'password' => encrypt('staging-secret'),
            'from_address' => 'staging@example.com',
            'from_name' => 'Staging',
        ]);

        $this->actingAs($this->admin())
            ->postJson(route(DashboardRoute::name('settings.smtp.presets.apply'), $preset->id), [], $this->ajaxHeaders())
            ->assertOk()
            ->assertJson(['success' => true]);

        $env = $this->envContents();

        expect($env)
            ->toContain('MAIL_MAILER="smtp"')
            ->toContain('MAIL_HOST="smtp.staging.example"')
            ->toContain('MAIL_PORT="465"')
            ->toContain('MAIL_SCHEME="ssl"')
            ->toContain('MAIL_USERNAME="staging-user"')
            ->toContain('MAIL_PASSWORD="staging-secret"')
            ->toContain('MAIL_FROM_ADDRESS="staging@example.com"')
            ->toContain('MAIL_FROM_NAME="Staging"');
    }

    public function test_destroy_preset_deletes_the_record(): void {
        $preset = SmtpPreset::create([
            'name' => 'Obsolete',
            'mailer' => 'log',
            'host' => '',
            'port' => 587,
        ]);

        $this->actingAs($this->admin())
            ->deleteJson(route(DashboardRoute::name('settings.smtp.presets.destroy'), $preset->id), [], $this->ajaxHeaders())
            ->assertOk()
            ->assertJson(['success' => true]);

        expect(SmtpPreset::count())->toBe(0);
    }
}
