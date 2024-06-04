@php
    $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : "";
    $user = $service_review[0];
    // Current Languge Code
                    $current_lang_code = (session()->has('lang_code')) ? session()->get('lang_code') : 'en';

                    $name_key = $current_lang_code . "_name";

@endphp
@extends('client.layouts.client-layout')

@section('title', __('Services Reviews'))

@section('content')



{{-- Page Title --}}
    <div class="pagetitle">
        <h1>{{ __('Services Reviews')}}</h1>
        <div class="row">
            <div class="col-md-8">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">{{ __('Dashboard')}}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('services.review') }}">{{ __('Services Reviews')}}</a></li>
                        <li class="breadcrumb-item active">{{ __('View')}}</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-4" style="text-align: right;">
                <a href="{{ route('services.review') }}" class="btn btn-sm new-amenity btn-primary">
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
                                        <h3>{{ __('Service Review Detail')}}</h3>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="name" class="form-label">{{ __('Name')}}</label>
                                            <input type="text" name="name" id="name" class="form-control {{ ($errors->has('name')) ? 'is-invalid' : '' }}"  value="{{ $user->name }}" disabled>

                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="email" class="form-label">{{ __('Email')}}</label>
                                            <input type="text"  class="form-control " value="{{ isset($user->email) ? $user->email : '' }}" disabled>

                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="ip_address" class="form-label">{{ __('Ip Address')}}</label>
                                            <input type="text" name="ip_address" id="ip_address" class="form-control"  value="{{ $user->ip_address }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="wp_number" class="form-label">{{ __('Comment')}}</label>
                                            <textarea class="form-control" rows="3" disabled>{{ isset($user->comment) ? $user->comment : '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                @forelse ($reviews as $review)

                                        <div class="col-md-6">
                                            <div class="client_review">
                                                <div class="form-group">
                                                    <label for="">{{ isset($review->serviceName) ? $review->serviceName->$name_key : '' }}</label>
                                                </div>
                                                <div class="rated">
                                                    @for($i=1; $i <= $review->rating; $i++)
                                                        <label class="star-rating-complete" title="text">{{$i}} stars</label>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>

                                        @empty

                                        @endforelse

                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </section>
@endsection
