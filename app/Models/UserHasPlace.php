<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserHasPlace extends Model
{
    use SoftDeletes;
    protected $table = 'user_has_place';
    protected $guarded = [];

    public function place(){
        return $this->belongsTo(Place::class, 'id');
    }

}
