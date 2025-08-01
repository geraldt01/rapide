<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobOrder;
use App\Models\JobOrdersPackage;
use App\Models\JobOrdersPackageOption;
use App\Models\JobOrdersLaborOption;
use App\Models\JobOrdersLabor;
use App\Models\JobOrdersPartServiceOption;
use App\Models\JobOrdersPartService;
use App\Models\SpecialNote;
use App\Models\JobOrdersStatus;


use App\Models\Package;

use DB;

class InvoiceEdit extends Controller
{
  public function index($job_order_id)
  {
    $jobOrderInfo = DB::table('job_orders')
    ->join('cars', 'cars.id', '=', 'job_orders.car_id')
    ->join('owners', 'owners.id', '=', 'cars.owner_id')
    ->where('job_orders.id', '=', $job_order_id)
   ->select('cars.*', 'owners.*', 'job_orders.*', 'job_orders.status as job_order_status')
    ->get(); 


    if($jobOrderInfo[0]->job_order_status  == 1) {
      $optionStatus = "bg-label-warning";
    } else if ($jobOrderInfo[0]->job_order_status  == 2) {
      $optionStatus = "bg-label-info";
    }else {
      $optionStatus = "bg-label-success";
    }
    $jobOrderPackageOption = DB::table('job_orders_package_options')
    ->where('status', '=', 1)
    ->get();
    $jobOrderPackageSelected = DB::table('job_orders_packages')
    ->where('status', '=', 1)
    ->where('job_order_id', '=', $job_order_id)
    ->get();

    $optionOneHtml = array();
    $keypckg = 1;
    if(isset($jobOrderPackageSelected[0]->job_order_id)) {
      foreach($jobOrderPackageSelected as $keypckg => $selected) {
      
        $optionOneHtml[] = '<div class="border row w-100  p-2"  data-repeater-item><div class="col-md-9 col-12 mb-md-0 mb-3"><select class="form-select item-details mb-3" name="package"  id="package-'.$keypckg.'"  onchange="calculatePackage('.$keypckg.')">';
        foreach($jobOrderPackageOption as $options) {
            $optionOneHtml[] = '<option value="'.$options->id.'" '.(($options->id == $selected->package_id) ? "selected" : "").'>'.$options->value.'</option>';
        }
        $optionOneHtml[] =  '</select></div>
            <div class="col-md-3 col-12 mb-md-0 mb-3 color-black d-flex">
                      <span class="pt-2 pl-2">₱</span>
                    <input type="text" class="form-control invoice-item-price package mb-3" name="package-price" id="package-price-'.$keypckg.'" value="'.$selected->package_price.'" placeholder="0" min="12" onchange="calculatePrice('.$keypckg.')" />
         </div>
        </div>';
        $keypckg++;
      }
    } else {
      $optionOneHtml = false;
    }
   
    $jobOrderLaborOption = DB::table('job_orders_labor_options')
    ->where('status', '=', 1)
    ->get();
    $jobOrderLaborSelected = DB::table('job_orders_labors')
    ->where('status', '=', 1)
    ->where('job_order_id', '=', $job_order_id)
    ->get();

    // $optionTwoHtml = array();
    // if(isset($jobOrderLaborSelected[0]->job_order_id)) {
    //   foreach($jobOrderLaborSelected as $selectedLabor) {
    //     $optionTwoHtml[] = '<div class="border row w-100  p-2"  data-repeater-item><div class="col-md-7 col-12 mb-md-0 mb-3"><select class="form-select item-details mb-3" name="package">';
    //     foreach($jobOrderLaborOption as $options) {
    //         $optionTwoHtml[] = '<option value="'.$options->id.'" '.(($options->id == $selectedLabor->labor_id) ? "selected" : "").'>'.$options->value.'</option>';
    //     }
    //     $optionTwoHtml[] =  '</select></div></div>';
    //   }
    // } else {
    //   $optionTwoHtml = false;
    // }


    $keyl = 1;
    $optionTwoHtml = array();
    if(isset($jobOrderLaborSelected[0]->job_order_id)) {
      foreach($jobOrderLaborSelected as $keyl => $selectedLabor) {
                $keyl++;
                $sub_amount = $selectedLabor->labor_price * $selectedLabor->labor_qty;
                $optionTwoHtml[] = '<div class="border row w-100 p-3 pr-0" style="padding-right: 0px !important;" id="item-list-labor-'.$keyl.'"  data-repeater-item>
                 <div class="col-md-1 col-12 mb-md-0 mb-3 color-black">
                    <h6 class="mb-2 repeater-title fw-medium"><strong>No</strong></h6>
                    <span id="labor-counter-'.$keyl.'">'.$keyl.'</span>
                  </div>
                  <div class="col-md-5 col-12 mb-md-0 mb-3">
                    <h6 class="mb-2 repeater-title fw-medium"><strong>Service</strong></h6>
                    <input type="text" class="form-control invoice-item-text " name="labor-text" id="labor-text-'.$keyl.'"  value="'.$selectedLabor->labor_value.'" onchange="calculateLabor('.$keyl.')" />
                  </div>
                     <div class="col-md-2 col-12 mb-md-0 mb-3">
                    <h6 class="mb-2 repeater-title fw-medium">Qty</h6>
                    <input type="text" class="form-control invoice-item-qty labor" name="labor-qty" id="labor-qty-'.$keyl.'" value="'.$selectedLabor->labor_qty.'" placeholder="1" min="1" max=""  onchange="calculateLabor('.$keyl.')"/>
                  </div>
                  <div class="col-md-2 col-12 mb-md-0 mb-3">
                    <h6 class="mb-2 repeater-title fw-medium">Price</h6>
                    <input type="text" class="form-control invoice-item-price labor mb-3" name="labor-price" id="labor-price-'.$keyl.'" value="'.$selectedLabor->labor_price.'" placeholder="" min=""  onchange="calculateLabor('.$keyl.')"/>
                    
                  </div>
               
                  <div class="col-md-1 col-12 pe-0">
                    <h6 class="mb-2 repeater-title fw-medium">Amount</h6>
                    <p class="mb-0 pt-2 color-black amount-labor-sub d-flex" id="amount-labor-sub-'.$keyl.'">₱
                    <input type="text" class="form-control invoice-item-amount mb-3 p-0 border-0 pe-none" name="labor-amount" id="labor-amount-'.$keyl.'" value="'.$sub_amount.'"  placeholder="" min="12"/>
                    </p>
                  </div>

                <div class="col-md-1 col-12 border-start text-right">
                  <i class="mdi mdi-close cursor-pointer color-black" onclick="deleteItem('.$keyl.', 1)" data-repeater-delete></i>
                  <div class="dropdown">
                    <i class="mdi mdi-cog-outline cursor-pointer more-options-dropdown color-black" role="button" id="dropdownMenuButton" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    </i>
                    <div class="dropdown-menu dropdown-menu-end w-px-300 p-3" aria-labelledby="dropdownMenuButton">

                      <div class="row g-3">
                        <div class="col-12">
                          <label for="discountInput" class="form-label">Discount(%)</label>
                          <input type="number" class="form-control" id="discountInput" min="0" max="100" />
                        </div>
                        <div class="col-md-6">
                          <label for="taxInput1" class="form-label">Tax 1</label>
                          <select name="tax-1-input" id="taxInput1" class="form-select tax-select">
                            <option value="0%" selected>0%</option>
                            <option value="1%">1%</option>
                            <option value="10%">10%</option>
                            <option value="18%">18%</option>
                            <option value="40%">40%</option>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <label for="taxInput2" class="form-label">Tax 2</label>
                          <select name="tax-2-input" id="taxInput2" class="form-select tax-select">
                            <option value="0%" selected>0%</option>
                            <option value="1%">1%</option>
                            <option value="10%">10%</option>
                            <option value="18%">18%</option>
                            <option value="40%">40%</option>
                          </select>
                        </div>
                      </div>
                      <div class="dropdown-divider my-3"></div>
                      <button type="button" class="btn btn-outline-primary btn-apply-changes">Apply</button>
                    </div>
                  </div>
                </div>
                </div>
                ';
              }
          } else {
            $optionTwoHtml = false;
          }

    $jobOrderPartServiceOption = DB::table('job_orders_part_service_options')
    ->where('status', '=', 1)
    ->get();

    $jobOrderPartSelected = DB::table('job_orders_part_services')
    ->where('status', '=', 1)
    ->where('job_order_id', '=', $job_order_id)
    ->get();


    $keyprt = 1;
    $optionThreeHtml = array();
    if(isset($jobOrderPartSelected[0]->job_order_id)) {
      foreach($jobOrderPartSelected as $keyprt => $selectedPart) {
                $keyprt++;
                $sub_amount = $selectedPart->part_price * $selectedPart->part_qty;

                $optionThreeHtml[] = '<div class="border row w-100 p-3 pr-0" style="padding-right: 0px !important;"  id="item-list-part-'.$keyprt.'"  data-repeater-item>
                 <div class="col-md-1 col-12 mb-md-0 mb-3 color-black">
                    <h6 class="mb-2 repeater-title fw-medium"><strong>No</strong></h6>
                     <span id="part-counter-'.$keyprt.'">'.$keyprt.'</span>
                  </div>
                  <div class="col-md-5 col-12 mb-md-0 mb-3">
                    <h6 class="mb-2 repeater-title fw-medium"><strong>Service</strong></h6>
                      <div class="input-group">
                        <i class="mdi mdi-ballot mdi-36px dropdown-con"></i>
                        <select class="btn btn-outline-primary dropdown-toggle dropdown-menu form-select item-details mb-3" name="part" id="part-option-'.$keyprt.'" onchange="calculatePart('.$keyprt.'); populateOption(this)">';
                             foreach($jobOrderPartServiceOption as $optionsPart) {
                      $optionThreeHtml[] = '<option value="'.$optionsPart->id.'" '.(($optionsPart->id == $selectedPart->part_id) ? "selected" : "").'>'.$optionsPart->value.'</option>';
                  }
                     $optionThreeHtml[] = '< </select>
                        <input type="text" class="form-control" aria-label="Text input with dropdown button" name="part-text"  id="part-text-'.$keyprt.'" value="'.$selectedPart->part_value.'" onchange="calculatePart('.$keyprt.')">
                      </div>
                     </div>
                     <div class="col-md-2 col-12 mb-md-0 mb-3">
                    <h6 class="mb-2 repeater-title fw-medium"></h6>
                    <input type="text" class="form-control invoice-item-part-number part" name="part-part-number" id="part-part-number-'.$keyprt.'" value="'.$selectedPart->part_number.'" placeholder="" min="1" max="" onchange="calculatePart('.$keyprt.')"/>
                  </div>
                   <div class="col-md-1 col-12 mb-md-0 mb-3">
                    <h6 class="mb-2 repeater-title fw-medium">Qty</h6>
                    <input type="text" class="form-control invoice-item-qty part" name="part-qty" id="part-qty-'.$keyprt.'" value="'.$selectedPart->part_qty.'" placeholder="1" min="1" max="" onchange="calculatePart('.$keyprt.')"/>
                  </div>
                  <div class="col-md-1 col-12 mb-md-0 mb-3">
                    <h6 class="mb-2 repeater-title fw-medium">Price</h6>
                    <input type="text" class="form-control invoice-item-price part mb-3" name="part-price" id="part-price-'.$keyprt.'" value="'.$selectedPart->part_price.'" placeholder="" min="" />
                    
                  </div>
               
                 <div class="col-md-1 col-12 pe-0">
                    <h6 class="mb-2 repeater-title fw-medium">Amount</h6>
                    <p class="mb-0 pt-2 color-black amount-part-sub d-flex" id="amount-part-sub-'.$keyprt.'">₱
                    <input type="text" class="form-control invoice-item-amount mb-3 p-0 border-0 pe-none" name="part-amount" id="part-amount-'.$keyprt.'" value="'.$sub_amount.'" placeholder="" min="12"/>
                    </p>
                  </div>

                  <div class="col-md-1 col-12 border-start text-right">
                  <i class="mdi mdi-close cursor-pointer color-black" onclick="deleteItem('.$keyprt.', 2)" data-repeater-delete></i>
                  <div class="dropdown">
                    <i class="mdi mdi-cog-outline cursor-pointer more-options-dropdown color-black" role="button" id="dropdownMenuButton" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    </i>
                    <div class="dropdown-menu dropdown-menu-end w-px-300 p-3" aria-labelledby="dropdownMenuButton">

                      <div class="row g-3">
                        <div class="col-12">
                          <label for="discountInput" class="form-label">Discount(%)</label>
                          <input type="number" class="form-control" id="discountInput" min="0" max="100" />
                        </div>
                        <div class="col-md-6">
                          <label for="taxInput1" class="form-label">Tax 1</label>
                          <select name="tax-1-input" id="taxInput1" class="form-select tax-select">
                            <option value="0%" selected>0%</option>
                            <option value="1%">1%</option>
                            <option value="10%">10%</option>
                            <option value="18%">18%</option>
                            <option value="40%">40%</option>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <label for="taxInput2" class="form-label">Tax 2</label>
                          <select name="tax-2-input" id="taxInput2" class="form-select tax-select">
                            <option value="0%" selected>0%</option>
                            <option value="1%">1%</option>
                            <option value="10%">10%</option>
                            <option value="18%">18%</option>
                            <option value="40%">40%</option>
                          </select>
                        </div>
                      </div>
                      <div class="dropdown-divider my-3"></div>
                      <button type="button" class="btn btn-outline-primary btn-apply-changes">Apply</button>
                    </div>
                  </div>
                </div>
                </div>
                ';
              }
          } else {
            $optionThreeHtml = false;
          }


    $specialNotes = DB::table('special_notes')
    ->where('status', '=', 1)
    ->get();

    return view('content.apps.app-invoice-edit', ['specialNotes' => $specialNotes, 'optionStatus' => $optionStatus, 'packageTotalItem' => $keypckg, 'partTotalItem' => $keyprt, 'laborTotalItem' => $keyl, 'optionThreeHtml' => $optionThreeHtml, 'optionTwoHtml' => $optionTwoHtml, 'optionOneHtml' => $optionOneHtml, 'job_order_id' => $job_order_id, 'jobOrderInfo' => $jobOrderInfo, 'jobOrderPartServiceOption' => $jobOrderPartServiceOption, 'jobOrderLaborOption' => $jobOrderLaborOption, 'jobOrderPackageOption' => $jobOrderPackageOption]);
  }


  
  public function getPartValue($part_id){
    $jobOrderOption = DB::table('job_orders_part_service_options')
    ->where('id', '=', $part_id)
    ->get();

     return response()->json(['success'=> true, 'value' => $jobOrderOption[0]->value]);
  }


