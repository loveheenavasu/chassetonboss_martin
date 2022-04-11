<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectEmail extends Model
{
    use HasFactory;
    protected $fillable = ['email'];
    protected $table = 'project_emails';
}
