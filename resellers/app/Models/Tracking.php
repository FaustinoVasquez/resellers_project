<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    protected $table = 'tracking';
    protected $connection = 'orderManager';
    protected $dateFormat = "Y-m-d H:i:s";
    protected $casts = [
      "DateAdded" => "datetime"
    ];
}
