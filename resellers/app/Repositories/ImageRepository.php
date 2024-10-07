<?php
namespace App\Repositories;

use App\Models\CustomerSpecificPricing;


class ImageRepository extends BaseRepository
{
    public function getModel()
    {
        return new CustomerSpecificPricing();
    }

        protected function category($catalogId){
        return $this->newQuery()
            ->select('CTG.ID')
                            ->leftJoin('MITDB.dbo.ProjectorData AS PJD', function($join){
                                $join->on('PJD.EncSKU','=','CustomerSpecificPricing.ProductCatalogID')
                                    ->orOn('PJD.EncSKUPH','=','CustomerSpecificPricing.ProductCatalogID')
                                    ->orOn('PJD.EncSKUOS','=','CustomerSpecificPricing.ProductCatalogID')
                                    ->orOn('PJD.EncSKUPX','=','CustomerSpecificPricing.ProductCatalogID')
                                    ->orOn('PJD.EncSKUUSH','=','CustomerSpecificPricing.ProductCatalogID')
                                    ->orOn('PJD.BareSKU','=','CustomerSpecificPricing.ProductCatalogID')
                                    ->orOn('PJD.BareSKUPH','=','CustomerSpecificPricing.ProductCatalogID')
                                    ->orOn('PJD.BareSKUOS','=','CustomerSpecificPricing.ProductCatalogID')
                                    ->orOn('PJD.BareSKUPX','=','CustomerSpecificPricing.ProductCatalogID')
                                    ->orOn('PJD.BareSKUUSH','=','CustomerSpecificPricing.ProductCatalogID');
                            })
                            ->leftJoin('Inventory.dbo.ProductCatalog AS PC','CustomerSpecificPricing.ProductCatalogID','=','PC.ID')
                            ->leftJoin('Inventory.dbo.Categories AS CTG','CTG.ID','=','PC.CategoryID')
                            ->where('PJD.CatalogID',$catalogId)
                            ->where('CustomerSpecificPricing.CustomerID',auth()->user()->customerid)
                            ->where('CustomerSpecificPricing.Active','1')
                            ->whereIn('CTG.ParentID',[4,3])
                            ->first()->ID;
    }

