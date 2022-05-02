<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmailFilter extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'gmail_filters';
    protected $fillable = ['name','group_name','token_check'];
}
