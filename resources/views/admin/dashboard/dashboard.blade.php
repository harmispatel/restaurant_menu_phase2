@extends('admin.layouts.admin-layout')

@section('title', __('Dashboard'))

@section('content')

    {{-- Page Title --}}
    <div class="pagetitle">
        <h1>{{ __('Dashboard') }}</h1>
        <div class="row">
            <div class="col-md-8">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active">{{ __('Dashboard') }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    {{-- Dashboard Section --}}
    <section class="section dashboard">
        <div class="row">
            {{-- Errors Message --}}
            @if (session()->has('errors'))
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('errors') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif


            <div class="col-md-12">
                <div class="row">
                    <!-- Restaurant Card -->
                    <div class="col-md-4">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">{{ __('Restaurants')}}</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="ri-restaurant-2-line"></i>
                                    </div>
                                    <div class="ps-3">
                                        <span class="text-success pt-1"><i class="bi bi-arrow-up-circle"></i> {{ __('Total')}}
                                            - {{ isset($shop['total']) ? $shop['total'] : 0 }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-12">
                <div class="card recent-sales overflow-auto">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('Recent Clients')}}</h5>
                        {{-- <h5 class="card-title">Recent Sales <span>| Today</span></h5> --}}
                        <table class="table recentClient">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Client')}}</th>
                                    <th>{{ __('Shop')}}</th>
                                    <th>{{ __('Subscription')}}</th>
                                    <th>{{ __('Expire In')}}</th>
                                    <th>{{ __('Status')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recent_clients as $recent_client)
                                    @php
                                        $expire_date =  (isset($recent_client->hasOneSubscription['end_date'])) ? \Carbon\Carbon::now()->diffInMonths($recent_client->hasOneSubscription['end_date'], false) : '';
                                    @endphp
                                    <tr>
                                        <td>{{ $recent_client->id }}</td>
                                        <td>{{ $recent_client->firstname }} {{ $recent_client->lastname }}</td>
                                        <td>{{ isset($recent_client->hasOneShop->shop['name']) ? $recent_client->hasOneShop->shop['name'] : '' }}</td>
                                        <td>{{ isset($recent_client->hasOneSubscription['duration']) ? $recent_client->hasOneSubscription['duration'] : '' }}</td>
                                        <td>{{ $expire_date }} Months.</td>
                                        <td>
                                            @if($recent_client->status == 1)
                                                <span class="badge bg-success">{{ __('Active')}}</span>
                                            @else
                                            <span class="badge bg-danger">{{ __('NonActive')}}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="text-center">
                                        <td colspan="6">{{ __('Clients Not Found!')}}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection

{{-- Custom JS --}}
@section('page-js')

    <script type="text/javascript">
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-bottom-right",
            timeOut: 4000
        }

        @if (Session::has('success'))
            toastr.success('{{ Session::get('success') }}')
        @endif

        @if (Session::has('error'))
            toastr.error('{{ Session::get('error') }}')
        @endif

        // Document Ready
        $(document).ready(function () {
            // $('.recentClient').DataTable();
        });
    </script>

@endsection
