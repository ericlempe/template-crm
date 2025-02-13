<?php

namespace App\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class DataSort
{
    public function __construct(
        protected readonly array|Collection $data,
        protected readonly string $table,
        protected readonly string $field
    ) {
    }

    public function handle(): void
    {
        $data   = is_array($this->data) ? collect($this->data) : $this->data;
        $orders = collect($data)->pluck($this->field)->join(',');
        DB::table($this->table)->update(['sort_order' => DB::raw("FIELD(id, $orders)")]);
    }
}
