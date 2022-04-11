<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Content extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function syndications(): HasMany
    {
        return $this->hasMany(Syndication::class);
    }
}
