<?php

use App\Providers\RouteServiceProvider;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    expect($response->status())->toBe(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'username' => 'Test_User',
        'mail' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'terms' => true,
    ]);

    expect(auth()->check())->toBeTrue();
    expect($response->status())->toBe(302);
    expect(parse_url($response->headers->get('Location'), PHP_URL_PATH))->toBe(parse_url(RouteServiceProvider::HOME, PHP_URL_PATH));
});
