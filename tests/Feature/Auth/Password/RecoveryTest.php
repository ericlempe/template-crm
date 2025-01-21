<?php

use App\Livewire\Auth\Password\Recovery;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

use function Pest\Laravel\{assertDatabaseCount, assertDatabaseHas, get};

test('needs to have a route to password recovery', function () {
    get(route('password.recovery'))
        ->assertOk();
});

it('renders successfully', function () {
    Livewire::test(Recovery::class)
        ->assertStatus(200);
});

it('should be able to send an notification with the password recovery link', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(Recovery::class)
        ->assertDontSee('You will receive an email with the password recovery link.')
        ->set('email', $user->email)
        ->call('recoveryPassword')
        ->assertSee('You will receive an email with the password recovery link.');

    Notification::assertSentTo($user, ResetPassword::class);
});

test('making sure the email is a valid email', function ($value, $rule) {
    Livewire::test(Recovery::class)
        ->set('email', $value)
        ->call('recoveryPassword')
        ->assertHasErrors(['email' => $rule]);
})->with([
    'required' => ['value' => '', 'rule' => 'required'],
    'email'    => ['value' => 'wrong-email', 'rule' => 'email'],
]);

test('needs to create a token when requesting a password recovery', function () {
    $user = User::factory()->create();

    Livewire::test(Recovery::class)
        ->set('email', $user->email)
        ->call('recoveryPassword');

    assertDatabaseCount('password_reset_tokens', 1);
    assertDatabaseHas('password_reset_tokens', ['email' => $user->email]);
});
