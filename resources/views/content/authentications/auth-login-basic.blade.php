@php
$configData = Helper::appClasses();
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Login Basic - Pages')

@section('vendor-style')
<!-- Vendor -->
<link rel="stylesheet" href="{{asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css')}}" />
@endsection

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
@endsection
<style type="text/css">
input, select {
  text-transform: initial !important;
}
  </style>
@section('vendor-script')
<script src="{{asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/pages-auth.js')}}"></script>
@endsection

@section('content')
<div class="position-relative">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-4">

      <!-- Login -->
      <div class="card p-2">
        <!-- Logo -->
        <div class="app-brand justify-content-center mt-5">
          <a href="{{url('/')}}" class="app-brand-link gap-2">
           <img src="/assets/img/branding/rapide-logo-transparent.png" style="width: 200px;" />
          </a>
        </div>
        <!-- /Logo -->

        <div class="card-body mt-2 text-center">
          <h5 class="mb-2">Welcome to Rapidé - Pandan Angeles</h5>
          <p class="mb-4">Please sign-in to your account and start the adventure</p>

          <form id="formAuthentication" class="mb-3" action="{{url('/doLogin')}}" method="POST">
             @csrf
            <div class="form-floating form-floating-outline mb-3">
              <input type="text" class="form-control" id="email" name="email-username" placeholder="Enter your email or username" autofocus>
              <label for="email">Email or Username</label>
              @if (Session('error'))
                <p class="text-danger">{{ session('error') }}</p>
              @endif
            </div>
            <div class="mb-3">
              <div class="form-password-toggle">
                <div class="input-group input-group-merge">
                  <div class="form-floating form-floating-outline">
                    <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                    <label for="password">Password</label>
                  </div>
                  <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                </div>
              </div>
            </div>
            <div class="mb-3 d-flex justify-content-between">
              <!-- <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember-me">
                <label class="form-check-label" for="remember-me">
                  Remember Me
                </label>
              </div> -->
              <a href="{{url('auth/forgot-password-basic')}}" class="float-end mb-1">
                <span>Forgot Password?</span>
              </a>
            </div>
            <div class="mb-3">
              <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
            </div>
          </form>

          <p class="text-center">
            <span>New on our platform?</span>
            <a href="{{url('auth/register-basic')}}">
              <span>Create an account</span>
            </a>
          </p>

          <div class="divider my-4">
          </div>

         
        </div>
      </div>
      <!-- /Login -->
      <img alt="mask" src="{{asset('assets/img/illustrations/auth-basic-login-mask-'.$configData['style'].'.png') }}" class="authentication-image d-none d-lg-block" data-app-light-img="illustrations/auth-basic-login-mask-light.png" data-app-dark-img="illustrations/auth-basic-login-mask-dark.png" />
    </div>
  </div>
</div>
@endsection
