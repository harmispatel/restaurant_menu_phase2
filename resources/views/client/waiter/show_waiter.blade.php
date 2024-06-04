@php
    $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : "";
    $location = ($call_waiter->location == 0) ? 'Table Service' : 'Room Service';
@endphp
@extends('client.layouts.client-layout')

@section('title',__('Show Waiter Call'))

@section('content')

    {{-- Page Title --}}
    <div class="pagetitle">
        <h1>{{ __('Waiter Call')}}</h1>
        <div class="row">
            <div class="col-md-8">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">{{ __('Dashboard')}}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('list.call.waiter') }}">{{ __('Waiter Call')}}</a></li>
                        <li class="breadcrumb-item active">{{ __('Show Waiter Call')}}</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-4" style="text-align: right;">
                <a href="{{ route('list.call.waiter') }}" class="btn btn-sm new-amenity btn-primary">
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- New Clients add Section --}}
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
                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3>{{ __('Waiter Call Detail')}}</h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="location" class="form-label">{{ __('Location')}}</label>
                                        <input type="text"  id="location" class="form-control" value="{{ $location }}" disabled>
                                    </div>
                                </div>
                                @if($call_waiter->location == 0)
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="shop_area" class="form-label">{{ __('Shop Area')}}</label>
                                            <input type="text" id="table_no" class="form-control" value="{{ (isset($call_waiter->table['shop_area'])) ? $call_waiter->table['shop_area'] : '' }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="table_no" class="form-label">{{ __('Table No.')}}</label>
                                            <input type="text" id="table_no" class="form-control" value="{{ (isset($call_waiter->table['table_name'])) ? $call_waiter->table['table_name'] : '' }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        @php
                                            $staffs = (isset($call_waiter->table->staffs)) ? implode(', ', $call_waiter->table->staffs->pluck('name')->toArray()) : [];
                                        @endphp
                                        <div class="form-group">
                                            <label for="staff" class="form-label">{{ __('Staff')}}</label>
                                            <input type="text" id="staff" class="form-control" value="{{ $staffs }}" disabled>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="floor" class="form-label">{{ __('Floor')}}</label>
                                            <input type="text"  id="floor" class="form-control"  value="{{ (isset($call_waiter->room['floor'])) ? $call_waiter->room['floor'] : '' }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="room_no" class="form-label">{{ __('Room No.')}}</label>
                                            <input type="text"  id="room_no" class="form-control"  value="{{ (isset($call_waiter->room['room_no'])) ? $call_waiter->room['room_no'] : '' }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        @php
                                            $staffs = (isset($call_waiter->room->staffs)) ? implode(', ', $call_waiter->room->staffs->pluck('name')->toArray()) : [];
                                        @endphp
                                        <div class="form-group">
                                            <label for="staff" class="form-label">{{ __('Staff')}}</label>
                                            <input type="text" id="staff" class="form-control" value="{{ $staffs }}" disabled>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="name" class="form-label">{{ __('Customer Name')}}</label>
                                        <input type="text"  id="name" class="form-control"  value="{{  isset($call_waiter->name) ? $call_waiter->name : '' }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="message" class="form-label">{{ __('Message')}}</label>
                                        <textarea  id="message" class="form-control" rows="3" disabled>{{ isset($call_waiter->message) ? $call_waiter->message : '' }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="call_waiter_service">
                                        <div class="row">
                                            <div class="col-md-6">
                                                    <div class="waiter_service">
                                                    <div class="row align-items-center">
                                                    <div class="col-8">
                                                        <h3>{{ __('Order')}}</h3>
                                                    </div>
                                                    <div class="col-4">
                                                        @if($call_waiter->order == 1)
                                                        <span class="waiter_service_box"></span>
                                                        @else
                                                        <span class="waiter_not_service"></span>
                                                        @endif
                                                    </div>
                                                </div>
                                                    </div>
                                            </div>
                                            <div class="col-md-6">
                                                    <div class="waiter_service">
                                                        <div class="row align-items-center">
                                                            <div class="col-8">
                                                                <h3>{{ __('Water')}}</h3>
                                                        </div>
                                                        <div class="col-4">
                                                            @if($call_waiter->water == 1)
                                                            <span class="waiter_service_box"></span>
                                                            @else
                                                            <span class="waiter_not_service"></span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    </div>
                                            </div>
                                            <div class="col-md-6">
                                                    <div class="waiter_service">
                                                    <div class="row align-items-center">
                                                            <div class="col-8">
                                                                <h3>{{ __('Pay Bill')}}</h3>
                                                        </div>
                                                        <div class="col-4">
                                                            @if($call_waiter->pay_bill == 1)
                                                                <span class="waiter_service_box"></span>
                                                            @else
                                                                <span class="waiter_not_service"></span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    </div>
                                            </div>
                                            <div class="col-md-6">
                                                    <div class="waiter_service">
                                                    <div class="row align-items-center">
                                                        <div class="col-8">
                                                            <h3>{{ __('Pay with card')}}</h3>
                                                        </div>
                                                        <div class="col-4">
                                                            @if($call_waiter->pay_with_bill == 1)
                                                            <span class="waiter_service_box"></span>
                                                            @else
                                                            <span class="waiter_not_service"></span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    </div>
                                            </div>
                                            <div class="col-md-6">
                                                    <div class="waiter_service">
                                                    <div class="row align-items-center">
                                                        <div class="col-8">
                                                            <h3>{{ __('Other')}}</h3>
                                                        </div>
                                                        <div class="col-4">
                                                            @if($call_waiter->other == 1)
                                                            <span class="waiter_service_box"></span>
                                                            @else
                                                            <span class="waiter_not_service"></span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>

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



    // function updateRoomNumber() {
    //   var floorInput = document.getElementById('floor');
    //   var roomInput = document.getElementById('room_no');

    //   var floorValue = floorInput.value.trim();
    //   var roomValue = roomInput.value.trim();

    //   // Step 1: Mirror the content of the floor textbox
    //   roomInput.value = floorValue;

    //   // Step 2: Prevent removal of characters matching floor textbox
    //   if (roomValue.startsWith(floorValue)) {
    //     roomInput.setSelectionRange(floorValue.length, roomValue.length);
    //   }
    // }

    // $('#room_no').on('keydown', function(event) {
    //     var floorValueLength = $('#floor').val().trim().length;
    //     // If the input length is 1 and backspace key is pressed, prevent default behavior
    //     if ($(this).val().length === floorValueLength && event.which === 8) {
    //         event.preventDefault();
    //     }
    // });

    </script>
@endsection
