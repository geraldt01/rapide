@extends('layouts/layoutMaster')

@section('title', 'Monthly Sales Report')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}" />
<style>
  .monthly-sales-table td { font-size: 13px; }
</style>
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/app-monthly-sales-report.js')}}"></script>
@endsection

@section('content')
<div class="container-fluid">
  <h4 class="py-3 mb-4"><span class="text-muted fw-light">Monthly Sales Report</span></h4>

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
  </div>

  <div class="card mb-4">
    <div class="card-body">
      <div class="row gy-4">
        <div class="col-sm-4">
          <p class="mb-2">Completed Job Orders</p>
          <h4 class="mb-0" id="monthly-order-count">0</h4>
        </div>
        <div class="col-sm-4">
          <p class="mb-2">Total Sales</p>
          <h4 class="mb-0" id="monthly-order-total">₱0.00</h4>
        </div>
        <div class="col-sm-4">
          <p class="mb-2">Total Collected</p>
          <h4 class="mb-0" id="monthly-collected">₱0.00</h4>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header bg-yellow">
      <h5 class="card-title mb-0" id="monthly-report-title">Sales Details</h5>
    </div>
    <div class="card-datatable table-responsive">
      <table class="table monthly-sales-table">
        <thead class="table-light">
          <tr>
            <th>Date</th>
            <th>Invoice Number</th>
            <th>Customer Name</th>
            <th>Plate Number</th>
            <th>Cash</th>
            <th>Gcash</th>
            <th>Mobile</th>
            <th>Check/Others</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody></tbody>
        <tfoot class="table-light">
          <tr>
            <th colspan="4">Monthly Total</th>
            <th id="monthly-cash">₱0.00</th>
            <th id="monthly-gcash">₱0.00</th>
            <th id="monthly-mobile">₱0.00</th>
            <th id="monthly-others">₱0.00</th>
            <th id="monthly-footer-total">₱0.00</th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>
@endsection