<?php

use HasinHayder\TyroDashboard\Support\DashboardRoute;
use HasinHayder\TyroDashboard\Support\OnlineUsers;
use Illuminate\Foundation\Auth\User;

it('requires authentication to post a heartbeat', function () {
    $this->post(route(DashboardRoute::name('heartbeat')))
        ->assertRedirect('/login');
});

it('records a heartbeat for the authenticated user', function () {
    $user = new User;
    $user->forceFill(['id' => 1, 'name' => 'Test User', 'email' => 'test@example.com']);

    $this->actingAs($user)
        ->postJson(route(DashboardRoute::name('heartbeat')))
        ->assertOk()
        ->assertJson(['ok' => true]);

    expect(OnlineUsers::isOnline($user->id))->toBeTrue();
    expect(Cache::has('tyro_dashboard_heartbeat_'.$user->id))->toBeTrue();
});

it('emits the heartbeat url in the scripts partial', function () {
    $html = view('tyro-dashboard::partials.scripts')->render();

    expect($html)->toContain(json_encode(route(DashboardRoute::name('heartbeat'))))
        ->toContain('tyro-dashboard-heartbeat');
});
