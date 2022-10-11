<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ env('APP_NAME', 'Permissions Manager') }}</title>
    <!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" /> -->
    <link href="{{ asset('login_styles/css/coreui.min.css') }}" rel="stylesheet" />
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" /> -->
    <!-- <link href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" rel="stylesheet" /> -->
    <!-- <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet" /> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" /> -->
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" /> -->
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" /> -->
    <!-- <link href="{{ asset('css/custom.css') }}" rel="stylesheet" /> -->
       <style type="text/css">
      .form-box {
    background-color: rgba(0, 0, 0, 0.5);
    margin: auto auto;
    padding: 40px;
    border-radius: 5px;
    box-shadow: 16px 15px 10px 3px #4e4e4e;
    position: absolute;
/*
  background-color: rgb(93 93 93 / 50%);*/
    
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  width: 500px;
  height: 430px;
      border-radius: 60px 10px 60px 10px;
}
.form-box .header-text {
  font-size: 32px;
  font-weight: 600;
  padding-bottom: 30px;
  text-align: center;
}
    </style>
    <style type="text/css">
    body {
  /* The image used */
  /*background-image: url("login_styles/img/login-bg.png");*/
background: linear-gradient(rgb(0 0 0 / 50%), rgb(0 0 0 / 50%)), url(login_styles/img/login-bg8.jpg);
  /* Half height */
  height: 50%;

  /* Center and scale the image nicely */
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
}
#img_logo_block {
    text-align: center;
    padding-bottom: 30px;
}
label.form-check-label {
    color: #fff;
}
a.btn.btn-link.px-0 {
    color: #fff !important;
}
    </style>
    @yield('styles')
</head>

<body class="header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden login-page">
    <div class="app flex-row align-items-center">
        <div class="container">
            @yield("content")
        </div>
    </div>
    @yield('scripts')
</body>

</html>
