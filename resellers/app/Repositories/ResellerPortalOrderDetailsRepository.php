<?php
namespace App\Repositories;
use App\Models\ResellerPortalOrderDetails;
use Illuminate\Support\Facades\DB;
use Auth;


class ResellerPortalOrderDetailsRepository extends BaseRepository
{
    public function getModel()
    {
        return new ResellerPortalOrderDetails();
    }


    public function checkDuplicate($sku){
      // $SQL = "select count(itemnumber) as count
      //            from ResellerPortalOrderDetails
      //            where (ItemLocalSku ='{$sku}')
      //            and (WebOrderNumber = {$this->session->userdata('webOrderID')})";
      $total = 0;

      if(session('webOrderID')){
        $total = $this->newQuery()->where('ItemLocalSKU',$sku)->where('WebOrderNumber',session('webOrderID'))->count();
      }
      if ($total > 0) {
        // $SQL = "execute sp_ResellerPortalOrderDetails_AddItems {$this->session->userdata('webOrderID')}, '{$sku}', 1";
        // $this->MCommon->executeQuery($SQL);
        DB::select(DB::raw("execute inventory.dbo.sp_ResellerPortalOrderDetails_AddItems ".session('webOrderID').", '".$sku."', 1"));
        return 1;//duplicado
      }
      return 0;
    }


    public function updateCart($row){
      //self::getCustomerSku($row->get('SKU'));
      $cart = new ResellerPortalOrderDetails;
      $cart->ItemLocalSKU = $row->get('SKU');
      $cart->ItemTitle = $row->get('PartNumber')." ".$row->get('Category');
      $cart->ItemUnitPrice = $row->get('Price');
      $cart->ItemUnitShipping = $row->get('DSEconomy');
      $cart->DSEconomyPrice = $row->get('DSEconomy');
      $cart->DS2ndDayPrice = $row->get('DS2ndDay');
      $cart->DS1DayPrice = $row->get('DSOvernight');
      $cart->ItemQuantityOrdered = 1;
      $cart->ItemTotalShipping = ($row->get('DSEconomy') * 1);
      $cart->ItemTotal = ($row->get('Price') * 1) + ($row->get('DSEconomy') * 1);
      $cart->ResellerCustomerID = Auth::id();
      $cart->WebOrderNumber = session('webOrderID');
      $cart->CustomerSKU = "inventory.dbo.fn_Catalog_2_SKU('" + $row->get('SKU') + "')";
      $cart->save();
      DB::select(DB::raw('execute inventory.dbo.sp_ResellerPortalOrder_Recal '.session('webOrderID').''));
      $output["status"] = true;
      $output["validation"] = 1;
      return $output;
    }

    public function deleteCart(){
      try{
        if(session('webOrderID')){
          ResellerPortalOrderDetails::where('WebOrderNumber',session('webOrderID'))->delete();
          session()->forget('webOrderID');
          $return["message"] = "The order has been successfully deleted.";
          $return["code"] = 0;
          return $return;
        }
        else{
          $return["message"] = "You have no order to delete.";
          $return["code"] = 1;
          return $return;
        }
      }
      catch(Exception $ex){
        $return["message"] = "There was an error deleting the order. Please try again.";
        $return["code"] = 1;
        return $return;
      }
    }

    public function getCustomerSku($sku){
      $result = DB::select(DB::raw("inventory.dbo.fn_Catalog_2_SKU(" . $sku . ")"));
      echo $result;
    }

    public function getCartPreview(){
      if(session('webOrderID')){
        $cart = ResellerPortalOrderDetails::where('WebOrderNumber',session('webOrderID'))->take(5)->get();
        return $cart;
      }
      return false;
    }

    public function getCount(){
      if(session('webOrderID')){
        $total = ResellerPortalOrderDetails::where('WebOrderNumber',session('webOrderID'))->count();
        return $total;
      }
      return 0;
    }

    public function getShoppingCart(){
      if(session('webOrderID')){
        $cart = ResellerPortalOrderDetails::where('WebOrderNumber',session('webOrderID'))->get();
        return $cart;
      }
      return false;
    }

    public function deleteItem($itemId){
      try{
        ResellerPortalOrderDetails::where('webOrderNumber',session('webOrderID'))->where('Id',$itemId)->delete();
        DB::select(DB::raw('execute inventory.dbo.sp_ResellerPortalOrder_Recal '.session('webOrderID').''));
        return true;
      }
      catch(Exception $ex){
        return false;
      }
    }

    public function updateQuantity($itemId,$value){
      if(session('webOrderID')){
        $cart = ResellerPortalOrderDetails::where('webOrderNumber',session('webOrderID'))->where('Id',$itemId)->first();
        $cart->ItemQuantityOrdered = $value;
        $cart->ItemTotalShipping = $cart->ItemUnitShipping * $value;
        $cart->ItemTotal = ($cart->ItemUnitPrice * $value) + ($cart->ItemUnitShipping * $value);
        $cart->save();
        DB::select(DB::raw('execute inventory.dbo.sp_ResellerPortalOrder_Recal '.session('webOrderID').''));
        $cart = ResellerPortalOrderDetails::where('webOrderNumber',session('webOrderID'))->where('Id',$itemId)->first();
        return $cart;
      }
        return false;
    }
}
