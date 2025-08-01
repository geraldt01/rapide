@extends('layouts/layoutMaster')

@section('title', 'Dashboard - Logistics')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}">
@endsection

@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/app-logistics-dashboard.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />

@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<script src="{{asset('assets/vendor/libs/moment/moment.js')}}"></script>
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>

<script src="{{asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/app-logistics-dashboard.js')}}"></script>
<script src="{{asset('assets/js/tables-database-inventory.js')}}"></script>

@endsection






@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Logistics /</span> Dashboard
</h4>


<!-- Card Border Shadow -->
<div class="row">
  <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-primary h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-primary"><i class="mdi mdi-bus-school mdi-20px"></i></span>
          </div>
          <h4 class="ms-1 mb-0 display-6">42</h4>
        </div>
        <p class="mb-0 text-heading">On route vehicles</p>
        <p class="mb-0">
          <span class="me-1">+18.2%</span>
          <small class="text-muted">than last week</small>
        </p>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-warning h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-warning">
              <i class='mdi mdi-alert mdi-20px'></i></span>
          </div>
          <h4 class="ms-1 mb-0 display-6">8</h4>
        </div>
        <p class="mb-0 text-heading">Vehicles with errors</p>
        <p class="mb-0">
          <span class="me-1">-8.7%</span>
          <small class="text-muted">than last week</small>
        </p>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-danger h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-danger">
              <i class='mdi mdi-source-fork mdi-20px'></i>
            </span>
          </div>
          <h4 class="ms-1 mb-0 display-6">27</h4>
        </div>
        <p class="mb-0 text-heading">Deviated from route</p>
        <p class="mb-0">
          <span class="me-1">+4.3%</span>
          <small class="text-muted">than last week</small>
        </p>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-info h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-info"><i class='mdi mdi-timer-outline mdi-20px'></i></span>
          </div>
          <h4 class="ms-1 mb-0 display-6">13</h4>
        </div>
        <p class="mb-0 text-heading">Late vehicles</p>
        <p class="mb-0">
          <span class="me-1">-2.5%</span>
          <small class="text-muted">than last week</small>
        </p>
      </div>
    </div>
  </div>
