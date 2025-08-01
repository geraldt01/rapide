<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CarVehicleTypeOption;
use DB;



class EcommerceSettingsCarVehicleType extends Controller
{
  public function index()
  {

        $VehicleTypeData = DB::table('car_vehicle_type_options')
        ->where('status', '=', 1)
        ->get(); 

    return view('content.apps.app-settings-car-vehicle-type-option', ['VehicleTypeData' => $VehicleTypeData]);
  }

  public function getVehicleType($vehicle_type_id)
  {

        $VehicleTypeData = DB::table('car_vehicle_type_options')
        ->where('status', '=', 1)
        ->where('id', '=', $vehicle_type_id)
        ->get(); 

      return response()->json(['success'=> true, 'VehicleTypeData' => $VehicleTypeData[0]]);

  }

    public function updateVehicleType($vehicle_type_id)
  {

      $VehicleTypeData = DB::table('car_vehicle_type_options')
      ->where('status', '=', 1)
      ->where('id', '=', $vehicle_type_id)
      ->get(); 

     CarVehicleTypeOption::where("id", $vehicle_type_id)->update(
      [
        "value" => $_GET['modalVehicleType'],
      ]);

 
    return response()->json( ['VehicleTypeData' => $VehicleTypeData, 'success'=> true, 'message' => 'Vehicle Type has been updated!']);
  }


  
    public function saveVehicleType() {
        $c = new CarVehicleTypeOption();
        $c->value = ((isset($_GET['modalVehicleType'])) ? $_GET['modalVehicleType'] : "");
        $c->save();

       return response()->json( ['success' => true, 'message' => 'Vehicle Type has been added!']);

    }

  
  
    public function showVehicleType() {
       $VehicleTypeData = DB::table('car_vehicle_type_options')
        ->where('status', '=', 1)
        ->get(); 

        $key = 1;
      foreach($VehicleTypeData as $key => $data) {
        $key++;
        $vehicleTypeHtml[] = '<tr>
        <td>'.$key.'</td>
          <td>'.$data->value.'</td>
          <td class="text-end">
            <div class="dropdown pe-3">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></button>
              <div class="dropdown-menu">
                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#addNewVehicleTypeOption" onclick="editVehicleType('.$data->id.')"><i class="mdi mdi-pencil-outline me-1"></i> Edit</a>
                <a class="dropdown-item" onclick="promptDeleteVehicleType('.$data->id.')"><i class="mdi mdi-delete-outline me-1"></i> Delete</a>
              </div>
            </div>
          </td>
        </tr>';
      }

      return response()->json( ['vehicleTypeHtml' => $vehicleTypeHtml]);
    }



     public function deleteVehicleType($vehicle_type_id) {
      $delete = CarVehicleTypeOption::where('id',$vehicle_type_id)->delete();
      return response()->json( ['success' => true, 'message' => 'Vehicle Type has been deleted!']);
    }


    
}
