@extends('backend.layouts.app')

@section('head')
    <title>Email Template | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/select2/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/summernote/summernote.css') }}">
@endsection

@section('title')
    Email Template
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active"><a href="{{ route('backend.email.template.index') }}">Vendor</a></li>
    <li class="breadcrumb-item active">Email Template</li>
@endpush

@section('content')
    <div class="panel panel-bordered">
        <div class="panel-heading">
            <h3 class="panel-title">Edit Email Template</h3>
        </div>
        <div class="panel-body">

            @include('inc.error-list')

            <form class="form-horizontal" id="form" action="{{ route('backend.email.template.update', $template->id) }}" method="POST">
                {!! csrf_field() !!}
                {!! method_field('PUT') !!}
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="control-label">Name:</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $template->name) }}" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label">Type:</label>
                        <select name="type" class="form-control select2" required>
                            <option value="" selected>Select Email Template Type</option>
                            <option value="registration" {{ old('type', $template->type) == 'registration' ? 'selected' : '' }}>Registration</option>
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="control-label">Content:</label>
                        @include('inc.email-variable', ['type' => 'general'])
                        <textarea class="summernote" name="content" required>{{ old('content', $template->content) }}</textarea>
                    </div>
                    <div class="form-group col-md-12">
                        <a href="{{ route('backend.email.template.index') }}" class="btn btn-danger">Back</a>
                        <button type="submit" class="btn btn-primary pull-right">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('global/vendor/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('global/vendor/summernote/summernote.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.select2').select2({
                'placeholder' : 'Select Email Template Type',
                'allowClear' : true,
                'width' : '100%'
            });

            $('.summernote').summernote({});
        });
    </script>
@endsection