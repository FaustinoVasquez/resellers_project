<?php

namespace App\Http\Controllers\OrderImport;


use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;


class NetSuiteImportController extends Controller
{
    public function index()
    {
        return view('orderImport/netSuiteImport');
    }

    public function store(Request $request)
    {
        $file = array(
            'PoNumber' => $request->get('PoNumber'),
            'file'  => Input::file('file')
        );

        $rules = array('file' => 'required|mimes:csv,txt',
            'PoNumber'=>'required'
        );
        $validator = Validator::make($file, $rules);


        if ($validator->fails()) {
          $bag = $validator->errors();
          $string = "";
          foreach($bag->all() as $message){
            $string .= $message . '<br/>';
          }
          return back()->withInput()->with('error',$string);
        } else {
            if (Input::file('file')->isValid()) {
                $destinationPath = '/var/www/html/mnt/orders'; // upload path
                $fileName = $request->get('PoNumber').'_'.auth()->user()->customerid.'_'.date('Y-m-d_H-i-s').'.csv';
                $customerId = auth()->user()->customerid;
                Input::file('file')->move($destinationPath,$fileName);

                try{
                  $query= DB::select("EXEC [Inventory].[dbo].[sp_ImportResellerNetSuiteBQ] 'c:\\sharedb\\OrderImport\\".$fileName."','{$customerId}'");
                }
                catch(Exception $ex){
                  return back()->with('errorMessage',"There was an error processing your order. Please try again.");
                }

                if ($query){
                    Session::flash('success', 'The file has been uploaded for processing.  Please allow up to 5 to 15 minutes for our system to show the orders from this import to show in the order management portal.  You will be receiving email(s) from our MI Tech AutoBot with the results of this import.  We appreciate your your business and your patients while we process this file. If you are experiencing problems with this tool, please contact it@mitechnologiesinc.com.');
                }else{
                    Session::flash('error', 'Something is Wrong With the file');
                }
                return Redirect::to('NetImport');
            }else{
                Session::flash('error', 'uploaded file is not valid');
                return Redirect::to('NetImport');
            }
        }
    }
}
