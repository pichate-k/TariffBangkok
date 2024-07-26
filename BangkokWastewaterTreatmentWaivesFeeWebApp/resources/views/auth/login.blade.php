@extends('layouts.guest')

<!-- ================== BEGIN BASE CSS STYLE ================== -->
<link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}" rel="stylesheet" />
<!-- ================== END BASE CSS STYLE ================== -->

<style>
.bg {
  /* The image used */
  background-image: url("/imgs/login_bg.png");
  /* Full height */
  height: 100%;
  /* Center and scale the image nicely */
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
}

input.form-control {
  background-color: #fff !important;;
}
</style>

@section('content')
<div class="container-fluid h-100">
  <div class="row h-100 bg">
    <div class="col-lg-8 d-none d-lg-block ps-0">
      <span class="fs-3 text-dark bg-white opacity-50 ms-0 m-2 p-2">ระบบการยื่นแบบคำร้อง/คำขอ/รายงาน ที่เกี่ยวข้องกับการจัดเก็บค่าธรรมเนียมบำบัดน้ำเสีย</span>
    </div>
    <!-- <div class="col-lg-4 col-md-12" style="background-color: #ffffffd6;"> -->
    <div class="col-lg-4 col-md-12" style="background-color: #f6f6f6f0;">
      <div class="d-flex align-items-center flex-column h-100">
        <div class="mb-auto">
        </div>
        <div class="mb-auto w-75 p-2">
          <div class="row justify-content-center align-items-center">
            <div class="col-12">
              <img class="mx-auto d-block img-fluid" src="{{ asset('imgs/logo_bkk_2.png') }}" alt="" width="150">
              <form class="mt-4" method="POST" action="/service/user/login">
                @csrf
                <div class="mb-2">
                  <label for="exampleFormControlInput1" class="form-label text-muted">ชื่อผู้ใช้งาน</label>
                  <input type="text" class="form-control bg-secondary" name="username" autofocus>
                </div>
                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label text-muted">รหัสผ่าน</label>
                  <input type="password" class="form-control" name="password">
                </div>
                <div class="d-flex justify-content-between align-items-center">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label text-muted" for="flexCheckDefault">จดจำฉัน</label>
                  </div>
                  <a type="button" href="/forgotpassword.htm" class="btn text-muted">ลืมรหัสผ่าน</a>
                </div>
                <div class="d-grid gap-2 mb-3">
                  <button type="submit" class="btn btn-blue-bg" id="btn-login">เข้าสู่ระบบ</button>
                </div>
                <div class="d-grid gap-2 mb-2">
                  <a type="button" href="/register.htm" class="btn btn-secondary">ลงทะเบียนเข้าใช้งาน</a>
                </div>
                @if($errors->any())
                 <div class="alert alert-danger">
                   @foreach ($errors->all() as $error)
                     <span><i class="fas fa-times-circle fa-lg"></i> {{ $error }}</span>
                   @endforeach
                 </div>
                 @endif
              </form>
            </div>
          </div>
        </div>
        <div class="p-2 text-center">
          <p class="text-muted mb-0 mx-2">กลุ่มงานระบบข้อมูลและบริหารการจัดเก็บค่าธรรมเนียม</p>
          <p class="text-muted mb-0 mx-2">สำนักงานจัดการคุณภาพน้ำ สำนักการระบายน้ำ</p>
          <p class="text-muted mx-2">โทร. 02-203-2657 อีเมล wastewatertariff@bangkok.go.th</p>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection


<!-- ================== BEGIN BASE JS ================== -->
<script src="{{ asset('assets/plugins/jquery/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<!-- ================== END BASE JS ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<!-- ================== END PAGE LEVEL JS ================== -->
