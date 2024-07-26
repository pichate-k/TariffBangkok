@extends('layouts.app')

<!-- ================== BEGIN BASE CSS STYLE ================== -->
<link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}" rel="stylesheet" />

<link href="{{ asset('assets/plugins/datatables/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/datatables-select/css/select.dataTables.min.css') }}" rel="stylesheet" />
<!-- ================== END BASE CSS STYLE ================== -->

<style>
.dropdown:hover .dropdown-menu {
    display: block;
}
input.form-control {
  background-color: #fff !important;;
}
</style>

@section('content')
<main class="flex-shrink-0">
  <div class="container-fluid h-100">

    <!-- begin #navbar -->
    @include('components.header')
    <!-- end #navbar -->

    <div class="container">
      <!-- begin #navbar -->
      @include('components.navbar', ['active'=> 'pageuserchangepassword'])
      <!-- end #navbar -->

      <div class="d-flex justify-content-center mt-5">
        <div class="card w-50">
          <div class="card-body">
            <h4 class="mb-3 text-blue"><i class="fa-solid fa-key text-warning"></i> เปลี่ยนรหัสผ่าน</h4>
            <form id="fm_user_change_password">
              <div class="form-floating mb-3">
                <input type="password" class="form-control" name="old_password">
                <label for="old_password">รหัสผ่านเดิม</label>
              </div>
              <div class="form-floating mb-3">
                <input type="password" class="form-control" name="new_password">
                <label for="new_password">รหัสผ่านใหม่</label>
              </div>
              <div class="form-floating mb-3">
                <input type="password" class="form-control" name="confirm_password">
                <label for="confirm_password">ยืนยันรหัสผ่าน</label>
              </div>
            </form>
            <button type="button" class="btn btn-blue-bg" id="btn_change_password">บันทึกรหัสผ่านใหม่</button>
          </div>
        </div>
      </div>


    </div>
  </div>
</main>

<!-- begin #footer -->
@include('components.footer')
<!-- end #footer -->
</div>
@endsection


<!-- ================== BEGIN BASE JS ================== -->
<script src="{{ asset('assets/plugins/jquery/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

<script src="{{ asset('assets/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-select/js/dataTables.select.min.js') }}"></script>

<script src="{{ asset('assets/plugins/moment/js/moment-with-locales.min.js') }}"></script>
<!-- ================== END BASE JS ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="{{ asset('js/web-service-task.js') }}"></script>
<script src="{{ asset('js/user-change-password.js') }}"></script>
<!-- ================== END PAGE LEVEL JS ================== -->
