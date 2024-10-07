<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;

class DashboardController extends Controller
{

  public function index(){
    //best sellers query
    $dbo = DB::connection()->getPdo();
    $qry = 'SELECT TOP 7 PC.NAME as Name,SUM(OD.QuantityOrdered) as total FROM OrderManager.dbo.Orders O
    INNER JOIN Ordermanager.dbo.[Order Details] OD
    ON OD.OrderNumber = O.OrderNumber AND Adjustment = 0
    LEFT OUTER JOIN Inventory.dbo.ProductCatalog PC
    ON PC.ID = OD.SKU
    WHERE CustomerID = '. Auth::user()->customerid .' AND CartID = '. Auth::user()->CartId .'
    GROUP BY PC.NAME ORDER BY total DESC';
    $query = $dbo->prepare($qry);
    $query->execute();
    $bestSellers = collect($query->fetchAll());

    //today total orders for the customer
    $todayTotal = Order::where('CartID',Auth::user()->CartId)->where('CustomerID',Auth::user()->customerid)->where('OrderDate',Carbon::today())->count();

    //today total rebates
    $todayRebates = Order::select(DB::raw("SUM(CouponDiscount) as rebates"))->where('CartID',Auth::user()->CartId)->where('CustomerID',Auth::user()->customerid)->where('OrderDate',Carbon::today())->first()->rebates;

    //today total due
    $todayDue = Order::select(DB::raw("SUM(BalanceDue) as due"))->where('CartID',Auth::user()->CartId)->where('CustomerID',Auth::user()->customerid)->where('OrderDate',Carbon::today())->first()->due;

    //today total products purchased
    $totalProducts = Order::where('CartID',Auth::user()->CartId)->where('CustomerID',Auth::user()->customerid)->where('OrderDate',Carbon::today())->with(['detailsCountRelation' => function($query){
      $query->where("Product",'not like','%Shipping%')->where('Product','<>','Sales Tax');
    }])->get();

    //return the view
    return view("dashboard.dashboard",[
      'bestSellers' => $bestSellers,
      'todayProducts' => $totalProducts->sum('detailsCount'),
      'todayDue' => number_format((float)$todayDue,2,'.',''),
      'todayRebates' => number_format((float)$todayRebates,2,'.',''),
      'todayTotal' => $todayTotal
    ]);
  }

  //I made this function because for some odd reason, sql server is sending me a formatted string instead of a XXXX-XX-XX XX:XX:XX
  public function recentOrders(Request $req){
    $search = $req->get('search');
    //where('CartID',Auth::user()->CartId)->where('CustomerID',Auth::user()->customerid)
    $searches = [
      'CartID' => Auth::user()->CartId,
      'CustomerID' => Auth::user()->customerid
    ];
    $orders = Order::query();
    $orders->where($searches);
    if($search['value'] != '')
    {
        $orders->where('OrderNumber','like',$search['value']."%")
        ->orWhere('ShipName','like',$search['value']."%")
        ->where('CustomerID',Auth::user()->customerid)
        ->where('CartID',Auth::user()->CartId);
    }
    $orders = $orders->select('OrderNumber',
        'SourceOrderID as Reference',
        'OrderStatus',
        'PONumber',
        'ShipName',
        'OrderDate',
        'FinalGrandTotal',
        'BalanceDue',
        'CartID',
        'CustomerID')->orderBy('DateCreated','desc')->take(5)->get();
    $data = array();
    foreach($orders as $order){
      $tRow = array();
      $tRow[] = '<a href class="btn-details" data-id="'. $order->OrderNumber .'">'.$order->OrderNumber."</a>";
      $tRow[] = ($order->Reference)? $order->Reference : "No Reference";
      $tRow[] = ($order->OrderStatus == "Shipped")? '<button class="btn btn-sm btn-info btn-trk btn-block" id="'. $order->OrderNumber .'">Tracking Info</button>' : '<button class="btn btn-sm btn-info btn-trk btn-block" disabled="true">No Tracking Number</button>';
      $tRow[] = ($order->OrderStatus)? $order->OrderStatus : "Pending Aproval";
      $tRow[] = $order->PONumber;
      $tRow[] = $order->ShipName;
      $tRow[] = $order->OrderDate->toFormattedDateString();
      $tRow[] = "$".number_format($order->FinalGrandTotal,2,'.',',');
      $data[] = $tRow;
    }
    $output = array(
      'recordsTotal' => intval($orders->count()),
      'recordsFiltered' => intval($orders->count()),
      'data' => $data
     );
    return json_encode($output);
  }

  public function salesAll(Request $req){
    $start = $req->get("start");
    $end = $req->get("end");
    $output = array();
    $collection["label"] = $start;
    $collection["data"] = self::queryTable($start,1);

    $output[] = $collection;

    $collection["label"] = $end;
    $collection["data"] = self::queryTable($end,1);

    $output[] = $collection;

    return json_encode($output);
  }

  public function salespaid(Request $req){
    $start = $req->get("start");
    $end = $req->get("end");
    $output = array();
    $collection["label"] = $start;
    $collection["data"] = self::queryTable($start,0);

    $output[] = $collection;

    $collection["label"] = $end;
    $collection["data"] = self::queryTable($end,0);

    $output[] = $collection;

    return json_encode($output);
  }

  public function salesPending(Request $req){
    $start = $req->get("start");
    $end = $req->get("end");
    $output = array();
    $collection["label"] = $start;
    $collection["data"] = self::queryTable($start,2);

    $output[] = $collection;

    $collection["label"] = $end;
    $collection["data"] = self::queryTable($end,2);

    $output[] = $collection;

    return json_encode($output);
  }

  private function queryTable($year,$fn){
    if($fn > 1)
      $row = DB::select(DB::raw("SELECT * FROM OrderManager.dbo.fn_balance_due_gz(". $year .",". Auth::user()->customerid  .",".  Auth::user()->CartId .")"));
    else
      $row = DB::select(DB::raw("select * from ordermanager.dbo.fn_sales_by_month(". $year .",". Auth::user()->customerid  ."," . Auth::user()->CartId . ",". $fn .")"));
    $output = array();
    foreach($row as $month){
      $time = new Carbon($month->mymonth);
      $x = $time->month;
      $y = floatval($month->total);
      $output[] = array($x,$y);
    }
    return $output;
  }

}
