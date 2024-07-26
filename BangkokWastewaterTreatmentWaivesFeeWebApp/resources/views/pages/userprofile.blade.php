@extends('layouts.app')

<!-- ================== BEGIN BASE CSS STYLE ================== -->
<link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}" rel="stylesheet" />

<link href="{{ asset('assets/plugins/datatables/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/datatables-select/css/select.dataTables.min.css') }}" rel="stylesheet" />

<link href="{{ asset('assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" />
<!-- ================== END BASE CSS STYLE ================== -->

<style>
.dropdown:hover .dropdown-menu {
    display: block;
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
      @include('components.navbar', ['active'=> 'pageuserprofile'])
      <!-- end #navbar -->

      <div class="card my-5">
        <div class="card-body">
          <h4 class="text-blue"><i class="fa-solid fa-user text-warning"></i> ชื่อผู้ยื่นแบบ/ผู้รับมอบอำนาจ</h4>
          @include('pages.form.userprofile', ['action' => 'readwrite'])
          <!-- Button save -->
          <div class="d-grid gap-2 col-6 mx-auto">
            <button class="btn btn-blue-bg btn-lg" type="button" id="btn_update_user_profile">บันทึกข้อมูลผู้ใช้งาน</button>
          </div>

          <h6 class="text-danger p-2">หมายเหตุ: ระบบจะส่งสถานะการยื่นแบบให้ทางอีเมลที่สมัครใช้งาน ซึ่งควรเป็นอีเมลเดียวกันกับที่ระบุในข้อมูลผู้ใช้งาน</h6>
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
<script src="{{ asset('assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
<!-- ================== END BASE JS ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="{{ asset('js/web-service-task.js') }}"></script>
<script src="{{ asset('js/form-userprofile.js') }}"></script>
<script src="{{ asset('js/user-profile.js') }}"></script>
<!-- ================== END PAGE LEVEL JS ================== -->
