@extends('layouts/layoutMaster')

@foreach($jobOrderInfo as $k => $data)
  @section('title', (($data->status == 1) ? 'RE ' : 'JO '). $data->plate_number)
@endforeach
@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />

<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/tagify/tagify.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/typeahead-js/typeahead.css')}}" />
@endsection

@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/app-invoice.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/spinkit/spinkit.css')}}" />


@endsection
<style type="text/css">
.tbl-header {
  background-color: #fce800;
}
.disable-part-item, .disable-labor-item, .disable-package-item {
  display: none !important; 
}
#item-counter-font {
  font-size: 12px !important;
}
.fixed-section-part {
  position: sticky;
    top: 104px;
    z-index: 99999999;
    background: white;
}
input.c-red, .c-red {
  color: red !important;
}


  </style>
@section('vendor-script')
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave-phone.js')}}"></script>
<script src="{{asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js')}}"></script>


<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/tagify/tagify.js')}}"></script>
<script src="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js')}}"></script>
<script src="{{asset('assets/vendor/libs/typeahead-js/typeahead.js')}}"></script>
<script src="{{asset('assets/vendor/libs/bloodhound/bloodhound.js')}}"></script>

@endsection

@section('page-script')
<script src="{{asset('assets/js/offcanvas-add-payment.js')}}"></script>
<script src="{{asset('assets/js/offcanvas-send-invoice.js')}}"></script>
<script src="{{asset('assets/js/app-invoice-edit.js?v=4.51.1')}}"></script>

<script src="{{asset('assets/js/forms-selects.js')}}"></script>
<script src="{{asset('assets/js/forms-tagify.js')}}"></script>
<script src="{{asset('assets/js/forms-typeahead.js')}}"></script>

@endsection

@section('content')
@foreach($jobOrderInfo as $k => $data)
<form id="form-job-order">
   @csrf
  <input type="hidden" name="hidden-job-order-id" id="hidden-job-order-id" value="{{$job_order_id}}" />
  <input type="hidden" name="hidden-invoice-date" id="hidden-invoice-date" value="{{$invoice_date}}" />
  <input type="hidden" name="hidden-job-order-current-status" id="hidden-job-order-current-status" value="{{$data->job_order_status}}" />
  <input type="hidden" name="hidden-job-order-new-status" id="hidden-job-order-new-status" value="" />
  <input type="hidden" name="hidden-package-sub-totals" id="hidden-package-sub-totals" value="" />
  <input type="hidden" name="hidden-labor-sub-totals" id="hidden-labor-sub-totals" value="" />
  <input type="hidden" name="hidden-part-sub-totals" id="hidden-part-sub-totals" value="" />
  
  <input type="hidden" name="hidden-package-total-item" id="hidden-package-total-item" value="{{$countEnabledPackage}}" />
  <input type="hidden" name="hidden-labor-total-item" id="hidden-labor-total-item" value="{{$countEnabledLabor}}" />
  <input type="hidden" name="hidden-part-total-item" id="hidden-part-total-item" value="{{$countEnabledPart}}" />
  
  <input type="hidden" name="hidden-payment2" id="hidden-payment2" value="{{$data->payment2}}" />
  <input type="hidden" name="hidden-payment-label" id="hidden-payment-label" value="{{$data->payment_label}}" />


