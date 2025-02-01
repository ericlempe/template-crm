<?php

use App\Livewire\Customers\Archive;
use App\Models\{Customer, User};
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, assertSoftDeleted};

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('should be able to archive a customer', function () {
    $customer = Customer::factory()->create();

    Livewire::test(Archive::class)
        ->set('customer', $customer)
        ->call('archive')
        ->assertDispatched('customer::reload');

    assertSoftDeleted('customers', [
        'id' => $customer->id,
    ]);
});

test('when confirming we should load the customer and set modal to true', function () {
    $customer = Customer::factory()->create();

    Livewire::test(Archive::class)
        ->call('confirmAction', $customer->id)
        ->assertSet('customer.id', $customer->id)
        ->assertSet('modal', true)
        ->assertPropertyEntangled('modal');
});

test('making sure restore method is wired', function () {
    Livewire::test(Archive::class)->assertMethodWired('archive');
});
