<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvalidGmailId extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $fillable = ['email', 'status','gmail_connection_id']; 
    protected $table = 'gmail_invalid_email'; 
}
