<?php
namespace App\Repositories;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Tracking;
use Illuminate\Support\Facades\DB;


class OrderDetailRepository extends BaseRepository
{
    public function getModel()
    {
        return new OrderDetail();
    }


    public function getCustomerIdData()
    {
        $customerIdData = Customer::select('CustomerID',
            'FullName',
            'Address',
            'City',
            'State',
            'Zip',
            'Country',
            'Email',
            'Phone',
            'Company')
            ->where('CustomerID', auth()->user()->customerid)->first()->toarray();

        return $customerIdData;
    }

    public function getShipData($orderNumber)
    {
        $shipData = Order::select('ShipName',
            'ShipAddress',
            'ShipCity',
            'ShipState',
            'ShipZip',
            'ShipCountry',
            'ShipEmail',
            'ShipPhone',
            'ShipCompany')
            ->where('OrderNumber', $orderNumber)->first()->toarray();

        return $shipData;
    }

    public function getProducts($orderNumber){
        $products = OrderDetail::select('SKU',
                                    'Product',
                                    DB::raw("Inventory.dbo.fn_Get_Packing_SerialNumber('$orderNumber',SKU) as SerialNumber"),
                                    'PricePerUnit',
                                    'QuantityOrdered',
                                    'Status',
                                    DB::raw('(PricePerUnit * QuantityOrdered) as extPrice'))
        ->where('OrderNumber', $orderNumber)->get();
        return $products;
    }


    public function getTotals($orderNumber)
    {
        $totals = Order::select('FinalProductTotal',
            'Surcharge',
            'Discount',
            'CouponDiscount',
            'ShippingTotal',
            'TaxTotal',
            'FinalGrandTotal')
            ->where('OrderNumber', $orderNumber)->first()->toarray();
        return $totals;
    }

    public function getComments($orderNumber)
    {
        $comments = Order::select('Comments')
            ->where('OrderNumber', $orderNumber)->first()->toarray();
        return $comments;
    }

    public function getTrackingInformation($orderNumber){
        $ti = Tracking::select ('DateAdded', 'TrackingID', 'Carrier', 'ShippersMethod', 'Notes')
            ->where('OrderNum',$orderNumber)
            ->where('IsVoid', null )->get();
        return $ti;
    }

}