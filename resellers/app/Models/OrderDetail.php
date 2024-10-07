<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $connection = 'orderManager';
    protected $table = 'Order Details';

    public function order(){
      return $this->belongsTo('App\Models\Order','OrderNumber');
    }

    public function product(){
      $relation = $this->belongsTo('App\Models\ProductCatalog','SKU','IDs');
      $relation->table = "Inventory.dbo.ProductCatalog";
      return $relation;
    }
}
