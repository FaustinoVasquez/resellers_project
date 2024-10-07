<?php

namespace App\Http\Controllers\Settings;

use App\Repositories\CustomerSpecificShipMappingRepository;
use App\Repositories\ProductCatalogRepository;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;


class CarrierMappingController extends Controller
{
    /**
     * @var CustomerSpecificSKUMappingRepository
     */
    private $customerSpecificSKUMappingRepository;

    public function __construct(CustomerSpecificShipMappingRepository $customerSpecificShipMappingRepository)
    {
        $this->customerSpecificShipMappingRepository = $customerSpecificShipMappingRepository;
    }

    public function index(){
        $title = 'Carrier Mapping';
        $colNames = "'ID','CustomerShippingMethod','MITShippingMethod','CustomerID'";
      //  $shippingMethod = ['Economy:Economy;2Day:2Day;1Day:1Day;ShippingLabelAttached:ShippingLabelAttached'];


        $colModel = "{name:'ID',index:'ID', width:40, align:'center',sortable:true,editable:false,hidden:true,'key':true},
                {name:'CustomerShippingMethod',index:'CustomerShippingMethod', width:100, align:'center',editable:true},
                {name:'MITShippingMethod',index:'MITShippingMethod', width:100, align:'center',editable:true, edittype: 'select', editoptions:{value: 'Economy:Economy;2Day:2Day;1Day:1Day;ShippingLabelAttached:Shipping Label Attached'} },
		        {name:'CustomerID',index:'CustomerID', width:80, align:'center',editable:true,hidden:true}";

        return view('settings/carriermap/carrierMapping',compact('colNames','colModel','title'));
    }

    public function search(Request $request){
        //grid Elements
        $page     = $request->input('page');
        $limit    = $request->input('rows');
        $sidx     = $request->input('sidx');
        $sord     = $request->input('sord');
        // form Elements
        $search   = $request->get('search');
        $excel    = $request->get('Excel');

        $fieldsToSearch = ['ID', 'CustomerShippingMethod', 'MITShippingMethod'];
        $data = $this->customerSpecificShipMappingRepository->getAll($search,$fieldsToSearch);

        if (!$excel){
            return response()->json($this->customerSpecificShipMappingRepository->response($data,$page,$limit,$sidx,$sord));
        }else{
            return $this->customerSpecificShipMappingRepository->exportExcel($data,'MappingFile','Orders');
        }
    }

    public function edit(Request $request){

        $action = $request->get('oper');
        $id = $request->get('id');
        $customerId = auth()->user()->customerid;
        $customerShippingMethod= $request->get('CustomerShippingMethod');
        $mitShippingMethod= $request->get('MITShippingMethod');


        switch ($action) {
            case 'add':
                $this->customerSpecificShipMappingRepository->insertSku($customerId,$customerShippingMethod,$mitShippingMethod);
                break;
            case 'edit':
                $this->customerSpecificShipMappingRepository->editSku($customerId,$customerShippingMethod,$mitShippingMethod,$id);
                break;
            case 'del':
                $this->customerSpecificShipMappingRepository->deleteSku($id);
                break;
        }
    }
}