  public function saveJobOrderItem($job_order_id){

    $jobOrderInfo = DB::table('job_orders')
    ->join('cars', 'cars.id', '=', 'job_orders.car_id')
    ->join('owners', 'owners.id', '=', 'cars.owner_id')
    ->where('job_orders.id', '=', $job_order_id)
    ->get();

   
    if($_GET['status'] == 1) {
      $status_display = "estimate";
    } else if ($_GET['status'] == 2) {
      $status_display = "job order";
    } else if ($_GET['status'] == 3) {
      $status_display = "receipt";
    } else {
      $status_display = $_GET['status-text'];

    }

    JobOrder::where("id", $job_order_id)->update(
      [
        "status" => $_GET['status'],
        "status_display" => $status_display,
        "package_total" => (($_GET['hidden-package-sub-totals']) ? $_GET['hidden-package-sub-totals'] : 0),
        "labor_total" => (($_GET['labor-total']) ? $_GET['labor-total'] : 0),
        "part_total" => (($_GET['part-total']) ? $_GET['part-total'] : 0),
        "sub_total" => (($_GET['sub_total']) ? intval(preg_replace('/[^\d.]/', '', $_GET['sub_total'])) : 0),
        "vat" => (($_GET['vat']) ? intval(preg_replace('/[^\d.]/', '', $_GET['vat'])) : 0),
        "total_amount" => (($_GET['total-amount']) ? intval(preg_replace('/[^\d.]/', '', $_GET['total-amount'])) : 0),
        "payment" => (($_GET['payment']) ? intval(preg_replace('/[^\d.]/', '', $_GET['payment'])) : 0),
        "balance" => (($_GET['balance']) ? intval(preg_replace('/[^\d.]/', '', $_GET['balance'])) : 0),
        "remarks" => (($_GET['remarks']) ?  $_GET['remarks'] : ''),
      ]
    );
  

    $delPackage=JobOrdersPackage::where('job_order_id',$job_order_id)->delete();
    $delLabor=JobOrdersLabor::where('job_order_id',$job_order_id)->delete();
    $delPart=JobOrdersPartService::where('job_order_id',$job_order_id)->delete();

    foreach($_GET as $key => $value) {
      if($key== 'group-a') {
        foreach($value as $package){
         if(isset($package['package']) && $package['package'] > "") {
            $pck = new JobOrdersPackage();
            $pck->job_order_id = $job_order_id;
            $pck->package_id    = $package['package'];
            $pck->package_value    =  JobOrdersPackageOption::find($package['package'])->value;
            $pck->package_price    = JobOrdersPackageOption::find($package['package'])->package_price;
            $pck->save();
          } else {
            $pck = new JobOrdersPackage();
            $pck->save();
          }
        }
      }
      if($key== 'group-b') {
        foreach($value as $labor){
         if(isset($labor['labor-text']) && $labor['labor-text'] > "") {
            $lbr = new JobOrdersLabor();
            $lbr->job_order_id = $job_order_id;
            $lbr->labor_qty     = ((isset($labor['labor-qty'])) ? $labor['labor-qty'] : 1);
             $lbr->labor_value  =  ((isset($labor['labor-text'])) ? $labor['labor-text'] : "");
            $lbr->labor_price   = ((isset($labor['labor-price'])) ? $labor['labor-price'] : 0);
            $lbr->labor_amount   = ((isset($labor['labor-amount'])) ? $labor['labor-amount'] : 0);
            $lbr->save();
          } else {
            $lbr = new JobOrdersLabor();
            $lbr->job_order_id = $job_order_id;
            $lbr->save();
          }
            
        }
      }
      if($key== 'group-c') {
        foreach($value as $part){
           if(isset($part['part']) && $part['part'] > "") {
            $prt = new JobOrdersPartService();
            $prt->job_order_id = $job_order_id;
            $prt->part_id      = ((isset($part['part']))? $part['part'] : "");
            $prt->part_qty     = ((isset($part['part-qty'])) ? $part['part-qty'] : 1);
            $prt->part_value   =  ((isset($part['part-text'])) ? $part['part-text'] : "");
            $prt->part_number   =  ((isset($part['part-part-number'])) ? $part['part-part-number'] : "");
            $prt->part_price   = ((isset($part['part-price'])) ? $part['part-price'] : 0);
            $prt->part_amount   = ((isset($part['part-amount'])) ? $part['part-amount'] : 0);
            $prt->save();
          } else {
            $prt = new JobOrdersPartService();
            $prt->job_order_id = $job_order_id;
            $prt->save();
          }
        }
      }
    }
     return response()->json(['success'=> true, 'message' => 'Job Order Updated!']);
  }

