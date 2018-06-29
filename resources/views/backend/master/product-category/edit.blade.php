@extends('backend.layouts.app')

@section('head')
    <title>Edit Product Category | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/select2/select2.css') }}">
@endsection

@section('title')
    Edit Product Category
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active"><a href="#">Master Data</a></li>
    <li class="breadcrumb-item active"><a href="{{ route('backend.master.product-category.index') }}">Product Category</a></li>
    <li class="breadcrumb-item active">Edit Product Category</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include('inc.error-list')

            <form action="{{ route('backend.master.product-category.update', $productCategory->id) }}" method="POST">
                @method('put')
                @csrf

                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="code" class="control-label">Code <span class="text-danger">(Optional)</span></label>
                        <input id="code" type="text" class="form-control" name="code" value="{{ old('code', $productCategory->code) }}">
                    </div>

                    <div class="form-group col-md-8">
                        <label for="name" class="control-label">Name</label>
                        <input id="name" type="text" class="form-control" name="name" value="{{ old('name', $productCategory->name) }}" required>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="parent" class="control-label">Parent <span class="text-danger">(Optional)</span></label>
                        <select id="parent" name="parent" class="form-control">
                            <option value=""></option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <a href="{{ route('backend.master.product-category.index') }}" class="btn btn-danger">Back</a>
                        <button type="submit" class="btn btn-primary pull-right">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('global/vendor/select2/select2.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            function formatResult(node) {
                var $result = $('<span style="padding-left:' + (20 * node.level) + 'px;">' + node.text + '</span>');
                return $result;
            };

            $("#parent").select2({
                placeholder: "Select Parent",
                width: "100%",
                allowClear: true,
                data: JSON.parse('{!! $productCategories !!}'),
                formatSelection: function(item) {
                    return item.text
                },
                formatResult: function(item) {
                    return item.text
                },
                templateResult: formatResult,
            });
        });
    </script>
@endsection