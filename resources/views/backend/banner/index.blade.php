@extends('backend.layouts.app')

@section('head')
    <title>Banner | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">
    <link rel='stylesheet' href='{{ asset('global/vendor/switchery/switchery.min.css') }}'>
    <link rel="stylesheet" href="{{ asset('global/vendor/select2/select2.css') }}">
    <link rel='stylesheet' href='{{ asset('global/vendor/dropify/dropify.min.css') }}'>
@endsection

@section('title')
    Banner
@endsection

@push('breadcrumb')
<li class="breadcrumb-item active">Banner</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include('inc.success-notif')
            <form action="{{ route('backend.banner.store') }}" method="POST" enctype="multipart/form-data">
            {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-12'>
                        <h3>Banner Images</h3>
                        <a class='btn btn-primary white add-image-btn' data-name='photos'><i class='icon wb-plus'></i> Add More Banner Images</a>
                    </div>
                    @foreach (range(1, $images->first() ? $images->last()->order : 4) as $i)
                        @php ($image = optional($images->where('order', $i)->first()))
                        <div class='form-group col-md-3'>
                            <input type='file' name='photos[{{ $i }}]' id='input-file-max-fs' data-plugin='dropify' data-id="{{ $image->id }}" data-height='160px' data-max-file-size='2M' data-allowed-file-extensions="png jpg jpeg bmp gif" data-default-file='{{ $image->url }}' />
                        </div>
                    @endforeach
                    <span id='photos-btm'></span>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary pull-right">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer')
    @include ('inc.confirm-delete-modal')

    <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src='{{ asset('global/vendor/summernote/summernote.min.js') }}'></script>
    <script src='{{ asset('global/vendor/dropify/dropify.min.js') }}'></script>
    <script src='{{ asset('global/vendor/select2/select2.min.js') }}'></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.add-image-btn').click(function() {
                var name = $(this).data('name');
                $('#' + name + '-btm').before("<div class='form-group col-md-3'><input type='file' name='" + name + "[]' class='dropify' data-height='160px' data-max-file-size='2M' data-allowed-file-extensions='png jpg jpeg bmp gif' /></div>");
                $('.dropify').dropify();
            });

            @include('inc.dropify-remove-img')
        });
    </script>
@endsection