<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookCron extends Model
{
    use HasFactory;
     protected $guarded = ['id'];
     protected $fillable = ['cron_time'];
     protected $table = 'webhook_crons';
     
}
