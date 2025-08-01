@extends('layouts/layoutMaster')

@section('title', 'eCommerce Product List - Apps')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/app-ecommerce-product-list.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light"> Dashboard
</h4>

<!-- Product List Widget -->

<div class="card mb-4">
  <div class="card-widget-separator-wrapper">
    <div class="card-body card-widget-separator">
      <div class="row gy-4 gy-sm-1">
        <div class="col-sm-6 col-lg-3">
          <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
            <div>
              <p class="mb-2">Total Sales Today</p>
              <h4 class="mb-2">₱5,345.43</h4>
              <p class="mb-0"><span class="me-2">5k orders</span><span class="badge rounded-pill bg-label-success">+5.7%</span></p>
            </div>
            <div class="avatar me-sm-4">
              <span class="avatar-initial rounded bg-label-secondary">
                <i class="mdi mdi-home-outline mdi-24px"></i>
              </span>
            </div>
          </div>
          <hr class="d-none d-sm-block d-lg-none me-4">
        </div>
        <div class="col-sm-6 col-lg-3">
          <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-3 pb-sm-0">
            <div>
              <p class="mb-2">Website Sales</p>
              <h4 class="mb-2">₱674,347.12</h4>
              <p class="mb-0"><span class="me-2">21k orders</span><span class="badge rounded-pill bg-label-success">+12.4%</span></p>
            </div>
            <div class="avatar me-lg-4">
              <span class="avatar-initial rounded bg-label-secondary">
                <i class="mdi mdi-laptop mdi-24px"></i>
              </span>
            </div>
          </div>
          <hr class="d-none d-sm-block d-lg-none">
        </div>
        <div class="col-sm-6 col-lg-3">
          <div class="d-flex justify-content-between align-items-start border-end pb-3 pb-sm-0 card-widget-3">
            <div>
              <p class="mb-2">Discount</p>
              <h4 class="mb-2">₱14,235.12</h4>
              <p class="mb-0">6k orders</p>
            </div>
            <div class="avatar me-sm-4">
              <span class="avatar-initial rounded bg-label-secondary">
                <i class="mdi mdi-wallet-giftcard mdi-24px"></i>
              </span>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-lg-3">
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
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Product List Table -->
 
<div class="card">
  <div class="card-header">
    <h5 class="card-title">Filter</h5>
    <div class="d-flex justify-content-between align-items-center row py-3 gap-3 gap-md-0">
      <div class="col-md-4 product_status"></div>
      <div class="col-md-4 product_category"></div>
      <div class="col-md-4 product_stock"></div>
    </div>
  </div>
  <div class="card-datatable table-responsive">
    <table class="datatables-products table">
      <thead class="table-light">
        <tr>
          <th></th>
          <th></th>
          <th>plate number</th>
          <th>Vehicle Type</th>
          <th>Mileage</th>
          <th>Owner</th>
          <!-- <th>price</th>
          <th>qty</th> -->
          <th>status</th>
          <th>actions</th>
        </tr>
      </thead>
    </table>
  </div>
</div>




