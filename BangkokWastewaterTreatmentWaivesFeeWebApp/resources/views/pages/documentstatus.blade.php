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
      @include('components.navbar', ['active'=> 'pagedocumentstatus'])
      <!-- end #navbar -->


      <!-- Job -->
      <h4 class="mt-5 text-blue"><i class="fa-solid fa-list-ul text-warning"></i> รายการยื่นแบบ</h4>
      <div class="card mb-5">
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
<!-- ================== END PAGE LEVEL JS ================== -->
