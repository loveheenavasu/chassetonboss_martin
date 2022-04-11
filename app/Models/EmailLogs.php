<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLogs extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $fillable = ['email', 'status','type','rule_number','rule_name','timezone'];
    protected $table = 'emails_logs';
}
