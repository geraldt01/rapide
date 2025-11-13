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
  <div class="col-lg-9 col-12 mb-lg-0 mb-4">
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
            <div class="d-flex repeater-wrapper pt-0 pt-md-4">
              <div class="rounded position-relative pe-0 color-white">
                @if($optionTwoHtml == false)
                  @for ($i = 1; $i < 11; $i++)
                <div class="border row w-100 p-3 pr-0" style="padding-right: 0px !important;"  id="item-list-part-1"  data-repeater-item>
                 <div class="col-md-1 col-12 mb-md-0 mb-3 color-black">
                    <h6 class="mb-2 repeater-title fw-medium"><strong>No</strong></h6>
                    <span id="labor-counter-1">{{$i}}</span>
                  </div>
                  <div class="col-md-5 col-12 mb-md-0 mb-3">
                    <h6 class="mb-2 repeater-title fw-medium"><strong>Service</strong></h6>
                    <input type="text" class="form-control invoice-item-text " name="labor-text" id="labor-text-{{$i}}"  onchange="calculateLabor({{$i}})" />
                  </div>
                     <div class="col-md-2 col-12 mb-md-0 mb-3">
                    <h6 class="mb-2 repeater-title fw-medium">Qty</h6>
                    <input type="text" class="form-control invoice-item-qty labor" name="labor-qty" id="labor-qty-{{$i}}" value="1" placeholder="1" min="1" max="" onchange="calculateLabor({{$i}})" />
                  </div>
                  <div class="col-md-2 col-12 mb-md-0 mb-3">
                    <h6 class="mb-2 repeater-title fw-medium">Price</h6>
                    <input type="text" class="form-control invoice-item-price labor mb-3" name="labor-price" id="labor-price-{{$i}}" value="0" placeholder="0" min="" onchange="calculateLabor({{$i}})" />
                  </div>
                  <div class="col-md-1 col-12 pe-0">
                    <h6 class="mb-2 repeater-title fw-medium">Amount</h6>
                    <p class="mb-0 pt-2 color-black amount-labor-sub d-flex" id="amount-labor-sub-{{$i}}">₱
                    <input type="text" class="form-control invoice-item-amount mb-3 p-0 border-0 pe-none" name="labor-amount" id="labor-amount-{{$i}}" value="0" placeholder="" min="12"/>
                    </p>
                  </div>
                <div class="col-md-1 col-12 border-start text-right">
                  <i class="mdi mdi-close cursor-pointer color-black" onclick="deleteItem(1 , {{$i}})" data-repeater-delete></i>
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
                @endfor
                @else 
                 @foreach($optionTwoHtml as $k => $l)
                    {!!$l!!}
                  @endforeach
                @endif
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
      <div class="card-body pt-0">
        <div class="row">
          <div class="col-md-10">
          </div>
          <div class="col-md-2 d-flex justify-content-md-end mt-2 bg-white">
            <div class="invoice-calculations">
              <div class="d-flex justify-content-between mb-2">
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
          <div class="col-md-10">
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
        </div>
      </div>
      </div>









      
