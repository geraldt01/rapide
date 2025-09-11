<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\JobOrdersPackageOption;
use DB;



class EcommerceSettingsPackage extends Controller
{
  public function index()
  {

        $PackageData = DB::table('job_orders_package_options')
        ->where('status', '=', 1)
        ->orderBy('value','asc')
        ->get(); 

    return view('content.apps.app-settings-car-package-option', ['PackageData' => $PackageData]);
  }

  public function getPackage($package_id)
  {

        $PackageData = DB::table('job_orders_package_options')
        ->where('status', '=', 1)
        ->where('id', '=', $package_id)
        ->get(); 

      return response()->json(['success'=> true, 'PackageData' => $PackageData[0]]);

  }

    public function updatePackage($package_id)
  {

      $PackageData = DB::table('job_orders_package_options')
      ->where('status', '=', 1)
      ->where('id', '=', $package_id)
      ->get(); 

     JobOrdersPackageOption::where("id", $package_id)->update(
      [
        "value" => $_GET['modalPackage'],
        "package_price" => $_GET['modalPrice'],
      ]);

 
    return response()->json( ['PackageData' => $PackageData, 'success'=> true, 'message' => 'Vehicle Type has been updated!']);
  }


  
    public function savePackage() {
        $c = new JobOrdersPackageOption();
        $c->value = ((isset($_GET['modalPackage'])) ? $_GET['modalPackage'] : "");
        $c->package_price = ((isset($_GET['modalPrice'])) ? $_GET['modalPrice'] : "");
        $c->save();

       return response()->json( ['success' => true, 'message' => 'Vehicle Type has been added!']);

    }

  
  
    public function showPackage() {
       $PackageData = DB::table('job_orders_package_options')
        ->where('status', '=', 1)
        ->get(); 

        $key = 1;
      foreach($PackageData as $key => $data) {
        $key++;
        $packageHtml[] = '<tr>
        <td>'.$key.'</td>
          <td>'.$data->value.'</td>
          <td>'.$data->package_price.'</td>
          <td class="text-end">
            <div class="dropdown pe-3">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></button>
              <div class="dropdown-menu">
                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#addNewPackageOption" onclick="editPackage('.$data->id.')"><i class="mdi mdi-pencil-outline me-1"></i> Edit</a>
                <a class="dropdown-item" onclick="promptDeletePackage('.$data->id.')"><i class="mdi mdi-delete-outline me-1"></i> Delete</a>
              </div>
            </div>
          </td>
        </tr>';
      }

      return response()->json( ['packageHtml' => $packageHtml]);
    }



     public function deletePackage($package_id) {
      $delete = JobOrdersPackageOption::where('id',$package_id)->delete();
      return response()->json( ['success' => true, 'message' => 'Package has been deleted!']);
    }


    
}
