<?php

namespace App\Http\Controllers\Reports;

use App\Repositories\PurchaseBySkuRepository;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;


class PurchaseBySkuController extends Controller
{
    /**
     * @var OrderRepository
     */
    private $purchaseBySkuRepository;

    public function __construct(PurchaseBySkuRepository $purchaseBySkuRepository)
    {
        $this->purchaseBySkuRepository = $purchaseBySkuRepository;
    }

    public function index(){
        $from = date('m/d/Y', strtotime('first day of this month'));
        $to = date('m/d/Y');
        $title = 'Billing Center';
        $colNames = "'Order#','Reference#','PONumber','OrderDate','MITSKU','CustomerSKU','Item','QTY','ProductPrice','ProductTotal','ShippingTotal','ShipName','ShipState','ShipMethod','Status','ShipDate'";

        $colModel = "{name:'OrderNumber',index:'OrderNumber', width:50, align:'center','key':true,sortable:true,formatter:orderDetails},
                {name:'CustomerOrderNumber',index:'O.OrderInst', width:70, align:'center'},
                {name:'PONumber',index:'O.PONumber', width:70, align:'center'},
                {name:'OrderDate',index:'OrderDate', width:50, align:'center'},
		        {name:'MITSKU',index:'Order Details.SKU', width:50, align:'center'},
                {name:'CustomerSKU',index:'Order Details.Option01', width:50, align:'center'},
                {name:'Item',index:'Order Details.Product', width:150, align:'left'},
                {name:'QTY',index:'Order Details.QuantityOrdered', width:40, align:'center'},
                {name:'ProductPrice',index:'Order Details.PricePerUnit', width:50, align:'center',formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                {name:'ProductTotal',index:'O.GrandTotal', width:50, align:'center',formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                {name:'ShippingTotal',index:'O.ShippingTotal', width:50, align:'center',formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                {name:'ShipName',index:'O.ShipName', width:80, align:'left'},
                {name:'ShipState',index:'O.ShipState', width:40, align:'center'},
                {name:'ShipMethod',index:'DateShipped', width:100, align:'center'},
                {name:'Status',index:'O.OrderNumber', width:60, align:'center'},
                {name:'ShipDate',index:'DateShipped', width:50, align:'center'},";

        return view('reports/purchaseBySku',compact('from','to','colNames','colModel','title'));
    }

    public function search(Request $request){
        //grid Elements
        $page     = $request->input('page');
        $limit    = $request->input('rows');
        $sidx     = $request->input('sidx');
        $sord     = $request->input('sord');
        // form Elements
        $search   = $request->get('Search');
        $dateFrom = $request->get('DateFrom');
        $dateTo   = $request->get('DateTo');
        $excel    = $request->get('Excel');

        $fieldsToSearch = ['O.OrderNumber', 'O.PONumber', '[Order Details].[SKU]', '[Order Details].[Option01]', '[Order Details].[Product]', 'O.ShipName'];
        $data = $this->purchaseBySkuRepository->getAll($search,$dateFrom,$dateTo,$fieldsToSearch);

        if (!$excel){
            return response()->json($this->purchaseBySkuRepository->response($data,$page,$limit,$sidx,$sord));
        }else{
            return $this->purchaseBySkuRepository->exportExcel($data,'OrdersFilex','Orders');
        }
    }

}
