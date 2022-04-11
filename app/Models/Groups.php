<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use App\Models\GmailConnection;

class Groups extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = ['name', 'accounts','selected_group','no_of_groups','gmails'];
    protected $table = 'groups';

    public function emails(): BelongsToMany
    {
        return $this->belongsToMany(Email::class, 'group_id')
            ->withPivot([
                'id'
            ])
            ->using(Groups::class);
    }

    public function listinggroups(): BelongsToMany
    {
        return $this->belongsToMany(GmailConnection::class);
    }

}
