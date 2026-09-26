<?php

namespace HasinHayder\TyroDashboard\Tests\Feature;

use HasinHayder\TyroDashboard\Models\Media;
use HasinHayder\TyroDashboard\Models\MediaCategory;
use HasinHayder\TyroDashboard\Support\DashboardRoute;
use HasinHayder\TyroDashboard\Tests\TestCase;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CategoryAdminUser extends Authenticatable {
    protected $table = 'users';
    protected $guarded = [];

    public function tyroRoleSlugs(): array {
        return ['admin'];
    }
}

class CategoryMemberUser extends Authenticatable {
    protected $table = 'users';
    protected $guarded = [];

    public function tyroRoleSlugs(): array {
        return ['user'];
    }
}

class MediaCategoryTest extends TestCase {
    protected function getPackageProviders($app) {
        return [
            \HasinHayder\Tyro\Providers\TyroServiceProvider::class,
            \HasinHayder\TyroDashboard\Providers\TyroDashboardServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app) {
        parent::defineEnvironment($app);

        $app['config']->set('tyro-dashboard.user_model', CategoryAdminUser::class);
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

    private function createMedia(int $userId, string $filename = 'sample.jpg'): Media {
        return Media::create([
            'user_id' => $userId,
            'filename' => $filename,
            'path' => 'media/'.$filename,
            'webp_path' => null,
            'thumbnail_path' => null,
            'disk' => 'public',
            'mime_type' => 'image/jpeg',
            'size' => 12345,
            'alt_text' => null,
            'source_url' => null,
        ]);
    }

    public function test_user_can_view_own_categories_and_admin_sees_all() {
        $admin = CategoryAdminUser::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('secret'),
        ]);

        $user1 = CategoryMemberUser::create([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => bcrypt('secret'),
        ]);

        $user2 = CategoryMemberUser::create([
            'name' => 'User Two',
            'email' => 'user2@example.com',
            'password' => bcrypt('secret'),
        ]);

        $cat1 = MediaCategory::create(['user_id' => $user1->id, 'name' => 'User1 Category']);
        $cat2 = MediaCategory::create(['user_id' => $user2->id, 'name' => 'User2 Category']);

        // User 1 sees only Cat 1
        $response = $this->actingAs($user1)->get(route(DashboardRoute::name('media.categories.index')));
        $response->assertOk();
        $response->assertSee('User1 Category');
        $response->assertDontSee('User2 Category');

        // Admin sees both
        $responseAdmin = $this->actingAs($admin)->get(route(DashboardRoute::name('media.categories.index')));
        $responseAdmin->assertOk();
        $responseAdmin->assertSee('User1 Category');
        $responseAdmin->assertSee('User2 Category');
    }

