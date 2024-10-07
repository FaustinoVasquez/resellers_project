<?php
namespace App\Repositories;

use App\Models\Customer;


class CustomerRepository extends BaseRepository
{
    public function getModel()
    {
        return new Customer();
    }


    function getCustomerIdData()
    {
        $customerIdData = Customer::select('CustomerID',
            'FullName',
            'Address',
            'City',
            'State',
            'Zip',
            'Country',
            'Email',
            'Phone',
            'Company')
            ->where('CustomerID', auth()->user()->customerid)->first()->toarray();

        return $customerIdData;
    }

}