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

  @include('components.leftmenu', ['active'=> 'pageadminuserlist'])

  <div class="container-fluid my-2" style="overflow-y: auto;">

    <div class="card mt-3">
      <div class="card-body">
        <h4 class="text-blue">
          <i class="fa-solid fa-user-group text-warning"></i> รายชื่อผู้ใช้งาน
        </h4>
        <div class="table-responsive mt-3">
          <table class="table table-hover table-bordered" id="tb_user_list">
            <thead class="text-center">
              <tr>
                <td scope="col">ลำดับ</td>
                <td scope="col" class=" text-start">ชื่อผู้ใช้งาน</td>
                <td scope="col" class=" text-start">ชื่อ-นามสกุล</td>
                <td scope="col">โทรศัพท์เคลื่อนที่</td>
                <td scope="col">อีเมล</td>
                <td scope="col">วันที่เข้าใช้งานล่าสุด</td>
                <td scope="col">รายละเอียด</td>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>

  </div>

</main>

<!-- Modal -->
<div class="modal fade" id="modalUserDetail" tabindex="-1" aria-labelledby="modalUserDetailLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="modalUserDetailLabel">รายละเอียดผู้ใช้งาน</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        @include('pages.form.userprofile', ['action' => 'readonly'])
        <hr>
        <form id="fm_user_detail">
          <div class="mb-3 row align-items-center">
            <label class="col-2 col-form-label text-end">ชื่อผู้ใช้งาน</label>
            <div class="col-4">
              <input type="text" class="form-control" name="username" readonly>
            </div>
          </div>
          <div class="mb-3 row align-items-center">
            <label class="col-2 col-form-label text-end">แก้ไขข้อมูลล่าสุด</label>
            <div class="col-4">
              <input type="text" class="form-control" name="last_update_date" readonly>
            </div>
            <label class="col-2 col-form-label text-end">วันที่เข้าสู่ระบบล่าสุด</label>
            <div class="col-4">
              <input type="text" class="form-control" name="last_login_dt" readonly>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
      </div>
    </div>
  </div>
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
<script src="{{ asset('js/user-list.js') }}"></script>
<script src="{{ asset('js/form-userprofile.js') }}"></script>
<!-- ================== END PAGE LEVEL JS ================== -->
