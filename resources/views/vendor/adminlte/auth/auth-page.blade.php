@extends('adminlte::master')

@php( $dashboard_url = View::getSection('dashboard_url') ?? config('adminlte.dashboard_url', 'home') )

@if (config('adminlte.use_route_url', false))
    @php( $dashboard_url = $dashboard_url ? route($dashboard_url) : '' )
@else
    @php( $dashboard_url = $dashboard_url ? url($dashboard_url) : '' )
@endif

@section('adminlte_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body'){{ ($auth_type ?? 'login') . '-page' }}@stop

@section('body')

    <style type="text/css">

        .card-header {
            text-align: center;
        }

        .card-header img {
            width: 210px;
        }

    </style>

    <div class="login-header mb-3" style="text-align: center; color: white">
        <style>
            .login-header h3 span {
                color: rgba(255, 120, 31, 1);
            }
        </style>
        <span style="font-size: 40px">Welcome to</span>
        <h3 style="font-size: 40px"><span>M</span>otorized <span>T</span>ricycle <span>O</span>perator's <span>P</span>ermit</h3>
    </div>


    <div class="{{ $auth_type ?? 'login' }}-box">

        {{-- Card Box --}}
        <div class="card {{ config('adminlte.classes_auth_card', 'card-outline card-primary') }}">

            {{-- Card Header --}}
            @hasSection('auth_header')
                <div class="card-header text-center {{ config('adminlte.classes_auth_header', '') }}">
                    <img style="width: 250px;" class="mb-4" src="{{ asset('image/icons/smartservelogo.jpg') }}" alt="logo">

                    <h2 class="card-title float-none font-weight-bold">
                        Login to your account
                    </h2>
                </div>
            @endif

            {{-- Card Body --}}
            <div class="card-body {{ $auth_type ?? 'login' }}-card-body {{ config('adminlte.classes_auth_body', '') }}">
                @yield('auth_body')
            </div>

            {{-- Card Footer --}}
            @hasSection('auth_footer')
                <div class="card-footer {{ config('adminlte.classes_auth_footer', '') }}">
                    @yield('auth_footer')
                </div>
            @endif

        </div>

    </div>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
@stop
