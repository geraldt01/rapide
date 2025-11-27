@extends('layouts/layoutMaster')

@section('title', 'Daily Sales Report')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />

<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>

<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('assets/vendor/libs/pickr/pickr.js')}}"></script>


@endsection

@section('page-script')
<script src="{{asset('assets/js/app-daily-sales-report.js')}}"></script>
<script src="{{asset('assets/js/forms-pickers.js')}}"></script>

@endsection

@section('content')
<div class="daily-sales-section d-none">
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light"> Daily Sales Report
</h4>

<!-- Product List Widget -->

<div class="col-md-6 col-12 mb-4">
            <div class="form-floating form-floating-outline">
              <input type="text" class="form-control flatpickr-input active" placeholder="YYYY-MM-DD" value="{{date('F j, Y')}}" id="flatpickr-date" onchange="changeDate()" readonly="readonly">
              <label for="flatpickr-date">Date</label>
            </div>
          </div>
          
<div class="card mb-4">
  <div class="card-widget-separator-wrapper">
    <div class="card-body card-widget-separator">
      <div class="row gy-4 gy-sm-1">
        <div class="col-sm-6 col-lg-3">
          <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
            <div>
              <p class="mb-2">Total Sales Today</p>
              <h4 class="mb-2"><p id="total-sales"></p></h4>
              <!-- <p class="mb-0"><span class="me-2">5k higher thank yesterday</span><span class="badge rounded-pill bg-label-success">+5.7%</span></p> -->
            </div>
            <div class="avatar me-sm-4">
              <span class="avatar-initial rounded bg-label-secondary">
                <i class="mdi mdi-cash mdi-24px"></i>
              </span>
            </div>
          </div>
          <hr class="d-none d-sm-block d-lg-none me-4">
        </div>
        <div class="col-sm-6 col-lg-3">
          <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-3 pb-sm-0">
            <div>
              <p class="mb-2">Total Cars Today</p>
              <h4 class="mb-2" id="total-cars"></h4>

              <!-- <p class="mb-0"><span class="me-2">21k orders</span><span class="badge rounded-pill bg-label-success">+12.4%</span></p> -->
            </div>
            <div class="avatar me-lg-4">
              <span class="avatar-initial rounded bg-label-secondary">
                <i class="mdi mdi-car mdi-24px"></i>
              </span>
            </div>
          </div>
          <hr class="d-none d-sm-block d-lg-none me-4">
        </div>
        <div class="col-sm-6 col-lg-3">
          <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-3 pb-sm-0">
            <div>
              <p class="mb-2">Month Sales</p>
              <h4 class="mb-2" id="month-sales">₱ 0.00</h4>
            </div>
            <div class="avatar me-sm-4">
              <span class="avatar-initial rounded bg-label-secondary">
                <i class="mdi mdi-cash-fast mdi-24px"></i>
              </span>
            </div>
          </div>
        </div>
          <div class="col-sm-6 col-lg-3">
          <div class="d-flex justify-content-between align-items-start pb-3 pb-sm-0 card-widget-3">
            <div>
              <p class="mb-2">Month Cars</p>
              <h4 class="mb-2" id="total-month-cars">₱ 0.00</h4>
            </div>
            <div class="avatar me-sm-4">
              <span class="avatar-initial rounded bg-label-secondary">
                <i class="mdi mdi-train-car-flatbed-car mdi-24px"></i>
              </span>
            </div>
          </div>
        </div>
        <!-- <div class="col-sm-6 col-lg-3">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <p class="mb-2">Affiliate</p>
              <h4 class="mb-2">₱8,345.23</h4>
              <p class="mb-0"><span class="me-2">150 orders</span><span class="badge rounded-pill bg-label-danger">-3.5%</span></p>
            </div>
            <div class="avatar">
              <span class="avatar-initial rounded bg-label-secondary">
                <i class="mdi mdi-currency-usd mdi-24px"></i>
              </span>
            </div>
          </div>
        </div> -->
      </div>
    </div>
  </div>
</div>

<!-- Product List Table -->
 
<!-- <div class="card mb-4">
  <div class="card-header bg-yellow tbl-daily-sales">
    <h5 class="card-title"></h5>
  </div>
  <div class="card-datatable table-responsive">
    <table class="datatables-products table">
      <thead class="table-light">
        <tr>
          <th></th>
          <th></th>
          <th>plate number</th>
          <th></th>
          <th></th>
          <th>Amount</th>
          <th>actions</th>
        </tr>
      </thead>
    </table>
  </div>
</div> -->

  <div class="row">

<div class="col-md-3" style="padding: 0px;">

