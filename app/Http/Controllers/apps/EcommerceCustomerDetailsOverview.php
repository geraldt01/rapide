<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobOrder;
use App\Models\Car;
use App\Models\JobOrdersPartService;
use App\Models\JobOrdersLabor;
use App\Models\JobOrdersPackage;
use App\Models\InvoiceNumber;
use App\Models\RepairEstimateNumber;
use App\Models\JobOrderNumber;
use App\Models\Owner;

use DB;



class EcommerceCustomerDetailsOverview extends Controller
{
  public function index($car_id)
  {

    $carInfo = DB::table('cars')
    ->where('cars.id', '=', $car_id)
    ->join('owners', 'owners.id', '=', 'cars.owner_id')
   ->select('cars.fuel_type as fuel_type', 'cars.transmission as transmission', 'cars.id as car_id', 'cars.year as year', 'cars.mileage as mileage', 'owners.id as owner_id', 'cars.manufacturer as manufacturer', 'cars.vehicle_type as vehicle_type', 'cars.vehicle_model as vehicle_model', 'cars.year as year', 'cars.plate_number as plate_number', 'cars.status as status', 'owners.owner_name as owner_name', 'owners.address as address', 'owners.mobile_number as mobile_number' )
    ->get();

    $otherCars = DB::table('owners')
    ->where('cars.id', '!=', $car_id)
    ->where('owners.id', '=', $carInfo[0]->owner_id)
    ->join('cars', 'cars.owner_id', '=', 'owners.id')
    ->get();
 
    $htmlAddCar = "";
    if(isset($otherCars['id']) == false) {
      $htmlAddCar = '<div class="col-md-6 mb-4">
        <div class="card">
          <div class="card-body section-add-car">
            <div class="card-icon mb-3">
              <div class="avatar">
                      <div class="avatar-initial rounded bg-label-primary"><i class="mdi mdi-car mdi-24px"></i>
                </div>
              </div>
            </div>
            <div class="card-info">
              <div class="d-flex justify-content-center">
                <a href="javascript:;" class="btn btn-primary me-3" data-bs-target="#addNewCar" data-bs-toggle="modal" onclick="getDropdown()">Add Car</a>
              </div>
            </div>
          </div>
        </div>
      </div>';
    }

    

    

    $getLatestInvoice = DB::table('invoice_numbers')->where('status', '=', 1)
     ->orderBy('id','desc')
    ->first();
   
    return view('content.apps.app-ecommerce-customer-details-overview', [ 'selectedCar' => $carInfo, 'otherCars' => $otherCars, 'htmlAddCar' => $htmlAddCar, 'invoice_number' => $getLatestInvoice->value+1]);
  }

  public function getDroptownOptions() {
     $carManufacturerOptions = DB::table('car_manufacturer_options')->where('status', '=', 1)
     ->orderBy('value','asc')
    ->get();

      $carVehicleTypeOptions = DB::table('car_vehicle_type_options')->where('status', '=', 1)
      ->orderBy('value','asc')
    ->get();


      $vehicleHtml = array();
      $vehicleHtml[] =  '<option value="">Select Vehicle Type</option>';
    foreach($carVehicleTypeOptions as $v) {
      $vehicleHtml[] =  '<option value="'.$v->value.'">'.$v->value.'</option>';
    }
      
      $manufacturerHtml = array();
        $manufacturerHtml[] =  '<option value="">Select Car Manufacturer</option>';
    foreach($carManufacturerOptions as $m) {
      $manufacturerHtml[] =  '<option value="'.$m->value.'">'.$m->value.'</option>';
    }


  return response()->json(['carVehicleTypeOptions'=> $vehicleHtml, 'carManufacturerOptions'=> $manufacturerHtml, 'status' => true]);
  }

  

