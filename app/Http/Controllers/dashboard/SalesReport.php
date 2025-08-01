<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobOrder;

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

    $first_date = date('Y-m-d',strtotime('first day of this month'));
    $last_date = date('Y-m-d',strtotime('last day of this month'));


    $monthSales = JobOrder::whereBetween('date', [$first_date, $last_date])->where('status', 2)->get();

    $total_month_sales = 0;
    foreach($monthSales as $da){
       $total_month_sales += $da->total_amount;
    }

    $array = array();

    $today_total_sales = 0;
    $total_cars = count($jobOrderSales);
    foreach ($jobOrderSales as $key => $value) {
    $today_total_sales += $value->total_amount;
    
    $array[] = array(
      'id' => $value->plate_number,
        'product_name' => $value->plate_number,
        'stock' => $value->mileage,
        'amount' =>   "₱". number_format($value->total_amount, 2) ,
        'price' => "$656.85",
        'qty' => 679,
        'status' => 1,
        'image' =>"car-placeholder.jpg",
        'product_brand' => $value->manufacturer . " ". $value->model,
        'car_overview_link' => "/app/car/view/".$value->car_id,
        'job_order_link' => "/app/job-order/".$value->id,
        'today_total_sales' => $today_total_sales,
        'total_cars' => $total_cars,
        'month_sales' => $total_month_sales,
      );
       
    
 }

 $jo['data'] = $array;
    return response()->json($jo);

    
}



  //  "id": 99,
  //     "product_name": "Komainer",
  //     "category": 0,
  //     "stock": 1,
  //     "sku": 59592,
  //     "price": "$656.85",
  //     "qty": 679,
  //     "status": 3,
  //     "image": "product-10.png",
  //     "product_brand": "Feest Group"
  //   },

  
}
