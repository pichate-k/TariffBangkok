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
      @include('components.navbar', ['active'=> 'pagedocumenthistory'])
      <!-- end #navbar -->


      <div class="card mt-5">
        <div class="card-body">
          <h4 class="text-blue"><i class="fa-solid fa-clock-rotate-left text-warning"></i> เงื่อนไขการค้นหาการยื่นแบบ</h4>
          <form id="fm_document_search">
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
              <label for="inputPassword" class="col-2 col-form-label text-end">ประเภทรายการ <span class="text-danger">*</span></label>
              <div class="col-4">
                <select class="form-select" name="doc_type">
                  <option value="ALL">ทั้งหมด</option>
                  <option value="YV1">แบบ ยว.1</option>
                  <option value="YV2">แบบ ยว.2</option>
                  <option value="RB1">แบบ รบ.1</option>
                  <option value="PG1">แบบ ปก.1</option>
                  <option value="PG2">แบบ ปก.2</option>
                  <option value="NT1">แบบ นท.1</option>
                  <option value="NT2">แบบ นท.2</option>
                </select>
              </div>
              <label for="inputPassword" class="col-2 col-form-label text-end">เลขที่แบบ</label>
              <div class="col-4">
                <input type="text" class="form-control" name="doc_no">
              </div>
            </div>
            <div class="d-grid gap-2 col-2 offset-2">
              <button class="btn btn-blue-bg" type="button" id="btn_document_search">ค้นหา</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Job -->
      <h4 class="pt-5 text-blue"><i class="fa-solid fa-list-ul text-warning"></i> รายการยื่นแบบ</h4>
      <div class="card mb-5">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover" id="tb_document_history">
              <thead class="text-center">
                <tr>
                  <td scope="col" class="col-1">ลำดับ</td>
                  <td scope="col" class="col-3 text-start">เลขที่แบบ</td>
                  <td scope="col" class="col-2">แบบคำร้อง/คำขอ/รายงาน</td>
                  <td scope="col" class="col-2">วันที่ยื่น</td>
                  <td scope="col" class="col-2">สถานะ</td>
                  <td scope="col" class="col-2">รายละเอียด</td>
                </tr>
              </thead>
            </table>
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
<script src="{{ asset('assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
<!-- ================== END BASE JS ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="{{ asset('js/web-service-task.js') }}"></script>
<script src="{{ asset('js/document-history.js') }}"></script>
<!-- ================== END PAGE LEVEL JS ================== -->
