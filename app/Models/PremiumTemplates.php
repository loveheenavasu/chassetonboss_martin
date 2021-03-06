<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PremiumTemplates extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];
    
    public function scopeByTool(Builder $query, string $tool): Builder
    {
        return $query->where('tool', '=', $tool);
    }

    public function premiumpages(): HasMany
    {
        return $this->hasMany(PremiumPages::class);
    }
}
