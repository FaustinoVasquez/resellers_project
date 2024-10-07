<?php
namespace App\Repositories;

use App\Models\ProductCatalog;
use Illuminate\Support\Facades\DB;


class OtherCustPricingRepository extends BaseRepository
{
    public function getModel()
    {
        return new ProductCatalog();
    }


    protected function categories(){
        return $this->newQuery()
            ->distinct()->select('ctg.ID as Id','ctg.Name as Name')
            ->leftJoin('CustomerSpecificPricing as csp', 'ProductCatalog.ID','=','csp.ProductCatalogID')
            ->leftJoin('Categories as ctg','ProductCatalog.CategoryID','=','ctg.ID')
            ->where('csp.CustomerID',auth()->user()->customerid)
            ->where('csp.Active','1')
            ->whereNotIn('ctg.ParentID',[1,2,3,4])
            ->groupby('ctg.Name', 'ctg.ID')
            ->orderby('ctg.Name')
            ->get()->toarray();
    }

    function getOtherCategories(){
        $elements =  $this->categories();

        $categories[0]= 'All Categories';
        foreach ($elements as $element ) {
            $categories[$element['Name']] = $element['Name'];
        }
        return $categories;
    }

    protected function manufacturers(){
        return $this->newQuery()
            ->distinct()->select('ProductCatalog.Manufacturer')
            ->leftJoin('CustomerSpecificPricing as csp', 'ProductCatalog.ID','=','csp.ProductCatalogID')
            ->leftJoin('Categories as ctg','ProductCatalog.CategoryID','=','ctg.ID')
            ->where('csp.CustomerID',auth()->user()->customerid)
            ->where('csp.Active','1')
            ->whereNotIn('ctg.ParentID',[1,2,3,4])
            ->groupby('ProductCatalog.Manufacturer')
            ->orderby('ProductCatalog.Manufacturer')
            ->get()->toarray();
    }

    function getManufacturers(){
        $elements =  $this->manufacturers();

        $categories[0]= 'All Manufacturers';
        foreach ($elements as $element ) {
            if ($element['Manufacturer'])
             $categories[$element['Manufacturer']] = $element['Manufacturer'];
        }
        return $categories;
    }


    protected function select(){
        return $this->newQuery()->select(
            'csp.ProductCatalogID as SKU',
            'ProductCatalog.Manufacturer as Make',
            'ProductCatalog.Name AS PartNumber',
            DB::raw("CASE WHEN gs.[TotalStock] > '1'
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

    public function allOrders($search,$availability,$categories,$manufacturers,$fields){
        $query= $this->select();
        $query = $this->getIfCategories($query,$categories);
        $query = $this->getIfManufacturers($query,$manufacturers);
        $query = $this->getIfAvailable($query,$availability)
            ->where('csp.CustomerID',auth()->user()->customerid)
            ->where('csp.Active','1')
            ->where('csp.Active','1')
            ->whereNotIn('ctg.ParentID',[1,2,3,4])
            ->whereraw($this->concatAllWerefields($search, $fields));

        return $query;
    }


    public function getIfAvailable($query,$available){
        if($available){
            return $query->where('gs.TotalStock','>','1');
        }
        return $query;
    }

    public function getIfCategories($query,$categories){
        if($categories){
            return $query->where('ctg.Name',$categories);
        }
        return $query;
    }

    public function getIfManufacturers($query,$manufacturers){
        if($manufacturers){
            return $query->where('ProductCatalog.Manufacturer',$manufacturers);
        }
        return $query;
    }

}
