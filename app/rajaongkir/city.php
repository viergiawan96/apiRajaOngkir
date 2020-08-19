<?php

namespace App\rajaongkir;

use Illuminate\Database\Eloquent\Model;

class city extends Model
{
    protected $guarded =[];

    protected $hidden = ['created_at','updated_at'];

    public function province()
    {
        return $this->belongsTo(province::class,'province_id','province_id');
    }
}
