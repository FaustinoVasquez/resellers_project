<?php

namespace App\Http\Controllers\Settings;

use App\Repositories\CustomerSpecificShipMappingRepository;
use App\Repositories\ProductCatalogRepository;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Hash;


class SettingsController extends Controller
{
  public function updateInfo(Request $req,$type){
    switch($type){
      case "updateAccount":
        $result = self::updatePersonalInfo($req);
        break;
      case "updateShippingAccounts":
        $result = self::updateShippingInfo($req);
        break;
      case "updatePassword":
        $result = self::updatePassword($req);
        break;
    }
    return $result;
  }

  private function updatePassword($req){
    Validator::extend('hash', function($attribute, $value, $parameters, $validator) {
       return Hash::check($value,auth()->user()->passwordnet);
    });
    $rules = [
      "OldPassword" => "required|hash",
      "NewPassword" => "required",
      "ConfirmPassword" => "required|same:NewPassword"
    ];
    $result = self::validateInfo($req,$rules);
    if($result["code"] == 0){
      $user = auth()->user();
      $user->password = $req->get("NewPassword");
      $user->passwordnet = bcrypt($req->get("NewPassword"));
      $user->save();
      $result["message"] = "You password has been updated.";
    }
    return json_encode($result);
  }

  private function updateShippingInfo($req){
    $user = auth()->user();
    $user->CustomerUPSAccount = $req->get("UPSAccount");
    $user->CustomerFEDEXAccount = $req->get("FEDEXAccount");
    $user->CustomerDHLAccount = $req->get("DHLAccount");
    $user->save();
    $result["code"] = 0;
    $result["message"] = "Your shipping information has been updated.";
    return json_encode($result);
  }

  private function updatePersonalInfo($req){
    $rules = [
      "fullname" => "required",
      "email" => "required"
    ];
    $result = self::validateInfo($req,$rules);
    if($result["code"] == 0){
      $user = auth()->user();
      $user->fullname = $req->get("fullname");
      $user->position = $req->get("position");
      $user->email = $req->get("email");
      $user->save();
      $result["message"] = "Your personal information has been updated.";
    }
    return json_encode($result);
  }

  private function validateInfo($req,$rules){
    $validator = Validator::make($req->all(),$rules);
    $string = "";
    if($validator->fails()){
      $messages = $validator->errors();
      foreach($messages->all() as $message){
        $string .= $message . "<br/>";
      }
      $output["code"] = 1;
      $output["message"] = $string;
      return $output;
    }
    $output["code"] = 0;
    $output["message"] = "";
    return $output;
  }
}
