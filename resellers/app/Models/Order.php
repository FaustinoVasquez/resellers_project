<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $connection = 'orderManager';
    protected $primaryKey = "OrderNumber";
    public $timestamps = false;

    public function details(){
      return $this->hasMany('App\Models\OrderDetail','OrderNumber');
    }

    public function getGrandTotalAttribute($number)
    {
        return number_format((float)$number, 2, '.', '');
    }

    public function getBalanceDueAttribute($number)
    {
        return number_format((float)$number, 2, '.', '');
    }

    public function getCoupontDiscountAttribute($number)
    {
        return number_format((float)$number, 2, '.', '');
    }

    public function getOrderDateAttribute($date)
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date);
    }

    public function detailsCountRelation(){
      return $this->hasOne('App\Models\OrderDetail','OrderNumber')->selectRaw('OrderNumber,COUNT(*) as count')->groupBy('OrderNumber');
    }

    public function getdetailsCountAttribute(){
      return $this->detailsCountRelation?$this->detailsCountRelation->count:0;
    }

    public function getShipNameAttribute($value)
     {
         return mb_convert_case($value, MB_CASE_TITLE, "UTF-8");
     }
}
