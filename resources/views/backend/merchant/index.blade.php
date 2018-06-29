@extends('backend.layouts.app')

@section('head')
    <title>Merchant List | {{ env('APP_NAME') }}</title>
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
<li class="breadcrumb-item active">Merchant</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include('inc.success-notif')
            @can ('create-merchant')
                <div class="row">
                    <div class="col-xs-12">
                        <div class="m-b-15">
                            <a href="{{ route('backend.merchant.create') }}" id="add-btn" class="btn btn-primary white">
                                <i class="icon wb-plus" aria-hidden="true"></i> Add Merchant
                            </a>
                        </div>
                    </div>
                </div>
            @endcan
            <table class="table table-bordered table-hover table-striped" cellspacing="0" id="datatable"></table>
        </div>
    </div>
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
            $('#datatable').dataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    method: 'POST',
                    url : '{{ route('backend.merchant.data') }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns : [
                    {title: 'Code', data: 'code', name: 'code', defaultContent: '-', class: 'text-center'},
                    {title: 'Merchant Name', data: 'name', name: 'name', defaultContent: '-', class: 'text-center'},
                    {title: 'Type', data: 'type', name: 'type', defaultContent: '-', class: 'text-center'},
                    {title: 'Active', data: 'status', name: 'status', defaultContent: '-', class: 'text-center'},
                    { title: 'Action', data: 'action', name: 'action', searchable: false, orderable: false, class: 'text-center' }
                ],
                initComplete: function() {
                    $('.tl-tip').tooltip();
                    @if (count($errors) > 0)
                        jQuery("html, body").animate({
                        scrollTop: $('#add-form').offset().top - 100
                    }, "slow");
                    @endif
                }
            });

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