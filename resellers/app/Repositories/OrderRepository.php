<?php
namespace App\Repositories;

use App\Models\Order;
use Illuminate\Support\Facades\DB;


class OrderRepository extends BaseRepository
{
    public function getModel()
    {
        return new Order();
    }

    protected function selectOrders()
    {

        return $this->newQuery()->select(
            'OrderNumber',
            'SourceOrderID',
            'OrderStatus',
            'PONumber',
             DB::raw('CAST(OrderDate as date) AS OrderDate'),
            'Shipping',
            'ShipName',
             DB::raw('[Inventory].[dbo].fn_WebOrder_LabelStatus(SourceOrderID) as LabelStatus'),
             DB::raw('[Inventory].[dbo].fn_WebOrder_CancelRequest(SourceOrderID) as CancelRequest'),
            'OrderInst');
    }

    protected function selectOrdersExcel()
    {

        return $this->newQuery()->select(
            'orders.OrderNumber',
            'orders.SourceOrderID',
            'orders.PONumber',
            DB::raw('CAST(orders.OrderDate as date) AS OrderDate'),
            'orders.OrderStatus',
            'orders.ShipName',
            'orders.Shipping',
            DB::raw('Inventory.dbo.fn_GetTrackingNumbers(orders.OrderNumber) as Tracking'),
            'T.DateAdded'
        );
    }

    public function allOrders($dateFrom, $dateTo, $status, $search, $fields,$excel,$csv)
    {
        if($excel || $csv){
            $query = $this->selectOrdersExcel()
            ->leftJoin('OrderManager.dbo.Tracking as T','T.OrderNum','=','orders.OrderNumber');
        }else{
            $query = $this->selectOrders();
        }
        $query = $this->getDateFromTo($query,$dateFrom,$dateTo);
        $query = $this->getIfStatus($query,$status);
        $query = $this->getCustomerCartID($query)
            ->whereraw($this->concatAllWerefields($search, $fields));
        return $query;
    }

    public function getIfStatus($query,$status){
        switch ($status) {
            case 1:
                $query->where('OrderStatus', 'Shipped');
                break;
            case 2:
                $query->where('OrderStatus', '!=', 'Shipped');;
        }
        return $query;
    }

}