  public function saveJobOrder($car_id) {

    $checkExistingJobOrder = DB::table('job_orders')
    ->where('car_id', '=', $car_id)
    ->where('date', '=', $_GET['date'])
    ->get();

    if(isset($checkExistingJobOrder[0])) {
     return response()->json(['success'=> false, 'message' => 'Job Order already exist!']);

    } else {
      $numbers_only = preg_replace("/[^0-9]/", "", $_GET['est']);
      $checkInvoiceNumber = DB::table('repair_estimate_numbers')->where('value', '=', $numbers_only)
      ->get();

      $final_invoice_number = $numbers_only + 1;

      if(isset($checkInvoiceNumber[0])) {
        $i = new RepairEstimateNumber();
        $i->value = $final_invoice_number;
        $i->save();
      } else {
        $getLatestInvoice = DB::table('repair_estimate_numbers')->where('status', '=', 1)
          ->orderBy('id','desc')
          ->get();

          $final_invoice_number = $getLatestInvoice[0]->value + 1;
          $i = new RepairEstimateNumber();
          $i->value = $final_invoice_number;
          $i->save();
      }



       $getOwner = DB::table('cars')
      ->where('cars.id', '=', $_GET['car_id'])
      ->join('owners', 'owners.id', '=', 'cars.owner_id')
      ->get();




      $c = new JobOrder();
      $c->car_id    = $_GET['car_id'] ? $_GET['car_id'] : "";
      $c->job_order_number    = $final_invoice_number;
      $c->date   = $_GET['date'] ? $_GET['date'] : "";
      $c->plate_number            = $_GET['modalPlateNumber'] ? $_GET['modalPlateNumber'] : "";
      $c->manufacturer    = $_GET['modalManufacturer'] ? $_GET['modalManufacturer'] : "";
      $c->model    = $_GET['modalVehicleModel'] ? $_GET['modalVehicleModel'] : "";
      $c->year    = $_GET['modalYear'] ? $_GET['modalYear'] : "";
      $c->mileage    = $_GET['modalMileage'] ? $_GET['modalMileage'] : "";
      $c->status_display    = $_GET['modalStatus'] ? $_GET['modalStatus'] : "";
      $c->customer_name    = $getOwner[0]->owner_name;
      $c->save();

     Car::where("id", $_GET['car_id'])->update(
      [
        "mileage" => $_GET['modalMileage'],
      ]
      );



      for($x=0;$x<=19;$x++) {
        if($x == 0) {
          $pck = new JobOrdersPackage();
          $pck->item_number = 1;
          $pck->job_order_id = $c->id;
          $pck->save(); 
        }
        $prt = new JobOrdersPartService();
        $prt->item_number =$x+1;
        $prt->job_order_id = $c->id;
        $prt->part_value = "";
        $prt->part_number = "";
        if($x > 9) {
        $prt->status = 2;
        }

        $prt->save();

        $lbr = new JobOrdersLabor();
        $lbr->job_order_id = $c->id;
        $lbr->item_number =$x+1;
        $lbr->labor_value = "";
        $lbr->part_number = "";
        if($x > 9) {
          $lbr->status = 2;
        }
        $lbr->save();

      } 
      return response()->json(['success'=> true, 'message' => 'Job Order added successfully!']);
      }
  }

  public function jsonJobOrder($car_id) {
    $jobOrderInfo = DB::table('job_orders')
    ->where('car_id', '=', $car_id)
  //  ->where('ex_job_order_id', '=', NULL)
    ->OrderBy('date', 'asc')
    ->get();

      $k = 0;
      $key = 1;
      $a = 1;
        $array = array();

 
    foreach ($jobOrderInfo as $k => $v) {
      $opt[$v->date][] = $v;
    }
      
    if(isset($opt)) {
     foreach ($opt as $key => $value) {
      $htmlJS = array();
      $number = array();
      if(isset($value[0])) {
        if($value[0]->status == '1') {
         $jo_id = $value[0]->id;

             $htmlJS[] = '<a href="/app/job-order/'.$value[0]->id.'"><span class="badge rounded-pill bg-label-warning" text-capitalized="">'.$value[0]->status_display.'</span></a>';
             $number[] = "EST#".$value[0]->job_order_number;
            $status = $value[0]->status;
            $date = $value[0]->date;

          }
        }

       if(isset($value[1])) {
        if($value[1]->status == '2') {
         $jo_id = $value[1]->id;
          $htmlJS[] = '<a href="/app/job-order/'.$value[1]->id.'"><span class="badge rounded-pill bg-label-info" text-capitalized="">'.$value[1]->status_display.'</span></a>';
          $number[] = " JO#".$value[1]->job_order_number;
          $status = $value[1]->status;
          $date = $value[1]->date;
        }
      

      }
         $array[] = array('id' => $jo_id,
        'counter' => $a,
        'order' => $number,
        'customer' => "Gabrielle Feyer",
        'email' => "gfeyer0@nyu.edu",
        'avatar' => "8.png",
        'payment' => 1,
        'status' => $status,
        'js_list' => $htmlJS,
        'spent' => "-",
        'method' => "paypal_logo",
        'date' => $date,
        'time' => "2:11 AM",
        'method_number' => 6522);
      $key++;
      $a++;
    }
  } 
  

    $jo['data'] = $array;
    return response()->json($jo);
  }
  

