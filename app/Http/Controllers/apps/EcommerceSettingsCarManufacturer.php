<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CarManufacturerOption;
use DB;



class EcommerceSettingsCarManufacturer extends Controller
{
  public function index()
  {

        $ManufacturerData = DB::table('car_manufacturer_options')
        ->where('status', '=', 1)
        ->get(); 

    return view('content.apps.app-settings-car-manufacturer-option', ['ManufacturerData' => $ManufacturerData]);
  }

  public function getManufacturer($manufacturer_id)
  {

        $ManufacturerData = DB::table('car_manufacturer_options')
        ->where('status', '=', 1)
        ->where('id', '=', $manufacturer_id)
        ->get(); 

      return response()->json(['success'=> true, 'ManufacturerData' => $ManufacturerData[0]]);

  }

    public function updateManufacturer($manufacturer_id)
  {

      $ManufacturerData = DB::table('car_manufacturer_options')
      ->where('status', '=', 1)
      ->where('id', '=', $manufacturer_id)
      ->get(); 

     CarManufacturerOption::where("id", $manufacturer_id)->update(
      [
        "value" => $_GET['modalManufacturer'],
      ]);

 
    return response()->json( ['ManufacturerData' => $ManufacturerData, 'success'=> true, 'message' => 'Manufacturer has been updated!']);
  }


  
    public function saveManufacturer() {
        $c = new CarManufacturerOption();
        $c->value = ((isset($_GET['modalManufacturer'])) ? $_GET['modalManufacturer'] : "");
        $c->save();

       return response()->json( ['success' => true, 'message' => 'Manufacturer has been added!']);

    }

  
  
    public function showManufacturer() {
       $ManufacturerData = DB::table('car_manufacturer_options')
        ->where('status', '=', 1)
        ->get(); 

        $key = 1;
      foreach($ManufacturerData as $key => $data) {
        $key++;
        $manufacturerHtml[] = '<tr>
        <td>'.$key.'</td>
          <td>'.$data->value.'</td>
          <td class="text-end">
            <div class="dropdown pe-3">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></button>
              <div class="dropdown-menu">
                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#addNewManufacturerOption" onclick="editManufacturer('.$data->id.')"><i class="mdi mdi-pencil-outline me-1"></i> Edit</a>
                <a class="dropdown-item" onclick="promptDeleteManufacturer('.$data->id.')"><i class="mdi mdi-delete-outline me-1"></i> Delete</a>
              </div>
            </div>
          </td>
        </tr>';
      }

      return response()->json( ['manufacturerHtml' => $manufacturerHtml]);
    }



     public function deleteManufacturer($manufacturer_id) {
      $delete = CarManufacturerOption::where('id',$manufacturer_id)->delete();
      return response()->json( ['success' => true, 'message' => 'Manufacturer has been deleted!']);
    }


    
}
