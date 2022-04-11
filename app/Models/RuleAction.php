<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RuleAction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function rule(): BelongsTo
    {
        return $this->belongsTo(Rule::class);
    }
}