<!-- Bootstrap Table with Caption -->
<div class="card">
  <div class="row">
    <div class="col-md-8">
      <h5 class="card-header">DAILY CASH BALANCE</h5>
    </div>
    <div class="col-md-4">
      <a href="javascript:;" class="btn btn-primary me-3 mt-2 btn-date" style="float: right" data-bs-target="#createCashBalance" data-bs-toggle="modal" onclick="createCashBalance()">Create</a>
      <a href="javascript:;" class="btn btn-primary me-3 mt-2 btn-date-edit d-none" style="float: right" data-bs-target="#createCashBalance" data-bs-toggle="modal" onclick="updateCashBalance()">Edit</a>
    </div>
  </div>
  
  <div class="table-responsive tbl-bal text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>Cash</th>
          <th></th>
          <th>Amount</th>
        
        </tr>
      
      </thead>
      <tbody  id="tblcashbalancehtml">
        
      </tbody>
    </table>

  
    
    <hr class="my-0" />
      <div class="card-body pt-0">
        <div class="row">
          <div class="col-md-8 d-flex justify-content-md-start mt-2">
            <div class="invoice-calculations">
              <div class=" justify-content-between mb-2 mt-3">
                <div class="d-flex">
                  <h6 class="mb-0 pt-1">Prepared By:</h6>
                  <i class="mdi mdi-arrow-down-right me-1 ml-4 js-textareacopybtn" id="note-icon" onclick="copyPreparedBy()"></i>
                </div>
                <br>
                <div class="form-floating form-floating-outline">
                  <input type="text" class="form-control flatpickr-input active"  name="input-prepared-by" id="input-prepared-by" value="">
                  <label for="flatpickr-date"></label>
                     <a class="btn btn-primary me-3 mt-2 btn-prepared-by "  onclick="saveRemarks()">Save</a>
                </div>
              </div>
            </div>
          </div>
            <div class="col-md-4 d-flex justify-content-md-end mt-2">
             <div class="invoice-calculations">
            </div>
          </div>
        </div>
      </div>

  </div>
</div>
</div>
<!-- Bootstrap Table with Caption -->

<div class="col-md-9">
  <div class="card">
  <div class="card-header bg-yellow tbl-daily-sales">
    <h5 class="card-title"></h5>
    <!-- <div class="d-flex justify-content-between align-items-center row py-3 gap-3 gap-md-0">
      <div class="col-md-4 product_status"></div>
      <div class="col-md-4 product_category"></div>
      <div class="col-md-4 product_stock"></div>
    </div> -->
  </div>
  <div class="card-datatable table-responsive">
    <table class="datatables-products table tbl-ds">
      <thead class="table-light">
        <tr>
          <th><b>Invoice Number</b></th>
          <th><b>plate number</b></th>
          <th><b>Cash</b></th>
          <th><b>Gcash</b></th>
          <th><b>Mobile</b></th>
          <th><b>Check/Others</b></th>
          <th><b>Total</b></th>
          <th><b>actions</b></th>
        </tr>
      </thead>

      <tfoot class="table-border-bottom-0">
        <tr style="background: #f4f4f6;">
          <th><b>Sales</b></th>
          <th></th>
          <th><span id="total-cash"></span></th>
          <th><span id="total-gcash"></span></th>
          <th><span id="total-mobile-check"></span></th>
          <th><span id="total-others"></span></th>
          <th><span id="grand-total"></span></th>
          <th></th>
        </tr>
      </tfoot>
    </table>
  </div>

   <hr class="my-0" />
      <div class="card-body pt-0">
        <div class="row">
          <div class="col-md-8 d-flex justify-content-md-start mt-2">
            <div class="invoice-calculations">
              <div class=" justify-content-between mb-2 mt-3">
               <div class="d-flex">
                  <h6 class="mb-0 pt-1">Checked By:</h6>
                  <i class="mdi mdi-arrow-down-right me-1 ml-4 js-textareacopybtn" id="note-icon" onclick="copyCheckedBy()"></i>
                </div>
                <br>
                <div class="form-floating form-floating-outline">
                  <input type="text" class="form-control flatpickr-input active"  name="input-checked-by" id="input-checked-by" value="">
                  <label for="flatpickr-date"></label>
                     <a class="btn btn-primary me-3 mt-2 btn-checked-by"  onclick="saveRemarks()">Save</a>
                </div>
                </>
              </div>
            </div>
          </div>
            <div class="col-md-4 d-flex justify-content-md-end mt-2">
             <div class="invoice-calculations">
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
                  <textarea class="form-control" name="remarks" id="remarks" style="height: 120px;"  name="history"cols="80"label="notes"rows="4">
                    
                  </textarea>
                  <a class="btn btn-primary me-3 mt-2"  onclick="saveRemarks()">Save</a>
              </div>
            </div>
          </div>
            <div class="col-md-4 d-flex justify-content-md-end mt-2">
             <div class="invoice-calculations">
            </div>
          </div>
        </div>
      </div>
</div>

</div>

</div>


<!-- Modal -->
@include('_partials/_modals/modal-daily-sales-report')
<!-- /Modal -->
@endsection
