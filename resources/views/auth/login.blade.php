@extends('layouts.minimal')
@section('title')
Login
@endsection
@section('content')
<div class="d-flex flex-column" style="min-height: 100vh">
<div class="flex-fill">
  <div align="center" class="p-5">
    @php
      $loginLogo = \ProcessMaker\Models\Setting::getLogin();
    @endphp
    <img src={{$loginLogo}}>
  </div>

  <div class="row">
    <div class="col-md-6 offset-md-3">
      <div class="card card-body">
        <form method="POST" class="form" action="{{ route('login') }}">
        @if (session()->has('login-error'))
          <div class="alert alert-danger">{{ session()->get('login-error')}}</div>
          @endif
          <div class="form-group">
            <label for="username">{{ __('Username') }}</label>
            <div>
              <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required>
              @if ($errors->has('username'))
                <span class="invalid-feedback">
                  <strong>{{ $errors->first('username') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="form-group">
            <label for="password">{{ __('Password') }}</label>
            <div class="">
              <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
              @if ($errors->has('password'))
                <span class="invalid-feedback">
                  <strong>{{ $errors->first('password') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="form-check">
            <label class="form-check-label">
          <input class="form-check-input" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
          {{ __('Remember me') }}</label>
          </div>
          <div class="form-group">
            <button type="submit" name="login" class="btn btn-success btn-block text-uppercase" dusk="login">{{ __('Log In') }}</button>
          </div>
          <div class="form-group">
              <a href="{{ route('password.request') }}">
                {{ __('Forgot Password?') }}
              </a>
          </div>
          @if(isset($footer))
          {!! $footer !!}
         @endif
        </form>
      </div>

    </div>


  </div>
</div>

@php
  $loginFooterSetting = \ProcessMaker\Models\Setting::byKey('login-footer');
@endphp
@if ($loginFooterSetting)
  <div>{!! $loginFooterSetting->config['html'] !!}</div>
@endif

@endsection
@section('css')
  <style media="screen">
  .formContainer {
      width:504px;
  }
  .formContainer .form {
    margin-top:85px;
    text-align: left
  }
  </style>

@endsection
