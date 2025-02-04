<?php

use App\Livewire\Opportunities\Update;
use App\Models\{Customer, Opportunity, User};
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, assertDatabaseHas};

beforeEach(function () {
    actingAs(User::factory()->create());
    $this->opportunity = Opportunity::factory()->create();
    $this->customer    = Customer::factory()->create();
});

it('should be able to update a opportunity', function () {
    Livewire::test(Update::class)
        ->call('load', $this->opportunity->id)
        ->set('form.customer_id', $this->customer->id)
        ->set('form.title', 'Opportunity 1')
        ->assertPropertyWired('form.title')
        ->set('form.status', 'open')
        ->assertPropertyWired('form.status')
        ->set('form.amount', '1230.45')
        ->assertPropertyWired('form.amount')
        ->call('save')
        ->assertMethodWiredToForm('save')
        ->assertHasNoErrors()
        ->assertDispatched('opportunity::reload');

    assertDatabaseHas('opportunities', [
        'id'          => $this->opportunity->id,
        'customer_id' => $this->customer->id,
        'title'       => 'Opportunity 1',
        'status'      => 'open',
        'amount'      => '123045',
    ]);
});

describe('validations', function () {
    test('required fields', function ($field) {
        Livewire::test(Update::class)
            ->call('load', $this->opportunity->id)
            ->set($field, '')
            ->call('save')
            ->assertHasErrors([$field => 'required']);
    })->with(['form.customer_id', 'form.title', 'form.status', 'form.amount']);

    test('rules customer_id field', function ($rule) {
        Livewire::test(Update::class)
            ->call('load', $this->opportunity->id)
            ->set('form.customer_id', 99999)
            ->call('save')
            ->assertHasErrors([
                'form.customer_id' => $rule,
            ]);
    })->with([
        'exists' => ['exists:customers,id'],
    ]);

    test('rules title field', function ($title, $rule) {
        Livewire::test(Update::class)
            ->call('load', $this->opportunity->id)
            ->set('form.title', $title)
            ->set('form.status', 'open')
            ->call('save')
            ->assertHasErrors([
                'form.title' => $rule,
            ]);
    })->with([
        'min validation' => ['Jo', 'min:3'],
        'max validation' => [str_repeat('a', 256), 'max:255'],
    ]);

    test('rules status field', function ($status, $rule) {
        Livewire::test(Update::class)
            ->call('load', $this->opportunity->id)
            ->set('form.status', $status)
            ->call('save')
            ->assertHasErrors([
                'form.status' => $rule,
            ]);
    })->with([
        'in' => ['invalid-status', 'in'],
    ]);
});
