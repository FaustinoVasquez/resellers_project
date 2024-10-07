<?php
namespace App\Repositories;

use App\Models\Tracking;


class TrackingRepository extends BaseRepository
{
    public function getModel()
    {
        return new Tracking();
    }

    function getTrackingInformation($orderNumber){
        $ti = Tracking::select ('DateAdded', 'TrackingID', 'Carrier', 'ShippersMethod', 'Notes')
            ->where('OrderNum',$orderNumber)
            ->where('IsVoid', null )->get();
        return $ti;
    }
}