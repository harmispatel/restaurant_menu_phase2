@php
$shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : "";
    $primary_lang_details = clientLanguageSettings($shop_id);

    $language = getLangDetails(isset($primary_lang_details['primary_language']) ? $primary_lang_details['primary_language'] : '');

    $language_code = isset($language['code']) ? $language['code'] : '';
    $name_key = $language_code."_name";

    $client_settings = getClientSettings();

    $emai_req = isset($client_settings['grading-email-required']) ?  $client_settings['grading-email-required'] : '';
    $emailChecked = ($emai_req == 1) ? 'checked' : '';
    $emailCheckVal = ($emai_req == 1) ? 0 : 1;
@endphp

@extends('client.layouts.client-layout')

@section('title', __('Grading'))

@section('content')

    {{-- Tags Edit Modal --}}
    <div class="modal fade" id="editRateSerivceModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editRateSerivceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRateSerivceModalLabel">{{ __('Edit Grading')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="rate_service_edit_div">
                </div>
                <div class="modal-footer">
                    <a class="btn btn-sm btn-success" onclick="updateRateService()">{{ __('Update') }}</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Tags Add Modal --}}
    <div class="modal fade" id="addRateSerivceModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addRateSerivceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRateSerivceModalLabel">{{ __('New Grading')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addRateServiceForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name" class="form-label">{{ __('Name') }}</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-success" id="saveRateService" onclick="saveRateService()">{{ __('Save') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Page Title --}}
    <div class="pagetitle">
        <h1>{{ __('Grading')}}</h1>
        <div class="row">
            <div class="col-md-8">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active"><a>{{ __('Grading')}}</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

     {{-- Tags Section --}}
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

            <div class="col-md-12 mb-3 text-end">
                <a class="btn btn-sm btn-primary" id="NewRateServiceBtn" data-bs-toggle="modal"  data-bs-target="#addRateSerivceModal"><i class="fa fa-plus"></i></a>
            </div>

            {{-- Tags Card --}}
            <div class="col-md-12">
            <div class="card">
            <div class="card-title p-3 d-flex m-0">
                        <h3 class="mb-0 me-2">{{ __('Email Required?') }}</h3>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" onchange="emailReqChange({{ $emailCheckVal }})"  id="statusBtn" {{ $emailChecked }}>
                        </div>
                    </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped w-100" id="tagsTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Id')}}</th>
                                    <th>{{ __('Name')}}</th>
                                    <th>{{ __('Status')}}</th>
                                    <th>{{ __('Actions')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rateServices as $rateService)
                                    @php
                                        $rate_name = (isset($rateService->$name_key) && !empty($rateService->$name_key)) ? $rateService->$name_key : "";
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{  $rate_name }}</td>
                                        <td>
                                            @php
                                                $status = $rateService->status;
                                                $checked = ($status == 1) ? 'checked' : '';
                                                $checkVal = ($status == 1) ? 0 : 1;
                                            @endphp
                                            <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" onchange="changeStatus({{ $checkVal }},{{ $rateService->id }})" id="statusBtn" {{ $checked }}>
                                            </div>
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" onclick="editRateService({{ $rateService->id }})">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a onclick="deleteRateService({{ $rateService->id }})" class=" m-1 btn btn-sm btn-danger">
                                                    <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="text-center">
                                        <td colspan="4">
                                        {{ __('Rate Service Not Found!')}}
                                        </td>
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


        // Reset Modal & Form
        $('#NewRateServiceBtn').on('click',function(){

            // Reset NewCategoryForm
            $('#addRateServiceForm').trigger('reset');

            // Remove Validation Class
            $('#addRateServiceForm #tag_name').removeClass('is-invalid');

            // Clear all Toastr Messages
            toastr.clear();
        });

        // Function for Save Tag
        function saveRateService()
        {
            const myFormData = new FormData(document.getElementById('addRateServiceForm'));

            // Remove Validation Class
            $('#addRateServiceForm #name').removeClass('is-invalid');

            // Clear all Toastr Messages
            toastr.clear();

            $.ajax({
                type: "POST",
                url: "{{ route('rate.services.save') }}",
                data: myFormData,
                dataType: "JSON",
                contentType: false,
                cache: false,
                processData: false,
                success: function (response)
                {
                    if(response.success == 1)
                    {
                        $('#addRateServiceForm').trigger('reset');
                        $('#addRateSerivceModal').modal('hide');
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                    else
                    {
                        $('#addRateServiceForm').trigger('reset');
                        $('#addRateSerivceModal').modal('hide');
                        toastr.error(response.message);
                    }
                },
                error: function(response)
                {
                    // All Validation Errors
                    const validationErrors = (response?.responseJSON?.errors) ? response.responseJSON.errors : '';

                    if (validationErrors != '')
                    {
                        // Name Error
                        var nameError = (validationErrors.name) ? validationErrors.name : '';
                        if (nameError != '')
                        {
                            $('#addRateServiceForm #name').addClass('is-invalid');
                            toastr.error(nameError);
                        }
                    }
                }
            });
        }


        // Function for Edit Tag
        function editRateService(rateServiceID)
        {
            // Reset All Form
            $('#editRateSerivceModal #rate_service_edit_div').html('');

            // Clear all Toastr Messages
            toastr.clear();

            $.ajax({
                type: "POST",
                url: "{{ route('rate.services.edit') }}",
                dataType: "JSON",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'id': rateServiceID,
                },
                success: function(response)
                {
                    if (response.success == 1)
                    {
                        $('#editRateSerivceModal #rate_service_edit_div').html(response.data);
                        $('#editRateSerivceModal').modal('show');
                    }
                    else
                    {
                        toastr.error(response.message);
                    }
                }
            });
        }


        // Update Tag By Language Code
        function updateByCode(next_lang_code)
        {
            const myFormData = new FormData(document.getElementById('editRateServiceForm'));
            myFormData.append('next_lang_code',next_lang_code);

            // Clear all Toastr Messages
            toastr.clear();

            $.ajax({
                type: "POST",
                url: "{{ route('rate.service.update-by-lang') }}",
                data: myFormData,
                dataType: "JSON",
                contentType: false,
                cache: false,
                processData: false,
                success: function (response)
                {
                    if(response.success == 1)
                    {
                        $('#editRateSerivceModal #rate_service_edit_div').html('');
                        $('#editRateSerivceModal #rate_service_edit_div').html(response.data);
                    }
                    else
                    {
                        $('#editRateSerivceModal').modal('hide');
                        $('#editRateSerivceModal #rate_service_edit_div').html('');
                        toastr.error(response.message);
                    }
                },
                error: function(response)
                {
                    $.each(response.responseJSON.errors, function (i, error) {
                        toastr.error(error);
                    });
                }
            });
        }


        // Update Tag
        function updateRateService()
        {
            const myFormData = new FormData(document.getElementById('editRateServiceForm'));

            // Clear all Toastr Messages
            toastr.clear();

            $.ajax({
                type: "POST",
                url: "{{ route('rate.service.update') }}",
                data: myFormData,
                dataType: "JSON",
                contentType: false,
                cache: false,
                processData: false,
                success: function (response)
                {
                    if(response.success == 1)
                    {
                        $('#editRateSerivceModal').modal('hide');
                        $('#editRateSerivceModal #rate_service_edit_div').html('');
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                    else
                    {
                        $('#editRateSerivceModal').modal('hide');
                        $('#editRateSerivceModal #rate_service_edit_div').html('');
                        toastr.error(response.message);
                    }
                },
                error: function(response)
                {
                    $.each(response.responseJSON.errors, function (i, error) {
                        toastr.error(error);
                    });
                }
            });

        }

        function emailReqChange(status)
        {
            $.ajax({
                type: "POST",
                url: "{{ route('email.rate.services') }}", // Added a closing single quote after 'email.rate.services'
                data: {
                    "_token": "{{ csrf_token() }}",
                    'status': status
                },
                dataType: 'JSON',
                success: function(response)
                {
                    if (response.success == 1)
                    {
                        toastr.success("Email Required been Changed SuccessFully");
                        setTimeout(() => {
                                            location.reload();
                                        }, 1200);
                    }
                    else
                    {
                        toastr.error("Internal Server Errors"); // Corrected spelling of "Server"
                        location.reload(); // Corrected the spelling of "location"
                    }
                }
            });
        }

        // Function for Change Status of Client
        function changeStatus(status, id)
        {
            $.ajax({
                type: "POST",
                url: "{{ route('rate.services.status') }}",
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

        // // Function for Delete Client
        function deleteRateService(rateServiceID)
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
                        url: '{{ route("rate.services.destroy") }}',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            'id': rateServiceID,
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

    </script>

@endsection
