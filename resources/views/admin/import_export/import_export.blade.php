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

            {{-- Instructions --}}
            <div class="col-md-12 mt-1">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title p-0">
                            <h2>Instructions</h2>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <ul style="list-style: none;padding: 0; margin: 0;" class="text-muted">
                                    <li><i class="bi bi-arrow-right-circle text-success"></i> It is mandatory to write the category type in the category.</li>
                                    <li><i class="bi bi-arrow-right-circle text-success"></i> The is_parent_category field is also mandatory and can only have a value of 1 or 0.</li>
                                    <li><i class="bi bi-arrow-right-circle text-success"></i> If your category is a parent category then write 1 in is_parent_category and if there is no parent category then write 0.</li>
                                    <li><i class="bi bi-arrow-right-circle text-success"></i> In category type, there will be only 7 types of category type.</li>
                                    <li><i class="bi bi-arrow-right-circle text-success"></i> Category Types which are as follows :
                                        <ol class="ms-3">
                                            <li>parent_category</li>
                                            <li>product_category</li>
                                            <li>page</li>
                                            <li>link</li>
                                            <li>image_gallary</li>
                                            <li>check_in_page</li>
                                            <li>pdf_category</li>
                                        </ol>
                                    </li>
                                    <li><i class="bi bi-arrow-right-circle text-success"></i> If your category is a parent category, type the parent_category in the category_type field.</li>
                                    <li><i class="bi bi-arrow-right-circle text-success"></i> If your category is a child category or category, type the product_category in the category_type field.</li>
                                    <li><i class="bi bi-arrow-right-circle text-success"></i> And if your category is a child category then it is mandatory to write parent category name in parent_cat_name.</li>
                                    <li><i class="bi bi-arrow-right-circle text-success"></i> And if category is parent and not-child(root) then don't write anything in parent_cat_name leave it blank.</li>
                                    <li><i class="bi bi-arrow-right-circle text-success"></i> If you want to add page, type the page in the category_type field.</li>
                                    <li><i class="bi bi-arrow-right-circle text-success"></i> If you want to add pdf, type the pdf_category in the category_type field.</li>
                                    <li><i class="bi bi-arrow-right-circle text-success"></i> If you want to add Image Gallery, type the image_gallary in the category_type field.</li>
                                    <li><i class="bi bi-arrow-right-circle text-success"></i> If you want to add CheckIn Page, type the check_in_page in the category_type field.</li>
                                    <li><i class="bi bi-arrow-right-circle text-success"></i> If you want to add Link, type the link in the category_type field.</li>
                                    <li><i class="bi bi-arrow-right-circle text-success"></i> And if your category_type is page then write your link in link_url field and if not leave it blank.</li>
                                    <li class="fw-bold"><i class="bi bi-arrow-right-circle text-success"></i> Items will be inserted only if your category is a category or a child category.</li>
                                    <li class="fw-bold"><i class="bi bi-arrow-right-circle text-success"></i> The data in your Excel file should be in the same format as the demo Excel file, so check the demo file first and then after import your excel file.</li>
                                    <li><i class="bi bi-arrow-right-circle text-success"></i> Before Import Your CSV Click on Download Demo CSV Button and Check Demo CSV.</li>
                                    <li><i class="bi bi-arrow-right-circle text-success"></i> Before Import Catalogue Select Client Shop & Delete Old Catalogue.</li>
                                    <li><i class="bi bi-arrow-right-circle text-success"></i> After Delete Catalogue Select Client Shop Then Choose CSV File & Click on Import Button.</li>
                                </ul>
                            </div>
                        </div>
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
