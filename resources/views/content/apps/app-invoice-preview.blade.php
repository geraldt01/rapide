@extends('layouts/layoutMaster')

@section('title', 'Preview - Invoice')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
@endsection

@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/app-invoice.css')}}" />
@endsection
<style type="text/css">
  .input.disabled-preview {
  }
</style>
@section('page-script')
<script src="{{asset('assets/js/offcanvas-add-payment.js')}}"></script>
<script src="{{asset('assets/js/offcanvas-send-invoice.js')}}"></script>
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/moment/moment.js')}}"></script>
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave-phone.js')}}"></script>
<script src="{{asset('assets/js/app-invoice-preview.js')}}"></script>

@endsection

@section('content')
@foreach($jobOrderInfo as $k => $data)
<div class="row invoice-preview">
  <!-- Invoice Edit-->
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
              <div class="col-sm-6 mb-2 d-md-flex align-items-right justify-content-end text-right">
                @if($data->status == 1)
                  <h3>Estimate</h3>
                @else
                  <h3>Job Order</h3>
                @endif
              </div>
            </div>
            <div class="row mb-2 g-2">
              <div class="col-sm-6 mb-2 d-md-flex align-items-right justify-content-end pt-2">
                                <span class="fw-normal">EST</span>
              </div>
              <div class="col-sm-6">
                <div class="input-group input-group-merge disabled align-items-right">
                  <input type="text" class="form-control disabled-preview text-right mr-3"  placeholder="74909" value="#74909" id="invoiceId" />
                </div>
              </div>
              <div class="col-sm-6 mb-2 d-md-flex align-items-right justify-content-end pt-2">
                <span class="fw-normal">Date:</span>
              </div>
              <div class="col-sm-6 align-items-right">
                <input type="text" class="form-control invoice-date disabled-preview text-right" placeholder="DD-MM-YYY" />
              </div>
              <div class="col-sm-6 mb-2 d-md-flex align-items-right justify-content-end pt-2">
                <span class="fw-normal">Expires:</span>
              </div>
              <div class="col-sm-6 align-items-right">
                <input type="text" class="form-control due-date disabled-preview text-right" placeholder="YYYY-MM-DD" />
              </div>
            </div>
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
                  <td style="width:20%">{{$data->mileage}}</td>
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
                  <td>{{$data->manufacturer}} {{$data->vehicle_model}} {{$data->year}}</td>
                  <td></td>
                  <td></td>
                </tr>
                
              </tbody>
            </table>
          </div>
        </div>
      </div>
     <br>
      <br>
      <div class="table-responsive">
        <table class="table table-borderless m-0">
          <thead class="border-top">
            <tr>
              <th colspan="5"><h6 class="mb-0"><strong>PACKAGE, EXPRESS</strong></h6></th>
            </tr>
          </thead>
          <tbody>
          @foreach($jobOrderPackageSelected as $k => $package)
            <tr>
              <td class="text-nowrap text-heading" colspan="4">{{$package->package_value}}</td>
              <td  class="text-right"><b>₱ {{number_format($package->package_price, 2)}}</b></td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>

      <br>
      <br>
      <div class="table-responsive">
        <table class="table table-borderless m-0">
          <thead class="border-top">
            <tr>
              <th colspan="5"><h6 class="mb-0"><strong>LABOR</strong></h6></th>
            </tr>
            <tr>
              <th>No</th>
              <th>SERVICE</th>
              <th class="text-center">Qty</th>
              <th class="text-right">Price</th>
              <th class="text-right">Amount</th>
            </tr>
          </thead>
          <tbody>
          @foreach($jobOrderLaborSelected as $klabor => $labor)
            <tr>
              <td  style="width: 10%" class="text-nowrap text-heading">{{$klabor + 1}}</td>
              <td style="width: 55%">{{$labor->labor_value}}</td>
              <td class="text-center">{{(($labor->labor_value) ? $labor->labor_qty : '' )}}</td>
              <td class="text-right">{{(($labor->labor_value) ? number_format($labor->labor_price, 2) : '' )}}</td>
              <td class="text-right">{{(($labor->labor_value) ?  number_format($labor->labor_amount, 2) : '' )}}</td>
            </tr>
          @endforeach
           <tr>
              <td colspan="3"></td>
              <td colspan="2" class="text-right"><b>Total</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>₱ {{number_format($data->labor_total, 2)}}</b></td>
            </tr>
          </tbody>
        </table>
      </div>
      <br>
      <br>


     <div class="table-responsive">
        <table class="table table-borderless m-0">
          <thead class="border-top">
            <tr>
              <th colspan="5"><h6 class="mb-0"><strong>PARTS & SERVICES</strong></h6></th>
            </tr>
            <tr>
              <th style="width: 10%">No</th>
              <th style="width: 55%">Item</th>
              <th class="text-center"></th>
              <th class="text-center">Qty</th>
              <th  class="text-right">Price</th>
              <th  class="text-right">Amount</th>
            </tr>
          </thead>
          <tbody>
          @foreach($jobOrderPartSelected as $kpart => $part)
            <tr>
              <td class="text-nowrap text-heading text-left">{{$kpart + 1}}</td>
              <td class="text-left">{{$part->part_value}}</td>
              <td class="text-left">{{$part->part_number}}</td>
              <td class="text-center">{{(($part->part_value) ? $part->part_qty : '' )}}</td>
              <td class="text-right">{{(($part->part_value) ? number_format($part->part_price, 2) : '' ) }}</td>
              <td class="text-right">{{(($part->part_value) ? number_format($part->part_amount, 2)   : '' ) }}</td>
            </tr>
          @endforeach
            <tr>
              <td colspan="3"></td>
              <td colspan="3" class="text-right"><b>Total</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>₱ {{number_format($data->part_total, 2)}}</b></td>
            </tr>
          </tbody>
        </table>
      </div>


    


         <hr class="my-0" />
      <div class="card-body pt-0">
        <div class="row">
          <div class="col-md-8 d-flex justify-content-md-start mt-2">
            <div class="invoice-calculations">
              <div class=" justify-content-between mb-2 mt-3">
                <h6 class="mb-0 pt-1">REMARKS</h6>
                  <b>{!! nl2br($data->remarks)  !!}</b>
                <br>
                <br>
                <span class="">
                  <b>
                    This is merely an estimate. Cost of parts quoted may change depending on the availability of the above quoted parts. NO WARRANTY on service where PARTS/FLUIDS are provided by customer. NO WARRANTY on change oil service where OIL SLUDGE is detected upon inspection. Presence of oil sludge may cause engine trouble. ENGINE FLUSH does not guarantee the complete removal of oil sludge. Proper period of changing your oil is still the best way in preventing the build up of oil sludge.			
                  </b>  
                </span>
              </div>
            </div>
          </div>
            <div class="col-md-4 d-flex justify-content-md-end mt-2">
             <div class="invoice-calculations">
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
                  PLEASE READ: Under MAP Uniform Inspection Guidelines, we are required to document all our findings on your vehicle. This is your estimate. Our Store Manager should bring you to your car, show you the needed repairs and go over the estimate with you, item by item. All your questions should be answered. We want you to know all your options. This is your car. We want to help you keep it in good running condition
                  </b>  
                </span>
              </div>
            </div>
          </div>
            <div class="col-md-4 d-flex justify-content-md-end mt-2">
             <div class="invoice-calculations">
              <div class="d-flex justify-content-between mb-2">
                <span class="w-px-150"><b>SUBTOTAL</b></span>
                <h6 class="mb-0 pt-1">₱ {{number_format($data->sub_total, 2)}}</h6>
              </div>
              <div class="d-flex justify-content-between mb-2">
                <span class="w-px-150">VAT (12%)</span>
                <h6 class="mb-0 pt-1">{{number_format($data->vat, 2)}}</h6>
              </div>
              <div class="d-flex justify-content-between mb-2">
                <span class="w-px-150"><b>TOTAL AMOUNT</b></span>
                <h6 class="mb-0 pt-1">₱ {{number_format($data->total_amount, 2)}}</h6>
              </div>
               <div class="d-flex justify-content-between mb-2">
                <span class="w-px-150"><b>PAYMENT</b></span>
                <h6 class="mb-0 pt-1">₱ {{number_format($data->payment, 2)}}</h6>
              </div>
              <hr />
              <div class="d-flex justify-content-between">
                <span class="w-px-150"><b>BALANCE</b></span>
                <h6 class="mb-0 pt-1">₱ {{number_format($data->balance, 2)}}</h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      

      <hr class="my-0" />
      <div class="card-body">
        <div class="row">
          <div class="col-md-6 mb-md-0 mb-3">
            <div class="">
              <table>
                <tr>
                  <td>FERDINAND ENCARNACION</td>
                </tr>
                  <tr>
                  <td style="border-top: 1px solid black;"> <p for="salesperson" class="fw-medium text-center">STORE MANAGER</p></td>
                </tr>
              </table>
            </div>
          </div>
          <div class="col-md-6  justify-content-md-start ">
            <div class="form-floating form-floating-outline mb-4">
              <input type="text" class="form-control" id="invoiceMsg" placeholder="Gerald Tejero" value="Gerald Tejero" />
              <label for="invoiceMsg">Customer Signature</label>
            </div>
          </div>
        </div>
      </div>

      <hr class="my-0" />
    </div>
  </div>
  <!-- /Invoice -->

  <!-- Invoice Actions -->
  <div class="col-xl-3 col-md-4 col-12 invoice-actions">
    <div class="card">
      <div class="card-body">
        <button class="btn btn-primary d-grid w-100 mb-3" data-bs-toggle="offcanvas" data-bs-target="#sendInvoiceOffcanvas">
          <span class="d-flex align-items-center justify-content-center text-nowrap"><i class="mdi mdi-send-outline scaleX-n1-rtl me-1"></i>Send Invoice</span>
        </button>
        <button class="btn btn-outline-secondary d-grid w-100 mb-3">
          Download
        </button>
        <a class="btn btn-outline-secondary d-grid w-100 mb-3" target="_blank" href="{{url('app/job-order/print/')}}/{{$data->job_order_id}}">
          Print
        </a>
        <a class="btn btn-outline-secondary d-grid w-100 mb-3" onclick="history.back()">
          Edit Job Order
        </a>
        <!-- <button class="btn btn-success d-grid w-100" data-bs-toggle="offcanvas" data-bs-target="#addPaymentOffcanvas">
          <span class="d-flex align-items-center justify-content-center text-nowrap"><i class="mdi mdi-currency-usd me-1"></i>Add Payment</span>
        </button> -->
      </div>
    </div>
  </div>
  <!-- /Invoice Actions -->
</div>
@endforeach

<!-- Offcanvas -->
@include('_partials/_offcanvas/offcanvas-send-invoice')
@include('_partials/_offcanvas/offcanvas-add-payment')
<!-- /Offcanvas -->
@endsection
