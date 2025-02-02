<?php

use App\Livewire\Opportunities\Restore;
use App\Models\{Opportunity, User};
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, assertNotSoftDeleted};

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('should be able to restore a opportunity', function () {
    $opportunity = Opportunity::factory()->create();

    Livewire::test(Restore::class)
        ->set('opportunity', $opportunity)
        ->call('restore')
        ->assertDispatched('opportunity::reload');

    assertNotSoftDeleted('opportunities', [
        'id' => $opportunity->id,
    ]);
});

test('when confirming we should load the opportunity and set modal to true', function () {
    $opportunity = Opportunity::factory()->deleted()->create();

    Livewire::test(Restore::class)
        ->call('confirmAction', $opportunity->id)
        ->assertSet('opportunity.id', $opportunity->id)
        ->assertSet('modal', true)
        ->assertPropertyEntangled('modal');
});

test('after restoring we should dispatch an event to tell the list to reload', function () {
    $opportunity = Opportunity::factory()->deleted()->create();

    Livewire::test(Restore::class)
        ->set('opportunity', $opportunity)
        ->call('restore')
        ->assertDispatched('opportunity::reload');
});

test('after restoring we should close the modal', function () {
    $opportunity = Opportunity::factory()->deleted()->create();

    Livewire::test(Restore::class)
        ->set('opportunity', $opportunity)
        ->call('restore')
        ->assertSet('modal', false);
});

test('making sure restore method is wired', function () {
    Livewire::test(Restore::class)->assertMethodWired('restore');
});
