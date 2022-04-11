<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventListingEmail extends Pivot
{
    use HasFactory;

    public $timestamps = false;
    
    protected $guarded = [];

    protected $table= 'eventlisting_emails';

    public $casts = [
        'in_pool' => 'boolean'
    ];
}
