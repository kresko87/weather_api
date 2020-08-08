<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Temperature extends Model
{
    use SoftDeletes;
    protected $table = 'temperature';
    protected $guarded = [];

}
