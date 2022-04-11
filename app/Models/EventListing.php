<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EventListing extends Model
{
    use HasFactory;
    protected $table= 'eventlistings';

    public $guarded = ['id'];

    public function emails(): BelongsToMany
    {
        return $this->belongsToMany(EventEmail::class, 'eventlisting_emails')
            ->withPivot([
                'in_pool'
            ])
            ->using(EventListingEmail::class);
    }

}

