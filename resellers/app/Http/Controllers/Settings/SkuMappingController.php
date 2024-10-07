<?php

namespace App\Http\Controllers\Settings;

use App\Repositories\CustomerSpecificSKUMappingRepository;
use App\Repositories\ProductCatalogRepository;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Validator;


class skuMappingController extends Controller
{
    /**
     * @var CustomerSpecificSKUMappingRepository
     */
    private $customerSpecificSKUMappingRepository;
    private $productCatalogRepository;

    public function __construct(CustomerSpecificSKUMappingRepository $customerSpecificSKUMappingRepository,
                                ProductCatalogRepository $productCatalogRepository)
    {
        $this->customerSpecificSKUMappingRepository = $customerSpecificSKUMappingRepository;
        $this->productCatalogRepository = $productCatalogRepository;
    }

    public function index(){
        $title = 'Sku Mapping';
        $colNames = "'ID','CustomerID','CustomerSKU','MITSKU','Description'";
       // $mitSkuList = $this->productCatalogRepository->getMitSkuList1();



        $colModel = "{name:'ID',index:'ID', align:'center',sortable:true,editable:false,hidden:true,'key':true},
                {name:'CustomerID',index:'CustomerID', align:'left',editable:true,hidden:true,editrules: {edithidden: false}},
                {name:'CustomerSKU',index:'CustomerSKU', align:'center',editable:true},
		        {name:'MITSKU',index:'MITSKU', align:'center',editable:true ,edittype: 'custom',
		            editoptions:{
                            'custom_element' : autocomplete_element,
                            'custom_value'   : autocomplete_value
		            }
		        },
		        {name:'Description',index:'Description', align:'center',editable:false}";


        return view('settings/skumap/skuMapping',compact('colNames','colModel','title','mitSkuList'));
    }

    public function search(Request $request){
        //grid Elements
        $page     = $request->input('page');
        $limit    = $request->input('rows');
        $sidx     = $request->input('sidx');
        $sord     = $request->input('sord');
        // form Elements
        $search   = $request->get('Search');
        $excel    = $request->get('Excel');

        $fieldsToSearch = ['ID', 'CustomerSKU', 'MITSKU'];
        $data = $this->customerSpecificSKUMappingRepository->getAll($search,$fieldsToSearch);

        if (!$excel){
            return response()->json($this->customerSpecificSKUMappingRepository->response($data,$page,$limit,$sidx,$sord));
        }else{
            return $this->customerSpecificSKUMappingRepository->exportExcel($data,'MappingFile','Orders');
        }
    }

    public function edit(Request $request){
        $action = $request->get('oper');
        $id = $request->get('id');
        $customerId = auth()->user()->customerid;
        $customerSKU = $request->get('CustomerSKU');
        $mitSku = $request->get('MITSKU');
        $description = $request->get('description');
        $output["message"] = "";
        switch ($action) {
            case 'add':
                $messages = [
                  "MITSKU.required" => "You haven't selected an SKU from our catalog.",
                  "CustomerSKU.unique" => "The Customer SKU has already been used."
                ];
                $validator = Validator::make($request->all(),[
                  "CustomerSKU" => "required|unique:CustomerSpecificSKUMapping,CustomerSKU,NULL,ID,CustomerID,".$customerId,
                  "MITSKU" => "required",
                  "description" => "required"
                ],$messages);
                $validator->setAttributeNames([
                  "CustomerSKU" => "Customer SKU",
                  "MITSKU" => "MITSKU"
                ]);
                if($validator->fails()){
                  $messages = $validator->errors();
                  foreach($messages->all() as $message){
                    $output["message"] .= $message . "<br/>";
                  }
                  $output["code"] = 1;
                }
                else{
                  $this->customerSpecificSKUMappingRepository->insertSku($customerId,$customerSKU,$mitSku,$description);
                  $output["message"] .= "The sku has been successfully mapped.";
                  $output["code"] = 0;
                }
                break;
            case 'edit':
                $messages = [
                  "MITSKU.required" => "You haven't selected an SKU from our catalog.",
                  "CustomerSKU.unique" => "The Customer SKU has already been used.",
                  "id.exists" => "This selection is not valid. Please select the SKU directly from the catalog."
                ];
                $validator = Validator::make($request->all(),[
                  "CustomerSKU" => "required|unique:CustomerSpecificSKUMapping,CustomerSKU,".$id.",ID,CustomerID,".$customerId,
                  "MITSKU" => "required",
                  "id" => "required:|exists:CustomerSpecificSKUMapping,ID,CustomerID,".auth()->user()->customerid,
                  "description" => "required"
                ],$messages);
                $validator->setAttributeNames([
                  "CustomerSKU" => "Customer SKU",
                  "MITSKU" => "MITSKU"
                ]);
                if($validator->fails()){
                  $messages = $validator->errors();
                  foreach($messages->all() as $message){
                    $output["message"] .= $message . "<br/>";
                  }
                  $output["code"] = 1;
                }
                else{
                  $this->customerSpecificSKUMappingRepository->editSku($customerId,$customerSKU,$mitSku,$id,$description);
                  $output["message"] .= "The sku has been successfully updated.";
                  $output["code"] = 0;
                  $output["close"] = 1;
                }
                break;
            case 'del':
                $this->customerSpecificSKUMappingRepository->deleteSku($id);
                break;
        }
        //This is just for today, im gonna fix it tomorrow.
        if($output["message"] != ""){
          return json_encode($output);
        }
    }

    public function getSkuList(Request $req){
        $mitSkuList = $this->productCatalogRepository->getMitSkuList1($req);
        return Response::json($mitSkuList);
    }
}
