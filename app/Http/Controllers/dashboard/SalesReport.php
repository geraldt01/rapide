<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobOrder;
use App\Models\DailyCashBalance;

use DB;

class SalesReport extends Controller
{
  public function index()
  {
    return view('content.dashboard.daily-sales-report');
  }

  public function jsonSalesReportList() {
    date_default_timezone_set("Asia/Manila");
    if(isset($_GET['date'])) {
      $date_today =  $_GET['date'];
    } else {
      $date_today =  date('Y-m-d');
    }

    $jobOrderSales = DB::table('job_orders')
    ->where('date', $date_today)
    ->where('status', 2)
    ->get();

  
    $array = array();

    
    $today_total_sales = 0;
    $total_cars = count($jobOrderSales);
    foreach ($jobOrderSales as $key => $value) {
    $today_total_sales += $value->total_amount;
    
    $array[] = array(
      'id' => $value->plate_number,
        'job_order_number' => $value->job_order_number,
        'product_name' => $value->plate_number,
        'stock' => $value->mileage,
        'amount' =>   "₱". number_format($value->total_amount, 2) ,
        'price' => "$656.85",
        'cash' => (($value->mode_of_payment == 'cash') ? $value->payment : '' ),
        'gcash' => (($value->mode_of_payment == 'gcash') ? $value->payment : '' ),
        'mobile_check' => (($value->mode_of_payment == 'mobile_check') ? $value->payment : '' ),
        'others' => (($value->mode_of_payment == 'others') ? $value->payment : '' ),
        'qty' => 679,
        'status' => 1,
        'image' =>"car-placeholder.jpg",
        'product_brand' => $value->manufacturer . " ". $value->model,
        'car_overview_link' => "/app/car/view/".$value->car_id,
        'job_order_link' => "/app/job-order/".$value->id,
        'today_total_sales' => $today_total_sales,
        'total_cars' => $total_cars,
      );
       
    
 }

 $jo['data'] = $array;
    return response()->json($jo);

    
}


  public function createCashBalance($date) {

    $c = new DailyCashBalance();
    $c->cash_1000    = $_GET['modaladdCash1000'] ? $_GET['modaladdCash1000'] : 0;
    $c->cash_500    = $_GET['modaladdCash500'] ? $_GET['modaladdCash500'] : 0;
    $c->cash_200   = $_GET['modaladdCash200'] ? $_GET['modaladdCash200'] : 0;
    $c->cash_100   = $_GET['modaladdCash100'] ? $_GET['modaladdCash100'] : 0;
    $c->cash_50    = $_GET['modaladdCash50'] ? $_GET['modaladdCash50'] : 0;
    $c->cash_20    = $_GET['modaladdCash20'] ? $_GET['modaladdCash20'] : 0;
    $c->cash_10    = $_GET['modaladdCash10'] ? $_GET['modaladdCash10'] : 0;
    $c->cash_5    = $_GET['modaladdCash5'] ? $_GET['modaladdCash5'] : 0;
    $c->loose_coins    = $_GET['modaladdLooseCoins'] ? $_GET['modaladdLooseCoins'] : 0;
    $c->change    = $_GET['modaladdLooseChange'] ? $_GET['modaladdLooseChange'] : 0;
    $c->date    = $date;
    $c->save();

      return response()->json(['success'=> true, 'message' => 'Daily Cash Balance has been created!']);

  }
  
