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
}
