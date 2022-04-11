<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProjectListing extends Model
{
    use HasFactory;
    public $guarded = ['id'];

    public function projectemails(): BelongsToMany
    {
        return $this->belongsToMany(ProjectEmail::class, 'project_listing_emails')
            ->withPivot([
                'in_pool'
            ])
            ->using(ProjectListingEmail::class);
    }
}
