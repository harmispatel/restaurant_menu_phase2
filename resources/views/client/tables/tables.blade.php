@php
    $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : "";
    $primary_lang_details = clientLanguageSettings($shop_id);

    $language = getLangDetails(isset($primary_lang_details['primary_language']) ? $primary_lang_details['primary_language'] : '');
    $language_code = isset($language['code']) ? $language['code'] : '';
    $name_key = $language_code."_name";

    $client_settings = getClientSettings();
    
@endphp

@extends('client.layouts.client-layout')

@section('title', __('Tables'))

@section('content')

{{-- Page Title --}}
<div class="pagetitle">
    <h1>{{ __('Tables')}}</h1>
    <div class="row">
        <div class="col-md-8">
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">{{ __('Dashboard')}}</a></li>
                    <li class="breadcrumb-item active">{{ __('Tables')}}</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-4" style="text-align: right;">
            <a href="{{ route('tables.create') }}" class="btn btn-sm new-amenity btn-primary">
                <i class="bi bi-plus-lg"></i>
            </a>
        </div>
    </div>
</div>

{{-- Tables Section --}}
<section class="section dashboard">
    <div class="row">
        {{-- Error Message Section --}}
        @if (session()->has('error'))
        <div class="col-md-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        @endif

        {{-- Success Message Section --}}
        @if (session()->has('success'))
        <div class="col-md-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        @endif

        {{-- Tables Card --}}
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <h4 class="me-3 mb-0">{{ __('Enable') }}</h4>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="enable_tables" name="enable_tables" {{ (isset($client_settings['table_enable_status']) && $client_settings['table_enable_status'] == 1)  ? 'checked' : ''}}>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped w-100" id="clientsTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Id')}}</th>
                                    <th>{{ __('Shop Area')}}</th>
                                    <th>{{ __('Table')}}</th>
                                    <th>{{ __('Status')}}</th>
                                    <th class="text-center">{{ __('Actions')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($shopTables as $shopTable)
                                <tr>
                                    <td>{{ $shopTable->id }}</td>
                                    <td>{{ $shopTable->shop_area }} </td>
                                    <td>{{ $shopTable->table_name }}</td>

                                    <td>
                                        @php
                                            $checked = ($shopTable->status == 1) ? 'checked' : '';
                                        @endphp
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" onchange="changeStatus({{ $shopTable->id }})" id="statusBtn" {{ $checked }}>
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ route('tables.edit',$shopTable->id) }}" class=" m-1 btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a onclick="deleteshopTable({{ $shopTable->id }})" class=" m-1 btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr class="text-center">
                                    <td colspan="6">{{ __('Tables Not Found!')}}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

{{-- Custom JS --}}
@section('page-js')
<script type="text/javascript">
    // Function for Change Status of Tables
    function changeStatus(id) {
        $.ajax({
            type: "POST"
            , url: "{{ route('tables.status') }}"
            , data: {
                "_token": "{{ csrf_token() }}"
                , 'id': id
            }
            , dataType: 'JSON'
            , success: function(response) {
                if (response.success == 1) {
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                    location.reload();
                }
            }
        });
    }

    // // Function for Delete Table
    function deleteshopTable(tableId) {
        swal({
                title: "Are you sure You want to Delete It ?"
                , icon: "warning"
                , buttons: true
                , dangerMode: true
            , })
            .then((willDelTable) => {
                if (willDelTable) {
                    $.ajax({
                        type: "POST"
                        , url: '{{ route("tables.destroy") }}'
                        , data: {
                            "_token": "{{ csrf_token() }}"
                            , 'id': tableId
                        , }
                        , dataType: 'JSON'
                        , success: function(response) {
                            if (response.success == 1) {
                                swal(response.message, {
                                    icon: "success"
                                , });
                                setTimeout(() => {
                                    location.reload();
                                }, 1200);
                            } else {
                                toastr.error(response.message);
                            }
                        }
                    });
                } else {
                    swal("Cancelled", "", "error");
                }
            });
    }

    $('#enable_tables').on('change', function(){
        let isChecked = 0;
        if($(this).prop('checked') === true){
            isChecked = 1;
        }
        $.ajax({
            type: "POST"
            , url: "{{ route('tables.enable') }}"
            , data: {
                "_token": "{{ csrf_token() }}"
                , 'isChecked': isChecked
            }
            , dataType: 'JSON'
            , success: function(response) {
                if (response.success == 1) {
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                    location.reload();
                }
            }
        });
    });

</script>
@endsection
