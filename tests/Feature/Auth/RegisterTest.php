<?php

use App\Livewire\Auth\Register;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\{Event};
use Livewire\Livewire;

use function Pest\Laravel\{assertDatabaseCount, assertDatabaseHas};

it('should render the component', function () {
    Livewire::test(Register::class)
        ->assertStatus(200);
});

it('should be able to register a new user', function () {
    Livewire::test(Register::class)
        ->set('name', 'John doe')
        ->set('email', 'john@doe.com')
        ->set('password', 'password')
        ->set('password_confirmation', 'password')
        ->call('submit')
        ->assertHasNoErrors();

    assertDatabaseHas('users', [
        'name'  => 'John doe',
        'email' => 'john@doe.com',
    ]);

    expect(auth()->check())
        ->and(auth()->user())
        ->id->toBe(User::first()->id);

    assertDatabaseCount('users', 1);
});

it('should be able to check if the email is valid', function () {
    Livewire::test(Register::class)
        ->set('name', 'John doe')
        ->set('email', 'invalid-email')
        ->set('password', 'password')
        ->set('password_confirmation', 'password')
        ->call('submit')
        ->assertHasErrors([
            'email' => 'email',
        ]);
});

it('should be able to check if the email already exists', function () {
    User::factory()->create(['email' => 'johndoe@example.com']);

    Livewire::test(Register::class)
        ->set('name', 'John doe')
        ->set('email', 'johndoe@example.com')
        ->set('password', 'password')
        ->set('password_confirmation', 'password')
        ->call('submit')
        ->assertHasErrors([
            'email' => 'unique',
        ]);
});

it('should be able to check if the password fields are different', function () {
    Livewire::test(Register::class)
        ->set('password', 'password')
        ->set('password_confirmation', 'another_password')
        ->call('submit')
        ->assertHasErrors(['password' => 'confirmed']);
});

test('strength password', function ($password, $rule) {
    Livewire::test(Register::class)
        ->set('password', $password)
        ->call('submit')
        ->assertHasErrors(['password' => $rule]);
})->with([
    'min validation' => ['pass', 'min:6'],
    'max validation' => ['max_password', 'max:8'],
]);

test('required fields', function ($field) {
    Livewire::test(Register::class)
        ->set($field, '')
        ->call('submit')
        ->assertHasErrors([$field => 'required']);
})->with(['name', 'email', 'password', 'password_confirmation']);

it('should dispatch registered event', function () {
    Event::fake();

    Livewire::test(Register::class)
        ->set('name', 'John doe')
        ->set('email', 'john@doe.com')
        ->set('password', 'password')
        ->set('password_confirmation', 'password')
        ->call('submit');

    Event::assertDispatched(Registered::class);
});
