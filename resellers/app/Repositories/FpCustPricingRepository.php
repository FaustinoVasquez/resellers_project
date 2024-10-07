<?php
namespace App\Repositories;

use App\Models\CustomerSpecificPricing;
use App\Models\ProjectorData;
use Illuminate\Support\Facades\DB;


class FpCustPricingRepository extends BaseRepository
{
    public function getModel()
    {
        return new ProjectorData();
    }

    protected function categories(){
        return $this->newQuery()
            ->distinct()->select('PC.CategoryID as Id','CS.Name')
                            ->leftJoin('Inventory.dbo.CustomerSpecificPricing as CSP', function($join){
                                $join->on('ProjectorData.EncSKU','=','CSP.ProductCatalogID')
                                    ->orOn('ProjectorData.EncSKUPH','=','CSP.ProductCatalogID')
                                    ->orOn('ProjectorData.EncSKUOS','=','CSP.ProductCatalogID')
                                    ->orOn('ProjectorData.EncSKUPX','=','CSP.ProductCatalogID')
                                    ->orOn('ProjectorData.EncSKUUSH','=','CSP.ProductCatalogID')
                                    ->orOn('ProjectorData.BareSKU','=','CSP.ProductCatalogID')
                                    ->orOn('ProjectorData.BareSKUPH','=','CSP.ProductCatalogID')
                                    ->orOn('ProjectorData.BareSKUOS','=','CSP.ProductCatalogID')
                                    ->orOn('ProjectorData.BareSKUPX','=','CSP.ProductCatalogID')
                                    ->orOn('ProjectorData.BareSKUUSH','=','CSP.ProductCatalogID');
                            })
                            ->leftJoin('Inventory.dbo.ProductCatalog AS PC','CSP.ProductCatalogID','=','PC.ID')
                            ->leftJoin('Inventory.dbo.Categories AS CS','CS.ID','=','PC.CategoryID')
                            ->where('CSP.CustomerID',auth()->user()->customerid)
                            ->whereIn('CS.ParentID',[4,3])
                            ->whereIn('ProjectorData.EncSKU', function($query){
                                $query->select(DB::raw('ProductCatalogID FROM Inventory.dbo.CustomerSpecificPricing'));
                            })
                            ->groupBy('PC.CategoryID')
                            ->groupBy('CS.Name')
                            ->orderBy('PC.CategoryID')
                            ->get()->toarray();
    }

    function getCategories(){
        $elements =  $this->categories();

        $categories[0]= 'All Categories';
        foreach ($elements as $element ) {
            $categories[$element['Id']] = $element['Name'];
        }
        return $categories;
    }


    protected function select(){
       $orders = CustomerSpecificPricing::select(
           DB::raw("PJD.[CatalogID] +
                   (CASE WHEN CTG.[Name] ='Generic FP Lamps with Housing' THEN '-G'
                         WHEN CTG.[Name] = 'Philips FP Lamps Bare' THEN '-BOP'
                         WHEN CTG.[Name] = 'Osram FP Lamps Bare'  THEN '-BOO'
                         WHEN CTG.[Name] = 'Phoenix FP Lamps Bare'  THEN '-BOX'
                         WHEN CTG.[Name] = 'Ushio FP Lamps Bare'  THEN '-BOU'
                         WHEN CTG.[Name] = 'Philips FP Lamps with Housing' THEN '-OP'
                         WHEN CTG.[Name] = 'Osram FP Lamps with Housing' THEN '-OO'
                         WHEN CTG.[Name] = 'Phoenix FP Lamps with Housing' THEN '-OX'
                         WHEN CTG.[Name] = 'Ushio FP Lamps with Housing' THEN '-OU'
                    ELSE '-O' END) AS 'SKU'"),
                     'PJD.Brand AS Make',
                     'PJD.PartNumber AS PartNumber',
                     DB::raw("(CASE WHEN floor(GS.[TotalStock] * 0.8) > '1' THEN 'In Stock' ELSE 'Please Call' END) AS 'Quantity'"),
                     'CustomerSpecificPricing.SalePrice AS Price',
                     'CustomerSpecificPricing.DSEconomyPrice AS DSEconomy',
                     'CustomerSpecificPricing.DS2ndDayPrice AS DS2ndDay',
                     'CustomerSpecificPricing.DS1DayPrice AS DSOvernight',
                     'CustomerSpecificPricing.Rebate',
                     'CTG.Name as Category',
		     'PC.ID as MITSKU'
       )
       ->leftjoin('MITDB.dbo.ProjectorData as PJD', function($join){
             $join->on('CustomerSpecificPricing.ProductCatalogID','=','PJD.BareSKU')
                ->orOn('CustomerSpecificPricing.ProductCatalogID','=','PJD.BareSKUPH')
                ->orOn('CustomerSpecificPricing.ProductCatalogID','=','PJD.BareSKUOS')
                ->orOn('CustomerSpecificPricing.ProductCatalogID','=','PJD.BareSKUPX')
                ->orOn('CustomerSpecificPricing.ProductCatalogID','=','PJD.BareSKUUSH')
                ->orOn('CustomerSpecificPricing.ProductCatalogID','=','PJD.EncSKU')
                ->orOn('CustomerSpecificPricing.ProductCatalogID','=','PJD.EncSKUPH')
                ->orOn('CustomerSpecificPricing.ProductCatalogID','=','PJD.EncSKUOS')
                ->orOn('CustomerSpecificPricing.ProductCatalogID','=','PJD.EncSKUPX')
                ->orOn('CustomerSpecificPricing.ProductCatalogID','=','PJD.EncSKUUSH');
                })
                ->leftJoin('Inventory.dbo.ProductCatalog AS PC','CustomerSpecificPricing.ProductCatalogID','=','PC.ID')
                ->leftJoin('Inventory.dbo.Categories AS CTG','PC.CategoryID','=','CTG.ID')
                ->leftJoin('Inventory.dbo.Global_Stocks AS GS','PC.ID','=','GS.ProductCatalogId');

       return $orders;
   }

    public function allOrders($search,$category,$availability,$fields){
        $query= $this->select();
        $query = $this->getIfCategory($query,$category);
        $query = $this->getIfAvailable($query,$availability)
            ->where('CustomerSpecificPricing.CustomerID',auth()->user()->customerid)
            ->where('CustomerSpecificPricing.Active','1')
            ->whereIn('CTG.ParentID',[3,4])
            ->whereraw("CTG.Name NOT LIKE 'Generic FP Lamps Bare'")
            ->whereraw("PJD.CatalogID IS NOT NULL")
            ->whereraw($this->concatAllWerefields($search, $fields));
        return $query;
    }

    public function getIfCategory($query,$category){
        if($category){
            return $query->where('CTG.ID',$category);
        }
        return $query;
    }

    public function getIfAvailable($query,$available){
        if($available){
            return $query->where('GS.TotalStock','>','1');
        }
        return $query;
    }

}
