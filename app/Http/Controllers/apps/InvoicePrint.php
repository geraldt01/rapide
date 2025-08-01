<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;

class InvoicePrint extends Controller
{
  public function index($job_order_id)
  {
    $pageConfigs = ['myLayout' => 'blank'];


     $jobOrderInfo = DB::table('job_orders')
    ->join('cars', 'cars.id', '=', 'job_orders.car_id')
    ->join('owners', 'owners.id', '=', 'cars.owner_id')
    ->where('job_orders.id', '=', $job_order_id)
   ->select('cars.*', 'owners.*', 'job_orders.*', 'job_orders.status as job_order_status', 'job_orders.id as job_order_id')
    
    ->get();

    $jobOrderPackageSelected = DB::table('job_orders_packages')
    ->where('status', '=', 1)
    ->where('job_order_id', '=', $job_order_id)
    ->get();

    $jobOrderLaborSelected = DB::table('job_orders_labors')
    ->where('status', '=', 1)
    ->where('job_order_id', '=', $job_order_id)
    ->get();

    $jobOrderPartSelected = DB::table('job_orders_part_services')
    ->where('status', '=', 1)
    ->where('job_order_id', '=', $job_order_id)
    ->get();

    


    
    return view('content.apps.app-invoice-print', ['pageConfigs' => $pageConfigs, 'job_order_id' => $job_order_id, 'jobOrderInfo' => $jobOrderInfo, 'jobOrderPackageSelected' => $jobOrderPackageSelected, 'jobOrderLaborSelected' => $jobOrderLaborSelected, 'jobOrderPartSelected' => $jobOrderPartSelected]);

  }
}
