<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mauticlogs extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $fillable = ['email', 'status'];
    protected $table = 'mautic_logs';
}
