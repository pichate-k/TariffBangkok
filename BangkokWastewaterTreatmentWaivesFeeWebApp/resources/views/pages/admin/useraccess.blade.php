@extends('layouts.app')

<!-- ================== BEGIN BASE CSS STYLE ================== -->
<link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}" rel="stylesheet" />

<link href="{{ asset('assets/plugins/datatables/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/datatables-select/css/select.dataTables.min.css') }}" rel="stylesheet" />
<!-- ================== END BASE CSS STYLE ================== -->

<style>
</style>

@section('content')

@include('components.header')

<main class="d-flex flex-nowrap">

  @include('components.leftmenu', ['active'=> 'pageadminuseraccess'])

  <div class="container-fluid my-3" style="overflow-y: auto;">

    <div class="card mt-3">
      <div class="card-body">
        <h4 class="text-blue">
          <i class="fa-solid fa-right-to-bracket text-warning"></i> รายการเข้าใช้ระบบ (1,000 รายการล่าสุด)
        </h4>
        <div class="table-responsive">
          <table class="table table-hover table-bordered" id="tb_user_access_list">
            <thead class="text-center">
              <tr>
                <td scope="col">ลำดับ</td>
                <td scope="col" class=" text-start">ชื่อผู้ใช้งาน</td>
                <td scope="col" class=" text-start">หมายเลขไอพี</td>
                <td scope="col">ช่องทางการเข้าถึง</td>
                <td scope="col">เวลาเข้าใช้งาน</td>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>

  </div>

</main>
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
<script src="{{ asset('js/user-access.js') }}"></script>
<!-- ================== END PAGE LEVEL JS ================== -->
