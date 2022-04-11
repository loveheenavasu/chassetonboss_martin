<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ListingEmail extends Pivot
{
    protected $guarded = [];

    public $casts = [
        'in_pool' => 'boolean'
    ];
}
