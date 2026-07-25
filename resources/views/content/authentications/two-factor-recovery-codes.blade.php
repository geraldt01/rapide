@php
$configData = Helper::appClasses();
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Two-Factor Recovery Codes - Pages')

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
@endsection

@section('content')
<div class="position-relative">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-4" style="max-width: 560px;">

      <div class="card p-2">
        <div class="app-brand justify-content-center mt-5">
          <a href="{{url('/')}}" class="app-brand-link gap-2">
            <img src="/assets/img/branding/rapide-logo-transparent.png" style="width: 200px;" />
          </a>
        </div>

        <div class="card-body mt-2">
          <h4 class="mb-2 text-center">Save your recovery codes</h4>
          <p class="mb-4 text-center">
            Two-factor authentication is now on. Store these codes somewhere safe — each one lets you
            sign in <strong>once</strong> if you ever lose access to your authenticator app. They won't be shown again.
          </p>

          <div class="row row-cols-2 g-2 mb-4">
            @foreach ($codes as $code)
              <div class="col">
                <code class="d-block text-center py-2 bg-light rounded">{{ $code }}</code>
              </div>
            @endforeach
          </div>

          <div class="d-flex gap-2 mb-3">
            <button type="button" class="btn btn-outline-primary d-grid w-100" id="copyCodesBtn">Copy codes</button>
            <button type="button" class="btn btn-outline-primary d-grid w-100" id="downloadCodesBtn">Download .txt</button>
          </div>

          <a href="{{ url('/dashboard') }}" class="btn btn-primary d-grid w-100">
            I've saved my codes, continue
          </a>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
var recoveryCodes = @json($codes);

document.getElementById('copyCodesBtn').addEventListener('click', function () {
  navigator.clipboard.writeText(recoveryCodes.join('\n')).then(function () {
    var btn = document.getElementById('copyCodesBtn');
    var original = btn.textContent;
    btn.textContent = 'Copied!';
    setTimeout(function () { btn.textContent = original; }, 1500);
  });
});

document.getElementById('downloadCodesBtn').addEventListener('click', function () {
  var blob = new Blob([recoveryCodes.join('\n') + '\n'], { type: 'text/plain' });
  var link = document.createElement('a');
  link.href = URL.createObjectURL(blob);
  link.download = 'recovery-codes.txt';
  link.click();
  URL.revokeObjectURL(link.href);
});
</script>
@endsection
