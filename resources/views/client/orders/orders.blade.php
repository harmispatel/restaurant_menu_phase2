@php

    $shop_id = (isset(Auth::user()->hasOneShop->shop['id'])) ? Auth::user()->hasOneShop->shop['id'] : '';

    $shop_settings = getClientSettings($shop_id);

    // Order Settings
    $order_setting = getOrderSettings($shop_id);
    $shop_latitude = (isset($order_setting['shop_latitude'])) ? $order_setting['shop_latitude'] : '';
    $shop_longitude = (isset($order_setting['shop_longitude'])) ? $order_setting['shop_longitude'] : '';
    $google_map_order_view = (isset($order_setting['google_map_order_view']) && $order_setting['google_map_order_view'] == 1) ? $order_setting['google_map_order_view'] : 0;

    $default_code_page = (isset($order_setting['default_code_page']) && !empty($order_setting['default_code_page'])) ? $order_setting['default_code_page'] : '';
    $code_page_settings = getCodePageSettings($default_code_page);
    $code_page_key = (isset($code_page_settings['key']) && !empty($code_page_settings['key'])) ? $code_page_settings['key'] : 14;
    $code_page_value = (isset($code_page_settings['value']) && !empty($code_page_settings['value'])) ? $code_page_settings['value'] : 737;

    // Default Printer
    $default_printer = (isset($order_setting['default_printer']) && !empty($order_setting['default_printer'])) ? $order_setting['default_printer'] : 'Microsoft Print to PDF';
    // Printer Paper
    $printer_paper = (isset($order_setting['printer_paper']) && !empty($order_setting['printer_paper'])) ? $order_setting['printer_paper'] : 'A4';
    // Printer Tray
    $printer_tray = (isset($order_setting['printer_tray']) && !empty($order_setting['printer_tray'])) ? $order_setting['printer_tray'] : '';
    // Auto Print
    $auto_print = (isset($order_setting['auto_print']) && !empty($order_setting['auto_print'])) ? $order_setting['auto_print'] : 0;
    // Enable Print
    $enable_print = (isset($order_setting['enable_print']) && !empty($order_setting['enable_print'])) ? $order_setting['enable_print'] : 0;
    // Print Font Size
    $printFontSize = (isset($order_setting['print_font_size']) && !empty($order_setting['print_font_size'])) ? $order_setting['print_font_size'] : 20;

    // Shop Currency
    $currency = (isset($shop_settings['default_currency']) && !empty($shop_settings['default_currency'])) ? $shop_settings['default_currency'] : 'EUR';

    $admin_settings = getAdminSettings();
    $google_map_api = (isset($admin_settings['google_map_api'])) ? $admin_settings['google_map_api'] : '';
@endphp

@extends('client.layouts.client-layout')

@section('title', __('Orders'))

