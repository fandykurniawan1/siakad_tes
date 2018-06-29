@extends('backend.layouts.app')

@section('head')
    <title>Edit Merchant | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">
    <link rel='stylesheet' href='{{ asset('global/vendor/switchery/switchery.min.css') }}'>
    <link rel="stylesheet" href="{{ asset('global/vendor/select2/select2.css') }}">
    <link rel='stylesheet' href='{{ asset('global/vendor/dropify/dropify.min.css') }}'>
    <link rel="stylesheet" href="{{ config('app.url') }}global/vendor/jackocnr-intl-tel-input/css/intlTelInput.css">
@endsection

@section('title')
    Edit Merchant
@endsection

@push('breadcrumb')
<li class="breadcrumb-item active"><a href="{{ route('backend.merchant.index') }}">Merchant</a></li>
<li class="breadcrumb-item active">Edit Merchant</li>
@endpush

@section('content')
    <form class="form-horizontal" id="form" action="{{ route('backend.merchant.update', $merchant->id) }}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('PUT') }}

        <div class="panel panel-bordered">
            <div class="panel-heading">
                <h3 class="panel-title">Edit Merchant</h3>
            </div>
            <div class="panel-body">
                @include('inc.error-list')

                <div class="row">
                    <div class="form-group col-md-5">
                        <label>Image <span class="text-danger">(Optional)</span></label>
                        <input type="file" name="image" id="input-file-max-fs" data-plugin="dropify" data-id="{{ optional($merchant->photo()->first())->id }}" data-max-file-size="2M" data-allowed-file-extensions="png jpg jpeg bmp gif" data-default-file="{{ $merchant->photo }}">
                    </div>
                    <div class="form-group col-md-7">
                        <label class="control-label">Code <span class="text-danger">(Optional)</span></label>
                        <input type="text" name="code" class="form-control" value="{{ old('code', $merchant->code) }}">
                    </div>
                    <div class="form-group col-md-7">
                        <label class="control-label">Merchant Name</label>
                        <input type="text" name="merchant_name" id="table" class="form-control" value="{{ old('merchant_name', $merchant->name) }}" required>
                    </div>
                    <div class="form-group col-md-2">
                        <label class="control-label">Type:</label>
                        <select name="type" class="form-control select2 input-type" required>
                            <option value="Default" {{ (old('type', $merchant->type) == 'Default') ? 'selected'  : '' }}>Default</option>
                            <option value="Money Changer" {{ old('type', $merchant->type) == 'Money Changer' ? 'selected' : '' }}>Money Changer</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="control-label">Phone <span class="text-danger">(Optional)</span></label>
                        <input type="text" id="phone" class="form-control" name="phone" value="{{ old('phone', $merchant->phone) }}">
                    </div>
                    <div class="form-group col-md-2">
                        <label class="control-label">Merchant Email <span class="text-danger">(Optional)</span></label>
                        <input type="text" name="merchant_email" class="form-control latitude" value="{{ old('merchant_email', $merchant->email) }}">
                    </div>
                </div>
                <div class='row'>
                    <div class="form-group col-md-12">
                        <label class="control-label">Address <span class="text-danger">(Optional)</span></label>
                        <textarea class="form-control" name="address">{{ old('address', $merchant->address) }}</textarea>
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Country <span class="text-danger">(Optional)</span></label>
                        <select class='form-control select2' name='country_id'>
                            <option></option>
                            @foreach ($countries as $country)
                                <option value='{{ $country->id }}' {{ old('country_id', $merchant->country_id) == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-4">
                        <label class="control-label">Province<span class="text-danger">(Optional)</span></label>
                        <select name="province_id" class="form-control select2" id="province">
                            <option></option>
                            @foreach($provinces as $province)
                                <option value="{{$province->id}}" {{ old('province', $merchant->province_id) == $province->id ? 'selected' : '' }}>{{$province->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-4">
                        <label class="control-label">City<span class="text-danger">(Optional)</span></label>
                        <select name="city_id" class="form-control select2" id="city">
                            <option></option>
                            @foreach($cities as $city)
                                <option value="{{$city->id}}" {{ old('city', $merchant->city_id) == $city->id ? 'selected' : '' }}>{{$city->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label class="control-label">Longitude <span class="text-danger">(Optional)</span></label>
                        <input type="text" name="longitude" class="form-control longitude" value="{{ old('longitude', $merchant->longitude) }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label">Latitude <span class="text-danger">(Optional)</span></label>
                        <input type="text" name="latitude" class="form-control latitude" value="{{ old('latitude', $merchant->latitude) }}">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12 col-lg-6">
                        <label class="control-label">Active</label>
                        <br />
                        <input type="checkbox" name="status" class="js-switch" value="1" data-color="#ffde17" data-plugin="switchery" {{ old('status', $merchant->status == 'Active' ? 'checked' : '') }}/>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <a href="{{ route('backend.merchant.index') }}" class="btn btn-danger">Cancel</a>
            <button type="submit" class="btn btn-primary pull-right">Update</button>
        </div>
    </form>
@endsection

@section('footer')
    @include ('inc.confirm-delete-modal')

    <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
    <script src='{{ asset('global/vendor/switchery/switchery.min.js') }}'></script>
    <script src='{{ asset('global/js/Plugin/switchery.min.js') }}'></script>
    <script src="{{ asset('global/vendor/select2/select2.min.js') }}"></script>
    <script src='{{ asset('global/vendor/dropify/dropify.min.js') }}'></script>
    <script src="{{ config('app.url') }}global/vendor/jackocnr-intl-tel-input/js/intlTelInput.js"></script>
    <script src="{{ config('app.url') }}global/vendor/jackocnr-intl-tel-input/js/utils.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.select2').select2({
                'placeholder' : 'Choose One',
                'allowClear' : true,
                'width' : '100%'
            });

            $('.dropify').dropify();

            @include('inc.dropify-remove-img')
        });
    </script>
@endsection