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
    <div class="col-lg-4 col-md-12" style="background-color: #f6f6f6f0;">
      <div class="d-flex align-items-center flex-column h-100">
        <div class="mb-auto">
        </div>
        <div class="mb-auto w-75 p-2">
          <div class="row justify-content-center align-items-center">
            <div class="col-12">
              <img class="mx-auto d-block img-fluid" src="{{ asset('imgs/logo_bkk_2.png') }}" alt="" width="150">
              <h5 class="text-center text-blue mt-3">ลืมรหัสผ่าน</h5>
              <p class="text-center text-blue mt-3">รีเซ็ตรหัสผ่านด้วยอีเมลที่ใช้งาน</p>
              <form class="mt-3" method="POST" action="/service/user/forgotpassword">
                @csrf
                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label text-blue">อีเมล <span class="text-danger">*</span></label>
                  <input type="text" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" name="username" autofocus>
                  @error('username')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
                <div class="d-grid gap-2 mb-5">
                  <button type="submit" class="btn btn-blue-bg" id="btn-login">ยืนยันรีเซ็ตรหัสผ่าน</button>
                </div>
                <div class="d-grid gap-2 mt-5">
                  <a type="button" href="/login.htm" class="btn btn-secondary">กลับสู่หน้าเข้าสุ่ระบบ</a>
                </div>

                @if (session('statusNOK'))
                  <div class="alert alert-danger mt-3" role="alert">
                    {{ session('statusNOK') }}
                  </div>
               @endif
               @if (session('statusOK'))
                 <div class="alert alert-success mt-3" role="alert">
                   {{ session('statusOK') }}
                 </div>
               @endif

              </form>
            </div>
          </div>
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
