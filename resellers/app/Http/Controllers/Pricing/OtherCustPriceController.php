<?php

namespace App\Http\Controllers\Pricing;

use App\Repositories\OtherCustPricingRepository;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class OtherCustPriceController extends Controller
{
    /**
     * @var RptvCustPricingRepository
     */
    private $otherCustPricingRepository;


    public function __construct(OtherCustPricingRepository $otherCustPricingRepository)
    {
        $this->otherCustPricingRepository = $otherCustPricingRepository;
    }

    public function index(){
        $title = 'Other Customized Price';
        $colNames = "'SKU','Make','Name','Availability','Price','DSEconomy','DS2ndDay','DSOvernight','Rebate','Category'";

        $colModel = "{name:'SKU',index:'csp.ProductCatalogID', width:60, align:'center','key':true},
                     {name:'Make',index:'ProductCatalog.Manufacturer', width:60, align:'center'},
                     {name:'PartNumber',index:'ProductCatalog.Name', width:240, align:'left'},
                     {name:'Availability',index:'gs.TotalStock', width:60, align:'center'},
                     {name:'Price',index:'csp.SalePrice', width:70, align:'center',formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                     {name:'DSEconomy',index:'csp.DSEconomyPrice', width:70, align:'center',formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                     {name:'DS2ndDay',index:'csp.DS2ndDayPrice', width:70, align:'center',formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                     {name:'DSOvernight',index:'csp.DS1DayPrice', width:70, align:'center',formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                     {name:'Rebate',index:'csp.Rebate', width:70, align:'center',formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                     {name:'Category',index:'ctg.Name', width:70, align:'center'}";

        $categories  = $this->otherCustPricingRepository->getOtherCategories();
        $manufacturers  = $this->otherCustPricingRepository->getManufacturers();
        $availability = [0=>'All Available',1 =>'In Stock'];

        return view('pricing/other/otherCustPrice',compact('availability','colNames','colModel','title','categories','manufacturers'));
    }

    public function search(Request $request){
        //grid Elements
        $page     = $request->input('page');
        $limit    = $request->input('rows');
        $sidx     = $request->input('sidx');
        $sord     = $request->input('sord');
        // form Elements
        $search   = $request->get('Search');
        $availability   = $request->get('Availability');
        $categories   = $request->get('Categories');
        $manufacturers= $request->get('Manufacturers');
        $excel    = $request->get('Excel');

        $fieldsToSearch = ['csp.ProductCatalogID', 'ProductCatalog.Manufacturer','ProductCatalog.Name','ctg.Name'];
        $data = $this->otherCustPricingRepository->allOrders($search,$availability,$categories,$manufacturers,$fieldsToSearch);

        if (!$excel){
            return response()->json($this->otherCustPricingRepository->response($data,$page,$limit,$sidx,$sord));
        }else{
            return $this->otherCustPricingRepository->exportExcel($data,'SkusFile','Orders');
        }
    }
}
