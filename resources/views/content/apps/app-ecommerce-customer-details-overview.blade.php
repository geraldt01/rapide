@extends('layouts/layoutMaster')

@section('title', 'eCommerce Customer Details Overview - Apps')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css')}}" />

<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/pickr/pickr-themes.css')}}" />

@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/moment/moment.js')}}"></script>
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave-phone.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js')}}"></script>

<script src="{{asset('assets/vendor/libs/moment/moment.js')}}"></script>
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js')}}"></script>
<script src="{{asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.js')}}"></script>
<script src="{{asset('assets/vendor/libs/pickr/pickr.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/modal-edit-user.js')}}"></script>
<script src="{{asset('assets/js/app-ecommerce-customer-detail.js')}}"></script>
<script src="{{asset('assets/js/app-ecommerce-customer-detail-overview.js')}}"></script>

<script src="{{asset('assets/js/forms-pickers.js')}}"></script>

@endsection

@section('content')
@foreach($selectedCar as $k => $customerInfo)
<input type="hidden" name="hidden-customer-id" id="hidden-customer-id" value="{{$customerInfo->owner_id}}" />
<input type="hidden" name="hidden-selected-car-plate-number" id="hidden-selected-car-plate-number" value="{{$customerInfo->plate_number}}" />
<input type="hidden" name="hidden-selected-car-id" id="hidden-selected-car-id" value="{{$customerInfo->car_id}}" />
<input type="hidden" name="hidden-selected-vehicle-model" id="hidden-selected-vehicle-model" value="{{$customerInfo->vehicle_model}}" />
<input type="hidden" name="hidden-selected-vehicle-manufacturer" id="hidden-selected-vehicle-manufacturer" value="{{$customerInfo->manufacturer}}" />
<input type="hidden" name="hidden-selected-vehicle-type" id="hidden-selected-vehicle-type" value="{{$customerInfo->vehicle_type}}" />
<input type="hidden" name="hidden-selected-mileage" id="hidden-selected-mileage" value="{{$customerInfo->mileage}}" />
<input type="hidden" name="hidden-selected-year" id="hidden-selected-year" value="{{$customerInfo->year}}" />


<h4 class="py-3 mb-4">
  <span class="text-muted fw-light"> Customer Details /</span> Overview
</h4>

<div class="d-flex flex-column flex-sm-row align-items-center justify-content-sm-between mb-4 text-center text-sm-start gap-2">
  <div class="mb-2 mb-sm-0">
    <h4 class="mb-1">

      Customer ID #00{{$customerInfo->owner_id}}
    </h4>
  
  </div>
  <button type="button" class="btn btn-outline-danger delete-customer">Delete Customer</button>
</div>


