@php
$customizerHidden = 'customizer-hide';
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Monthly Payment')

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-misc.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/spinkit/spinkit.css')}}" />

@endsection


@section('page-script')
<script src="{{asset('assets/js/app-monthly-subscription.js')}}"></script>
@endsection





@section('content')
<!-- Not Authorized -->
<div class="misc-wrapper section-body">
  <h1 class="mb-2 mx-2" style="font-size: 6rem;"></h1>
  <h4 class="mb-2 text-center">Scan the QR Code below using your gcash app🔐</h4>
  <h6 class="mt-2">Payment for {{date('F')}}.</h6>
  <div class="d-flex justify-content-center mt-5">
    <img src="{{ asset('assets/img/illustrations/misc-not-authorized-object.png')}}" alt="misc-not-authorized" class="img-fluid misc-object d-none d-lg-inline-block" width="190">
    <img src="{{ asset('assets/img/illustrations/misc-bg-'.$configData['style'].'.png') }}" alt="misc-not-authorized" class="misc-bg d-none d-lg-inline-block" data-app-light-img="illustrations/misc-bg-light.png" data-app-dark-img="illustrations/misc-bg-dark.png">
    <div class="d-flex flex-column align-items-center">
      <img src="{{ asset('assets/img/pages/gcash-1500.jpg')}}" alt="misc-not-authorized" class="img-fluid z-1" width="360">
      <div class="text-center">
        <span class="note-alert d-none"><br>Please click the button below if you're done.<br></span>
        <button class="btn btn-primary text-center my-4 click-disabled" id="btn-payment-confirm" onclick="paymentConfirm()">Please Scan</button>
      </div>
    </div>
  </div>
</div>

<div class="misc-wrapper confirm-body d-none">
  <h1 class="mb-2 mx-2"><i class="mdi mdi-check me-1" style="font-size: 23px;color: green"></i></h1>
  <h2 class="mb-2 text-center">Thank you for your confirmation!</h2>
  <h6 class="mt-2">Payment for {{date('F')}}.</h6>
    <br>
  <h3 class="mb-2 text-center">Click the link below to notify Mr. Gerald to verify your payment!</h3>

   <button class="btn btn-primary text-center my-4" id="btn-payment-confirm-success" onclick="notify()">Send notification</button>
  
   <a href="/"><u>Go back to Home</u></a>
</div>


<!-- /Not Authorized -->


<div class="loader d-none">
<div class="sk-wave sk-primary">
    <div class="sk-wave-rect"></div>
    <div class="sk-wave-rect"></div>
    <div class="sk-wave-rect"></div>
    <div class="sk-wave-rect"></div>
    <div class="sk-wave-rect"></div>
</div>
</div>

@endsection
