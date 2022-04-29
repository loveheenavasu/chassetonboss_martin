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

    public function allemails(): BelongsToMany
    {
        return $this->belongsToMany(EventEmail::class, 'eventlisting_emails')
            ->withPivot([
                'in_pool'
            ])
            ->where('in_pool',1)
            ->using(EventListingEmail::class);
    }

    public function copiedValue()
    {
        $this->load('allemails');
        return $this->allemails->pluck('email')->implode(PHP_EOL);
    }

    public function allemailsnotinpool(): BelongsToMany
    {
        return $this->belongsToMany(EventEmail::class, 'eventlisting_emails')
            ->withPivot([
                'in_pool'
            ])
            ->where('in_pool',0)
            ->using(EventListingEmail::class);
    }

    public function copiedValueInvalue()
    {
        $this->load('allemailsnotinpool');
        return $this->allemailsnotinpool->pluck('email')->implode(PHP_EOL);
    }

}

