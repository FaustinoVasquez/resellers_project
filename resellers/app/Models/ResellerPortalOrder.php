<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResellerPortalOrder extends Model
{
    protected $table = 'ResellerPortalOrder';
    public $timestamps = false;
    protected $primaryKey = "WebOrderNumber";

    public function resellerPortalOrderDetails(){
      return $this->hasMany('App\Models\ResellerPortalOrderDetails','WebOrderNumber','WebOrderNumber');
    }

    public function resellerPortalOrderFile(){
      return $this->hasMany('App\Models\ResellerPortalOrderFile',"WebOrderNumber");
    }

    public function getShipFromAddressLine1Attribute($value)
    {
        return ucwords($value);
    }

    public function getShipFromAddressLine2Attribute($value)
    {
        return ucwords($value);
    }

    public function getShipFromCityAttribute($value)
    {
        return ucwords($value);
    }

    public function getShipFromCompanyAttribute($value)
    {
        return ucwords($value);
    }

    public function getShipToNameAttribute($value)
    {
        return ucwords($value);
    }

    public function getShipToAddressLine1Attribute($value)
    {
        return ucwords($value);
    }

    public function getShipToAddressLine2Attribute($value)
    {
        return ucwords($value);
    }

    public function getShipToCityAttribute($value)
    {
        return ucwords($value);
    }

    public function getShipToEmailAttribute($value)
    {
        return strtolower($value);
    }
}
