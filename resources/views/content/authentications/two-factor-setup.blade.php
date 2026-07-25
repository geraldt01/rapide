@php
$configData = Helper::appClasses();
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Set Up Two-Factor Authentication - Pages')

@section('vendor-style')
<!-- Vendor -->
<link rel="stylesheet" href="{{asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css')}}" />
@endsection

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
@endsection

@section('page-script')
<script src="{{asset('assets/js/pages-auth.js')}}"></script>
@endsection

@section('content')
<div class="position-relative">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-4" style="max-width: 640px;">

      <div class="card p-2">
        <!-- Logo -->
        <div class="app-brand justify-content-center mt-5">
          <a href="{{url('/')}}" class="app-brand-link gap-2">
            <img src="/assets/img/branding/rapide-logo-transparent.png" style="width: 200px;" />
          </a>
        </div>
        <!-- /Logo -->

        <div class="card-body mt-2">
          <h4 class="mb-2 text-center">Set up two-factor authentication</h4>
          <p class="mb-4 text-center">
            Your account requires an authenticator app for extra security.
            This is a one-time setup — it takes about two minutes.
          </p>

          @if ($errors->any())
            <div class="alert alert-danger">
              @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
              @endforeach
            </div>
          @endif

          <div class="row g-4">
            <!-- Step-by-step instructions -->
            <div class="col-12">
              <h6 class="mb-3">Follow these steps</h6>
              <ol class="ps-3 mb-0">
                <li class="mb-2">
                  Install an authenticator app on your phone if you don't already have one —
                  <strong>Google Authenticator</strong>, <strong>Microsoft Authenticator</strong>, or <strong>Authy</strong> all work.
                </li>
                <li class="mb-2">
                  Open the app and tap the <strong>"+"</strong> (add account) button, then choose
                  <strong>"Scan a QR code"</strong>.
                </li>
                <li class="mb-2">
                  Point your phone's camera at the QR code below until it's recognized.
                </li>
                <li class="mb-2">
                  The app will start showing a new <strong>6-digit code</strong> every 30 seconds.
                  Type the current code into the box below and confirm.
                </li>
              </ol>
            </div>

            <!-- QR code -->
            <div class="col-12 text-center">
              <div class="d-inline-block p-3 bg-white rounded border">
                {!! $qrCodeSvg !!}
              </div>
              <p class="mt-3 mb-1 text-muted">Can't scan the code? Enter this key manually in your app instead:</p>
              <code class="d-inline-block px-3 py-2 bg-light rounded fs-6" style="letter-spacing: 2px;">{{ $secretKey }}</code>
            </div>

            <!-- Video tutorial -->
            <div class="col-12">
              <h6 class="mb-3">Prefer to watch it?</h6>
              <div class="ratio ratio-16x9 rounded overflow-hidden">
                <iframe
                  src="https://www.youtube.com/embed/BfPgICzq8VU"
                  title="How to scan a QR code with an authenticator app"
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                  allowfullscreen
                  referrerpolicy="strict-origin-when-cross-origin"
                ></iframe>
              </div>
              <p class="text-muted small mt-2 mb-0">
                General walkthrough of scanning a QR code into an authenticator app — the on-screen app may look
                slightly different from ours, but the scan-and-enter-the-code steps are the same.
              </p>
            </div>

            <!-- Confirmation form -->
            <div class="col-12">
              <hr class="my-1">
              <h6 class="mt-3 mb-3">Enter the 6-digit code from your app</h6>
              <form id="twoFactorSetupForm" action="{{ route('2fa.setup.confirm') }}" method="POST">
                @csrf
                <div class="mb-3">
                  <div class="auth-input-wrapper d-flex align-items-center justify-content-sm-between numeral-mask-wrapper" id="setupPinWrapper">
                    <input type="tel" inputmode="numeric" class="form-control auth-input text-center h-px-50 mx-1 my-2" maxlength="1" autofocus>
                    <input type="tel" inputmode="numeric" class="form-control auth-input text-center h-px-50 mx-1 my-2" maxlength="1">
                    <input type="tel" inputmode="numeric" class="form-control auth-input text-center h-px-50 mx-1 my-2" maxlength="1">
                    <input type="tel" inputmode="numeric" class="form-control auth-input text-center h-px-50 mx-1 my-2" maxlength="1">
                    <input type="tel" inputmode="numeric" class="form-control auth-input text-center h-px-50 mx-1 my-2" maxlength="1">
                    <input type="tel" inputmode="numeric" class="form-control auth-input text-center h-px-50 mx-1 my-2" maxlength="1">
                  </div>
                  <input type="hidden" name="code" id="setupCodeInput" />
                </div>
                <button class="btn btn-primary d-grid w-100" type="submit">Confirm and turn on two-factor authentication</button>
              </form>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var wrapper = document.getElementById('setupPinWrapper');
  var hidden = document.getElementById('setupCodeInput');
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
});
</script>
@endsection
