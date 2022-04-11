<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmailConnection extends Model
{
    use HasFactory;
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = ['email_id','password','alternatemailid','alternatepassword'];
    protected $table = 'gmail_connections';
}