</div>
<!--/ Card Border Shadow -->
<div class="row">
  <!-- Vehicles overview -->
  <!-- <div class="col-xxl-6 mb-4 order-5 order-xxl-0">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title mb-0 me-2">Vehicles overview</h5>
        <div class="dropdown">
          <button class="btn p-0" type="button" id="topic" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="mdi mdi-dots-vertical mdi-24px"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="topic">
            <a class="dropdown-item" href="javascript:void(0);">Highest Views</a>
            <a class="dropdown-item" href="javascript:void(0);">See All</a>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="d-none d-lg-flex vehicles-progress-labels mb-3">
          <div class="vehicles-progress-label on-the-way-text" style="width: 39.7%;">On the way</div>
          <div class="vehicles-progress-label unloading-text" style="width: 28.3%;">Unloading</div>
          <div class="vehicles-progress-label loading-text" style="width: 17.4%;">Loading</div>
          <div class="vehicles-progress-label waiting-text" style="width: 14.6%;">Waiting</div>
        </div>
        <div class="vehicles-overview-progress progress rounded mb-3" style="height: 46px;">
          <div class="progress-bar fs-big fw-medium text-start bg-label-light text-heading px-1 px-lg-3" role="progressbar" style="width: 39.7%" aria-valuenow="39.7" aria-valuemin="0" aria-valuemax="100">39.7%</div>
          <div class="progress-bar fs-big fw-medium text-start bg-primary px-1 px-lg-3" role="progressbar" style="width: 28.3%" aria-valuenow="28.3" aria-valuemin="0" aria-valuemax="100">28.3%</div>
          <div class="progress-bar fs-big fw-medium text-start text-bg-info px-1 px-lg-3" role="progressbar" style="width: 17.4%" aria-valuenow="17.4" aria-valuemin="0" aria-valuemax="100">17.4%</div>
          <div class="progress-bar fs-big fw-medium text-start bg-gray-900 px-1 px-lg-3" role="progressbar" style="width: 14.6%" aria-valuenow="14.6" aria-valuemin="0" aria-valuemax="100">14.6%</div>
        </div>
        <div class="table-responsive">
          <table class="table card-table">
            <tbody class="table-border-bottom-0">
              <tr>
                <td class="w-50 ps-0">
                  <div class="d-flex justify-content-start align-items-center">
                    <div class="me-2">
                      <i class="text-heading mdi mdi-bus-school"></i>
                    </div>
                    <h6 class="mb-0 fw-normal">On the way</h6>
                  </div>
                </td>
                <td class="text-end pe-0 text-nowrap">
                  <h6 class="mb-0">2hr 10min</h6>
                </td>
                <td class="text-end pe-0">
                  <span>39.7%</span>
                </td>
              </tr>
              <tr>
                <td class="w-50 ps-0">
                  <div class="d-flex justify-content-start align-items-center">
                    <div class="me-2">
                      <i class='text-heading mdi mdi-progress-download'></i>
                    </div>
                    <h6 class="mb-0 fw-normal">Unloading</h6>
                  </div>
                </td>
                <td class="text-end pe-0 text-nowrap">
                  <h6 class="mb-0">3hr 15min</h6>
                </td>
                <td class="text-end pe-0">
                  <span>28.3%</span>
                </td>
              </tr>
              <tr>
                <td class="w-50 ps-0">
                  <div class="d-flex justify-content-start align-items-center">
                    <div class="me-2">
                      <i class='text-heading mdi mdi-upload'></i>
                    </div>
                    <h6 class="mb-0 fw-normal">Loading</h6>
                  </div>
                </td>
                <td class="text-end pe-0 text-nowrap">
                  <h6 class="mb-0">1hr 24min</h6>
                </td>
                <td class="text-end pe-0">
                  <span>17.4%</span>
                </td>
              </tr>
              <tr>
                <td class="w-50 ps-0">
                  <div class="d-flex justify-content-start align-items-center">
                    <div class="me-2">
                      <i class="text-heading mdi mdi-timer-outline"></i>
                    </div>
                    <h6 class="mb-0 fw-normal">Waiting</h6>
                  </div>
                </td>
                <td class="text-end pe-0 text-nowrap">
                  <h6 class="mb-0">5hr 19min</h6>
                </td>
                <td class="text-end pe-0">
                  <span>14.6%</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div> -->
  <!--/ Vehicles overview -->
  <!-- Shipment statistics-->
  <!-- <div class="col-lg-6 col-xxl-6 mb-4 order-3 order-xxl-1">
    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <div class="card-title mb-0">
          <h5 class="m-0 me-2 mb-1">Shipment statistics</h5>
          <p class="text-body mb-0">Total number of deliveries 23.8k</p>
        </div>
        <div class="dropdown">
          <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">January</button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="javascript:void(0);">January</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);">February</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);">March</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);">April</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);">May</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);">June</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);">July</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);">August</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);">September</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);">October</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);">November</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);">December</a></li>
          </ul>
        </div>
      </div>
      <div class="card-body">
        <div id="shipmentStatisticsChart"></div>
      </div>
    </div>
  </div> -->
  <!--/ Shipment statistics -->
  <!-- Delivery Performance -->
  <!-- <div class="col-lg-6 col-xxl-4 mb-4 order-2 order-xxl-2">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between">
        <div class="card-title mb-0">
          <h5 class="m-0 me-2 mb-1">Delivery Performance</h5>
          <p class="text-body mb-0">12% increase in this month</p>
        </div>
        <div class="dropdown">
          <button class="btn p-0" type="button" id="deliveryPerformance" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="mdi mdi-dots-vertical mdi-24px"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="deliveryPerformance">
            <a class="dropdown-item" href="javascript:void(0);">Select All</a>
            <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
            <a class="dropdown-item" href="javascript:void(0);">Share</a>
          </div>
        </div>
      </div>
      <div class="card-body">
        <ul class="p-0 m-0">
          <li class="d-flex mb-4 pb-1">
            <div class="avatar flex-shrink-0 me-3">
              <span class="avatar-initial rounded bg-label-primary"><i class="mdi mdi-wallet-giftcard mdi-24px"></i></span>
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-1 fw-normal">Packages in transit</h6>
                <small class="text-success fw-normal d-block">
                  <i class="mdi mdi-chevron-up"></i>
                  25.8%
                </small>
              </div>
              <div class="user-progress">
                <h6 class="mb-0">10k</h6>
              </div>
            </div>
          </li>
          <li class="d-flex mb-4 pb-1">
            <div class="avatar flex-shrink-0 me-3">
              <span class="avatar-initial rounded bg-label-info"><i class="mdi mdi-bus-school mdi-24px"></i></span>
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-1 fw-normal">Packages out for delivery</h6>
                <small class="text-success fw-normal d-block">
                  <i class="mdi mdi-chevron-up"></i>
                  4.3%
                </small>
              </div>
              <div class="user-progress">
                <h6 class="mb-0">5k</h6>
              </div>
            </div>
          </li>
          <li class="d-flex mb-4 pb-1">
            <div class="avatar flex-shrink-0 me-3">
              <span class="avatar-initial rounded bg-label-success"><i class="mdi mdi-check text-success mdi-24px"></i></span>
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-1 fw-normal">Packages delivered</h6>
                <small class="text-danger fw-normal d-block">
                  <i class="mdi mdi-chevron-down"></i>
                  12.5
                </small>
              </div>
              <div class="user-progress">
                <h6 class="mb-0">15k</h6>
              </div>
            </div>
          </li>
          <li class="d-flex mb-4 pb-1">
            <div class="avatar flex-shrink-0 me-3">
              <span class="avatar-initial rounded bg-label-warning"><i class="mdi mdi-home-outline mdi-24px"></i></span>
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-1 fw-normal">Delivery success rate</h6>
                <small class="text-success fw-normal d-block">
                  <i class="mdi mdi-chevron-up"></i>
                  35.6%
                </small>
              </div>
              <div class="user-progress">
                <h6 class="mb-0">95%</h6>
              </div>
            </div>
          </li>
          <li class="d-flex mb-4 pb-1">
            <div class="avatar flex-shrink-0 me-3">
              <span class="avatar-initial rounded bg-label-secondary"><i class="mdi mdi-timer-outline mdi-24px"></i></span>
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-1 fw-normal">Average delivery time</h6>
                <small class="text-danger fw-normal d-block">
                  <i class="mdi mdi-chevron-down"></i>
                  2.15
                </small>
              </div>
              <div class="user-progress">
                <h6 class="mb-0">2.5 Days</h6>
              </div>
            </div>
          </li>
          <li class="d-flex">
            <div class="avatar flex-shrink-0 me-3">
              <span class="avatar-initial rounded bg-label-danger"><i class="mdi mdi-account-outline mdi-24px"></i></span>
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-1 fw-normal">Customer satisfaction</h6>
                <small class="text-success fw-normal d-block">
                  <i class="mdi mdi-chevron-up"></i>
                  5.7%
                </small>
              </div>
              <div class="user-progress">
                <h6 class="mb-0">4.5/5</h6>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div> -->
  <!--/ Delivery Performance -->
  <!-- Reasons for delivery exceptions -->
  <!-- <div class="col-md-6 col-xxl-4 mb-4 order-1 order-xxl-3">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between">
        <div class="card-title mb-0">
          <h5 class="m-0 me-2">Reasons for delivery exceptions</h5>
        </div>
        <div class="dropdown">
          <button class="btn p-0" type="button" id="deliveryExceptions" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="mdi mdi-dots-vertical mdi-24px"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="deliveryExceptions">
            <a class="dropdown-item" href="javascript:void(0);">Select All</a>
            <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
            <a class="dropdown-item" href="javascript:void(0);">Share</a>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div id="deliveryExceptionsChart"></div>
      </div>
    </div>
  </div> -->
  <!--/ Reasons for delivery exceptions -->
  <!-- Orders by Countries -->
  <!-- <div class="col-md-6 col-xxl-4 mb-4 order-0 order-xxl-4">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between">
        <div class="card-title mb-0">
          <h5 class="m-0 me-2 mb-1">Orders by Countries</h5>
          <p class="text-body mb-0">62 deliveries in progress</p>
        </div>
        <div class="dropdown">
          <button class="btn p-0" type="button" id="ordersCountries" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="mdi mdi-dots-vertical mdi-24px"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="ordersCountries">
            <a class="dropdown-item" href="javascript:void(0);">Select All</a>
            <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
            <a class="dropdown-item" href="javascript:void(0);">Share</a>
          </div>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="nav-align-top">
          <ul class="nav nav-tabs nav-fill tabs-line border-bottom-0" role="tablist">
            <li class="nav-item">
              <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-new" aria-controls="navs-justified-new" aria-selected="true">New</button>
            </li>
            <li class="nav-item">
              <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-link-preparing" aria-controls="navs-justified-link-preparing" aria-selected="false">Preparing</button>
            </li>
            <li class="nav-item">
              <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-link-shipping" aria-controls="navs-justified-link-shipping" aria-selected="false">Shipping</button>
            </li>
          </ul>
          <div class="tab-content border-0 pb-0 px-4 mx-1">
            <div class="tab-pane fade show active" id="navs-justified-new" role="tabpanel">
              <ul class="timeline mb-0">
                <li class="timeline-item ps-4 border-left-dashed">
                  <span class="timeline-indicator-advanced text-success border-0 shadow-none">
                    <i class='mdi mdi-check-circle-outline'></i>
                  </span>
                  <div class="timeline-event ps-1">
                    <div class="timeline-header">
                      <small class="text-success text-uppercase fw-medium">sender</small>
                    </div>
                    <h6 class="mb-2">Myrtle Ullrich</h6>
                    <p class="mb-0">101 Boulder, California(CA), 95959</p>
                  </div>
                </li>
                <li class="timeline-item ps-4 border-transparent">
                  <span class="timeline-indicator-advanced text-primary border-0 shadow-none">
                    <i class='mdi mdi-map-marker-outline'></i>
                  </span>
                  <div class="timeline-event ps-1">
                    <div class="timeline-header">
                      <small class="text-primary text-uppercase fw-medium">Receiver</small>
                    </div>
                    <h6 class="mb-2">Barry Schowalter</h6>
                    <p class="mb-0">939 Orange, California(CA), 92118</p>
                  </div>
                </li>
              </ul>
              <div class="border-1 border-light border-top border-dashed mb-2"></div>
              <ul class="timeline mb-0">
                <li class="timeline-item ps-4 border-left-dashed">
                  <span class="timeline-indicator-advanced text-success border-0 shadow-none">
                    <i class='mdi mdi-check-circle-outline'></i>
                  </span>
                  <div class="timeline-event ps-1">
                    <div class="timeline-header">
                      <small class="text-success text-uppercase fw-medium">sender</small>
                    </div>
                    <h6 class="mb-2">Veronica Herman</h6>
                    <p class="mb-0">162 Windsor, California(CA), 95492</p>
                  </div>
                </li>
                <li class="timeline-item ps-4 border-transparent">
                  <span class="timeline-indicator-advanced text-primary border-0 shadow-none">
                    <i class='mdi mdi-map-marker-outline'></i>
                  </span>
                  <div class="timeline-event ps-1">
                    <div class="timeline-header">
                      <small class="text-primary text-uppercase fw-medium">Receiver</small>
                    </div>
                    <h6 class="mb-2">Helen Jacobs</h6>
                    <p class="mb-0">487 Sunset, California(CA), 94043</p>
                  </div>
                </li>
              </ul>
            </div>
            <div class="tab-pane fade" id="navs-justified-link-preparing" role="tabpanel">
              <ul class="timeline mb-0">
                <li class="timeline-item ps-4 border-left-dashed">
                  <span class="timeline-indicator-advanced text-success border-0 shadow-none">
                    <i class='mdi mdi-check-circle-outline'></i>
                  </span>
                  <div class="timeline-event ps-1">
                    <div class="timeline-header">
                      <small class="text-success text-uppercase fw-medium">sender</small>
                    </div>
                    <h6 class="mb-2">Barry Schowalter</h6>
                    <p class="mb-0">939 Orange, California(CA), 92118</p>
                  </div>
                </li>
                <li class="timeline-item ps-4 border-transparent border-left-dashed">
                  <span class="timeline-indicator-advanced text-primary border-0 shadow-none">
                    <i class='mdi mdi-map-marker-outline'></i>
                  </span>
                  <div class="timeline-event ps-1">
                    <div class="timeline-header">
                      <small class="text-primary text-uppercase fw-medium">Receiver</small>
                    </div>
                    <h6 class="mb-2">Myrtle Ullrich</h6>
                    <p class="mb-0">101 Boulder, California(CA), 95959 </p>
                  </div>
                </li>
              </ul>
              <div class="border-1 border-light border-top border-dashed mb-2 "></div>
              <ul class="timeline mb-0">
                <li class="timeline-item ps-4 border-left-dashed">
                  <span class="timeline-indicator-advanced text-success border-0 shadow-none">
                    <i class='mdi mdi-check-circle-outline'></i>
                  </span>
                  <div class="timeline-event ps-1">
                    <div class="timeline-header">
                      <small class="text-success text-uppercase fw-medium">sender</small>
                    </div>
                    <h6 class="mb-2">Veronica Herman</h6>
                    <p class="mb-0">162 Windsor, California(CA), 95492</p>
                  </div>
                </li>
                <li class="timeline-item ps-4 border-transparent">
                  <span class="timeline-indicator-advanced text-primary border-0 shadow-none">
                    <i class='mdi mdi-map-marker-outline'></i>
                  </span>
                  <div class="timeline-event ps-1">
                    <div class="timeline-header">
                      <small class="text-primary text-uppercase fw-medium">Receiver</small>
                    </div>
                    <h6 class="mb-2">Helen Jacobs</h6>
                    <p class="mb-0">487 Sunset, California(CA), 94043</p>
                  </div>
                </li>
              </ul>
            </div>
            <div class="tab-pane fade" id="navs-justified-link-shipping" role="tabpanel">
              <ul class="timeline mb-0">
                <li class="timeline-item ps-4 border-left-dashed">
                  <span class="timeline-indicator-advanced text-success border-0 shadow-none">
                    <i class='mdi mdi-check-circle-outline'></i>
                  </span>
                  <div class="timeline-event ps-1">
                    <div class="timeline-header">
                      <small class="text-success text-uppercase fw-medium">sender</small>
                    </div>
                    <h6 class="mb-2">Veronica Herman</h6>
                    <p class="mb-0">101 Boulder, California(CA), 95959</p>
                  </div>
                </li>
                <li class="timeline-item ps-4 border-transparent">
                  <span class="timeline-indicator-advanced text-primary border-0 shadow-none">
                    <i class='mdi mdi-map-marker-outline'></i>
                  </span>
                  <div class="timeline-event ps-1">
                    <div class="timeline-header">
                      <small class="text-primary text-uppercase fw-medium">Receiver</small>
                    </div>
                    <h6 class="mb-2">Barry Schowalter</h6>
                    <p class="mb-0">939 Orange, California(CA), 92118</p>
                  </div>
                </li>
              </ul>
              <div class="border-1 border-light border-top border-dashed mb-2 "></div>
              <ul class="timeline mb-0">
                <li class="timeline-item ps-4 border-left-dashed">
                  <span class="timeline-indicator-advanced text-success border-0 shadow-none">
                    <i class='mdi mdi-check-circle-outline'></i>
                  </span>
                  <div class="timeline-event ps-1">
                    <div class="timeline-header">
                      <small class="text-success text-uppercase fw-medium">sender</small>
                    </div>
                    <h6 class="mb-2">Myrtle Ullrich</h6>
                    <p class="mb-0">162 Windsor, California(CA), 95492 </p>
                  </div>
                </li>
                <li class="timeline-item ps-4 border-transparent">
                  <span class="timeline-indicator-advanced text-primary border-0 shadow-none">
                    <i class='mdi mdi-map-marker-outline'></i>
                  </span>
                  <div class="timeline-event ps-1">
                    <div class="timeline-header">
                      <small class="text-primary text-uppercase fw-medium">Receiver</small>
                    </div>
                    <h6 class="mb-2">Helen Jacobs</h6>
                    <p class="mb-0">487 Sunset, California(CA), 94043</p>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div> -->
  <!--/ Orders by Countries -->

