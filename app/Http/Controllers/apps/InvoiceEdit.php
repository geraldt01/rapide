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
use App\Models\ModeOfPayment;
use App\Models\RepairEstimateNumber;
use App\Models\JobOrderNumber;


use App\Models\Package;

use DB;
use DateTime;

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

    $originalDate =  $jobOrderInfo[0]->date;
    $unixTimestamp = strtotime($originalDate);
    $newInvoiceDate = date("m/d/Y", $unixTimestamp);


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
    // ->where('status', '=', 1)
    ->where('job_order_id', '=', $job_order_id)
    ->get();

    $optionOneHtml = array();
    $keypckg = 1;
    if(isset($jobOrderPackageSelected[0]->job_order_id)) {
      foreach($jobOrderPackageSelected as $keypckg => $selected) {
      
        $optionOneHtml[] = '<div class="border row w-100  p-2 '.(($selected->status == 2) ? 'disable-package-item' : '').'" id="item-list-package-'.$keypckg.'"   data-repeater-item><div class="col-md-6 col-12 mb-md-0 mb-3">
        <input type="hidden" name="package-id" value="'.$selected->id.'" />
        <select id="package-option-'.$keypckg.' select1Basic'.$keypckg.'" name="package-option" class="select2" data-allow-clear="true"  onchange=" calculatePackage('.$keypckg.')" style="width: 0px;">
          <option value="">Select Package</option>';
          foreach($jobOrderPackageOption as $options) {
       
                  $optionOneHtml[] = '<option value="'.$options->id.'" '.(($options->id == $selected->package_id) ? "selected" : "").'>'.$options->value.'</option>';
              }
        $optionOneHtml[] =  '</select></div>
            <div class="col-md-4 col-12">
             <input type="text" class="form-control package mb-3" name="package-note2" id="package-note2-'.$keypckg.'" value="'.$selected->package_note2.'" onchange="calculatePackage('.$keypckg.')" />
            </div>
              
            <div class="col-md-2 col-12 mb-md-0 mb-3 color-black d-flex">
                      <span class="pt-2 pl-2">₱</span>
                    <input type="text" class="form-control invoice-item-price package mb-3" name="package-price" id="package-price-'.$keypckg.'" value="'.$selected->package_price.'" placeholder="0" onchange="calculatePackage('.$keypckg.')" />
                  <i class="mdi mdi-close cursor-pointer color-black" onclick="deleteItem('.$keypckg.', 0, '.$selected->id.');calculatePackage('.$keypckg.')" ></i>
            </div>
        </div>';
        $keypckg++;
      }
    } else {
      $optionOneHtml = false;
    }
   
    $jobOrderLaborOption = DB::table('job_orders_labor_options')
    ->where('status', '=', 1)
    ->orderBy('value','asc')
    ->get();
    $jobOrderLaborSelected = DB::table('job_orders_labors')
    // ->where('status', '=', 1)
    ->where('job_order_id', '=', $job_order_id)
    ->get();

    

    $keyl = 1;
    $optionTwoHtml = array();
    if(isset($jobOrderLaborSelected[0]->job_order_id)) {
      foreach($jobOrderLaborSelected as $keyl => $selectedLabor) {
                $keyl++;
                $sub_amount = $selectedLabor->labor_price * $selectedLabor->labor_qty;
                $optionTwoHtml[] = '<div class="border row w-100 p-3 pr-0 '.(($selectedLabor->status == 2) ? 'disable-labor-item' : '').'" style="padding-right: 0px !important;" id="item-list-labor-'.$keyl.'"  data-repeater-item>
                 <div class="col-md-1 col-12 mb-md-0 mb-3 color-black"  style="width: 4.333333%;">
                    <h6 class="mb-2 repeater-title fw-medium"><strong>No</strong></h6>
                    <span id="labor-counter-'.$keyl.'">'.$keyl.'</span>
                  </div>
                  <div class="col-md-4 col-12 mb-md-0 mb-3">
                    <h6 class="mb-2 repeater-title fw-medium"><strong>Service</strong></h6>
                        <input type="hidden" name="labor-id" value="'.$selectedLabor->id.'" />
                    <input type="text" class="form-control invoice-item-text " name="labor-text" id="labor-text-'.$keyl.'"  value="'.$selectedLabor->labor_value.'" onchange="calculateLabor('.$keyl.')" />
                  </div>
                   <div class="col-md-3 col-12 mb-md-0 mb-3">
                    <h6 class="mb-2 repeater-title fw-medium">Cost</h6>
                    <input type="text" class="form-control invoice-item-cost labor customer-hidden" name="labor-cost" id="labor-cost-'.$keyl.'" value="'.$selectedLabor->cost.'" placeholder="" min="1" max="" onchange="calculateLabor('.$keyl.')"/>
                  </div>
               

                     <div class="col-md-1 col-12 mb-md-0 mb-3">
                    <h6 class="mb-2 repeater-title fw-medium">Qty</h6>
                    <input type="text" class="form-control invoice-item-qty labor" name="labor-qty" id="labor-qty-'.$keyl.'" value="'.$selectedLabor->labor_qty.'" placeholder="1" min="1" max=""  onchange="calculateLabor('.$keyl.')"/>
                  </div>
                  <div class="col-md-1 col-12 mb-md-0 mb-3">
                    <h6 class="mb-2 repeater-title fw-medium">Price</h6>
                    <input type="text" class="form-control invoice-item-price labor mb-3" name="labor-price" id="labor-price-'.$keyl.'" value="'.$selectedLabor->labor_price.'" placeholder="" min=""  onchange="calculateLabor('.$keyl.')" pattern="^(?=.)(\d{1,3}(,\d{3})*)?(\.\d+)?$"/>
                    
                  </div>
               
                  <div class="col-md-1 col-12 pe-0">
                    <h6 class="mb-2 repeater-title fw-medium">Amount</h6>
                    <p class="mb-0 pt-2 color-black amount-labor-sub d-flex" id="amount-labor-sub-'.$keyl.'">₱
                    <input type="text" class="form-control invoice-item-amount mb-3 p-0 border-0 pe-none" name="labor-amount" id="labor-amount-'.$keyl.'" value="'.$sub_amount.'"  placeholder="" min="12"/>
                    </p>
                  </div>

                <div class="col-md-1 col-12 border-start text-right" style="width: 2.333333%;">
                  <i class="mdi mdi-close cursor-pointer color-black" onclick="deleteItem('.$keyl.', 1, '.$selectedLabor->id.');calculateLabor('.$keyl.')" ></i>
                  <div class="dropdown">
                    
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
    ->orderBy('value','asc')
    ->get();

    $jobOrderPartSelected = DB::table('job_orders_part_services')
    // ->where('status', '=', 1)
    ->where('job_order_id', '=', $job_order_id)
    ->get();


    $keyprt = 1;
    $optionThreeHtml = array();
    // if(isset($jobOrderPartSelected[0]->job_order_id)) {
      foreach($jobOrderPartSelected as $keyprt => $selectedPart) {
                $keyprt++;
                $sub_amount = $selectedPart->part_price * $selectedPart->part_qty;

                $optionThreeHtml[] = '<div class="border row w-100 p-3 pr-0 '.(($selectedPart->status == 2) ? 'disable-part-item' : '').'" style="padding-right: 0px !important;"  id="item-list-part-'.$keyprt.'"  data-repeater-item>
                 <div class="col-md-1 col-12 mb-md-0 mb-3 color-black" style="width: 4.333333%;">
                    <h6 class="mb-2 repeater-title fw-medium"><strong>No</strong></h6>
                     <span id="part-counter-'.$keyprt.'">'.$keyprt.'</span>
                  </div>
                  <div class="col-md-3 col-12 mb-md-0 mb-3" id="refresh-div-'.$keyl.'">
                    <h6 class="mb-2 ml-2 repeater-title fw-medium"><strong>Service</strong></h6>
                      <div class="row">
                      <div class="col-1">
                      <i class="mdi mdi-content-copy me-1 js-textareacopybtn" id="part-icon" onclick="copyParts('.$keyprt.')"></i><span class="alert-coppied" id="icon-part-'.$keyprt.'">Coppied!</span>
                      </div>
                      <div class="col-11">
                        <input type="hidden" name="part-id" value="'.$selectedPart->id.'" />
                        <select id="part-option-'.$keyprt.' select2Basic'.$keyprt.'" name="part-option" class="select2" data-allow-clear="true"  onchange=" populateOption('.$keyprt.')">
                         <option value="" id="part-selected-'.$keyprt.'">Select Parts</option>';
                         foreach($jobOrderPartServiceOption as $optionsPart) {
                                $optionThreeHtml[] = '<option value="'.$optionsPart->id.'" '.(($optionsPart->id == $selectedPart->part_id) ? "selected" : "").'>'.$optionsPart->value.'</option>';
                              }
                                $optionThreeHtml[] = '
                            </select>
                        <input type="hidden" class="form-control" aria-label="Text input with dropdown button" name="part-text"  id="part-text-'.$keyprt.'" value="'.$selectedPart->part_value.'" onchange="calculatePart('.$keyprt.')">
                        <input type="hidden" name="part-counter"  id="part-'.$keyprt.'" value="'.$keyprt.'" >
                         </div>
                        </div>
                     </div>

                  <div class="col-md-1 col-12 mb-md-0 mb-3">
                    <h6 class="mb-2 repeater-title fw-medium">Part Number</h6>
                    <input type="text" class="form-control invoice-item-part-number part" name="part-part-number" id="part-part-number-'.$keyprt.'" value="'.$selectedPart->part_number.'" placeholder="" min="1" max="" onchange="calculatePart('.$keyprt.')"/>
                  </div>            
                  <div class="col-md-1 col-12 mb-md-0 mb-3">
                      <h6 class="mb-2 repeater-title fw-medium">Supplier</h6>
                    <input type="text" class="form-control invoice-item-supplier part customer-hidden" name="part-supplier" id="supplier-'.$keyprt.'" value="'.$selectedPart->supplier.'" placeholder="" min="1" max="" onchange="calculatePart('.$keyprt.')"/>
                  </div>
                  <div class="col-md-1 col-12 mb-md-0 mb-3">
                      <h6 class="mb-2 repeater-title fw-medium">Supplier Inv</h6>
                    <input type="text" class="form-control invoice-item-supplier-inv part customer-hidden" name="part-supplier-inv" id="supplier-inv-'.$keyprt.'" value="'.$selectedPart->supplier_inv.'" placeholder="" min="1" max="" onchange="calculatePart('.$keyprt.')"/>
                  </div>
                   <div class="col-md-1 col-12 mb-md-0 mb-3">
                    <h6 class="mb-2 repeater-title fw-medium">Unit Cost</h6>
                    <input type="text" class="form-control invoice-unit-cost part customer-hidden" name="part-unit-cost" id="part-unit-cost-'.$keyprt.'" value="'.$selectedPart->unit_cost.'" placeholder="1" min="1" max="" onchange="calculatePart('.$keyprt.')"/>
                  </div>
                      <div class="col-md-1 col-12 pe-0">
                      <h6 class="mb-2 repeater-title fw-medium">Total Cost</h6>
                    <input type="text" class="form-control invoice-unit-cost part customer-hidden" name="part-total-cost" id="part-total-cost-'.$keyprt.'" value="'.$selectedPart->total_cost.'" placeholder="1" min="1" max="" onchange="calculatePart('.$keyprt.')"/>
                 
                  </div>

                  <div class="col-md-1 col-12 mb-md-0 mb-3">
                    <h6 class="mb-2 repeater-title fw-medium">Qty</h6>
                    <input type="text" class="form-control invoice-item-qty part" name="part-qty" id="part-qty-'.$keyprt.'" value="'.$selectedPart->part_qty.'" placeholder="1" min="1" max="" onchange="calculatePart('.$keyprt.')"/>
                  </div>

                  <div class="col-md-1 col-12 mb-md-0 mb-3">
                    <h6 class="mb-2 repeater-title fw-medium">Price</h6>
                    <input type="text" class="form-control invoice-item-price part mb-3" name="part-price" id="part-price-'.$keyprt.'" value="'.$selectedPart->part_price.'" placeholder="" min="" onchange="calculatePart('.$keyprt.')" />
                  </div>
               
             
                  <div class="col-md-1 col-12 border-start text-right" style="">
                   <h6 class="mb-2 repeater-title fw-medium">Amount</h6>
                    <p class="mb-0 pt-2 color-black amount-part-sub d-flex" id="amount-part-sub-'.$keyprt.'">₱
                    <input type="text" class="form-control invoice-item-amount mb-3 p-0 border-0 pe-none" name="part-amount" id="part-amount-'.$keyprt.'" value="'.$sub_amount.'" placeholder="" min="12"/>
                    </p>

                  <i class="mdi mdi-close cursor-pointer color-black" onclick="deleteItem('.$keyprt.', 2, '.$selectedPart->id.');calculatePart('.$keyprt.')" ></i>
                  <div class="dropdown">
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
          // } else {
          //   // $optionThreeHtml = false;
          // }


    $specialNotes = DB::table('special_notes')
    ->where('status', '=', 1)
    ->get();

    
     $modeOfPaymentData = DB::table('mode_of_payments')
    ->where('status', '=', 1)
    ->get();


    foreach($modeOfPaymentData as $mop) {
      $modeOfPayment[] = $mop->value;
    }


    $countEnabledPackageData = DB::table('job_orders_packages')
    ->where('status', '=', 1)
    ->where('job_order_id', '=', $job_order_id)
    ->get();


    $countEnabledLaborData = DB::table('job_orders_labors')
    ->where('status', '=', 1)
    ->where('job_order_id', '=', $job_order_id)
    ->get();

      $countEnabledPartData = DB::table('job_orders_part_services')
    ->where('status', '=', 1)
    ->where('job_order_id', '=', $job_order_id)
    ->get();

    $countEnabledPackage = count($countEnabledPackageData);
    $countEnabledLabor = count($countEnabledLaborData);
    $countEnabledPart = count($countEnabledPartData);

    if(!empty($_POST)) {
    return response()->json(['success'=> true, 'optionOneHtml' => $optionOneHtml]);
    } else {
     return view('content.apps.app-invoice-edit', ['invoice_date' => $newInvoiceDate, 'expire_date' => $jobOrderInfo[0]->expire_date, 'modeOfPayment' => $modeOfPayment, 'specialNotes' => $specialNotes, 'optionStatus' => $optionStatus, 'packageTotalItem' => $keypckg, 'partTotalItem' => $keyprt, 'laborTotalItem' => $keyl, 'optionThreeHtml' => $optionThreeHtml, 'optionTwoHtml' => $optionTwoHtml, 'optionOneHtml' => $optionOneHtml, 'job_order_id' => $job_order_id, 'jobOrderInfo' => $jobOrderInfo, 'jobOrderPartServiceOption' => $jobOrderPartServiceOption, 'jobOrderLaborOption' => $jobOrderLaborOption, 'jobOrderPackageOption' => $jobOrderPackageOption, 'countEnabledPart' => $countEnabledPart, 'countEnabledPackage' => $countEnabledPackage, 'countEnabledLabor' => $countEnabledLabor ]);
    }
  }


  
  public function getPartValue($part_id){
    $jobOrderOption = DB::table('job_orders_part_service_options')
    ->where('id', '=', $part_id)
    ->get();

     return response()->json(['success'=> true, 'value' => $jobOrderOption[0]->value, 'part_number' => $jobOrderOption[0]->part_number, 'cost' => $jobOrderOption[0]->cost, 'price' => $jobOrderOption[0]->price]);
  }

  
  public function deleteJobOrderItem($item_id){
    JobOrdersPartService::where("id", $item_id)->update(
          [
            "part_qty"     => 1,
            "part_value"   =>  NULL,
            "part_number"   =>  NULL,
            "supplier"   =>  NULL,
            "supplier_inv"   =>  NULL,
            "unit_cost"   => 0,
            "total_cost"   =>  0,
            "part_price"  =>  0,
            "part_amount"   =>  0,
          ] );
     return response()->json(['success'=> true, 'message' => 'Item deleted!']);

  }

   public function deleteLaborItem($item_id){
    JobOrdersLabor::where("id", $item_id)->update(
          [
             "labor_qty"     => 1,
            "labor_value"  =>  NULL,
            "cost"  =>  NULL,
            "part_number"   =>  NULL,
            "labor_price"   => 0,
            "labor_amount"   => 0,
          ] );
     return response()->json(['success'=> true, 'message' => 'Item deleted!']);

  }


     public function deletePackageItem($item_id){
    JobOrdersPackage::where("id", $item_id)->update(
          [
            "package_qty"     => 1,
            "package_id"  =>  NULL,
            "package_value"  =>  NULL,
            "package_note"  =>  NULL,
            "package_note2"   =>  NULL,
            "package_price"   => 0,
          ] );
     return response()->json(['success'=> true, 'message' => 'Item deleted!']);

  }


  
  public function saveJobOrderItem($job_order_id){

    
    $jobOrderInfo = DB::table('job_orders')
    ->join('cars', 'cars.id', '=', 'job_orders.car_id')
    ->join('owners', 'owners.id', '=', 'cars.owner_id')
    ->where('job_orders.id', '=', $job_order_id)
    ->get();
   
    if($_POST['status'] == 1) {
      $status_display = "estimate";
    } else if ($_POST['status'] == 2) {
      $status_display = "job order";
    } else if ($_POST['status'] == 3) {
      $status_display = "receipt";
    } else {
      $status_display = $_POST['status-text'];

    }

    $invoice_date = $_POST['invoice_date']; // Example date in m/d/Y format
    // Create a DateTime object from the original format
    $dateTimeObject = DateTime::createFromFormat('m/d/Y', $invoice_date);
    // Format the DateTime object into the desired Y-m-d format
    $new_invoice_date = $dateTimeObject->format('Y-m-d');

    // $payment = intval(preg_replace('/[^\d.]/', '', $_POST['payment']));
    $payment =  $_POST['payment'];
    // $payment2 = intval(preg_replace('/[^\d.]/', '', $_POST['payment2']));
    $payment2 = $_POST['payment2'];
    $amount_net_vat = $_POST['amount-net-vat'];

    JobOrder::where("id", $job_order_id)->update(
      [
        "status" => $_POST['status'],
        "status_display" => $status_display,
        "date" => $new_invoice_date,
        "expire_date" => (($_POST['expire_date']) ? $_POST['expire_date'] : 0),
        "package_total" => (($_POST['hidden-package-sub-totals']) ? $_POST['hidden-package-sub-totals'] : 0),
        "labor_total" => (($_POST['labor-total']) ? $_POST['labor-total'] : 0),
        "part_total" => (($_POST['part-total']) ? $_POST['part-total'] : 0),
        "sub_total" => (($_POST['sub_total']) ? $_POST['sub_total'] : 0),
        "vat" => (($_POST['vat']) ?  $_POST['vat'] : 0),
        "total_amount" => (($_POST['total_amount']) ?  $_POST['total_amount'] : 0),
        "payment" => (($_POST['payment']) ? $payment : 0),
        "payment2" => (($_POST['payment2']) ? $payment2 : 0),
        "amount_net_vat" => (($_POST['amount-net-vat']) ? $amount_net_vat : 0),
        "balance" => (($_POST['balance']) ? $_POST['balance'] : 0),
        "discount" => (($_POST['discount']) ? $_POST['discount'] : 0),
        "remarks" => (($_POST['remarks']) ?  $_POST['remarks'] : ''),
        "mode_of_payment" => (($_POST['mop']) ?  $_POST['mop'] : ''),
        "mode_of_payment2" => (($_POST['mop2']) ?  $_POST['mop2'] : ''),
        "customer_name" => (($_POST['customer_name']) ?  $_POST['customer_name'] : ''),
        
      ]
    );
  

    // $delPackage=JobOrdersPackage::where('job_order_id',$job_order_id)->delete();
    // $delLabor=JobOrdersLabor::where('job_order_id',$job_order_id)->delete();
    // $delPart=JobOrdersPartService::where('job_order_id',$job_order_id)->delete();
     
  
   
    foreach($_POST as $key => $value) {
    
      if($key== 'group-a') {
        foreach($value as $package){


        if(isset($package['package-option']) && $package['package-option'] > "") {

         JobOrdersPackage::where("id", $package['package-id'])->update(
          [
            "job_order_id" => $job_order_id,
            "package_id"    => $package['package-option'],
              "package_value"    =>  JobOrdersPackageOption::find($package['package-option'])->value,
              "package_price"    => JobOrdersPackageOption::find($package['package-option'])->package_price,
              "package_note2"    => ((isset($package['package-note2'])) ? $package['package-note2'] : ""),
              "package_note"    => ((isset($package['package-note'])) ? $package['package-note'] : ""),
          ]
        );
        //         $pck = new JobOrdersPackage();
        //         $pck->job_order_id = $job_order_id; 
        //         $pck->package_id    = $package['package'];
        //         $pck->package_value    =  JobOrdersPackageOption::find($package['package'])->value;
        //         $pck->package_price    = JobOrdersPackageOption::find($package['package'])->package_price;
        //         $pck->save();
        }
          // else {
          //   $pck = new JobOrdersPackage();
          //   $pck->save();
          // }
        }
      }
      if($key== 'group-b') {
        foreach($value as $labor){
         if(isset($labor['labor-text']) && $labor['labor-text'] > "") {
            // $lbr = new JobOrdersLabor();
            // $lbr->job_order_id = $job_order_id;
            // $lbr->labor_qty     = ((isset($labor['labor-qty'])) ? $labor['labor-qty'] : 1);
            // $lbr->labor_value  =  ((isset($labor['labor-text'])) ? $labor['labor-text'] : "");
            // $lbr->part_number   =  ((isset($labor['labor-part-number'])) ? $labor['labor-part-number'] : "");
            // $lbr->labor_price   = ((isset($labor['labor-price'])) ? $labor['labor-price'] : 0);
            // $lbr->labor_amount   = ((isset($labor['labor-amount'])) ? $labor['labor-amount'] : 0);
            // $lbr->save();

             JobOrdersLabor::where("id", $labor['labor-id'])->update(
            [
            "job_order_id" => $job_order_id,
             "labor_qty"     => ((isset($labor['labor-qty'])) ? $labor['labor-qty'] : 1),
            "labor_value"  =>  ((isset($labor['labor-text'])) ? $labor['labor-text'] : ""),
            "cost"  =>  ((isset($labor['labor-cost'])) ? $labor['labor-cost'] : ""),
            "part_number"   =>  ((isset($labor['labor-part-number'])) ? $labor['labor-part-number'] : ""),
            "labor_price"   => ((isset($labor['labor-price'])) ? $labor['labor-price'] : 0),
            "labor_amount"   => ((isset($labor['labor-amount'])) ? $labor['labor-amount'] : 0),
            ]
          );

          } 
          // else {
          //   $lbr = new JobOrdersLabor();
          //   $lbr->job_order_id = $job_order_id;
          //   $lbr->save();
          // }
            
        }
      }
      if($key== 'group-c') {
        foreach($value as $part){
           if(isset($part['part-text']) && $part['part-text'] > "") {
            $counter = $part['part-counter'];
          JobOrdersPartService::where("id", $part['part-id'])->update(
          [
            "job_order_id" => $job_order_id,
            "part_id"      => ((isset($part['part-option']))? $part['part-option'] : ""),
            "part_qty"     => ((isset($part['part-qty'])) ? $part['part-qty'] : 1),
            "part_value"   =>  JobOrdersPartServiceOption::find($part['part-option'])->value,
            "part_number"   => ((isset($part['part-part-number'])) ? $part['part-part-number'] : ""),
            "supplier"   => ((isset($part['part-supplier'])) ? $part['part-supplier'] : ""),
            "supplier_inv"   => ((isset($part['part-supplier-inv'])) ? $part['part-supplier-inv'] : ""),
            "unit_cost"   => ((isset($part['part-unit-cost'])) ? $part['part-unit-cost'] : ""),
            "total_cost"   => ((isset($part['part-total-cost'])) ? $part['part-total-cost'] : ""),
            "part_price"  => ((isset($part['part-price'])) ? $part['part-price'] : 0),
            "part_amount"   => ((isset($part['part-amount'])) ? $part['part-amount'] : 0),
          ] );

          } else {
            // $prt = new JobOrdersPartService();
            // $prt->job_order_id = $job_order_id;
            // $prt->save();
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

     $job_order_new_status = $_GET['job_order_new_status'];

  $new_status = $job_order_new_status;
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

      $getLatestInvoice = DB::table('job_order_numbers')->where('status', '=', 1)
      ->orderBy('id','desc')
      ->get();

      // $final_invoice_number = $getLatestInvoice[0]->value + 1;

      // $i = new JobOrderNumber();
      // $i->value = $final_invoice_number;
      // $i->save();


      $jo = new JobOrder();
      $jo->ex_job_order_id = $data->id;
      // $jo->job_order_number = $final_invoice_number;
      $jo->job_order_number = $data->job_order_number;
      $jo->car_id = $data->car_id;
      $jo->date = $data->date;
      $jo->plate_number = $data->plate_number;
      $jo->manufacturer = $data->manufacturer; 
      $jo->mileage = $data->mileage; 
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
      $jo->payment2 = $data->payment2;
      $jo->payment_label = $data->payment_label;
      $jo->mode_of_payment = $data->mode_of_payment;
      $jo->mode_of_payment2 = $data->mode_of_payment2;
      $jo->balance = $data->balance;
      $jo->remarks = $data->remarks;

      $jo->save();


    }
    $new_job_order_id = $jo->id;

    $jobOrderPackageInfo = DB::table('job_orders_packages')
    ->where('job_order_id', '=', $job_order_id)
    ->get();

    foreach($jobOrderPackageInfo as $package) {
      if($package->status == 1) {
        $pa = new JobOrdersPackage();
        $pa->job_order_id = $new_job_order_id;
        $pa->package_id = $package->package_id;
        $pa->package_value = $package->package_value;
        $pa->package_note = $package->package_note;
        $pa->package_note2 = $package->package_note2;
        $pa->package_qty = $package->package_qty;
        $pa->package_price = $package->package_price;
        $pa->save();
      }
      
    }

    $jobOrderLaborInfo = DB::table('job_orders_labors')
    ->where('job_order_id', '=', $job_order_id)
    ->get();

    foreach($jobOrderLaborInfo as $labor) {
      if($labor->status == 1) {
        $la = new JobOrdersLabor();
        $la->job_order_id = $new_job_order_id;
        $la->labor_id = $labor->labor_id;
        $la->labor_value = $labor->labor_value;
        $la->labor_qty = $labor->labor_qty;
        $la->labor_price = $labor->labor_price;
        $la->save();
      }
    }


    $jobOrderPartInfo = DB::table('job_orders_part_services')
    ->where('job_order_id', '=', $job_order_id)
    ->get();

     foreach($jobOrderPartInfo as $part) {
      if($part->status == 1) {
        $par = new JobOrdersPartService();
        $par->job_order_id = $new_job_order_id;
        $par->part_id = $part->part_id;
        $par->part_value = $part->part_value;
        $par->part_qty = $part->part_qty;
        $par->part_price = $part->part_price;
        $par->save();
      }
    }
   } else {
      $new_job_order_id = $check[0]->id;
    }
     return response()->json(['success'=> true, 'newJobOrderId' => $new_job_order_id ]);

    }

  
  public function duplicateParts($job_order_id) {
    $jobOrderPartInfo = DB::table('job_orders_part_services')
    ->where('job_order_id', '=', $job_order_id)
    ->get();

     return response()->json(['success'=> true, 'jobOrderPartDuplicate' => $jobOrderPartInfo ]);

  }

  public function changePaymentLabel() {
    JobOrder::where("id", $_GET['hidden-job-order-id-payment-label'])->update(
      [
        "payment_label" => $_GET['payment-label-field'],
  
      ]
    );
     return response()->json(['success'=> true, 'message' => 'Label has been changed!', 'label' =>  $_GET['payment-label-field']]);

  }

  public function enableItem($type) {

    $job_order_id = $_GET['job_order_id'];
    $itemNum = $_GET['itemNum'];
    if($type == 'package') {
       JobOrdersPackage::where("job_order_id", $job_order_id)->where("item_number", $itemNum)->update([
        "status" => 1,
      ]);
    } else if ($type == 'labor') {
      JobOrdersLabor::where("job_order_id", $job_order_id)->where("item_number", $itemNum)->update([
        "status" => 1,
      ]);
    } else {
      JobOrdersPartService::where("job_order_id", $job_order_id)->where("item_number", $itemNum)->update([
        "status" => 1,
      ]);
      echo "pasok";
    }

  }

  
}
