<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResellerPortalOrderFile extends Model
{
    protected $table = "ResellerPortalOrderFile";
    protected $primaryKey = "id";

    public function ResellerPortalOrder(){
      return $this->belongsTo('ResellerPortalOrder',"WebOrderNumber");
    }
}
