@extends('layouts/layoutMaster')

@section('title', 'Monthly Cost of Sales')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}" />
<style>
  .monthly-cost-table th,
  .monthly-cost-table td { font-size: 12px; padding: 4px !important; }
</style>
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/app-monthly-cost-of-sales.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">Monthly Cost of Sales</span></h4>

<div class="row align-items-end mb-4">
  <div class="col-sm-5 col-md-4 col-lg-3">
    <div class="form-floating form-floating-outline">
      <input type="month" class="form-control" id="report-month" value="{{ $month }}">
      <label for="report-month">Month</label>
    </div>
  </div>
  <div class="col-auto mt-3 mt-sm-0">
    <button type="button" class="btn btn-primary" id="apply-month">View Report</button>
  </div>
  <div class="col-auto mt-3 mt-sm-0">
    <button type="button" class="btn btn-secondary" id="export-month"><i class="mdi mdi-export-variant me-1"></i>Export CSV</button>
  </div>
</div>

<div class="card mb-4">
  <div class="card-body">
    <div class="row gy-3">
      <div class="col-sm-4">
        <p class="mb-1">Report Items</p>
        <h5 class="mb-0" id="monthly-item-count">0</h5>
      </div>
      <div class="col-sm-4">
        <p class="mb-1">Total Cost</p>
        <h5 class="mb-0" id="monthly-total-cost">₱0.00</h5>
      </div>
      <div class="col-sm-4">
        <p class="mb-1">Total Sell Price</p>
        <h5 class="mb-0" id="monthly-total-sell">₱0.00</h5>
      </div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h5 class="card-title mb-0" id="monthly-report-title">Cost of Sales Details</h5>
  </div>
  <div class="card-datatable table-responsive">
    <table class="table monthly-cost-table" id="monthly-cost-of-sales-table">
      <thead class="table-light">
        <tr>
          <th>Date</th>
          <th>Customer Name</th>
          <th>Address</th>
          <th>Car Details</th>
          <th>INV#</th>
          <th>JO#</th>
          <th>QTY</th>
          <th>DETAILS</th>
          <th>PART NO.</th>
          <th>SUPPLIER</th>
          <th>SUPPLIER INV</th>
          <th>UNIT COST</th>
          <th>TOTAL COST</th>
          <th>UNIT SELLING PRICE WITH LABOR</th>
          <th>TOTAL SELL PRICE</th>
          <th></th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>
@endsection