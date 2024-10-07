<?php

namespace App\Http\Controllers\OrderManagement;

use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

use stdClass;

class OrderManagerController extends Controller
{
    /**
     * @var OrderRepository
     */
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function index(){
        $from = date('m/d/Y', strtotime('-1 week'));
        $to = date('m/d/Y');
        $colNames = "'Order#','Tracking', 'Reference', 'PO Number', 'Order Date', 'Order Status','Ship To', 'Order Instructions'";
        $colModel = "{name:'OrderNumber',index:'OrderNumber',key: true,align:'center',sortable:true,formatter:orderDetails},
                            {name:'Tracking',index:'Tracking', align:'center',formatter:showCellTrk},
                            {name:'SourceOrderID',index:'SourceOrderID',align:'center',formatter:reference},
                            {name:'PONumber',index:'PONumber', align:'center'},
                            {name:'OrderDate',index:'OrderDate',align:'center',formatter:'date',formatoptions: {newformat:'F j, Y'}},
                            {name:'OrderStatus',index:'OrderStatus',align:'center',cellattr:orderStatus, formatter:orderStatusInfo},
                            {name:'ShipName',index:'ShipName',lign:'center',formatter:capitalize},
                            {name:'OrderInst',index:'OrderInst',align:'center',formatter:showCellInfo}";

        $status = [0 => 'All',1=>'Shipped',2=>'Pending'];
        return view('orderManagement/om/orderManager',compact('from','to','status','colNames','colModel'));
    }

    public function search(Request $request){
        //grid Elements
        $page     = $request->get('page');
        $limit    = $request->get('rows');
        $sidx     = $request->get('sidx');
        $sord     = $request->get('sord');
        // form Elements
        $search   = $request->get('Search');
        $status   = $request->get('Status');
        $dateFrom = $request->get('DateFrom');
        $dateTo   = $request->get('DateTo');
        $excel    = $request->get('Excel');
        $csv      = $request->get('Csv');


        $fieldsToSearch = ['OrderNumber', 'SourceOrderID', 'PONumber', 'OrderStatus', 'Shipping', 'ShipName'];
        $data = $this->orderRepository->allOrders($dateFrom,$dateTo,$status,$search,$fieldsToSearch,$excel,$csv);

        if (!$excel && !$csv){
            return response()->json($this->orderRepository->response($data,$page,$limit,$sidx,$sord));
        }elseif($csv){
            $orders = $data->get();
            $string = "";
            foreach($orders as $order){
              $order = $order->toArray();
              foreach($order as $key => $data){
                if($key == "Tracking"){
                  $string .= '="'.$data.'"'."\t";
                }
                else
                  $string .= $data."\t";
              }
              $string .= "\n";
            }
            return response($string)->header('Content-Type', "Content-Type:   application/vnd.ms-excel; charset=utf-8")->header('Content-Disposition',"attachment; filename=OrderReport.xls");
        }else{
            return $this->orderRepository->exportExcel($data,'OrdersFile','Orders');
        }
    }
}
