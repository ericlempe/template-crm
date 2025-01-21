<?php

use App\Livewire\Dev\Login as DevLogin;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, assertAuthenticatedAs, get};

it('should be able to list all users of the system', function () {
    User::factory()->count(10)->create();

    $users = User::all();

    Livewire::test(DevLogin::class)
        ->assertSet('users', $users)
        ->assertSee($users->first()->name);
});

it('should be able to login with any user', function () {
    $user = User::factory()->create();

    Livewire::test(DevLogin::class)
        ->set('selectedUser', $user->id)
        ->call('login')
        ->assertRedirect(route('dashboard'));

    assertAuthenticatedAs($user);
});

it('should not load the livewire component on production enviroment', function () {
    $user = User::factory()->create();

    app()->detectEnvironment(fn () => 'production');

    actingAs($user);

    get(route('dashboard'))->assertDontSeeLivewire(DevLogin::class);
});
