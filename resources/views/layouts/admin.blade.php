<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
 <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ (isset($page_title))? $page_title.' - ' : ''}}{{ env('APP_NAME', 'Transflow') }}</title>
    @include('layouts/admincss')
    @include('layouts/adminjs')
    @yield('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed sidebar-collapse">
<div class="wrapper">

  <!-- Navbar -->
  @include('layouts/header')
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  @include('partials.menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 boardtitle">{{ (isset($page_title))? $page_title : ''}}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">{{ (isset($page_title))? $page_title : ''}}</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
<!-- toastr.success('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'); -->
    @if(session('message'))
      <script type="text/javascript">
        toastr.success("{{ session('message') }}");
      </script>
    @endif
    @if(session('error_message'))
      <script type="text/javascript">
        toastr.error("{{ session('error_message') }}");
      </script>
    @endif
    @if($errors->count() > 0)
      @foreach($errors->all() as $error)
        <script type="text/javascript">
          toastr.error("{{ $error }}");
        </script>
      @endforeach
    @endif
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        @yield('content')
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2004-<?php echo date('Y');?> <a href="https://www.keypoint-tech.com/">Keypoint Technologies</a>.</strong>
    All rights reserved.
    <!-- <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.1.0-pre
    </div> -->
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- <script>
  $.widget.bridge('uibutton', $.ui.button)
</script> -->
@yield('scripts')
</body>
</html>
