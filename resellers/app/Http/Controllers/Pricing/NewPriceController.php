<?php

namespace App\Http\Controllers\Pricing;

use App\Repositories\NewPricingRepository;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class NewPriceController extends Controller
{
    /**
     * @var NewPricingRepository
     */
    private $newPricingRepository;


    public function __construct(NewPricingRepository $newPricingRepository)
    {
        $this->newPricingRepository = $newPricingRepository;
    }

    public function index(){
        $title = 'RPTV Customized Price';
        $colNames = "'MITSKU','CategoryID','SKU','Make','PartNumber','Quantity','Price','DSEconomy','DS2ndDay','DSOvernight','Rebate','Category','ParentCategory','OcultoKeywords'";

        $colModel = "
                    {name:'MISKU',index:'MISKU', width:90, align:'center','hidden':true},
                    {name:'CategoryID',index:'pc.CategoryID', width:70, 'hidden':true},
                    {name:'SKU',index:'SKU', width:90, align:'center','key': true },
                    {name:'Make',index:'Make', width:180, align:'left','hidden':true},
                    {name:'PartNumber',index:'PartNumber', width:350, align:'left'},
                    {name:'Quantity',index:'Quantity', width:110, align:'left'},
                    {name:'Price',index:'Price', width:90, align:'center',formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                    {name:'DSEconomy',index:'DSEconomy', width:90, align:'center',formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                    {name:'DS2ndDay',index:'DS2ndDay', width:90, align:'center',formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                    {name:'DSOvernight',index:'DSOvernight', width:90, align:'center',formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                    {name:'Rebate',index:'csp.[Rebate]', width:90, align:'center',formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                    {name:'Category',index:'Category', width:180, align:'left'},
                    {name:'ParentCategory',index:'ParentCategory', width:180, align:'left','hidden':true},
                    {name:'OcultoKeywords',index:'OcultoKeywords', width:100, align:'center','hidden':true}"

        ;


        $categories  = $this->newPricingRepository->getCategories();
        $availability = [0=>'All',1 =>'In Stock'];

        return view('pricing/new/newPrice',compact('categories','availability','colNames','colModel','title'));
    }

    public function search(Request $request){
        //grid Elements
        $page     = $request->input('page');
        $limit    = $request->input('rows');
        $sidx     = $request->input('sidx');
        $sord     = $request->input('sord');
        // form Elements
        $search   = $request->get('Search');
        $category   = $request->get('Categories');
        $availability   = $request->get('Availability');
        $excel    = $request->get('Excel');


        $fieldsToSearch = ['rsp.MISKU','rsp.PartNumber', 'rsp.MIPortalSKU', 'rsp.Manufacturer'];
        $data = $this->newPricingRepository->allOrders($search,$availability,$category,$fieldsToSearch);


        if (!$excel){
            return response()->json($this->newPricingRepository->response($data,$page,$limit,$sidx,$sord));
        }else{
            return $this->newPricingRepository->exportExcel($data,'SkuFile','Orders');
        }
    }


}


