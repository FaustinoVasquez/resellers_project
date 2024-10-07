<?php
namespace App\Repositories;

use App\Models\CustomerSpecificPricing;
use App\Models\ResellerPortalSKUs;
use App\Models\ProductCatalog;
use Illuminate\Support\Facades\DB;


class NewPricingRepository extends BaseRepository
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
	 $orders =  DB::table('MiTech.mi.vw_ResellerPortalSKUs as rsp')
	->leftjoin('Inventory.dbo.CustomerSpecificPricing as csp','rsp.MISKU','=','csp.ProductCatalogID')
      	->leftjoin('Mitech.mi.vw_ProductCatalog as pc','rsp.MISKU','=','pc.ProductSKU')
       	->leftjoin('Mitech.mi.vw_CompatabilityList as cl',function( $q ){
			$q->on('cl.Manufacturer','=','pc.Manufacturer')
			->where('cl.PartNumber','=','pc.ManufacturerPN');
	})
	->select('rsp.MISKU',
		'pc.CategoryID',
		'rsp.MIPortalSKU as SKU',
   		'rsp.Manufacturer AS Make',
    		'rsp.PartNumber AS PartNumber',
		DB::raw( "(CASE
         			WHEN pc.CategoryName LIKE '%Bare%' AND pc.QOH > '1'
             				THEN 'In Stock'
         			WHEN pc.CategoryName LIKE '%Housing%' AND pc.VQty > '1'
             				THEN 'In Stock'
         			ELSE 'Out of Stock' END) AS 'Quantity'"),
		'csp.SalePrice AS Price',
    		'csp.DSEconomyPrice AS DSEconomy',
    		'csp.DS2ndDayPrice AS DS2ndDay',
    		'csp.DS1DayPrice AS DSOvernight',
    		'csp.Rebate',
		'pc.CategoryName AS Category',
 		'pc.ParentCategoryName AS ParentCategory',
		DB::raw("ISNULL(cl.CompatibilityPartSpecific,'') AS 'OcultoKeywords'")
	);
       return $orders;
   }

    public function allOrders($search,$availability,$category,$fields){


        $query= $this->select();
        $query = $this->getCustomerId($query);
        $query = $this->getIfCategory($query,$category);
        $query = $this->getIfAvailable($query,$availability)
            ->where('csp.Active','1')
	    ->where('pc.CategoryID','<>','24')
           // ->whereIn('ctg.ParentID',[1,2,3])
            ->whereraw($this->concatAllWerefields($search, $fields));
        return $query;
    }

    public function getCustomerid($query){
        return $query->where('csp.CustomerID',auth()->user()->customerid);
    }

    public function getIfCategory($query,$category){
        if($category){
            return $query->where('pc.CategoryID',$category);
        }
        return $query;
    }

    public function getIfAvailable($query,$available){
        if($available){
            return $query->where(function($query){
                $query->where('pc.QOH','>','1')->where('pc.VQty','>','1');
            });
        }
        return $query;
    }

}

