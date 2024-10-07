<?php
namespace App\Repositories;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;


class EmailRepository extends BaseRepository
{
    public function getModel()
    {
        return new Order();
    }

    public function getSummary($omOrder){
        $summary =  $this->newQuery()->select(
            'OrderNumber',
            'Shipping',
            DB::raw('CONVERT(char(10), OrderDate, 111) as orderDate'))
            ->where('OrderNumber',$omOrder)->first()->toArray();
        return $summary;
    }

    public function getShipData($omOrder){
        $shipData =  $this->newQuery()->select(
            'ShipName', 'ShipAddress', 'ShipCity','ShipState','ShipZip','ShipCountry','ShipEmail','ShipPhone','ShipCompany')
            ->where('OrderNumber',$omOrder)->first()->toarray();
            //->where('CustomerID',auth()->user()->customerid)

        return $shipData;
    }

    public function getProductsData($omOrder){
        $productsData =  OrderDetail::select(
            DB::raw('CASE  WHEN WebSKU IS NOT NULL THEN WebSKU ELSE SKU END AS SKU'),
            'Product',
            'PricePerUnit',
            'QuantityOrdered',
            'Status',
            DB::raw('(PricePerUnit * QuantityOrdered) as extPrice'))
            ->where('OrderNumber',$omOrder)->get()->toarray();
        return $productsData;
    }

    public function getProductsTotal($omOrder){
        $productsTotal =  OrderDetail::select(
            DB::raw('SUM (PricePerUnit * QuantityOrdered) as totalOrder'))
            ->where('OrderNumber',$omOrder)->first()->toarray();
        return $productsTotal;
    }

}
