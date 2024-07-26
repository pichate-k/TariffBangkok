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
      @include('components.navbar', ['active'=> 'pageuserwastewateraddress'])
      <!-- end #navbar -->


      <div class="card my-5">
        <div class="card-body">
          <h4 class="text-blue"><i class="fa-solid fa-list-ul text-warning"></i> รายการแหล่งกำเนิดน้ำเสีย</h4>
          <div class="table-responsive">
            <table class="table" id="tb_user_wastewater_address">
              <thead class="text-center">
                <tr>
                  <td scope="col">ลำดับ</td>
                  <td scope="col" class=" text-start">รหัสผู้ใช้น้ำ</td>
                  <td scope="col" class=" text-start">ทะเบียนผู้ใช้น้ำ</td>
                  <td scope="col">ชื่อผู้ใช้น้ำ กปน.</td>
                  <td scope="col">สถานะผู้ใช้น้ำ กปน.</td>
                  <td scope="col">รหัสผู้ใช้น้ำ กปน.</td>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>

      <div class="card my-5">
        <div class="card-body">
          <h4 class="text-blue"><i class="fa-solid fa-clock-rotate-left text-warning"></i> ข้อมูลแหล่งกำเนิดน้ำเสีย</h4>
          <form id="fm_user_profile">
            <h5 class="mt-3">ประเภทแหล่งกำเนิดน้ำเสีย</h5>
            <div class="mb-3 row align-items-center">
              <div class="col-4 offset-2">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="inlineRadioOptions2" id="inlineRadio1" value="option1" checked>
                  <label class="form-check-label" for="inlineRadio1">บุคคลธรรมดา</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="inlineRadioOptions2" id="inlineRadio2" value="option2">
                  <label class="form-check-label" for="inlineRadio2">นิติบุคคล</label>
                </div>
              </div>
            </div>
            <div class="mb-3 row align-items-center">
              <label for="inputPassword" class="col-2 col-form-label text-end">เลขประจำตัวประชาชน/ทะเบียนนิติบุคคล</label>
              <div class="col-4">
                <input type="text" class="form-control" name="telephone">
              </div>
            </div>
            <div class="mb-3 row align-items-center">
              <label for="inputPassword" class="col-2 col-form-label text-end">รหัสผู้ใช้น้ำ</label>
              <div class="col-4">
                <input type="text" class="form-control" name="telephone">
              </div>
              <label for="inputPassword" class="col-2 col-form-label text-end">ทะเบียนผู้ใช้น้ำ</label>
              <div class="col-4">
                <input type="text" class="form-control" name="telephone">
              </div>
            </div>

            <hr>
            <h5 class="mt-3">ข้อมูลการประปานครหลวง</h5>
            <div class="mb-3 row align-items-center">
              <label for="inputPassword" class="col-2 col-form-label text-end">ชื่อผู้ใช้น้ำ (กปน.)</label>
              <div class="col-4">
                <input type="text" class="form-control" name="email">
              </div>
            </div>
            <div class="mb-3 row align-items-center">
              <label for="inputPassword" class="col-2 col-form-label text-end">รหัสประเภทผู้ใช้น้ำ กปน.</label>
              <div class="col-4">
                <input type="text" class="form-control" name="email">
              </div>
              <label for="inputPassword" class="col-2 col-form-label text-end">รหัสประเภท กทม.</label>
              <div class="col-4">
                <input type="text" class="form-control" name="telephone">
              </div>
            </div>

            <hr>
            <h5 class="mt-3">ที่อยู่แหล่งกำเนิดน้ำเสีย</h5>
            <div class="mb-3 row align-items-center">
              <label for="inputPassword" class="col-2 col-form-label text-end">ตั้งอยู่เลขที่</label>
              <div class="col-10">
                <input type="text" class="form-control" name="telephone">
              </div>
            </div>
            <div class="mb-3 row align-items-center">
              <label for="inputPassword" class="col-2 col-form-label text-end">จังหวัด</label>
              <div class="col-4">
                <select class="form-select" name="province_code">
                </select>
              </div>
              <label for="inputPassword" class="col-2 col-form-label text-end">อำเภอ/เขต</label>
              <div class="col-4">
                <select class="form-select" name="district_code">
                </select>
              </div>
            </div>
            <div class="mb-3 row align-items-center">
              <label for="inputPassword" class="col-2 col-form-label text-end">ตำบล/แขวง</label>
              <div class="col-4">
                <select class="form-select" name="sub_district_code">
                </select>
              </div>
              <label for="inputPassword" class="col-2 col-form-label text-end">รหัสไปรษณีย์</label>
              <div class="col-4">
                <input type="number" class="form-control" name="zip_code">
              </div>
            </div>
            <div class="mb-3 row align-items-center">
              <label for="inputPassword" class="col-2 col-form-label text-end">ละติจูด</label>
              <div class="col-4">
                <input type="text" class="form-control" name="email">
              </div>
              <label for="inputPassword" class="col-2 col-form-label text-end">ลองจิจูด</label>
              <div class="col-4">
                <input type="text" class="form-control" name="telephone">
              </div>
            </div>

            <!-- Button save -->
            <div class="d-grid gap-2 col-6 mx-auto">
              <button class="btn btn-blue-bg btn-lg" type="button" id="btn_update_user_profile">บันทึกข้อมูลแหล่งกำเนิดน้ำเสีย</button>
            </div>
          </form>
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
<script src="{{ asset('js/wastewater-address.js') }}"></script>
<!-- ================== END PAGE LEVEL JS ================== -->
