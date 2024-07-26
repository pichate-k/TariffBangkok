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
<main class="flex-shrink-0">
  <div class="container-fluid h-100">

    <!-- begin #navbar -->
    @include('components.header')
    <!-- end #navbar -->

    <div class="container">
      <!-- begin #navbar -->
      @include('components.navbar', ['active'=> 'pagedocumentyv2'])
      <!-- end #navbar -->

      <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-4">
          <li class="breadcrumb-item">
            <a href="/">หน้าแรก</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            แบบ ยว.2 คำร้องขอยกเลิกการขอยกเว้นค่าธรรมเนียมบำบัดน้ำเสีย
          </li>
        </ol>
      </nav>

      <div id="layout_nouserprofile" class="d-none">
        @include('components.nouserprofile')
      </div>

      <div id="layout_document" class="d-none">

        <!-- begin #navbar -->
        @include('components.stepper', [
          'step_display1'=> 'กรอกข้อมูลการยื่นแบบ',
          'step_action1'=> 'active',
          'step_display2'=> 'ตรวจสอบความครบถ้วนของเอกสาร',
          'step_action2'=> '',
          'step_display3'=> 'รอเจ้าหน้าที่ตรวจสอบข้อมูล',
          'step_action3'=> '',
          'step_display4'=> 'อนุมัติ / ไม่อนุมัติ (ยื่นเอกสารเพิ่มเติม)',
          'step_action4'=> '',
          'step_display5'=> '',
          'step_action5'=> ''
        ])
        <!-- end #navbar -->

        <div class="card mb-5 mt-3">
          <div class="card-body">

            @if (Request::get("doc_no") != "")
              @include('pages.form.yv2', ['action' => 'readwrite'])

              <!-- Button update -->
              <div class="d-grid gap-2 col-6 mx-auto">
                <button class="btn btn-warning btn-lg" type="button" id="btn_update_document_yv2">บันทึกและยื่นเอกสารเพิ่มเติม</button>
              </div>
            @else
              @include('pages.form.yv2', ['action' => 'freeform'])

              <!-- Button save -->
              <div class="d-grid gap-2 col-6 mx-auto">
                <button class="btn btn-blue-bg btn-lg" type="button" id="btn_create_document_yv2">ยืนยันยื่นแบบ</button>
              </div>
            @endif
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
<!-- ================== END PAGE LEVEL JS ================== -->
