@php
    $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : "";
    $primary_lang_details = clientLanguageSettings($shop_id);

    $language = getLangDetails(isset($primary_lang_details['primary_language']) ? $primary_lang_details['primary_language'] : '');
    $language_code = isset($language['code']) ? $language['code'] : '';
    $name_key = $language_code."_name";
@endphp

@extends('client.layouts.client-layout')

@section('title', __('Employees'))

@section('content')

    {{-- Page Title --}}
    <div class="pagetitle">
        <h1>{{ __('Employees')}}</h1>
        <div class="row">
            <div class="col-md-8">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">{{ __('Dashboard')}}</a></li>
                        <li class="breadcrumb-item active">{{ __('Employees')}}</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-4" style="text-align: right;">
                <a href="{{ route('staffs.add') }}" class="btn btn-sm new-amenity btn-primary">
                    <i class="bi bi-plus-lg"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Clients Section --}}
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

            {{-- <div class="col-md-12 mb-2">
                <div class="row">
                    <div class="col-md-6"></div>
                    <div class="col-md-2"></div>
                    <div class="col-md-4">
                        <select name="subscription" id="subscription" class="form-select">
                            <option value=""> -- Filter By Subscriptions -- </option>
                            @if(count($subscriptions) > 0)
                                @foreach ($subscriptions as $subscription)
                                    <option value="{{ $subscription->name }}" {{ ($subscription->name == $filter_id) ? 'selected' : '' }}>{{ $subscription->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div> --}}

            {{-- Clients Card --}}
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped w-100" id="clientsTable">
                                <thead>
                                    <tr>
                                        <th>{{ __('Id')}}</th>
                                        <th>{{ __('Name')}}</th>
                                        <th>{{ __('Email')}}</th>
                                        <th>{{ __('Type')}}</th>
                                        <th>{{ __('Status')}}</th>
                                        <th class="text-center">{{ __('Actions')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($staffs as $staff)
                                        <tr>
                                            <td>{{ $staff->id }}</td>
                                            <td>{{ $staff->name }} </td>
                                            <td>{{ $staff->email }}</td>
                                            <td>
                                                @if ($staff->type == 0)
                                                    Driver
                                                    @else
                                                    Waiter
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $status = $staff->status;
                                                    $checked = ($status == 1) ? 'checked' : '';
                                                    $checkVal = ($status == 1) ? 0 : 1;
                                                @endphp
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" onchange="changeStatus({{ $checkVal }},{{ $staff->id }})" id="statusBtn" {{ $checked }}>
                                                </div>
                                            </td>

                                            <td class="text-center">
                                                <a href="{{ route('staffs.edit',$staff->id) }}" class=" m-1 btn btn-sm btn-primary">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a onclick="deleteStaff({{ $staff->id }})" class=" m-1 btn btn-sm btn-danger">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="text-center">
                                            <td colspan="6">{{ __('Staffs Not Found!')}}</td>
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

        // Function for Change Status of Client
        function changeStatus(status, id)
        {
            $.ajax({
                type: "POST",
                url: "{{ route('staffs.status') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'status': status,
                    'id': id
                },
                dataType: 'JSON',
                success: function(response)
                {
                    if (response.success == 1)
                    {
                        toastr.success("Status has been Changed SuccessFully");
                    }
                    else
                    {
                        toastr.error("Internal Serve Errors");
                        locattion.reload();
                    }
                }
            });
        }


        // // Function for Change Status of Favourite
        // function addToisFav(is_fav, id)
        // {
        //     $.ajax({
        //         type: "POST",
        //         url: "{{ route('clients.addtofav') }}",
        //         data: {
        //             "_token": "{{ csrf_token() }}",
        //             'status': is_fav,
        //             'id': id
        //         },
        //         dataType: 'JSON',
        //         success: function(response)
        //         {
        //             if (response.success == 1)
        //             {
        //                 toastr.success("Add to Favourite SuccessFully");
        //                 setTimeout(() => {
        //                     window.location.href = "{{ route('clients.list')}}";
        //                 }, 1000);
        //             }
        //             else
        //             {
        //                 toastr.error("Internal Serve Errors");
        //                 location.reload();
        //             }
        //         }
        //     });
        // }


        // // Function for Delete Client
        function deleteStaff(staffID)
        {
            swal({
                title: "Are you sure You want to Delete It ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelClient) =>
            {
                if (willDelClient)
                {
                    $.ajax({
                        type: "POST",
                        url: '{{ route("staffs.destroy") }}',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            'id': staffID,
                        },
                        dataType: 'JSON',
                        success: function(response)
                        {
                            if (response.success == 1)
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


        // // Function for Subscriptions Filter
        // $('#subscription').on('change',function(){
        //     var sub_name = $(this).val();
        //     var redirectRoute = "{{ route('clients.list') }}"+'/'+sub_name;
        //     window.location.href = redirectRoute;
        // })

    </script>
@endsection
