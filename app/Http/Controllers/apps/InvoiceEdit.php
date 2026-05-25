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
use App\Models\JobOrdersPackageManualItem;
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


    $jobOrderPackageManualSelected = DB::table('job_orders_package_manual_items')
    // ->where('status', '=', 1)
    ->where('job_order_id', '=', $job_order_id)
    ->get();


     $jobOrderPartServiceOption = DB::table('job_orders_part_service_options')
    ->where('status', '=', 1)
    ->orderBy('value','asc')
    ->get();


    $optionOneHtml = array();
    $keypckg = 1;
    if(isset($jobOrderPackageSelected[0]->job_order_id)) {
      foreach($jobOrderPackageSelected as $keypckg => $selected) {
      
        $optionOneHtml[] = '<div class="border row w-100  p-2 '.(($selected->status == 2) ? 'disable-package-item' : '').'" id="item-list-package-'.$keypckg.'"   data-repeater-item><div class="col-md-6 col-12 mb-md-0 mb-3">
        <input type="hidden" name="package-id" value="'.$selected->id.'" />
        <select id="package-option-'.$keypckg.' select1Basic'.$keypckg.'" name="package-option" class="select2" data-allow-clear="true"  onchange="calculatePackage('.$keypckg.')" style="width: 0px;">
          <option value="">Select Package</option>';
          foreach($jobOrderPackageOption as $options) {
                  $optionOneHtml[] = '<option value="'.$options->id.'" '.(($options->id == $selected->package_id) ? "selected" : "").'>'.$options->value.'</option>';
              }
        $optionOneHtml[] =  '</select></div>
            <div class="col-md-4 col-12">
             <input type="text" class="form-control package mb-3" name="package-note2" id="package-note2-'.$keypckg.'" value="'.$selected->package_note2.'" />
            </div>
              
            <div class="col-md-2 col-12 mb-md-0 mb-3 color-black d-flex">
                      <span class="pt-2 pl-2">₱</span>
                    <input type="text" class="form-control package mb-3" name="package-price" id="package-price-'.$keypckg.'" value="' .number_format($selected->package_price, 2, '.', ',').'" placeholder="0" onchange="calculatePackage('.$keypckg.');" />
                  <i class="mdi mdi-close cursor-pointer color-black" onclick="deleteItem('.$keypckg.', 0, '.$selected->id.');recalculateAll()" ></i>
            </div>
        </div>';
        $keypckg++;
      }
    } else {
      $optionOneHtml = false;
    }
   


    $optionOneTwoHtml = array();
    $keypckgm = 1;
    if(isset($jobOrderPackageManualSelected[0]->job_order_id)) {

      foreach($jobOrderPackageManualSelected as $keypckgm => $selectedm) {
        $optionOneTwoHtml[] = '<div class="border row w-100  p-2 '.(($selectedm->status == 2) ? 'disable-packagemanual-item' : '').'" id="item-list-packagemanual-'.$keypckgm.'"data-repeater-item>
        <div class="col-md-3 col-12 mb-md-0 mb-3">
          <input type="hidden" name="package-sub-id" value="'.$selectedm->id.'" />
          <select id="package-sub-option-'.$keypckgm.' select2Basic'.$keypckgm.'" name="package-sub-option" class="select2" data-allow-clear="true"  onchange="calculatePackageManualItem('.$keypckgm.', this)">
            <option value="" id="package-sub-selected-'.$keypckgm.'">Select Parts</option>';
            foreach($jobOrderPartServiceOption as $optionsPart) {
                  $optionOneTwoHtml[] = '<option value="'.$optionsPart->id.'" '.(($optionsPart->id == $selectedm->part_id) ? "selected" : "").'>'.$optionsPart->value.'</option>';
                }
        $optionOneTwoHtml[] = '
          </select> 
        </div>
          <div class="col-md-1 col-12">
                <input type="text" class="form-control packapackage-manualge mb-3" name="package-manual-part-number" id="package-manual-part-number'.$keypckgm.'" value="'.$selectedm->part_number.'" placeholder="Part Number" />
            </div>
             <div class="col-md-1 col-12">
                 <input type="hidden" name="package-manual-id" value="'.$selectedm->id.'" />
                <input type="text" class="form-control package-manual-qty mb-3" name="package-qty" id="package-qty'.$keypckgm.'" value="'.$selectedm->qty.'" placeholder="Quantity"  onchange="calculatePackageManual('.$keypckgm.')"/>
            </div>
               <div class="col-md-1 col-12">
                <input type="text" class="form-control package-manual mb-3" name="package-manual-supplier" id="package-manual-supplier'.$keypckgm.'" value="'.$selectedm->supplier.'" placeholder="Supplier"  onchange="calculatePackageManual('.$keypckgm.')" />
            </div>
              <div class="col-md-1 col-12">
                <input type="text" class="form-control package-manual mb-3" name="package-manual-supplier-inv" id="package-manual-supplier-inv'.$keypckgm.'" value="'.$selectedm->supplier_inv.'" placeholder="Supplier Inv"  onchange="calculatePackageManual('.$keypckgm.')" />
            </div>
            <div class="col-md-1 col-12">
                <input type="text" class="form-control package-manual mb-3" name="package-manual-cost" id="package-manual-cost'.$keypckgm.'" value="'.$selectedm->unit_cost.'" placeholder="Unit Cost"  onchange="formatNumberWithCommas(this);calculatePackageManual('.$keypckgm.')" />
            </div>
              
              <div class="col-md-1 col-12">
                <input type="text" class="form-control package-manual mb-3" name="package-manual-total-cost" id="package-manual-total-cost'.$keypckgm.'" value="'.$selectedm->total_cost.'" placeholder="Total Cost"  onchange="formatNumberWithCommas(this);calculatePackageManual('.$keypckgm.')" />
            </div>
           <div class="col-md-1 col-12">
                <input type="text" class="form-control package-manual mb-3" name="package-manual-srp-labor" id="package-manual-srp-labor'.$keypckgm.'" value="'.(($selectedm->srp_labor) ? $selectedm->srp_labor : 0).'" placeholder="SRP & Labor"  min="0" onchange="formatNumberWithCommas(this);calculatePackageManual('.$keypckgm.')" />
            </div>
            <div class="col-md-1 col-12">
                <input type="text" class="form-control package-manual mb-3" name="package-manual-total-srp" id="package-manual-total-srp'.$keypckgm.'" value="'.(($selectedm->total_srp) ? $selectedm->total_srp : 0).'" placeholder="Total SRP"  onchange="formatNumberWithCommas(this);calculatePackageManual('.$keypckgm.')" />
            </div>
          
            <div class="col-md-1 col-12 mb-md-0 mb-3 color-black d-flex">
               <span class="pt-2 pl-2">₱</span>
                <input type="text" class="form-control package-manual-price mb-3" name="package-manual-price" id="package-manual-price'.$keypckgm.'" value="'.(($selectedm->price) ? $selectedm->price : 0).'" placeholder="Price"  onchange="formatNumberWithCommas(this);calculatePackageManual('.$keypckgm.');recalculateAll()"/>
            
            <i class="mdi mdi-close cursor-pointer color-black" onclick="deleteItem('.$keypckgm.', 0.1, '.$selectedm->id.');recalculateAll()" ></i> 
            </div>
        </div>';
        $keypckgm++;
      }
    } else {
      $optionOneTwoHtml = false;
    }


    $jobOrderLaborOption = DB::table('job_orders_labor_options')
    ->where('status', '=', 1)
    ->orderBy('value','asc')
    ->get();
    $jobOrderLaborSelected = DB::table('job_orders_labors')
    // ->where('status', '=', 1)
    ->where('job_order_id', '=', $job_order_id)
    ->where('status', '!=', 0)

    ->get();

    

    $keyl = 1;
    $optionTwoHtml = array();
    if(isset($jobOrderLaborSelected[0]->job_order_id)) {
      foreach($jobOrderLaborSelected as $keyl => $selectedLabor) {

                $keyl++;
                @$sub_amount = $selectedLabor->labor_price * $selectedLabor->labor_qty;
                $optionTwoHtml[] = '<div class="border row w-100 p-3 pr-0 '.(($selectedLabor->status == 2) ? 'disable-labor-item' : '').'" style="padding-right: 0px !important;" id="item-list-labor-'.$keyl.'"  data-repeater-item>
                 <div class="col-md-1 col-12 mb-md-0 mb-3 color-black"  style="width: 4.333333%;">
                    <span id="item-counter-font labor-counter-'.$keyl.'" style="font-size: 12px;padding-top: 15px;">'.$keyl.'</span>
                  </div>
                  <div class="col-md-4 col-12 mb-md-0 mb-3">
                 
                 <input type="hidden" name="labor-id" value="'.$selectedLabor->id.'" />
                    <input type="text" class="form-control invoice-item-text '.(($selectedLabor->font_color == "on") ? "c-red" : "").'" name="labor-text" id="labor-text-'.$keyl.'"  value="'.$selectedLabor->labor_value.'" onchange="calculateLabor('.$keyl.')" />
                   <label class="switch">
                    <input type="checkbox" name="font-color" id="font-color-'.$keyl.'" class="switch-input is-invalid" onclick="changeColor('.$keyl.')" '.(($selectedLabor->font_color == "on") ? "checked" : "").'/>
                    <span class="switch-toggle-slider">
                      <span class="switch-on"></span>
                      <span class="switch-off"></span>
                    </span>
                    <span class="switch-label"></span>
                  </label>
                  
                    </div>
                   <div class="col-md-3 col-12 mb-md-0 mb-3">
                    <input type="text" class="form-control invoice-item-cost labor customer-hidden" name="labor-cost" id="labor-cost-'.$keyl.'" value="'.$selectedLabor->cost.'" placeholder="" min="1" max="" onchange="calculateLabor('.$keyl.')"/>
                  </div>
               

                     <div class="col-md-1 col-12 mb-md-0 mb-3">
                    <input type="text" class="form-control invoice-item-qty labor" name="labor-qty" id="labor-qty-'.$keyl.'" value="'.$selectedLabor->labor_qty.'" placeholder="1" min="1" max=""  onchange="calculateLabor('.$keyl.')"/>
                  </div>
                  <div class="col-md-1 col-12 mb-md-0 mb-3">
                    <input type="text" class="form-control  labor mb-3" name="labor-price" id="labor-price-'.$keyl.'" value="'.number_format($selectedLabor->labor_price, 2, '.', ',').'" placeholder="" min=""  onchange="calculateLabor('.$keyl.');formatNumberWithCommas(this)" />
                    
                  </div>
               
                  <div class="col-md-1 col-12 pe-0">
                    <p class="mb-0 pt-2 color-black amount-labor-sub d-flex" id="amount-labor-sub-'.$keyl.'">₱
                    <input type="text" class="form-control invoice-item-amount mb-3 p-0 border-0 pe-none" name="labor-amount" id="labor-amount-'.$keyl.'" value="'.number_format($selectedLabor->labor_amount, 2, '.', ',').'"  placeholder="" min="12" onchange="formatNumberWithCommas(this)"/>
                    </p>
                  </div>

                <div class="col-md-1 col-12 border-start text-right d-flex">
                   <input type="text" class="form-control invoice-item-code" name="labor-code" style="width: 40px;height: 35px;" id="labor-code-'.$keyl.'" value="'.$selectedLabor->labor_code.'"  placeholder="" min="12" onchange=""/>

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

   
    $jobOrderPartSelected = DB::table('job_orders_part_services')
    // ->where('status', '=', 1)
    ->where('job_order_id', '=', $job_order_id)
    ->where('status', '!=', 0)
    ->get();


    $keyprt = 1;
    $optionThreeHtml = array();
    // if(isset($jobOrderPartSelected[0]->job_order_id)) {
      foreach($jobOrderPartSelected as $keyprt => $selectedPart) {
          // print_r($selectedPart);
          // exit();
                $keyprt++;
     
                $part_price = $selectedPart->part_price;
                $part_qty = $selectedPart->part_qty;
                @$sub_amount = (int)$part_price * (int)$part_qty;

                $optionThreeHtml[] = '<div class="border row w-100 p-3 pr-0 '.(($selectedPart->status == 2) ? 'disable-part-item' : '').'" style="padding-right: 0px !important;"  id="item-list-part-'.$keyprt.'"  data-repeater-item>
                 <div class="col-md-1 mumber col-12 mb-md-0 mb-3 color-black" style="width: 2.333333%;">
                     <span id="item-counter-font part-counter-'.$keyprt.'" style="font-size: 13px;margin-top: 25px;">'.$keyprt.'</span>
                  </div>
                  <div class="col-md-2 col-12 mb-md-0 mb-3 " id="refresh-div-'.$keyl.'">
                      <div class="row">
                      <div class="col-2">
                      <i class="mdi mdi-content-copy me-1 js-textareacopybtn" id="part-icon" onclick="copyParts('.$keyprt.')"></i><span class="alert-coppied" id="icon-part-'.$keyprt.'">Coppied!</span>
                      </div>
                      <div class="col-10">
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

                  <div class="col-md-1 col-12 mb-md-0 mb-3"  style="width: 8.333333%;">
                    <input type="text" class="form-control invoice-item-part-note part" name="part-part-note" id="part-part-note-'.$keyprt.'" value="'.((isset($selectedPart->part_note)) ? $selectedPart->part_note : '').'" placeholder="" min="1" max="" onchange="calculatePart('.$keyprt.')" style="font-size: 12px;"/>
                  </div> 
                   <div class="col-md-1 col-12 mb-md-0 mb-3">
                    <input type="text" class="form-control invoice-item-part-number part" name="part-part-number" id="part-part-number-'.$keyprt.'" value="'.$selectedPart->part_number.'" placeholder="" min="1" max="" onchange="calculatePart('.$keyprt.')"/>
                  </div>            
                  <div class="col-md-1 col-12 mb-md-0 mb-3">
                    <input type="text" class="form-control invoice-item-supplier part customer-hidden" name="part-supplier" id="supplier-'.$keyprt.'" value="'.$selectedPart->supplier.'" placeholder="" min="1" max="" onchange="calculatePart('.$keyprt.')"/>
                  </div>
                  <div class="col-md-1 col-12 mb-md-0 mb-3">
                    <input type="text" class="form-control invoice-item-supplier-inv part customer-hidden" name="part-supplier-inv" id="supplier-inv-'.$keyprt.'" value="'.$selectedPart->supplier_inv.'" placeholder="" min="1" max="" onchange="calculatePart('.$keyprt.')"/>
                  </div>
                   <div class="col-md-1 col-12 mb-md-0 mb-3">
                    <input type="text" class="form-control invoice-unit-cost part customer-hidden" name="part-unit-cost" id="part-unit-cost-'.$keyprt.'" value="'.$selectedPart->unit_cost.'" placeholder="1" min="1" max="" onchange="calculatePart('.$keyprt.')"/>
                  </div>
                      <div class="col-md-1 col-12 pe-0">
                    <input type="text" class="form-control invoice-unit-cost part customer-hidden" name="part-total-cost" id="part-total-cost-'.$keyprt.'" value="'.$selectedPart->total_cost.'" placeholder="1" min="1" max="" onchange="calculatePart('.$keyprt.')"/>
                 
                  </div>

                  <div class="col-md-1 col-12 mb-md-0 mb-3 w-6">
                    <input type="text" class="form-control form-control invoice-item-price part" name="part-qty" id="part-qty-'.$keyprt.'" value="'.$part_qty.'" placeholder="1" min="1" max="" onchange="calculatePart('.$keyprt.')"/>
                  </div>

                  <div class="col-md-1 col-12 mb-md-0 mb-3">
                    <input type="text" class="form-control part mb-3" name="part-price" id="part-price-'.$keyprt.'" value="'.((is_float($part_price)) ? number_format($part_price, 2, '.', ',') : $part_price ).'" placeholder="" min="" onchange="calculatePart('.$keyprt.');formatNumberWithCommas(this)" />
                  </div>
               
             
                  <div class="col-md-1 number col-12 border-start text-right d-flex">
                    <p class="mb-0 pt-2 color-black amount-part-sub d-flex" id="amount-part-sub-'.$keyprt.'">₱
                    <input type="text" class="form-control invoice-item-amount mb-3 p-0 border-0 pe-none" name="part-amount" id="part-amount-'.$keyprt.'" value="'.number_format($selectedPart->part_amount, 2, '.', ',').'" placeholder="" min="12" onchange="formatNumberWithCommas(this)" />
                    </p>
                    <span style="color: #eaeaec;font-size: 25px;padding-right: 4px;">|</span>
                   <input type="text" class="form-control invoice-item-code" name="part-code" style="width: 40px;height: 35px;" id="part-code-'.$keyprt.'" value="'.$selectedPart->part_code.'"  placeholder="" min="12" onchange=""/>

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

   $countEnabledPackageManualData = DB::table('job_orders_package_manual_items')
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
    $countEnabledPackageManual = count($countEnabledPackageManualData);
    $countEnabledLabor = count($countEnabledLaborData);
    $countEnabledPart = count($countEnabledPartData);

    if(!empty($_POST)) {
    return response()->json(['success'=> true, 'optionOneHtml' => $optionOneHtml]);
    } else {
     return view('content.apps.app-invoice-edit', ['invoice_date' => $newInvoiceDate, 'expire_date' => $jobOrderInfo[0]->expire_date, 'modeOfPayment' => $modeOfPayment, 'specialNotes' => $specialNotes, 'optionStatus' => $optionStatus, 'packageTotalItem' => $keypckg, 'partTotalItem' => $keyprt, 'laborTotalItem' => $keyl, 'optionThreeHtml' => $optionThreeHtml, 'optionTwoHtml' => $optionTwoHtml, 'optionOneHtml' => $optionOneHtml, 'optionOneTwoHtml' => $optionOneTwoHtml, 'job_order_id' => $job_order_id, 'jobOrderInfo' => $jobOrderInfo, 'jobOrderPartServiceOption' => $jobOrderPartServiceOption, 'jobOrderLaborOption' => $jobOrderLaborOption, 'jobOrderPackageOption' => $jobOrderPackageOption, 'countEnabledPart' => $countEnabledPart, 'countEnabledPackage' => $countEnabledPackage,  'countEnabledPackageManual' => $countEnabledPackageManual, 'countEnabledLabor' => $countEnabledLabor ]);
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
            "part_id"   =>  NULL,
            "part_note"   =>  NULL,
            "part_number"   =>  NULL,
            "supplier"   =>  NULL,
            "supplier_inv"   =>  NULL,
            "unit_cost"   => 0,
            "total_cost"   =>  0,
            "part_price"  =>  0,
            "part_amount"   =>  0,
            "part_code"   =>  NULL,
            "status"   =>  0,
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
            "labor_code"   =>  NULL,
            "status"   =>  0,

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
            "status"   =>  0,
          ] );
     return response()->json(['success'=> true, 'message' => 'Item deleted!']);

  }


      public function deletePackageManualItem($item_id){
    JobOrdersPackageManualItem::where("id", $item_id)->update(
          [
            "qty"     => 0,
            "part_number"  =>  "",
            "total_cost"  =>  0,
            "unit_cost"  =>  0,
            "price"  =>  0,
            "status"  =>  2,
         
          ] );
     return response()->json(['success'=> true, 'message' => 'Item deleted!']);

  }

  
  public function saveJobOrderItem($job_order_id){
    
    $jobOrderInfo = DB::table('job_orders')
    ->join('cars', 'cars.id', '=', 'job_orders.car_id')
    ->join('owners', 'owners.id', '=', 'cars.owner_id')
    ->where('job_orders.id', '=', $job_order_id)
    ->get();
   
    // print_r($_POST);
    if($_POST['status'] == 1) {
      $status_display = "estimate";
    } else if ($_POST['status'] == 2) {
      $status_display = "job order";
    } else if ($_POST['status'] == 3) {
      $status_display = "receipt";
    } else {
      $status_display = $_POST['status-text'];
    }

    // check stock
    foreach($_POST as $key => $value) {
    $arrayPart = array();
    $checkProceedArray = array();

    if($key== 'group-a') {

      $stock_status = true;
      $status_pak = array();
      $getPackageOption = DB::table('job_orders_packages')
        ->where('job_order_id', '=', $value[0]['package-option'])
        ->get();

      foreach($getPackageOption as $pa) {
            $getpackagesSub = DB::table('package_sub_items')
          ->where('package_id', '=', $pa->package_id)
          ->where('status', '=', 1)
          ->get();


      foreach($getpackagesSub as $subp) {
        $arrayPart[] = array(
          'id' => $subp->part_id,
          'qty' => $subp->package_qty,
        );
      }

      foreach($arrayPart as $var) {
          $getInventory = DB::table('job_orders_part_service_options')
        ->where('id', '=', $var['id'])
        ->get();

        if($getInventory[0]->stock > $var['qty'] || $getInventory[0]->stock == $var['qty']) {
          $stock_status = true;
          $message = 'Stock available';
        } 

        if($var['qty'] > $getInventory[0]->stock) {
          $message = 'Stock conflict on package! Please check stocks available.';
          $stock_status = false;
          return response()->json(['success'=> $stock_status, 'message' => $message, 'qty' => $var['qty'], 'package_value' => $getInventory[0]->stock, 'stock' => $getInventory[0]->stock ]);

        }
      }

      }
      // print_r($arrayPart);
    }


 
   
    // check first
    if($key== 'group-a2') {
    foreach($value as $packageManual){
          if($packageManual['package-sub-option'] > "") {
            if($packageManual['package-qty'] !== '0') {
                $checkProceedArray[] = true;
            } else {
               $checkProceedArray[] = false;
                return response()->json(['success'=> false, 'message' => 'One of the Part item has O quantity!', 'section' => 'package']);
            }
           
          }
      }
    }
      // check first
    if($key== 'group-c') {
      foreach($value as $part){
          if(isset($part['part-text']) && $part['part-text'] > "") {
            if($part['part-qty'] !== '0') {
              $getPartServiceOption = DB::table('job_orders_part_service_options')
              ->where('id', '=', $part['part-option'])
              ->get();
              $stock_deduct = $getPartServiceOption[0]->stock - $part['part-qty'];
              if($getPartServiceOption[0]->exempt == 0) {
                if(!($stock_deduct < 0)) {
                  $checkProceedArray[] = true;
                } else {
                  $checkProceedArray[] = false;
                  return response()->json(['success'=> false, 'message' => 'Invalid stock for '.$part['part-text'] .'!']);
                }
              }
                } else {
                      $checkProceedArray[] = false;
                      return response()->json(['success'=> false, 'message' => 'One of the Part item has O quantity!', 'section' => 'part']);
                }
            }
        }
      }



    if($key== 'group-a2') {
    foreach($value as $packageManual){
          if($packageManual['package-sub-option'] > "") {
            if($packageManual['package-qty'] !== '0') {
              $details = @JobOrdersPartServiceOption::find($packageManual['package-sub-option'])["value"];
              JobOrdersPackageManualItem::where("id", $packageManual['package-manual-id'])->update(
                [
                  "part_id"   => (($packageManual['package-sub-option'] > 0) ? $packageManual['package-sub-option'] : NULL),
                  "qty"   => $packageManual['package-qty'],
                   "details" => ((isset($details)) ? $details : NULL), 
                  "total_cost"   => $packageManual['package-manual-total-cost'],
                  "supplier"   => $packageManual['package-manual-supplier'],
                  "supplier_inv"   => $packageManual['package-manual-supplier-inv'],
                  "unit_cost"   => $packageManual['package-manual-cost'],
                  "part_number"   => $packageManual['package-manual-part-number'],
                  "srp_labor"   => $packageManual['package-manual-srp-labor'],
                  "total_srp"   => $packageManual['package-manual-total-srp'],
                   "price"   => $packageManual['package-manual-price'],
                   "status"   => (($packageManual['package-sub-option'] > 0) ? 1 : 2),
                ]
              );
            }
           
          }
      }
    }

  

    // if($key== 'group-c') {
    //   if(isset($part['part-text']) && $part['part-text'] > "") {
    //       if($part['part-qty'] !== '0') {
    //         $getPartServiceOption = DB::table('job_orders_part_service_options')
    //         ->where('id', '=', $part['part-option'])
    //         ->get();
    //         $stock_deduct = $getPartServiceOption[0]->stock - $part['part-qty'];
    //           if(!($stock_deduct < 0)) {
    //             JobOrdersPartServiceOption::where("id", $part['part-id'])->update(
    //               ["stock"   => $stock_deduct]
    //           );
    //           }
    //       } 
    //   }
    // }
  }


    //check inventory stock deduction
  if($status_display == "job order" && $_POST['balance'] == 0 && $jobOrderInfo[0]->status_inventory_deduction == 0) {
    $check = 1;
   } else {
    $check = 0;
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
        "status_inventory_deduction" => $check,
        
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

      if($key == 'group-a2') {
        if($check == 1) {
            // sub package deduct quantity
             $stock_status = true;
            $status_pak = array();
            $getPackageOption = DB::table('job_orders_package_manual_items')
              ->where('job_order_id', '=', $job_order_id)
              ->where('status', '=', 1)
              ->get();

            foreach($getPackageOption as $pa) {
                //   $getpackagesSub = DB::table('package_sub_items')
                // ->where('package_id', '=', $pa->package_id)
                // ->where('status', '=', 1)
                // ->get();
            // foreach($getpackagesSub as $subp) {
              $arrayPart[] = array(
                'id' => $pa->part_id,
                'qty' => $pa->qty,
              );
            // }
          }

          
            foreach($arrayPart as $var) {
              $getInventory = DB::table('job_orders_part_service_options')
              ->where('id', '=', $var['id'])
              ->get();


              if($var['qty'] > $getInventory[0]->stock) {
                $message = 'Stock conflict on package! Please check stocks available.';
                $stock_status = false;
                return response()->json(['success'=> false, 'message' => $message, 'qty' => $var['qty'], 'package_value' => $getInventory[0]->stock, 'stock' => $getInventory[0]->stock ]);

              }

                if($getInventory[0]->stock > $var['qty'] || $getInventory[0]->stock == $var['qty']) {
                $stock_status = true;
                $message = 'Stock available';

               $stock_deductPackage = $getInventory[0]->stock - $var['qty'];
                  JobOrdersPartServiceOption::where("id", $var['id'])->update(
                  ["stock"   => $stock_deductPackage]
                  );
              } 
            }
        }
      }

      if($key== 'group-b') {
        foreach($value as $labor){
        
         if(isset($labor['labor-text']) && $labor['labor-text'] > "") {
             JobOrdersLabor::where("id", $labor['labor-id'])->update(
            [
            "job_order_id" => $job_order_id,
             "labor_qty"     => ((isset($labor['labor-qty'])) ? $labor['labor-qty'] : 1),
            "labor_value"  =>  ((isset($labor['labor-text'])) ? $labor['labor-text'] : ""),
            "font_color"  =>  ((isset($labor['font-color'])) ? $labor['font-color'][0] : NULL),
            "cost"  =>  (($labor['labor-cost'] > '') ? $labor['labor-cost'] : 0),
            "part_number"   =>  ((isset($labor['labor-part-number'])) ? $labor['labor-part-number'] : ""),
            "labor_price"   => ((isset($labor['labor-price'])) ?  str_replace(",", "", $labor['labor-price'] ) : 0),
            "labor_amount"   => ((isset($labor['labor-amount'])) ? str_replace(",", "", $labor['labor-amount'])  : 0),
            "labor_code"  =>  ((isset($labor['labor-code'])) ? strtoupper($labor['labor-code']) : ""),
          
          
            ]
          );

          } 
            
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
            "part_note"   => ((isset($part['part-part-note'])) ? $part['part-part-note'] : ""),
            "part_number"   => ((isset($part['part-part-number'])) ? $part['part-part-number'] : ""),
            "supplier"   => ((isset($part['part-supplier'])) ? $part['part-supplier'] : ""),
            "supplier_inv"   => ((isset($part['part-supplier-inv'])) ? $part['part-supplier-inv'] : ""),
            "unit_cost"   => ((isset($part['part-unit-cost'])) ? $part['part-unit-cost'] : ""),
            "total_cost"   => ((isset($part['part-total-cost'])) ? number_format($part['part-total-cost'], 2, '.', '') : ""),
            "part_price"  => ((isset($part['part-price'])) ? intval(str_replace(",", "", $part['part-price'] ))  : 0),
            "part_amount"   => ((isset($part['part-amount'])) ? intval(str_replace(",", "", $part['part-amount'] )) : 0),
            "part_code"  =>  ((isset($part['part-code'])) ? strtoupper($part['part-code']) : ""),

          ] );


          

        if($check == 1) {
            ////////////////////////////
            $getPartServiceOption = DB::table('job_orders_part_service_options')
            ->where('id', '=', $part['part-option'])
            ->get();
            $stock_deduct = $getPartServiceOption[0]->stock - $part['part-qty'];
            if($stock_deduct >= 0) {
                JobOrdersPartServiceOption::where("id", $part['part-option'])->update(
                 ["stock"   => $stock_deduct]
              );
            } else {
               return response()->json(['success'=> false, 'message' => 'Invalid stock!']);
            }

          }
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
      ->where('job_order_number', '=', $jobOrderInfo[0]->job_order_number)
      ->where('status', '=',  2)
      ->get();

      // if(!isset($check[0])) {
      //     $check =  DB::table('job_orders')
      //   ->where('ex_job_order_id', '=', $job_order_id)
      //   ->where('status', '=',  2)
      //   ->get();
      // }
      //   if(!isset($check[0])) {
      //     $check =  DB::table('job_orders')
      //   ->where('id', '=', $jobOrderInfo[0]->ex_job_order_id)
      //   ->where('status', '=',  2)
      //   ->get();
      // }

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
      $jo->year = $data->year;
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
      $jo->customer_name = $data->customer_name;
      $jo->amount_net_vat = $data->amount_net_vat;
      $jo->expire_date = $data->expire_date;
      $jo->discount = $data->discount;

      $jo->save();
 

    }
    $new_job_order_id = $jo->id;

    $jobOrderPackageInfo = DB::table('job_orders_packages')
    ->where('job_order_id', '=', $job_order_id)
    ->get();

    foreach($jobOrderPackageInfo as $package) {
        $pa = new JobOrdersPackage();
        $pa->job_order_id = $new_job_order_id;
        $pa->item_number = $package->item_number;
        $pa->package_id = $package->package_id;
        $pa->package_value = $package->package_value;
        $pa->package_note = $package->package_note;
        $pa->package_note2 = $package->package_note2;
        $pa->package_qty = $package->package_qty;
        $pa->package_price = $package->package_price;
          if($package->status == 1) {
            $pa->status = 1;
          } else {
            $pa->status = 2;
          }
        $pa->save();
      
    }

     $jobOrderPackageManualInfo = DB::table('job_orders_package_manual_items')
    ->where('job_order_id', '=', $job_order_id)
    ->get();

     foreach($jobOrderPackageManualInfo as $packageMa) {
        $pam = new JobOrdersPackageManualItem();
        $pam->job_order_id = $new_job_order_id;
        $pam->part_id = $packageMa->part_id;
        $pam->item_number = $packageMa->item_number;
        $pam->details = $packageMa->details;
        $pam->part_number = $packageMa->part_number;
        $pam->total_cost = $packageMa->total_cost;
        $pam->unit_cost = $packageMa->unit_cost;
        $pam->srp_labor = $packageMa->srp_labor;
        $pam->total_srp = $packageMa->total_srp;
        $pam->qty = $packageMa->qty;
        $pam->price = $packageMa->price;
          if($packageMa->status == 1) {
            $pam->status = 1;
          } else {
            $pam->status = 2;
          }
        $pam->save();
      
    }

    $jobOrderLaborInfo = DB::table('job_orders_labors')
    ->where('job_order_id', '=', $job_order_id)
    ->get();

    foreach($jobOrderLaborInfo as $labor) {
        $la = new JobOrdersLabor();
        $la->job_order_id = $new_job_order_id;
        $la->item_number = $labor->item_number;
        $la->labor_id = $labor->labor_id;
        $la->labor_value = $labor->labor_value;
        $la->labor_qty = $labor->labor_qty;
        $la->labor_price = $labor->labor_price;
        $la->labor_amount = $labor->labor_amount;

          if($labor->status == 1) {
            $la->status = 1;
          } else {
            $la->status = 2;
          }
        $la->save();
    }


    $jobOrderPartInfo = DB::table('job_orders_part_services')
    ->where('job_order_id', '=', $job_order_id)
    ->get();

     foreach($jobOrderPartInfo as $part) {
        $par = new JobOrdersPartService();
        $par->job_order_id = $new_job_order_id;
        $par->item_number = $part->item_number;
        $par->part_id = $part->part_id;
        $par->part_value = $part->part_value;
        $par->part_qty = $part->part_qty;
        $par->part_price = $part->part_price;
        $par->part_number = $part->part_number;
        $par->part_note = $part->part_note;
        $par->supplier = $part->supplier;
        $par->supplier_inv = $part->supplier_inv;
        $par->unit_cost = $part->unit_cost;
        $par->total_cost = $part->total_cost;
        $par->part_amount = $part->part_amount;
        if($part->status == 1) {
          $par->status = 1;
        } else {
          $par->status = 2;
        }
        $par->save();
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
     } else if ($type == 'part') {
     
      JobOrdersPartService::where("job_order_id", $job_order_id)->where("item_number", $itemNum)->update([
        "status" => 1,
      ]);
  
    } else {
       JobOrdersPackageManualItem::where("job_order_id", $job_order_id)->where("item_number", $itemNum)->update([
        "status" => 1,
      ]);
    }

  }

  
  public function checkInventory($inventory_id) {

    $getInventory = DB::table('job_orders_part_service_options')
    ->where('id', '=', $inventory_id)
    ->get();



    if($getInventory[0]->stock > 0) {
      $status = true;
      $message = 'Stock available';

    } else {
      $status = false;
      $message = 'Part out of stock!';
    }

    if($_GET['qty'] > $getInventory[0]->stock) {
      $message = 'Stock conflict! Please check stocks available.';
      $status = false;
    }


     return response()->json(['success'=> $status, 'stock' => $getInventory[0]->stock, 'message' => $message, 'exempted' => $getInventory[0]->exempt ]);

  }


    public function checkInventoryPackageManualSelect($package_id) {
      $getInventory = DB::table('job_orders_part_service_options')
      ->where('id', '=', $package_id)
      ->get();
 
      if($getInventory[0]->stock < 1) {
        $message = $getInventory[0]->value. ' is Out of stock!';
        $stock_status = false;
        return response()->json(['success'=> false, 'message' => $message,  'package_value' => $getInventory[0]->value, 'stock' => $getInventory[0]->stock, 'exempted' => $getInventory[0]->exempt   ]);

      } else {
        return response()->json(['success'=> true, 'message' => 'stock available!', 'getInventory' => $getInventory[0], 'exempted' => $getInventory[0]->exempt ]);
      }
    }
  
  public function checkInventoryPackage($package_id) {
 
    $job_order_id = $_GET['job_order_id'];

    $getSubPackage = DB::table('package_sub_items')
    ->where('package_id', '=', $package_id)
    ->where('status', '=', 1)
    ->get();

    

        $getPackageMain = DB::table('job_orders_package_options')
    ->where('id', '=', $package_id)
    ->get();


        $jobOrderPartServiceOption = DB::table('job_orders_part_service_options')
    ->where('status', '=', 1)
    ->orderBy('value','asc')
    ->get();

    $stock_status = true;
    $status = array();
    foreach($getSubPackage as $sub) {
      
      $getInventory = DB::table('job_orders_part_service_options')
        ->where('id', '=', $sub->part_id)
        ->get();


        if($getInventory[0]->stock > $sub->package_qty) {
          $status[] = true;
          $message = 'Stock available';


        } 

        if($sub->package_qty > $getInventory[0]->stock) {

          $message = 'Stock conflict! Please check stocks available.';
          $status[] = false;
          $stock_status = false;
        }

    }
      $optionOneTwoHtml = array();

      $getSubPackageArray = array();


    if($stock_status == false) {
     $message = 'Stock conflict! Please check stocks available.';
      $final_status = false;
    } else {
      $final_status = true;
      $message = 'Stock available';

    $keypckgm = 0;

    if($final_status == true) {
 
        foreach($getSubPackage as $keypckgm => $selectedm) {
          $keypckgm++;
            // $getSubPackageArray[] = array(
            //   "item_counter" => $keypckgm++,
            //   "package_id" => $selectedm->package_id,
            //   "part_id"=> $selectedm->part_id,
            //   "package_qty"=> $selectedm->package_qty,
            //   "package_details"=> $selectedm->package_details,
            //   "package_part_number"=> $selectedm->package_part_number,
            //   "supplier"=> $selectedm->supplier,
            //   "supplier_inv"=> $selectedm->supplier_inv,
            //   "package_unit_cost" => $selectedm->package_unit_cost,
            //   "package_total_cost"=> $selectedm->package_total_cost,
            //   "package_unit_selling_price_with_labor"=> $selectedm->package_unit_selling_price_with_labor,
            //   "package_sell_price"=>   $selectedm->package_sell_price,
            // );

          
        JobOrdersPackageManualItem::where("job_order_id", $job_order_id)->where("item_number", $keypckgm)->update([
          "status" => 1,
            "part_id"=> $selectedm->part_id,
            "details"=> $selectedm->package_details,
            "qty"=> $selectedm->package_qty,
            "part_number"=> $selectedm->package_part_number,
            "unit_cost" => $selectedm->package_unit_cost,
            "total_cost"=> $selectedm->package_total_cost,
            "price"=>   $selectedm->package_sell_price,
        ]);

  
        }

        $total = $keypckgm + 1;
        // delete others
           for($x=$total;$x<=7;$x++) {
              JobOrdersPackageManualItem::where("job_order_id", $job_order_id)->where("item_number", $x)->update([
              "status" => 2,
                "part_id"=> NULL,
                "qty"=> 0,
                "part_number"=> NULL,
                "unit_cost" => 0,
                "total_cost"=> 0,
                "srp_labor"=> NULL,
                "total_srp"=> NULL,
                "price"=>   NULL,
            ]);

           }
        if(isset($getPackageMain)) {
          foreach($getPackageMain as $da) {  
          JobOrdersPackage::where("job_order_id", $job_order_id)->update([
          "item_number" => 1,
            "package_id"=> $da->id,
            "package_value"=> $da->value,
            "package_qty" => 1,
            "package_price"=> $da->package_price,
          ]);
          } 
        }
       
      }
     
    }

//////////////////////////////////////////////////
    return response()->json(['success'=> $final_status, 'getSubPackageArray' => $getSubPackageArray, 'message' => $message ]);


  }


  public function selectPackageSub($package_id) {
    echo $package_id;
  } 


   public function invoiceCheckNumber($invoiceId) {
    $job_order_id = $_GET['hidden-job-order-id'];
    $getNumber = DB::table('repair_estimate_numbers')
    ->where('value', '=', $invoiceId)
    ->get();


    $checkValid = DB::table('repair_estimate_numbers')->where('status', '=', 1)
      ->orderBy('id','desc')
      ->get();
  

     $revert =  DB::table('job_orders')
    ->where('id', '=', $job_order_id)
    ->get(); 

     $rever_number = number_format($revert[0]->job_order_number);

    if($invoiceId > $checkValid[0]->value) {
      return response()->json(['success'=> false, 'message' => 'invalid number!', 'revert_number' => $rever_number ]);
    }
    if(isset($getNumber[0])) {
      return response()->json(['success'=> false, 'message' => 'number not available!', 'revert_number' => $rever_number ]);
    } else {

      JobOrder::where("id", $job_order_id)->update([
        "job_order_number" => $invoiceId,
      ]);
     
      return response()->json(['success'=> true, 'message' => 'number available!' ]);
    }

  }
  
}


 