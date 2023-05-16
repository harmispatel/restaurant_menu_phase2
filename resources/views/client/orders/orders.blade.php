@php

    $shop_id = (isset(Auth::user()->hasOneShop->shop['id'])) ? Auth::user()->hasOneShop->shop['id'] : '';

    $shop_settings = getClientSettings($shop_id);

    // Shop Currency
    $currency = (isset($shop_settings['default_currency']) && !empty($shop_settings['default_currency'])) ? $shop_settings['default_currency'] : 'EUR';
@endphp

@extends('client.layouts.client-layout')

@section('title', __('Orders'))

@section('content')

    {{-- Page Title --}}
    <div class="pagetitle">
        <h1>{{ __('Orders')}}</h1>
        <div class="row">
            <div class="col-md-8">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">{{ __('Orders') }}</li>
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
                        @forelse ($orders as $order)
                            <div class="order">
                                <div class="order-btn d-flex align-items-center justify-content-end">
                                    <div class="d-flex align-items-center flex-wrap">Estimated time of arrival <input type="number" name="estimated_time" id="estimated_time" value="{{ $order->estimated_time }}" class="form-control mx-1 estimated_time" style="width: 100px!important" ord-id="{{ $order->id }}"> Minutes.
                                    </div>
                                    <a class="btn btn-sm btn-success ms-3" onclick="acceptOrder({{ $order->id }})"><i class="bi bi-check-circle" data-bs-toggle="tooltip" title="Accept"></i></a>
                                </div>
                                <div class="order-info">
                                    <ul>
                                        <li><strong>#{{ $order->id }}</strong></li>
                                        <li><strong>Order Date : </strong>{{ date('d-m-Y h:i:s',strtotime($order->created_at)) }}</li>
                                        <li><strong>Order Type : </strong>{{ $order->checkout_type }}</li>
                                        <li><strong>Payment Method : </strong>{{ $order->payment_method }}</li>
                                        @if($order->checkout_type == 'takeaway')
                                            <li><strong>Customer : </strong> {{ $order->firstname }} {{ $order->lastname }}</li>
                                            <li><strong>Telephone : </strong> {{ $order->phone }}</li>
                                            <li><strong>Email : </strong> {{ $order->email }}</li>
                                        @elseif($order->checkout_type == 'table_service')
                                            <li><strong>Table No. : </strong> {{ $order->table }}</li>
                                        @elseif($order->checkout_type == 'room_delivery')
                                            <li><strong>Customer : </strong> {{ $order->firstname }} {{ $order->lastname }}</li>
                                            <li><strong>Room No. : </strong> {{ $order->room }}</li>
                                            @if(!empty($order->delivery_time ))
                                                <li><strong>Delivery Time : </strong> {{ $order->delivery_time }}</li>
                                            @endif
                                        @elseif($order->checkout_type == 'delivery')
                                            <li><strong>Customer : </strong> {{ $order->firstname }} {{ $order->lastname }}</li>
                                            <li><strong>Telephone : </strong> {{ $order->phone }}</li>
                                            <li><strong>Email : </strong> {{ $order->email }}</li>
                                            <li><strong>Address : </strong> {{ $order->address }}</li>
                                            <li><strong>Floor : </strong> {{ $order->floor }}</li>
                                            <li><strong>Door Bell : </strong> {{ $order->door_bell }}</li>
                                            <li><strong>Google Map : </strong> <a href="https://maps.google.com?q={{ $order->address }}" target="_blank">Address Link</a></li>
                                            <li><strong>Comments : </strong> {{ $order->instructions }}</li>
                                        @endif
                                    </ul>
                                </div>
                                <hr>
                                <div class="order-info mt-2">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <table class="table">
                                                @if($order->discount_per > 0)
                                                    <tr>
                                                        <td><b>Sub Total</b></td>
                                                        <td class="text-end">{{ $order->order_total_text }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Discount</b></td>
                                                        <td class="text-end">- {{ $order->discount_per }}%</td>
                                                    </tr>
                                                    <tr class="text-end">
                                                        @php
                                                            $discount_amount = ($order->order_total * $order->discount_per) / 100;
                                                            $discount_amount = $order->order_total - $discount_amount;
                                                        @endphp
                                                        <td colspan="2"><strong>{{ Currency::currency($currency)->format($discount_amount) }}</strong></td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td><b>Total</b></td>
                                                        <td class="text-end">{{ $order->order_total_text }}</td>
                                                    </tr>
                                                @endif
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="order-items">
                                    <div class="row">
                                        @if(count($order->order_items) > 0)
                                            <div class="col-md-8">
                                                <table class="table">
                                                    @foreach ($order->order_items as $ord_item)
                                                        <tr>
                                                            @php
                                                                $sub_total = ( $ord_item['sub_total'] / $ord_item['item_qty']);
                                                                $option = unserialize($ord_item['options']);
                                                            @endphp
                                                            <td>
                                                                <b>{{ $ord_item['item_qty'] }} x {{ $ord_item['item_name'] }}</b>
                                                                @if(!empty($option))
                                                                    <br> {{ implode(', ',$option) }}
                                                                @endif
                                                            </td>
                                                            <td width="25%" class="text-end">{{ Currency::currency($currency)->format($sub_total) }}</td>
                                                            <td width="25%" class="text-end">{{ $ord_item['sub_total_text'] }}</td>
                                                        </tr>
                                                    @endforeach
                                                </table>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <h3>Orders Not Available</h3>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection


{{-- Custom Script --}}
@section('page-js')
    <script type="text/javascript">

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


        // Change Estimated Time
        $('.estimated_time').on('change',function()
        {
            var time = $(this).val();
            var ord_id = $(this).attr('ord-id');

            $.ajax({
                type: "POST",
                url: "{{ route('change.order.estimate') }}",
                data: {
                    "_token" : "{{ csrf_token() }}",
                    'estimate_time' : time,
                    'order_id' : ord_id,
                },
                dataType: "JSON",
                success: function (response)
                {
                    if(response.success != 1)
                    {
                        toastr.error(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1300);
                    }
                }
            });

        });


        // Function for Accept Order
        function acceptOrder(ordID)
        {
            $.ajax({
                type: "POST",
                url: "{{ route('accept.order') }}",
                data: {
                    "_token":"{{ csrf_token() }}",
                    "order_id":ordID,
                },
                dataType: "JSON",
                success: function (response)
                {
                    if(response.success == 1)
                    {
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1300);
                    }
                    else
                    {
                        toastr.error(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1300);
                    }
                }
            });
        }

    </script>
@endsection
