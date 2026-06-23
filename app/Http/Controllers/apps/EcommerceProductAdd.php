<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Owner;
use App\Models\CarManufacturerOption;
use App\Models\CarVehicleTypeOption;
use Illuminate\Support\Str;
use DB;


class EcommerceProductAdd extends Controller
{
  public function index()
  {
    

      $carManufacturerOptions = DB::table('car_manufacturer_options')->where('status', '=', 1)
      ->orderBy('value','asc')
    ->get();
    
      $carVehicleTypeOptions = DB::table('car_vehicle_type_options')->where('status', '=', 1)

    ->get();


    

    return view('content.apps.app-ecommerce-product-add', ['carManufacturerOptions' => $carManufacturerOptions, 'carVehicleTypeOptions' => $carVehicleTypeOptions]);

  }

  public function EcommerceProductAdd(Request $request) {

    $o = new Owner();
    $o->owner_name = $_GET['ownerName'] ? $_GET['ownerName'] : "";
    $o->address    = $_GET['ownerAddress'] ? $_GET['ownerAddress'] : "";
    $o->mobile_number     = $_GET['mobileNumber'] ? $_GET['mobileNumber'] : "";
    $o->save();


    $c = new Car();
    $c->owner_id        = $o->id;
    $c->manufacturer    = $_GET['manufacturer'] ? $_GET['manufacturer'] : "";
    // $c->vehicle_type    = $_GET['vehicleType'] ? $_GET['vehicleType'] : "";
    $c->vehicle_model   = $_GET['vehicleModel'] ? $_GET['vehicleModel'] : "";
    $c->year            = $_GET['yearModel'] ? $_GET['yearModel'] : "";
    $c->plate_number    = $_GET['plateNumber'] ? $_GET['plateNumber'] : "";
    $c->transmission    = $_GET['transmission'] ? $_GET['transmission'] : "";
    $c->fuel_type    = $_GET['fuelType'] ? $_GET['fuelType'] : "";
    $c->mileage    = $_GET['mileage'] ? $_GET['mileage'] : "";
    $c->save();

      $carManufacturerOptions = DB::table('car_manufacturer_options')->where('status', '=', 1)
    ->get();

      $carVehicleTypeOptions = DB::table('car_vehicle_type_options')->where('status', '=', 1)
    ->get();

     return response()->json(['success'=> true, 'new_car_id' =>  $c->id, 'message' => 'Car added successfully!', 'carManufacturerOptions' => $carManufacturerOptions, 'carVehicleTypeOptions' => $carVehicleTypeOptions]);

  }


  function searchCar() {
    $carData = DB::table('cars')->where('status', '=', 1)
    ->get();

    $cars = array();
    foreach($carData as $car) {
      $cars['pages'][] = array(
        'name' => $car->plate_number,
        'icon' => "mdi-car",
        'url' => "app/car/view/".$car->id,
      );
    }
     return response()->json([$cars]);
  }

  function dashboardData($date) {
    date_default_timezone_set('Asia/Manila');

      $totalSalesData = DB::table('job_orders')
    ->where('date', $date)
    ->where('status', '>', '0')
    ->get();
    $key = 0;

   
 
    foreach($totalSalesData as  $d) {
       $data[$d->plate_number][] = $d;
    }
    $estimateTotal = 0;
    $jobOrderTotal = 0;
    $totalCars = 0;
    
    if(!isset($data)) {
      $data = array();
    }
    foreach($data as $key => $final) {
      if($final[0]->status == 1 && !isset($final[1])) {
        $estimateTotal +=1;
      } else {
        $jobOrderTotal +=1;
      }
      $var[$key][] = $final[0];
    }


    $totalCars = $estimateTotal + $jobOrderTotal;
     return response()->json(['success'=> true, 'total_estimate' =>  $estimateTotal, 'total_job_order' =>  $jobOrderTotal, 'total_cars' => $totalCars ]);

  }

}
