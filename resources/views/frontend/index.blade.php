<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Smart QR</title>
    <link href="{{ asset('public/admin_images/favicons/home.png') }}" rel="icon">
    <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <style>

        .bgimg {
            background-image: url("{{ asset('/public/admin_images/backgrounds/login_background.png') }}");
            height: 100vh;
            background-position: center;
            background-size: cover;
            position: relative;
            color: white;
            font-size: 25px;
        }

    </style>
</head>
<body>
    <div class="bgimg">
        <div class="d-flex align-items-center justify-content-center h-100 flex-column">
            <img src="{{ asset('public/admin_images/logos/smartqr.png') }}" width="220px">
            <hr>
            <a href="{{ route('login') }}" class="btn btn-success">Login to Access Your Account</a>
        </div>
    </div>
</body>
</html>
