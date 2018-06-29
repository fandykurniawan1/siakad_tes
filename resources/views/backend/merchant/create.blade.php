@extends('backend.layouts.app')

@section('head')
    <title>Merchant | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">
    <link rel='stylesheet' href='{{ asset('global/vendor/switchery/switchery.min.css') }}'>
    <link rel="stylesheet" href="{{ asset('global/vendor/select2/select2.css') }}">
    <link rel='stylesheet' href='{{ asset('global/vendor/dropify/dropify.min.css') }}'>
@endsection

@section('title')
    Merchant List
@endsection

@push('breadcrumb')
<li class="breadcrumb-item active"><a href="{{ route('backend.merchant.index') }}">Merchant</a></li>
<li class="breadcrumb-item active">Add New Merchant</li>
@endpush

@section('content')
    <form class="form-horizontal" id="form" action="{{ route('backend.merchant.store') }}" method="POST" enctype="multipart/form-data">
        {!! csrf_field() !!}

        <div class="panel panel-bordered">
            <div class="panel-heading">
                <h3 class="panel-title">Create Merchant</h3>
            </div>
            <div class="panel-body">
                @include('inc.error-list')
                <div class="row">
                    <div class="form-group col-md-5">
                        <label>Image <span class="text-danger">(Optional)</span></label>
                        <input type="file" name="image" class="dropify" data-max-file-size="2M">
                    </div>
                    <div class="form-group col-md-7">
                        <label class="control-label">Code <span class="text-danger">(Optional)</span></label>
                        <input type="text" name="code" class="form-control" value="{{ old('code') }}">
                    </div>
                    <div class="form-group col-md-7">
                        <label class="control-label">Name</label>
                        <input type="text" name="merchant_name" id="table" class="form-control" value="{{ old('merchant_name') }}" required>
                    </div>
                    <div class="form-group col-md-2">
                        <label class="control-label">Type:</label>
                        <select name="type" class="form-control select2 input-type" required>
                            <option value="Default" {{ old('type') == 'Default' ? 'selected' : '' }}>Default</option>
                            <option value="Money Changer" {{ old('type') == 'Money Changer' ? 'selected' : '' }}>Money Changer</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="control-label">Phone <span class="text-danger">(Optional)</span></label>
                        <input type="text" id="phone" class="form-control" name="phone" value="{{ old('phone') }}">
                    </div>
                    <div class="form-group col-md-2">
                        <label class="control-label">Email <span class="text-danger">(Optional)</span></label>
                        <input type="text" name="merchant_email" class="form-control latitude" value="{{ old('merchant_email') }}">
                    </div>
                </div>
                <div class='row'>
                    <div class="form-group col-md-12">
                        <label class="control-label">Address <span class="text-danger">(Optional)</span></label>
                        <textarea class="form-control" name="address">{{ old('address') }}</textarea>
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Country <span class="text-danger">(Optional)</span></label>
                        <select class='form-control select2' name='country_id'>
                            <option></option>
                            @foreach ($countries as $country)
                                <option value='{{ $country->id }}' {{ old('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Province <span class="text-danger">(Optional)</span></label>
                        <select class='form-control select2' name='province_id' id="province">
                            <option></option>
                            @foreach ($provinces as $province)
                                <option value='{{ $province->id }}' {{ old('province_id') == $province->id ? 'selected' : '' }}>{{ $province->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>City <span class="text-danger">(Optional)</span></label>
                        <select class='form-control select2' name='city_id' id="city">
                            <option></option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label class="control-label">Longitude <span class="text-danger">(Optional)</span></label>
                        <input type="text" name="longitude" class="form-control longitude" value="{{ old('longitude') }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label">Latitude <span class="text-danger">(Optional)</span></label>
                        <input type="text" name="latitude" class="form-control latitude" value="{{ old('latitude') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label class="control-label">Active</label><br>
                        <input type="checkbox" name="status" data-color="#ffde17" data-plugin="switchery" value="Active" {{ old('status', 'Active') == 'Active' ? 'checked' : '' }}>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-bordered">
            <div class="panel-heading">
                <h3 class="panel-title">Create User</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="form-group col-sm-4">
                        <label class="control-label">Name</label><br>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                    </div>
                    <div class="form-group col-sm-4">
                        <label class="control-label">Username</label><br>
                        <input type="text" name="username" class="form-control" value="{{ old('username') }}">
                    </div>
                    <div class="form-group col-sm-4">
                        <label class="control-label">Email</label><br>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label class="control-label">Password</label><br>
                        <input type="password" name="password" class="form-control" value="{{ old('password') }}">
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="control-label">Password Confirmation</label><br>
                        <input type="password" name="password_confirmation" class="form-control" value="{{ old('password_confirmation') }}">
                    </div>
                </div>
                {{--<div class="row">--}}
                    {{--<div class="form-group col-sm-6">--}}
                        {{--<label class="control-label">Active</label>--}}
                        {{--<br/>--}}
                        {{--<input type="checkbox" name="active" class="js-switch" value="1" data-color="#ffde17" data-plugin="switchery" {{ old('active', 1) == 1 ? 'checked' : '' }} />--}}
                    {{--</div>--}}
                {{--</div>--}}

                <div class="form-group">
                    <a href="{{ route('backend.merchant.index') }}" class="btn btn-danger">Cancel</a>
                    <button type="submit" class="btn btn-primary pull-right">Submit</button>
                </div>
            </div>
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
    <script type="text/javascript">
        $('.dropify').dropify();
        $(document).ready(function() {
            $('#province').on('change', function(){
                var val = $(this).val();
                $.ajax({
                    url: '{{ url('/api/v1/master/province') }}/'+ val + '/city',
                    success: function(data) {
                        $('.city-data').remove();
                        $.each(data.data, function(i, data){
                            $('#city').append($('<option>', {value: data.id, text: data.name}).addClass('city-data'));
                        });
                        $('#city').select2({
                            'placeholder' : 'Choose One',
                            'allowClear' : true
                        }).change();
                    }
                });
            });

            $('.select2').select2({
                'placeholder' : 'Choose One',
                'allowClear' : true,
                'width' : '100%'
            });
        });
    </script>
@endsection