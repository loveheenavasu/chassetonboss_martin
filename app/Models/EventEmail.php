<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventEmail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table= 'eventemails';

    protected $casts = [
        'is_valid' => 'bool'
    ];

    public function infos(): HasMany
    {
        return $this->hasMany(EventEmailInfo::class);
    }
}
