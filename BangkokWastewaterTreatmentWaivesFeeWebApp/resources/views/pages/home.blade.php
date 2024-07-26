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
      @include('components.navbar', ['active'=> 'pagehome'])
      <!-- end #navbar -->


      <!-- Job -->
      <h4 class="pt-5 text-blue"><i class="fa-solid fa-list-ul text-warning"></i> รายการยื่นแบบ</h4>
      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover" id="tb_document_inprocess">
              <thead class="text-center">
                <tr>
                  <td scope="col" class="col-1">ลำดับ</td>
                  <td scope="col" class="col-3 text-start">เลขที่แบบ</td>
                  <td scope="col" class="col-2">แบบคำร้อง/คำขอ/รายงาน</td>
                  <td scope="col" class="col-2">วันที่ยื่น</td>
                  <td scope="col" class="col-2">สถานะ</td>
                  <td scope="col" class="col-2">รายละเอียด</td>
                  <td scope="col" class="col-2">ยกเลิก</td>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>


      <!-- Information -->
      <!-- <h4 class="pt-5 text-blue"><i class="fa-solid fa-bullhorn text-warning"></i> ข่าวสารประชาสัมพันธ์</h4>
      <ul class="list-group">
        <li class="list-group-item p-2">
          <a href="{{ asset('files/ข้อบัญญัติฯ ค่าธรรมเนียมฯ (ฉบับที่2) พ.ศ. 2562.pdf') }}" class="btn">1.  ข้อบัญญัติกรุงเทพมหานคร เรื่อง การจัดเก็บค่าธรรมเนียมบำบัดน้ำเสีย (ฉบับที่ 2) พ.ศ. 2562 <span class="badge bg-primary">New</span></a>
        </li>
        <li class="list-group-item p-2">
          <a href="{{ asset('files/ข้อบัญญัติฯ ค่าธรรมเนียมฯ พ.ศ. 2547.pdf') }}" class="btn">2.  ข้อบัญญัติกรุงเทพมหานคร เรื่อง การจัดเก็บค่าธรรมเนียมบำบัดน้ำเสีย พ.ศ. 2547</a>
        </li>
      </ul> -->


      <!-- Information -->
      <h4 class="pt-5 text-blue"><i class="fa-solid fa-droplet text-warning"></i> รายการแบบ</h4>
      <ul class="list-group mb-3">
        <li class="list-group-item py-0">
          <div class="d-flex">
            <div class="p-2 w-100"><a href="#" class="btn">แบบ ยว.1 คำร้องขอยกเว้นค่าธรรมเนียมบำบัดน้ำเสีย</a></div>
            <div class="p-2 flex-shrink-1"><a type="button" href="/u/document/yv1.htm" class="btn btn-blue-bg btn-sm text-nowrap">ยื่นแบบ</a></div>
          </div>
        </li>
        <li class="list-group-item py-0">
          <div class="d-flex">
            <div class="p-2 w-100"><a href="#" class="btn">แบบ ยว.2 คำร้องขอยกเลิกการขอยกเว้นค่าธรรมเนียมบำบัดน้ำเสีย</a></div>
            <div class="p-2 flex-shrink-1"><a type="button" href="/u/document/yv2.htm" class="btn btn-blue-bg btn-sm text-nowrap">ยื่นแบบ</a></div>
          </div>
        </li>
        <li class="list-group-item py-0">
          <div class="d-flex">
            <div class="p-2 w-100"><a href="#" class="btn">แบบ รบ.1 คำขอรับบริการบำบัดน้ำเสียของกรุงเทพมหานคร</a></div>
            <div class="p-2 flex-shrink-1"><a type="button" href="/u/document/rb1.htm" class="btn btn-blue-bg btn-sm text-nowrap">ยื่นแบบ</a></div>
          </div>
        </li>
        <li class="list-group-item py-0">
          <div class="d-flex">
            <div class="p-2 w-100"><a href="#" class="btn">แบบ ปก.1 คำขอรายงานปริมาณการใช้น้ำหรือปริมาณน้ำเสีย</a></div>
            <div class="p-2 flex-shrink-1"><a type="button" href="/u/document/pg1.htm" class="btn btn-blue-bg btn-sm text-nowrap">ยื่นแบบ</a></div>
          </div>
        </li>
        <li class="list-group-item py-0">
          <div class="d-flex">
            <div class="p-2 w-100"><a href="#" class="btn">แบบ ปก.2 รายงานปริมาณการใช้น้ำหรือปริมาณน้ำเสียโดยติดตั้งอุปกรณ์วัดปริมาณน้ำ</a></div>
            <div class="p-2 flex-shrink-1"><a type="button" href="/u/document/pg2.htm" class="btn btn-blue-bg btn-sm text-nowrap">ยื่นแบบ</a></div>
          </div>
        </li>
        <li class="list-group-item py-0">
          <div class="d-flex">
            <div class="p-2 w-100"><a href="#" class="btn">แบบ นท.1 คำขอติดตั้งอุปกรณ์วัดปริมาณน้ำเสียจากแหล่งกำเนิดน้ำเสียที่ใช้น้ำประปา</a></div>
            <div class="p-2 flex-shrink-1"><a type="button" href="/u/document/nt1.htm" class="btn btn-blue-bg btn-sm text-nowrap">ยื่นแบบ</a></div>
          </div>
        </li>
        <li class="list-group-item py-0">
          <div class="d-flex">
            <div class="p-2 w-100"><a href="#" class="btn">แบบ นท.2 รายงานปริมาณน้ำเสียโดยติดตั้งอุปกรณ์วัดปริมาณน้ำเสียจากแหล่งกำเนิดน้ำเสียที่ใช้น้ำประปา</a></div>
            <div class="p-2 flex-shrink-1"><a type="button" href="/u/document/nt2.htm" class="btn btn-blue-bg btn-sm text-nowrap">ยื่นแบบ</a></div>
          </div>
        </li>
      </ul>


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
<script src="{{ asset('js/document-status.js') }}"></script>
<script src="{{ asset('js/home.js') }}"></script>
<!-- ================== END PAGE LEVEL JS ================== -->
