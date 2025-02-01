<?php

use App\Livewire\Customers\Restore;
use App\Models\{Customer, User};
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, assertNotSoftDeleted};

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('should be able to restore a customer', function () {
    $customer = Customer::factory()->create();

    Livewire::test(Restore::class)
        ->set('customer', $customer)
        ->set('confirmed', true)
        ->call('restore')
        ->assertDispatched('customer::restored');

    assertNotSoftDeleted('customers', [
        'id' => $customer->id,
    ]);
});

it('should have a confirmation before restoration', function () {
    $customer = Customer::factory()->create();

    Livewire::test(Restore::class)
        ->set('customer', $customer)
        ->set('confirmed', false)
        ->call('restore')
        ->assertHasErrors(['confirmed' => 'accepted']);

    assertNotSoftDeleted('customers', [
        'id' => $customer->id,
    ]);
});
