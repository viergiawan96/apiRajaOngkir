<?php

namespace App\rajaongkir;

use Illuminate\Database\Eloquent\Model;

class province extends Model
{
    protected $guarded =[];
    protected $hidden = ['created_at','updated_at'];

    public function city()
    {
        return $this->hasMany(city::class,'province_id');
    }
}
