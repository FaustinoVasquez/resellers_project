<?php
namespace App\Repositories;
use App\Models\Order;
use Illuminate\Support\Facades\DB;


class BillingCenterRepository extends BaseRepository
{
    public function getModel()
    {
        return new Order();
    }

    protected function selectOrders(){

        $select = Order::select('OrderNumber',
            'SourceOrderID as Reference',
            'OrderStatus',
            'PONumber',
            'ShipName',
             DB::raw('CAST(OrderDate as date) AS OrderDate'),
            'GrandTotal as OrderTotal',
             DB::raw('[OrderManager].[dbo].fn_Sum_Amount(OrderNumber) as PaidAmount'),
            'BalanceDue',
             DB::raw('DATEDIFF( DAY, OrderDate, GETDATE() ) as DaysOld'),
            'OrderInst');
        return $select;
    }

    protected function selectOrdersExcel(){
        $select = Order::select('OrderNumber',
            DB::raw('Inventory.dbo.fn_GetTrackingNumbers(OrderNumber) as Tracking'),
            'SourceOrderID as Reference',
            'OrderStatus',
            'PONumber',
            'ShipName',
            DB::raw('CAST(OrderDate as date) AS OrderDate'),
            'GrandTotal as OrderTotal',
            DB::raw('[OrderManager].[dbo].fn_Sum_Amount(OrderNumber) as PaidAmount'),
            'BalanceDue',
            DB::raw('DATEDIFF( DAY, OrderDate, GETDATE() ) as DaysOld'),
            'OrderInst');
        return $select;
    }

    public function allOrders($search,$status,$dateFrom,$dateTo,$fields,$excel){
        if($excel){
            $query = $this->selectOrdersExcel();
        }else{
            $query = $this->selectOrders();
        }

       $query = $this->getDateFromTo($query,$dateFrom,$dateTo);
       $query = $this->getIfStatus($query,$status);
       $query = $this->getCustomerCartID($query)
           ->whereraw($this->concatAllWerefields($search, $fields));
        return $query;
    }


    public function getIfStatus($query,$status)
    {
        switch ($status) {
            case 1:
                return $query->where('BalanceDue', 0);
                break;
            case 2:
                return $query->where('BalanceDue', '>', 0);
                break;
            case 3:
                return $query->where('BalanceDue','<', 0);
                break;
        }
        return $query;
    }

}