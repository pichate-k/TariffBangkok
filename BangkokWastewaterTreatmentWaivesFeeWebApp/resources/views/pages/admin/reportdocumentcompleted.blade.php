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

  @include('components.leftmenu', ['active'=> 'pageadminreportdocumentcompleted'])

  <div class="container-fluid my-4" style="overflow-y: auto;">

    <div class="card mt-3">
      <div class="card-body">
        <h4 class="text-blue">
          <i class="fa-solid fa-list-check text-warning"></i> รายงานผู้ได้รับการตรวจสอบเอกสารถูกต้อง
        </h4>
        <a href="/service/doc/reportUserDocumentCompleteExcel" type="button" class="btn btn-blue-bg" target="_blank">ดาวน์โหลดใบแปะหน้า</a>
        <div class="table-responsive">
          <table class="table table-hover table-bordered" id="tb_document_list">
            <thead class="text-center">
              <tr>
                <td scope="col">ลำดับ</td>
                <td scope="col" class=" text-start">เลขที่แบบ</td>
                <td scope="col" class=" text-start">แบบคำร้อง/คำขอ/รายงาน</td>
                <td scope="col">ชื่อผู้ยื่นแบบ</td>
                <td scope="col">วันที่ยื่น</td>
                <td scope="col">สถานะ</td>
                <td scope="col">รายละเอียด</td>
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
<script src="{{ asset('js/report-document-completed.js') }}"></script>
<script src="{{ asset('js/button-view-document.js') }}"></script>
<!-- ================== END PAGE LEVEL JS ================== -->
