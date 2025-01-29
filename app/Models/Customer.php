<?php

namespace App\Models;

use App\Traits\Models\HasSearch;
use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

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
}