<!-- Top Referral Source  -->
  <!-- <div class="col-12 col-xl-8 mb-4">
    <div class="card card mt-4">
      <div class="card-header d-flex justify-content-between">
        <div class="card-title m-0">
          <h5 class="mb-1">Inventory</h5>
          <p class="text-body mb-0">82% Activity Growth</p>
        </div>
        <div class="dropdown">
          <button class="btn p-0" type="button" id="earningReportsTabsId" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="mdi mdi-dots-vertical mdi-24px"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="earningReportsTabsId">
            <a class="dropdown-item" href="javascript:void(0);">View More</a>
            <a class="dropdown-item" href="javascript:void(0);">Delete</a>
          </div>
        </div>
      </div>
      <div class="card-body pb-1">
        <ul class="nav nav-tabs nav-tabs-widget pb-3 gap-4 mx-1 d-flex flex-nowrap" role="tablist">
          <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link btn active d-flex flex-column align-items-center justify-content-center" role="tab" data-bs-toggle="tab" data-bs-target="#navs-orders-id" aria-controls="navs-orders-id" aria-selected="true">
              <div class="avatar">
                <div class="avatar-initial bg-label-secondary rounded">
                  <i class="mdi mdi-cellphone"></i>
                </div>
              </div>
            </a>
          </li>
          <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link btn d-flex flex-column align-items-center justify-content-center" role="tab" data-bs-toggle="tab" data-bs-target="#navs-sales-id" aria-controls="navs-sales-id" aria-selected="false">
              <div class="avatar">
                <div class="avatar-initial bg-label-secondary rounded">
                  <i class="mdi mdi-television"></i>
                </div>
              </div>
            </a>
          </li>
          <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link btn d-flex flex-column align-items-center justify-content-center" role="tab" data-bs-toggle="tab" data-bs-target="#navs-profit-id" aria-controls="navs-profit-id" aria-selected="false">
              <div class="avatar">
                <div class="avatar-initial bg-label-secondary rounded">
                  <i class="mdi mdi-gamepad-circle-outline"></i>
                </div>
              </div>
            </a>
          </li>
          <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link btn d-flex flex-column align-items-center justify-content-center" role="tab" data-bs-toggle="tab" data-bs-target="#navs-income-id" aria-controls="navs-income-id" aria-selected="false">
              <div class="avatar">
                <div class="avatar-initial bg-label-secondary rounded">
                  <i class="mdi mdi-watch"></i>
                </div>
              </div>
            </a>
          </li>
          <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link btn d-flex align-items-center justify-content-center disabled" role="tab" data-bs-toggle="tab" aria-selected="false">
              <div class="avatar">
                <div class="avatar-initial bg-label-secondary rounded">
                  <i class="mdi mdi-plus"></i>
                </div>
              </div>
            </a>
          </li>
        </ul>
        <div class="tab-content p-0 ms-0 ms-sm-2">
          <div class="tab-pane fade show active" id="navs-orders-id" role="tabpanel">
            <div class="table-responsive text-nowrap">
              <table class="table table-borderless">
                <thead class="border-bottom">
                  <tr>
                    <th class="ps-0 fw-medium">Image</th>
                    <th class="fw-medium ps-0">Product Name</th>
                    <th class="pe-0 fw-medium text-end">Qty</th>
                    <th class="pe-0 fw-medium text-end">Price</th>
                    <th class="pe-0 text-end fw-medium">Total</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="ps-0">
                      <img src="{{asset('assets/img/products/samsung-s22.png')}}" alt="samsung" class="rounded" height="34">
                    </td>
                    <td class="h6 ps-0">Oneplus 9 Pro</td>
                    <td class="text-heading text-end pe-0">4</td>
                    <td class="text-heading text-end pe-0">$599</td>
                    <td class="pe-0 text-end h6">$2,396</td>
                  </tr>
                  <tr>
                    <td class="ps-0">
                      <img src="{{asset('assets/img/products/apple-iPhone-13-pro.png')}}" alt="iphone" class="rounded" height="34">
                    </td>
                    <td class="h6 ps-0">Apple iPhone 13 Pro</td>
                    <td class="text-heading text-end pe-0">1</td>
                    <td class="text-heading text-end pe-0">$399</td>
                    <td class="pe-0 text-end h6">$399</td>
                  </tr>
                  <tr>
                    <td class="ps-0">
                      <img src="{{asset('assets/img/products/oneplus-9-pro.png')}}" alt="us-flag" class="rounded" height="34">
                    </td>
                    <td class="h6 ps-0">Oneplus 9 Pro</td>
                    <td class="text-heading text-end pe-0">4</td>
                    <td class="text-heading text-end pe-0">$599</td>
                    <td class="pe-0 text-end h6">$2,396</td>
                  </tr>
                  <tr>
                    <td class="ps-0">
                      <img src="{{asset('assets/img/products/google-pixel-6.png')}}" alt="us-flag" class="rounded" height="34">
                    </td>
                    <td class="h6 ps-0">Google Pixel 6</td>
                    <td class="text-heading text-end pe-0">3</td>
                    <td class="text-heading text-end pe-0">$450</td>
                    <td class="pe-0 text-end h6">$1,350</td>
                  </tr>

                </tbody>

              </table>
            </div>
          </div>
          <div class="tab-pane fade" id="navs-sales-id" role="tabpanel">
            <div class="table-responsive text-nowrap">
              <table class="table table-borderless">
                <thead class="border-bottom">
                  <tr>
                    <th class="ps-0 fw-medium">Image</th>
                    <th class="fw-medium ps-0">Product Name</th>
                    <th class="pe-0 fw-medium text-end">Qty</th>
                    <th class="pe-0 fw-medium text-end">Price</th>
                    <th class="pe-0 text-end fw-medium">Total</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="ps-0">
                      <img src="{{asset('assets/img/products/apple-mac-mini.png')}}" alt="mac-mini" class="rounded" height="34">
                    </td>
                    <td class="h6 ps-0">Apple Mac Mini</td>
                    <td class="text-heading text-end pe-0">2</td>
                    <td class="text-heading text-end pe-0">$849</td>
                    <td class="pe-0 text-end h6">$1,698</td>
                  </tr>
                  <tr>
                    <td class="ps-0">
                      <img src="{{asset('assets/img/products/hp-envy-x360.png')}}" alt="hp-envy" class="rounded" height="34">
                    </td>
                    <td class="h6 ps-0">Newest HP Envy x360</td>
                    <td class="text-heading text-end pe-0">4</td>
                    <td class="text-heading text-end pe-0">$599</td>
                    <td class="pe-0 text-end h6">$2,399</td>
                  </tr>
                  <tr>
                    <td class="ps-0">
                      <img src="{{asset('assets/img/products/dell-inspiron-3000.png')}}" alt="dell" class="rounded" height="34">
                    </td>
                    <td class="h6 ps-0">Dell Inspiron 3000</td>
                    <td class="text-heading text-end pe-0">1</td>
                    <td class="text-heading text-end pe-0">$399</td>
                    <td class="pe-0 text-end h6">$399</td>
                  </tr>
                  <tr>
                    <td class="ps-0">
                      <img src="{{asset('assets/img/products/apple-iMac-4k.png')}}" alt="apple-iMac" class="rounded" height="34">
                    </td>
                    <td class="h6 ps-0">Apple iMac 4k</td>
                    <td class="text-heading text-end pe-0">3</td>
                    <td class="text-heading text-end pe-0">$450</td>
                    <td class="pe-0 text-end h6">$1,350</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane fade" id="navs-profit-id" role="tabpanel">
            <div class="table-responsive text-nowrap">
              <table class="table table-borderless">
                <thead class="border-bottom">
                  <tr>
                    <th class="ps-0 fw-medium">Image</th>
                    <th class="fw-medium ps-0">Product Name</th>
                    <th class="pe-0 fw-medium text-end">Qty</th>
                    <th class="pe-0 fw-medium text-end">Price</th>
                    <th class="pe-0 text-end fw-medium">Total</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="ps-0">
                      <img src="{{asset('assets/img/products/sony-play-station-5.png')}}" alt="sony-play-station" class="rounded" height="34">
                    </td>
                    <td class="h6 ps-0">Sony Play Station 5</td>
                    <td class="text-heading text-end pe-0">1</td>
                    <td class="text-heading text-end pe-0">$599</td>
                    <td class="pe-0 text-end h6">$1,698</td>
                  </tr>
                  <tr>
                    <td class="ps-0">
                      <img src="{{asset('assets/img/products/xbox-series-x.png')}}" alt="xbox" class="rounded" height="34">
                    </td>
                    <td class="h6 ps-0">XBOX Series X</td>
                    <td class="text-heading text-end pe-0">3</td>
                    <td class="text-heading text-end pe-0">$489</td>
                    <td class="pe-0 text-end h6">$1,467</td>
                  </tr>
                  <tr>
                    <td class="ps-0">
                      <img src="{{asset('assets/img/products/nintendo-switch.png')}}" alt="nintendo-switch" class="rounded" height="34">
                    </td>
                    <td class="h6 ps-0">Nintendo Switch</td>
                    <td class="text-heading text-end pe-0">4</td>
                    <td class="text-heading text-end pe-0">$335</td>
                    <td class="pe-0 text-end h6">$1,340</td>
                  </tr>
                  <tr>
                    <td class="ps-0">
                      <img src="{{asset('assets/img/products/sup-game-box-400.png')}}" alt="game" class="rounded" height="34">
                    </td>
                    <td class="h6 ps-0">SUP Game Box 400</td>
                    <td class="text-heading text-end pe-0">8</td>
                    <td class="text-heading text-end pe-0">$14</td>
                    <td class="pe-0 text-end h6">$112</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane fade" id="navs-income-id" role="tabpanel">
            <div class="table-responsive text-nowrap">
              <table class="table table-borderless">
                <thead class="border-bottom">
                  <tr>
                    <th class="ps-0 fw-medium">Image</th>
                    <th class="fw-medium ps-0">Product Name</th>
                    <th class="pe-0 fw-medium text-end">Qty</th>
                    <th class="pe-0 fw-medium text-end">Price</th>
                    <th class="pe-0 text-end fw-medium">Total</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="ps-0">
                      <img src="{{asset('assets/img/products/samsung-watch-4.png')}}" alt="samsung-watch" class="rounded" height="34">
                    </td>
                    <td class="h6 ps-0">Samsung Watch 4</td>
                    <td class="text-heading text-end pe-0">2</td>
                    <td class="text-heading text-end pe-0">$202</td>
                    <td class="pe-0 text-end h6">$404</td>
                  </tr>
                  <tr>
                    <td class="ps-0">
                      <img src="{{asset('assets/img/products/apple-watch-7.png')}}" alt="apple-watch" class="rounded" height="34">
                    </td>
                    <td class="h6 ps-0">Apple Watch 7</td>
                    <td class="text-heading text-end pe-0">1</td>
                    <td class="text-heading text-end pe-0">$399</td>
                    <td class="pe-0 text-end h6">$399</td>
                  </tr>
                  <tr>
                    <td class="ps-0">
                      <img src="{{asset('assets/img/products/amazon-echo-dot.png')}}" alt="amazon-echo-dot" class="rounded" height="34">
                    </td>
                    <td class="h6 ps-0">Amazon Echo Dot</td>
                    <td class="text-heading text-end pe-0">3</td>
                    <td class="text-heading text-end pe-0">$59</td>
                    <td class="pe-0 text-end h6">$177</td>
                  </tr>
                  <tr>
                    <td class="ps-0">
                      <img src="{{asset('assets/img/products/gramin-verve.png')}}" alt="gramin-verve" class="rounded" height="34">
                    </td>
                    <td class="h6 ps-0">Gramin Verve</td>
                    <td class="text-heading text-end pe-0">1</td>
                    <td class="text-heading text-end pe-0">$139</td>
                    <td class="pe-0 text-end h6">$139</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div> -->
  <!--/ Top Referral Source  -->



@endsection