@section('content')

    <input type="hidden" name="default_printer" id="default_printer" value="{{ $default_printer }}">
    <input type="hidden" name="printer_paper" id="printer_paper" value="{{ $printer_paper }}">
    <input type="hidden" name="printer_tray" id="printer_tray" value="{{ $printer_tray }}">
    <input type="hidden" name="auto_print" id="auto_print" value="{{ $auto_print }}">
    <input type="hidden" name="shop_latitude" id="shop_latitude" value="{{ $shop_latitude }}">
    <input type="hidden" name="shop_longitude" id="shop_longitude" value="{{ $shop_longitude }}">

    {{-- Page Title --}}
    <div class="pagetitle">
        <h1>{{ __('Orders')}}</h1>
        <div class="row">
            <div class="col-md-8">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('Orders') }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    {{-- Orders Section --}}
    <section class="section dashboard">
        <div class="row">

            <div class="col-md-12 mb-3" id="print-data" style="display: none;"></div>

            <div class="col-md-12 mb-3 text-end">
                <div class="d-flex justify-content-end align-items-center">
                    <div class="form-check form-switch">
                        @php
                            $newStatus = ($google_map_order_view == 1) ? 0 : 1;
                        @endphp
                        <input class="form-check-input" type="checkbox" name="status" role="switch" id="status" onclick="changeMapView({{ $newStatus }})" value="1" {{ ($google_map_order_view == 1) ? 'checked' : '' }}  data-bs-toggle="tooltip" title="Enabled/Disable Map View Orders">
                    </div>
                    <a class="btn btn-sm btn-primary ms-3" target="_blank" href="{{ route('client.orders.map') }}">Full Screen</a>
                </div>
            </div>

            @if($google_map_order_view == 1)
                <div class="col-md-12 mb-3">
                    <svg style=" fill: #0d6efd;" xmlns="http://www.w3.org/2000/svg" height="30px" width="30px" viewBox="0 0 384 512"><path d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"/></svg> Accepted
                    <svg class="ms-2" style="fill: #fabe09" xmlns="http://www.w3.org/2000/svg" height="30px" width="30px" viewBox="0 0 384 512"><path d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"/></svg> Pending
                </div>
                <div class="col-md-12 mb-3">
                    <div id="gmap" style="height: 500px;"></div>
                </div>
            @endif

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body" id="order">
                        @forelse ($orders as $order)
                            @php
                                $discount_type = (isset($order->discount_type) && !empty($order->discount_type)) ? $order->discount_type : 'percentage';
                            @endphp
                            <div class="order">
                                <div class="order-btn d-flex align-items-center justify-content-end">
                                    <div class="d-flex align-items-center flex-wrap">{{ __('Estimated time of arrival') }} <input type="number" name="estimated_time" onchange="changeEstimatedTime(this)" id="estimated_time" value="{{ $order->estimated_time }}" class="form-control mx-1 estimated_time" style="width: 100px!important" ord-id="{{ $order->id }}" {{ ($order->order_status == 'accepted') ? 'disabled' : '' }}> {{ __('Minutes') }}.
                                    </div>
                                    @if($order->order_status == 'pending')
                                        <a class="btn btn-sm btn-primary ms-3" onclick="acceptOrder({{ $order->id }})"><i class="bi bi-check-circle" data-bs-toggle="tooltip" title="Accept"></i> {{ __('Accept') }}</a>
                                        <a class="btn btn-sm btn-danger ms-3" onclick="rejectOrder({{ $order->id }})"><i class="bi bi-x-circle" data-bs-toggle="tooltip" title="Reject"></i> {{ __('Reject') }}</a>
                                    @elseif($order->order_status == 'accepted')
                                        <a class="btn btn-sm btn-success ms-3" onclick="finalizedOrder({{ $order->id }})"><i class="bi bi-check-circle" data-bs-toggle="tooltip" title="Complete"></i> {{ __('Finalize') }}</a>
                                    @endif

                                    @if($enable_print == 1)
                                        <a class="btn btn-sm btn-primary ms-3" onclick="printReceipt({{ $order->id }})"><i class="bi bi-printer"></i> Print</a>
                                    @endif
                                </div>
                                <div class="order-info">
                                    <ul>
                                        <li><strong>#{{ $order->order_id }}</strong></li>
                                        <li><strong>{{ __('Order Date') }} : </strong>{{ date('d-m-Y h:i:s',strtotime($order->created_at)) }}</li>
                                        <li><strong>{{ __('Order Type') }} : </strong>{{ $order->checkout_type }}</li>
                                        <li><strong>{{ __('Payment Method') }} : </strong>{{ $order->payment_method }}</li>
                                        @if($order->checkout_type == 'takeaway')
                                            <li><strong>{{ __('Customer') }} : </strong> {{ $order->firstname }} {{ $order->lastname }}</li>
                                            <li><strong>{{ __('Telephone') }} : </strong> {{ $order->phone }}</li>
                                            <li><strong>{{ __('Email') }} : </strong> {{ $order->email }}</li>
                                        @elseif($order->checkout_type == 'table_service')
                                            <li><strong>{{ __('Table No.') }} : </strong> {{ $order->table }}</li>
                                        @elseif($order->checkout_type == 'room_delivery')
                                            <li><strong>{{ __('Customer') }} : </strong> {{ $order->firstname }} {{ $order->lastname }}</li>
                                            <li><strong>{{ __('Room No.') }} : </strong> {{ $order->room }}</li>
                                            @if(!empty($order->delivery_time ))
                                                <li><strong>{{ __('Delivery Time') }} : </strong> {{ $order->delivery_time }}</li>
                                            @endif
                                        @elseif($order->checkout_type == 'delivery')
                                            <li><strong>{{ __('Customer') }} : </strong> {{ $order->firstname }} {{ $order->lastname }}</li>
                                            <li><strong>{{ __('Telephone') }} : </strong> {{ $order->phone }}</li>
                                            <li><strong>{{ __('Email') }} : </strong> {{ $order->email }}</li>
                                            <li><strong>{{ __('Address') }} : </strong> {{ $order->address }}</li>
                                            <li><strong>{{ __('Street Number') }} : </strong> {{ $order->street_number }}</li>
                                            <li><strong>{{ __('Floor') }} : </strong> {{ $order->floor }}</li>
                                            <li><strong>{{ __('Door Bell') }} : </strong> {{ $order->door_bell }}</li>
                                            <li><strong>{{ __('Google Map') }} : </strong> <a href="https://maps.google.com?q={{ $order->address }}" target="_blank">Address Link</a></li>
                                            <li><strong>{{ __('Comments') }} : </strong> {{ $order->instructions }}</li>
                                        @endif
                                    </ul>
                                </div>
                                <hr>
                                <div class="order-info mt-2">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <table class="table">
                                                @php
                                                    $total_amount = $order->order_total;
                                                @endphp
                                                <tr>
                                                    <td><b>{{ __('Sub Total') }}</b></td>
                                                    <td class="text-end">{{ Currency::currency($currency)->format($total_amount) }}</td>
                                                </tr>
                                                @if($order->discount_per > 0)
                                                    <td><b>{{ __('Discount') }}</b></td>
                                                    @if($discount_type == 'fixed')
                                                        @php $discount_amount = $order->discount_per; @endphp
                                                        <td class="text-end">- {{ Currency::currency($currency)->format($order->discount_per) }}</td>
                                                    @else
                                                        @php $discount_amount = ($total_amount * $order->discount_per) / 100; @endphp
                                                        <td class="text-end">- {{ $order->discount_per }}%</td>
                                                    @endif
                                                    @php
                                                        $total_amount = $total_amount - $discount_amount;
                                                    @endphp
                                                @endif
                                                @if(($order->payment_method == 'paypal' || $order->payment_method == 'every_pay') && $order->tip > 0)
                                                    @php
                                                        $total_amount = $total_amount + $order->tip;
                                                    @endphp
                                                    <tr>
                                                        <td><b>{{ __('Tip') }}</b></td>
                                                        <td class="text-end">+ {{ Currency::currency($currency)->format($order->tip) }}</td>
                                                    </tr>
                                                @endif
                                                <tr class="text-end">
                                                    <td colspan="2"><strong>{{ Currency::currency($currency)->format($total_amount) }}</strong></td>
                                                </tr>
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
    <script src="{{ asset('public/admin/assets/js/jsprintmanager.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bluebird/3.3.5/bluebird.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script type="text/javascript" src="https://maps.google.com/maps/api/js?key={{ $google_map_api }}&libraries=places"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/gh/SheetJS/js-codepage/dist/cptable.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/gh/SheetJS/js-codepage/dist/cputils.js"></script>
    <script src="https://jsprintmanager.azurewebsites.net/scripts/JSESCPOSBuilder.js"></script>
    <script src="https://jsprintmanager.azurewebsites.net/scripts/zip.js"></script>
    <script src="https://jsprintmanager.azurewebsites.net/scripts/zip-ext.js"></script>
    <script src="https://jsprintmanager.azurewebsites.net/scripts/deflate.js"></script>

    <script type="text/javascript">

        var map;
        var enablePrint = "{{ $enable_print }}";
        var printFontSize = "{{ $printFontSize }}";
        var locations = @json($location_array);
        var shop_latitude = $('#shop_latitude').val();
        var shop_longitude =  $('#shop_longitude').val();
        var markersArray = [];
        var google_map_order_view = @json($google_map_order_view);
        var code_page_key = parseInt(@json($code_page_key));
        var code_page_value = parseInt(@json($code_page_value));

        if(shop_latitude == '' || isNaN(shop_latitude) || shop_longitude == '' || isNaN(shop_longitude))
        {
            navigator.geolocation.getCurrentPosition(
                function (position)
                {
                    $('#shop_latitude').val(position.coords.latitude);
                    $('#shop_longitude').val(position.coords.longitude);
                },
                function errorCallback(error)
                {
                    console.log(error)
                }
            );
        }

        var lat_center = $('#shop_latitude').val();
        var long_center = $('#shop_longitude').val();

        // Initialize Map
        if(google_map_order_view == 1){
            initMap(lat_center,long_center);
        }
        function initMap(lat,long)
        {
            const myLatLng = { lat: parseFloat(lat), lng: parseFloat(long) };
            map = new google.maps.Map(document.getElementById("gmap"), {
                zoom: 12,
                center: myLatLng,
            });

            setMarker(locations);
        }


        // Function For Add Marker
        function setMarker(orderLocations)
        {
            var marker, i, text;

            for (i = 0; i < orderLocations.length; i++)
            {

                var newLatlng = { lat: parseFloat(orderLocations[i][1]), lng: parseFloat(orderLocations[i][2]) };
                var fillColor = '#f00';
                var contentString = "<div class='infowindow-container'>" +
                    "<div class='inner'><ul><li class='text-capitalize'><strong>Order Status : </strong>"+orderLocations[i][3]+"</li><li><strong>Order Number : </strong>"+orderLocations[i][4]+"</li><li><strong>Total Amount : </strong>"+orderLocations[i][5]+"</li></ul></div></div>";

                if(orderLocations[i][3] == 'pending')
                {
                    fillColor = '#ffc107';
                }
                else if(orderLocations[i][3] == 'accepted')
                {
                    fillColor = '#0d6efd';
                }

                marker = new google.maps.Marker({
                    position: newLatlng,
                    map: map,
                    animation: google.maps.Animation.BOUNCE,
                    icon: {
                        path: "M7.8,1.3L7.8,1.3C6-0.4,3.1-0.4,1.3,1.3c-1.8,1.7-1.8,4.6-0.1,6.3c0,0,0,0,0.1,0.1" +
                                "l3.2,3.2l3.2-3.2C9.6,6,9.6,3.2,7.8,1.3C7.9,1.4,7.9,1.4,7.8,1.3z M4.6,5.8c-0.7,0-1.3-0.6-1.3-1.4c0-0.7,0.6-1.3,1.4-1.3" +
                                "c0.7,0,1.3,0.6,1.3,1.3C5.9,5.3,5.3,5.9,4.6,5.8z",
                        strokeColor: '#00000',
                        fillColor: fillColor,
                        fillOpacity: 2.0,
                        scale: 3
                    },
                });

                let infowindow = new google.maps.InfoWindow({
                    content: contentString,
                });

                marker.addListener('mouseover', function()
                {
                    infowindow.open(map, this);
                });

                marker.addListener("mouseout", function() {
                    infowindow.close();
                });

                markersArray.push(marker);
            }
        }


        // Function for Remove Markers
        function clearOverlays()
        {
            if (markersArray)
            {
                for (i in markersArray)
                {
                    markersArray[i].setMap(null);
                }
            }
            markersArray = [];
        }


        if(enablePrint == 1)
        {
            JSPM.JSPrintManager.license_url = "{{ route('jspm') }}";
            JSPM.JSPrintManager.auto_reconnect = true;
            JSPM.JSPrintManager.start();
        }


        function printReceipt(ordID)
        {
            if(jspmWSStatus())
            {
                $.ajax({
                    type: "POST",
                    url: "{{ route('order.receipt') }}",
                    data: {
                        "_token":"{{ csrf_token() }}",
                        "order_id" : ordID,
                    },
                    dataType: "JSON",
                    success: function (response)
                    {
                        if(response.success == 1)
                        {
                            if (jspmWSStatus())
                            {
                                const raw_printing = response?.raw_printing;

                                //Create a ClientPrintJob
                                var cpj = new JSPM.ClientPrintJob();

                                //Set Printer info
                                var myPrinter = new JSPM.InstalledPrinter($('#default_printer').val());
                                myPrinter.paperName = $('#printer_paper').val();
                                myPrinter.trayName = $('#printer_tray').val();
                                cpj.clientPrinter = myPrinter;

                                if(raw_printing == 1)
                                {
                                    const print_data = response.data;
                                    var escpos = Neodynamic.JSESCPOSBuilder;
                                    var doc = new escpos.Document();

                                    var escposCommands = doc
                                    .font(escpos.FontFamily.A)
                                    .align(escpos.TextAlignment.LeftJustification)
                                    .style([escpos.FontStyle.Bold])
                                    .size(2)
                                    .setCharacterCodeTable(code_page_key)
                                    .text(print_data.receipt_intro, code_page_value)
                                    .drawLine(1);

                                    escposCommands = escposCommands
                                    .feed(1)
                                    .font(escpos.FontFamily.A)
                                    .text(@json(__('Order'))+" "+@json(__('Id'))+" : "+ print_data.order_inv, code_page_value)
                                    .text(@json(__('Order Type'))+" : "+print_data.checkout_type, code_page_value)
                                    .text(@json(__('Payment Method'))+" : "+print_data.payment_method, code_page_value)
                                    .text(@json(__('Order Date'))+" : "+print_data.order_date, code_page_value);

                                    if(print_data.checkout_type == 'takeaway' || print_data.checkout_type == 'delivery' || print_data.checkout_type == 'room_delivery'){
                                        escposCommands  = escposCommands
                                        .text(@json(__('Customer'))+" : "+print_data.customer, code_page_value);
                                    }

                                    if(print_data.checkout_type == 'delivery'){
                                        escposCommands = escposCommands
                                        .text(@json(__('Address'))+" : "+print_data.address, code_page_value)
                                        .text(@json(__('Street Number'))+" : "+print_data.street_number, code_page_value)
                                        .text(@json(__('Floor'))+" : "+print_data.floor, code_page_value)
                                        .text(@json(__('Door Bell'))+" : "+print_data.door_bell, code_page_value);
                                    }

                                    if(print_data.checkout_type == 'room_delivery'){
                                        escposCommands = escposCommands
                                        .text(@json(__('Room No.'))+" : "+print_data.room_no, code_page_value)
                                        .text(@json(__('Delivery Time'))+" : "+print_data.delivery_time, code_page_value);
                                    }

                                    if(print_data.checkout_type == 'table_service'){
                                        escposCommands = escposCommands
                                        .text(@json(__('Table No.'))+" : "+print_data.table_no, code_page_value);
                                    }

                                    if(print_data.checkout_type == 'takeaway' || print_data.checkout_type == 'delivery'){
                                        escposCommands = escposCommands
                                        .text(@json(__('Telephone'))+" : "+print_data.phone, code_page_value);
                                        // .text(@json(__('Email'))+" : "+print_data.email, code_page_value);
                                    }

                                    escposCommands = escposCommands
                                    .drawLine(1)
                                    .font(escpos.FontFamily.A)
                                    .size(2);

                                    escposCommands = escposCommands
                                    .feed(1);

                                    if(print_data.items.length > 0){
                                        print_data.items.forEach(item => {
                                            escposCommands = escposCommands
                                            .text(@json(__('Item'))+" "+@json(__('Name'))+" : " + item.item_name,737)
                                            .text(@json(__('Qty.'))+" : " + item.item_qty,737);

                                            if(item.options != ''){
                                                escposCommands = escposCommands
                                                .text(@json(__('Options'))+": " + item.options,737)
                                            }

                                            escposCommands = escposCommands
                                            .text(@json(__('Price'))+" : " + item.sub_total_text + "\u20AC",737).feed(1);
                                        });
                                    }

                                    escposCommands = escposCommands
                                    .drawLine(1);

                                    escposCommands = escposCommands
                                    .text(@json(__('Comments'))+" : "+ print_data.instructions, code_page_value);

                                    escposCommands = escposCommands
                                    .drawLine(1)
                                    .text(@json(__('Sub Total'))+" : " + print_data.subtotal + "\u20AC",737);

                                    if(print_data.discount != 0){
                                        escposCommands = escposCommands
                                        .feed(1)
                                        .text(@json(__('Discount'))+" : " + print_data.discount ,737);
                                    }

                                    if(print_data.tip != 0){
                                        escposCommands = escposCommands
                                        .feed(1)
                                        .text(@json(__('Tip'))+" : " + print_data.tip + "\u20AC",737);
                                    }

                                    escposCommands = escposCommands
                                    .text(@json(__('Total Amount'))+" : " + print_data.total_amount + "\u20AC",737);

                                    escposCommands = escposCommands
                                    .drawLine(1)
                                    .text("T:"+print_data.shop_telephone+" "+"M:"+print_data.shop_mobile+" "+print_data.shop_address+", "+print_data.shop_city, code_page_value)
                                    .text("Thank You", code_page_value)
                                    .drawLine(1);

                                    escposCommands = escposCommands
                                    .feed(5)
                                    .cut()
                                    .generateUInt8Array();

                                    // Set the ESC/POS commands
                                    cpj.binaryPrinterCommands = escposCommands;

                                    // Send print job to printer!
                                    cpj.sendToClient();
                                }
                                else
                                {
                                    $('#print-data').html('');
                                    $('#print-data').append(response.data);
                                    $('#print-data').show();
                                    $('#print-data .card-body').attr('style','font-size:'+printFontSize+'px;')

                                    html2canvas(document.getElementById('print-data'), { scale: 5 }).then(function (canvas)
                                    {
                                        //Set content to print...
                                        var b64Prefix = "data:image/png;base64,";
                                        var imgBase64DataUri = canvas.toDataURL("image/png");
                                        var imgBase64Content = imgBase64DataUri.substring(b64Prefix.length, imgBase64DataUri.length);

                                        var myImageFile = new JSPM.PrintFile(imgBase64Content, JSPM.FileSourceType.Base64, 'ORD-INVOICE.PNG', 1);

                                        //add file to print job
                                        cpj.files.push(myImageFile);

                                        // Send print job to printer!
                                        cpj.sendToClient();
                                    });
                                    $('#print-data').hide();
                                }
                            }
                        }
                        else
                        {
                            toastr.error(response.message);
                        }
                    }
                });
            }
        }

        //Check JSPM WebSocket status
        function jspmWSStatus()
        {
            if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Open)
                return true;
            else if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Closed) {
                alert('JSPrintManager (JSPM) is not installed or not running! Download JSPM Client App from https://neodynamic.com/downloads/jspm');
                return false;
            }
            else if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Blocked) {
                alert('JSPM has blocked this website!');
                return false;
            }
        }


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
        function changeEstimatedTime(ele)
        {
            var time = $(ele).val();
            var ord_id = $(ele).attr('ord-id');

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
        }


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
                        var auto_print = $('#auto_print').val();

                        toastr.success(response.message);

                        if(auto_print == 1 && enablePrint == 1)
                        {
                            printReceipt(ordID);
                            setTimeout(() => {
                                location.reload();
                            }, 2500);
                        }
                        else
                        {
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        }

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


        // Function for Finalized Order
        function finalizedOrder(ordID)
        {
            $.ajax({
                type: "POST",
                url: "{{ route('finalized.order') }}",
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


        // Function for Reject Order
        function rejectOrder(ordID)
        {

            swal({
                title: "Enter Reason for Reject Order.",
                icon: "info",
                buttons: true,
                dangerMode: true,
                content: {
                    element: "input",
                    attributes: {
                        placeholder: "Enter Your Reason",
                        type: "text",
                    },
                },
                closeOnClickOutside: false,
            })
            .then((reasonResponse) =>
            {
                if (reasonResponse == '')
                {
                    swal("Please Enter Reason to Reject Order!", {
                        icon: "info",
                    });
                    return false;
                }
                else if(reasonResponse == null)
                {
                    return false;
                }
                else
                {
                    swal({
                        title: "Are you sure You want to Reject this Order ?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willRejectThisOrder) =>
                    {
                        if (willRejectThisOrder)
                        {
                            $.ajax({
                                type: "POST",
                                url: "{{ route('reject.order') }}",
                                data: {
                                    "_token":"{{ csrf_token() }}",
                                    "order_id":ordID,
                                    "reject_reason":reasonResponse,
                                },
                                dataType: "JSON",
                                success: function (response)
                                {
                                    if(response.success == 1)
                                    {
                                        toastr.success(response.message);
                                        setTimeout(() => {
                                            location.reload();
                                        }, 1000);
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
                        else
                        {
                            swal("Cancelled", "", "error");
                        }
                    });
                }
            });
        }


        // Function for get New Orders
        setInterval(() =>
        {
            getNewOrders();
        }, 10000);


        function getNewOrders()
        {
            $.ajax({
                type: "GET",
                url: "{{ route('new.orders') }}",
                dataType: "JSON",
                success: function (response)
                {
                    if(response.success == 1)
                    {
                        $('#order').html('');
                        $('#order').append(response.data);
                        clearOverlays();
                        setMarker(response.location_array);
                    }
                }
            });
        }

        // Function for Change Map View
        function changeMapView(status)
        {
            $.ajax({
                type: "POST",
                url: '{{ route("map.view.order.setting") }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'status':status,
                },
                dataType: 'JSON',
                success: function(response)
                {
                    if (response.success == 1)
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
