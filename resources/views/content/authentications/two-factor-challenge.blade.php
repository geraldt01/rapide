@php
$configData = Helper::appClasses();
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Two-Factor Verification - Pages')

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
@endsection

@section('content')
<div class="position-relative">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-4">

      <div class="card p-2">
        <!-- Logo -->
        <div class="app-brand justify-content-center mt-5">
          <a href="{{url('/')}}" class="app-brand-link gap-2">
            <img src="/assets/img/branding/rapide-logo-transparent.png" style="width: 200px;" />
          </a>
        </div>
        <!-- /Logo -->

        <div class="card-body mt-2" id="codeStep">
          <h4 class="mb-2">Two-factor verification</h4>
          <p class="mb-4">Enter the 6-digit code from your authenticator app.</p>

          @if ($errors->has('code'))
            <p class="text-danger">{{ $errors->first('code') }}</p>
          @endif

          <form id="twoFactorChallengeForm" action="{{ route('2fa.challenge.verify') }}" method="POST">
            @csrf
            <div class="mb-3">
              <div class="auth-input-wrapper d-flex align-items-center justify-content-sm-between" id="challengePinWrapper">
                <input type="tel" inputmode="numeric" class="form-control auth-input text-center h-px-50 mx-1 my-2" maxlength="1" autofocus>
                <input type="tel" inputmode="numeric" class="form-control auth-input text-center h-px-50 mx-1 my-2" maxlength="1">
                <input type="tel" inputmode="numeric" class="form-control auth-input text-center h-px-50 mx-1 my-2" maxlength="1">
                <input type="tel" inputmode="numeric" class="form-control auth-input text-center h-px-50 mx-1 my-2" maxlength="1">
                <input type="tel" inputmode="numeric" class="form-control auth-input text-center h-px-50 mx-1 my-2" maxlength="1">
                <input type="tel" inputmode="numeric" class="form-control auth-input text-center h-px-50 mx-1 my-2" maxlength="1">
              </div>
              <input type="hidden" name="code" id="challengeCodeInput" />
            </div>
            <button class="btn btn-primary d-grid w-100 mb-3" type="submit">Verify</button>
          </form>

          <div class="text-center">
            <a href="javascript:void(0);" id="useRecoveryCodeLink">Use a recovery code instead</a>
          </div>
        </div>

        <div class="card-body mt-2 d-none" id="recoveryStep">
          <h4 class="mb-2">Use a recovery code</h4>
          <p class="mb-4">
            Enter one of the recovery codes you saved when you set up two-factor authentication.
            Each code can only be used once.
          </p>

          @if ($errors->has('recovery_code'))
            <p class="text-danger">{{ $errors->first('recovery_code') }}</p>
          @endif

          <form id="recoveryCodeForm" action="{{ route('2fa.challenge.verify') }}" method="POST">
            @csrf
            <div class="mb-3">
              <input type="text" class="form-control text-center" name="recovery_code" placeholder="XXXXX-XXXXX" autocomplete="off">
            </div>
            <button class="btn btn-primary d-grid w-100 mb-3" type="submit">Verify recovery code</button>
          </form>

          <div class="text-center">
            <a href="javascript:void(0);" id="useCodeLink">Use your authenticator app instead</a>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var wrapper = document.getElementById('challengePinWrapper');
  var hidden = document.getElementById('challengeCodeInput');
  var pins = Array.prototype.slice.call(wrapper.children);

  function syncHiddenField() {
    hidden.value = pins.map(function (p) { return p.value; }).join('');
  }

  pins.forEach(function (pin, index) {
    pin.addEventListener('input', function () {
      pin.value = pin.value.replace(/[^0-9]/g, '').slice(0, 1);
      if (pin.value && pins[index + 1]) {
        pins[index + 1].focus();
      }
      syncHiddenField();
    });
    pin.addEventListener('keydown', function (e) {
      if (e.key === 'Backspace' && !pin.value && pins[index - 1]) {
        pins[index - 1].focus();
      }
    });
    pin.addEventListener('paste', function (e) {
      var digits = (e.clipboardData.getData('text') || '').replace(/[^0-9]/g, '').split('');
      if (!digits.length) return;
      e.preventDefault();
      pins.forEach(function (p, i) { p.value = digits[i] || ''; });
      syncHiddenField();
      var next = pins[Math.min(digits.length, pins.length - 1)];
      if (next) next.focus();
    });
  });

  var codeStep = document.getElementById('codeStep');
  var recoveryStep = document.getElementById('recoveryStep');

  document.getElementById('useRecoveryCodeLink').addEventListener('click', function () {
    codeStep.classList.add('d-none');
    recoveryStep.classList.remove('d-none');
  });
  document.getElementById('useCodeLink').addEventListener('click', function () {
    recoveryStep.classList.add('d-none');
    codeStep.classList.remove('d-none');
    pins[0].focus();
  });

  @if ($errors->has('recovery_code'))
    codeStep.classList.add('d-none');
    recoveryStep.classList.remove('d-none');
  @endif
});
</script>
@endsection
