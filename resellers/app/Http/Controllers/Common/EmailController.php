<?php

namespace App\Http\Controllers\Common;

use App\Repositories\EmailRepository;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\ResellerPortalOrder;
use \Exception;

class EmailController extends Controller
{
    private $emailRepository;

    public function __construct(EmailRepository $emailRepository)
    {
        $this->emailRepository = $emailRepository;
    }

    public function cancelOrder($omOrder){
      $param = $this->parameters($omOrder);
      $result = $this->emailRepository->sendEmail("emails.cancelOrder",$param,"Order Cancellation - Reseller Portal <ordercancellations@mitechnologiesinc.com>",Auth::user()->fullname."<". Auth::user()->email .">","Order Cancellation - Reseller Portal");
      if($result->http_response_code == 200){
        $output["code"] = 0;
        $output["message"] = "Your special request has been sent. We will answer your email as soon as posible.";
      }
      else{
        $output["code"] = 1;
        $output["message"] = "There was an error processing your request. Please try again";
      }
      return json_encode($output);
    }

    public function parameters($omOrder){
        return [
            'summary'   => $this->emailRepository->getSummary($omOrder),
            'shipData'  => $this->emailRepository->getShipData($omOrder),
            'products'  => $this->emailRepository->getProductsData($omOrder),
            'total'     => $this->emailRepository->getProductsTotal($omOrder),
        ];
    }

    public function sendSpecialRequest(Request $req){
      $messages = [
          'comment.required' => 'At least one SKU is required to ask for a special request',
      ];
      $validator = Validator::make($req->all(),[
        'from' => 'required|email',
        'comment' => 'required'
      ],$messages);

      if($validator->fails()){
        $string = "";
        $errors = $validator->errors();
        foreach($errors->all() as $error){
          $string .= $error."<br/>";
        }
        $output["code"] = 1;
        $output["message"] = $string;
        return json_encode($output);
      }
      $param = [
        'request' => $req->get("comment"),
        'notes' => $req->get('notes')
      ];
      $result =  $this->emailRepository->sendEmail("emails.request",$param,'Special Request <specialrequests@mitechnologiesinc.com>',Auth::user()->fullname."<". $req->get("from") .">","[[Special Request]] ". Auth::user()->fullname ." - ". Carbon::now());
      if($result->http_response_code == 200){
        $output["code"] = 0;
        $output["message"] = "Your special request has been sent. We will answer your email as soon as posible.";
      }
      else{
        $output["code"] = 1;
        $output["message"] = "There was an error processing your request. Please try again";
      }
      return json_encode($output);
    }

    public function sendTestMail(){
      try{
        $order = ResellerPortalOrder::with('resellerPortalOrderDetails')->where('WebOrderNumber',session('webOrderID'))->first();
        $order->ShipFromName = "Testing";
        $order->ShipFromCompany = "Testing";
        $order->ShipFromAddressLine1 = "Testing";
        $order->ShipFromAddressLine2 = "Testing";
        $order->ShipFromCity = "Testing";
        $order->ShipFromState = "Testing";
        $order->ShipFromZipCode = "Testing";
        $order->ShipFromCountry = "Testing";
        $order->ShipToName = "Testing";
        $order->ShipToCompany = "Testing";
        $order->ShipToAddressLine1 = "Testing";
        $order->ShipToAddressLine2 = "Testing";
        $order->ShipToEmail = "Testing";
        $order->ShipToCity = "Testing";
        $order->ShipToState = "Testing";
        $order->ShipToZipCode = "Testing";
        $order->ShipToCountry = "Testing";
        return view('emails.orderEmail',[
          'order' => $order
        ]);
        // $result =  $this->emailRepository->sendEmail("emails.orderEmail",['order' => $order], Auth::user()->fullname . ' <hector.mendoza@mitechnologiesinc.com>',"Resellers Portal Receipts<noreplay@mitechnologiesinc.com>","Order Recipt from Resellers Portal " . Carbon::now()->toFormattedDateString());
        // dd($result);
      }
      catch(\Exception $ex){
        return $ex->getMessage();
      }
    }
}
