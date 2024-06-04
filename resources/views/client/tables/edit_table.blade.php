@php
    $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : "";

@endphp
@extends('client.layouts.client-layout')

@section('title',__('Edit Table'))

@section('content')

    {{-- Page Title --}}
    <div class="pagetitle">
        <h1>{{ __('Tables')}}</h1>
        <div class="row">
            <div class="col-md-8">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">{{ __('Dashboard')}}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('tables') }}">{{ __('Tables')}}</a></li>
                        <li class="breadcrumb-item active">{{ __('Edit Table')}}</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-4" style="text-align: right;">
                <a href="{{ route('tables') }}" class="btn btn-sm new-amenity btn-primary">
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Edit Table add Section --}}
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
                    <form class="form" action="{{ route('tables.update') }}" method="POST" enctype="multipart/form-data">
                        <div class="card-body">
                            <div class="card-title">
                            </div>
                            @csrf
                            <input type="hidden" name="shop_id" id="shop_id" value="{{ $shop_id }}">
                            <input type="hidden" name="id" id="id" value="{{ $shopTable->id }}">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>{{ __('Table Details')}}</h3>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="shop_area" class="form-label">{{ __('Shop Area')}}</label>
                                            <input type="text" name="shop_area" id="shop_area" class="form-control {{ ($errors->has('shop_area')) ? 'is-invalid' : '' }}" placeholder="Enter Shop Area" value="{{ old('shop_area', $shopTable->shop_area) }}">
                                            @if($errors->has('shop_area'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('shop_area') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="table_name" class="form-label">{{ __('Table')}}</label>
                                            <input type="text" name="table_name" id="table_name" class="form-control {{ ($errors->has('table_name')) ? 'is-invalid' : '' }}" placeholder="Enter Table" value="{{ old('table_name', $shopTable->table_name) }}">
                                            @if($errors->has('table_name'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('table_name') }}
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
                                                        <option value="{{ $staff->id }}" {{ in_array($staff->id, $staffIds) ? 'selected' : '' }}>{{ $staff->name }}</option>
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
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-success">{{ __('Update')}}</button>
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

</script>
@endsection
