<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Syndication extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }

    public function syndicatedConnections(): HasMany
    {
        return $this->hasMany(SyndicationConnection::class);
    }

    public function copiedValue()
    {
        $this->load('syndicatedConnections');
        return $this->syndicatedConnections->pluck('full_url')->implode(PHP_EOL);
    }

    public function copiedValueSpintax()
    {
        $this->load('syndicatedConnections');
        return $this->syndicatedConnections->pluck('full_url')->implode('|',PHP_EOL);
    }
}
