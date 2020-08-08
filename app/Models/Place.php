<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Place extends Model
{
    use SoftDeletes;
    protected $table = 'places';
    protected $guarded = [];

    public function temperatures()
    {
        return $this->hasMany(Temperature::class, 'id_place');
    }

}
