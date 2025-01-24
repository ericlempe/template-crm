<?php

use App\Livewire\Customers\Index;
use App\Models\{Customer, User};
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, get};

it("should be able to access the route customers", function () {
    actingAs(User::factory()->create());

    get(route('customers'))->assertOk();
});

it("let's create a livewire component to list all customers in the page", function () {
    actingAs(User::factory()->create());

    $customers = Customer::factory()->count(10)->create();

    $lw = Livewire::test(Index::class);

    $lw->assertSet('customers', function ($customers) {
        expect($customers)
            ->toBeInstanceOf(LengthAwarePaginator::class)
            ->toHaveCount(10);

        return true;
    });

    foreach ($customers as $customer) {
        $lw->assertSee($customer->name);
    }
});

test('check the table headers', function () {
    actingAs(User::factory()->create());

    Livewire::test(Index::class)
        ->assertSet('headers', [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'phone', 'label' => 'Phone'],
            ['key' => 'created_at', 'label' => 'Created at'],
        ]);
});

it('should be able to filter by name and email', function () {
    actingAs(User::factory()->create());
    Customer::factory()->create(['name' => 'Search Guy', 'email' => 'search-guy@email.com']);
    Customer::factory()->create(['name' => 'Another Guy', 'email' => 'another-guy@email.com']);

    Livewire::test(Index::class)
        ->assertSet('customers', function ($customers) {
            expect($customers)->toHaveCount(2);

            return true;
        })
        ->set('search', 'Search Guy')
        ->assertSet('customers', function ($customers) {
            expect($customers)
                ->toHaveCount(1)
                ->first()->name->toBe('Search Guy');

            return true;
        })
        ->set('search', 'search-guy@email.com')
        ->assertSet('customers', function ($customers) {
            expect($customers)
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
        ->assertSet('customers', function ($customers) {
            expect($customers)->toHaveCount(2);

            return true;
        })

        ->set('sortBy', ['column' => 'name', 'direction' => 'asc'])
        ->assertSet('customers', function ($customers) {
            expect($customers)
                ->first()->name->toBe('A customer')
                ->and($customers)->last()->name->toBe('B customer');

            return true;
        })
        ->set('sortBy', ['column' => 'name', 'direction' => 'desc'])
        ->assertSet('customers', function ($customers) {
            expect($customers)
                ->first()->name->toBe('B customer')
                ->and($customers)->last()->name->toBe('A customer');

            return true;
        });
});

it('should be able to order the list by created at', function () {
    actingAs(User::factory()->create());
    Customer::factory()->create(['name' => 'A customer', 'created_at' => now()->subDay()]);
    Customer::factory()->create(['name' => 'B customer', 'created_at' => now()]);

    Livewire::test(Index::class)
        ->assertSet('customers', function ($customers) {
            expect($customers)->toHaveCount(2);

            return true;
        })

        ->set('sortBy', ['column' => 'created_at', 'direction' => 'asc'])
        ->assertSet('customers', function ($customers) {
            expect($customers)
                ->first()->name->toBe('A customer')
                ->and($customers)->last()->name->toBe('B customer');

            return true;
        })
        ->set('sortBy', ['column' => 'created_at', 'direction' => 'desc'])
        ->assertSet('customers', function ($customers) {
            expect($customers)
                ->first()->name->toBe('B customer')
                ->and($customers)->last()->name->toBe('A customer');

            return true;
        });
});

it('should be able to paginate the list', function () {
    actingAs(User::factory()->create());
    Customer::factory()->count(30)->create();

    Livewire::test(Index::class)
        ->assertSet('customers', function (LengthAwarePaginator $customers) {
            expect($customers)->toHaveCount(15);

            return true;
        })
        ->set('perPage', 10)
        ->assertSet('customers', function (LengthAwarePaginator $customers) {
            expect($customers)->toHaveCount(10);

            return true;
        });
});
