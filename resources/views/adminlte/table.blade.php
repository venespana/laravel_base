@extends('adminlte::containers.box')

@if(isset($title))
    @section('title_postfix', "| {$title}")

    @section('content_header')
        <h1>{{ $title }}</h1>
    @endsection
@endif

@section('box-header', $header)

@section('box-content')
    {!! $table !!}
@endsection