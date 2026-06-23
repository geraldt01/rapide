<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class Analytics extends Controller
{
  public function index()
  {
    return view('content.dashboard.dashboards-analytics');
  }

  public function jsonJobOrderList() {
    date_default_timezone_set("Asia/Manila");
    $date_today =  date('Y-m-d');

    $jobOrderInfo = DB::table('cars')
     ->join('job_orders', 'job_orders.car_id', '=', 'cars.id')
     ->join('owners', 'owners.id', '=', 'cars.owner_id')
     ->select('job_orders.id as id', 'job_orders.job_order_number as job_order_number', 
    'cars.id as car_id', 
    'job_orders.date as date', 
    'cars.plate_number as plate_number', 
    'cars.manufacturer as manufacturer', 
    'cars.vehicle_type as vehicle_type', 
    'cars.vehicle_model as vehicle_model', 
    'cars.transmission as transmission', 
    'cars.fuel_type as fuel_type', 
    'job_orders.mileage as mileage', 
    // 'job_orders.status as status', 
    'job_orders.status as status_display', 
    'cars.owner_id as owner_id', 
    'cars.vehicle_type as vehicle_type', 
    'cars.year as year', 
    'owners.owner_name as owner_name', 
    'owners.address as address', 
    'owners.mobile_number as mobile_number', 
  )
   ->where('job_orders.date', $date_today)
   ->where('job_orders.status', '!=', '0')
    ->OrderBy('job_orders.status_display', 'Desc')
    ->get();


    $key = 0;
    foreach($jobOrderInfo as  $d) {
       $data[$d->plate_number][] = $d;
    }

    if(!isset($data)) {
  $data = array();
  $var = array();
}




foreach($data as $key => $final) {
  $var[$key][] = $final[0];
}



  //   $date_today =  date('Y-m-d');
  //     $jobOrderInfo = DB::table('job_orders')
  //    ->join('cars', 'cars.id', '=', 'job_orders.car_id')
  //    ->join('owners', 'owners.id', '=', 'cars.owner_id')
  //  ->select('job_orders.id as id', 'job_orders.job_order_number as job_order_number', 
  //   'cars.id as car_id', 
  //   'job_orders.date as date', 
  //   'cars.plate_number as plate_number', 
  //   'cars.manufacturer as manufacturer', 
  //   'cars.vehicle_model as vehicle_model', 
  //   'job_orders.mileage as mileage', 
  //   'job_orders.status as status', 
  //   'job_orders.status_display as status_display', 
  //   'cars.owner_id as owner_id', 
  //   'cars.vehicle_type as vehicle_type', 
  //   'cars.year as year', 
  //   'owners.owner_name as owner_name', 
  //   'owners.address as address', 
  //   'owners.mobile_number as mobile_number', 
  // )
  //   ->where('job_orders.date', $date_today)
  //   ->get();

 
    $array = array();
$counter = 0;

  foreach($var as $a){
    $select['plate_number'][] = $a;
  }



  foreach($var as $i){
      foreach($i as $key => $v){

         if($v) {
         switch ($v->status_display) {
              case "estimate":
                $status_display = 1;
                break;
              case "job order":
                $status_display = 2;
                break;
                  case "receipt":
                $status_display = 3;
          
              }
            $array[] = array(
              'id' => $v->plate_number,
                'product_name' => $v->plate_number,
                'category' => $v->vehicle_type,
                'stock' => $v->mileage,
                'sku' => $v->owner_name,
                'price' => "$656.85",
                'qty' => 679,
                'status' => $v->status_display,
                'image' =>"car-placeholder.jpg",
                'product_brand' => $v->manufacturer." ".$v->vehicle_model." ".$v->transmission ." ".$v->year,
                'car_overview_link' => "/app/car/view/".$v->car_id,
                'job_order_link' => "/app/job-order/".$v->id,
                
              );
            
          } 
      

      
      }

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
