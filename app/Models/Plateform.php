<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plateform extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'type',
        'name',
        'logo',
        'imageName',
        'image_type',
        'add_format',
    ];
}
