<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLoginDetails extends Model
{
    use HasFactory;
    protected $table = 'user_login_details';
    protected $fillable = [
        'email',
        'ip_address',
        'timezone',
        'type',
    ];
}
