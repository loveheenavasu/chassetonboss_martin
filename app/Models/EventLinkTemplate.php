<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventLinkTemplate extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table= 'eventlink_template';
    
}