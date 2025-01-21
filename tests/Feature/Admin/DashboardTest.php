<?php

use App\Livewire\Admin\Dashboard;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, get};

it('renders successfully', function () {
    actingAs(User::factory()->admin()->create());
    Livewire::test(Dashboard::class)
        ->assertStatus(200);
});

it("should block an user without the permission 'be an admin'", function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(Dashboard::class)
        ->assertForbidden();

    get(route('admin.dashboard'))->assertForbidden();
});
