<?php

use App\Livewire\Admin\Users\Restore;
use App\Models\User;
use App\Notifications\UserRestoredNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, assertNotSoftDeleted, assertSoftDeleted};

it('should be able to restore a deleted user', function () {
    $admin        = User::factory()->admin()->create(['name' => 'Joe Doe', 'email' => 'joe@doe.com']);
    $restoredUser = User::factory()->deleted()->create();

    actingAs($admin);

    Livewire::test(Restore::class)
        ->set('user', $restoredUser)
        ->set('confirmedRestoration', true)
        ->call('restore')
        ->assertDispatched("user::restored");

    assertNotSoftDeleted('users', [
        'id' => $restoredUser->id,
    ]);

    $restoredUser->refresh();

    expect($restoredUser)
        ->restored_at->not->toBeNull()
        ->restored_by->toBe($admin->id)
        ->deleted_by->toBeNull();

});

it('should have a confirmation before restoration', function () {
    $admin        = User::factory()->admin()->create(['name' => 'Joe Doe', 'email' => 'joe@doe.com']);
    $restoredUser = User::factory()->deleted()->create();

    actingAs($admin);

    Livewire::test(Restore::class)
        ->set('user', $restoredUser)
        ->set('confirmedRestoration', false)
        ->call('restore')
        ->assertHasErrors(['confirmedRestoration' => 'accepted']);

    assertSoftDeleted('users', [
        'id' => $restoredUser->id,
    ]);
});

it('should send a notification to the user telling him that he has access to the application again', function () {
    Notification::fake();
    $admin        = User::factory()->admin()->create(['name' => 'Joe Doe', 'email' => 'joe@doe.com']);
    $restoredUser = User::factory()->deleted()->create();

    actingAs($admin);

    Livewire::test(Restore::class)
        ->set('user', $restoredUser)
        ->set('confirmedRestoration', true)
        ->call('restore');

    Notification::assertSentTo($restoredUser, UserRestoredNotification::class);
});
