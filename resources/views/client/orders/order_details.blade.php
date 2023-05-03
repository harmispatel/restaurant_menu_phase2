@php
    $shop_slug = isset(Auth::user()->hasOneShop->shop['shop_slug']) ? Auth::user()->hasOneShop->shop['shop_slug'] : '';
    $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : "";
    $primary_lang_details = clientLanguageSettings($shop_id);

    $language = getLangDetails(isset($primary_lang_details['primary_language']) ? $primary_lang_details['primary_language'] : '');
    $language_code = isset($language['code']) ? $language['code'] : '';
    $name_key = $language_code."_name";

    $shop_settings = getClientSettings($shop_id);

    // Shop Currency
    $currency = (isset($shop_settings['default_currency']) && !empty($shop_settings['default_currency'])) ? $shop_settings['default_currency'] : 'EUR';
@endphp

@extends('client.layouts.client-layout')

@section('title', __('Order Details'))

@section('content')

    {{-- Page Title --}}
    <div class="pagetitle">
        <h1>{{ __('Order Details')}}</h1>
        <div class="row">
            <div class="col-md-8">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('client.orders') }}">{{ __('Orders') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('Order Details') }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    {{-- Order Details Section --}}
    <section class="section dashboard">
        <div class="row">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-md-12 mb-2">
                                <h3>Order : #{{ $order->id }}</h3>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="card mb-0">
                                    <div class="card-body">
                                        <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                            <tbody class="fw-semibold text-gray-600">
                                                <tr>
                                                    <td class="text-muted">
                                                        <div class="client-order-info">
                                                            <div class="">
                                                                <i class="bi bi-calendar-date"></i>&nbsp;Order Date
                                                            </div>
                                                            <div class="fw-bold">
                                                                {{ date('d-m-Y h:i:s',strtotime($order->created_at)) }}
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">
                                                        <div class="client-order-info">
                                                            <div class="">
                                                                <i class="bi bi-credit-card"></i>&nbsp;Payment Method
                                                            </div>
                                                            <div class="fw-bold">
                                                                {{ $order->payment_method }}
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">
                                                        <div class="client-order-info">
                                                            <div class="">
                                                                <i class="bi bi-truck"></i>&nbsp;Shipping Method
                                                            </div>
                                                            <div class="fw-bold">
                                                                {{ $order->checkout_type }}
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @if($order->checkout_type == 'table_service')
                                                    <tr>
                                                        <td class="text-muted">
                                                            <div class="client-order-info">
                                                                <div class="">
                                                                    <i class="bi bi-table"></i>&nbsp;Table No.
                                                                </div>
                                                                <div class="fw-bold">
                                                                    {{ $order->table }}
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @if($order->checkout_type != 'table_service')
                                <div class="col-md-6 mb-2">
                                    <div class="card mb-0">
                                        <div class="card-body">
                                            <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                                <tbody class="fw-semibold text-gray-600">
                                                    @if($order->checkout_type == 'takeaway' || $order->checkout_type == 'room_delivery')
                                                        <tr>
                                                            <td class="text-muted">
                                                                <div class="client-order-info">
                                                                    <div class="">
                                                                        <i class="bi bi-person-circle"></i>&nbsp;Customer
                                                                    </div>
                                                                    <div class="fw-bold">
                                                                        {{ $order->firstname }} {{ $order->lastname }}
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @if($order->checkout_type == 'takeaway')
                                                        <tr>
                                                            <td class="text-muted">
                                                                <div class="client-order-info">
                                                                    <div class="">
                                                                        <i class="bi bi-envelope"></i>&nbsp;Email
                                                                    </div>
                                                                    <div class="fw-bold text-break">
                                                                        {{ $order->email }}
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">
                                                                <div class="client-order-info">
                                                                    <div class="">
                                                                        <i class="bi bi-telephone"></i>&nbsp;Phone No.
                                                                    </div>
                                                                    <div class="fw-bold">
                                                                        {{ $order->phone }}
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @if($order->checkout_type == 'room_delivery')
                                                        <tr>
                                                            <td class="text-muted">
                                                                <div class="client-order-info">
                                                                    <div class="">
                                                                        <i class="bi bi-house"></i>&nbsp;Room No.
                                                                    </div>
                                                                    <div class="fw-bold text-break">
                                                                        {{ $order->room }}
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">
                                                                <div class="client-order-info">
                                                                    <div class="">
                                                                        <i class="bi bi-bicycle"></i>&nbsp;Delivery Time
                                                                    </div>
                                                                    <div class="fw-bold text-break">
                                                                        {{ $order->delivery_time }}
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                        <thead>
                                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                <th class="text-start" style="width:60%">Item</th>
                                                <th class="text-center">Qty.</th>
                                                <th class="text-end">Item Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fw-semibold text-gray-600">
                                            @if(isset($order->order_items) && count($order->order_items) > 0)
                                                @foreach ($order->order_items as $ord_item)
                                                    @php
                                                        $item_dt = itemDetails($ord_item['item_id']);
                                                        $item_image = (isset($item_dt['image']) && !empty($item_dt['image']) && file_exists('public/client_uploads/shops/'.$shop_slug.'/items/'.$item_dt['image'])) ? asset('public/client_uploads/shops/'.$shop_slug.'/items/'.$item_dt['image']) : asset('public/client_images/not-found/no_image_1.jpg');
                                                        $options_array = (isset($ord_item['options']) && !empty($ord_item['options'])) ? unserialize($ord_item['options']) : '';
                                                        if(count($options_array) > 0)
                                                        {
                                                            $options_array = implode(', ',$options_array);
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td class="text-start">
                                                            <div class="d-flex align-items-center">
                                                                <a class="symbol symbol-50px">
                                                                    <span class="symbol-label" style="background-image:url({{ $item_image }});"></span>
                                                                </a>
                                                                <div class="ms-5">
                                                                    <a class="fw-bold" style="color: #7e8299">
                                                                        {{ ($ord_item->item_name) }}
                                                                    </a>
                                                                    <div class="fs-7" style="color: #a19e9e;">{{ $options_array }}</div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            {{ $ord_item['item_qty'] }}
                                                        </td>
                                                        <td class="text-end">
                                                            {{ $ord_item['sub_total_text'] }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            <tr>
                                                <td colspan="2" class="text-dark fs-5 text-end">
                                                    Sub Total
                                                </td>
                                                <td class="text-dark fs-5 text-end">{{ $order->order_total_text }}</td>
                                            </tr>
                                            @if($order->discount_per > 0)
                                                <tr>
                                                    <td colspan="2" class="text-dark fs-5 text-end">
                                                        Discount
                                                    </td>
                                                    <td class="text-dark fs-5 text-end">- {{ $order->discount_per }}%</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" class="text-dark fs-5 fw-bold text-end">
                                                        {{ Currency::currency($currency)->format($order->discount_value) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
