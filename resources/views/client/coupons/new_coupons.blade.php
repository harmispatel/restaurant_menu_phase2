@php
    $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : "";

@endphp
@extends('client.layouts.client-layout')

@section('title',__('New Coupon'))

@section('content')

    {{-- Page Title --}}
    <div class="pagetitle">
        <h1>{{ __('Coupons')}}</h1>
        <div class="row">
            <div class="col-md-8">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">{{ __('Dashboard')}}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('coupons') }}">{{ __('Coupons')}}</a></li>
                        <li class="breadcrumb-item active">{{ __('New Coupon')}}</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-4" style="text-align: right;">
                <a href="{{ route('coupons') }}" class="btn btn-sm new-amenity btn-primary">
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
                    <form class="form" action="{{ route('coupons.save') }}" method="POST" enctype="multipart/form-data">
                        <div class="card-body">
                            <div class="card-title">
                            </div>
                            @csrf
                            <input type="hidden" name="shop_id" id="shop_id" value="{{ $shop_id }}">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>{{ __('Coupon Details')}}</h3>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="option_code" class="form-label">{{ __('Generate Code')}}</label>
                                            <select name="option_code" id="option_code" class="form-control">
                                                <option value="0">{{ __('Manual') }}</option>
                                                <option value="1">{{ __('Auto Generate') }}</option>
                                            </select>
                                            @if($errors->has('option_code'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('option_code') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="code" class="form-label">{{ __('Coupon Code')}}</label>
                                            <input type="text" name="code" id="code" class="form-control {{ ($errors->has('code')) ? 'is-invalid' : '' }}" placeholder="Enter Coupon Code" value="{{ old('code') }}">
                                            @if($errors->has('code'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('code') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="name" class="form-label">{{ __('Name')}}</label>
                                            <input type="text" name="name" id="name" class="form-control {{ ($errors->has('name')) ? 'is-invalid' : '' }}" placeholder="Enter Name" value="{{ old('name') }}">
                                            @if($errors->has('name'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('name') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="value" class="form-label">{{ __('Amount')}}</label>
                                            <input type="number" name="value" id="value" class="form-control {{ ($errors->has('value')) ? 'is-invalid' : '' }}" placeholder="Enter Amount" value="{{ old('value') }}">
                                            @if($errors->has('value'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('value') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="total_users" class="form-label">{{ __('Repeated Usage')}}</label>
                                            <input type="number" name="total_users" id="total_users" min="1" class="form-control {{ ($errors->has('total_users')) ? 'is-invalid' : '' }}" placeholder="Enter Repeated Usage" value="{{ old('total_users', 1) }}">
                                            @if($errors->has('total_users'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('total_users') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="type" class="form-label">{{ __('Type')}}</label>
                                            <select name="type" id="type" class="form-control {{ ($errors->has('type')) ? 'is-invalid' : '' }}">
                                                <option value="percentage">{{ __('Percentage %') }}</option>
                                                <option value="fixed">{{ __('Fixed Amount') }}</option>
                                            </select>
                                            @if($errors->has('type'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('type') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="end_date" class="form-label">{{ __('End Date')}}</label>
                                            <input type="date" name="end_date" id="end_date"  class="form-control  {{$errors->has('end_date') ? 'is-ivalid' : '' }}">
                                            @if($errors->has('end_date'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('end_date') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="min_cart_amount" class="form-label">{{ __('Minimum Amount')}}</label>
                                            <input type="number" name="min_cart_amount" id="min_cart_amount" class="form-control {{ ($errors->has('min_cart_amount')) ? 'is-invalid' : '' }}" placeholder="Enter Minimum Amount" value="{{ old('min_cart_amount') }}">
                                            @if($errors->has('min_cart_amount'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('min_cart_amount') }}
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

    $(document).ready(function() {
        $('#option_code').change(function() {
            var option = $(this).val();
            if (option == '1') { // If "Auto Generate" option is selected
                // Generate a random code
                var randomCode = Math.random().toString(36).substring(2, 10).toUpperCase();
                // Fill the code input field with the generated code
                $('#code').val(randomCode);
                $('#code').prop('readonly', true);
            }else {
                $('#code').val('');
                $('#code').prop('readonly', false);

            }
        });
    });

    </script>
@endsection
