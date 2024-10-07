<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectorData extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $connection = 'mitDb';
    protected $table = 'ProjectorData';
}
