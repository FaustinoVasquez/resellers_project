<?php

namespace App\Http\Controllers\OrderManagement;

use App\Repositories\BillingCenterRepository;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;


class BillingCenterController extends Controller
{
    /**
     * @var OrderRepository
     */
    private $billingCenterRepository;

    public function __construct(BillingCenterRepository $billingCenterRepository)
    {
        $this->billingCenterRepository = $billingCenterRepository;
    }

    public function index(){
        $from = date('m/d/Y', strtotime('-1 week'));
        $to = date('m/d/Y');
        $title = 'Billing Center';
        $colNames = "'Order#','Tracking','Reference#','OrderStatus','PO Number','Ship Name','Order Date','Order Total','Paid Amount','Balance Due','DaysOld','OrderInstructions'";

        $colModel = "{name:'OrderNumber',index:'OrderNumber',  align:'center','key':true,sortable:true,formatter:orderDetails},
                     {name:'Tracking',index:'Tracking',  align:'center',formatter:showCellTrk},
                     {name:'Reference',index:'SourceOrderID',  align:'center',formatter:reference},
                     {name:'OrderStatus',index:'OrderStatus',  align:'center',cellattr:orderStatus,formatter:orderStatusInfo},
                     {name:'PONumber',index:'PONumber',  align:'center'},
                     {name:'ShipName',index:'ShipName', align:'center',formatter:capitalize},
                     {name:'OrderDate',index:'OrderDate',  align:'center',formatter:'date',formatoptions: {newformat:'F j, Y'}},
                     {name:'OrderTotal',index:'GrandTotal',  align:'right',formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                     {name:'PaidAmount',index:'OrderManager.dbo.fn_Sum_Amount(OrderNumber)',  align:'right',formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                     {name:'BalanceDue',index:'BalanceDue',  align:'right',cellattr:balanceDue,formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                     {name:'DaysOld',index:'OrderDate',  align:'center'},
                     {name:'orderinst',index:'OrderInst',  align:'center',formatter:showCellInfo}";

        $status = [0 => 'All',1=>'Paid in Full',2=>'Balance Due',3=>'Credit Due'];
        return view('orderManagement/bc/billingCenter',compact('from','to','status','colNames','colModel','title'));
    }

    public function search(Request $request){
        //grid Elements
        $page     = $request->input('page');
        $limit    = $request->input('rows');
        $sidx     = $request->input('sidx');
        $sord     = $request->input('sord');
        // form Elements
        $search   = $request->get('Search');
        $status   = $request->get('Status');
        $dateFrom = $request->get('DateFrom');
        $dateTo   = $request->get('DateTo');
        $excel    = $request->get('Excel');

        $fieldsToSearch = ['OrderNumber', 'SourceOrderID', 'PONumber', 'OrderStatus', 'Shipping', 'ShipName'];
        $data = $this->billingCenterRepository->allOrders($search,$status,$dateFrom,$dateTo,$fieldsToSearch,$excel);

        if (!$excel){
            return response()->json($this->billingCenterRepository->response($data,$page,$limit,$sidx,$sord));
        }else{
            return $this->billingCenterRepository->exportExcel($data,'OrdersFile','Orders');
        }
    }

}
