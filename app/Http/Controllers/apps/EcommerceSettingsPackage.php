<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\JobOrdersPackageOption;
use App\Models\PackageSubItem;

use DB;



class EcommerceSettingsPackage extends Controller
{
  public function index()
  {

        $PackageData = DB::table('job_orders_package_options')
        ->where('status', '=', 1)
        ->orderBy('value','asc')
        ->get(); 

        $jobOrderPackageOption = DB::table('job_orders_package_options')
      ->where('status', '=', 1)
      ->get();
          
    $optionOneHtml = array();

    foreach($jobOrderPackageOption as $options) {
      $optionOneHtml[] = '<option value="'.$options->id.'" >'.$options->value.'</option>';
    }

        
    return view('content.apps.app-settings-car-package-option', ['PackageData' => $PackageData, 'optionOneHtml' => $optionOneHtml]);
  }

  public function getPackage($package_id)
  {

        $PackageData = DB::table('job_orders_package_options')
        ->where('status', '=', 1)
        ->where('id', '=', $package_id)
        ->get(); 


        
      $jobOrderPackageOption = DB::table('job_orders_part_service_options')
      ->where('status', '=', 1)
      ->get();
          
    $optionOneHtml = array();

    $optionOneHtml[] = '<option value="">Select Parts</option>';
    foreach($jobOrderPackageOption as $options) {
      $optionOneHtml[] = '<option value="'.$options->id.'" >'.$options->value.'</option>';
    }


      return response()->json(['success'=> true, 'PackageData' => $PackageData[0], 'optionOneHtml' => $optionOneHtml]);

  }


  
   public function getPartDetails($part_id) {


       $PartData = DB::table('job_orders_part_service_options')
        ->where('status', '=', 1)
        ->where('id', '=', $part_id)
        ->get(); 

      return response()->json(['success'=> true, 'id' => $PartData[0]->id, 'part_number' => $PartData[0]->part_number, 'package_unit_cost' => $PartData[0]->cost, 'package_details' => $PartData[0]->value, 
      'package_unit_selling_price_with_labor' => $PartData[0]->price,
    ]);

   }


