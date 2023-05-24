@extends('admin.layouts.admin-layout')

@section('title',"Import - Export")

@section('content')

    {{-- Page Title --}}
    <div class="pagetitle">
        <h1>{{ __('Import / Export') }}</h1>
        <div class="row">
            <div class="col-md-8">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('Import / Export') }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    {{-- Clients Section --}}
    <section class="section dashboard">
        <div class="row">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3>{{ __('Import and Export') }}</h3>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <a class="btn btn-primary me-2" href="{{ asset('public/csv_demo/category_item_demo.xlsx') }}" download><i class="fa fa-download"></i> {{ __('Download Demo CSV') }}</a>
                                        <form id="exportForm" action="{{ route('admin.export.data') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="shop_id" id="shop_id">
                                            <a onclick="exportData()" class="btn btn-secondary"><i class="fa-solid fa-file-export"></i> {{ __('Export CSV') }}</a>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form action="{{ route('admin.import.data') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="shop" class="form-label">{{ __('Client Shop') }}</label>
                                        <select name="shop" id="shop" class="form-select form-control {{ ($errors->has('shop')) ? 'is-invalid' : '' }}">
                                            <option value="">Choose Shop</option>
                                            @if(count($shops) > 0)
                                                @foreach ($shops as $shop)
                                                    <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @if($errors->has('shop'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('shop') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="import" class="form-label">{{ __('Import Data') }}</label>
                                        <input type="file" name="import" id="import" class="form-control {{ ($errors->has('import')) ? 'is-invalid' : '' }}">
                                        @if($errors->has('import'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('import') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <button class="btn btn-sm btn-success">{{ __('Import') }}</button>
                                        <a class="btn btn-danger btn-sm" onclick="deleteShopData()">{{ __('Delete Category & Items') }}</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection

@section('page-js')

    <script type="text/javascript">

        $('#shop').select2();

        // Success Message
        @if (Session::has('success'))
            toastr.success('{{ Session::get('success') }}')
        @endif

        // Error Message
        @if (Session::has('error'))
            toastr.error('{{ Session::get('error') }}')
        @endif


        // Function for Delete Shop Data
        function deleteShopData()
        {
            var shopID = $('#shop :selected').val();

            if(shopID == '')
            {
                toastr.error('Please Select Shop!');
                return false;
            }
            else
            {

                swal({
                    title: "Are you sure You want to Delete It ?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelData) =>
                {
                    if (willDelData)
                    {
                        $.ajax({
                            type: "POST",
                            url: "{{ route('client.delete.data') }}",
                            data: {
                                "_token" : "{{ csrf_token() }}",
                                "shop_id" : shopID,
                            },
                            dataType: "JSON",
                            success: function (response)
                            {
                                if(response.success == 1)
                                {
                                    swal(response.message, {
                                        icon: "success",
                                    });

                                    setTimeout(() => {
                                        location.reload();
                                    }, 1200);
                                }
                                else
                                {
                                    toastr.error(response.message);
                                }
                            }
                        });
                    }
                    else
                    {
                        swal("Cancelled", "", "error");
                    }
                });
            }
        }


        // Function for Submit Export Form
        function exportData()
        {
            shopID = $('#shop_id').val();
            if(shopID == '')
            {
                toastr.error("Please Select Shop to Export Data!");
                return false;
            }
            else
            {
                $('#exportForm').submit();
            }
        }

        // Paste Shop ID in Export Form
        $('#shop').on('change',function()
        {
            var shopID = $(this).val();
            $('#shop_id').val(shopID);
        });

    </script>

@endsection
