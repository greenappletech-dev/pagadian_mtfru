@extends('adminlte::page')

@section('title', 'HRISystem')

@section('content_header')


@stop

@section('content')

    <div id="app">
        <charges-component></charges-component>
    </div>

@stop

@extends('layout.footer')


@section('js')
    <script src="{{ asset('js/app.js') }}" defer></script>
@stop

