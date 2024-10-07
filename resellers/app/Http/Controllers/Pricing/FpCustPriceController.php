<?php

namespace App\Http\Controllers\Pricing;

use App\Repositories\FpCustPricingRepository;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class FpCustPriceController extends Controller
{
    /**
     * @var RptvCustPricingRepository
     */
    private $fpCustPricingRepository;


    public function __construct(FpCustPricingRepository $fpCustPricingRepository)
    {
        $this->fpCustPricingRepository = $fpCustPricingRepository;
    }

    public function index(){
        $title = 'FP Customized Price';
        $colNames = "'SKU','Make','MITSKU','Name','Availability','Price','DSEconomy','DS2ndDay','DSOvernight','Rebate','Category'";

        $colModel = "{name:'SKU',index:'PJD.CatalogID', width:60, align:'center', 'key':true},
                {name:'Make',index:'PJD.Brand', width:60, align:'center'},
		{name:'MITSKU',index:'MITSKU', width:60, align:'center', 'hidden':true},
                {name:'PartNumber',index:'PJD.PartNumber', width:100, align:'center'},
                {name:'Quantity',index:'GS.TotalStock', width:60, align:'center', formatter:checkAvailability},
                {name:'Price',index:'CustomerSpecificPricing.SalePrice', width:70, align:'center',formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                {name:'DSEconomy',index:'CustomerSpecificPricing.DSEconomyPrice', width:70, align:'center',formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                {name:'DS2ndDay',index:'CustomerSpecificPricing.DS2ndDayPrice', width:70, align:'center',formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                {name:'DSOvernight',index:'CustomerSpecificPricing.DS1DayPrice', width:70, align:'center',formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                {name:'Rebate',index:'CustomerSpecificPricing.Rebate', width:70, align:'center',formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                {name:'Category',index:'CTG.Name', width:80, align:'center'}";

        $categories  = $this->fpCustPricingRepository->getCategories();
        $availability = [0=>'All',1 =>'In Stock'];

        return view('pricing/fp/fpCustPrice',compact('categories','availability','colNames','colModel','title'));
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

        $fieldsToSearch = ["PJD.[CatalogID] +
                   (CASE WHEN CTG.[Name] ='Generic FP Lamps with Housing' THEN '-G'
                         WHEN CTG.[Name] = 'Philips FP Lamps Bare' THEN '-BOP'
                         WHEN CTG.[Name] = 'Osram FP Lamps Bare'  THEN '-BOO'
                         WHEN CTG.[Name] = 'Phoenix FP Lamps Bare'  THEN '-BOX'
                         WHEN CTG.[Name] = 'Ushio FP Lamps Bare'  THEN '-BOU'
                         WHEN CTG.[Name] = 'Philips FP Lamps with Housing' THEN '-OP'
                         WHEN CTG.[Name] = 'Osram FP Lamps with Housing' THEN '-OO'
                         WHEN CTG.[Name] = 'Phoenix FP Lamps with Housing' THEN '-OX'
                         WHEN CTG.[Name] = 'Ushio FP Lamps with Housing' THEN '-OU'
                    ELSE '-O' END)", 'PJD.[Brand]','PJD.[PartNumber]','PJD.[PartNumberV2]','PJD.[PartNumberV3]','CTG.[Name]'];


        $data = $this->fpCustPricingRepository->allOrders($search,$category,$availability,$fieldsToSearch);

		

        if (!$excel){
           return response()->json($this->fpCustPricingRepository->response($data,$page,$limit,$sidx,$sord));
        }else{
            return $this->fpCustPricingRepository->exportExcel($data,'SkuFile','Orders');
        }
    }
}