<!-- 

      <hr class="my-0" />
      <div class="card-body pb-0 tbl-header">
        <div class="mb-0 pb-0">
          <h6><strong>PARTS & MATERIALS</strong></h6>
        </div>
      </div>
      <div class="card-body">
        <div class="source-item pt-1">
          <div class="mb-3" data-repeater-list="group-c">
            <div class="repeater-wrapper pt-0 pt-md-4">
              <div class="border rounded position-relative pe-0">

                @if($optionThreeHtml == false)
                <div class="border row w-100 p-3"  data-repeater-item>
                      <div class="col-md-1 col-12 mb-md-0 mb-3">
                    <h6 class="mb-2 repeater-title fw-medium"><strong>No</strong></h6>
                    1
                  </div>
                  <div class="col-md-6 col-12 mb-md-0 mb-3">
                    <h6 class="mb-2 repeater-title fw-medium"><strong>Service</strong></h6>
                    <select class="form-select item-details mb-3" name="part" id="part-option-1" onchange="calculatePart(1)">';

                       @foreach($jobOrderPartServiceOption as $keyp => $prt)
                        <option value="{{$prt->id}}" selected>{{$prt->value}}</option>
                       @endforeach
                    </select>
                  </div>
                     <div class="col-md-2 col-12 mb-md-0 mb-3">
                    <h6 class="mb-2 repeater-title fw-medium">Qty</h6>
                    <input type="text" class="form-control invoice-item-qty" name="part-qty" id="part-qty-1" value="1" placeholder="1" min="1" max="50" />
                  </div>
                  <div class="col-md-2 col-12 mb-md-0 mb-3">
                    <h6 class="mb-2 repeater-title fw-medium">Price</h6>
                    <input type="text" class="form-control invoice-item-price mb-3" name="part-price" id="part-price-1" value="24" placeholder="24" min="12" />
                    
                  </div>
            
                <div class="col-md-1 col-12 pe-0">
                    <h6 class="mb-2 repeater-title fw-medium">Amount</h6>
                    <p class="mb-0 pt-2 color-black amount-part-sub d-flex" id="amount-part-sub-1">₱
                    <input type="text" class="form-control invoice-item-amount mb-3 p-0 border-0 pe-none" name="part-amount" id="part-amount-1" value="0" placeholder="" min="12"/>
                    </p>
                  </div>
                </div>
                <div class="d-flex flex-column align-items-center justify-content-between border-start p-2">
                  <i class="mdi mdi-close cursor-pointer color-black" data-repeater-delete></i>
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
                @else 
                 @foreach($optionThreeHtml as $k => $prtSrvc)
                    {!!$prtSrvc!!}
                  @endforeach
                @endif

                <div class="row w-100 p-3">
                      <div class="col-md-1 col-12 mb-md-0 mb-3">
                    <h6 class="mb-2 repeater-title fw-medium"><strong>No</strong></h6>
                    1
                  </div>
                  <div class="col-md-6 col-12 mb-md-0 mb-3">
                    <h6 class="mb-2 repeater-title fw-medium"><strong>Service</strong></h6>
                    <select class="form-select item-details mb-3" name="part">
                      @foreach($jobOrderPartServiceOption as $k => $s)
                        <option value="{{$s->id}}" selected>{{$s->value}}</option>
                       @endforeach
                    </select>
                  </div>
                     <div class="col-md-2 col-12 mb-md-0 mb-3">
                    <h6 class="mb-2 repeater-title fw-medium">Qty</h6>
                    <input type="text" class="form-control invoice-item-qty" name="part-qty"  value="1" placeholder="1" min="1" max="50" />
                  </div>
                  <div class="col-md-2 col-12 mb-md-0 mb-3">
                    <h6 class="mb-2 repeater-title fw-medium">Price</h6>
                    <input type="text" class="form-control invoice-item-price mb-3" name="part-price" value="24" placeholder="24" min="12" />
                    
                  </div>
               
                  <div class="col-md-1 col-12 pe-0">
                    <h6 class="mb-2 repeater-title fw-medium">Amount</h6>
                    <p class="mb-0 pt-2" id="amount-sub">$1222</p>
                  </div>
                </div>
                <div class="d-flex flex-column align-items-center justify-content-between border-start p-2">
                  <i class="mdi mdi-close cursor-pointer" data-repeater-delete></i>
                  <div class="dropdown">
                    <i class="mdi mdi-cog-outline cursor-pointer more-options-dropdown" role="button" id="dropdownMenuButton" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
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
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button type="button" class="btn btn-primary btn-sm"  onclick="addItem('part')" data-repeater-create><i class="mdi mdi-plus me-1"></i> Add Item</button>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body pt-0">
        <div class="row">
          <div class="col-md-6">
          </div>
          <div class="col-md-6 d-flex justify-content-md-end mt-2">
            <div class="invoice-calculations">
              <div class="d-flex justify-content-between mb-2">
                <span class="w-px-150">Total:</span>
                <h6 class="mb-0 pt-1">$5000.25</h6>
              </div>
            </div>
          </div>
        </div>
      </div> -->

      
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
                  PLEASE READ: Under MAP Uniform Inspection Guidelines, we are required to document all our findings on your vehicle. This is your estimate. Our Store Manager should bring you to your car, show you the needed repairs and go over the estimate with you, item by item. All your questions should be answered. We want you to know all your options. This is your car. We want to help you keep it in good running condition

                
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
  <div class="col-lg-3 col-12 invoice-actions fixed-section" id="myFixedDiv" >
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
