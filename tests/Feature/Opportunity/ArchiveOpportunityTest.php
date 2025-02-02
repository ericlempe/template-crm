<?php

use App\Livewire\Opportunities\Archive;
use App\Models\{Opportunity, User};
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, assertSoftDeleted};

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('should be able to archive a opportunity', function () {
    $opportunity = Opportunity::factory()->create();

    Livewire::test(Archive::class)
        ->set('opportunity', $opportunity)
        ->call('archive')
        ->assertDispatched('opportunity::reload');

    assertSoftDeleted('opportunities', [
        'id' => $opportunity->id,
    ]);
});

test('when confirming we should load the opportunity and set modal to true', function () {
    $opportunity = Opportunity::factory()->create();

    Livewire::test(Archive::class)
        ->call('confirmAction', $opportunity->id)
        ->assertSet('opportunity.id', $opportunity->id)
        ->assertSet('modal', true)
        ->assertPropertyEntangled('modal');
});

test('making sure restore method is wired', function () {
    Livewire::test(Archive::class)->assertMethodWired('archive');
});
