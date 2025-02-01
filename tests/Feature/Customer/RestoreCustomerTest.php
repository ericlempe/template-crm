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
        ->call('restore')
        ->assertDispatched('customer::reload');

    assertNotSoftDeleted('customers', [
        'id' => $customer->id,
    ]);
});

test('when confirming we should load the customer and set modal to true', function () {
    $customer = Customer::factory()->deleted()->create();

    Livewire::test(Restore::class)
        ->call('confirmAction', $customer->id)
        ->assertSet('customer.id', $customer->id)
        ->assertSet('modal', true)
        ->assertPropertyEntangled('modal');
});

test('after restoring we should dispatch an event to tell the list to reload', function () {
    $customer = Customer::factory()->deleted()->create();

    Livewire::test(Restore::class)
        ->set('customer', $customer)
        ->call('restore')
        ->assertDispatched('customer::reload');
});

test('after restoring we should close the modal', function () {
    $customer = Customer::factory()->deleted()->create();

    Livewire::test(Restore::class)
        ->set('customer', $customer)
        ->call('restore')
        ->assertSet('modal', false);
});

test('making sure restore method is wired', function () {
    Livewire::test(Restore::class)->assertMethodWired('restore');
});
