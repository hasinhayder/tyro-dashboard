<?php

namespace HasinHayder\TyroDashboard\Tests\Feature;

use HasinHayder\TyroDashboard\Support\DashboardRoute;
use HasinHayder\TyroDashboard\Support\OnlineUsers;
use HasinHayder\TyroDashboard\Tests\TestCase;
use HasinHayder\TyroLogin\Events\ForceLogout;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Event;
use Laravel\Sanctum\HasApiTokens;

class LogoutTestUser extends Authenticatable {
    use HasApiTokens;

    protected $table = 'users';
    protected $guarded = [];

    public function tyroRoleSlugs(): array {
        return ['admin'];
    }
}

class UserForceLogoutTest extends TestCase {
    protected function defineEnvironment($app) {
        parent::defineEnvironment($app);

        $app['config']->set('tyro-dashboard.user_model', LogoutTestUser::class);
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite.database', ':memory:');
        $app['config']->set('database.redis.client', 'predis');
    }

    protected function setUp(): void {
        parent::setUp();

        $this->loadLaravelMigrations();
        $this->loadMigrationsFrom(__DIR__.'/../../vendor/laravel/sanctum/database/migrations');
    }

    protected function withSessionDriver(string $driver): void {
        $this->app['config']->set('session.driver', $driver);
    }

    protected function skipWithoutRedisServer(): void {
        // Mirrors RedisSessionHandler: session.connection when set, else the 'default' connection
        $connection = config('session.connection') ?: 'default';
        $host = (string) config("database.redis.{$connection}.host", '127.0.0.1');
        $port = (int) config("database.redis.{$connection}.port", 6379);

        $socket = @fsockopen($host, $port, $errorCode, $errorMessage, 1);

        if ($socket === false) {
            $this->markTestSkipped("No Redis server reachable at {$host}:{$port}.");
        }

        fclose($socket);
    }

    public function test_redis_session_driver_dispatches_force_logout_event(): void {
        $this->skipWithoutRedisServer();
        $this->withSessionDriver('redis');
        Event::fake([ForceLogout::class]);

        $admin = LogoutTestUser::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'secret',
        ]);
        $target = LogoutTestUser::create([
            'name' => 'Target User',
            'email' => 'target@example.com',
            'password' => 'secret',
        ]);

        OnlineUsers::touch($target->id);
        expect(OnlineUsers::isOnline($target->id))->toBeTrue();

        $response = $this->actingAs($admin)
            ->post(route(DashboardRoute::name('users.logout'), $target->id));

        $response->assertRedirect(route(DashboardRoute::name('users.index')));
        $response->assertSessionHas('success');

        Event::assertDispatched(ForceLogout::class, fn (ForceLogout $event) => $event->userId === $target->id);
        expect(OnlineUsers::isOnline($target->id))->toBeFalse();
    }

    public function test_database_session_driver_revocation_clears_heartbeat(): void {
        $this->withSessionDriver('database');

        $admin = LogoutTestUser::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'secret',
        ]);
        $target = LogoutTestUser::create([
            'name' => 'Target User',
            'email' => 'target@example.com',
            'password' => 'secret',
        ]);

        \Illuminate\Support\Facades\DB::table('sessions')->insert([
            'id' => 'test-session-id',
            'user_id' => $target->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'test',
            'payload' => 'test',
            'last_activity' => time(),
        ]);
        OnlineUsers::touch($target->id);

        $response = $this->actingAs($admin)
            ->post(route(DashboardRoute::name('users.logout'), $target->id));

        $response->assertRedirect(route(DashboardRoute::name('users.index')));
        $response->assertSessionHas('success');

        expect(\Illuminate\Support\Facades\DB::table('sessions')->where('user_id', $target->id)->count())->toBe(0);
        expect(OnlineUsers::isOnline($target->id))->toBeFalse();
    }

    public function test_admin_cannot_log_themselves_out(): void {
        $this->withSessionDriver('array');
        Event::fake([ForceLogout::class]);

        $admin = LogoutTestUser::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'secret',
        ]);

        $response = $this->actingAs($admin)
            ->post(route(DashboardRoute::name('users.logout'), $admin->id));

        $response->assertRedirect(route(DashboardRoute::name('users.index')));
        $response->assertSessionHas('error');

        Event::assertNotDispatched(ForceLogout::class);
    }

    public function test_unsupported_session_driver_does_not_dispatch_force_logout(): void {
        $this->withSessionDriver('array');
        Event::fake([ForceLogout::class]);

        $admin = LogoutTestUser::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'secret',
        ]);
        $target = LogoutTestUser::create([
            'name' => 'Target User',
            'email' => 'target@example.com',
            'password' => 'secret',
        ]);

        $response = $this->actingAs($admin)
            ->post(route(DashboardRoute::name('users.logout'), $target->id));

        $response->assertRedirect(route(DashboardRoute::name('users.index')));
        $response->assertSessionHas('warning');

        Event::assertNotDispatched(ForceLogout::class);

        OnlineUsers::touch($target->id);
        $this->actingAs($admin)
            ->post(route(DashboardRoute::name('users.logout'), $target->id));

        expect(OnlineUsers::isOnline($target->id))->toBeTrue();
    }
}
