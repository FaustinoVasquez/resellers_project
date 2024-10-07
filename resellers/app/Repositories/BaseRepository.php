<?php
namespace App\Repositories;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use stdClass;
use Mailgun\Mailgun;

abstract class BaseRepository
{
    abstract public function getModel();

    public function newQuery()
    {
        return $this->getModel()->newQuery();
    }

    public function concatAllWerefields($search, $fields ) {
        $words = explode(" ", $search);
        $where = '';
        if ($search != '') {
            foreach ($words as $word) {
                foreach ($fields as $field) {
                    $where .= '(CONVERT(varchar(250),' . $field . ',0)) + ';
                }
                $where = substr($where, 0, -4);
                $where.= ') like ' . "'%" . $word . "%'" . ' and ';
            }
            $where = substr($where, 0, -4);
        } else {
            $where .= '1=1';
        }
        return $where;
    }

    public function exportExcel($query,$fileName,$sheetName){
        Excel::create($fileName, function($excel) use ($query,$sheetName) {
            $excel->sheet($sheetName, function($sheet) use($query) {
                $query = $query->get();
                $sheet->fromArray($query);
            });
        })->export('xls');
    }

    public function exportCSV($query,$fileName,$sheetName){
        Excel::create($fileName, function($excel) use ($query,$sheetName) {
            $excel->sheet($sheetName, function($sheet) use($query) {
                $query = $query->get();
                $sheet->fromArray($query);
            });
        })->export('csv');
    }

    public function exportCSVCustom($query,$fileName,$sheetName){

    }

    public function sendEmail($view,$viewParam,$to,$from,$subject){
      try{
        $view = view($view,$viewParam);
        $contents = $view->render();

        $mgClient = new Mailgun('key-4d73d8cb15e1065c8b8d6b66c80f065f');
        $domain = "mg.mitechnologiesinc.com";

        # Make the call to the client.

        $result = $mgClient->sendMessage($domain, array(
            'from'    => $from,
            'to'      => $to,
            'subject' => $subject,
            'html'    => $contents
        ));
        return $result;
      }catch(\Exception $ex){
        throw new \Exception($ex->getMessage());
      }
    }

    /**
     * @param $query
     * @param $page
     * @param $limit
     * @param $sidx
     * @param $sord
     * @return stdClass
     */
    public function response($query,$page,$limit,$sidx,$sord){
        $response          = new stdClass();
        $response->page    = $page; //page
        $response->records = $query->count();  //TotalRecords

        $response->total   = ceil($response->records/$limit);  //TotalPages
        $response->rows = $query
            ->skip($limit * $page - $limit)
            ->take($limit)
            ->orderby($sidx, $sord)
            ->get();
        return $response;
    }


    public function getDateFromTo($query,$dateFrom,$dateTo ){
        return $query->whereBetween('OrderDate', array($dateFrom, $dateTo));
    }


    public function getCustomerCartID($query)
    {
        return $query->where('CartID',auth()->user()->CartId)->where('customerID', auth()->user()->customerid);
    }

}
