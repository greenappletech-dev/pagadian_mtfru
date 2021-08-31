@extends('adminlte::page')

@section('title', 'Pagadian MTFRU')

@section('content_header')


@stop

@section('content')

    <div id="app">
        <fvr_renewal-component
            :fvr_application = "{{ $prev_fvr_application }}"
            :fvr_auxiliary_engine  = "{{ $prev_auxiliary }}"
            :fvr_application_charges = "{{ $prev_charges }}"
            :total_charges = "{{$total_charges}}"
        ></fvr_renewal-component>
    </div>

@stop

@extends('layout.footer')


@section('js')
    <script src="{{ asset('js/app.js') }}" defer></script>
@stop

