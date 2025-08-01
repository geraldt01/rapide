<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory;

use DB;


class InventoryDashboard extends Controller
{
  public function index()
  {
    return view('content.apps.app-inventory-dashboard');
  }

  public function jsonInventoryList() {
    $Inventory = DB::table('inventories')
    ->where('status', '=', 1)
    ->get();
    foreach($Inventory as $value) {
      $array[] = array(
        '' => $value->id,
        'id' => $value->id,
        'item_name' => $value->name,
        'stock' => $value->stock,
        'stock_status' => (($value->stock > 0) ? 1 : 0),
        'status' => $value->status,
        '' => '',
      );
    }
    $inventory['data'] = $array;
    return response()->json($inventory);
  }

  public function viewInventoryDetails($item_id) {
    $Inventory = DB::table('inventories')
    ->where('id', '=', $item_id)
    ->where('status', '=', 1)
    ->get();
    return response()->json(['success'=> true, 'inventoryData' => $Inventory[0]]);
  }

  public function saveInventory($item_id) {

    if($item_id) {
      Inventory::where("id", $item_id)->update(
      [
        "name" => ((isset($_GET['modalItemName'])) ? $_GET['modalItemName'] : ''),
        "stock" => ((isset($_GET['modalStock'])) ? $_GET['modalStock'] : 0),
      ]
    );
     $message = 'Inventory edited successfully!';
    } else {
      $c = new Inventory();
      $c->name    = $_GET['car_id'] ? $_GET['car_id'] : "";
      $c->stock    = $_GET['car_id'] ? $_GET['car_id'] : "";
      $c->price    = $_GET['car_id'] ? $_GET['car_id'] : "";
      $c->save();
       $message = 'Inventory added successfully!';

    }
   
     return response()->json(['success'=> true, 'message' => $message]);
  }
  

}
