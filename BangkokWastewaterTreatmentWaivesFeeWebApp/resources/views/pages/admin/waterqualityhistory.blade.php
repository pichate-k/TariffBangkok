@extends('layouts.app')

<!-- ================== BEGIN BASE CSS STYLE ================== -->
<link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}" rel="stylesheet" />

<link href="{{ asset('assets/plugins/datatables/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/datatables-select/css/select.dataTables.min.css') }}" rel="stylesheet" />

<link href="{{ asset('assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" />
<!-- ================== END BASE CSS STYLE ================== -->

<style>
</style>

@section('content')

@include('components.header')

<main class="d-flex flex-nowrap">

  @include('components.leftmenu', ['active'=> 'pageadmindocumentwaterqualityhistory'])

  <div class="container-fluid my-4" style="overflow-y: auto;">

    <div class="card">
      <div class="card-body">
        <h4 class="text-blue"><i class="fa-solid fa-clock-rotate-left text-warning"></i> เงื่อนไขการค้นหารายงาน</h4>
        <form id="fm_document_water_quality_search">
          <div class="mb-3 row">
            <label for="inputPassword" class="col-2 col-form-label text-end">วันที่ทำรายการ <span class="text-danger">*</span></label>
            <div class="col-4">
              <input type="text" class="form-control startdatetimepicker" name="start_date">
            </div>
            <label for="inputPassword" class="col-2 col-form-label text-end">ถึงวันที่ <span class="text-danger">*</span></label>
            <div class="col-4">
              <input type="text" class="form-control enddatetimepicker" name="end_date">
            </div>
          </div>
          <div class="mb-3 row">
            <label for="inputPassword" class="col-2 col-form-label text-end">เลขที่แบบ</label>
            <div class="col-4">
              <input type="text" class="form-control" name="doc_no">
            </div>
          </div>
          <div class="d-grid gap-2 col-2 offset-2">
            <button class="btn btn-blue-bg" type="button" id="btn_document_water_quality_search">ค้นหา</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Job -->
    <div class="card mt-3">
      <div class="card-body">
        <h4 class="text-blue"><i class="fa-solid fa-list-ul text-warning"></i> ข้อมูลการรายงานผลคุณภาพน้ำ</h4>
        <div class="table-responsive">
          <table class="table table-hover table-bordered" id="tb_document_water_quality">
            <thead class="text-center">
              <tr>
                <td scope="col">ลำดับ</td>
                <td scope="col" class=" text-start">เลขที่แบบ</td>
                <td scope="col">รายงานผลเดือน</td>
                <td scope="col">รายงานผลปี</td>
                <td scope="col">เอกสารแนบ</td>
                <td scope="col">วันที่ยื่น</td>
                <td scope="col">ชื่อผู้ยื่น</td>
                <td scope="col">สถานะ</td>
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
<script src="{{ asset('assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
<!-- ================== END BASE JS ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="{{ asset('js/web-service-task.js') }}"></script>
<script src="{{ asset('js/waterquality-history-admin.js') }}"></script>
<!-- ================== END PAGE LEVEL JS ================== -->
