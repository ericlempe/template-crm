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
        'name'  => 'John Doe',
        'email' => 'john.doe@email.com',
        'phone' => '1234567890',
        'type'  => 'customer',
    ]);
});

describe('validations', function () {
    it('should be able to check if the email is valid', function () {
        Livewire::test(Create::class)
            ->set('form.name', 'John Doe')
            ->set('form.email', 'invalid-email')
            ->call('save')
            ->assertHasErrors(['form.email' => 'email']);

        Livewire::test(Create::class)
            ->set('form.name', 'John Doe')
            ->set('form.email', 'johndoe@example.com')
            ->call('save')
            ->assertHasNoErrors(['form.email' => 'email']);
    });

    it('should be able to check if the email already exists', function () {
        Customer::factory()->create(['email' => 'johndoe@example.com']);

        Livewire::test(Create::class)
            ->set('form.name', 'John doe')
            ->set('form.email', 'johndoe@example.com')
            ->call('save')
            ->assertHasErrors([
                'form.email' => 'unique',
            ]);
    });

    test('required fields', function ($field) {
        Livewire::test(Create::class)
            ->set($field, '')
            ->call('save')
            ->assertHasErrors([$field => 'required']);
    })->with(['form.name', 'form.email']);

    test('rules name field', function ($name, $rule) {
        Livewire::test(Create::class)
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
