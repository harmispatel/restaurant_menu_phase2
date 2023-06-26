@php
    $shop_id = (isset(Auth::user()->hasOneShop->shop['id'])) ? Auth::user()->hasOneShop->shop['id'] : '';

    $shop_settings = getClientSettings($shop_id);

    // Shop Currency
    $currency = (isset($shop_settings['default_currency']) && !empty($shop_settings['default_currency'])) ? $shop_settings['default_currency'] : 'EUR';
@endphp

@extends('client.layouts.client-layout')

@section('title', __('Orders History'))

@section('content')

    {{-- Page Title --}}
    <div class="pagetitle">
        <h1>{{ __('Orders History')}}</h1>
        <div class="row">
            <div class="col-md-8">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('Orders History') }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    {{-- Orders Section --}}
    <section class="section dashboard">
        <div class="row">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="order_history">
                                <thead>
                                    <tr>
                                        <th>{{ __('Order No.') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Customer') }}</th>
                                        <th>{{ __('Mobile No.') }}</th>
                                        <th>{{ __('Total Price') }}</th>
                                        <th>{{ __('Created At') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($orders as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>
                                            <td>
                                                @if($order->order_status == 'completed')
                                                    <span class="badge bg-success">Completed</span>
                                                @elseif ($order->order_status == 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif ($order->order_status == 'accepted')
                                                    <span class="badge bg-primary">Accepted</span>
                                                @elseif ($order->order_status == 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if((isset($order['firstname']) && !empty($order['firstname'])) || (isset($order['lastname']) && !empty($order['lastname'])))
                                                    {{ $order['firstname'] }} {{ $order['lastname'] }}
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($order['phone']) && !empty($order['phone']))
                                                    {{ $order['phone'] }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($order->discount_per > 0)
                                                    {{ Currency::currency($currency)->format($order->discount_value) }}
                                                @else
                                                    {{ $order->order_total_text }}</td>
                                                @endif
                                            <td>
                                                {{ date('d-m-Y h:i:s',strtotime($order->created_at)) }}
                                            </td>
                                            <td>
                                                <a href="{{ route('view.order',encrypt($order->id)) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="View Order"><i class="bi bi-eye"></i></a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="text-center">
                                            <td colspan="7">
                                                <h3>Orders Not Found!</h3>
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


{{-- Custom Script --}}
@section('page-js')
    <script type="text/javascript">

        // $('#order_history').Datatable();
        $('#order_history').DataTable();

        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": 4000
        }

        @if (Session::has('success'))
            toastr.success('{{ Session::get('success') }}')
        @endif

        @if (Session::has('error'))
            toastr.error('{{ Session::get('error') }}')
        @endif

    </script>
@endsection
