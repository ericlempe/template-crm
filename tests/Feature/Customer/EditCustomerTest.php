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
        ->call('load', $this->customer->id)
        ->set('form.name', 'John Doe')
        ->assertPropertyWired('form.name')
        ->set('form.email', 'john.doe@email.com')
        ->assertPropertyWired('form.email')
        ->set('form.phone', '1234567890')
        ->assertPropertyWired('form.phone')
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
            ->call('load', $this->customer->id)
            ->set('form.name', 'John Doe')
            ->set('form.email', 'invalid-email')
            ->set('form.phone', '1234567890')
            ->call('save')
            ->assertHasErrors(['form.email' => 'email']);

        Livewire::test(Update::class)
            ->call('load', $this->customer->id)
            ->set('form.name', 'John Doe')
            ->set('form.email', 'johndoe@example.com')
            ->set('form.phone', '1234567890')
            ->call('save')
            ->assertHasNoErrors(['form.email' => 'email']);
    });

    it('should be able to check if the email already exists', function () {
        $anotherCustomer = Customer::factory()->create();

        Livewire::test(Update::class)
            ->call('load', $this->customer->id)
            ->set('form.name', 'John Doe')
            ->set('form.email', $anotherCustomer->email)
            ->set('form.phone', '1234567890')
            ->call('save')
            ->assertHasErrors(['form.email' => 'unique']);
    });

    it('should be able to update the same email', function () {
        Livewire::test(Update::class)
            ->call('load', $this->customer->id)
            ->set('form.name', $this->customer->name)
            ->set('form.email', $this->customer->email)
            ->set('form.phone', $this->customer->phone)
            ->call('save')
            ->assertHasNoErrors(['form.email' => 'unique']);
    });

    test('required fields', function ($field) {
        Livewire::test(Update::class)
            ->call('load', $this->customer->id)
            ->set($field, '')
            ->call('save')
            ->assertHasErrors([$field => 'required']);
    })->with(['form.name', 'form.email']);

    test('rules name field', function ($name, $rule) {
        Livewire::test(Update::class)
            ->call('load', $this->customer->id)
            ->set('form.name', $name)
            ->set('form.email', 'johndoe@example.com')
            ->call('save')
            ->assertHasErrors([
                'form.name' => $rule,
            ]);
    })->with([
        'min validation' => ['Jo', 'min:3'],
        'max validation' => [str_repeat('a', 256), 'max:255'],
    ]);
});
