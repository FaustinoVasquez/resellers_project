<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResellerPortalSKUs extends Model
{
    protected $connection = 'miTech';
    protected $table = 'mi.vw_ResellerPortalSKUs';
    protected $primaryKey = 'rsp.MISKU';
    public $timestamps = false;
    protected $guarded = [];

}
