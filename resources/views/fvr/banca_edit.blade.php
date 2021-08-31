@extends('adminlte::page')

@section('title', 'Pagadian MTFRU')

@section('content_header')


@stop

@section('content')

    <div id="app">
        <banca_edit-component
            :banca="{{ $banca }}"
            :auxiliary_engine="{{ $auxiliary_engine }}"
        ></banca_edit-component>
    </div>

@stop

@extends('layout.footer')


@section('js')
    <script src="{{ asset('js/app.js') }}" defer></script>
@stop