<div class="row">
  <!-- Customer-detail Sidebar -->
  <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
    <!-- Customer-detail Card -->
    <div class="card mb-4">
      <div class="card-body">
        <div class="customer-avatar-section">
          <div class="d-flex align-items-center flex-column">
            <img class="img-fluid rounded mb-3 mt-4" src="{{asset('assets/img/avatars/12.png')}}" height="120" width="120" alt="User avatar" />
            <div class="customer-info text-center mb-4">
              <h5 class="mb-1">{{$customerInfo->owner_name}}</h5>
              <span>Customer ID #00{{$customerInfo->owner_id}}</span>
            </div>
          </div>
        </div>
        <!-- <div class="d-flex justify-content-around flex-wrap mb-4">
          <div class="d-flex align-items-center gap-2">
            <div class="avatar me-1">
              <div class="avatar-initial rounded bg-label-primary"><i class='mdi mdi mdi-cart-plus mdi-20px'></i>
              </div>
            </div>
            <div>
              <h5 class="mb-0">184</h5>
              <span>Orders</span>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <div class="avatar me-1">
              <div class="avatar-initial rounded bg-label-primary"><i class='mdi mdi-currency-usd mdi-20px'></i>
              </div>
            </div>
            <div>
              <h5 class="mb-0">$12,378</h5>
              <span>Spent</span>
            </div>
          </div>
        </div> -->

        <div class="info-container">
          <h5 class="border-bottom text-uppercase pb-3">DETAILS</h5>
          <ul class="list-unstyled mb-4">
        
            <li class="mb-2">
              <span class="h6 me-1">Email:</span>
              <span>N/A</span>
            </li>
            <li class="mb-2">
              <span class="h6 me-1">Status:</span>
              <span class="badge bg-label-success rounded-pill">Active</span>
            </li>
            <li class="mb-2">
              <span class="h6 me-1">Contact:</span>
              <span>{{$customerInfo->mobile_number}}</span>
            </li>
             <li class="mb-2">
              <span class="h6 me-1">Address:</span>
              <span>{{$customerInfo->address}}</span>
            </li>
          </ul>
          <div class="d-flex justify-content-center">
            <a href="javascript:;" class="btn btn-primary me-3" data-bs-target="#editUser" data-bs-toggle="modal">Edit Details</a>
          </div>
        </div>
      </div>
    </div>
    <!-- /Customer-detail Card -->
    <!-- Plan Card -->

    <div class="card mb-4 bg-gradient-primary">
      <div class="card-body">
        <div class="row justify-content-between mb-3">
          <div class="col-md-12 col-lg-7 col-xl-12 col-xxl-7 text-center text-lg-start text-xl-center text-xxl-start order-1  order-lg-0 order-xl-1 order-xxl-0">
            <h4 class="card-title text-white text-nowrap">Upgrade to premium</h4>
            <p class="card-text text-white">Upgrade customer to premium membership to access pro features.</p>
          </div>
          <span class="col-md-12 col-lg-5 col-xl-12 col-xxl-5 text-center mx-auto mx-md-0 mb-2"><img src="{{asset('assets/img/illustrations/rocket.png')}}" class="w-px-75 m-2" alt="3dRocket"></span>
        </div>
        <button class="btn btn-white text-primary w-100 fw-medium shadow-sm" data-bs-target="#upgradePlanModal" data-bs-toggle="modal">Upgrade to premium</button>
      </div>
    </div>

    <!-- /Plan Card -->
  </div>
  <!--/ Customer Sidebar -->


  <!-- Customer Content -->
  <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
    <!-- Customer Pills -->
    <ul class="nav nav-pills flex-column flex-md-row mb-3">
      <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="mdi mdi-account-outline mdi-20px me-1"></i>Overview</a></li>
      <!-- <li class="nav-item"><a class="nav-link" href="{{url('app/ecommerce/customer/details/security')}}"><i class="mdi mdi-lock-open-outline mdi-20px me-1"></i>Security</a></li>
      <li class="nav-item"><a class="nav-link" href="{{url('app/ecommerce/customer/details/billing')}}"><i class="mdi mdi-bookmark-outline mdi-20px me-1"></i>Address & Billing</a></li>
      <li class="nav-item"><a class="nav-link" href="{{url('app/ecommerce/customer/details/notifications')}}"><i class="mdi mdi-bell-outline mdi-20px me-1"></i>Notifications</a></li> -->
    </ul>
    <!--/ Customer Pills -->

  @endforeach

    <!-- Customer cards -->
      <div class="row text-nowrap">
        <div class="col-md-6 mb-4 show-car-added d-none">
        </div>
        <div class="col-md-6 mb-4">
            @foreach($selectedCar as $key => $firstCar)
            <a href="/app/car/view/{{$firstCar->car_id}}">
          <div class="card h-100 cursor-pointer hover-effect selected-highlight" >
            <div class="card-body">
              <div class="card-icon mb-3">
                <div class="avatar">
                  <div class="avatar-initial rounded bg-label-primary"><i class='mdi mdi-car mdi-24px'></i>
                  </div>
                </div>
              </div>
              <div class="card-info">
                <h4 class="card-title mb-3">{{$firstCar->plate_number}}</h4>
                <div class="d-flex align-items-end mb-1 gap-1">
                  <h4 class="text-primary mb-0">{{$firstCar->manufacturer}}</h4>
                  <p class="mb-0"> {{$firstCar->vehicle_model}}  {{$firstCar->year}} </p>
                </div>
              </div>
            </div>
              </a>
            @endforeach
        </div>
      </div>

      
     @foreach($otherCars as $key => $other)
     @if($other > "")
        <div class="col-md-6 mb-4">
      <a href="/app/car/view/{{$other->id}}">
          <div class="card cursor-pointer hover-effect">
            <div class="card-body">
              <div class="card-icon mb-3">
                <div class="avatar">
                      <div class="avatar-initial rounded bg-label-primary"><i class='mdi mdi-car mdi-24px'></i>
                  </div>
                </div>
              </div>
              <div class="card-info">
                <h4 class="card-title mb-3">{{$other->plate_number}}</h4>
                <div class="d-flex align-items-end mb-1 gap-1">
                  <h4 class="text-primary mb-0">{{$other->manufacturer}}</h4>
                  <p class="mb-0"> {{$other->vehicle_model}}  {{$other->year}} </p>
                </div>
                <!-- <p class="mb-0 text-truncate">Account balance for next purchase</p> -->
              </div>
            </div>
          </div>
      </a>
        </div>
      @endif
      @endforeach

         {!!$htmlAddCar!!}

     
    </div>
    <!--/ customer cards -->


    <!-- Invoice table -->
    <div class="card mb-4">
      <div class="table-responsive mb-3">
        
        <div class="text-right mt-3">
          <a href="javascript:;" class="btn btn-primary me-3" data-bs-target="#addNewJobOrder" data-bs-toggle="modal" onclick="addJobOrder()">Add</a>
        </div>
        <table class="table datatables-customer-order">
          <thead class="table-light">
            <tr>
              <th></th>
              <th></th>
              <th>Order</th>
              <th>Date</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
    <!-- /Invoice table -->
  </div>
  <!--/ Customer Content -->
</div>

<!-- Modal -->
@include('_partials/_modals/modal-edit-user')
@include('_partials/_modals/modal-add-new-job-order')
@include('_partials/_modals/modal-add-new-car')
@include('_partials/_modals/modal-upgrade-plan')
<!-- /Modal -->
@endsection
