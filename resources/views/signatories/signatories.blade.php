@extends('adminlte::page')

@section('title', 'Pagadian MTFRU')

@section('content_header')


@stop

@section('content')

    <div id="app">
        <signatories-component></signatories-component>
    </div>

@stop

@extends('layout.footer')


@section('js')
    <script src="{{ asset('js/app.js') }}" defer></script>
@stop
