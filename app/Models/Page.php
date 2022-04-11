<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory;

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
        return Str::of($this->template->content)
            ->replace('*NAME*', $this->name)
            ->replace('*PRODUCT*', $this->product);
    }

    public function connection(): BelongsTo
    {
        return $this->belongsTo(Connection::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }
}
