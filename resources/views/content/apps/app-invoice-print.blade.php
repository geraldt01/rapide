@extends('layouts/layoutMaster')

@foreach($jobOrderInfo as $k => $data)
  @section('title', 'Print - '. (($data->status == 1) ? 'RE ' : 'JO '). $data->plate_number)
@endforeach


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
        @if($data->status == 1)
         <span>EST#:</span>
        @else
         <span>JO#:</span>
        @endif

        <span>{{$data->job_order_number}}</span>
      </div>
      <div class="text-right">
        <span>DATE:</span>
        <span>{{$invoice_date}}</span>
      </div>
      <div class="text-right">
        <span>EXPIRES:</span>
        <span>{{$expire_date}}</span>
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
            <td  rowspan="2" class="pe-3 fw-medium capital-letter">{{$data->address}}</td>
           
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
            <td style="width:20%">{{$data->mileage}} KMS</td>
          </tr>
          <tr>
        <td><strong>PLATE NUMBER</strong></td>
            <td class="capital-letter">{{$data->plate_number}}</td>
          </tr>
          <tr>
               <td><strong>VEHICLE MODEL</strong></td>
            <td></td>
          </tr>
          <tr>
            <td colspan="2" class="capital-letter">{{$data->manufacturer}} {{$data->vehicle_model}} {{$data->transmission}} {{$data->fuel_type}} {{$data->year}}</td>
          </tr>
        
        </tbody>
      </table>
    </div>
  </div>


  <div class="table-responsive">
    <table class="table m-0 border-black">
        <tr>
          <th colspan="7"><strong>PACKAGE, EXPRESS</strong></th>
        </tr>
        @foreach($jobOrderPackageSelected as $k => $package)
        <tr>
            <td class="text-nowrap text-heading" colspan="4">{{$package->package_value}}</td>
            <td  colspan="3" class="text-right">₱ {{number_format((float)$package->package_price, 2)}}</td>
        </tr>
        <tr>
            <td class="text-nowrap text-heading" colspan="4">{{$package->package_note2}}</td>
            <td  colspan="3" class="text-right"></td>
        </tr>
        <tr>
          <td colspan="7"></td>
        </tr>
        @endforeach
        <tr>
          <th colspan="7"><strong>LABOR</strong></th>
        </tr>
        <tr>
          <th style="width: 4%"><strong>NO</strong></th>
          <th colspan="3">SERVICE</th>
          <th class="text-right">Qty</th>
          <th class="text-right">Price</th>
          <th  class="text-right">Amount</th>
        </tr>
      @foreach($jobOrderLaborSelected as $klabor => $labor)
        <tr>
          <td style="width: 10%" class="text-nowrap text-heading">{{$klabor + 1}}</td>
          <td colspan="3" style="width: 55%">{{$labor->labor_value}}</td>
          <td class="text-right">{{(($labor->labor_value) ? $labor->labor_qty : '')}}</td>
          <td class="text-right">{{(($labor->labor_value) ? number_format((float)$labor->labor_price, 2) : '' )}}</td>
          <td class="text-right">{{(($labor->labor_value) ?  number_format((float)$labor->labor_amount, 2) : '' )}}</td>
        </tr>
      @endforeach
        <tr>
            <td colspan="4"></td>
            <td colspan="3" class="text-right"><b>Total</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>₱ {{$data->labor_total}}</b></td>
        </tr>
        <tr>
          <td colspan="7"></td>
        </tr>
         <tr>
          <th colspan="7"><strong>PARTS & SERVICES</strong></th>
        </tr>
        <tr>
          <th style="width: 4%"><strong>NO</strong></th>
          <th>SERVICE</th>
          <th></th>
          <th class="text-right">PART NO.</th>
          <th class="text-right">Qty</th>
          <th class="text-right">Price</th>
          <th  class="text-right">Amount</th>
        </tr>
         @foreach($jobOrderPartSelected as $kpart => $part)
            <tr>
               <td class="text-nowrap text-heading text-left">{{$kpart + 1}}</td>
              <td class="text-left">{{$part->part_value}}</td>
              <td class="text-left" style="width: 10%">{{$part->part_note}}</td>
              <td class="text-right" style="width: 10%">{{$part->part_number}}</td>
              <td class="text-right" style="width: 10%">{{(($part->part_value) ? $part->part_qty : '' )}}</td>
              <td class="text-right" style="width: 10%">{{(($part->part_value) ? number_format((float)$part->part_price, 2) : '' ) }}</td>
              <td class="text-right" style="width: 10%">{{(($part->part_value) ? number_format((float)$part->part_amount, 2)   : '' ) }}</td>
            </tr>
          @endforeach
        <tr>
          <td colspan="4"></td>
          <td colspan="3" class="text-right"><b>Total</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>₱ {{$data->part_total}}</b></td>
        </tr>
         <tr>
          <td colspan="7"></td>
        </tr>
         <tr>
          <td colspan="7"><strong>REMARKS</strong><br>
            <b>{!! nl2br($data->remarks)  !!}</b>
        </td>
        </tr>
    </table>
    <table class="table m-0 " style="border-top: none;border-left: 1px solid rgba(76, 78, 100, 0.5);border-right: 1px solid rgba(76, 78, 100, 0.5);">
        <tr  style="border-top: none;border-left: 1px solid rgba(76, 78, 100, 0.5);border-right: 1px solid rgba(76, 78, 100, 0.5);">
          <td colspan="3"  style="border-top: none;width: 62%;" class="align-top px-4 py-3">
            <p class="mb-2">
              <strong>
                   This is merely an estimate. Cost of parts quoted may change depending on the availability of the above quoted parts. NO WARRANTY on service where PARTS/FLUIDS are provided by customer. NO WARRANTY on change oil service where OIL SLUDGE is detected upon inspection. Presence of oil sludge may cause engine trouble. ENGINE FLUSH does not guarantee the complete removal of oil sludge. Proper period of changing your oil is still the best way in preventing the build up of oil sludge.			
                  <br>
                  <br>
                  PLEASE READ: Under MAP Uniform Inspection Guidelines, we are required to document all our findings on your vehicle. This is your estimate. Our Store Manager should bring you to your car, show you the needed repairs and go over the estimate with you, item by item. All your questions should be answered. We want you to know all your options. This is your car. We want to help you keep it in good running condition
              </strong>
            </p>
          
          </td>
          <td class="text-end px-4 py-3" style="width: 25%;vertical-align: top;">
            <p class="mb-2"><b>TOTAL SALES (VAT Inclusive)</b></p>
            <p class="mb-2"><b>VAT</b>(12%)</p>
            <p class="mb-2"><b>AMOUNT: Net of VAT</b></p>
            @if($data->discount > 0)
              <p class="mb-2"><b>DISCOUNT</b></p>
            @endif
            <p class="mb-2"><b>TOTAL AMOUNT</b></p>
            <p class="mb-2"><b style="text-transform: uppercase;">{{$data->payment_label}}</b>
              @if($data->payment2 > 0)
                 <span>({{$data->mode_of_payment}})</span>
              @endif
          </p>
             @if($data->payment2 > 0)
            <p class="mb-2"><b>PAYMENT</b>
            @if($data->payment2 > 0)
              <span>({{$data->mode_of_payment2}})</span>
             @endif
          </p>
            @endif
            <p class="mb-2"><b>BALANCE</b></p>
          </td>
          <td class="text-end px-4 py-3" style="width: 23%;vertical-align: top;">
            <p class="mb-2"><b>₱ {{$data->sub_total}}</b></p>
            <p class=" mb-2">{{$data->vat}}</p>
            <p class=" mb-2">{{$data->amount_net_vat}}</p>
            @if($data->discount > 0)
              <p class=" mb-2">{{$data->discount}}</p>
            @endif
            <p class=" mb-2"><b>₱ {{$data->total_amount}}</b></p>
            <p class="mb-2"><b>₱ {{$data->payment}}</b></p>

             @if($data->payment2 > 0)
            <p class="mb-2"><b>₱ {{$data->payment2}}</b></p>
            @endif
            <p class="mb-2"><b>₱ {{$data->balance}}</b></p>
          </td>
        </tr>
    </table>


        <table class="table" style="margin-top: 0px;">
        <tr>
           <td colspan="2" style="border-color: #ffffff !important;">
              <table class="text-center m-0-auto" style="display: table">
                <tr>
                  <td class="text-center">
                    <span><img src="/assets/img/esig/sir-bong-sig.png" /></span><br>
                    FERDINAND ENCARNACION</td>
                </tr>
                  <tr>
                  <td style="border-top: 1px solid black;"> <p for="salesperson" class="fw-medium text-center">STORE MANAGER</p></td>
                </tr>
              </table>
          </td>
          <td colspan="2" style="border-color: #ffffff !important;">
            <br>
            <br>
            <br>
            <br>
            <br>
              <table class="text-center m-0-auto mt-4" style="display: table">
                <tr>
                  <td>{{$data->customer_name}}</td>
                </tr>
                  <tr>
                  <td style="border-top: 1px solid black;"> <p for="salesperson" class="fw-medium text-center">CUSTOMER NAME AND SIGNATURE</p></td>
                </tr>
              </table>
          </td>
        </tr>
    </table>
  </div>

 
</div>
@endsection
@endforeach
