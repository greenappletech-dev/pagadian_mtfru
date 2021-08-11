@extends('adminlte::page')

@section('title', 'Pagadian MTFRU')

@section('content_header')


@stop

@section('content')

    <div id="app">
        <mtop_edit-component
            :mtop_application="{{ $mtop_application }}"
            :mtop_charges="{{ $mtop_charges }}"
            :total_charges="{{ $total_charges }}"
            :tricycle_current_record = "{{ $tricycle_current_record }}"
        >
        </mtop_edit-component>
    </div>

@stop

@extends('layout.footer')


@section('js')
    <script src="{{ asset('js/app.js') }}" defer></script>
@stop

