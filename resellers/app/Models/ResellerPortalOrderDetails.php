<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ResellerPortalOrderDetails extends Model
{
    protected $table = "ResellerPortalOrderDetails";
    public $timestamps = false;
    protected $primaryKey = 'Id';

    public function resellerPortalOrder(){
      return $this->belongsTo('App\Models\ResellerPortalOrder','WebOrderNumber','WebOrderNumber');
    }
}
