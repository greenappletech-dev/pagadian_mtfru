@extends('adminlte::page')

@section('title', 'Pagadian MTFRU')

@section('content_header')


@stop

@section('content')

    <div id="app">

        <renewal-component
            v-bind:mtop_application="{{ $mtop_application }}"
            v-bind:charges="{{ $charges }}"
            v-bind:total_charges="{{ $total_charges }}"
            v-bind:mtfrb_case_no="{{ $mtfrb_case_no }}"
        >
        </renewal-component>
    </div>

@stop

@extends('layout.footer')


@section('js')
    <script src="{{ asset('js/app.js') }}" defer></script>
@stop