    public function test_user_can_create_category_with_unique_slug() {
        $user = CategoryMemberUser::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('secret'),
        ]);

        $response = $this->actingAs($user)->post(route(DashboardRoute::name('media.categories.store')), [
            'name' => 'Logos & Branding',
            'description' => 'Brand assets',
        ]);

        $response->assertRedirect(route(DashboardRoute::name('media.categories.index')));

        $this->assertDatabaseHas('tyro_media_categories', [
            'user_id' => $user->id,
            'name' => 'Logos & Branding',
            'slug' => 'logos-branding',
            'description' => 'Brand assets',
        ]);

        // Creating another category with same name creates unique slug
        $this->actingAs($user)->post(route(DashboardRoute::name('media.categories.store')), [
            'name' => 'Logos & Branding',
        ]);

        $this->assertDatabaseHas('tyro_media_categories', [
            'user_id' => $user->id,
            'slug' => 'logos-branding-1',
        ]);
    }

    public function test_user_can_update_own_category_but_not_others_unless_admin() {
        $admin = CategoryAdminUser::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('secret'),
        ]);

        $user1 = CategoryMemberUser::create([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => bcrypt('secret'),
        ]);

        $user2 = CategoryMemberUser::create([
            'name' => 'User Two',
            'email' => 'user2@example.com',
            'password' => bcrypt('secret'),
        ]);

        $cat1 = MediaCategory::create(['user_id' => $user1->id, 'name' => 'Initial Name']);

        // User 2 tries to update User 1's category -> 403 Forbidden
        $responseForbidden = $this->actingAs($user2)->put(route(DashboardRoute::name('media.categories.update'), $cat1), [
            'name' => 'Hacked Name',
        ]);
        $responseForbidden->assertForbidden();

        // User 1 updates own category -> Success
        $responseUser = $this->actingAs($user1)->put(route(DashboardRoute::name('media.categories.update'), $cat1), [
            'name' => 'Updated Name',
        ]);
        $responseUser->assertRedirect();
        $this->assertEquals('Updated Name', $cat1->fresh()->name);

        // Admin updates User 1's category -> Success
        $responseAdmin = $this->actingAs($admin)->put(route(DashboardRoute::name('media.categories.update'), $cat1), [
            'name' => 'Admin Changed Name',
        ]);
        $responseAdmin->assertRedirect();
        $this->assertEquals('Admin Changed Name', $cat1->fresh()->name);
    }

    public function test_category_deletion_preserves_media_files() {
        $user = CategoryMemberUser::create([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => bcrypt('secret'),
        ]);

        $category = MediaCategory::create(['user_id' => $user->id, 'name' => 'Temp Category']);
        $media = $this->createMedia($user->id, 'photo.jpg');

        $category->media()->attach($media->id);
        $this->assertEquals(1, $category->media()->count());

        // Delete category
        $response = $this->actingAs($user)->delete(route(DashboardRoute::name('media.categories.destroy'), $category));
        $response->assertRedirect();

        $this->assertDatabaseMissing('tyro_media_categories', ['id' => $category->id]);
        $this->assertDatabaseMissing('tyro_media_category_media', ['media_category_id' => $category->id]);
        // The media record itself still exists!
        $this->assertDatabaseHas('tyro_media', ['id' => $media->id]);
    }

    public function test_can_add_multiple_media_to_category() {
        $user = CategoryMemberUser::create([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => bcrypt('secret'),
        ]);

        $category = MediaCategory::create(['user_id' => $user->id, 'name' => 'Galleries']);
        $media1 = $this->createMedia($user->id, 'photo1.jpg');
        $media2 = $this->createMedia($user->id, 'photo2.jpg');
        $media3 = $this->createMedia($user->id, 'photo3.jpg');

        $response = $this->actingAs($user)->postJson(route(DashboardRoute::name('media.categories.add-media'), $category), [
            'media_ids' => [$media1->id, $media2->id, $media3->id],
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'attached_count' => 3,
            'total_media_count' => 3,
        ]);

        $this->assertEquals(3, $category->media()->count());
    }

    public function test_filter_media_by_category_and_uncategorized() {
        $user = CategoryMemberUser::create([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => bcrypt('secret'),
        ]);

        $category = MediaCategory::create(['user_id' => $user->id, 'name' => 'Wallpapers']);
        $catMedia = $this->createMedia($user->id, 'wallpaper-123.jpg');
        $uncatMedia = $this->createMedia($user->id, 'random-note.jpg');

        $category->media()->attach($catMedia->id);

        // Filter by category ID
        $response = $this->actingAs($user)->get(route(DashboardRoute::name('media'), ['category' => $category->id]));
        $response->assertOk();
        $response->assertSee('wallpaper-123.jpg');
        $response->assertDontSee('random-note.jpg');

        // Filter by uncategorized ('none')
        $responseUncat = $this->actingAs($user)->get(route(DashboardRoute::name('media'), ['category' => 'none']));
        $responseUncat->assertOk();
        $responseUncat->assertSee('random-note.jpg');
        $responseUncat->assertDontSee('wallpaper-123.jpg');
    }

    public function test_bulk_category_attach_and_bulk_unlink() {
        $user = CategoryMemberUser::create([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => bcrypt('secret'),
        ]);

        $category = MediaCategory::create(['user_id' => $user->id, 'name' => 'Products']);
        $media1 = $this->createMedia($user->id, 'prod1.jpg');
        $media2 = $this->createMedia($user->id, 'prod2.jpg');

        // Bulk Attach
        $responseAttach = $this->actingAs($user)->post(route(DashboardRoute::name('media.bulk-category-attach')), [
            'selected_ids' => [$media1->id, $media2->id],
            'category_id' => $category->id,
        ]);
        $responseAttach->assertRedirect();
        $this->assertEquals(2, $category->media()->count());

        // Bulk Unlink
        $responseUnlink = $this->actingAs($user)->post(route(DashboardRoute::name('media.bulk-category-unlink')), [
            'selected_ids' => [$media1->id],
            'category_id' => $category->id,
        ]);
        $responseUnlink->assertRedirect();

        $this->assertEquals(1, $category->media()->count());
        $this->assertTrue($category->media->contains($media2->id));
        $this->assertFalse($category->media->contains($media1->id));
        // media1 file still exists in media library
        $this->assertDatabaseHas('tyro_media', ['id' => $media1->id]);
    }

    public function test_single_media_category_update_attach_and_detach() {
        $user = CategoryMemberUser::create([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => bcrypt('secret'),
        ]);

        $category = MediaCategory::create(['user_id' => $user->id, 'name' => 'Headshots']);
        $media = $this->createMedia($user->id, 'avatar.jpg');

        // Attach via AJAX
        $responseAttach = $this->actingAs($user)->patchJson(route(DashboardRoute::name('media.update-categories'), $media), [
            'action' => 'attach',
            'category_id' => $category->id,
        ]);
        $responseAttach->assertOk();
        $responseAttach->assertJson(['success' => true]);
        $this->assertTrue($media->fresh()->categories->contains($category->id));

        // Detach via AJAX
        $responseDetach = $this->actingAs($user)->patchJson(route(DashboardRoute::name('media.update-categories'), $media), [
            'action' => 'detach',
            'category_id' => $category->id,
        ]);
        $responseDetach->assertOk();
        $responseDetach->assertJson(['success' => true]);
        $this->assertFalse($media->fresh()->categories->contains($category->id));
    }
}
