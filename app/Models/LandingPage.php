<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use App\Models\TokenValue;
use App\Models\LandingPageConnection;

class LandingPage extends Model
{
    use HasFactory;
    protected $fillable = ['landing_template_id','slug','product','affiliate_link','name','custom_code'];
    protected $table = 'landing_page';

    protected $guarded = ['id'];

    public function getFullUrlAttribute(): string
    {
        $connection = $this->getAttribute('connection');
        if (!$connection) {
            return '';
        }

        return rtrim($connection->base_url, '/') . '/' . $this->slug;
    }

    public function getTitleAttribute(): string
    {
        return '';
    }

    public function getContentAttribute(): string
    {
        return $this->landing_template->content;
    }

    public function connection(): BelongsTo
    {
        return $this->belongsTo(Connection::class);
    }

    public function landing_template(): BelongsTo
    {
        return $this->belongsTo(LandingTemplate::class);
    }

    public function landingpageConnections(): HasMany
    {
        return $this->HasMany(LandingPageConnection::class,'landing_page_id');
    }
    public function copiedValue()
    {
        $this->load('landingpageConnections');
        return $this->landingpageConnections->pluck('full_url')->implode(PHP_EOL);
    }

    public function copiedValueSpintax()
    {
        $this->load('landingpageConnections');
        return $this->landingpageConnections->pluck('full_url')->implode('|',PHP_EOL);
    }

}
