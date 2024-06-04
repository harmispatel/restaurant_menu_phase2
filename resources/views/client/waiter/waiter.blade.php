@php
$client_settings = getClientSettings();
$onOffWaiter = isset($client_settings['waiter_call_status']) ?  $client_settings['waiter_call_status'] : '';
    $waiterChecked = ($onOffWaiter == 1) ? 'checked' : '';
    $waiterCheckVal = ($onOffWaiter == 1) ? 0 : 1;

    $onOffSound = isset($client_settings['waiter_call_on_off_sound']) ?  $client_settings['waiter_call_on_off_sound'] : '';
    $soundChecked = ($onOffSound == 1) ? 'checked' : '';
    $soundCheckVal = ($onOffSound == 1) ? 0 : 1;


    $sound = isset($client_settings['waiter_call_sound']) ? $client_settings['waiter_call_sound'] : '';
@endphp
@extends('client.layouts.client-layout')

@section('title', __('Waiter Call'))

@section('content')

    {{-- Page Title --}}
    <div class="pagetitle">
        <h1>{{ __('Waiter Call')}}</h1>
        <div class="row">
            <div class="col-md-8">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">{{ __('Dashboard')}}</a></li>
                        <li class="breadcrumb-item active">{{ __('Waiter Call')}}</li>
                    </ol>
                </nav>
            </div>
            <!-- <div class="col-md-4" style="text-align: right;">
                <a href="{{ route('rooms.add') }}" class="btn btn-sm new-amenity btn-primary">
                    <i class="bi bi-plus-lg"></i>
                </a>
            </div> -->
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

            {{-- Clients Card --}}
            <div class="col-md-12">
                <div class="card">
                    <div class="card-title p-3 m-0">
                        <div class="waiter_title">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <h4 class="me-3">{{ __('Enable') }}</h4>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"  onchange="waiterCallChange({{ $waiterCheckVal }})"  id="onOff" {{ $waiterChecked }}>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <h4 class="me-3">{{ __('Sound Notification')}}</h4>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"  onchange="waiterCallPlayChange({{ $soundCheckVal }})"  id="onOffPlay" {{ $soundChecked }}>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="sound-select">
                                        <label>{{ __('Select Sound')}}</label>
                                        <select name="waiter_notification_sound_choose" id="waiter_notification_sound_choose" class="form-select" onchange="updateNotificationSound()">
                                            <option value="buzzer-01.mp3" {{ ($sound == 'buzzer-01.mp3') ? 'selected' : '' }}>Buzzer 1</option>
                                            <option value="buzzer-02.mp3" {{ ($sound == 'buzzer-02.mp3') ? 'selected' : '' }}>Buzzer 2</option>
                                            <option value="buzzer-03.mp3" {{ ($sound == 'buzzer-03.mp3') ? 'selected' : '' }}>Buzzer 3</option>
                                            <option value="buzzer-04.mp3"  {{ ($sound == 'buzzer-04.mp3') ? 'selected' : '' }}>Buzzer 4</option>
                                            <option value="buzzer-05.mp3" {{ ($sound == 'buzzer-05.mp3') ? 'selected' : '' }} >Buzzer 5</option>
                                            <option value="male.mp3" {{ ($sound == 'male.mp3') ? 'selected' : '' }}>Male Gr</option>
                                            <option value="female.mp3" {{ ($sound == 'female.mp3') ? 'selected' : '' }}>Female Gr</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped w-100" id="clientsTable">
                                <thead>
                                    <tr>
                                        <th>{{ __('Id')}}</th>
                                        <th>{{ __('Staff')}}</th>
                                        <th>{{ __('Location')}}</th>
                                        <th>{{ __('No')}}</th>
                                        <th class="text-center">{{ __('Actions')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($call_waiters as $callWaiter)

                                        <tr>
                                            <td>{{ $callWaiter->id }}</td>
                                            <td>
                                                @if($callWaiter->location == 0)
                                                    @php
                                                        $staffs = isset($callWaiter->table->staffs) ? implode(', ', $callWaiter->table->staffs->pluck('name')->toArray()) : [];
                                                    @endphp
                                                    {{ $staffs }}
                                                @else
                                                    @php
                                                        $staffs = isset($callWaiter->room->staffs) ? implode(', ', $callWaiter->room->staffs->pluck('name')->toArray()) : [];
                                                    @endphp
                                                    {{ $staffs }}
                                                @endif
                                            </td>
                                            <td>{{ ($callWaiter->location == 0) ? 'Table Service' : 'Room Service' }}</td>
                                            <td>
                                                @if($callWaiter->location == 0)
                                                    @php
                                                        $table = isset($callWaiter->table['table_name']) ? $callWaiter->table['table_name'] : '';
                                                    @endphp
                                                    {{ $table }}
                                                @else
                                                    @php
                                                        $room = isset($callWaiter->room['room_no']) ? $callWaiter->room['room_no'] : '';
                                                    @endphp
                                                    {{ $room }}
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    @if($callWaiter->read == 0)
                                                    <span  onclick="acceptWaiterCall({{ $callWaiter->id }})" class="waiter_req me-2"><i class="fa-solid fa-check"></i></span>
                                                    @else
                                                    <span  class="waiter_req accept me-2"><i class="fa-solid fa-check"></i></span>
                                                    @endif
                                                    <a href="{{ route('show.call.waiter',$callWaiter->id) }}" class="me-2 btn btn-sm btn-primary rounded-circle">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </a>
                                                    <a onclick="deleteWaiterCall({{ $callWaiter->id }})" class="btn btn-sm btn-danger rounded-circle">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="text-center">
                                            <td colspan="6">{{ __('Waiter Call Not Found!')}}</td>
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
        // function changeStatus(status, id)
        // {
        //     $.ajax({
        //         type: "POST",
        //         url: "{{ route('rooms.status') }}",
        //         data: {
        //             "_token": "{{ csrf_token() }}",
        //             'status': status,
        //             'id': id
        //         },
        //         dataType: 'JSON',
        //         success: function(response)
        //         {
        //             if (response.success == 1)
        //             {
        //                 toastr.success("Status has been Changed SuccessFully");
        //             }
        //             else
        //             {
        //                 toastr.error("Internal Serve Errors");
        //                 locattion.reload();
        //             }
        //         }
        //     });
        // }

        // Function for On Off Status of Waiter
        function waiterCallChange(status)
        {
            $.ajax({
                type: "POST",
                url: "{{ route('on.off.call.waiter') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'status': status,

                },
                dataType: 'JSON',
                success: function(response)
                {
                    if (response.success == 1)
                    {
                        toastr.success("Status has been Changed SuccessFully");
                        setTimeout(() => {
                                    location.reload();
                                }, 1200);
                    }
                    else
                    {
                        toastr.error("Internal Serve Errors");
                        locattion.reload();
                    }
                }
            });
        }

        // Function for On Off Play Sound of Waiter
        function waiterCallPlayChange(status)
        {
            $.ajax({
                type: "POST",
                url: "{{ route('on.off.playsound') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'status': status,

                },
                dataType: 'JSON',
                success: function(response)
                {
                    if (response.success == 1)
                    {
                        toastr.success("Play Sound has been Changed SuccessFully");
                        setTimeout(() => {
                                    location.reload();
                                }, 1200);
                    }
                    else
                    {
                        toastr.error("Internal Serve Errors");
                        locattion.reload();
                    }
                }
            });
        }



        function acceptWaiterCall(id)
        {
            $.ajax({
                        type: "POST",
                        url: '{{ route("accept.call.waiter") }}',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            'id': id,
                        },
                        dataType: 'JSON',
                        success: function(response)
                        {
                            if (response.success == 1)
                            {
                                toastr.success("Accept Waiter has been  SuccessFully");
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



        // // Function for Delete Client
        function deleteWaiterCall(id)
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
                        url: '{{ route("delete.call.waiter") }}',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            'id': id,
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

        function updateNotificationSound()
        {
            var selectedSound = document.getElementById("waiter_notification_sound_choose").value;


            $.ajax({
            type: "POST",
            url: "{{ route('select.play.sound') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "sound": selectedSound,
            },
            dataType: 'JSON',
            success: function(response) {
                if (response.success == 1)
                    {
                        toastr.success("Play Sound has been Changed SuccessFully");
                        setTimeout(() => {
                                    location.reload();
                                }, 1200);
                    }
                    else
                    {
                        toastr.error("Internal Serve Errors");
                        locattion.reload();
                    }
            }

        });
        }
    </script>
@endsection
