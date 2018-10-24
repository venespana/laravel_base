@extends('adminlte::page')

@section('content')
    <div class="@yield('box-size', 'col-md-12')">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    @yield('box-header')
                </h3>
                @yield('box-header-actions')
            </div>
            <div class="box-body @yield('box-body-class')">
                @yield('box-content')
            </div>
            @yield('box-footer')
        </div>
    </div>
@endsection
