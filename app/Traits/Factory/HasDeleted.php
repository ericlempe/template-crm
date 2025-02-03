<?php

namespace App\Traits\Factory;

trait HasDeleted
{
    public function deleted($deleted_by = null): static
    {
        return $this->state(fn (array $attributes) => [
            'deleted_at' => now(),
        ]);
    }
}