  public function getCashBalance($date) {

      $cashData = DB::table('daily_cash_balances')
      ->where('date', $date)
      ->where('status', 1)
      ->get();

    $htmlCashBalance = "";

  
    if(isset($cashData[0])) {
      foreach($cashData as $data) {
        $htmlCashBalance ='
        <tr>
        <td class="text-right">PHP 1,000.00</td><td>'.$data->cash_1000.'</td><td class="text-right">PHP ' .(($data->cash_1000>0) ? number_format(1000*$data->cash_1000, 2, '.', ',')  : 0).'</td>
        </tr>
        <tr>
        <td class="text-right"> 500.00</td><td>'.$data->cash_500.'</td><td class="text-right">PHP ' .(($data->cash_500>0) ? number_format(500*$data->cash_500, 2, '.', ',')  : 0).'</td>
        </tr>
         <tr>
        <td class="text-right"> 100.00</td><td>'.$data->cash_100.'</td><td class="text-right">PHP ' .(($data->cash_100>0) ? number_format(100*$data->cash_100, 2, '.', ',')  : 0).'</td>
        </tr>
        <tr>
        <td class="text-right"> 50.00</td><td>'.$data->cash_50.'</td><td class="text-right">PHP ' .(($data->cash_50>0) ? number_format(50*$data->cash_50, 2, '.', ',')  : 0).'</td>
        </tr>
        <tr>
        <td class="text-right"> 20.00</td><td>'.$data->cash_20.'</td><td class="text-right">PHP ' .(($data->cash_20>0) ? number_format(20*$data->cash_20, 2, '.', ',')  : 0).'</td>
        </tr>
         <tr>
        <td class="text-right"> 10.00</td><td>'.$data->cash_10.'</td><td class="text-right">PHP ' .(($data->cash_10>0) ? number_format(10*$data->cash_10, 2, '.', ',')  : 0).'</td>
        </tr>
        <tr>
        <td class="text-right"> 5.00</td><td>'.$data->cash_5.'</td><td class="text-right">PHP ' .(($data->cash_5>0) ? number_format(5*$data->cash_5, 2, '.', ',')  : 0).'</td>
        </tr>
         <tr>
        <td class="text-left"> LOOSE COINS</td><td></td><td class="text-right">PHP ' .(($data->loose_coins>0) ?  number_format($data->loose_coins, 2, '.', ',')  : 0).'</td>
        </tr>
         <tr>
        <td class="text-left" colspan="2"> TOTAL</td><td class="text-right">PHP '.  number_format((1000*$data->cash_1000 + 500*$data->cash_500 + 100*$data->cash_100 + 50*$data->cash_50 + 20*$data->cash_20 
        + 10*$data->cash_10 + 5*$data->cash_5 + $data->loose_coins), 2, '.', ',').'</td>
        </tr>
         <tr style="border-bottom: 2px solid black;">
        <td class="text-left" colspan="2"> <small><i>CHANGE, ADMIN DRAWER</i></small></td><td class="text-right"> -' . number_format($data->change, 2, '.', ',').'</td>
        </tr>
           <tr>
        <td class="text-left"> <b><i>CASH SALES</i></b></td><td></td><td class="text-right"><b>PHP ' .number_format((1000*$data->cash_1000 + 500*$data->cash_500 + 100*$data->cash_100 + 50*$data->cash_50 + 20*$data->cash_20 
        + 10*$data->cash_10 + 5*$data->cash_5 + $data->loose_coins - $data->change), 2, '.', ',').'</b></td>
        </tr>
        ';
        
      }
      return response()->json(['success'=> true, 'htmlCashBalance' => $htmlCashBalance]);
    } else {
      return response()->json(['success'=> false, 'htmlCashBalance' => '']);

    }
      


  }
  

  public function getFormCashBalance($date) {
    $cashData = DB::table('daily_cash_balances')
    ->where('date', $date)
    ->get();
      return response()->json(['success'=> true, 'cashData' => $cashData[0]]);
  }



   public function updateCashBalance(Request $request, $date) {
      DailyCashBalance::where("date", $date)->update(
      [
        "cash_1000" => $_GET['modaladdCash1000'],
        "cash_500" => $_GET['modaladdCash500'],
        "cash_200" => $_GET['modaladdCash200'],
        "cash_100" => $_GET['modaladdCash100'],
        "cash_50" => $_GET['modaladdCash50'],
        "cash_20" => $_GET['modaladdCash20'],
        "cash_10" => $_GET['modaladdCash10'],
        "cash_5" => $_GET['modaladdCash5'],
        "loose_coins" => $_GET['modaladdLooseCoins'],
        "change" => $_GET['modaladdLooseChange'],
      ]);

      return response()->json(['success'=> true, 'message' => "Cash Blance successfully updated!"]);
  }

  public function getTotal(Request $request, $date) {
    $totalSalesData = DB::table('job_orders')
    ->where('date', $date)
    ->where('payment', '>', 0)
    ->where('status', 2)
    ->get();

    $totalSales = 0;
    foreach($totalSalesData as $data) {
      $payment =  $data->payment;
      $totalSales = $totalSales + $payment;

    }

    $totalCashData = DB::table('job_orders')
    ->where('date', $date)
    ->where('payment', '>', 0)
    ->where('mode_of_payment', 'cash')
    ->where('status', 2)
    ->get();

    $grandTotal = 0;
    $totalCash = 0;
    foreach($totalCashData as $datac) {
      $payment =  $datac->payment;
      $totalCash = $totalCash + $payment;
       $grandTotal += $totalCash;
    }
    $totalCash = preg_replace('/[^0-9]/', '', $totalCash);

    $totalGcashData = DB::table('job_orders')
    ->where('date', $date)
    ->where('payment', '>', 0)
    ->where('mode_of_payment', 'gcash')
    ->where('status', 2)
    ->get();
    $totalGcash = 0;
    foreach($totalGcashData as $datagc) {
      $payment =  $datagc->payment;
      $totalGcash = $totalGcash + $payment;
      $grandTotal += $totalGcash;

    }
    $totalGcash = preg_replace('/[^0-9]/', '', $totalGcash);

    $totalMobileCheckData = DB::table('job_orders')
    ->where('date', $date)
    ->where('payment', '>', 0)
    ->where('mode_of_payment', 'mobile_check')
    ->where('status', 2)
    ->get();
    $totalMobileCheck = 0;
    foreach($totalMobileCheckData as $datamc) {
      $payment =  $datamc->payment;
      $totalMobileCheck = $totalMobileCheck + $payment;
      $grandTotal += $totalMobileCheck;
    }
    $totalMobileCheck = preg_replace('/[^0-9]/', '', $totalMobileCheck);


    $totalOthersData = DB::table('job_orders')
    ->where('date', $date)
    ->where('payment', '>', 0)
    ->where('mode_of_payment', 'others')
    ->where('status', 2)
    ->get();
    $totalOthers = 0;
    foreach($totalOthersData as $dataoh) {
      $payment =  $dataoh->payment;
      $totalOthers = $totalOthers + $payment;
     $grandTotal += $totalOthers;
    }
    $totalOthers = preg_replace('/[^0-9]/', '', $totalOthers);


    $first_date = date('Y-m-d',strtotime('first day of this month'));
    $last_date = date('Y-m-d',strtotime('last day of this month'));


    $monthSales = JobOrder::whereBetween('date', [$first_date, $last_date])->where('status', 2)->get();

    $total_month_sales = 0;
    foreach($monthSales as $da){
       $total_month_sales += $da->total_amount;
    }


      return response()->json(['success'=> true, 'total_sales' => $totalSales, 'total_cars' => count($totalSalesData), 'total_monthly_sales' => $total_month_sales, 'total_monthly_cars' => count($monthSales), 'total_cash' => (($totalCash > 0) ? "₱".number_format($totalCash, 2) : ''), 'total_gcash' => (($totalGcash > 0) ? "₱".number_format($totalGcash, 2) : ''), 'total_mobile_check' => (($totalMobileCheck > 0) ? "₱".number_format($totalMobileCheck, 2) : ''), 'total_others' => (($totalOthers > 0) ? "₱".number_format($totalOthers, 2) : ''), 'grand_total' => "₱".number_format($grandTotal, 2) ]);
  }



  
}
