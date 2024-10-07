<?php
namespace App\Repositories;

use App\Models\CustomerSpecificShipMapping;


class CustomerSpecificShipMappingRepository extends BaseRepository
{
    public function getModel()
    {
        return new CustomerSpecificShipMapping();
    }


    protected function select(){
        return $this->newQuery()->select('ID','CustomerShippingMethod','MITShippingMethod','CustomerID');
        return $orders;
    }

    public function getAll($search,$fields){
        $query= $this->select()
            ->where('CustomerID',auth()->user()->customerid)
            ->whereraw($this->concatAllWerefields($search, $fields));;
        return $query;
    }


    public function insertSku($customerId,$customerShippingMethod,$mitShippingMethod){
        return $this->newQuery()->insert(['CustomerID' =>$customerId,'CustomerShippingMethod'=>$customerShippingMethod,'MITShippingMethod'=>$mitShippingMethod]);
    }
    public function editSku($customerId,$customerShippingMethod,$mitShippingMethod,$id){
        return $this->newQuery()->where('ID',$id)
                                ->update(['CustomerID' =>$customerId,'CustomerShippingMethod'=>$customerShippingMethod,'MITShippingMethod'=>$mitShippingMethod]);
    }
    public function deleteSku($id){
        return $this->newQuery()->where('ID',$id)->delete();
    }




}