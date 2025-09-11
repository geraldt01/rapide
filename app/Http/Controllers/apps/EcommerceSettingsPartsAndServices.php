<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\JobOrdersPartServiceOption;
use DB;



class EcommerceSettingsPartsAndServices extends Controller
{
  public function index()
  {

        $PartsAndServicesData = DB::table('job_orders_part_service_options')
        ->where('status', '=', 1)
        ->orderBy('value','asc')
        ->get(); 

    return view('content.apps.app-settings-car-parts-and-services-option', ['PartsAndServicesData' => $PartsAndServicesData]);
  }

  public function getPartsAndServices($parts_and_services_id)
  {

        $PartsAndServicesData = DB::table('job_orders_part_service_options')
        ->where('status', '=', 1)
        ->where('id', '=', $parts_and_services_id)
        ->get(); 

      return response()->json(['success'=> true, 'PartsAndServicesData' => $PartsAndServicesData[0]]);

  }

    public function updatePartsAndServices($parts_and_services_id)
  {

      $PartsAndServicesData = DB::table('job_orders_part_service_options')
      ->where('status', '=', 1)
      ->where('id', '=', $parts_and_services_id)
      ->get(); 

     JobOrdersPartServiceOption::where("id", $parts_and_services_id)->update(
      [
        "value" => $_GET['modalPartsAndServices'],
      ]);

 
    return response()->json( ['PartsAndServicesData' => $PartsAndServicesData, 'success'=> true, 'message' => 'Vehicle Type has been updated!']);
  }


  
    public function savePartsAndServices() {
        $c = new JobOrdersPartServiceOption();
        $c->value = ((isset($_GET['modalPartsAndServices'])) ? $_GET['modalPartsAndServices'] : "");
        $c->save();

       return response()->json( ['success' => true, 'message' => 'Vehicle Type has been added!']);

    }

  
  
    public function showPartsAndServices() {
       $PartsAndServicesData = DB::table('job_orders_part_service_options')
        ->where('status', '=', 1)
        ->get(); 

        $key = 1;
      foreach($PartsAndServicesData as $key => $data) {
        $key++;
        $partsAndServicesHtml[] = '<tr>
        <td>'.$key.'</td>
          <td>'.$data->value.'</td>
          <td class="text-end">
            <div class="dropdown pe-3">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></button>
              <div class="dropdown-menu">
                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#addNewPartsAndServicesOption" onclick="editPartsAndServices('.$data->id.')"><i class="mdi mdi-pencil-outline me-1"></i> Edit</a>
                <a class="dropdown-item" onclick="promptDeletePartsAndServices('.$data->id.')"><i class="mdi mdi-delete-outline me-1"></i> Delete</a>
              </div>
            </div>
          </td>
        </tr>';
      }

      return response()->json( ['partsAndServicesHtml' => $partsAndServicesHtml]);
    }



     public function deletePartsAndServices($parts_and_services_id) {
      $delete = JobOrdersPartServiceOption::where('id',$parts_and_services_id)->delete();
      return response()->json( ['success' => true, 'message' => 'Parts and Services has been deleted!']);
    }


    
}
