@extends('backend.layouts.app')

@section('head')
    <title>Dashboard | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/flag-icon-css/flag-icon.css') }}">
@endsection

@section('title')
    Dashboard
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endpush

@section('content')
@endsection

@section('footer')
@endsection