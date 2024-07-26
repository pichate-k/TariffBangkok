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
</style>

@section('content')
<main class="flex-shrink-0">
  <div class="container-fluid h-100">

    <!-- begin #navbar -->
    @include('components.header')
    <!-- end #navbar -->

    <div class="container">
      <!-- begin #navbar -->
      @include('components.navbar', ['active'=> 'pagewaterqualitysubmit'])
      <!-- end #navbar -->


      <!-- Job -->
      <div class="d-flex justify-content-between mt-5">
        <h4 class="text-blue"><i class="fa-solid fa-vial text-warning"></i> ข้อมูลการรายงานผลตรวจวัดคุณภาพน้ำ</h4>
        <button type="button" class="btn btn-blue-bg mb-1" data-bs-toggle='modal' data-bs-target='#modalFormWaterQualiy'><i class="fa-solid fa-plus"></i> อัพโหลดรายงานคุณภาพน้ำ</button>
      </div>
      <div class="card mb-5">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover table-bordered" id="tb_water_quality_submit">
              <thead class="text-center">
                <tr>
                  <td scope="col">ลำดับ</td>
                  <td scope="col" class=" text-start">เลขที่แบบ</td>
                  <td scope="col">รายงานผลเดือน</td>
                  <td scope="col">รายงานผลปี</td>
                  <td scope="col">วันที่ยื่น</td>
                  <td scope="col">สถานะ</td>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>


    </div>
  </div>
</main>


<!-- Modal -->
<div class="modal fade" id="modalFormWaterQualiy" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalFormWaterQualiyLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="modalFormWaterQualiyLabel">แบบฟอร์มรายงานผลตรวจวัดคุณภาพน้ำ</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="fn_waterqulity_submit">
          <div class="mb-3 row justify-content-center">
            <label for="inputPassword" class="col-5 col-form-label text-end">เลขที่แบบ <span class="text-danger">*</span></label>
            <div class="col-7">
              <select class="form-select" name="doc_no">
                <option selected>ไม่พบข้อมูล</option>
              </select>
            </div>
          </div>
          <div class="mb-3 row justify-content-center">
            <label for="inputPassword" class="col-5 col-form-label text-end">ชื่ออาคาร/สถานประกอบการ <span class="text-danger">*</span></label>
            <div class="col-7">
              <input type="text" class="form-control" name="address_name" readonly>
            </div>
          </div>
          <div class="mb-3 row justify-content-center">
            <label for="inputPassword" class="col-5 col-form-label text-end">รหัสผู้ใช้น้ำ <span class="text-danger">*</span></label>
            <div class="col-7">
              <input type="text" class="form-control" name="address_code" readonly>
            </div>
          </div>
          <div class="mb-3 row justify-content-center">
            <label for="inputPassword" class="col-5 col-form-label text-end">รายงานผลปี <span class="text-danger">*</span></label>
            <div class="col-7">
              <input type="text" class="form-control" name="data_year" readonly>
            </div>
          </div>
          <div class="mb-3 row justify-content-center">
            <label for="inputPassword" class="col-5 col-form-label text-end">รายงานผลเดือน <span class="text-danger">*</span></label>
            <div class="col-7">
              <select class="form-select" name="data_month">
                <option selected>ไม่พบข้อมูล</option>
              </select>
            </div>
          </div>
          <div class="mb-3 row justify-content-center">
            <label for="inputPassword" class="col-5 col-form-label text-end">แนบไฟล์</label>
            <div class="col-7">
              <input type="file" class="form-control" accept="image/png, image/jpeg, application/pdf" name="doc_attach_1">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">ยกเลิกและปิด</button>
        <button type="button" class="btn btn-blue-bg" id="btn_save_waterqulity">บันทึกผลตรวจวัด</button>
      </div>
    </div>
  </div>
</div>

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
<script src="{{ asset('js/water-quality-submit.js') }}"></script>
<!-- ================== END PAGE LEVEL JS ================== -->
