<?php

namespace HasinHayder\TyroDashboard\Tests\Feature;

use HasinHayder\TyroDashboard\Http\Controllers\EmailerController;
use HasinHayder\TyroDashboard\Jobs\SendQueuedEmailJob;
use HasinHayder\TyroDashboard\Support\DashboardRoute;
use HasinHayder\TyroDashboard\Tests\TestCase;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

class EmailerFeatureTest extends TestCase {
    protected function defineEnvironment($app) {
        parent::defineEnvironment($app);

        $app['config']->set('tyro-dashboard.features.emailer', true);
        $app['config']->set('tyro-dashboard.admin_roles', ['admin']);
    }

    protected function defineRoutes($router) {
        parent::defineRoutes($router);
        $router->post('logout', fn () => 'logout')->name('tyro-login.logout');
    }

    protected function createAdminUser(): User {
        return new class extends User {
            public function tyroRoleSlugs(): array {
                return ['admin'];
            }
        };
    }

    public function test_emailer_routes_are_registered_when_feature_is_enabled(): void {
        $this->assertNotNull(app('router')->getRoutes()->getByName(DashboardRoute::name('emailer.index')));
        $this->assertNotNull(app('router')->getRoutes()->getByName(DashboardRoute::name('emailer.send')));
        $this->assertNotNull(app('router')->getRoutes()->getByName(DashboardRoute::name('emailer.preview')));
    }

    public function test_emailer_index_page_is_accessible_by_admin(): void {
        $user = $this->createAdminUser();

        $response = $this->actingAs($user)->get(route(DashboardRoute::name('emailer.index')));
        $response->assertOk();
        $response->assertSee('Emailer');
        $response->assertSee('Select Email Design Preset');
        $response->assertSee('Modern Minimal');
        $response->assertSee('Corporate Announcement');
    }

    public function test_emailer_send_dispatches_background_job(): void {
        Queue::fake();

        $user = $this->createAdminUser();

        $response = $this->actingAs($user)->postJson(route(DashboardRoute::name('emailer.send')), [
            'to' => 'test@example.com, another@example.com',
            'subject' => 'Important Notification',
            'body' => '<p>Hello from <strong>Tyro Dashboard</strong>!</p>',
            'design' => 'corporate',
            'cc' => 'team@example.com',
            'bcc' => 'audit@example.com',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'count' => 2,
        ]);

        Queue::assertPushed(SendQueuedEmailJob::class, function ($job) {
            return in_array('test@example.com', $job->to)
                && in_array('another@example.com', $job->to)
                && $job->subject === 'Important Notification'
                && $job->design === 'corporate'
                && in_array('team@example.com', $job->cc)
                && in_array('audit@example.com', $job->bcc);
        });
    }

    public function test_emailer_send_validates_required_fields(): void {
        Queue::fake();

        $user = $this->createAdminUser();

        $response = $this->actingAs($user)->postJson(route(DashboardRoute::name('emailer.send')), [
            'to' => '',
            'subject' => '',
            'body' => '',
        ]);

        $response->assertStatus(422);
        Queue::assertNothingPushed();
    }

    public function test_emailer_preview_returns_rendered_html(): void {
        $user = $this->createAdminUser();

        $response = $this->actingAs($user)->post(route(DashboardRoute::name('emailer.preview')), [
            'design' => 'newsletter',
            'subject' => 'Preview Subject',
            'body' => '<p>Newsletter Body Content</p>',
        ]);

        $response->assertOk();
        $response->assertSee('Preview Subject');
        $response->assertSee('Newsletter Body Content');
    }

    public function test_send_queued_email_job_executes_mail_send(): void {
        Mail::shouldReceive('send')
            ->once()
            ->with(
                'tyro-dashboard::emailer.templates.modern',
                \Mockery::on(function ($data) {
                    return $data['subject'] === 'Job Test Subject' && $data['content'] === '<p>Body</p>';
                }),
                \Mockery::type('Closure')
            );

        $job = new SendQueuedEmailJob(
            to: ['recipient@example.com'],
            subject: 'Job Test Subject',
            content: '<p>Body</p>',
            design: 'modern'
        );

        $job->handle();
    }
}
