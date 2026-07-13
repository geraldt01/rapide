<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;

class InvoicePreview extends Controller
{
  public function index($job_order_id)
  {
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
    ->orderByRaw('part_id IS NULL')
    ->where('status', '=', 1)
    ->where('job_order_id', '=', $job_order_id)
    ->get();

    
    $originalDate =  $jobOrderInfo[0]->date;
    $unixTimestamp = strtotime($originalDate);
    $newInvoiceDate = date("m/d/Y", $unixTimestamp);


    
    return view('content.apps.app-invoice-preview', ['invoice_date' => $newInvoiceDate, 'expire_date' => $jobOrderInfo[0]->expire_date, 'job_order_id' => $job_order_id, 'jobOrderInfo' => $jobOrderInfo, 'jobOrderPackageSelected' => $jobOrderPackageSelected, 'jobOrderLaborSelected' => $jobOrderLaborSelected, 'jobOrderPartSelected' => $jobOrderPartSelected]);


  }
}
