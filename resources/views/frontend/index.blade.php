<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Smart QR™ | Just Scan It!</title>
    <link href="{{ asset('public/admin_images/favicons/home.png') }}" rel="icon">
    <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"/>
<style>

.bgimg {
	background-image: url("{{ asset('/public/admin_images/backgrounds/fp-smartqr.jpg') }}");
	/*height: 100vh;*/
	background-position: center;
	background-size: cover;
	position: relative;
	background-repeat:repeat;
	color: white;
	font-size: 25px;
}
.title-yan {
	margin: 20px auto 10px;
	font-family: 'Roboto', Arial, sans-serif;
	font-size: 38px;
	line-height: 50px;
	font-weight: 700;
	color: #fff;
}
.subtitle-yan {
	padding-top: 20px;
	font-family: 'Open Sans', Arial ,sans-serif;
	font-size: 23px;
	font-weight: 400;
	color: #EFEFEF;
	padding-bottom: 20px;
}
.header ul li {
    margin:0 15px;
}
.sale_number {
    display: flex;
    flex-direction: column;
    text-align: end;
}

.sale_number span {
    font-size:14px;
}
.login_bt {
    padding:10px 25px;
    border:1px solid #000;
    background:#fff;
    font-size:15px;
    color:#000;
    transition:all 0.5s ease-in-out;
    border-radius:30px;
}
.login_bt: hover {
    border:1px solid #000;
    box-shadow:0 8px 0 #000;
    color:#000;
    background:#fff;
}
.trial_bt {
    padding:10px 25px;
    background:#ffe01b;
    border:1px solid #000;
    color:#000;
    font-size:15px;
    border-radius:30px;
    transition:all 0.5s ease-in-out;
}
.trial_bt:hover {
    border:1px solid #000;
    background:#ffe01b;
    box-shadow:0 8px 0 #000;
    color:#000;
}
.logo_li {
    position: absolute;
    top: 15px;
    left: 48%;
    transform: translate(-48%, 0);
}
.logo {
    display:none;
}
.header .navbar {
    background: transparent;
}

@media(max-width:767px) {
.logo {
        display: block;
    }
.logo_li {
        display: none;
    }
}
/*div.Logo {*/
/*  text-align: center;*/
/*}*/

 </style>
</head>

<body>
    <div class="bgimg">
        <div class="header">
            <nav class="navbar navbar-expand-lg navbar-dark">
                <div class="container">
                <a class="navbar-brand logo" href="https://www.smartqr.gr/">Smart QR™</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent" align="center">
                    <!--<div class="Logo" >Smart QR™</div>-->
                  <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                      <li class="logo_li">
                          <a class="navbar-brand m-0" href="https://www.smartqr.gr">Smart QR™</a>
                      </li>
                    <li class="nav-item">
                      <a class="nav-link active sale_number" aria-current="page" href="tel:+306937008015">
                          <span>Sales</span>
                          <span>+30 693 700 8015</span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#"><i class="fa-solid fa-globe"></i></a>
                    </li>
                    <li class="nav-item">
                      <a href="{{ route('login') }}" class="login_bt btn">Login</a>
                    </li>
                    <li class="nav-item">
                      <button class="trial_bt btn">Free Trial</button>
                    </li>
                  </ul>
                </div>
              </div>
            </nav>
        </div>
        <div class="d-flex align-items-center justify-content-center flex-column" style="height:100vh;">
    		<h1 class="title-yan">New Way To View a Catalog</h1>
            <img src="{{ asset('public/admin_images/logos/smartqr-logo.png') }}" width="350px">
    		<p class="subtitle-yan">Smart Catalog Solution Powered by Yan Studio™ </p>
        </div>
    </div>

    <script src="{{ asset('public/admin/assets/vendor/bootstrap/js/bootstrap.min.js') }}" ></script>
</body>
</html>
