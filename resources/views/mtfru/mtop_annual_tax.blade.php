@extends('adminlte::page')

@section('title', 'Pagadian MTFRU')

@section('content_header')


@stop

@section('content')

    <div id="app">
        <annual-component :annualtax="{{ $annualtax }}" :otherinc="{{ $otherinc }}"></annual-component>
    </div>

@stop

@extends('layout.footer')


@section('js')
    <script src="{{ asset('js/app.js') }}" d\efer></script>
@stop