  public function getJobOrderItemprice($job_order_id) {
    
    $jobOrderPartInfo = DB::table('job_orders_part_service_options')
    ->where('id', '=', $job_order_id)
    ->get();

     return response()->json(['success'=> true, 'price' => $jobOrderPartInfo[0]->price]);

  }


  public function getJobOrderItemPackagePrice($package_id) {
    
    $jobOrderPackageInfo = DB::table('job_orders_package_options')
    ->where('id', '=', $package_id)
    ->get();

     return response()->json(['success'=> true, 'price' => $jobOrderPackageInfo[0]->package_price]);

  }

  public function changeStatus($status_id) {
    $statusInfo = DB::table('job_orders_status')
    ->where('status_id', '=', $status_id)
    ->get();

    return response()->json(['success'=> true, 'value' => $statusInfo[0]]);
  }

  public function upgradeNewStatus($job_order_id) {

      // $jo = new JobOrder();
      // $jo->save();

  $new_status = $_GET['hidden-job-order-new-status'];
  $jobOrderInfo = DB::table('job_orders')
    ->where('id', '=', $job_order_id)
    ->get();

      $newStatus = DB::table('job_orders_status')
    ->where('id', '=', $new_status)
    ->get();

    if($newStatus[0]->status_id == '1') {
      $check =  DB::table('job_orders')
      ->where('id', '=', $jobOrderInfo[0]->ex_job_order_id)
      ->where('ex_job_order_id', '=', NULL)
      ->where('status', '=',  1)
      ->get();


      if(!isset($check[0])) {
          $check =  DB::table('job_orders')
        ->where('id', '=', $jobOrderInfo[0]->ex_job_order_id)
        ->where('status', '=',  2)
        ->get();
      }


    } else if($newStatus[0]->status_id == '2') {
      $check =  DB::table('job_orders')
      ->where('ex_job_order_id', '=', $jobOrderInfo[0]->ex_job_order_id)
      ->where('status', '=',  2)
      ->get();

      if(!isset($check[0])) {
          $check =  DB::table('job_orders')
        ->where('ex_job_order_id', '=', $job_order_id)
        ->where('status', '=',  2)
        ->get();
      }
        if(!isset($check[0])) {
          $check =  DB::table('job_orders')
        ->where('id', '=', $jobOrderInfo[0]->ex_job_order_id)
        ->where('status', '=',  2)
        ->get();
      }

    } else if($newStatus[0]->status_id == '3') {
      $check =  DB::table('job_orders')
      ->where('ex_job_order_id', '=', $jobOrderInfo[0]->id)
      ->where('status', '=',  3)
      ->get();

    }else {
      $check =  DB::table('job_orders')
      ->where('ex_job_order_id', '=', $job_order_id)
      ->where('status', '=',  $newStatus[0]->status_id)
      ->get();
    }
   
  
    if(!isset($check[0])) {

    foreach($jobOrderInfo as $data) {
      $jo = new JobOrder();
      $jo->ex_job_order_id = $data->id;
      $jo->job_order_number = $data->job_order_number;
      $jo->car_id = $data->car_id;
      $jo->date = $data->date;
      $jo->plate_number = $data->plate_number;
      $jo->manufacturer = $data->manufacturer;
      $jo->model = $data->model;
      $jo->status = $newStatus[0]->status_id;
      $jo->status_display = $newStatus[0]->status_value;
      $jo->package_total = $data->package_total;
      $jo->labor_total = $data->labor_total;
      $jo->part_total = $data->part_total;
      $jo->sub_total = $data->sub_total;
      $jo->vat = $data->vat;
      $jo->total_amount = $data->total_amount;
      $jo->payment = $data->payment;
      $jo->balance = $data->balance;
      $jo->remarks = $data->remarks;
      $jo->save();


    }
    $new_job_order_id = $jo->id;

    $jobOrderPackageInfo = DB::table('job_orders_packages')
    ->where('job_order_id', '=', $job_order_id)
    ->get();

    foreach($jobOrderPackageInfo as $package) {
      $pa = new JobOrdersPackage();
      $pa->job_order_id = $new_job_order_id;
      $pa->package_id = $package->package_id;
      $pa->package_value = $package->package_value;
      $pa->package_qty = $package->package_qty;
      $pa->package_price = $package->package_price;
      $pa->save();
    }

    $jobOrderLaborInfo = DB::table('job_orders_labors')
    ->where('job_order_id', '=', $job_order_id)
    ->get();

    foreach($jobOrderLaborInfo as $labor) {
      $la = new JobOrdersLabor();
      $la->job_order_id = $new_job_order_id;
      $la->labor_id = $labor->labor_id;
      $la->labor_value = $labor->labor_value;
      $la->labor_qty = $labor->labor_qty;
      $la->labor_price = $labor->labor_price;
      $la->save();
    }


    $jobOrderPartInfo = DB::table('job_orders_part_services')
    ->where('job_order_id', '=', $job_order_id)
    ->get();

     foreach($jobOrderPartInfo as $part) {
      $par = new JobOrdersPartService();
      $par->job_order_id = $new_job_order_id;
      $par->part_id = $part->part_id;
      $par->part_value = $part->part_value;
      $par->part_qty = $part->part_qty;
      $par->part_price = $part->part_price;
      $par->save();
      
    }
   } else {
      $new_job_order_id = $check[0]->id;
    }
     return response()->json(['success'=> true, 'newJobOrderId' => $new_job_order_id ]);

    }

  
  
  
}
