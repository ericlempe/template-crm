<?php

use App\Livewire\Admin\Users\Index;
use App\Models\{User};
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, get};

it("should be able to access the route customers", function () {
    actingAs(User::factory()->create());

    get(route('customers'))->assertOk();
});

it("let's create a livewire component to list all customers in the page", function () {
    actingAs(User::factory()->admin()->create());

    $customers = Customer::factory()->count(10)->create();


    $lw = Livewire::test(Index::class);

    $lw->assertSet('customers', function ($customers) {
        expect($customers)
            ->toBeInstanceOf(LengthAwarePaginator::class)
            ->toHaveCount(11);

        return true;
    });

    foreach ($customers as $customer) {
        $lw->assertSee($customer->name);
    }
});

test('check the table headers', function () {
    actingAs(User::factory()->admin()->create());

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
    $admin = User::factory()->admin()->create(['name' => 'John Doe', 'email' => 'john@doe.com']);
    Customer::factory()->create(['name' => 'Search Guy', 'email' => 'search-guy@email.com']);

    actingAs($admin);

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

it('should be able to list deleted customers', function () {
    $admin = User::factory()->admin()->create(['name' => 'Joe Doe', 'email' => 'joe@doe.com']);
    Customer::factory()->deleted($admin->id)->count(2)->create();

    actingAs($admin);

    Livewire::test(Index::class)
        ->assertSet('customers', function ($customers) {
            expect($customers)->toHaveCount(1);

            return true;
        })
        ->set('search_trash', 1)
        ->assertSet('customers', function ($customers) {
            expect($customers)
                ->toHaveCount(2);

            return true;
        });
});

it('should be able to order the list by name', function () {
    $admin = User::factory()->admin()->create(['name' => 'A user', 'email' => 'a-user@email.com']);
    User::factory()->create(['name' => 'B user', 'email' => 'b-user@email.com']);

    actingAs($admin);

    Livewire::test(Index::class)
        ->assertSet('customers', function ($customers) {
            expect($customers)->toHaveCount(2);

            return true;
        })

        ->set('sortBy', ['column' => 'name', 'direction' => 'asc'])
        ->assertSet('customers', function ($customers) {
            expect($customers)
                ->first()->name->toBe('A user')
                ->and($customers)->last()->name->toBe('B user');

            return true;
        })
        ->set('sortBy', ['column' => 'name', 'direction' => 'desc'])
        ->assertSet('customers', function ($customers) {
            expect($customers)
                ->first()->name->toBe('B user')
                ->and($customers)->last()->name->toBe('A user');

            return true;
        });
});

it('should be able to order the list by created at', function () {
    $admin = User::factory()->admin()->create(['name' => 'A user', 'created_at' => now()->subDay()]);
    User::factory()->create(['name' => 'B user', 'created_at' => now()]);

    actingAs($admin);

    Livewire::test(Index::class)
        ->assertSet('customers', function ($customers) {
            expect($customers)->toHaveCount(2);

            return true;
        })

        ->set('sortBy', ['column' => 'created_at', 'direction' => 'asc'])
        ->assertSet('customers', function ($customers) {
            expect($customers)
                ->first()->name->toBe('A user')
                ->and($customers)->last()->name->toBe('B user');

            return true;
        })
        ->set('sortBy', ['column' => 'created_at', 'direction' => 'desc'])
        ->assertSet('customers', function ($customers) {
            expect($customers)
                ->first()->name->toBe('B user')
                ->and($customers)->last()->name->toBe('A user');

            return true;
        });
});

it('should be able to paginate the list', function () {
    $admin = User::factory()->admin()->create();
    User::factory()->count(30)->create();

    actingAs($admin);

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
