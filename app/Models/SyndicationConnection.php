<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SyndicationConnection extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'was_deployed' => 'boolean'
    ];

    public function getFullUrlAttribute(): string
    {
        if (! $this->syndication || ! $this->getAttribute('connection')) {
            return '';
        }

        return trim($this->getAttribute('connection')->base_url, '/') . '/' . $this->syndication->slug;
    }

    public function syndication(): BelongsTo
    {
        return $this->belongsTo(Syndication::class);
    }

    public function connection(): BelongsTo
    {
        return $this->belongsTo(Connection::class);
    }
}