<div class="row invoice-edit">
  <!-- Invoice Edit-->
   <div class="row">
    <div class="col-lg-9 col-12 mb-lg-0 mb-4 mb-4 d-flex">
          


    </div>
  </div>
  <br>
  <div class="col-md-12 col-lg-9 col-12 mb-lg-0 mb-4">
    <div class="card invoice-preview-card">
      <div class="card-body">
        <div class="row mx-0">
          <div class="col-md-7 mb-md-0 mb-4 ps-0">
            <div class="d-flex svg-illustration align-items-center gap-2 mb-4">
              <span class="app-brand-logo demo"><img src="/assets/img/branding/rapide-invoice-logo.jpg" /></span>
            </div>
            <!-- <p class="mb-1">Office 149, 450 South Brand Brooklyn</p>
            <p class="mb-1">San Diego County, CA 91905, USA</p>
            <p class="mb-0">+1 (123) 456 7891, +44 (876) 543 2198</p> -->
          </div>
          <div class="col-md-5 pe-0 ps-0 ps-md-2">
            <div class="row mb-2 g-2 justify-content-end">
              <div class="col-sm-6 mb-2 d-md-flex align-items-center justify-content-end"> 
                  <div class="input-group">
                        <i class="mdi mdi-ballot mdi-36px dropdown-con"></i>
                           <select class="btn btn-outline-primary dropdown-toggle dropdown-menu form-select item-details mb-3 waves-effect{{$optionStatus}}" name="status" id="job-order-status" onchange="showStatus()">
                                  <option value="1" id="option1" class="bg-label-warning" {{$data->job_order_status == 1 ? 'selected' : ''}}>ESTIMATE</option>
                                  <option value="2" class="bg-label-info"  {{$data->job_order_status == 2 ? 'selected' : ''}}>JOB ORDER</option>
                                  <option value="3" class="alert-solid-success"  {{$data->job_order_status == 3 ? 'selected' : ''}}>RECEIPT</option>
                                  <option value="4">Others</option>
                            </select>
                        <input type="text" class="form-control" aria-label="Text input with dropdown button" name="status-text" id="status-text" value="">
                      </div>
              </div>
            </div>
            <dl class="row mb-2 g-2">
              <dt class="col-sm-6 mb-2 d-md-flex align-items-center justify-content-end">
                @if($data->job_order_status == '1')
                <span class="fw-normal">Repair Estimate #</span>
                @else
                <span class="fw-normal">Job Order #</span>
                @endif
              </dt>
              <dd class="col-sm-6">
                <div class="input-group input-group-merge disabled">
                  <input type="text" class="form-control" disabled placeholder="74909" value="{{$data->job_order_number}}" id="invoiceId" />
                </div>
              </dd>
              <dt class="col-sm-6 mb-2 d-md-flex align-items-center justify-content-end">
                <span class="fw-normal">Date:</span>
              </dt>
              <dd class="col-sm-6">
                <input type="text" class="form-control invoice-date" name="invoice_date" placeholder="DD-MM-YYY" value="{{$invoice_date}}"/>
              </dd>
              <dt class="col-sm-6 mb-2 d-md-flex align-items-center justify-content-end">
                <span class="fw-normal">Expires:</span>
              </dt>
              <dd class="col-sm-6">
                <input type="text" class="form-control due-date" name="expire_date" placeholder="YYYY-MM-DD" />
              </dd>
            </dl>
          </div>
        </div>
      </div>
      <hr class="my-0" />
      <div class="card-body">
        <div class="d-flex justify-content-between flex-wrap">
          <div class="col-md-12 ">
            <table style="width: 100%">
              <tbody>
                <tr>
                  <td rowspan="4"style="width:15%;vertical-align: top;" class="pe-3 fw-medium"><strong>Customer</strong></td>
                  <td class="pe-3 fw-medium capital-letter" style="width:45%">{{$data->owner_name}}</td>
                  <td style="width:20%"><strong>MILEAGE</strong></td>
                  <td style="width:20%">{{$data->mileage}} KMS</td>
                </tr>
                <tr>
                  <td  rowspan="2" class="pe-3 fw-medium">{{$data->address}}</td>
                  <td><strong>PLATE NUMBER</strong></td>
                  <td>{{$data->plate_number}}</td>
                  <td></td>
                </tr>
                <tr>
                 <td><strong>VEHICLE MODEL</strong></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td class="pe-3 fw-medium">{{$data->mobile_number}}</td>
                  <td colspan="2" class="capital-letter">{{$data->manufacturer}} {{$data->vehicle_model}} {{$data->transmission}} {{$data->fuel_type}} {{$data->year}}</td>
                  <td></td>
                </tr>
                
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <hr class="my-0" />
        <div class="card-body pb-0 tbl-header">
            <h6 class=""><strong>PACKAGE</strong></h6>
      </div>
      <div class="card-body">
        <div class="source-item pt-1">
          <div class="mb-3" data-repeater-list="group-a">
            <div class="repeater-wrapper pt-0 pt-md-2">
              <div class=" rounded position-relative pe-0 color-white" id="package-item-section">
                @if($optionOneHtml == false)
                  @for ($p = 1; $p < 2; $p++)
                  
                  @endfor
                @else 
                  @foreach($optionOneHtml as $k => $d)
                    {!!$d!!}
                  @endforeach
                @endif
                
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <!-- <button type="button" class="btn btn-primary btn-sm" onclick="addItem('package')" data-repeater-create><i class="mdi mdi-plus me-1"></i> Add Item</button> -->
            </div>
          </div>
        </div>
      </div>



      <hr class="my-0" />
      <div class="card-body pb-0 tbl-header">
        <div class="mb-0 pb-0">
          <h6><strong>LABOR</strong></h6>

          <i class="mdi mdi-content-duplicate me-1 js-textareacopybtn" id="part-duplicate" onclick="duplicateParts({{$job_order_id}})"></i>

        </div>
      </div>
      <div class="card-body">
        <div class="source-item pt-1">
          <div class="mb-3" data-repeater-list="group-b">
            <div class="repeater-wrapper pt-0 pt-md-4">
              <div class="rounded position-relative pe-0 color-white">
             


              <div class="row invoice-table-labor">
                  <div class="col-md-1 mumber col-12 mb-md-0color-black" style="width: 4.333333%;">
                    <h6 class="mb-2 repeater-title fw-medium"><strong>No</strong></h6>
                  </div>

                  <div class="col-md-4 col-12 mb-md-0 mb-3 " id="refresh-div-'">
                    <h6 class="mb-2 ml-2 repeater-title fw-medium text-center" style=""><strong>Service</strong></h6>
                  </div>

             
                   <div class="col-md-3 col-12 mb-md-0 mb-3">
                    <h6 class="mb-2 repeater-title fw-medium"><strong>Cost</strong></h6>
                  </div>            
                  <div class="col-md-1 col-12 mb-md-0 pl-0 text-left">
                      <h6 class="mb-2 repeater-title fw-medium"><strong>Qty</strong></h6>
                  </div>
                  <div class="col-md-1 col-12 mb-md-0 p-0 text-left">
                      <h6 class="mb-2 repeater-title fw-medium"><strong>Price</strong></h6>
                  </div>
                  <div class="col-md-2 col-12 mb-md-0 pl-0 text-left">
                    <h6 class="mb-2 repeater-title fw-medium"><strong>Amount</strong></h6>
                  </div>
                
                </div>


                 @foreach($optionTwoHtml as $k => $l)
                    {!!$l!!}
                  @endforeach
        
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button type="button" class="btn btn-primary btn-sm"  onclick="addItem('labor')"><i class="mdi mdi-plus me-1"></i> Add Item</button>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body pt-0 float-right">
        <div class="row">
          <div class="col-md-10">
          </div>
          <div class="col-md-2 d-flex justify-content-md-end mt-2 bg-white">
            <div class="invoice-calculations">
              <div class=" justify-content-between mb-2">
                <span class="w-px-150">Total:</span>
                <h6 class="mb-0  d-flex" id="">
                    <span style="padding-top: 3px;">₱ </span><input type="text" class="form-control invoice-item-amount mb-3 p-0 border-0 pe-none" style="font-weight: 800" name="labor-total" id="labor-total" value="0" placeholder="" min="12"/>
                </h6>
              </div>
           
            </div>
          </div>
        </div>
      </div>







    <hr class="my-0" />
    <div class="invoice-min-width">
      <div class="card-body pb-0 tbl-header h-55 invoice-min-width" >
        <div class="mb-0 pb-0">
          <h6><strong>PARTS & MATERIALS</strong></h6>
        </div>
      </div>
      <div class="card-body invoice-min-width bg-white">
        <div class="source-item pt-1">
          <div class="mb-3" data-repeater-list="group-c">
            <div class="d-block repeater-wrapper pt-0 pt-md-4">
              <div class="rounded position-relative pe-0 color-white">
           
                <div class="row invoice-table">
                  <div class="col-md-1 mumber col-12 mb-md-0 mb-3 color-black" style="width: 3.333333%;">
                    <h6 class="mb-2 repeater-title fw-medium"><strong>No</strong></h6>
                  </div>

                  <div class="col-md-2 col-12 mb-md-0 mb-3 " id="refresh-div-'">
                    <h6 class="mb-2 ml-2 repeater-title fw-medium text-center" style=""><strong>Item</strong></h6>
                  </div>

                  <div class="col-md-1 col-12 mb-md-0 mb-3"  style="width: 7.333333%;">
                    <h6 class="mb-2 repeater-title fw-medium"></h6>
                  </div> 
            
                   <div class="col-md-1 col-12 mb-md-0 mb-3">
                    <h6 class="mb-2 repeater-title fw-medium"><strong>Part Number</strong></h6>
                  </div>            
                  <div class="col-md-1 col-12 mb-md-0 mb-3">
                      <h6 class="mb-2 repeater-title fw-medium"><strong>Supplier</strong></h6>
                  </div>
                  <div class="col-md-1 col-12 mb-md-0 mb-3">
                      <h6 class="mb-2 repeater-title fw-medium"><strong>Supplier Inv</strong></h6>
                  </div>
                   <div class="col-md-1 col-12 mb-md-0 mb-3">
                    <h6 class="mb-2 repeater-title fw-medium"><strong>Unit Cost</strong></h6>
                  </div>
                      <div class="col-md-1 col-12 pe-0">
                      <h6 class="mb-2 repeater-title fw-medium"><strong>Total Cost</strong></h6>
                 
                  </div>

                  <div class="col-md-1 col-12 mb-md-0 mb-3 w-6">
                    <h6 class="mb-2 repeater-title fw-medium"><strong>Qty</strong></h6>
                  </div>

                  <div class="col-md-1 col-12 mb-md-0 mb-3">
                    <h6 class="mb-2 repeater-title fw-medium"><strong>Price</strong></h6>
                  </div>
                  <div class="col-md-1 number col-12 border-start">
                    <h6 class="mb-2 repeater-title fw-medium"><strong>Amount</strong></h6>
                  </div>
                </div>
             

                 @foreach($optionThreeHtml as $k => $l)
                    {!!$l!!}
                  @endforeach
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button type="button" class="btn btn-primary btn-sm"  onclick="addItem('part', {{$countEnabledPart}})" ><i class="mdi mdi-plus me-1"></i> Add Item</button>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body pt-0 bg-white float-right">
        <div class="row">
          <div class="col-md-8">
          </div>
          <div class="col-md-2 d-flex justify-content-md-end mt-2">
            <div class="invoice-calculations">
              <div class="d-flex justify-content-between mb-2">
                <span class="w-px-150">Total:</span>

                <h6 class="mb-0  d-flex" id="">
                    <span style="padding-top: 3px;">₱ </span><input type="text" class="form-control invoice-item-amount p-0 border-0 pe-none"  style="font-weight: 800" name="part-total" id="part-total" value="0" placeholder="" min="12"/>
                </h6>

              </div>
           
            </div>
          </div>
           <div class="col-md-2">
          </div>
        </div>
      </div>
      </div>





      
      <hr class="my-0" />
      <div class="card-body pt-0">
        <div class="row">
          <div class="col-md-8 d-flex justify-content-md-start mt-2">
            <div class="invoice-calculations">
              <div class=" justify-content-between mb-2 mt-3">
                <h6 class="mb-0 pt-1">REMARKS</h6><br>
                  <textarea class="form-control" name="remarks" id="remarks" style="height: 120px;"  name="history"cols="80"label="notes"rows="4"wrap="virtual">
                  {{$data->remarks}}
                  </textarea>
                <br>
                  <span class="">
                  <b>
                    This is merely an estimate. Cost of parts quoted may change depending on the availability of the above quoted parts. NO WARRANTY on service where PARTS/FLUIDS are provided by customer. NO WARRANTY on change oil service where OIL SLUDGE is detected upon inspection. Presence of oil sludge may cause engine trouble. ENGINE FLUSH does not guarantee the complete removal of oil sludge. Proper period of changing your oil is still the best way in preventing the build up of oil sludge.			
                    <br>
                    <br>
                  PLEASE READ: Under MAP Uniform Inspection Guidelines, we are required to document all our findings on your vehicle. This is your estimate. Our Store Manager, Technicians and/or admin should bring you to your car, show you the needed repairs and go over the estimate with you, item by item. All your questions should be answered. We want you to know all your options. This is your car. We want to help you keep it in good running condition

                
                  </b>  
                </span>
              </div>
            </div>
          </div>
            <div class="col-md-4 d-flex justify-content-md-center mt-2">
             <div class="invoice-calculations">
              <div class="d-flex justify-content-between mt-4">
                <span class="w-px-250 pt-1 text-right"><b>TOTAL SALES (VAT Inclusive)</b></span>
                <h6 class="mb-0 pt-1">
                    <p class="mb-0 color-black d-flex width-95px">
                      <span class="pt-1">₱</span>
                    <input type="text" class="form-control invoice-item-amount mb-3 p-0 border-0 pe-none text-right font-bold" name="sub_total" id="sub-total" value="0" placeholder="" min="12">
                    </p>
                </h6>
              </div>
              <div class="d-flex justify-content-between mb-2">
                <span class="w-px-250 text-right"><b>VAT</b>(12%)</span>
                <h6 class="mb-0">
                  <p class="mb-0 color-black d-flex width-95px">
                      <span class="pt-1">₱</span>
                    <input type="text" class="form-control invoice-item-amount mb-3 p-0 border-0 pe-none text-right font-bold" name="vat" id="vat" value="0" placeholder="" min="12">
                    </p>
                </h6>
              </div>

              <div class="d-flex justify-content-between mb-2">
                <span class="w-px-250 text-right"><b>AMOUNT: Net of VAT</b></span>
                <h6 class="mb-0">
                  <p class="mb-0 color-black d-flex width-95px">
                      <span class="pt-1">₱</span>
                    <input type="text" class="form-control invoice-item-amount mb-3 p-0 border-0 pe-none text-right font-bold" name="amount-net-vat" id="amount-net-vat" value="0" placeholder="" min="12">
                    </p>
                </h6>
              </div>

                <div class="d-flex justify-content-between mb-2">
                <span class="w-px-250 text-right pt-2"><b>DISCOUNT</b></span>
                <h6 class="mb-0">
                  <p class="mb-0 color-black d-flex width-95px">
                      <span class="pt-2">₱</span>
                    <input type="text" class="form-control invoice-discount mb-1 text-right" name="discount" id="discount" value="{{$data->discount}}" placeholder="" onkeyup="calculateAll()" min="12">
                    </p>
                </h6>
              </div>


              <div class="d-flex justify-content-between mb-2">
                <span class="w-px-250 text-right"><b>TOTAL </b></span>
                <h6 class="mb-0">
                    <p class="mb-0 color-black d-flex width-95px">
                      <span class="pt-1">₱</span>
                    <input type="text" class="form-control invoice-item-amount mb-3 ml-1 p-0 border-0 pe-none text-right font-bold" name="total_amount" id="total-amount" placeholder="" min="12">
                    </p>

                </h6>
              </div>
              <div class="d-flex justify-content-between mb-2">
                <span class="w-px-250 text-right pt-3"><b><span id="display-payment"  style="text-transform: uppercase;">{{$data->payment_label}}</span><i class="mdi mdi-pencil me-1 js-textareacopybtn" id="note-icon" onclick="editPaymentLabel()"></i></b></span>
                <span class="alert-coppied" id="icon-{{$k}}">Coppied!</span>
                  <i class="mdi mdi-arrow-down-right me-1 js-textareacopybtn" id="note-icon" onclick="copyPayment()"></i><span class="alert-coppied" id="icon-{{$k}}">Coppied!</span>


                <h6 class="mb-0 pt-1 width-95px">
                  <input type="text" class="form-control invoice-payment mb-3 text-right" name="payment" id="payment" value="{{$data->payment}}" placeholder="" onchange="calculateAll()" min="12">
                </h6>

              </div>
              <div class="d-flex justify-content-between mb-2">
              <i class="mdi mdi-plus me-1 mt-3 add-payment" onclick="addPayment()"></i>
 
                <span class="w-px-250 text-right pt-3"><b>MODE OF PAYMENT</b></span>
                <h6 class="mb-0 pt-1 width-95px">
                   <select class="form-select mb-3" name="mop" id="mop" onchange="filterOption()">
                          <option value="" selected></option>
                        @foreach($modeOfPayment as $k => $mop)
                          <option value="{{$mop}}" class="bg-label-warning" {{$data->mode_of_payment == $mop ? 'selected' : ''}}>{{$mop}}</option>
                        @endforeach
                    </select>

                </h6>
                
              </div>


              <div class="second-payment d-none">
                <hr class="my-0 mb-2" />
                <div class="d-flex justify-content-between mb-2">
                  <span class="w-px-250 text-right pt-3"><b>PAYMENT</b></span>
                  <!-- <i class="mdi mdi-arrow-down-right me-1 js-textareacopybtn" id="note-icon" onclick="copyPayment()"></i><span class="alert-coppied" id="icon-{{$k}}">Coppied!</span> -->

                  <h6 class="mb-0 pt-1 width-95px">
                    <input type="text" class="form-control invoice-payment2 mb-3 text-right" name="payment2" id="payment2" value="{{$data->payment2}}" placeholder="" onchange="calculateAll()" min="12">
                  </h6>

                </div>
                <div class="d-flex justify-content-between mb-2">
                  <span class="w-px-250 text-right pt-3"><b>MODE OF PAYMENT</b></span>
                  <h6 class="mb-0 pt-1 width-95px">
                    <select class="form-select item-details mb-3" name="mop2" id="mop2">
                        <option value="" selected></option>
                          @foreach($modeOfPayment as $k => $mop)
                            <option value="{{$mop}}" id="option-{{$mop}}" class="bg-label-warning" {{$data->mode_of_payment2 == $mop ? 'selected' : ''}}>{{$mop}}</option>
                          @endforeach
                      </select>

                  </h6>
                </div>
              </div>


              <div class="d-flex justify-content-between">
                <span class="w-px-250 text-right pt-1"><b>BALANCE</b></span>
                <h6 class="mb-0 pt-1">
                  <p class="mb-0 color-black d-flex width-95px">
                    <span class="pt-1">₱</span>
                  <input type="text" class="form-control invoice-item-amount mb-3 p-0 border-0 pe-none text-right font-bold" name="balance" id="balance" value="0" placeholder="" min="12">
                  </p>
                </h6>
              </div>
            </div>
          </div>
        </div>
      </div>



      <div class="card-body pt-0">
        <div class="row">
          <div class="col-md-8 d-flex justify-content-md-start">
            <div class="invoice-calculations">
              <div class=" justify-content-between mb-2">
                <span class="">
                  <b>
                  </b>  
                </span>
              </div>
            </div>
          </div>
           <!--  -->
        </div>
      </div>
      



      <hr class="my-0" />
      <div class="card-body">
        <div class="row">
          <div class="col-md-6 mb-md-0 mb-3">
            <div class="form-floating form-floating-outline mb-4">
              <input type="text" class="form-control" id="salesperson" placeholder="Edward Crowley" value="FERDINAND ENCARNACION" />
              <label for="salesperson" class="fw-medium">STORE MANAGER</label>
            </div>
           
          </div>
          <div class="col-md-6 d-flex justify-content-md-end mt-2">
            <div class="form-floating form-floating-outline mb-4">
                  
              <input type="text" class="form-control" id="customername" name="customer_name" placeholder="" value="{{$data->customer_name}}" />
              <label for="invoiceMsg">Customer Name and Signature</label>
            </div>
          </div>
        </div>
      </div>
      <hr class="my-0" />
      <!-- <div class="card-body">
        <div class="row">
          <div class="col-12">
            <div class="mb-3">
              <label for="note" class="form-label fw-medium text-heading">Note:</label>
              <textarea class="form-control" rows="2" id="note">It was a pleasure working with you and your team. We hope you will keep us in mind for future freelance projects. Thank You!</textarea>
            </div>
          </div>
        </div>
      </div> -->
    </div>
  </div>
  <!-- /Invoice Edit-->

  <!-- Invoice Actions -->
  <div class="col-md-12 col-lg-3 col-12 invoice-actions fixed-section" id="myFixedDiv" >
    <div class="card mb-4">
      <div class="card-body">
        <!-- <button class="btn btn-primary d-grid w-100 mb-3" data-bs-toggle="offcanvas" data-bs-target="#sendInvoiceOffcanvas">
          <span class="d-flex align-items-center justify-content-center text-nowrap"><i class="mdi mdi-send-outline scaleX-n1-rtl me-2"></i>Send Invoice</span>
        </button> -->



        <a href="/app/car/view/{{$data->car_id}}" class="btn btn-primary d-grid w-100 mb-3">
            <span class="d-flex align-items-center justify-content-center text-nowrap"><i class="mdi mdi-step-backward scaleX-n1-rtl me-2"></i>Back to &nbsp;<i class="mdi mdi-train-car-flatbed-car scaleX-n1-rtl me-2 pl-2"></i></span>
        </a>
        <a href="#" id="btn-preview" class="btn btn-outline-secondary w-100 me-2 mb-3">Preview</a>
        <button type="button" class="btn btn-success w-100 mb-3 bt-save-changes disabled" onclick="saveInvoice({{$job_order_id}})">Save</button>
        <!-- <button class="btn btn-success d-grid w-100 mb-3" data-bs-toggle="offcanvas" data-bs-target="#addPaymentOffcanvas">
          <span class="d-flex align-items-center justify-content-center text-nowrap"><i class="mdi mdi-currency-usd me-1"></i>Add Payment</span>
        </button> -->
      </div>
    </div>
     <div class="card mb-4">
        <div class="card-body">

      <div class="accordion mt-3 accordion-header-primary" id="accordionStyle1">
      <div class="accordion-item active">
        <h2 class="accordion-header">
          <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionStyle1-1" aria-expanded="false">
           Special Notes 
          </button>
        </h2>
        <div id="accordionStyle1-1" class="accordion-collapse collapse show" data-bs-parent="#accordionStyle1" style="">
          <div class="accordion-body">

             @foreach($specialNotes  as $k => $note)
              <p class="special-notes" id="note-{{$k}}">
                <i class="mdi mdi-content-copy me-1 js-textareacopybtn" id="note-icon" onclick="showNoteAlert({{$k}})"></i><span class="alert-coppied" id="icon-{{$k}}">Coppied!</span>
                <input type="text" class="js-copytextarea" id="js-copytextarea-{{$k}}" value="{{$note->value}}">
              </p>
            @endforeach
          
          </div>
        </div>
      </div>

     

    
    </div>

       
        </div>
    </div>
  </div>
  <!-- /Invoice Actions -->
