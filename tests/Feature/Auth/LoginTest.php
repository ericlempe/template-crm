<?php

namespace Tests\Feature\Livewire\Auth;

use App\Livewire\Auth\Login;
use App\Models\User;
use Livewire\Livewire;

it('should render the component', function () {
    Livewire::test(Login::class)
        ->assertStatus(200);
});

it('should be able to login', function () {
    $user = User::factory()->create(['email' => 'johndoe@example.com', 'password' => 'password']);

    Livewire::test(Login::class)
        ->set('email', 'johndoe@example.com')
        ->set('password', 'password')
        ->call('login')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard'));

    expect(auth()->check())->toBeTrue()
        ->and(auth()->user())->id->toBe($user->id);
});

it('should to inform the user an error when the credentials are invalid', function () {
    Livewire::test(Login::class)
        ->set('email', 'johndoe@example.com')
        ->set('password', 'password')
        ->call('login')
        ->assertHasErrors(['invalidCredentials'])
        ->assertSee(trans('auth.failed'));
});

it('should make sure that the rate limiting is blocking after 5 attempts', function () {
    $user = User::factory()->create(['email' => 'johndoe@example.com', 'password' => 'password']);

    for ($i = 0; $i < 5; $i++) {
        Livewire::test(Login::class)
            ->set('email', $user->email)
            ->set('password', 'wrong_password')
            ->call('login');
    }

    Livewire::test(Login::class)
        ->set('email', 'johndoe@example.com')
        ->set('password', 'password')
        ->call('login')
        ->assertHasErrors(['rateLimiter']);

});

test('required fields', function ($field) {
    Livewire::test(Login::class)
        ->set($field, '')
        ->call('login')
        ->assertHasErrors([$field => 'required']);
})->with(['email', 'password']);
