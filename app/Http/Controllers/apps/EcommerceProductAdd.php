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
    $c->vehicle_type    = $_GET['vehicleType'] ? $_GET['vehicleType'] : "";
    $c->vehicle_model   = $_GET['vehicleModel'] ? $_GET['vehicleModel'] : "";
    $c->year            = $_GET['yearModel'] ? $_GET['yearModel'] : "";
    $c->plate_number    = $_GET['plateNumber'] ? $_GET['plateNumber'] : "";
    $c->save();



        

      $carManufacturerOptions = DB::table('car_manufacturer_options')->where('status', '=', 1)
    ->get();



    
      $carVehicleTypeOptions = DB::table('car_vehicle_type_options')->where('status', '=', 1)
    ->get();

     return response()->json(['success'=> true, 'message' => 'Car added successfully!', 'carManufacturerOptions' => $carManufacturerOptions, 'carVehicleTypeOptions' => $carVehicleTypeOptions]);

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



}
