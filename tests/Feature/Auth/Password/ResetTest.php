<?php

use App\Livewire\Auth\Password\{Recovery, Reset};
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\{Hash, Notification};
use Livewire\Livewire;

use function Pest\Laravel\get;
use function PHPUnit\Framework\assertTrue;

test('need to receive a valid token with a combination with the email', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(Recovery::class)
        ->set('email', $user->email)
        ->call('recoveryPassword');

    Notification::assertSentTo([$user], ResetPassword::class, function ($notification) {

        get(route('password.reset') . '?token=' . $notification->token)
            ->assertSuccessful();

        get(route('password.reset') . '?token=' . 'any-token')
            ->assertRedirect(route('login'));

        return true;
    });
});

test('if is possible to reset the password with the given token', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(Recovery::class)
        ->set('email', $user->email)
        ->call('recoveryPassword');

    Notification::assertSentTo([$user], ResetPassword::class, function ($notification) use ($user) {

        Livewire::test(Reset::class, ['token' => $notification->token, 'email' => $user->email])
            ->set('email_confirmation', $user->email)
            ->set('password', 'new-password')
            ->set('password_confirmation', 'new-password')
            ->call('changePassword')
            ->assertHasNoErrors()
            ->assertRedirect(route('login'));

        $user->refresh();

        assertTrue(Hash::check('new-password', $user->password));

        return true;
    });
});

test('validating rules', function ($field, $value, $rule) {

    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(Recovery::class)
        ->set('email', $user->email)
        ->call('recoveryPassword');

    Notification::assertSentTo([$user], ResetPassword::class, function ($notification) use ($user, $field, $value, $rule) {

        Livewire::test(Reset::class, ['token' => $notification->token, 'email' => $user->email])
            ->set($field, $value)
            ->call('changePassword')
            ->assertHasErrors([$field => $rule]);

        return true;
    });
})->with([
    'email:required'     => ['field' => 'email', 'value' => '', 'rule' => 'required'],
    'email:confirmed'    => ['field' => 'email', 'value' => 'email@email.com', 'rule' => 'confirmed'],
    'email:email'        => ['field' => 'email', 'value' => 'wrong-email', 'rule' => 'email'],
    'password:required'  => ['field' => 'password', 'value' => '', 'rule' => 'required'],
    'password:confirmed' => ['field' => 'password', 'value' => 'any-password', 'rule' => 'confirmed'],
]);

test('needs to show an obfuscate email to the user', function () {

    $email = "email@example.com";

    $obfuscateEmail = obfuscate_email($email);
    expect($obfuscateEmail)->toBe("em***@********com");

    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(Recovery::class)
        ->set('email', $user->email)
        ->call('recoveryPassword');

    Notification::assertSentTo([$user], ResetPassword::class, function ($notification) use ($user) {

        Livewire::test(Reset::class, ['token' => $notification->token, 'email' => $user->email])
            ->assertSet('obfuscatedEmail', obfuscate_email($user->email));

        return true;
    });

});
