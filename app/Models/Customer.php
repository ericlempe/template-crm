<?php

namespace App\Models;

use App\Traits\Models\HasSearch;
use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, Relations\HasMany, SoftDeletes};

class Customer extends Model
{
    /** @use HasFactory<CustomerFactory> */
    use HasFactory;
    use HasSearch;
    use SoftDeletes;

    protected $fillable = [
        'type',
        'name',
        'email',
        'phone',
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
