<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>สำนักงานจัดการคุณภาพน้ำ กรุงเทพมหานคร</title>

  <link rel="shortcut icon" href="{{ asset('/imgs/logo_bkk_2.png') }}" />

  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet">
  <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
</head>

<body class="d-flex flex-column h-100">

  <!-- begin #components -->
  @include('components.loading')
  <!-- end #components -->

  <!-- begin #content -->
  @yield('content')
  <!-- end #content -->

  <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
