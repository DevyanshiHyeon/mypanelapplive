<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginRecord extends Model
{
    protected $table = 'login_records';

    protected $fillable = ['ip_address', 'login_count','is_block'];
    use HasFactory;
}
