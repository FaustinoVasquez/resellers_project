<?php

namespace App\Http\Controllers\Common;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\CustomerRepository;
use App\Repositories\ProductCatalogRepository;
use App\Repositories\ResellerPortalOrderRepository;
use App\Repositories\ResellerPortalOrderDetailsRepository;
use App\Models\ResellerPortalOrderFile;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Mail;


class CartController extends Controller
{
    private $customerRepository;
    private $resellerPortalOrderRepository;
    private $resellerPortalOrderDetailsRepository;
    private $productCatalogRepository;

    public function __construct(CustomerRepository $customerRepository,
                                ResellerPortalOrderRepository $resellerPortalOrderRepository,
                                ResellerPortalOrderDetailsRepository $resellerPortalOrderDetailsRepository,
                                ProductCatalogRepository $productCatalogRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->resellerPortalOrderRepository = $resellerPortalOrderRepository;
        $this->resellerPortalOrderDetailsRepository = $resellerPortalOrderDetailsRepository;
        $this->productCatalogRepository = $productCatalogRepository;
    }


    //This will be used to hash all the passwords on the DB in the future. For now is commented.
    public function hashUser(){
      //select user_type,password, passwordnet,CartID,customerid from users where customerid <> 0 and CartID <>0
      $users = User::whereIn('id',[226])->get();
      foreach($users as $user){
        echo "id: ".$user->id."<br/>";
        echo "fullname: ".$user->fullname."<br/>";
        echo "password: ".$user->password."<br/>";
        echo "user Type: ".$user->user_type.'<br/>';
        echo "passwordnet: " . $user->passwordnet.'<br/>';
        echo "CartID: " . $user->CartID.'<br/>';
        echo "customerid: " . $user->customerid.'<br/>';
        echo "<hr/>";
        $user->passwordnet = bcrypt($user->password);
        $user->save();
      }
      echo "<hr/><hr/>";
      foreach($users as $user){
        echo "id: ".$user->id."<br/>";
        echo "fullname: ".$user->fullname."<br/>";
        echo "password: ".$user->password."<br/>";
        echo "user Type: ".$user->user_type.'<br/>';
        echo "passwordnet: " . $user->passwordnet.'<br/>';
        echo "CartID: " . $user->CartID.'<br/>';
        echo "customerid: " . $user->customerid.'<br/>';
        echo "<hr/>";
      }
    }