    public function addNewCar() {
 
    $c = new Car();
    $c->owner_id   = $_GET['owner_id'] ? $_GET['owner_id'] : "";
    $c->plate_number            = $_GET['modalPlateNumber'] ? $_GET['modalPlateNumber'] : "";
    $c->manufacturer    = $_GET['modalManufacturer'] ? $_GET['modalManufacturer'] : "";
    $c->vehicle_model    = $_GET['modalVehicleModel'] ? $_GET['modalVehicleModel'] : "";
    // $c->vehicle_type    = $_GET['modalVehicleType'] ? $_GET['modalVehicleType'] : "";
    $c->mileage    = $_GET['modalMileage'] ? $_GET['modalMileage'] : "";
    $c->fuel_type    = $_GET['modaladdFuelType'] ? $_GET['modaladdFuelType'] : "";
    $c->transmission    = $_GET['modaladdTransmission'] ? $_GET['modaladdTransmission'] : "";
    $c->year    = $_GET['modaladdYearModel'] ? $_GET['modaladdYearModel'] : "";

    
    $c->save();

      $car_id  = $c->id; 
     $carInfo = DB::table('cars')
    ->where('cars.id', '=', $car_id)
    ->join('owners', 'owners.id', '=', 'cars.owner_id')
   ->select('cars.id as car_id', 'owners.id as owner_id', 'cars.manufacturer as manufacturer', 'cars.vehicle_type as vehicle_type', 'cars.vehicle_model as vehicle_model', 'cars.year as year', 'cars.plate_number as plate_number', 'cars.status as status', 'owners.owner_name as owner_name', 'owners.address as address', 'owners.mobile_number as mobile_number' )
    ->get();

   
    $htmlAddCar = "";
    foreach($carInfo as $car) {
      $htmlAddCar = '
        <a href="/app/car/view/'.$car_id.'">
          <div class="card h-100">
            <div class="card-body">
              <div class="card-icon mb-3">
                <div class="avatar">
                  <div class="avatar-initial rounded bg-label-primary"><i class="mdi mdi-car mdi-24px"></i>
                  </div>
                </div>
              </div>
              <div class="card-info">
                <h4 class="card-title mb-3">'.$car->plate_number.'</h4>
                <div class="d-flex align-items-end mb-1 gap-1">
                  <h4 class="text-primary mb-0">'.$car->manufacturer.'</h4>
                  <p class="mb-0"> '.$car->vehicle_model.' '.$car->year.' </p>

                </div>
              </div>
            </div>
          </div>
        </a>
      ';
    }

   
     return response()->json(['success'=> true, 'message' => 'New Car Added!', 'selectedCar' => $carInfo, 'htmlAddCar' => $htmlAddCar]);
  }



  function getEstimateNumber() {
    
    
    $getLatestEstimateNumber = DB::table('repair_estimate_numbers')->where('status', '=', 1)
     ->orderBy('id','desc')
    ->first();

     return response()->json(['success'=> true, 'estimate_number' => $getLatestEstimateNumber->value+1]);
  }

  function editCustomer() {
    $customer_id = $_GET['hidden-customer-id-edit'];
    $modalEditCustomerName = $_GET['modalEditCustomerName'];
    $modalEditAddress = $_GET['modalEditAddress'];
    $modalEditContact = $_GET['modalEditContact'];

    Owner::where("id", $customer_id)->update(
      [
        "owner_name" => $modalEditCustomerName,
        "address" => $modalEditAddress,
        "mobile_number" => $modalEditContact,
      ]);

     return response()->json(['success'=> true, 'owner_name' => $modalEditCustomerName, 'address' => $modalEditAddress, 'mobile_number' => $modalEditContact, 'message' => 'Customer Information updated!' ]);

  }


  
}
