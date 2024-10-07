<?php
namespace App\Repositories;

use App\Models\CustomerSpecificPricing;
use App\Models\ProductCatalog;
use Illuminate\Support\Facades\DB;


class RptvCustPricingRepository extends BaseRepository
{
    public function getModel()
    {
        return new CustomerSpecificPricing();
    }

    protected function categories(){
        return $this->newQuery()
            ->distinct()->select('C.id as Id','C.Name as Name')
                            ->leftJoin('ProductCatalog as PC', 'CustomerSpecificPricing.ProductCatalogID','=','PC.ID')
                            ->leftJoin('Categories as C','PC.CategoryID','=','C.ID')
                            ->where('CustomerSpecificPricing.CustomerID',auth()->user()->customerid)
                            ->whereIn('C.ParentID',[1,2,3])->get()->toarray();
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
       $orders = ProductCatalog::select(
           'csp.ProductCatalogID as SKU',
            DB::raw("CASE WHEN CHARINDEX(' (Compatible)', ProductCatalog.[Name]) > 1
                       THEN SUBSTRING(ProductCatalog.[Name], 1, CHARINDEX(' (Compatible)', ProductCatalog.[Name]) )
                       ELSE ProductCatalog.[Name] END
                      AS PartNumber"),
            DB::raw("CASE WHEN ((gs.[TotalStock] - Inventory.dbo.fn_Get_GlobalStock_by_BinID(csp.[ProductCatalogID])) > '1' OR ProductCatalog.[AlwaysInStock] = '1')
                      THEN 'In Stock' ELSE 'Out of Stock' END AS Availability"),
            'csp.SalePrice AS Price',
            'csp.DSEconomyPrice AS DSEconomy',
            'csp.DS2ndDayPrice AS DS2ndDay',
            'csp.DS1DayPrice AS DSOvernight',
            'csp.Rebate',
            'ctg.Name as Category')
       ->leftjoin('CustomerSpecificPricing as csp','ProductCatalog.ID','=','csp.ProductCatalogID')
       ->leftjoin('Categories as ctg','ProductCatalog.CategoryID','=','ctg.ID')
       ->leftjoin('Global_Stocks as gs','ProductCatalog.ID','=','gs.ProductCatalogId');

       return $orders;
   }

    public function allOrders($search,$category,$availability,$fields){
        $query= $this->select();
        $query = $this->getCustomerId($query);
        $query = $this->getIfCategory($query,$category);
        $query = $this->getIfAvailable($query,$availability)
            ->where('csp.Active','1')
            ->whereIn('ctg.ParentID',[1,2,3])
            ->whereraw($this->concatAllWerefields($search, $fields));
        return $query;
       
    }

    public function getCustomerid($query){
        return $query->where('csp.CustomerID',auth()->user()->customerid);
    }

    public function getIfCategory($query,$category){
        if($category){
            return $query->where('ctg.ID',$category);
        }
        return $query;
    }

    public function getIfAvailable($query,$available){
        if($available){
            return $query->where(function($query){
                $query->where('gs.TotalStock','>','1')->orWhere('ProductCatalog.AlwaysInStock','1');
            });
        }
        return $query;
    }

}
