<?php
namespace App\Repositories;

use Validator;
use App\Models\ResellerPortalOrder;
use Auth;
use DB;
use Carbon\Carbon;


class ResellerPortalOrderRepository extends BaseRepository
{
    public function getModel()
    {
        return new ResellerPortalOrder();
    }

    public function putWebOrder(){
        return $this->newQuery()->insert(['ResellerCustomerID'=>auth()->user()->customerid,'ShipToCountry'=>'US']);
    }

    public function getWebOrder(){
        return $this->newQuery()->select('WebOrderNumber')->where('ResellerCustomerID',auth()->user()->customerid)->orderby('WebOrderNumber','desc')->first()->WebOrderNumber;
     //   return $query->WebOrderNumber;
    }

    public function getOrder(){
      return ResellerPortalOrder::where('WebOrderNumber',session('webOrderID'))->first();
    }

    public function setShippingMethod($value){
      ResellerPortalOrder::where('WebOrderNumber',session('webOrderID'))->update(['ShippingMethod' => $value]);
      DB::select(DB::raw('execute inventory.dbo.sp_ResellerPortalOrder_Recal '.session('webOrderID').''));
    }

    public function deleteOrder(){
      if(session('webOrderID')){
        ResellerPortalOrder::where('WebOrderNumber',session('webOrderID'))->delete();
        return true;
      }
      return false;
    }

    public function processOrder($req){
      try{
        $validation = Validator::make($req->all(),[
          'poNumber' => 'required'
        ]);

        $validation->setAttributeNames([
          'poNumber' => 'PO#'
        ]);

        if($validation->fails()){
          $messages = $validation->errors();
          $error = "";
          foreach($messages->all() as $message){
            $error .= $message . "<br/>";
          }
          return back()->with('errorMessage',$error)->withInput();
        }
        $order = ResellerPortalOrder::with("resellerPortalOrderFile")->where('WebOrderNumber',session('webOrderID'))->first();
        if($order->ShippingMethod == "Shipping Label Attached"){
          if($order->resellerPortalOrderFile->count() == 0){
            return back()->with('errorMessage',"There's no label attached to the order. Please upload the shipping label.")->withInput();
          }
        }
        ResellerPortalOrder::with("resellerPortalOrderFile")->where('WebOrderNumber',session('webOrderID'))->update([
          'PONumber' => $req->get('poNumber'),
          'PackingSlipNotes' => $req->get('notes'),
          'InternalNotes' => $req->get('specialNotes')
        ]);
        return redirect('shippingInformation');
      }
      catch(Exception $ex){
        return false;
      }
    }

    public function sendOrder($req){
      $validator = Validator::make($req->all(),[
        "fromFullName" => "required",
        "fromCompany" => "required",
        "fromAddress" => "required",
        "fromCity" => "required",
        "fromState" => "required",
        "fromZip" => "required",
        "fromCountry" => "required",
        "toFullName" => "required",
        "toCompany" => 'required',
        "toAddress" => 'required',
        "toEmail" => 'required|email',
        "toCity" => 'required',
        "toState" => 'required',
        "toZip" => 'required',
        "toCountry" => 'required'
      ]);

      $validator->setAttributeNames([
        "fromFullName" => "From: Full Name",
        "fromCompany" => "From: Company",
        "fromAddress" => "From: Address",
        "fromCity" => "From: City",
        "fromState" => "From: State",
        "fromZip" => "From: Zip Code",
        "fromCountry" => "From: Country",
        "toFullName" => "To: Full Name",
        "toCompany" => "To: Company",
        "toAddress" => "To: Address",
        "toEmail" => "To: Email",
        "toCity" => "To: City",
        "toState" => "To: State",
        "toZip" => "To: Zip Code",
        "toCountry" =>"To: Country"
      ]);

      if($validator->fails()){
        $errors = $validator->errors();
        $message = "";
        foreach($errors->all() as $error){
          $message .= $error."<br/>";
        }
        return back()->with('errorMessage',$message)->withInput();
      }
      else{
        //UPDATE ResellerPortalOrder SET OrderStatus = 1 WHERE WebOrderNumber = {$this->session->userdata('webOrderID')}
        $order = ResellerPortalOrder::with('resellerPortalOrderDetails')->where('WebOrderNumber',session('webOrderID'))->update([
          "ShipFromName" => $req->get("fromFullName"),
          "ShipFromCompany" => $req->get("fromCompany"),
          "ShipFromAddressLine1" => $req->get("fromAddress"),
          "ShipFromAddressLine2" => $req->get("fromAddress2"),
          "ShipFromCity" => $req->get("fromCity"),
          "ShipFromState" => $req->get("fromState"),
          "ShipFromZipCode" => $req->get("fromZip"),
          "ShipFromCountry" => $req->get("fromCountry"),
          "ShipToName" => $req->get("toFullName"),
          "ShipToCompany" => $req->get("toCompany"),
          "ShipToAddressLine1" => $req->get("toAddress"),
          "ShipToAddressLine2" => $req->get("toAddress2"),
          "ShipToEmail" => $req->get("toEmail"),
          "ShipToCity" => $req->get("toCity"),
          "ShipToState" => $req->get("toState"),
          "ShipToZipCode" => $req->get("toZip"),
          "ShipToCountry" => $req->get("toCountry"),
          'OrderStatus' => 1
        ]);
        $order = ResellerPortalOrder::with('resellerPortalOrderDetails')->where('WebOrderNumber',session('webOrderID'))->first();
        try{
          // return view('emails.orderEmail',[
          //   'order' => $order
          // ]);
          $result = $this->sendEmail("emails.orderEmail",['order' => $order], Auth::user()->fullname . ' <' . Auth::user()->email . '>',"Resellers Portal Receipts<noreplay@mitechnologiesinc.com>","Order Receipt from Resellers Portal " . Carbon::now()->toFormattedDateString());
        }
        catch(\Exception $ex){
          session()->forget('webOrderID');
          return redirect('dashboard')->with('successMessage', $ex->getMessage()."There was a problem sending you the receipt to your email, but your order has been processed correctly.");
        }
        session()->forget('webOrderID');
        return redirect('dashboard')->with('successMessage',"The order has been processed.");
      }
    }
}
