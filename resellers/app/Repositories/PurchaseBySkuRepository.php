<?php
namespace App\Repositories;

use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;


class PurchaseBySkuRepository extends BaseRepository
{
    public function getModel()
    {
        return new OrderDetail();
    }

    function select(){
        return $this->newQuery()
            ->selectraw("O.OrderNumber AS OrderNumber,
                        (CASE WHEN len(IsNull(Cast(O.[OrderInst] AS NVARCHAR(50)),'')) - len(replace(IsNull(Cast(O.[OrderInst] AS NVARCHAR(50)),''), '-', '')) > 0
                        THEN CAST(O.[OrderInst] AS NVARCHAR(50))
                        ELSE CAST(O.[SourceOrderNumber] AS NVARCHAR(50)) END) AS 'CustomerOrderNumber',
                  O.PONumber,
                  CONVERT(NVARCHAR(50),CAST([Order Details].[DetailDate] AS DATE),101) AS 'OrderDate',
                  [Order Details].[SKU] AS 'MITSKU',
                  IsNull([Order Details].[Option01],'') AS 'CustomerSKU',
                  [Order Details].[Product] AS 'Item',
                  [Order Details].[QuantityOrdered] AS 'QTY',
                  CAST([Order Details].[PricePerUnit] AS Decimal(10,2)) AS 'ProductPrice',
                  CAST(O.GrandTotal AS Decimal(10,2)) AS 'ProductTotal',
                  CAST(O.ShippingTotal AS Decimal(10,2)) AS 'ShippingTotal',
                  O.ShipName AS 'ShipName',
                  O.ShipState AS 'ShipState',
                  (CASE WHEN [DateShipped] IS NULL THEN 'Pending' ELSE O.Shipping END) AS 'ShipMethod',
                  (CASE WHEN (SELECT TOP(1) TRK.[TRACKINGID] FROM [OrderManager].[dbo].[Tracking] AS TRK WHERE TRK.NumericKey = O.OrderNumber) IS NOT NULL THEN 'Shipped' ELSE 'Pending' END) AS 'Status',
                  CONVERT(NVARCHAR(50),CAST(IsNull([DateShipped],'') AS DATE),101) AS 'ShipDate'
                  ")
            ->leftJoin('OrderManager.dbo.Orders as O','Order Details.OrderNumber','=','O.OrderNumber');
    }

    public function getAll($search,$dateFrom,$dateTo,$fields){
        $query = $this->select();
        $query = $this->getDateFromTo($query,$dateFrom,$dateTo);
        $query = $this->getCustomerCartID($query)
            ->where('Order Details.Adjustment','0')
            ->where('O.Cancelled','0')
            ->whereraw($this->concatAllWerefields($search, $fields));
        return $query;
    }


}
