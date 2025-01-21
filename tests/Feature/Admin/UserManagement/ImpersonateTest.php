<?php

use App\Livewire\Admin\Users\{Impersonate, StopImpersonate};
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, get};
use function PHPUnit\Framework\{assertFalse, assertSame, assertTrue};

it('should add a key impersonate to the session with the given user', function () {
    $admin            = User::factory()->admin()->create();
    $userImpersonated = User::factory()->create();

    actingAs($admin);

    Livewire::test(Impersonate::class)
        ->set('user', $userImpersonated)
        ->set('confirmedImpersonation', true)
        ->call('impersonate')
        ->assertRedirect(route('dashboard'));

    assertTrue(session()->has('impersonate'));
    assertTrue(session()->has('impersonator'));

    assertSame(session()->get('impersonate'), $userImpersonated->id);
    assertSame(session()->get('impersonator'), $admin->id);
});

it('should make sure that we are logged with the impersonated user', function () {
    $admin            = User::factory()->admin()->create();
    $userImpersonated = User::factory()->create();

    actingAs($admin);

    expect(auth()->id())->toBe($admin->id);

    Livewire::test(Impersonate::class)
        ->set('user', $userImpersonated)
        ->set('confirmedImpersonation', true)
        ->call('impersonate')
        ->assertRedirect(route('dashboard'));

    get(route('dashboard'))
        ->assertSee(__("You're impersonating :name, click here to stop the impersonation.", ['name' => $userImpersonated->name]));

    expect(auth()->id())->toBe($userImpersonated->id);
});

it('should have a confirmation before impersonate an user', function () {
    $admin            = User::factory()->admin()->create();
    $userImpersonated = User::factory()->create();

    actingAs($admin);

    Livewire::test(Impersonate::class)
        ->set('user', $userImpersonated)
        ->set('confirmedImpersonation', false)
        ->call('impersonate')
        ->assertHasErrors(['confirmedImpersonation' => 'accepted']);
});

it('should be able to stop the impersonation', function () {
    $admin            = User::factory()->admin()->create();
    $userImpersonated = User::factory()->create();

    actingAs($admin);

    expect(auth()->id())->toBe($admin->id);

    Livewire::test(Impersonate::class)
        ->set('user', $userImpersonated)
        ->set('confirmedImpersonation', true)
        ->call('impersonate')
        ->assertRedirect(route('dashboard'));

    Livewire::test(StopImpersonate::class)
        ->call('stop')
        ->assertRedirect(route('admin.users'));

    get(route('dashboard'))
        ->assertDontSee(__("You're impersonating :name, click here to stop the impersonation.", ['name' => $userImpersonated->name]));

    assertFalse(session()->has('impersonate'));

    expect(auth()->id())->toBe($admin->id);
});

it('should have the correct permission to impersonate someone', function () {
    $admin            = User::factory()->admin()->create();
    $nonUser          = User::factory()->create();
    $userImpersonated = User::factory()->create();

    actingAs($nonUser);

    Livewire::test(Impersonate::class)
        ->set('user', $userImpersonated)
        ->set('confirmedImpersonation', true)
        ->call('impersonate')
        ->assertForbidden();

    actingAs($admin);

    Livewire::test(Impersonate::class)
        ->set('user', $userImpersonated)
        ->set('confirmedImpersonation', false)
        ->call('impersonate')
        ->assertHasErrors(['confirmedImpersonation' => 'accepted']);
});

it('should not possible impersonate myself', function () {
    $admin = User::factory()->admin()->create();

    actingAs($admin);

    Livewire::test(Impersonate::class)
        ->set('user', $admin)
        ->set('confirmedImpersonation', false)
        ->call('impersonate');
})->throws(Exception::class);
