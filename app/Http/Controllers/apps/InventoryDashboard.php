<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\JobOrdersPartServiceOption;
use App\Imports\InventoryImport;

use DB;


class InventoryDashboard extends Controller
{
  public function index()
  {
    return view('content.apps.app-inventory-dashboard');
  }

  public function jsonInventoryList() {
    $Inventory = DB::table('job_orders_part_service_options')
    ->where('status', '=',1)
   
    ->get();
    foreach($Inventory as $value) {
      $array[] = array(
        '' => $value->id,
        'id' => $value->id,
        'item_name' => (($value->value) ? $value->value : ""),
        'part_number' => $value->part_number,
        'cost' => $value->cost,
        'price' => $value->price,
        'stock' => $value->stock,
        'exempt' => $value->exempt,
        'stock_status' => (($value->stock > 0) ? 1 : 0),
        'status' => $value->status,
        '' => '',
      );
    }
    $inventory['data'] = $array;
    return response()->json($inventory);
  }

  public function viewInventoryDetails($item_id) {
    $Inventory = DB::table('job_orders_part_service_options')
    ->where('id', '=', $item_id)
    ->where('status', '=', 1)
    ->get();
    return response()->json(['success'=> true, 'inventoryData' => $Inventory[0]]);
  }

  public function saveInventory($item_id) {

    if($item_id !== 'undefined') {

      JobOrdersPartServiceOption::where("id", $item_id)->update(
      [
        "value" => ((isset($_GET['modalItemName'])) ? $_GET['modalItemName'] : ''),
        "cost" => ((isset($_GET['modalCost'])) ? $_GET['modalCost'] : ''),
        "part_number" => ((isset($_GET['modalPartNumber'])) ? $_GET['modalPartNumber'] : ''),
        "price" => ((isset($_GET['modalPrice'])) ? $_GET['modalPrice'] : ''),
        "stock" => ((isset($_GET['modalStock'])) ? $_GET['modalStock'] : 0),
      ]
    );
     $message = 'Inventory edited successfully!';
    } else {
      $c = new JobOrdersPartServiceOption();
      $c->value    =  ((isset($_GET['modalItemName'])) ? $_GET['modalItemName'] : '');
      $c->cost    =  ((isset($_GET['modalCost'])) ? $_GET['modalCost'] : '');
      $c->part_number    =  ((isset($_GET['modalPartNumber'])) ? $_GET['modalPartNumber'] : '');
      $c->stock    = $_GET['modalStock'] ? $_GET['modalStock'] : "";
      $c->price    = $_GET['modalPrice'] ? $_GET['modalPrice'] : "";
      $c->save();
       $message = 'Inventory added successfully!';

    }
     return response()->json(['success'=> true, 'message' => $message]);
  }

  public function importInventory(Request $request) {
    $request->validate([
      'file' => 'required|file',
    ]);

    $extension = strtolower($request->file('file')->getClientOriginalExtension());
    if (!in_array($extension, ['pdf', 'xlsx', 'xls'], true)) {
      return response()->json([
        'success' => false,
        'message' => 'The file must be a PDF or Excel (.xlsx/.xls) file.',
      ], 422);
    }

    $import = new InventoryImport();
    if ($extension === 'pdf') {
      $import->importFile($request->file('file')->getRealPath());
    } else {
      $import->importExcelFile($request->file('file')->getRealPath());
    }

    $message = "Inventory updated: {$import->updated} item(s).";
    if (count($import->notFound)) {
      $message .= ' Part # not found: ' . implode(', ', $import->notFound);
    }
    if (count($import->unparsed)) {
      $message .= ' Unrecognized lines (skipped): ' . implode(' | ', $import->unparsed);
    }

    return response()->json([
      'success' => true,
      'message' => $message,
      'updated' => $import->updated,
      'not_found' => $import->notFound,
      'unparsed' => $import->unparsed,
    ]);
  }

}
