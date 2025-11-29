<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobileModel extends Model
{
    protected $fillable = ['name'];
    protected $table = 'models';  // if table name differs
}
