<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventEmailLogs extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $fillable = ['email', 'status','type','event_id','event_name','timezone'];
    protected $table = 'event_emails_logs';
}
