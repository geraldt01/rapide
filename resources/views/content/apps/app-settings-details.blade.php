@extends('layouts/layoutMaster')

@section('title', 'eCommerce Settings Store Details - Apps')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave-phone.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/app-settings.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Global Settings /</span> Special Notes
</h4>

<div class="row g-4">

  <!-- Navigation -->
  <div class="col-12 col-lg-4">
    <div class="d-flex justify-content-between flex-column mb-3 mb-md-0">
      <ul class="nav nav-align-left nav-pills flex-column">
        <li class="nav-item mb-1">
          <a class="nav-link active" href="javascript:void(0);">
            <i class="mdi mdi-storefront-outline me-2"></i>
            <span class="align-middle">Special Notes</span>
          </a>
        </li>
        <li class="nav-item mb-1">
          <a class="nav-link" href="{{url('/app/ecommerce/settings/car-manufacturer')}}">
            <i class="mdi mdi-credit-var-outline me-2"></i>
            <span class="align-middle">Car Manufacturer</span>
          </a>
        </li>
         <li class="nav-item mb-1">
          <a class="nav-link " href="{{url('/app/ecommerce/settings/car-vehicle-type')}}">
            <i class="mdi mdi-credit-car-outline me-2"></i>
            <span class="align-middle">Car Vehicle Type</span>
          </a>
        </li>
        <!-- <li class="nav-item mb-1">
          <a class="nav-link" href="{{url('/app/ecommerce/settings/shipping')}}">
            <i class="mdi mdi-package-variant-closed me-2"></i>
            <span class="align-middle">Shipping & delivery</span>
          </a>
        </li>
        <li class="nav-item mb-1">
          <a class="nav-link" href="{{url('/app/ecommerce/settings/locations')}}">
            <i class="mdi mdi-map-marker-outline me-2"></i>
            <span class="align-middle">Locations</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{url('/app/ecommerce/settings/notifications')}}">
            <i class="mdi mdi-bell-outline me-2"></i>
            <span class="align-middle">Notifications</span>
          </a>
        </li> -->
      </ul>
    </div>
  </div>
  <!-- /Navigation -->

    <div class="col-12 col-lg-8 pt-4 pt-lg-0">
    <div class="tab-content p-0">
      <!-- Shipping & delivery Tab -->
      <div class="tab-pane fade show active" id="shipping_delivery" role="tabpanel">
        <div class="card mb-4">
          <div class="card-header d-flex justify-content-between align-items-start">
            <div class="card-title m-0">
              <h5 class="m-0">Special Notes</h5>
              <!-- <p class="text-body mb-0">Choose where you ship and how much you charge for shipping at check out.</p> -->
            </div>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <div class="table-responsive text-nowrap border rounded">
                <table class="table">
                  <thead class="table-light">
                    <tr>
                      <th>#</th>
                      <th>Note</th>
                      <th class="text-end">Actions</th>
                    </tr>
                  </thead>
                  <tbody id="tbl-note-body">
                @foreach($SpecialNoteData  as $k => $note)
                    <tr>
                      <td>{{$k +1}}</td>
                      <td>{{$note->value}}</td>
                      <td class="text-end">
                        <div class="dropdown pe-3">
                          <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></button>
                          <div class="dropdown-menu">
                            <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#addNewSpecialNote" onclick="editNote({{$note->id}})"><i class="mdi mdi-pencil-outline me-1"></i> Edit</a>
                            <a class="dropdown-item" onclick="promptDeleteNote({{$note->id}})"><i class="mdi mdi-delete-outline me-1" ></i> Delete</a>
                          </div>
                        </div>
                      </td>
                    </tr>
                  @endforeach 
                  </tbody>
                </table>
              </div>
            </div>
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addNewSpecialNote" onclick="createNote()">Add Note</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /Options-->
</div>

@include('_partials/_modals/modal-add-new-special-note')
@endsection