<!-- DataTable with Buttons -->
<div class="col-12 order-5">
  <div class="card">
    <div class="card-datatable table-responsive pt-0">
      <table class="datatables-basic table table-bordered">
        <thead>
          <tr>
            <th></th>
            <th></th>
            <th>id</th>
            <th>Item Name</th>
            <th>Stocks remaining</th>
            <th>Stock Status</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
  <!-- Modal to add new record -->
  <div class="offcanvas offcanvas-end" id="add-new-record">
    <div class="offcanvas-header border-bottom">
      <h5 class="offcanvas-title" id="exampleModalLabel">New Record</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body flex-grow-1">
      <form class="add-new-record pt-0 row g-3" id="form-add-new-record" onsubmit="return false">
        <div class="col-sm-12">
          <div class="input-group input-group-merge">
            <span id="basicFullname2" class="input-group-text"><i class="mdi mdi-account-outline"></i></span>
            <div class="form-floating form-floating-outline">
              <input type="text" id="basicFullname" class="form-control dt-full-name" name="basicFullname" placeholder="John Doe" aria-label="John Doe" aria-describedby="basicFullname2" />
              <label for="basicFullname">Full Name</label>
            </div>
          </div>
        </div>
        <div class="col-sm-12">
          <div class="input-group input-group-merge">
            <span id="basicPost2" class="input-group-text"><i class='mdi mdi-briefcase-outline'></i></span>
            <div class="form-floating form-floating-outline">
              <input type="text" id="basicPost" name="basicPost" class="form-control dt-post" placeholder="Web Developer" aria-label="Web Developer" aria-describedby="basicPost2" />
              <label for="basicPost">Post</label>
            </div>
          </div>
        </div>
        <div class="col-sm-12">
          <div class="input-group input-group-merge">
            <span class="input-group-text"><i class="mdi mdi-email-outline"></i></span>
            <div class="form-floating form-floating-outline">
              <input type="text" id="basicEmail" name="basicEmail" class="form-control dt-email" placeholder="john.doe@example.com" aria-label="john.doe@example.com" />
              <label for="basicEmail">Email</label>
            </div>
          </div>
          <div class="form-text">
            You can use letters, numbers & periods
          </div>
        </div>
        <div class="col-sm-12">
          <div class="input-group input-group-merge">
            <span id="basicDate2" class="input-group-text"><i class='mdi mdi-calendar-month-outline'></i></span>
            <div class="form-floating form-floating-outline">
              <input type="text" class="form-control dt-date" id="basicDate" name="basicDate" aria-describedby="basicDate2" placeholder="MM/DD/YYYY" aria-label="MM/DD/YYYY" />
              <label for="basicDate">Joining Date</label>
            </div>
          </div>
        </div>
        <div class="col-sm-12">
          <div class="input-group input-group-merge">
            <span id="basicSalary2" class="input-group-text"><i class='mdi mdi-currency-usd'></i></span>
            <div class="form-floating form-floating-outline">
              <input type="number" id="basicSalary" name="basicSalary" class="form-control dt-salary" placeholder="12000" aria-label="12000" aria-describedby="basicSalary2" />
              <label for="basicSalary">Salary</label>
            </div>
          </div>
        </div>
        <div class="col-sm-12">
          <button type="submit" class="btn btn-primary data-submit me-sm-3 me-1">Submit</button>
          <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        </div>
      </form>

    </div>
  </div>
  </div>
<!--/ DataTable with Buttons -->

@endsection

@include('_partials/_modals/modal-add-new-inventory')
