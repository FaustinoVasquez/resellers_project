<?php
namespace App\Repositories;

use App\Models\CustomerSpecificSKUMapping;


class CustomerSpecificSKUMappingRepository extends BaseRepository
{
    public function getModel()
    {
        return new CustomerSpecificSKUMapping();
    }

    protected function select(){
        return $this->newQuery()->select('ID','CustomerID','CustomerSKU','MITSKU','Description');

        return $orders;
    }

    public function getAll($search,$fields){
        $query= $this->select()
            ->where('CustomerID',auth()->user()->customerid)
            ->whereraw($this->concatAllWerefields($search, $fields));;
        return $query;
    }

    public function insertSku($customerID,$customerSKU,$mitSku,$description){
        return $this->newQuery()->insert(['CustomerID' =>$customerID,'CustomerSKU'=>$customerSKU,'MITSKU'=>$mitSku,"Description" => $description]);
    }
    public function editSku($customerID,$customerSKU,$mitSku,$id,$description){
        return $this->newQuery()->where('ID',$id)
                                ->update(['CustomerID' =>$customerID,'CustomerSKU'=>$customerSKU,'MITSKU'=>$mitSku,"Description" => $description]);
    }
    public function deleteSku($id){
        return $this->newQuery()->where('ID',$id)->delete();
    }



    protected function selectCustomerSpecificMap(){
        return $this->newQuery()->select('ID','CustomerShippingMethod','MITShippingMethod','CustomerID');
        return $orders;
    }

    public function getAllCustomerSpecificMap($search,$fields){
        $query= $this->selectCustomerSpecificMap()
            ->where('CustomerID',auth()->user()->customerid)
            ->whereraw($this->concatAllWerefields($search, $fields));;
        return $query;
    }


}
