@extends('layouts/layoutMaster')

@section('title', 'Invoice (Print version) - Pages')

@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/app-invoice-print.css')}}" />
@endsection

<style type="text/css">
 table td {
  font-size: 12px;
 }

.table > :not(caption) > * > * {
    padding: 6px 10px;
  padding: 2px 15px !important;
    height: 25px;
}

table.border-black {
    border: 1px solid rgba(76, 78, 100, 0.5) !important;

}

.invoice-print * {
    color: #828393 !important;
}

</style>
@section('page-script')
<script src="{{asset('assets/js/app-invoice-print.js')}}"></script>
@endsection

@section('content')
@foreach($jobOrderInfo as $k => $data)
<div class="invoice-print p-4">
  <div class="d-flex justify-content-between flex-row">
    <div class="">
      <img src="/assets/img/branding/rapide-invoice-logo.jpg" style="height: 120px;"/>
    </div>
    <div>
      <h4 class="text-right text-uppercase "> 
        @if($data->status == 1)
          Estimate
        @else
          Job Order
        @endif
      </h4>
      <div class="text-right">
        <span>EST:</span>
        <span>{{$data->job_order_number}}</span>
      </div>
      <div class="text-right">
        <span>Date:</span>
        <span>{{$data->date}}</span>
      </div>
      <div class="text-right">
        <span>EXPIRES:</span>
        <span>{{$data->date}}</span>
      </div>
    </div>
  </div>

  <div class="d-flex justify-content-between">
    <div class="my-2" style="width: 50%">
     <table style="width: 100%" class="tbl-no-border ">
        <tbody>
          <tr>
            <td rowspan="4"style="width:15%;vertical-align: top;" class="pe-3 fw-medium"><strong>Customer</strong></td>
            <td class="pe-3 fw-medium capital-letter" style="width:45%">{{$data->owner_name}}</td>
          
          </tr>
          <tr>
            <td  rowspan="2" class="pe-3 fw-medium">{{$data->address}}</td>
           
            <td></td>
          </tr>
          <tr>
        
            <td></td>
          </tr>
          <tr>
            <td class="pe-3 fw-medium">{{$data->mobile_number}}</td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          
        </tbody>
      </table>
    </div>
    <div class="my-2" style="width: 50%">
      <table>
        <tbody>
          <tr>
             <td style="width:20%"><strong>MILEAGE</strong></td>
            <td style="width:20%">{{$data->mileage}}</td>
          </tr>
          <tr>
        <td><strong>PLATE NUMBER</strong></td>
            <td>{{$data->plate_number}}</td>
          </tr>
          <tr>
               <td><strong>VEHICLE MODEL</strong></td>
            <td></td>
          </tr>
          <tr>
            <td>{{$data->manufacturer}} {{$data->vehicle_model}} {{$data->year}}</td>

            <td></td>
          </tr>
        
        </tbody>
      </table>
    </div>
  </div>


  <div class="table-responsive">
    <table class="table m-0 border-black">
        <tr>
          <th colspan="6"><strong>PACKAGE, EXPRESS</strong></th>
        </tr>
        @foreach($jobOrderPackageSelected as $k => $package)
        <tr>
            <td class="text-nowrap text-heading" colspan="4">{{$package->package_value}}</td>
            <td  colspan="2" class="text-right">${{number_format($package->package_price, 2)}}</td>
        </tr>
        <tr>
          <td colspan="6"></td>
        </tr>
        @endforeach
        <tr>
          <th colspan="6"><strong>LABOR</strong></th>
        </tr>
        <tr>
          <th style="width: 4%"><strong>NO</strong></th>
          <th colspan="2">SERVICE</th>
          <th>Qty</th>
          <th class="text-right">Price</th>
          <th  class="text-right">Amount</th>
        </tr>
      @foreach($jobOrderLaborSelected as $klabor => $labor)
        <tr>
          <td style="width: 10%" class="text-nowrap text-heading">{{$klabor + 1}}</td>
          <td colspan="2" style="width: 55%">{{$labor->labor_value}}</td>
          <td class="text-center">{{(($labor->labor_value) ? $labor->labor_qty : '')}}</td>
          <td class="text-right">{{(($labor->labor_value) ? number_format($labor->labor_price, 2) : '' )}}</td>
          <td class="text-right">{{(($labor->labor_value) ?  number_format($labor->labor_amount, 2) : '' )}}</td>
        </tr>
      @endforeach
        <tr>
            <td colspan="4"></td>
            <td colspan="2" class="text-right"><b>Total</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>₱ {{number_format($data->labor_total, 2)}}</b></td>
        </tr>
        <tr>
          <td colspan="6"></td>
        </tr>
         <tr>
          <th colspan="6"><strong>PARTS & SERVICES</strong></th>
        </tr>
        <tr>
          <th style="width: 4%"><strong>NO</strong></th>
          <th colspan="2">SERVICE</th>
          <th>Qty</th>
          <th class="text-right">Price</th>
          <th  class="text-right">Amount</th>
        </tr>
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
         <tr>
          <td colspan="6"></td>
        </tr>
         <tr>
          <td colspan="6"><strong>REMARKS</strong><br>
            <b>{!! nl2br($data->remarks)  !!}</b>
        </td>
        </tr>
    </table>
    <table class="table m-0 " style="border-top: none;border-left: 1px solid rgba(76, 78, 100, 0.5);border-right: 1px solid rgba(76, 78, 100, 0.5);">
        <tr  style="border-top: none;border-left: 1px solid rgba(76, 78, 100, 0.5);border-right: 1px solid rgba(76, 78, 100, 0.5);">
          <td colspan="3"  style="border-top: none;width: 65%;" class="align-top px-4 py-3">
            <p class="mb-2">
              <strong>
                   This is merely an estimate. Cost of parts quoted may change depending on the availability of the above quoted parts. NO WARRANTY on service where PARTS/FLUIDS are provided by customer. NO WARRANTY on change oil service where OIL SLUDGE is detected upon inspection. Presence of oil sludge may cause engine trouble. ENGINE FLUSH does not guarantee the complete removal of oil sludge. Proper period of changing your oil is still the best way in preventing the build up of oil sludge.			
                  <br>
                  <br>
                  PLEASE READ: Under MAP Uniform Inspection Guidelines, we are required to document all our findings on your vehicle. This is your estimate. Our Store Manager should bring you to your car, show you the needed repairs and go over the estimate with you, item by item. All your questions should be answered. We want you to know all your options. This is your car. We want to help you keep it in good running condition
              </strong>
            </p>
          
          </td>
          <td class="text-end px-4 py-3" style="width: 20%;vertical-align: top;">
            <p class="mb-2"><b>SUBTOTAL</b></p>
            <p class="mb-2"><b>VAT</b>(12%)</p>
            <p class="mb-2"><b>TOTAL AMOUNT</b></p>
            <p class="mb-2"><b>PAYMENT</b></p>
            <p class="mb-2"><b>BALANCE</b></p>
          </td>
          <td class="text-end px-4 py-3" style="width: 15%;vertical-align: top;">
            <p class="mb-2"><b>₱ {{number_format($data->sub_total, 2)}}</b></p>
            <p class=" mb-2">{{number_format($data->vat, 2)}}</p>
            <p class=" mb-2"><b>₱ {{number_format($data->total_amount, 2)}}</b></p>
            <p class="mb-2"><b>₱ {{number_format($data->payment, 2)}}</b></p>
            <p class="mb-2"><b>₱ {{number_format($data->balance, 2)}}</b></p>
          </td>
        </tr>
    </table>


        <table class="table" style="margin-top: 50px;">
        <tr>
           <td colspan="2" style="border-color: #ffffff !important;">
              <table class="text-center m-0-auto" style="display: table">
                <tr>
                  <td>FERDINAND ENCARNACION</td>
                </tr>
                  <tr>
                  <td style="border-top: 1px solid black;"> <p for="salesperson" class="fw-medium text-center">STORE MANAGER</p></td>
                </tr>
              </table>
          </td>
          <td colspan="2" style="border-color: #ffffff !important;">
              <table class="text-center m-0-auto" style="display: table">
                <tr>
                  <td>FERDINAND ENCARNACION</td>
                </tr>
                  <tr>
                  <td style="border-top: 1px solid black;"> <p for="salesperson" class="fw-medium text-center">STORE MANAGER</p></td>
                </tr>
              </table>
          </td>
        </tr>
    </table>
  </div>

 
</div>
@endsection
@endforeach
