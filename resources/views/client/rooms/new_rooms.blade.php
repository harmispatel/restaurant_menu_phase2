@php
    $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : "";

@endphp
@extends('client.layouts.client-layout')

@section('title',__('New Room'))

@section('content')

    {{-- Page Title --}}
    <div class="pagetitle">
        <h1>{{ __('Rooms')}}</h1>
        <div class="row">
            <div class="col-md-8">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">{{ __('Dashboard')}}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('rooms') }}">{{ __('Rooms')}}</a></li>
                        <li class="breadcrumb-item active">{{ __('New Room')}}</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-4" style="text-align: right;">
                <a href="{{ route('rooms') }}" class="btn btn-sm new-amenity btn-primary">
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
                    <form class="form" action="{{ route('rooms.save') }}" method="POST" enctype="multipart/form-data">
                        <div class="card-body">
                            <div class="card-title">
                            </div>
                            @csrf
                            <input type="hidden" name="shop_id" id="shop_id" value="{{ $shop_id }}">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>{{ __('Room Details')}}</h3>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="floor" class="form-label">{{ __('Floor')}}</label>
                                            <input type="number" name="floor" id="floor" class="form-control {{ ($errors->has('floor')) ? 'is-invalid' : '' }}" placeholder="Enter Floor" value="{{ old('floor') }}" oninput="updateRoomNumber()">
                                            @if($errors->has('floor'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('floor') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="room_no" class="form-label">{{ __('Room')}}</label>
                                            <input type="text" name="room_no" id="room_no" class="form-control {{ ($errors->has('room_no')) ? 'is-invalid' : '' }}" placeholder="Enter Room" value="{{ old('room_no') }}">
                                            @if($errors->has('room_no'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('room_no') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="room_no" class="form-label">{{ __('Employees')}}</label>
                                            <select name="staffs[]" id="staffs" class="form-control {{ ($errors->has('staffs')) ? 'is-invalid' : '' }}" multiple>
                                                @if(count($staffs) > 0)
                                                    @foreach ($staffs as $staff)
                                                        <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if($errors->has('staffs'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('staffs') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="status" class="form-label">{{ __('Status')}}</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="status" role="switch" id="status" value="1" checked>
                                        </div>
                                    </div>
                                </div>
                                <hr>

                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-success">{{ __('Save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection

{{-- Custom JS --}}
@section('page-js')
    <script type="text/javascript">

        var staffs = $('#staffs').select2();


    //   function updateRoomNumber() {
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
