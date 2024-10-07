<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCatalog extends Model
{
    protected $table = 'ProductCatalog';
    protected $primaryKey = "ID";

    public function orderDetail(){
      return $this->hasMany('App\Models\OrderDetail','SKU','ID');
    }
}
