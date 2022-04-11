<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmailConnectionGroup extends Model
{
    use HasFactory;
    protected $table = 'gmail_connection_groups';
    protected $fillable = ['groups_id','gmail_connection_id','event_id','sync_status'];

    
}
