@php

    $shop_id = (isset(Auth::user()->hasOneShop->shop['id'])) ? Auth::user()->hasOneShop->shop['id'] : '';

    $shop_settings = getClientSettings($shop_id);

    // Order Settings
    $order_setting = getOrderSettings($shop_id);
    $shop_latitude = (isset($order_setting['shop_latitude'])) ? $order_setting['shop_latitude'] : '';
    $shop_longitude = (isset($order_setting['shop_longitude'])) ? $order_setting['shop_longitude'] : '';

    // Shop Currency
    $currency = (isset($shop_settings['default_currency']) && !empty($shop_settings['default_currency'])) ? $shop_settings['default_currency'] : 'EUR';

    $admin_settings = getAdminSettings();
    $google_map_api = (isset($admin_settings['google_map_api'])) ? $admin_settings['google_map_api'] : '';
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Orders Map</title>

    <link href="{{ asset('public/admin/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/admin/assets/css/custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"/>
    <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/css/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/css/toastr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/client/assets/css/custom.css') }}">
</head>
<body>

    <input type="hidden" name="shop_latitude" id="shop_latitude" value="{{ $shop_latitude }}">
    <input type="hidden" name="shop_longitude" id="shop_longitude" value="{{ $shop_longitude }}">

    <div class="order_map">
        <div id="gmap" style="height: 100vh;"></div>
    </div>

    {{-- Scripts --}}
    <script src="{{ asset('public/admin/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('public/admin/assets/vendor/js/main.js') }}"></script>
    <script src="{{ asset('public/admin/assets/vendor/js/jquery.min.js') }}"></script>
    <script src="{{ asset('public/admin/assets/vendor/js/jquery-ui.js') }}"></script>
    <script src="{{ asset('public/admin/assets/vendor/js/toastr.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://maps.google.com/maps/api/js?key={{ $google_map_api }}&libraries=places"></script>

    <script type="text/javascript">

        var map;
        var locations = @json($location_array);
        var shop_latitude = $('#shop_latitude').val();
        var shop_longitude =  $('#shop_longitude').val();
        var markersArray = [];

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
        initMap(lat_center,long_center);
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
                        clearOverlays();
                        setMarker(response.location_array);
                    }
                }
            });
        }

    </script>

</body>
</html>
