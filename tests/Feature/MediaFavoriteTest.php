<?php

namespace HasinHayder\TyroDashboard\Tests\Feature;

use HasinHayder\TyroDashboard\Models\Media;
use HasinHayder\TyroDashboard\Support\DashboardRoute;
use HasinHayder\TyroDashboard\Tests\TestCase;
use Illuminate\Foundation\Auth\User as Authenticatable;

class MediaAdminUser extends Authenticatable {
    protected $table = 'users';
    protected $guarded = [];

    public function tyroRoleSlugs(): array {
        return ['admin'];
    }
}

class MediaMemberUser extends Authenticatable {
    protected $table = 'users';
    protected $guarded = [];

    public function tyroRoleSlugs(): array {
        return ['user'];
    }
}

class MediaFavoriteTest extends TestCase {
    protected function getPackageProviders($app) {
        return [
            \HasinHayder\Tyro\Providers\TyroServiceProvider::class,
            \HasinHayder\TyroDashboard\Providers\TyroDashboardServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app) {
        parent::defineEnvironment($app);

        $app['config']->set('tyro-dashboard.user_model', MediaAdminUser::class);
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite.database', ':memory:');
    }

    protected function defineRoutes($router) {
        parent::defineRoutes($router);
        $router->post('logout', fn () => 'logout')->name('tyro-login.logout');
    }

    protected function setUp(): void {
        parent::setUp();

        $this->loadLaravelMigrations();
        $this->artisan('migrate')->run();
    }

    public function test_can_toggle_favorite_status_for_image_media() {
        $user = MediaAdminUser::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('secret'),
        ]);

        $media = Media::create([
            'user_id' => $user->id,
            'filename' => 'test-photo.jpg',
            'path' => 'media/test-photo.jpg',
            'disk' => 'public',
            'mime_type' => 'image/jpeg',
            'size' => 1024,
            'is_favorite' => false,
        ]);

        // 1. Toggle to favorite
        $response = $this->actingAs($user)
            ->patchJson(route(DashboardRoute::name('media.toggle-favorite'), ['media' => $media->id]));

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'is_favorite' => true,
            ]);

        $this->assertTrue($media->fresh()->is_favorite);

        // 2. Toggle back to unfavorite
        $response = $this->actingAs($user)
            ->patchJson(route(DashboardRoute::name('media.toggle-favorite'), ['media' => $media->id]));

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'is_favorite' => false,
            ]);

        $this->assertFalse($media->fresh()->is_favorite);
    }

    public function test_filter_favorites_in_media_gallery() {
        $user = MediaAdminUser::create([
            'name' => 'Admin User',
            'email' => 'admin2@example.com',
            'password' => bcrypt('secret'),
        ]);

        $fav = Media::create([
            'user_id' => $user->id,
            'filename' => 'favorite-photo.jpg',
            'path' => 'media/favorite-photo.jpg',
            'disk' => 'public',
            'mime_type' => 'image/jpeg',
            'size' => 1024,
            'is_favorite' => true,
        ]);

        $regular = Media::create([
            'user_id' => $user->id,
            'filename' => 'regular-photo.jpg',
            'path' => 'media/regular-photo.jpg',
            'disk' => 'public',
            'mime_type' => 'image/jpeg',
            'size' => 2048,
            'is_favorite' => false,
        ]);

        // Without filter: both images present
        $responseAll = $this->actingAs($user)->get(route(DashboardRoute::name('media')));
        $responseAll->assertOk();
        $responseAll->assertSee('favorite-photo');
        $responseAll->assertSee('regular-photo');
        $responseAll->assertSee('name="favorite"', false);
        $responseAll->assertSee('media-fav-btn', false);
        $responseAll->assertSee('is-favorite', false);

        // With filter favorite=1: only favorite-photo present
        $responseFav = $this->actingAs($user)->get(route(DashboardRoute::name('media'), ['favorite' => '1']));
        $responseFav->assertOk();
        $responseFav->assertSee('favorite-photo');
        $responseFav->assertDontSee('regular-photo');
        $responseFav->assertSee('>Clear</a>', false);

        // When query parameters exist but are all empty strings, Clear button should NOT appear
        $responseEmptyParams = $this->actingAs($user)->get(route(DashboardRoute::name('media'), [
            'view' => 'grid',
            'search' => '',
            'type' => '',
            'date' => '',
            'favorite' => '',
        ]));
        $responseEmptyParams->assertOk();
        $responseEmptyParams->assertDontSee('>Clear</a>', false);
    }

    public function test_filter_favorites_in_media_picker() {
        $user = MediaAdminUser::create([
            'name' => 'Admin User 3',
            'email' => 'admin3@example.com',
            'password' => bcrypt('secret'),
        ]);

        $fav = Media::create([
            'user_id' => $user->id,
            'filename' => 'picker-fav.jpg',
            'path' => 'media/picker-fav.jpg',
            'disk' => 'public',
            'mime_type' => 'image/jpeg',
            'size' => 1024,
            'is_favorite' => true,
        ]);

        $regular = Media::create([
            'user_id' => $user->id,
            'filename' => 'picker-regular.jpg',
            'path' => 'media/picker-regular.jpg',
            'disk' => 'public',
            'mime_type' => 'image/jpeg',
            'size' => 2048,
            'is_favorite' => false,
        ]);

        // Picker without filter: both images returned
        $responseAll = $this->actingAs($user)
            ->getJson(route(DashboardRoute::name('media.picker'), ['type' => 'image']));
        $responseAll->assertOk();
        $filenamesAll = collect($responseAll->json('data'))->pluck('filename')->all();
        $this->assertContains('picker-fav.jpg', $filenamesAll);
        $this->assertContains('picker-regular.jpg', $filenamesAll);

        // Picker with favorite=1: only favorite returned
        $responseFav = $this->actingAs($user)
            ->getJson(route(DashboardRoute::name('media.picker'), ['type' => 'image', 'favorite' => '1']));
        $responseFav->assertOk();
        $filenamesFav = collect($responseFav->json('data'))->pluck('filename')->all();
        $this->assertContains('picker-fav.jpg', $filenamesFav);
        $this->assertNotContains('picker-regular.jpg', $filenamesFav);

        // Check is_favorite boolean in JSON
        $favItem = collect($responseFav->json('data'))->firstWhere('filename', 'picker-fav.jpg');
        $this->assertTrue($favItem['is_favorite']);
    }
}
