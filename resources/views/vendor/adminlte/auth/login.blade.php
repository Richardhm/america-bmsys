@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@php( $login_url = View::getSection('login_url') ?? config('adminlte.login_url', 'login') )
@php( $register_url = View::getSection('register_url') ?? config('adminlte.register_url', 'register') )
@php( $password_reset_url = View::getSection('password_reset_url') ?? config('adminlte.password_reset_url', 'password/reset') )

@if (config('adminlte.use_route_url', false))
    @php( $login_url = $login_url ? route($login_url) : '' )
    @php( $register_url = $register_url ? route($register_url) : '' )
    @php( $password_reset_url = $password_reset_url ? route($password_reset_url) : '' )
@else
    @php( $login_url = $login_url ? url($login_url) : '' )
    @php( $register_url = $register_url ? url($register_url) : '' )
    @php( $password_reset_url = $password_reset_url ? url($password_reset_url) : '' )
@endif





@section('auth_body')
    <form action="{{ $login_url }}" method="post">
        @csrf
        <fieldset style="border-bottom:1px solid #FFF;border-left:1px solid #FFF;border-right:1px solid #FFF;padding:10px;border-bottom-left-radius: 5px;border-bottom-right-radius: 5px;">
        
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control-person @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" placeholder="{{ __('adminlte::adminlte.email') }}" autofocus>

            

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control-person @error('password') is-invalid @enderror"
                   placeholder="{{ __('adminlte::adminlte.password') }}">

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="row">
            <div class="col-7">
                <div class="icheck-primary" title="{{ __('adminlte::adminlte.remember_me_hint') }}">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label for="remember" style="color:#FFF;">
                        Lembrar-me
                    </label>
                </div>
            </div>

            <div class="col-5">
                <button type=submit class="btn btn-block" style="border-radius:20px;background-color:rgb(1,226,249);color:#000;">
                    Realizar Login
                </button>
            </div>
        </div>
        
        
        <!-- <div class="form-group">
            
            <input type="email" class="form-control-person" placeholder="Email">

        </div>
        <div class="form-group">
            
            <input type="password" class="form-control-person" placeholder="Senha">

        </div>

        <button type=submit class="btn btn-block" style="border-radius:20px;background-color:aquamarine;color:#FFF;">
            Realizar Login
        </button> -->
        </fieldset>

    </form>
@stop

@section('auth_footer')
    {{-- Password reset link --}}
    @if($password_reset_url)
        <!-- <p class="my-0">
            <a href="{{ $password_reset_url }}">
                {{ __('adminlte::adminlte.i_forgot_my_password') }}
            </a>
        </p> -->
    @endif

    {{-- Register link --}}
    @if($register_url)
        <!-- <p class="my-0">
            <a href="{{ $register_url }}">
                {{ __('adminlte::adminlte.register_a_new_membership') }}
            </a>
        </p> -->
    @endif
@stop
