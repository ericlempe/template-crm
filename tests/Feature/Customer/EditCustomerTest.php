<?php

use App\Livewire\Customers\Update;
use App\Models\{Customer, User};
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, assertDatabaseHas};

beforeEach(function () {
    actingAs(User::factory()->create());
    $this->customer = Customer::factory()->create();
});

it('should be able to update a customer', function () {
    Livewire::test(Update::class)
        ->set('customer', $this->customer)
        ->set('customer.name', 'John Doe')
        ->assertPropertyWired('customer.name')
        ->set('customer.email', 'john.doe@email.com')
        ->assertPropertyWired('customer.email')
        ->set('customer.phone', '1234567890')
        ->assertPropertyWired('customer.phone')
        ->call('save')
        ->assertMethodWiredToForm('save')
        ->assertHasNoErrors()
        ->assertDispatched('customer::reload');

    assertDatabaseHas('customers', [
        'id'    => $this->customer->id,
        'name'  => 'John Doe',
        'email' => 'john.doe@email.com',
        'phone' => '1234567890',
        'type'  => 'customer',
    ]);
});

describe('validations', function () {
    it('should be able to check if the email is valid', function () {
        Livewire::test(Update::class)
            ->set('customer', $this->customer)
            ->set('customer.name', 'John Doe')
            ->set('customer.email', 'invalid-email')
            ->set('customer.phone', '1234567890')
            ->call('save')
            ->assertHasErrors(['customer.email' => 'email']);

        Livewire::test(Update::class)
            ->set('customer', $this->customer)
            ->set('customer.name', 'John Doe')
            ->set('customer.email', 'johndoe@example.com')
            ->set('customer.phone', '1234567890')
            ->call('save')
            ->assertHasNoErrors(['customer.email' => 'email']);
    });

    it('should be able to check if the email already exists', function () {
        $anotherCustomer = Customer::factory()->create();

        Livewire::test(Update::class)
            ->set('customer', $this->customer)
            ->set('customer.name', 'John Doe')
            ->set('customer.email', $anotherCustomer->email)
            ->set('customer.phone', '1234567890')
            ->call('save')
            ->assertHasErrors(['customer.email' => 'unique']);
    });

    it('should be able to update the same email', function () {
        Livewire::test(Update::class)
            ->set('customer', $this->customer)
            ->set('customer.name', $this->customer->name)
            ->set('customer.email', $this->customer->email)
            ->set('customer.phone', $this->customer->phone)
            ->call('save')
            ->assertHasNoErrors(['customer.email' => 'unique']);
    });

    test('required fields', function ($field) {
        Livewire::test(Update::class)
            ->set('customer', $this->customer)
            ->set($field, '')
            ->call('save')
            ->assertHasErrors([$field => 'required']);
    })->with(['customer.name', 'customer.email']);

    test('rules name field', function ($name, $rule) {
        Livewire::test(Update::class)
            ->set('customer', $this->customer)
            ->set('customer.name', $name)
            ->set('customer.email', 'johndoe@example.com')
            ->call('save')
            ->assertHasErrors([
                'customer.name' => $rule,
            ]);
    })->with([
        'min validation' => ['Jo', 'min:3'],
        'max validation' => [str_repeat('a', 256), 'max:255'],
    ]);
});