    function getCategory($catalogId){
        $categoryID =  $this->category($catalogId);
        $html ='<div><center>';
        $philips ='http://d1vp05nmmxpish.cloudfront.net/catalog/philips/';
        $osram = 'http://d1vp05nmmxpish.cloudfront.net/catalog/osram/';
        $phoenix ='http://d1vp05nmmxpish.cloudfront.net/catalog/phoenix/';
        $ushio ='http://d1vp05nmmxpish.cloudfront.net/catalog/ushio/';
        $generic ='http://d1vp05nmmxpish.cloudfront.net/catalog/generic/';

        switch ($categoryID) {
            case (($categoryID == 5) || ($categoryID == 10) || ($categoryID == 15) || ($categoryID == 20)):
                $html .= "<a href='".$philips.$catalogId."-001.jpg' target='_blank'><img src='".$philips.$catalogId."-001.jpg' width='150' height='150'></a>&nbsp;&nbsp;";
                $html .= "<a href='".$philips.$catalogId."-002.jpg' target='_blank'><img src='".$philips.$catalogId."-002.jpg' width='150' height='150'></a>&nbsp;&nbsp;";
                $html .= "<a href='".$philips.$catalogId."-003.jpg' target='_blank'><img src='".$philips.$catalogId."-003.jpg' width='150' height='150'></a>&nbsp;&nbsp;";
                $html .= "<a href='".$philips.$catalogId."-004.jpg' target='_blank'><img src='".$philips.$catalogId."-004.jpg' width='150' height='150'></a>&nbsp;&nbsp;";
                $html .= "<a href='".$philips.$catalogId."-005.jpg' target='_blank'><img src='".$philips.$catalogId."-005.jpg' width='150' height='150'></a>&nbsp;&nbsp;";
                break;
            case (($categoryID == 6) || ($categoryID == 11) || ($categoryID == 16) || ($categoryID == 21)):
                $html .= "<a href='".$osram.$catalogId."-001.jpg' target='_blank'><img src='".$osram.$catalogId."-001.jpg' width='150' height='150'></a>&nbsp;&nbsp;";
                $html .= "<a href='".$osram.$catalogId."-002.jpg' target='_blank'><img src='".$osram.$catalogId."-002.jpg' width='150' height='150'></a>&nbsp;&nbsp;";
                $html .= "<a href='".$osram.$catalogId."-003.jpg' target='_blank'><img src='".$osram.$catalogId."-003.jpg' width='150' height='150'></a>&nbsp;&nbsp;";
                $html .= "<a href='".$osram.$catalogId."-004.jpg' target='_blank'><img src='".$osram.$catalogId."-004.jpg' width='150' height='150'></a>&nbsp;&nbsp;";
                $html .= "<a href='".$osram.$catalogId."-005.jpg' target='_blank'><img src='".$osram.$catalogId."-005.jpg' width='150' height='150'></a>&nbsp;&nbsp;";
                break;

            case (($categoryID == 7) || ($categoryID == 12) || ($categoryID == 17) || ($categoryID == 22)):
                $html .= "<a href='".$phoenix.$catalogId."-001.jpg' target='_blank'><img src='".$phoenix.$catalogId."-001.jpg' width='150' height='150'></a>&nbsp;&nbsp;";
                $html .= "<a href='".$phoenix.$catalogId."-002.jpg' target='_blank'><img src='".$phoenix.$catalogId."-002.jpg' width='150' height='150'></a>&nbsp;&nbsp;";
                $html .= "<a href='".$phoenix.$catalogId."-003.jpg' target='_blank'><img src='".$phoenix.$catalogId."-003.jpg' width='150' height='150'></a>&nbsp;&nbsp;";
                $html .= "<a href='".$phoenix.$catalogId."-004.jpg' target='_blank'><img src='".$phoenix.$catalogId."-004.jpg' width='150' height='150'></a>&nbsp;&nbsp;";
                $html .= "<a href='".$phoenix.$catalogId."-005.jpg' target='_blank'><img src='".$phoenix.$catalogId."-005.jpg' width='150' height='150'></a>&nbsp;&nbsp;";
                break;

            case (($categoryID == 8) || ($categoryID == 13) || ($categoryID == 18) || ($categoryID == 23)):
                $html .= "<a href='".$ushio.$catalogId."-001.jpg' target='_blank'><img src='".$ushio.$catalogId."-001.jpg' width='150' height='150'></a>&nbsp;&nbsp;";
                $html .= "<a href='".$ushio.$catalogId."-002.jpg' target='_blank'><img src='".$ushio.$catalogId."-002.jpg' width='150' height='150'></a>&nbsp;&nbsp;";
                $html .= "<a href='".$ushio.$catalogId."-003.jpg' target='_blank'><img src='".$ushio.$catalogId."-003.jpg' width='150' height='150'></a>&nbsp;&nbsp;";
                $html .= "<a href='".$ushio.$catalogId."-004.jpg' target='_blank'><img src='".$ushio.$catalogId."-004.jpg' width='150' height='150'></a>&nbsp;&nbsp;";
                $html .= "<a href='".$ushio.$catalogId."-005.jpg' target='_blank'><img src='".$ushio.$catalogId."-005.jpg' width='150' height='150'></a>&nbsp;&nbsp;";
                break;

            case (($categoryID == 9) || ($categoryID == 14) || ($categoryID == 19) || ($categoryID == 24)):
                $html .= "<a href='".$generic.$catalogId."-001.jpg' target='_blank'><img src='".$generic.$catalogId."-001.jpg' width='150' height='150'></a>&nbsp;&nbsp;";
                $html .= "<a href='".$generic.$catalogId."-002.jpg' target='_blank'><img src='".$generic.$catalogId."-002.jpg' width='150' height='150'></a>&nbsp;&nbsp;";
                $html .= "<a href='".$generic.$catalogId."-003.jpg' target='_blank'><img src='".$generic.$catalogId."-003.jpg' width='150' height='150'></a>&nbsp;&nbsp;";
                $html .= "<a href='".$generic.$catalogId."-004.jpg' target='_blank'><img src='".$generic.$catalogId."-004.jpg' width='150' height='150'></a>&nbsp;&nbsp;";
                $html .= "<a href='".$generic.$catalogId."-005.jpg' target='_blank'><img src='".$generic.$catalogId."-005.jpg' width='150' height='150'></a>&nbsp;&nbsp;";
                break;
        }

        return $html.'</div><center>';

    }

}