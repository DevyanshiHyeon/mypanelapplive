<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['plateform_id','category_id','account_id','name','package_name','logo','logo_type','status','is_notified','is_trashed','treshed_at','version','newpackagename'];
}