    public function index($sku,Request $request){

        $this->createWebOrderId($request);
        $webOrder = $request->session()->get('webOrderID');
        $shipFromInformation = $this->customerRepository->getCustomerIdData();

        $colNames = "'ID','Number','LocalSKU', 'Title', 'UnitPrice','UnitShipping','QuantityOrdered','TotalShipping','Total','CustomerSKU','ResellerCustomerID'";

        $colModel = "{name:'Id',index:'Id', width:70,hidden:true},
                     {name:'ItemNumber',index:'ItemNumber', width:70 ,hidden:true},
                     {name:'ItemLocalSKU',index:'ItemLocalSKU', width:80},
                     {name:'ItemTitle',index:'ItemTitle', width:250, editable:true},
                     {name:'ItemUnitPrice',index:'ItemUnitPrice', width:80, align:'right',formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                     {name:'ItemUnitShipping',index:'ItemUnitShipping', width:90 , align:'right',formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                     {name:'ItemQuantityOrdered',index:'ItemQuantityOrdered', width:80,align:'center',editable:true},
                     {name:'ItemTotalShipping',index:'ItemTotalShipping', width:80, align:'right',formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                     {name:'ItemTotal',index:'ItemTotal', width:80, align:'right',formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                     {name:'CustomerSKU',index:'CustomerSKU', width:90,hidden:true},
                     {name:'ResellerCustomerID',index:'ResellerCustomerID', width:80 ,hidden:true}";


        if($request->ajax()){
            return response()->json(view('common/cart',
                compact('shipFromInformation','sku','webOrder','colNames','colModel'))->render());
        }
        return view('common/cart', compact('shipFromInformation','sku','webOrder','colNames','colModel'));
    }

    public function getCart($sku,Request $request){

        $this->createWebOrderId($request);
        $webOrder = $request->session()->get('webOrderID');
        $shipFromInformation = $this->customerRepository->getCustomerIdData();

        $colNames = "'ID','Number','LocalSKU', 'Title', 'UnitPrice','UnitShipping','QuantityOrdered','TotalShipping','Total','CustomerSKU','ResellerCustomerID'";

        $colModel = "{name:'Id',index:'Id', width:70,hidden:true},
                     {name:'ItemNumber',index:'ItemNumber', width:70 ,hidden:true},
                     {name:'ItemLocalSKU',index:'ItemLocalSKU', width:80},
                     {name:'ItemTitle',index:'ItemTitle', width:250, editable:true},
                     {name:'ItemUnitPrice',index:'ItemUnitPrice', width:80, align:'right',formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                     {name:'ItemUnitShipping',index:'ItemUnitShipping', width:90 , align:'right',formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                     {name:'ItemQuantityOrdered',index:'ItemQuantityOrdered', width:80,align:'center',editable:true},
                     {name:'ItemTotalShipping',index:'ItemTotalShipping', width:80, align:'right',formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                     {name:'ItemTotal',index:'ItemTotal', width:80, align:'right',formatter:'currency', formatoptions:{decimalSeparator:'.', thousandsSeparator: ',', decimalPlaces: 2, prefix: '$ '}},
                     {name:'CustomerSKU',index:'CustomerSKU', width:90,hidden:true},
                     {name:'ResellerCustomerID',index:'ResellerCustomerID', width:80 ,hidden:true}";


        if($request->ajax()){
            return response()->json(view('common/cart',
                compact('shipFromInformation','sku','webOrder','colNames','colModel'))->render());
        }
        return view('common/cart', compact('shipFromInformation','sku','webOrder','colNames','colModel'));
    }


    public function createWebOrderId(Request $request){

        if (!$request->session()->has('webOrderID')) {
            $this->resellerPortalOrderRepository->putWebOrder();
            $webOrderId= $this->resellerPortalOrderRepository->getWebOrder();
            $request->session()->put('webOrderID', $webOrderId);
        }
        return "false";
    }



    public function removeSession(){
       // $request->session()->forget('key');
    }


    public function saveOrder(Request $req){
      $response = $this->resellerPortalOrderDetailsRepository->updateCart($req);
      if($response["status"]){
        return json_encode($response);
      }else{
        $output["validation"] = 0;
        return json_encode($output);
      }
    }

    public function validateInStock(Request $request){
        $sku = $request->get('sku');
        $available = $this->productCatalogRepository->getIfSkuIsAvailable($sku);
        $array = [
          'validation' => $available
        ];
        if($request->ajax()){
            return json_encode($array);
        }
    }

    public function validateDuplicity(Request $req) {
    	$sku = $req->get('sku');
      $repeated = $this->resellerPortalOrderDetailsRepository->checkDuplicate($sku);
      $array = [
        'validation' => $repeated
      ];
      return json_encode($array);
    }

    public function getCartPreview(){
      $cart = $this->resellerPortalOrderDetailsRepository->getCartPreview();
      return view('partials.cart',[
        'cart' => $cart
      ]);
    }

    public function getCartCount(){
      $total = $this->resellerPortalOrderDetailsRepository->getCount();
      return $total;
    }

    public function deleteCart(){
      $returned = $this->resellerPortalOrderRepository->deleteOrder();
      if($returned){
        $output = $this->resellerPortalOrderDetailsRepository->deleteCart();
      }
      else{
        $output["code"] = 3;
        $output["message"] = "You have no order to delete.";
      }
      return json_encode($output);
    }

    public function checkout(){
      $cart = $this->resellerPortalOrderDetailsRepository->getShoppingCart();
      if($cart && $cart->count() > 0){
        $order = $cart[0]->resellerPortalOrder;
        return view("checkout.checkout",[
          'cart' => $cart,
          'order' => $order
        ]);
      }
      else{
        return redirect("/");
      }
    }

    public function deleteSingleItem(Request $req){
      $itemId = $req->get('itemId');
      if(session('webOrderID')){
        $state = $this->resellerPortalOrderDetailsRepository->deleteItem($itemId);
        if($state){
          $output["message"] = "Item deleted from your cart.";
          $output["code"] = 0;
          return json_encode($output);
        }
        else{
          $output["message"] = "The item wasn't found in your shopping cart.";
          $output["code"] = 1;
          return json_encode($output);
        }
      }
      $output["message"] = "There's no session active in this moment, please log back in.";
      $output["code"] = 2;
      return json_encode($output);
    }

    public function changeShipping(Request $req){
      $cart = false;
      if(session('webOrderID')){
        $this->resellerPortalOrderRepository->setShippingMethod($req->get('ShippingMethod'));
        $cart = $this->resellerPortalOrderDetailsRepository->getShoppingCart();
      }
      return view('checkout.current',[
        'cart' => $cart,
      ]);
    }

    public function updateQuantity(Request $req){
      $item = $this->resellerPortalOrderDetailsRepository->updateQuantity($req->get('itemId'),$req->get('quantity'));
      if(!$item){
        $output['message'] = "There was an error procesing your request. Please try again.";
        $output['code'] = 1;
        return json_encode($output);
      }
      $output['item'] = $item;
      $output['code'] = 0;
      return json_encode($output);
    }

    public function uploadShippingLabel(Request $req){
      $output = array();
      if(!$req->hasFile('shippingLabel')){
        $output["code"] = 1;
        $output["message"] = "You need to select a file containing your shipping label to upload.";
        return json_encode($output);
      }
      foreach($req->file("shippingLabel") as $file){
        if(!$file->isValid()){
          $output["code"] = 2;
          $output["message"] = "The file ". $file->getClientOriginalName() ." is not valid. Please, try again.";
          return json_encode($output);
        }
      }
      if(!session('webOrderID')){
        $output["code"] = 3;
        $output["message"] = "You don't have a pending order.";
        return json_encode($output);
      }
      try{
        $destinationPath = storage_path()."/uploads/".Auth::id()."/".session('webOrderID');
        foreach($req->file("shippingLabel") as $uploadedFile){
          $fileName = str_random("32").".".$uploadedFile->getClientOriginalExtension();
          $uploadedFile->move($destinationPath,$fileName);
          $file = new ResellerPortalOrderFile;
          $file->WebOrderNumber = session('webOrderID');
          $file->url = $destinationPath."/".$fileName;
          $file->file_name = $uploadedFile->getClientOriginalName();
          $file->file_type = $uploadedFile->getClientOriginalExtension();
          $file->save();
        }
        $output["code"] = 0;
        $output["message"] = "Your shipping label was uploaded successfully.";
        return json_encode($output);
      }
      catch(Exception $ex){
        $output["code"] = 4;
        $output["message"] = "There was an error uploading the shipping labels. Pleas, try again.";
        $output["error"] = $ex->getMessage();
        return json_encode($output);
      }
    }

    public function processOrder(Request $req){
      if(session('webOrderID')){
        return $this->resellerPortalOrderRepository->processOrder($req);
      }
      else{
        return back()->with('errorMessage',"You don't have a pending order.");
      }
    }

    public function shippingInformation(){
      if(session('webOrderID')){
        $countryArray = self::getCountries();
        $order = $this->resellerPortalOrderRepository->getOrder();
        return view("checkout.shippingInformation",[
          'countriesSelect' => $countryArray,
          'order' => $order
        ]);
      }
      else{
        return back()->with('errorMessage','There is no pending order.');
      }
    }

    public function sendOrder(Request $req){
      if(session('webOrderID')){
        return $this->resellerPortalOrderRepository->sendOrder($req);
      }
      else{
        return back()->with('errorMessage',"There's no pending order");
      }
    }

    private function getCountries(){
      return ' <option value="US" selected>United States</option>
		<option value="AF">Afghanistan</option>
            	<option value="AX">Åland Islands</option>
            	<option value="AL">Albania</option>
            	<option value="DZ">Algeria</option>
            	<option value="AS">American Samoa</option>
            	<option value="AD">Andorra</option>
            	<option value="AO">Angola</option>
            	<option value="AI">Anguilla</option>
            	<option value="AQ">Antarctica</option>
            	<option value="AG">Antigua and Barbuda</option>
            	<option value="AR">Argentina</option>
            	<option value="AM">Armenia</option>
            	<option value="AW">Aruba</option>
            	<option value="AU">Australia</option>
            	<option value="AT">Austria</option>
            	<option value="AZ">Azerbaijan</option>
            	<option value="BS">Bahamas</option>
            	<option value="BH">Bahrain</option>
            	<option value="BD">Bangladesh</option>
            	<option value="BB">Barbados</option>
            	<option value="BY">Belarus</option>
            	<option value="BE">Belgium</option>
            	<option value="BZ">Belize</option>
            	<option value="BJ">Benin</option>
            	<option value="BM">Bermuda</option>
            	<option value="BT">Bhutan</option>
            	<option value="BO">Bolivia, Plurinational State of</option>
            	<option value="BQ">Bonaire, Sint Eustatius and Saba</option>
            	<option value="BA">Bosnia and Herzegovina</option>
            	<option value="BW">Botswana</option>
            	<option value="BV">Bouvet Island</option>
            	<option value="BR">Brazil</option>
            	<option value="IO">British Indian Ocean Territory</option>
            	<option value="BN">Brunei Darussalam</option>
            	<option value="BG">Bulgaria</option>
            	<option value="BF">Burkina Faso</option>
            	<option value="BI">Burundi</option>
            	<option value="KH">Cambodia</option>
            	<option value="CM">Cameroon</option>
            	<option value="CA">Canada</option>
            	<option value="CV">Cape Verde</option>
            	<option value="KY">Cayman Islands</option>
            	<option value="CF">Central African Republic</option>
            	<option value="TD">Chad</option>
            	<option value="CL">Chile</option>
            	<option value="CN">China</option>
            	<option value="CX">Christmas Island</option>
            	<option value="CC">Cocos (Keeling) Islands</option>
            	<option value="CO">Colombia</option>
            	<option value="KM">Comoros</option>
            	<option value="CG">Congo</option>
            	<option value="CD">Congo, the Democratic Republic of the</option>
            	<option value="CK">Cook Islands</option>
            	<option value="CR">Costa Rica</option>
            	<option value="CI">Côte d\'Ivoire</option>
            	<option value="HR">Croatia</option>
            	<option value="CU">Cuba</option>
            	<option value="CW">Curaçao</option>
            	<option value="CY">Cyprus</option>
            	<option value="CZ">Czech Republic</option>
            	<option value="DK">Denmark</option>
            	<option value="DJ">Djibouti</option>
            	<option value="DM">Dominica</option>
            	<option value="DO">Dominican Republic</option>
            	<option value="EC">Ecuador</option>
            	<option value="EG">Egypt</option>
            	<option value="SV">El Salvador</option>
            	<option value="GQ">Equatorial Guinea</option>
            	<option value="ER">Eritrea</option>
            	<option value="EE">Estonia</option>
            	<option value="ET">Ethiopia</option>
            	<option value="FK">Falkland Islands (Malvinas)</option>
            	<option value="FO">Faroe Islands</option>
            	<option value="FJ">Fiji</option>
            	<option value="FI">Finland</option>
            	<option value="FR">France</option>
            	<option value="GF">French Guiana</option>
            	<option value="PF">French Polynesia</option>
            	<option value="TF">French Southern Territories</option>
            	<option value="GA">Gabon</option>
            	<option value="GM">Gambia</option>
            	<option value="GE">Georgia</option>
            	<option value="DE">Germany</option>
            	<option value="GH">Ghana</option>
            	<option value="GI">Gibraltar</option>
            	<option value="GR">Greece</option>
            	<option value="GL">Greenland</option>
            	<option value="GD">Grenada</option>
            	<option value="GP">Guadeloupe</option>
            	<option value="GU">Guam</option>
            	<option value="GT">Guatemala</option>
            	<option value="GG">Guernsey</option>
            	<option value="GN">Guinea</option>
            	<option value="GW">Guinea-Bissau</option>
            	<option value="GY">Guyana</option>
            	<option value="HT">Haiti</option>
            	<option value="HM">Heard Island and McDonald Islands</option>
            	<option value="VA">Holy See (Vatican City State)</option>
            	<option value="HN">Honduras</option>
            	<option value="HK">Hong Kong</option>
            	<option value="HU">Hungary</option>
            	<option value="IS">Iceland</option>
            	<option value="IN">India</option>
            	<option value="ID">Indonesia</option>
            	<option value="IR">Iran, Islamic Republic of</option>
            	<option value="IQ">Iraq</option>
            	<option value="IE">Ireland</option>
            	<option value="IM">Isle of Man</option>
            	<option value="IL">Israel</option>
            	<option value="IT">Italy</option>
            	<option value="JM">Jamaica</option>
            	<option value="JP">Japan</option>
            	<option value="JE">Jersey</option>
            	<option value="JO">Jordan</option>
            	<option value="KZ">Kazakhstan</option>
            	<option value="KE">Kenya</option>
            	<option value="KI">Kiribati</option>
            	<option value="KP">Korea, Democratic People\'s Republic of</option>
            	<option value="KR">Korea, Republic of</option>
            	<option value="KW">Kuwait</option>
            	<option value="KG">Kyrgyzstan</option>
            	<option value="LA">Lao People\'s Democratic Republic</option>
            	<option value="LV">Latvia</option>
            	<option value="LB">Lebanon</option>
            	<option value="LS">Lesotho</option>
            	<option value="LR">Liberia</option>
            	<option value="LY">Libya</option>
            	<option value="LI">Liechtenstein</option>
            	<option value="LT">Lithuania</option>
            	<option value="LU">Luxembourg</option>
            	<option value="MO">Macao</option>
            	<option value="MK">Macedonia, the former Yugoslav Republic of</option>
            	<option value="MG">Madagascar</option>
            	<option value="MW">Malawi</option>
            	<option value="MY">Malaysia</option>
            	<option value="MV">Maldives</option>
            	<option value="ML">Mali</option>
            	<option value="MT">Malta</option>
            	<option value="MH">Marshall Islands</option>
            	<option value="MQ">Martinique</option>
            	<option value="MR">Mauritania</option>
            	<option value="MU">Mauritius</option>
            	<option value="YT">Mayotte</option>
            	<option value="MX">Mexico</option>
            	<option value="FM">Micronesia, Federated States of</option>
            	<option value="MD">Moldova, Republic of</option>
            	<option value="MC">Monaco</option>
            	<option value="MN">Mongolia</option>
            	<option value="ME">Montenegro</option>
            	<option value="MS">Montserrat</option>
            	<option value="MA">Morocco</option>
            	<option value="MZ">Mozambique</option>
            	<option value="MM">Myanmar</option>
            	<option value="NA">Namibia</option>
            	<option value="NR">Nauru</option>
            	<option value="NP">Nepal</option>
            	<option value="NL">Netherlands</option>
            	<option value="NC">New Caledonia</option>
            	<option value="NZ">New Zealand</option>
            	<option value="NI">Nicaragua</option>
            	<option value="NE">Niger</option>
            	<option value="NG">Nigeria</option>
            	<option value="NU">Niue</option>
            	<option value="NF">Norfolk Island</option>
            	<option value="MP">Northern Mariana Islands</option>
            	<option value="NO">Norway</option>
            	<option value="OM">Oman</option>
            	<option value="PK">Pakistan</option>
            	<option value="PW">Palau</option>
            	<option value="PS">Palestinian Territory, Occupied</option>
            	<option value="PA">Panama</option>
            	<option value="PG">Papua New Guinea</option>
            	<option value="PY">Paraguay</option>
            	<option value="PE">Peru</option>
            	<option value="PH">Philippines</option>
            	<option value="PN">Pitcairn</option>
            	<option value="PL">Poland</option>
            	<option value="PT">Portugal</option>
            	<option value="PR">Puerto Rico</option>
            	<option value="QA">Qatar</option>
            	<option value="RE">Réunion</option>
            	<option value="RO">Romania</option>
            	<option value="RU">Russian Federation</option>
            	<option value="RW">Rwanda</option>
            	<option value="BL">Saint Barthélemy</option>
            	<option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
            	<option value="KN">Saint Kitts and Nevis</option>
            	<option value="LC">Saint Lucia</option>
            	<option value="MF">Saint Martin (French part)</option>
            	<option value="PM">Saint Pierre and Miquelon</option>
            	<option value="VC">Saint Vincent and the Grenadines</option>
            	<option value="WS">Samoa</option>
            	<option value="SM">San Marino</option>
            	<option value="ST">Sao Tome and Principe</option>
            	<option value="SA">Saudi Arabia</option>
            	<option value="SN">Senegal</option>
            	<option value="RS">Serbia</option>
            	<option value="SC">Seychelles</option>
            	<option value="SL">Sierra Leone</option>
            	<option value="SG">Singapore</option>
            	<option value="SX">Sint Maarten (Dutch part)</option>
            	<option value="SK">Slovakia</option>
            	<option value="SI">Slovenia</option>
            	<option value="SB">Solomon Islands</option>
            	<option value="SO">Somalia</option>
            	<option value="ZA">South Africa</option>
            	<option value="GS">South Georgia and the South Sandwich Islands</option>
            	<option value="SS">South Sudan</option>
            	<option value="ES">Spain</option>
            	<option value="LK">Sri Lanka</option>
            	<option value="SD">Sudan</option>
            	<option value="SR">Suriname</option>
            	<option value="SJ">Svalbard and Jan Mayen</option>
            	<option value="SZ">Swaziland</option>
            	<option value="SE">Sweden</option>
            	<option value="CH">Switzerland</option>
            	<option value="SY">Syrian Arab Republic</option>
            	<option value="TW">Taiwan, Province of China</option>
            	<option value="TJ">Tajikistan</option>
            	<option value="TZ">Tanzania, United Republic of</option>
            	<option value="TH">Thailand</option>
            	<option value="TL">Timor-Leste</option>
            	<option value="TG">Togo</option>
            	<option value="TK">Tokelau</option>
            	<option value="TO">Tonga</option>
            	<option value="TT">Trinidad and Tobago</option>
            	<option value="TN">Tunisia</option>
            	<option value="TR">Turkey</option>
            	<option value="TM">Turkmenistan</option>
            	<option value="TC">Turks and Caicos Islands</option>
            	<option value="TV">Tuvalu</option>
            	<option value="UG">Uganda</option>
            	<option value="UA">Ukraine</option>
            	<option value="AE">United Arab Emirates</option>
            	<option value="GB">United Kingdom</option>
            	<option value="UM">United States Minor Outlying Islands</option>
            	<option value="UY">Uruguay</option>
            	<option value="UZ">Uzbekistan</option>
            	<option value="VU">Vanuatu</option>
            	<option value="VE">Venezuela, Bolivarian Republic of</option>
            	<option value="VN">Viet Nam</option>
            	<option value="VG">Virgin Islands, British</option>
            	<option value="VI">Virgin Islands, U.S.</option>
            	<option value="WF">Wallis and Futuna</option>
            	<option value="EH">Western Sahara</option>
            	<option value="YE">Yemen</option>
            	<option value="ZM">Zambia</option>
            	<option value="ZW">Zimbabwe</option>';
    }
}
