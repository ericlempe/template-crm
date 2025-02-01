<?php

use App\Livewire\Customers\Create;
use App\Models\{Customer, User};
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, assertDatabaseHas};

beforeEach(function () {
    $user = User::factory()->create();
    actingAs($user);
});

it('should be able to create a customer', function () {
    Livewire::test(Create::class)
        ->set('name', 'John Doe')
        ->assertPropertyWired('name')
        ->set('email', 'john.doe@email.com')
        ->assertPropertyWired('email')
        ->set('phone', '1234567890')
        ->assertPropertyWired('phone')
        ->call('save')
        ->assertMethodWiredToForm('save')
        ->assertHasNoErrors()
        ->assertDispatched('customer::reload');

    assertDatabaseHas('customers', [
        'name'  => 'John Doe',
        'email' => 'john.doe@email.com',
        'phone' => '1234567890',
        'type'  => 'customer',
    ]);
});

describe('validations', function () {
    it('should be able to check if the email is valid', function () {
        Livewire::test(Create::class)
            ->set('name', 'John Doe')
            ->set('email', 'invalid-email')
            ->call('save')
            ->assertHasErrors(['email' => 'email']);

        Livewire::test(Create::class)
            ->set('name', 'John Doe')
            ->set('email', 'johndoe@example.com')
            ->call('save')
            ->assertHasNoErrors(['email' => 'email']);
    });

    it('should be able to check if the email already exists', function () {
        Customer::factory()->create(['email' => 'johndoe@example.com']);

        Livewire::test(Create::class)
            ->set('name', 'John doe')
            ->set('email', 'johndoe@example.com')
            ->call('save')
            ->assertHasErrors([
                'email' => 'unique',
            ]);
    });

    test('required fields', function ($field) {
        Livewire::test(Create::class)
            ->set($field, '')
            ->call('save')
            ->assertHasErrors([$field => 'required']);
    })->with(['name', 'email']);

    test('rules name field', function ($name, $rule) {
        Livewire::test(Create::class)
            ->set('name', $name)
            ->set('email', 'johndoe@example.com')
            ->call('save')
            ->assertHasErrors([
                'name' => $rule,
            ]);
    })->with([
        'min validation' => ['Jo', 'min:3'],
        'max validation' => [str_repeat('a', 256), 'max:255'],
    ]);
});
