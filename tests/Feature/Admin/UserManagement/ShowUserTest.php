<?php

use App\Livewire\Admin\Users\{Show};
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('should be able to show a details of an active user in a component', function () {
    $admin = User::factory()->admin()->create();
    $user  = User::factory()->create();

    actingAs($admin);

    Livewire::test(Show::class)
        ->call('loadUser', $user->id)
        ->set('modal', true)
        ->assertSee($user->name)
        ->assertSee($user->email)
        ->assertSee($user->created_at->format('d/m/Y H:i'))
        ->assertSee($user->updated_at->format('d/m/Y H:i'));
});

it('should be able to show a details of a deleted user in a component', function () {
    $admin       = User::factory()->admin()->create();
    $userDeleted = User::factory()->deleted()->create();

    actingAs($admin);

    Livewire::test(Show::class)
        ->call('loadUser', $userDeleted->id)
        ->set('modal', true)
        ->assertSee($userDeleted->name)
        ->assertSee($userDeleted->email)
        ->assertSee($userDeleted->created_at->format('d/m/Y H:i'))
        ->assertSee($userDeleted->updated_at->format('d/m/Y H:i'))
        ->assertSee($userDeleted->deleted_at->format('d/m/Y H:i'))
        ->assertSee($userDeleted->deletedBy->name);
});

it('should open the modal with the event is dispatched', function () {
    $admin = User::factory()->admin()->create();
    $user  = User::factory()->create();

    actingAs($admin);

    $lw = Livewire::test(Show::class)
        ->set('user', null)
        ->set('modal', false);

    $lw->dispatch('user::show', id: $user->id)
        ->assertSet('modal', true);

    expect($lw->get('user'))
        ->id->toBe($user->id)
        ->name->toBe($user->name);
});

test('making sure that the method loadUser has the attribute On', function () {
    $reflection = new ReflectionClass(new Show());

    $attributes = $reflection->getMethod('loadUser')->getAttributes();

    /** @var ReflectionAttribute $attribute */
    $attribute = $attributes[0];

    expect($attribute)->getName()->toBe('Livewire\Attributes\On')
        ->and($attribute)->getArguments()->toHaveCount(1);

    $argument = $attribute->getArguments()[0];
    expect($argument)->toBe('user::show');
});
