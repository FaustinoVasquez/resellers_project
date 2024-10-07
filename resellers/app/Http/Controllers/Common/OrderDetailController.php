<?php

namespace App\Http\Controllers\Common;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\OrderDetailRepository;
use Illuminate\Http\Request;


class OrderDetailController extends Controller
{
    private $orderDetailRepository;

    /**
     * @param OrderDetailRepository $orderDetailRepository
     */
    public function __construct(
                                OrderDetailRepository $orderDetailRepository)
    {
        $this->orderDetailRepository = $orderDetailRepository;
    }

    public function index($orderNumber,$from,Request $request){
        $soldData = $this->orderDetailRepository->getCustomerIdData();
        $shipData = $this->orderDetailRepository->getShipData($orderNumber);
        $products = $this->orderDetailRepository->getProducts($orderNumber);
        $totals   = $this->orderDetailRepository->getTotals($orderNumber);
        $comments = $this->orderDetailRepository->getComments($orderNumber);
        $tracks   = $this->orderDetailRepository->getTrackingInformation($orderNumber);
        $orderStatus = $this->orderStatus();

        if($request->ajax()){
            return response()->json(view('common/orderDetail',
                compact('soldData','shipData','products','totals','comments','tracks','orderStatus','orderNumber','from'))->render());
        }
    }

    /**
     * @param $orderNumber
     * @param $from
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderDetails($orderNumber,$from,Request $request){
        $soldData= $this->customerRepository->getCustomerIdData();
        $shipData= $this->orderRepository->getShipData($orderNumber);
        $products = $this->orderDetailRepository->getProducts($orderNumber);
        $totals= $this->orderRepository->getTotals($orderNumber);
        $comments= $this->orderRepository->getComments($orderNumber);
        $tracks= $this->orderDetailRepository->getTrackingInformation($orderNumber);
        $orderStatus = $this->orderStatus();

        if($request->ajax()){
            return response()->json(view('common/orderDetail',
                compact('soldData','shipData','products','totals','comments','tracks','orderStatus','orderNumber','from'))->render());
        }
    }

    public function getTrackingInformation(Request $req){
      $tracks= $this->orderDetailRepository->getTrackingInformation($req->get('orderNumber'));
      return json_encode($tracks);
    }

    public function orderStatus(){
        return ['' =>'',
            'Shipped'=>'success',
            'Order Approved'=>'active',
            'Pending Shipment'=>'info',
            'Payment Received'=>'warning',
            'Canceled'=>'danger',
            'Order Received' => '#CECEF6',
            'Refunded'=>'#F2F2F2'];
    }
}
