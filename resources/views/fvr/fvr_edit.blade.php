@extends('adminlte::page')

@section('title', 'Pagadian MTFRU')

@section('content_header')


@stop

@section('content')

    <div id="app">
        <fvr_edit-component
            :fvr_application = "{{ $fvr_application }}"
            :banca_master_data = "{{ $banca_master_data }}"
            :fvr_auxiliary_engine  = "{{ $fvr_auxiliary_engine }}"
            :fvr_application_charges = "{{$fvr_application_charges}}"
            :total_charges = "{{$total_charges}}"
        ></fvr_edit-component>
    </div>

@stop

@extends('layout.footer')


@section('js')
    <script src="{{ asset('js/app.js') }}" defer></script>
@stop

