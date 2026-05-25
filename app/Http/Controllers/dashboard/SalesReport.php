<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobOrder;
use App\Models\DailyCashBalance;
use App\Models\DailySalesRemark;
use App\Models\InvoiceNumber;
use Auth;
use DB;

use App\Exports\SalesReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Response;
use File;
use Mail;
use Illuminate\Support\Facades\Http;
use App\Services\FileDownloader; 


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
    ////////////////
    ->WhereIn('status', [3, 2])
    ->where('date', $date_today)
    ->distinct()
    ->get();

  
    $array = array();

    
    $today_total_sales = 0;
    $total_cars = count($jobOrderSales);

    foreach ($jobOrderSales as $key => $value) {
    $today_total_sales += str_replace(",", "", $value->total_amount);
        $showCash = 0;
        $showGcash = 0;
        $showMobile = 0;
        $showOthers = 0;
    if($value->payment > 0 && $value->mode_of_payment == 'cash') {
      $showCash += str_replace(",", "", $value->payment);
    }
    if($value->payment2 > 0 && $value->mode_of_payment2 == 'cash') {
      $showCash += str_replace(",", "", $value->payment2);
    }
    if($value->payment > 0 && $value->mode_of_payment == 'gcash') {
      $showGcash += str_replace(",", "", $value->payment);
    }
    if($value->payment2 > 0 && $value->mode_of_payment2 == 'gcash') {
      $showGcash += str_replace(",", "", $value->payment2);
    }

    if($value->payment > 0 && $value->mode_of_payment == 'mobile') {
      $showMobile += str_replace(",", "", $value->payment);
    }
    if($value->payment2 > 0 && $value->mode_of_payment2 == 'mobile') {
      $showMobile += str_replace(",", "", $value->payment2);
    }

     if($value->payment > 0 && $value->mode_of_payment == 'check_others') {
      $showOthers += str_replace(",", "", $value->payment);
    }
    if($value->payment2 > 0 && $value->mode_of_payment2 == 'check_others') {
      $showOthers += str_replace(",", "", $value->payment2);
    }

    
    $array[] = array(
        'id' => $value->plate_number,
        'jo_id' => $value->id,
        'job_order_number' => '<span id="inv-'.$value->id.'">'.$value->invoice_number.'</span> <i class="mdi mdi-note-edit" onclick="changeInvoiceNo('.$value->id.')"></i>' ,
        'product_name' => $value->plate_number,
        'stock' => $value->mileage,
        'amount' =>   "₱". $value->total_amount ,
        'price' => "$656.85",
        'cash' => ((isset($showCash)) ? $showCash : '' ),
        'gcash' => ((isset($showGcash)) ? $showGcash : '' ),
        'mobile_check' => ((isset($showMobile)) ? $showMobile: '' ),
        'others' => ((isset($showOthers)) ? $showOthers : '' ),
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


  public function getDeleteJo($jo_id) {
    $getJo = JobOrder::find($jo_id);
    return response()->json(['success'=> true, 'plate_number' => $getJo['plate_number'] ]);
  }


  public function deleteJo($jo_id) {

     JobOrder::where("id", $jo_id)->update(
    [
      "status" => 0,
    ]);
    $getJo = JobOrder::find($jo_id);
    
    return response()->json(['success'=> true, 'message' => $getJo['plate_number']." Deleted Successfully!" ]);
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

      $remarksData = DB::table('daily_sales_remarks')
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
      return response()->json(['success'=> true, 'htmlCashBalance' => $htmlCashBalance, 'remarks' => ((isset($remarksData[0])) ? $remarksData : '')]);
    } else {
      return response()->json(['success'=> false, 'htmlCashBalance' => '', 'remarks' => ((isset($remarksData[0])) ? $remarksData : '')]);

    }
      


  }
  

  public function getLatestInvoiceNumber($jo_id) {
    $jobOrderData = DB::table('job_orders')
    ->where('id', $jo_id)
    ->get();
    if($jobOrderData[0]->invoice_number > 0) {
      $invoice_number = $jobOrderData[0]->invoice_number;
    } else {
      $invoiceData = DB::table('invoice_numbers')
      ->orderBy('id','desc')
      ->get();
      $invoice_number = $invoiceData[0]->value + 1;
    }
    return response()->json(['success'=> true, 'invoice_number' => $invoice_number]);
  }


  public function saveInvoiceNumber() {

  $invoice_number = $_GET['modalInvoiceNumber']; 
    $jo_id = $_GET['jo_id']; 
    
    //  $invoiceCheckData = DB::table('invoice_numbers')
    // ->where('value', $invoice_number)
    // ->where('job_order_id', $jo_id)
    // ->get();
 

        $invoiceCheckData = DB::table('invoice_numbers')
      ->where('value', $invoice_number)
      ->get();


    $getLatestinvoiceData = DB::table('invoice_numbers')
    ->orderBy('id','desc')
    ->get();

      $process = false;
    if( !isset($invoiceCheckData[0])) {
      $process = true;
   
       $invoiceCheckAvailableData = DB::table('invoice_numbers')
      ->where('value', $invoice_number)
      ->where('job_order_id', $jo_id)
      ->get();

      if(!isset($invoiceCheckAvailableData[0])) {
        $process = true;
      }

    }

    if($process == true) {
     $check_invoice_number = $invoice_number - 1;
      if($check_invoice_number == $getLatestinvoiceData[0]->value) {
          $c = new InvoiceNumber();
          $c->value = $invoice_number;
          $c->job_order_id = $jo_id;
          $c->save();
      }


    JobOrder::where("id", $jo_id)->update(
    [
      "invoice_number" => $invoice_number,
    ]);
     return response()->json(['success'=> true, 'message' => 'Invoice Number has been changed!', 'invoice_number' => $invoice_number]);
    } else {
      return response()->json(['success'=> false, 'message' => 'Invoice Number not available!']);
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
    ->where('payment', '>', 0)
    ->WhereIn('status', [3, 2])
    ->where('date', $date)
    ->get();

    $totalSalesData2 = DB::table('job_orders')
    ->where('payment2', '>', 0)
       ->WhereIn('status', [3, 2])
    ->where('date', $date)
    ->get();


    $totalSales = 0;
    foreach($totalSalesData as $data) {
      $payment =  str_replace(",", "", $data->payment);
      $totalSales = $totalSales + str_replace(",", "", $payment);

    }
    foreach($totalSalesData2 as $data2) {
      $payment =  str_replace(",", "", $data2->payment2);
      $totalSales = $totalSales + str_replace(",", "", $payment);

    }

    $totalCashData = DB::table('job_orders')
    ->where('date', $date)
    ->where('payment', '>', 0)
    ->where('mode_of_payment', 'cash')
    ->WhereIn('status', [2, 3])
    ->get();

    $totalCashData2 = DB::table('job_orders')
    ->where('date', $date)
    ->where('payment2', '>', 0)
    ->where('mode_of_payment2', 'cash')
        ->WhereIn('status', [2, 3])
    ->get();


    $grandTotal = 0;
    $totalCash = 0;
    foreach($totalCashData as $datac) {
        $totalCash += str_replace(",", "", $datac->payment);
    }
     foreach($totalCashData2 as $datac2) {
        $totalCash += str_replace(",", "", $datac2->payment2);
    }
    
     $grandTotal += $totalCash;
    $totalCash = $totalCash;



    $totalGcashData = DB::table('job_orders')
    ->where('date', $date)
    ->where('payment', '>', 0)
    ->where('mode_of_payment', 'gcash')
    ->WhereIn('status', [3, 2])
    ->get();

    $totalGcashData2 = DB::table('job_orders')
    ->where('date', $date)
    ->where('payment2', '>', 0)
    ->where('mode_of_payment2', 'gcash')
    ->WhereIn('status', [3, 2])
    ->get();
    
    $totalGcash = 0;
    foreach($totalGcashData as $datagc) {
        $totalGcash += str_replace(",", "", $datagc->payment);
    }
    foreach($totalGcashData2 as $datagc2) {
        $totalGcash += str_replace(",", "", $datagc2->payment2);
    }
    $grandTotal += $totalGcash;
    $totalGcash = $totalGcash;


    $totalMobileCheckData = DB::table('job_orders')
    ->where('date', $date)
    ->where('payment', '>', 0)
    ->where('mode_of_payment', 'mobile')
    ->WhereIn('status', [3, 2])
    ->get();

     $totalMobileCheckData2 = DB::table('job_orders')
    ->where('date', $date)
    ->where('payment2', '>', 0)
    ->where('mode_of_payment2', 'mobile')
    ->WhereIn('status', [3, 2])
    ->get();

    $totalMobileCheck = 0;

    foreach($totalMobileCheckData2 as $datamc2) {
      $totalMobileCheck += str_replace(",", "", $datamc2->payment2);
    }
    foreach($totalMobileCheckData as $datamc) {
      $totalMobileCheck += str_replace(",", "", $datamc->payment);
    }
    $grandTotal += $totalMobileCheck;
    $totalMobileCheck = $totalMobileCheck;


    $totalOthersData = DB::table('job_orders')
    ->where('date', $date)
    ->where('payment', '>', 0)
    ->where('mode_of_payment', 'check_others')
    ->WhereIn('status', [3, 2])
    ->get();

    $totalOthersData2 = DB::table('job_orders')
    ->where('date', $date)
    ->where('payment2', '>', 0)
    ->where('mode_of_payment2', 'check_others')
    ->WhereIn('status', [3, 2])
    ->get();
    $totalOthers = 0;
    foreach($totalOthersData as $dataoh) {
      $totalOthers += str_replace(",", "", $dataoh->payment);
    }
    foreach($totalOthersData2 as $dataoh2) {
      $totalOthers += str_replace(",", "", $dataoh2->payment2);
    }
    $grandTotal += $totalOthers;
    $totalOthers = $totalOthers;

    $first_date = date('Y-m-d',strtotime('first day of this month'));
    $last_date = date('Y-m-d',strtotime('last day of this month'));


    $monthSales = JobOrder::whereBetween('date', [$first_date, $last_date])->WhereIn('status', [3, 2])->get();
    $monthSales2 = JobOrder::whereBetween('date', [$first_date, $last_date])->where('status', 1)->get();


    $allCars = array();

    foreach($monthSales as $c1) {
      if($c1->plate_number > '') {
        $allCars[] = $c1->plate_number; 
      }
    }
    foreach($monthSales2 as $c2) {
      if($c2->plate_number > '') {
        $allCars[] = $c2->plate_number; 
      }
    }

    $arrayWithoutDuplicates = array_unique($allCars);
//////////////////
    $total_month_sales = 0;
    foreach($monthSales as $da){
       $total_month_sales += str_replace(",", "", $da->total_amount);
    }
      return response()->json(['success'=> true, 'no_of_cars_repair_and_jo' => count($arrayWithoutDuplicates),'total_sales' => $totalSales, 'total_cars' => count($totalSalesData), 'total_monthly_sales' => $total_month_sales, 'total_monthly_cars' => count($monthSales), 'total_cash' => (($totalCash > 0) ? "₱".number_format($totalCash, 2) : ''), 'total_gcash' => (($totalGcash > 0) ? "₱".number_format($totalGcash, 2) : ''), 'total_mobile_check' => (($totalMobileCheck > 0) ? "₱".number_format($totalMobileCheck, 2) : ''), 'total_others' => (($totalOthers > 0) ? "₱".number_format($totalOthers, 2) : ''), 'grand_total' => "₱".number_format($grandTotal, 2) ]);
  }



  public function saveRemarks($date) {
     $check = DB::table('daily_sales_remarks')
    ->where('date', $date)
    ->get();

    if(isset($check[0])) {
        DailySalesRemark::where("date", $date)->update(
      [
        "remarks" => $_GET['remarks'],
        "checked_by" => $_GET['checkedBy'],
        "prepared_by" => $_GET['preparedBy'],
     
      ]);
      return response()->json(['success'=> true, 'message' => 'Saved successfully!' ]);
    } else {
      $c = new DailySalesRemark();
      $c->remarks    = $_GET['remarks'] ? $_GET['remarks'] : '';
      $c->checked_by    = $_GET['checkedBy'] ? $_GET['checkedBy'] : '';
      $c->prepared_by    = $_GET['preparedBy'] ? $_GET['preparedBy'] : '';
      $c->date    = $date;
      $c->save();
      return response()->json(['success'=> true, 'message' => 'Saved successfully!' ]);
    }
  }

  public function getUserName() {
    if (Auth::check()) {
        $user = Auth::user();
        $userName = $user->name;
         return response()->json(['success'=> true, 'userName' => $userName ]);
      }
    }
  


    
  public function exportSalesReport($date) {

    date_default_timezone_set("Asia/Manila");
    $jobOrderSales = DB::table('job_orders')
    ->where('date', $date)
    ->where('status', 2)
    ->get();
  
    $array = array();
    
    $today_total_sales = 0;
    $total_cars = count($jobOrderSales);

    foreach ($jobOrderSales as $key => $value) {
    $today_total_sales +=  str_replace(",", "", $value->total_amount) ;
        $showCash = 0;
    $showGcash = 0;
    $showMobile = 0;
    $showOthers = 0;
    if($value->payment > 0 && $value->mode_of_payment == 'cash') {
      $showCash = str_replace(",", "", $value->payment);
    }
    if($value->payment2 > 0 && $value->mode_of_payment2 == 'cash') {
      $showCash = str_replace(",", "", $value->payment2);
    }
    if($value->payment > 0 && $value->mode_of_payment == 'gcash') {
      $showGcash = str_replace(",", "", $value->payment);
    }
    if($value->payment2 > 0 && $value->mode_of_payment2 == 'gcash') {
      $showGcash = str_replace(",", "", $value->payment2);
    }
    if($value->payment > 0 && $value->mode_of_payment == 'mobile') {
      $showMobile = str_replace(",", "", $value->payment);
    }
    if($value->payment2 > 0 && $value->mode_of_payment2 == 'mobile') {
      $showMobile = str_replace(",", "", $value->payment2);
    }
     if($value->payment > 0 && $value->mode_of_payment == 'check_others') {
      $showOthers = str_replace(",", "", $value->payment);
    }
    if($value->payment2 > 0 && $value->mode_of_payment2 == 'check_others') {
      $showOthers = str_replace(",", "", $value->payment2);
    }
  }
  $fileName = 'daily_sales_report';
    $file = 'daily_sales_report.xlsx';

      $items[] = array('INVOICE NUMBER', 'PLATE NUMBER', 'CASH', 'GCASH', 'MOBILE', 'CHECK/OTHERS', 'TOTAL');

        $cash = 0;
       $gcash = 0;
       $mobile = 0;
       $check_others = 0;
       $totalCash = 0;
       $totalGcash = 0;
       $totalMobile = 0;
       $totalCheckOthers = 0;
       $grandTotal = 0;
    foreach ($jobOrderSales as $k => $v) {
        $cash = 0;
       $gcash = 0;
       $mobile = 0;
       $check_others = 0;
     
      if($v->mode_of_payment == 'cash') {
        $cash +=  str_replace(",", "", $v->payment);
        $totalCash += str_replace(",", "", $v->payment);
      } else if($v->mode_of_payment == 'gcash') {
        $gcash +=  str_replace(",", "", $v->payment);
        $totalGcash += str_replace(",", "", $v->payment);
      } else if($v->mode_of_payment == 'mobile') {
        $mobile +=  str_replace(",", "", $v->payment);
        $totalMobile += str_replace(",", "", $v->payment);
      } else if($v->mode_of_payment == 'check_others') {
        $check_others +=  str_replace(",", "", $v->payment);
        $totalCheckOthers += str_replace(",", "", $v->payment);
      }

      if($v->mode_of_payment2 == 'cash') {
        $cash +=  str_replace(",", "", $v->payment2);
        $totalCash += str_replace(",", "", $v->payment2);
      } else if($v->mode_of_payment2 == 'gcash') {
        $gcash +=  str_replace(",", "", $v->payment2);
        $totalGcash += str_replace(",", "", $v->payment2);
      } else if($v->mode_of_payment2 == 'mobile') {
        $mobile +=  str_replace(",", "", $v->payment2);
        $totalMobile += str_replace(",", "", $v->payment2);
      } else if($v->mode_of_payment2 == 'check_others') {
        $check_others +=  str_replace(",", "", $v->payment2);
        $totalCheckOthers += str_replace(",", "", $v->payment2);
      }

      $grandTotal +=  str_replace(",", "", $v->total_amount);

      $items[] = array($v->invoice_number, $v->plate_number, (($cash > 0) ? number_format($cash, 2) : ''), (($gcash > 0) ? number_format($gcash, 2) : ''), (($mobile > 0) ? number_format($mobile, 2) : '') , (($check_others > 0) ? number_format($check_others, 2) : '') , $v->total_amount );
    }

      $items[] = array('Sales', '', (($totalCash > 0) ? number_format($totalCash, 2) : '') , (($totalGcash > 0) ? number_format($totalGcash, 2) : '') , (($totalMobile > 0) ? number_format($totalMobile, 2) : '') , (($totalCheckOthers > 0) ? number_format($totalCheckOthers, 2) : '') , (($grandTotal > 0) ? number_format($grandTotal, 2) : '') );

      $export = new SalesReportExport([
        $items
      ]);

      Excel::store($export, $file, 'public');

  return response()->json(['fileName'=> $fileName]);
 }


 public function download(Request $request, $filename)
    {
    date_default_timezone_set("Asia/Manila");

      $dateName = date('m-d-Y');
     $filePath = storage_path('app');
    if (Storage::exists('public/'.$filename.'.xlsx')) {
      //   Attempt to download or retrieve size
          $headers = [
          'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
          'X-Custom-Header' => 'SomeValue',
      ];
        return Storage::download('public/'.$filename.'.xlsx', 'daily_sales_report-'.$dateName.'.xlsx', $headers);
      } else {
        abort('404');
      }
    }

}
