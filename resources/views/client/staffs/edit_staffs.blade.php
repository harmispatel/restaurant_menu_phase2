@php
    $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : "";
@endphp
@extends('client.layouts.client-layout')

@section('title',__('Edit Employee'))

@section('content')

    {{-- Page Title --}}
    <div class="pagetitle">
        <h1>{{ __('Employees')}}</h1>
        <div class="row">
            <div class="col-md-8">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">{{ __('Dashboard')}}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('staffs') }}">{{ __('Employees')}}</a></li>
                        <li class="breadcrumb-item active">{{ __('Edit Employee')}}</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-4" style="text-align: right;">
                <a href="{{ route('staffs') }}" class="btn btn-sm new-amenity btn-primary">
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
                    <form class="form" action="{{ route('staffs.udpate') }}" method="POST" enctype="multipart/form-data">
                        <div class="card-body">
                            <div class="card-title">
                            </div>
                            @csrf
                            <input type="hidden" name="shop_id" id="shop_id" value="{{ $shop_id }}">
                            <input type="hidden" name="id" id="id" value="{{ $staff->id }}">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>{{ __('Employee Details')}}</h3>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="name" class="form-label">{{ __('Name')}}</label>
                                            <input type="text" name="name" id="name" class="form-control {{ ($errors->has('name')) ? 'is-invalid' : '' }}" placeholder="Enter First Name" value="{{ $staff->name }}">
                                            @if($errors->has('name'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('name') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="email" class="form-label">{{ __('Email')}}</label>
                                            <input type="text" name="email" id="email" class="form-control {{ ($errors->has('email')) ? 'is-invalid' : '' }}" placeholder="Enter Client Email" value="{{ $staff->email }}">
                                            @if($errors->has('email'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('email') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="wp_number" class="form-label">{{ __('Whatsapp Number')}}</label>
                                            <input type="text" name="wp_number" id="wp_number" class="form-control {{ ($errors->has('wp_number')) ? 'is-invalid' : '' }}" placeholder="Enter Whatsapp Number" value="{{ $staff->wp_number }}">
                                            @if($errors->has('wp_number'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('wp_number') }}
                                                </div>
                                            @endif
                                            <code>{{ __('Please enter the pnone with this format: +30xxxxxxxxx') }}</code>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="type" class="form-label">{{ __('Type')}}</label>
                                            <select name="type" id="type" class="form-control {{ ($errors->has('type')) ? 'is-invalid' : '' }}">
                                                <option value="0" {{ $staff->type == 0 ? 'selected' : '' }}>{{ __('Driver') }}</option>
                                                <option value="1" {{ $staff->type == 1 ? 'selected' : '' }}>{{ __('Waiter') }}</option>
                                            </select>
                                            @if($errors->has('type'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('type') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="status" class="form-label">{{ __('Status')}}</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="status" role="switch" id="status" value="1" {{ ($staff->status == 1) ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>
                                <hr>

                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-success">{{ __('Udpate')}}</button>
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

    $(document).ready(function(){
        $('#wp_number').on('input', function() {
            let value = $(this).val().replace(/[^0-9+]/g, '').replace(/^\+/, '');
            $(this).val('+' + value.substring(0, 12));
        }).on('paste', function(e) {
            var pastedData = (e.originalEvent.clipboardData || window.clipboardData).getData('Text');
            if (!/^\+?[0-9]*$/.test(pastedData)) {
                e.preventDefault();
            }
        }).on('focus', function() {
            if (!$(this).val()) $(this).val('+');
        });
    });

    </script>
@endsection

