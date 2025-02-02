<?php

use App\Livewire\Customers\Index;
use App\Models\{Customer, User};
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, get};

it('should be able to access the route customers', function () {
    actingAs(User::factory()->create());
    get(route('customers'))->assertOk();
});

it("let's create a livewire component to list all items in the page", function () {
    actingAs(User::factory()->create());

    $items = Customer::factory()->count(10)->create();

    $lw = Livewire::test(Index::class);

    $lw->assertSet('items', function ($items) {
        expect($items)
            ->toBeInstanceOf(LengthAwarePaginator::class)
            ->toHaveCount(10);

        return true;
    });

    foreach ($items as $customer) {
        $lw->assertSee($customer->name);
    }
});

test('check the table format', function () {
    actingAs(User::factory()->create());

    Livewire::test(Index::class)
        ->assertSet('headers', [
            ['key' => 'id', 'label' => '#', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
            ['key' => 'name', 'label' => 'Name', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
            ['key' => 'email', 'label' => 'Email', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
            ['key' => 'phone', 'label' => 'Phone', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
            ['key' => 'created_at', 'label' => 'Created at', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
        ]);
});

it('should be able to filter by name and email', function () {
    actingAs(User::factory()->create());
    Customer::factory()->create(['name' => 'Search Guy', 'email' => 'search-guy@email.com']);
    Customer::factory()->create(['name' => 'Another Guy', 'email' => 'another-guy@email.com']);

    Livewire::test(Index::class)
        ->assertSet('items', function ($items) {
            expect($items)->toHaveCount(2);

            return true;
        })
        ->set('search', 'Search Guy')
        ->assertSet('items', function ($items) {
            expect($items)
                ->toHaveCount(1)
                ->first()->name->toBe('Search Guy');

            return true;
        })
        ->set('search', 'search-guy@email.com')
        ->assertSet('items', function ($items) {
            expect($items)
                ->toHaveCount(1)
                ->first()->email->toBe('search-guy@email.com');

            return true;
        });
});

it('should be able to order the list by name', function () {
    actingAs(User::factory()->create());
    Customer::factory()->create(['name' => 'A customer', 'email' => 'a-customer@email.com']);
    Customer::factory()->create(['name' => 'B customer', 'email' => 'b-customer@email.com']);

    Livewire::test(Index::class)
        ->assertSet('items', function ($items) {
            expect($items)->toHaveCount(2);

            return true;
        })
        ->set('sortDirection', 'asc')
        ->set('sortColumnBy', 'name')
        ->assertSet('items', function ($items) {
            expect($items)
                ->first()->name->toBe('A customer')
                ->and($items)->last()->name->toBe('B customer');

            return true;
        })
        ->set('sortDirection', 'desc')
        ->set('sortColumnBy', 'name')
        ->assertSet('items', function ($items) {
            expect($items)
                ->first()->name->toBe('B customer')
                ->and($items)->last()->name->toBe('A customer');

            return true;
        });
});

it('should be able to order the list by created at', function () {
    actingAs(User::factory()->create());
    Customer::factory()->create(['name' => 'A customer', 'created_at' => now()->subDay()]);
    Customer::factory()->create(['name' => 'B customer', 'created_at' => now()]);

    Livewire::test(Index::class)
        ->assertSet('items', function ($items) {
            expect($items)->toHaveCount(2);

            return true;
        })
        ->set('sortDirection', 'asc')
        ->set('sortColumnBy', 'created_at')
        ->assertSet('items', function ($items) {
            expect($items)
                ->first()->name->toBe('A customer')
                ->and($items)->last()->name->toBe('B customer');

            return true;
        })
        ->set('sortDirection', 'desc')
        ->set('sortColumnBy', 'created_at')
        ->assertSet('items', function ($items) {
            expect($items)
                ->first()->name->toBe('B customer')
                ->and($items)->last()->name->toBe('A customer');

            return true;
        });
});

it('should be able to paginate the list', function () {
    actingAs(User::factory()->create());
    Customer::factory()->count(30)->create();

    Livewire::test(Index::class)
        ->assertSet('items', function (LengthAwarePaginator $items) {
            expect($items)->toHaveCount(15);

            return true;
        })
        ->set('perPage', 10)
        ->assertSet('items', function (LengthAwarePaginator $items) {
            expect($items)->toHaveCount(10);

            return true;
        });
});

it('should list archived items', function () {
    actingAs(User::factory()->create());

    $customer = Customer::factory()->count(2)->create();
    $archived = Customer::factory()->deleted()->create();

    Livewire::test(Index::class)
        ->set('show_archived', false)
        ->assertSet('items', function (LengthAwarePaginator $items) use ($archived) {
            expect($items->items())->toHaveCount(2)
                ->and(collect($items->items()))->filter(fn (Customer $customer) => $customer->id === $archived->id)->toBeEmpty();

            return true;
        })
        ->set('show_archived', true)
        ->assertSet('items', function (LengthAwarePaginator $items) use ($archived) {
            expect($items->items())->toHaveCount(1)
                ->and(collect($items->items()))->filter(fn (Customer $customer) => $customer->id === $archived->id)->not->toBeEmpty();

            return true;
        });
});

test('check if all actions components  is in the page', function () {
    Livewire::test(Index::class)
        ->assertContainsLivewireComponent('customers.create')
        ->assertContainsLivewireComponent('customers.update')
        ->assertContainsLivewireComponent('customers.archive')
        ->assertContainsLivewireComponent('customers.restore');
});
