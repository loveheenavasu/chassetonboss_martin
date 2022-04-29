<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Listing extends Model
{
    use HasFactory;

    public $guarded = ['id'];

    public function emails(): BelongsToMany
    {
        return $this->belongsToMany(Email::class, 'listing_email')
            ->withPivot([
                'in_pool'
            ])
            ->using(ListingEmail::class);
    }

    public function rules(): BelongsToMany
    {
        return $this->belongsToMany(Rule::class);
    }

    public function allemails(): BelongsToMany
    {
        return $this->belongsToMany(Email::class, 'listing_email')
            ->withPivot([
                'in_pool'
            ])
            ->where('in_pool',1)
            ->using(ListingEmail::class);
    }

    public function copiedValue()
    {
        $this->load('allemails');
        return $this->allemails->pluck('email')->implode(PHP_EOL);
    }


    public function allemailsnotinpool(): BelongsToMany
    {
        return $this->belongsToMany(Email::class, 'listing_email')
            ->withPivot([
                'in_pool'
            ])
            ->where('in_pool',0)
            ->using(ListingEmail::class);
    }

    public function copiedValueInvalue()
    {
        $this->load('allemailsnotinpool');
        return $this->allemailsnotinpool->pluck('email')->implode(PHP_EOL);
    }
}
