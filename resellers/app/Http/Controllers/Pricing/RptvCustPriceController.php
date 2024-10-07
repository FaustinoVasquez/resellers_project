<?php

namespace App\Http\Controllers\Pricing;

use App\Repositories\RptvCustPricingRepository;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class RptvCustPriceController extends Controller
{
    /**
     * @var RptvCustPricingRepository
     */
    private $rptvCustPricingRepository;


    public function __construct(RptvCustPricingRepository $rptvCustPricingRepository)
    {
        $this->rptvCustPricingRepository = $rptvCustPricingRepository;
    }

    public function index(){
        $title = 'RPTV Customized Price';
        $colNames = "'SKU','Name','Availability','Price','DSEconomy','DS2ndDay','DSOvernight','Rebate','Category'";

        $colModel = "{name:'SKU',index:'csp.ProductCatalogID', width:60, align:'center', 'key':true},
                     {name:'PartNumber',index:'ProductCatalog.Name', width:150, align:'left'},
                     {name:'Availability',index:'gs.TotalStock', width:60, align:'center'},
                     {name:'Price',index:'csp.SalePrice', width:70, align:'center',formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                     {name:'DSEconomy',index:'csp.DSEconomyPrice', width:70, align:'center',formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                     {name:'DS2ndDay',index:'csp.DS2ndDayPrice', width:70, align:'center',formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                     {name:'DSOvernight',index:'csp.DS1DayPrice', width:70, align:'center',formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                     {name:'Rebate',index:'csp.Rebate', width:70, align:'center',formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                     {name:'Category',index:'ctg.Name', width:80, align:'center'}";

        $categories  = $this->rptvCustPricingRepository->getCategories();
        $availability = [0=>'All',1 =>'In Stock'];

        return view('pricing/rptv/rptvCustPrice',compact('categories','availability','colNames','colModel','title'));
    }

    public function search(Request $request){
        //grid Elements
        $page     = $request->input('page');
        $limit    = $request->input('rows');
        $sidx     = $request->input('sidx');
        $sord     = $request->input('sord');
        // form Elements
        $search   = $request->get('Search');
        $category   = $request->get('Category');
        $availability   = $request->get('Availability');
        $excel    = $request->get('Excel');

        $fieldsToSearch = ['csp.ProductCatalogID', 'ProductCatalog.Name', 'ctg.Name'];
        $data = $this->rptvCustPricingRepository->allOrders($search,$category,$availability,$fieldsToSearch);

        if (!$excel){
            return response()->json($this->rptvCustPricingRepository->response($data,$page,$limit,$sidx,$sord));
        }else{
            return $this->rptvCustPricingRepository->exportExcel($data,'SkuFile','Orders');
        }
    }


}
