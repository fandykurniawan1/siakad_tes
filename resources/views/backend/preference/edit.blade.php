@extends('backend.layouts.app')

@section('head')
    <title>Preference | {{ env('APP_NAME') }}</title>
    <link rel='stylesheet' href="{{ asset('global/vendor/dropify/dropify.min.css') }}">
    <link rel='stylesheet' href="{{ asset('global/vendor/summernote/summernote.css') }}">
@endsection

@section('title')
    Preference
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Preference</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include('inc.success-notif')
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('backend.preference.update') }}" method="POST" enctype="multipart/form-data">
                {!! method_field('PUT') !!}
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-6 offset-md-3'>
                        <label class='control-label'>Logo</label>
                        <input type='file' name='logo' id='input-file-max-fs' data-plugin='dropify' data-show-remove='false' data-height='160px' data-max-file-size='2M' data-default-file='{{ $logo }}' />
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class='control-label'>Activation Email Template</label>
                        <div class="alert alert-alt alert-info">
                            Available variable:
                            <ul>
                                <li>%LINK% (Your activation link)</li>
                            </ul>
                        </div>
                        <textarea id="content-create" name="activation_email_template" required>{{ old('activationEmailTemplate', $activationEmailTemplate) }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <button type="Submit" class="btn btn-primary pull-right">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('global/vendor/dropify/dropify.min.js') }}"></script>
    <script src="{{ asset('global/vendor/summernote/summernote.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#content-create').summernote({
                height: 400
            });
        });
    </script>
@endsection