</div>
</form>


<div class="loader d-none">
<div class="sk-wave sk-primary">
    <div class="sk-wave-rect"></div>
    <div class="sk-wave-rect"></div>
    <div class="sk-wave-rect"></div>
    <div class="sk-wave-rect"></div>
    <div class="sk-wave-rect"></div>
</div>
</div>

<div class="autosave">
    <p>Autosave</p>
</div>
@endforeach
<!-- Offcanvas -->
@include('_partials/_offcanvas/offcanvas-send-invoice')
@include('_partials/_offcanvas/offcanvas-add-payment')
@include('_partials/_modals/modal-confirm-update-status')

<!-- /Offcanvas -->
@endsection

<script>
   function formatNumberWithCommas(inputElement) {
        let rawValue = inputElement.value;

        // Remove any existing commas for accurate number conversion
        let numericValue = parseFloat(rawValue.replace(/,/g, ''));

        if (!isNaN(numericValue)) {
            // Format the number with commas using toLocaleString()
            // You can specify a locale for specific comma/decimal behavior, e.g., 'en-US'
            let formattedValue = numericValue.toLocaleString('en-US'); 
            inputElement.value = formattedValue;
        } else {
            // Handle cases where the input is not a valid number
            inputElement.value = ''; // Clear or set a default value
        }
    }
  </script>
