<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValidGmailId extends Model
{
    protected $guarded = ['id'];
    protected $fillable = ['email', 'status','gmail_connection_id']; 
    protected $table = 'gmail_valid_email'; 
}
