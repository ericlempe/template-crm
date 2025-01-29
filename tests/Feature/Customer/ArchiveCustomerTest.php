<?php

use App\Livewire\Customers\Archive;
use App\Models\{Customer, User};
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, assertNotSoftDeleted, assertSoftDeleted};

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('should be able to archive a customer', function () {
    $customer = Customer::factory()->create();

    Livewire::test(Archive::class)
        ->set('customer', $customer)
        ->set('confirmedArchiving', true)
        ->call('archive')
        ->assertDispatched('customer::archived');

    assertSoftDeleted('customers', [
        'id' => $customer->id,
    ]);
});

it('should have a confirmation before deletion', function () {
    $customer = Customer::factory()->create();

    Livewire::test(Archive::class)
        ->set('customer', $customer)
        ->call('archive')
        ->assertHasErrors(['confirmedArchiving' => 'accepted']);

    assertNotSoftDeleted('customers', [
        'id' => $customer->id,
    ]);
});
