<?php

use App\Livewire\Customers\Show;
use App\Models\{Customer, User};

use Livewire\Livewire;

use function Pest\Laravel\{actingAs, get};

beforeEach(function () {
    actingAs(User::factory()->create());
    $this->customer = Customer::factory()->create();
});

it('should be able to access customer show route', function () {
    get(route('customers.show', $this->customer->id))
        ->assertOk();
});

it('should show all the customer infomration in the page', function () {
    Livewire::test(Show::class, ['customer' => $this->customer])
        ->assertSee($this->customer->name)
        ->assertSee($this->customer->email)
        ->assertSee($this->customer->phone)
        ->assertSee($this->customer->linkedin)
        ->assertSee($this->customer->facebook)
        ->assertSee($this->customer->twitter)
        ->assertSee($this->customer->instagram)
        ->assertSee($this->customer->address)
        ->assertSee($this->customer->city)
        ->assertSee($this->customer->state)
        ->assertSee($this->customer->country)
        ->assertSee($this->customer->zip)
        ->assertSee($this->customer->birthday)
        ->assertSee($this->customer->gender)
        ->assertSee($this->customer->company)
        ->assertSee($this->customer->position);
});
