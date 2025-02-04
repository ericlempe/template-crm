<?php

use App\Livewire\Opportunities\Index;
use App\Models\{Opportunity, User};
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, get};

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('should be able to access the route opportunities', function () {
    get(route('opportunities'))->assertOk();
});

it("let's create a livewire component to list all items in the page", function () {
    $items = Opportunity::factory()->count(10)->create();

    $lw = Livewire::test(Index::class);

    $lw->assertSet('items', function ($items) {
        expect($items)
            ->toBeInstanceOf(LengthAwarePaginator::class)
            ->toHaveCount(10);

        return true;
    });

    foreach ($items as $opportunity) {
        $lw->assertSee($opportunity->title);
    }
});

test('check the table format', function () {
    Livewire::test(Index::class)
        ->assertSet('headers', [
            ['key' => 'id', 'label' => '#', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
            ['key' => 'customer_name', 'label' => 'Customer', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
            ['key' => 'title', 'label' => 'Title', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
            ['key' => 'status', 'label' => 'Status', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
            ['key' => 'amount', 'label' => 'Amount', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
            ['key' => 'created_at', 'label' => 'Created at', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
        ]);
});

it('should be able to filter by title', function () {
    actingAs(User::factory()->create());
    Opportunity::factory()->create(['title' => 'Title one']);
    Opportunity::factory()->create(['title' => 'Title two']);

    Livewire::test(Index::class)
        ->assertSet('items', function ($items) {
            expect($items)->toHaveCount(2);

            return true;
        })
        ->set('search', 'Title one')
        ->assertSet('items', function ($items) {
            expect($items)
                ->toHaveCount(1)
                ->first()->title->toBe('Title one');

            return true;
        });
});

it('should be able to order the list by title', function () {
    Opportunity::factory()->create(['title' => 'A opportunity']);
    Opportunity::factory()->create(['title' => 'B opportunity']);

    Livewire::test(Index::class)
        ->assertSet('items', function ($items) {
            expect($items)->toHaveCount(2);

            return true;
        })
        ->set('sortDirection', 'asc')
        ->set('sortColumnBy', 'title')
        ->assertSet('items', function ($items) {
            expect($items)
                ->first()->title->toBe('A opportunity')
                ->and($items)->last()->title->toBe('B opportunity');

            return true;
        })
        ->set('sortDirection', 'desc')
        ->set('sortColumnBy', 'title')
        ->assertSet('items', function ($items) {
            expect($items)
                ->first()->title->toBe('B opportunity')
                ->and($items)->last()->title->toBe('A opportunity');

            return true;
        });
});

it('should be able to order the list by created at', function () {
    Opportunity::factory()->create(['title' => 'A opportunity', 'created_at' => now()->subDay()]);
    Opportunity::factory()->create(['title' => 'B opportunity', 'created_at' => now()]);

    Livewire::test(Index::class)
        ->assertSet('items', function ($items) {
            expect($items)->toHaveCount(2);

            return true;
        })
        ->set('sortDirection', 'asc')
        ->set('sortColumnBy', 'created_at')
        ->assertSet('items', function ($items) {
            expect($items)
                ->first()->title->toBe('A opportunity')
                ->and($items)->last()->title->toBe('B opportunity');

            return true;
        })
        ->set('sortDirection', 'desc')
        ->set('sortColumnBy', 'created_at')
        ->assertSet('items', function ($items) {
            expect($items)
                ->first()->title->toBe('B opportunity')
                ->and($items)->last()->title->toBe('A opportunity');

            return true;
        });
});

it('should be able to paginate the list', function () {
    Opportunity::factory()->count(30)->create();

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
    $opportunity = Opportunity::factory()->count(2)->create();
    $archived    = Opportunity::factory()->deleted()->create();

    Livewire::test(Index::class)
        ->set('show_archived', false)
        ->assertSet('items', function (LengthAwarePaginator $items) use ($archived) {
            expect($items->items())->toHaveCount(2)
                ->and(collect($items->items()))->filter(fn (Opportunity $opportunity) => $opportunity->id === $archived->id)->toBeEmpty();

            return true;
        })
        ->set('show_archived', true)
        ->assertSet('items', function (LengthAwarePaginator $items) use ($archived) {
            expect($items->items())->toHaveCount(1)
                ->and(collect($items->items()))->filter(fn (Opportunity $opportunity) => $opportunity->id === $archived->id)->not->toBeEmpty();

            return true;
        });
});

test('check if all actions components  is in the page', function () {
    Livewire::test(Index::class)
        ->assertContainsLivewireComponent('opportunities.create')
        ->assertContainsLivewireComponent('opportunities.update')
        ->assertContainsLivewireComponent('opportunities.archive')
        ->assertContainsLivewireComponent('opportunities.restore');
});
