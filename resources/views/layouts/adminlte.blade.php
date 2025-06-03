@extends('adminlte::page')

@section('title', config('app.name'))

@section('content_header')
    @yield('header')
@stop

@section('content')
    @yield('content')
@stop

@section('footer')
    <div class="float-right d-none d-sm-block">
        <!-- <b>Version</b> 1.0.0 -->
    </div>
    <strong>Copyright &copy; {{ date('Y') }} <a href="#">Olaniwun Management System</a>.</strong> All rights reserved.
@stop

@section('css')
    @stack('styles')
@stop

@section('js')
    @stack('scripts')
@stop
