<?php

use App\Livewire\Opportunities\Create;
use App\Models\{Customer, User};
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, assertDatabaseHas};

beforeEach(function () {
    $user = User::factory()->create();
    actingAs($user);
    $this->customer = Customer::factory()->create();
});

it('should be able to create a oppotunity', function () {
    Livewire::test(Create::class)
        ->set('form.customer_id', $this->customer->id)
        ->assertPropertyWired('form.customer_id')
        ->set('form.title', 'Opportunity Title')
        ->set('form.status', 'open')
        ->assertPropertyWired('form.status')
        ->set('form.amount', '1230.45')
        ->assertPropertyWired('form.amount')
        ->call('save')
        ->assertMethodWiredToForm('save')
        ->assertHasNoErrors()
        ->assertDispatched('opportunity::reload');

    assertDatabaseHas('opportunities', [
        'customer_id' => $this->customer->id,
        'title'       => 'Opportunity Title',
        'status'      => 'open',
        'amount'      => '123045',
    ]);
});

describe('validations', function () {
    test('required fields', function ($field) {
        Livewire::test(Create::class)
            ->set($field, '')
            ->call('save')
            ->assertHasErrors([$field => 'required']);
    })->with(['form.customer_id', 'form.title', 'form.status', 'form.amount']);

    test('rules customer_id field', function ($rule) {
        Livewire::test(Create::class)
            ->set('form.customer_id', 99999)
            ->call('save')
            ->assertHasErrors([
                'form.customer_id' => $rule,
            ]);
    })->with([
        'exists' => ['exists:customers,id'],
    ]);

    test('rules title field', function ($title, $rule) {
        Livewire::test(Create::class)
            ->set('form.title', $title)
            ->call('save')
            ->assertHasErrors([
                'form.title' => $rule,
            ]);
    })->with([
        'min validation' => ['Jo', 'min:3'],
        'max validation' => [str_repeat('a', 256), 'max:255'],
    ]);

    test('rules status field', function ($status, $rule) {
        Livewire::test(Create::class)
            ->set('form.status', $status)
            ->call('save')
            ->assertHasErrors([
                'form.status' => $rule,
            ]);
    })->with([
        'in' => ['invalid-status', 'in'],
    ]);
});
