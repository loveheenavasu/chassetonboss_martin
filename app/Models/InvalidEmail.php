<?php

namespace App\Models;

use App\Tools;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class InvalidEmail extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $fillable = ['email', 'status','type','rule_number','rule_name','timezone']; 
    protected $table = 'invalid_emails'; 

}