   public function getPackageSubItem($package_sub_item_id)
  {

        $PackageSubData = DB::table('package_sub_items')
        ->where('status', '=', 1)
        ->where('id', '=', $package_sub_item_id)
        ->get(); 


        $selected = $PackageSubData[0]->part_id;


        $jobOrderPackageOption = DB::table('job_orders_part_service_options')
      ->where('status', '=', 1)
      ->get();
                

      $optionOneHtml = array();

      foreach($jobOrderPackageOption as $options) {
        $optionOneHtml[] = '<option value="'.$options->id.'" '.(($selected == $options->id) ? "selected" : "").'>'.$options->value.'</option>';
      }



      return response()->json(['success'=> true, 'PackageSubData' => $PackageSubData[0], 'optionOneHtml' => $optionOneHtml]);

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



    
     public function deletePackageSubItem($package_sub_item_id) {
      $delete = PackageSubItem::where('id',$package_sub_item_id)->delete();
      return response()->json( ['success' => true, 'message' => 'Sub Package has been deleted!']);
    }
    

     public function showPackageOption() {
       $PackageData = DB::table('job_orders_package_options')
        ->where('status', '=', 1)
        ->get(); 

        $key = 1;
      foreach($PackageData as $key => $data) {
        $key++;
        $packageHtml[] = '<tr>
        <td>'.$key.'</td>
          <td colspan="5">'.$data->value.'</td>
          <td>'.$data->package_price.'</td>
          <td class="text-end">
            <div class="dropdown pe-3">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addNewPackageSubItemOption" onclick="addPackageSubItem('.$data->id.')">Add Item</button>
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></button>
              <div class="dropdown-menu">
                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#addNewPackageOption" onclick="editPackage('.$data->id.')"><i class="mdi mdi-pencil-outline me-1"></i> Edit</a>
                <a class="dropdown-item" onclick="promptDeletePackage('.$data->id.')"><i class="mdi mdi-delete-outline me-1"></i> Delete</a>
              </div>
            </div>
          </td>
        </tr>';

      $PackageSubItemData = DB::table('package_sub_items')
        ->where('package_id', '=', $data->id)
        ->where('status', '=', 1)
        ->get(); 

        $k = 1;
        foreach($PackageSubItemData as $k => $sub) {
          if($k == 0) {

            $packageHtml[] = '
                    <tr  class="table-light tbl-sub">
                      <td></td>
                      <td>Item Details</td>
                      <td>Qty</td>
                      <td>Unit Cost</td>
                      <td>Total Cost</td>
                      <td>Sell Price with Labor</td>
                      <td>Sell Price</td>
                      <td>Actions</td>
                    </tr>';
          }

          $packageHtml[] = '<tr class="tbl-sub">
        <td></td>
          <td>'.$sub->package_details.'</td>
          <td>'.$sub->package_qty.'</td>
          <td>₱'.$sub->package_unit_cost.'</td>
          <td>₱'.$sub->package_total_cost.'</td>
          <td>₱'.$sub->package_unit_selling_price_with_labor.'</td>
          <td>₱'.$sub->package_sell_price.'</td>
          <td class="text-end">
            <div class="dropdown pe-3">

              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></button>
              <div class="dropdown-menu">
                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#addNewPackageSubItemOption" onclick="editPackageSubItem('.$sub->id.')"><i class="mdi mdi-pencil-outline me-1"></i> Edit</a>
                <a class="dropdown-item" onclick="promptDeletePackageSubItem('.$sub->id.')"><i class="mdi mdi-delete-outline me-1"></i> Delete</a>
              </div>
            </div>
          </td>
        </tr>';
          $k++;
        }
      }





      return response()->json( ['packageHtml' => $packageHtml]);
    }



     public function savePackageSubItem($package_id) {

        $c = new PackageSubItem();
        $c->package_id = ((isset($_GET['hidden_package_id'])) ? $_GET['hidden_package_id'] : "");
        $c->package_qty = ((isset($_GET['package_qty'])) ? $_GET['package_qty'] : "");
        $c->package_details = ((isset($_GET['package_details'])) ? $_GET['package_details'] : "");
        $c->package_part_number = ((isset($_GET['package_part_number'])) ? $_GET['package_part_number'] : "");
        $c->supplier = ((isset($_GET['supplier'])) ? $_GET['supplier'] : "");
        $c->supplier_inv = ((isset($_GET['supplier_inv'])) ? $_GET['supplier_inv'] : "");
        $c->package_unit_cost = ((isset($_GET['package_unit_cost'])) ? $_GET['package_unit_cost'] : "");
        $c->package_total_cost = ((isset($_GET['package_total_cost'])) ? $_GET['package_total_cost'] : "");
        $c->package_unit_selling_price_with_labor = ((isset($_GET['package_unit_selling_price_with_labor'])) ? $_GET['package_unit_selling_price_with_labor'] : "");
        $c->package_sell_price = ((isset($_GET['package_sell_price'])) ? $_GET['package_sell_price'] : "");
        $c->part_id = ((isset($_GET['hidden_package_part_id'])) ? $_GET['hidden_package_part_id'] : "");
        $c->save();
    }

     public function updatePackageSubItem($package_sub_item_id) {

       PackageSubItem::where("id", $package_sub_item_id)->update(
          [
            "package_qty" => ((isset($_GET['package_qty'])) ? $_GET['package_qty'] : "1"),
            "part_id" => ((isset($_GET['hidden_package_part_id'])) ? $_GET['hidden_package_part_id'] : ""),
            "package_details" => ((isset($_GET['package_details'])) ? $_GET['package_details'] : NULL),
            "package_part_number" => ((isset($_GET['package_part_number'])) ? $_GET['package_part_number'] : NULL),
            "supplier" => ((isset($_GET['supplier'])) ? $_GET['supplier'] : ""),
            "supplier_inv" => ((isset($_GET['supplier_inv'])) ? $_GET['supplier_inv'] : ""),
            "package_unit_cost" => ((isset($_GET['package_unit_cost'])) ? $_GET['package_unit_cost'] : ""),
            "package_total_cost" => ((isset($_GET['package_total_cost'])) ? $_GET['package_total_cost'] : ""),
            "package_unit_selling_price_with_labor" => ((isset($_GET['package_unit_selling_price_with_labor'])) ? $_GET['package_unit_selling_price_with_labor'] : NULL),
            "package_sell_price" => ((isset($_GET['package_sell_price'])) ? $_GET['package_sell_price'] : ""),
          ]);

      return response()->json( ['success' => true, 'message' => 'Sub Package has been update!']);

     }



    
}
