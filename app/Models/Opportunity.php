<?php

namespace App\Models;

use App\Traits\Models\HasSearch;
use Database\Factories\OpportunityFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Opportunity extends Model
{
    /** @use HasFactory<OpportunityFactory> */
    use HasFactory;
    use HasSearch;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'status',
        'amount',
    ];
}
