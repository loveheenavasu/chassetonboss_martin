<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LandingTemplate extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'landing_templates';

    public function scopeByTool(Builder $query, string $tool): Builder
    {
        return $query->where('tool', '=', $tool);
    }

    public function landingpages(): HasMany
    {
        return $this->hasMany(LandingPage::class);
    }
}
