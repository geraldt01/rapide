<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

use App\Exports\SalesReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CostOfSales extends Controller
{
  public function index()
  {
    return view('content.dashboard.cost-of-sales');
  }

  public function jsonCostOfSalesList($date) {
    date_default_timezone_set("Asia/Manila");

    $jobOrderInfo = DB::table('cars')
     ->join('job_orders', 'job_orders.car_id', '=', 'cars.id')
     ->join('owners', 'owners.id', '=', 'cars.owner_id')
     ->select('job_orders.id as job_order_id', 'job_orders.job_order_number as job_order_number', 
    'cars.id as car_id', 
    'job_orders.date as date', 
    'job_orders.job_order_number as job_order_number', 
    'job_orders.invoice_number as invoice_number', 
    'cars.plate_number as plate_number', 
    'cars.manufacturer as manufacturer', 
    'cars.vehicle_type as vehicle_type', 
    'cars.vehicle_model as vehicle_model', 
    'cars.transmission as transmission', 
    'cars.fuel_type as fuel_type', 
    'job_orders.mileage as mileage', 
    // 'job_orders.status as status', 
    'job_orders.status as status_display', 
    'cars.owner_id as owner_id', 
    'cars.vehicle_type as vehicle_type', 
    'cars.year as year', 
    'owners.owner_name as owner_name', 
    'owners.address as address', 
    'owners.mobile_number as mobile_number', 
  )
   ->where('job_orders.date', $date)
   ->where('job_orders.status', 2)
    ->OrderBy('job_orders.status_display', 'Desc')
    ->get();

 

    $key = 0;
    $ctr = 0;
    

    foreach($jobOrderInfo as  $d) {
       $data[$d->plate_number][] = $d;
    }

    if(!isset($data)) {
      $data = array();
      $var = array();
    }


foreach($data as $key => $final) {
  $pacakgeData = DB::table('job_orders_packages')
  ->where('job_order_id', $final[0]->job_order_id)
  ->where('package_value', '>', '')
  ->where('status', 1)
  ->get();


  $pacakgeManualData = DB::table('job_orders_package_manual_items')
  ->where('job_order_id', $final[0]->job_order_id)
  ->where('status', 1)
  ->get();


  $laborData = DB::table('job_orders_labors')
  ->where('job_order_id', $final[0]->job_order_id)
  ->where('labor_value', '>', '')
  ->where('status', 1)
  ->get();

  $partData = DB::table('job_orders_part_services')
  ->where('job_order_id', $final[0]->job_order_id)
  ->where('part_value', '>', '')
  ->where('status', 1)
  ->get();

 

  $var[$key][] = array(
  'data' => $final[0],
  'package_data' => $pacakgeData,
  'labor_data' => $laborData,
  'part_data' => $partData,
  'package_manual_data' => $pacakgeManualData,
  );
}


  


$cosHtml = [];
    $check_primary_item = 0;
$ctr = 0;
    $overall_total_sell_price = 0;
    $total_inv_amount_package = 0;

  // print_r($var);
foreach($var as $ctr => $d) {
  foreach($d as $v) {

    $compute = count($v['part_data']) + count($v['package_manual_data']);
    $check_counter = $compute - 1;
    $overall_unit_selling_price_with_labor = 0;
    $unit_selling_price_with_labor = 0;
    $total_cost = 0;
    $total_sell_price = 0;


     foreach($v['package_manual_data'] as $ctr => $pmdata) {
        $unit_cost = str_replace(",", "", intval($pmdata->unit_cost));
        $unit_selling_price_with_labor =  str_replace(",", "", $pmdata->price);
        $pmdata_qty = str_replace(' ', '', $pmdata->qty);
        $total_cost = intval($pmdata_qty) * $unit_cost;
        $total_sell_price = intval($pmdata_qty) * intval($unit_selling_price_with_labor);

        $overall_unit_selling_price_with_labor += intval($unit_selling_price_with_labor);
        $overall_total_sell_price  += $total_sell_price;
        $total_inv_amount_package =  $overall_total_sell_price;
       
        $number_without_commas = 0;



        // PACKAGE
     

       if(isset($pmdata)) {
        if($pmdata->details > '') {
          $cosHtml[] ="<tr class='".(($ctr == 0) ? 'tbl-gray-bg' : '')."'>
          <td>".(($ctr == 0) ? $v['data']->date : '' )."</td>
          <td>".(($ctr == 0) ? $v['data']->owner_name : '' )."</td>
          <td>".(($ctr == 0) ? $v['data']->address : '' ) ."</td>
          <td>". (($ctr == 0) ?  "<a href='/app/job-order/".$v['data']->job_order_id."'><b>".$v['data']->plate_number."</b></a> ".$v['data']->manufacturer." ". $v['data']->vehicle_model." ". $v['data']->year." ". $v['data']->transmission." ". $v['data']->fuel_type : '')."</td>
          <td>". (($ctr == 0) ? $v['data']->invoice_number : '')."</td>
          <td>". (($ctr == 0) ? $v['data']->job_order_number : '')."</td>
          <td>". $pmdata->qty."</td>
          <td>". $pmdata->details."</td>
          <td>". $pmdata->part_number."</td>
          <td>". (($pmdata->supplier) ? $pmdata->supplier : "")."</td>
          <td>". (($pmdata->supplier_inv) ? $pmdata->supplier_inv : "")."</td>
          <td>". $pmdata->unit_cost ."</td>
          <td>". $pmdata->total_cost."</td>
          <td>".$pmdata->srp_labor."</td>
          <td>".$pmdata->total_srp."</td>
          <td></td>";
          // <td>".(($check_counter == $ctr) ? number_format($total_inv_amount_package, 2) : '')."</td>
        $cosHtml[] ="<td>".(($check_counter == $ctr) ? "<a href='/app/job-order/".$v['data']->job_order_id."'><button class='btn btn-sm btn-icon'><i class='mdi mdi-pencil-outline'></i></button></a>" : '') ."</td>
          </tr>
          ";
        $ctr++;

        } else {
          $ctr = 0;
        }
  
        
         
        }




      }

      


      /////////////////////////////////

      foreach($v['part_data'] as $ctrp => $pdata) {
   $overall_unit_selling_price_with_labor = 0;
    $unit_selling_price_with_labor = 0;
    $total_cost = 0;
    $total_sell_price = 0;

        // if(isset($v['labor_data'][$ctr]->labor_price)) {
        //   $get_labor_price =  $v['labor_data'][$ctr]->labor_price;
        // } else {
        //   $get_labor_price = 0;
        // }

        $unit_cost = intval($pdata->unit_cost);
        $unit_selling_price_with_labor =  $pdata->part_price;
        $total_cost = $pdata->part_qty * $unit_cost;
        $total_sell_price = $pdata->part_qty * $unit_selling_price_with_labor;

        $overall_unit_selling_price_with_labor += $unit_selling_price_with_labor;
        $overall_total_sell_price  += $total_sell_price;
        $total_inv_amount_package =  $overall_total_sell_price;
       
        $number_without_commas = 0;

        $c = 0;

        if(isset($num)) {
          foreach($num as $add) {
            $total_inv_amount_package += $add;
          }
        }
 
          $cosHtml[] ="<tr class='".(($ctr == 0) ? 'tbl-gray-bg' : '')."'>
          <td>".(($ctr == 0) ? $v['data']->date : '' )."</td>
          <td>".(($ctr == 0) ? $v['data']->owner_name : '' )."</td>
          <td>".(($ctr == 0) ? $v['data']->address : '' ) ."</td>
          <td>". (($ctr == 0) ? "<a href='/app/job-order/".$v['data']->job_order_id."'><b>".$v['data']->plate_number."</b></a> ".  $v['data']->manufacturer." ". $v['data']->vehicle_model." ". $v['data']->year." ". $v['data']->transmission." ". $v['data']->fuel_type : '')."</td>
          <td>". (($ctr == 0) ? $v['data']->invoice_number : '')."</td>
          <td>". (($ctr == 0) ? $v['data']->job_order_number : '')."</td>
          <td>". $pdata->part_qty."</td>
          <td>". $pdata->part_value."</td>
          <td>". $pdata->part_number."</td>
          <td>". $pdata->supplier."</td>
          <td>". $pdata->supplier_inv."</td>
          <td>". number_format($unit_cost, 2) ."</td>
          <td>". number_format($total_cost, 2)."</td>
          <td>". number_format($unit_selling_price_with_labor, 2)."</td>
          <td>". number_format($total_sell_price, 2)."</td>
          <td></td>";

          // <td>".(($check_counter == $ctr) ? number_format($total_inv_amount_package, 2) : '')."</td>

          $cosHtml[] ="<td>".(($check_counter == $ctr) ? "<a href='/app/job-order/".$v['data']->job_order_id."'><button class='btn btn-sm btn-icon'><i class='mdi mdi-pencil-outline'></i></button></a>" : '') ."</td>
          </tr>
          ";
        $ctr++;

      }

  }


}

      return response()->json(['success'=> true, 'cosHtml' => $cosHtml]);


}





    
  public function exportCostOfSales($date) {

     $type = $_GET['type'];
   date_default_timezone_set("Asia/Manila");

  
  if($type == '1') {
  $jobOrderInfo = DB::table('cars')
     ->join('job_orders', 'job_orders.car_id', '=', 'cars.id')
     ->join('owners', 'owners.id', '=', 'cars.owner_id')
     ->select('job_orders.id as job_order_id', 'job_orders.job_order_number as job_order_number', 
    'cars.id as car_id', 
    'job_orders.date as date', 
    'job_orders.job_order_number as job_order_number', 
    'job_orders.invoice_number as invoice_number', 
    'cars.plate_number as plate_number', 
    'cars.manufacturer as manufacturer', 
    'cars.vehicle_type as vehicle_type', 
    'cars.vehicle_model as vehicle_model', 
    'cars.transmission as transmission', 
    'cars.fuel_type as fuel_type', 
    'job_orders.mileage as mileage', 
    // 'job_orders.status as status', 
    'job_orders.status as status_display', 
    'cars.owner_id as owner_id', 
    'cars.vehicle_type as vehicle_type', 
    'cars.year as year', 
    'owners.owner_name as owner_name', 
    'owners.address as address', 
    'owners.mobile_number as mobile_number', 
  )
   ->where('job_orders.date', $date)
    ->where('job_orders.status', 2)
    ->OrderBy('job_orders.status_display', 'Desc')
    ->get();
  } else {

  $jobOrderInfo = DB::table('cars')
     ->join('job_orders', 'job_orders.car_id', '=', 'cars.id')
     ->join('owners', 'owners.id', '=', 'cars.owner_id')
     ->select('job_orders.id as job_order_id', 'job_orders.job_order_number as job_order_number', 
    'cars.id as car_id', 
    'job_orders.date as date', 
    'job_orders.job_order_number as job_order_number', 
    'job_orders.invoice_number as invoice_number', 
    'cars.plate_number as plate_number', 
    'cars.manufacturer as manufacturer', 
    'cars.vehicle_type as vehicle_type', 
    'cars.vehicle_model as vehicle_model', 
    'cars.transmission as transmission', 
    'cars.fuel_type as fuel_type', 
    'job_orders.mileage as mileage', 
    // 'job_orders.status as status', 
    'job_orders.status as status_display', 
    'cars.owner_id as owner_id', 
    'cars.vehicle_type as vehicle_type', 
    'cars.year as year', 
    'owners.owner_name as owner_name', 
    'owners.address as address', 
    'owners.mobile_number as mobile_number', 
  )
    ->whereMonth('job_orders.created_at', now()->month)
    ->where('job_orders.status', 2)
    ->OrderBy('job_orders.status_display', 'Desc')
    ->get();
  }
  


    $key = 0;
    $ctr = 0;
    

    foreach($jobOrderInfo as  $d) {
       $data[$d->plate_number][] = $d;
    }

    if(!isset($data)) {
      $data = array();
      $var = array();
    }


foreach($data as $key => $final) {
  $pacakgeData = DB::table('job_orders_packages')
  ->where('job_order_id', $final[0]->job_order_id)
  ->where('package_value', '>', '')
  ->where('status', 1)
  ->get();

  $laborData = DB::table('job_orders_labors')
  ->where('job_order_id', $final[0]->job_order_id)
  ->where('labor_value', '>', '')
  ->where('status', 1)
  ->get();

    $partData = DB::table('job_orders_part_services')
  ->where('job_order_id', $final[0]->job_order_id)
  ->where('part_value', '>', '')
  ->where('status', 1)
  ->get();

  $var[$key][] = array(
  'data' => $final[0],
  'package_data' => $pacakgeData,
  'labor_data' => $laborData,
  'part_data' => $partData,
  );
}



  $fileName = 'cost_of_sales';
  $file = 'cost_of_sales.xlsx';
  
  $items[] = array('DATE', 'CUSTOMER NAME', 'ADDRESS', 'CAR DETAILS', 'INV#', 'JO#', 'QTY', 'DETAILS', 'PART NO.',
  'SUPPLIER', 'SUPPLIER INV', 'UNIT COST', 'TOTAL COST', 'UNIT SELLING PRICE WITH LABOR',
  'TOTAL SELL PRICE', 'TOTAL INV AMOUNT PACKAGE', '');
    
foreach($var as $ctr => $d) {
  foreach($d as $v) {

    $check_counter = count($v['part_data']) - 1;
    $overall_unit_selling_price_with_labor = 0;
    $overall_total_sell_price = 0;
    $unit_selling_price_with_labor = 0;
    $total_cost = 0;
    $total_sell_price = 0;
    $total_inv_amount_package = 0;

    
      foreach($v['part_data'] as $ctr => $pdata) {

        if(isset($v['labor_data'][$ctr]->labor_price)) {
          $get_labor_price =  $v['labor_data'][$ctr]->labor_price;
        } else {
          $get_labor_price = 0;
        }
        $unit_selling_price_with_labor = $get_labor_price  + $pdata->part_price;
       
        $quantity = (int) $pdata->part_qty;
        $unit_cost = (int) $pdata->unit_cost;
       
        $total_cost = $quantity * $unit_cost;
        $unit_selling_price_with_labor = (float) $unit_selling_price_with_labor;

        $total_sell_price = $quantity * $unit_selling_price_with_labor;

        $overall_unit_selling_price_with_labor += $unit_selling_price_with_labor;
        $overall_total_sell_price  += $total_sell_price;
        $total_inv_amount_package = $overall_unit_selling_price_with_labor + $overall_total_sell_price;
       

          
     
        $items[] = array(
        (($ctr == 0) ? $v['data']->date : ''), (($ctr == 0) ? $v['data']->owner_name : ''), (($ctr == 0) ? $v['data']->address : ''), (($ctr == 0) ? $v['data']->manufacturer." ". $v['data']->vehicle_model." ". $v['data']->year." ". $v['data']->transmission." ". $v['data']->fuel_type : ''),
        $v['data']->invoice_number, $v['data']->job_order_number, $pdata->part_qty, $pdata->part_value, $pdata->part_number, $pdata->supplier, $pdata->supplier_inv, number_format($unit_cost, 2),
        number_format($total_cost, 2), number_format($unit_selling_price_with_labor, 2), number_format($total_sell_price, 2), number_format($total_inv_amount_package, 2), ''
        );

 
       $export = new SalesReportExport([
        $items
      ]);

      Excel::store($export, $file, 'public');



        }

      }
    } 
    return response()->json(['fileName'=> $fileName]);

  }

  //  "id": 99,
  //     "product_name": "Komainer",
  //     "category": 0,
  //     "stock": 1,
  //     "sku": 59592,
  //     "price": "$656.85",
  //     "qty": 679,
  //     "status": 3,
  //     "image": "product-10.png",
  //     "product_brand": "Feest Group"
  //   },

  
}
