@extends('admin.layouts.admin-layout')

@section('title', __('Clients'))

@section('content')

    {{-- Page Title --}}
    <div class="pagetitle">
        <h1>{{ __('Clients')}}</h1>
        <div class="row">
            <div class="col-md-8">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard')}}</a></li>
                        <li class="breadcrumb-item active">{{ __('Clients')}}</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-4" style="text-align: right;">
                <a href="{{ route('clients.add') }}" class="btn btn-sm new-amenity btn-primary">
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

            {{-- Clients Card --}}
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            @forelse ($clients as $client)
                                <div class="col-md-4 mb-3 mt-3">
                                    <div class="client-box">
                                        <div class="shop_logo_name">
                                            <h3>{{ isset($client->hasOneShop->shop['name']) ? $client->hasOneShop->shop['name'] : '' }}</h3>
                                            <img  src="{{ isset($client->hasOneShop->shop['logo']) ? $client->hasOneShop->shop['logo'] : '' }}" alt="">
                                        </div>
                                        <div class="client-name">
                                            <h2>{{ $client->firstname }} {{  $client->lastname }}</h2>
                                        </div>
                                        <div class="action-bt">
                                            <a href="{{ route('clients.edit',$client->id) }}" class="btn btn-sm btn-primary me-2">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="{{ route('clients.destroy',$client->id) }}" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                {{-- <tr>
                                    <td>{{ $client->id }}</td>
                                    <td>{{ $client->name }}</td>
                                    <td>{{ $client->email }}</td>
                                    <td>
                                        @if($client->status == 1)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">InActive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('clients.edit',$client->id) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="{{ route('clients.destroy',$client->id) }}" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr> --}}
                            @empty
                                <div class="col-md-12 text-center">
                                    {{ __('Clients Not Found!')}}
                                </div>
                            @endforelse
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

    </script>
@endsection
