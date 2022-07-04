<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use App\Models\TokenValue;

class LandingPage extends Model
{
    use HasFactory;
    protected $fillable = ['connection_id','landing_template_id','slug','product','affiliate_link','name','custom_code'];
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

}
