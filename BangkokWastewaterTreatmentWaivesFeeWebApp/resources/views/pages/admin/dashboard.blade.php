@extends('layouts.app')

<!-- ================== BEGIN BASE CSS STYLE ================== -->
<link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}" rel="stylesheet" />

<link href="{{ asset('assets/plugins/datatables/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/datatables-select/css/select.dataTables.min.css') }}" rel="stylesheet" />
<!-- ================== END BASE CSS STYLE ================== -->

<style>
#chart_waive_fee, #chart_connect_pipe, #chart_max_waive_fee_district {
  height: 300px !important;
}
#chart_max_waive_fee_district {
  height: 500px !important;
}

</style>

@section('content')

@include('components.header')

<main class="d-flex flex-nowrap">

  @include('components.leftmenu', ['active'=> 'pageadmindashboard'])

  <div class="container-fluid my-4" style="overflow-y: auto;">

    <div class="row justify-content-center align-items-center">
      <div class="col">
        <div class="card mb-3 text-dark border-0" style="background-image: linear-gradient(120deg, #ffecd2 0%, #fcb69f 100%);">
          <div class="row g-0 justify-content-center align-items-center">
            <div class="col-4 text-center">
              <i class="fa-solid fa-hourglass-half fa-3x"></i>
            </div>
            <div class="col-8">
              <div class="card-body ps-0 p-3 text-end">
                <h5 class="card-title">จำนวนเอกสารรอตรวจสอบ</h5>
                <h3 class="card-text" id="lb_total_document_awaiting_verify">0</h3>
                <p class="card-text">case</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card mb-3 text-dark border-0" style="background-image: linear-gradient(120deg, #84fab0 0%, #8fd3f4 100%);">
          <div class="row g-0 justify-content-center align-items-center">
            <div class="col-4 text-center">
              <i class="fa-regular fa-square-check fa-3x"></i>
            </div>
            <div class="col-8">
              <div class="card-body ps-0 p-3 text-end">
                <h5 class="card-title">จำนวนเอกสารรอการอนุมัติ</h5>
                <h3 class="card-text" id="lb_total_document_awaiting_approve">0</h3>
                <p class="card-text">case</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card mb-3 text-dark border-0" style="background-image: linear-gradient(to top, #cfd9df 0%, #e2ebf0 100%);">
          <div class="row g-0 justify-content-center align-items-center">
            <div class="col-4 text-center">
              <i class="fa-solid fa-rectangle-xmark fa-3x"></i>
            </div>
            <div class="col-8">
              <div class="card-body ps-0 p-3 text-end">
                <h5 class="card-title">จำนวนเอกสารได้รับการอนุมัติ</h5>
                <h3 class="card-text" id="lb_total_document_approved">0</h3>
                <p class="card-text">case</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card mb-3 text-dark border-0" style="background-image: linear-gradient(120deg, #a1c4fd 0%, #c2e9fb 100%);">
          <div class="row g-0 justify-content-center align-items-center">
            <div class="col-4 text-center">
              <i class="fa-solid fa-circle-nodes fa-3x"></i>
            </div>
            <div class="col-8">
              <div class="card-body ps-0 p-3 text-end">
                <h5 class="card-title">จำนวนผู้ใช้งานทั้งหมด</h5>
                <h3 class="card-text" id="lb_total_all_user">0</h3>
                <p class="card-text">case</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-6">
        <div class="card">
          <div class="card-body">
            <h5 class="text-muted">จำนวนขอยกเว้นค่าธรรมเนียม (ย้อนหลัง 6 เดือน)</h5>
            <canvas id="chart_last_6m_waive_fee" height="150"></canvas>
          </div>
        </div>
      </div>
      <div class="col-6">
        <div class="card">
          <div class="card-body">
            <h5 class="text-muted">จำนวนขออนุญาตต่อเชื่อมท่อ (ย้อนหลัง 6 เดือน)</h5>
            <canvas id="chart_last_6m_connect_pipe" height="150"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- <div class="card text-muted">
      <div class="card-body">
        <h5 class="text-muted">เขตที่มีขอยกเว้นมากที่สุด 30 เขต</h5>
        <canvas id="chart_max_waive_fee_district" width="800" height="300"></canvas>
      </div>
    </div> -->

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

<script src="{{ asset('assets/plugins/chartjs/js/chart.min.js') }}"></script>

<script src="{{ asset('assets/plugins/moment/js/moment-with-locales.min.js') }}"></script>
<!-- ================== END BASE JS ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="{{ asset('js/web-service-task.js') }}"></script>
<script src="{{ asset('js/dashboard.js') }}"></script>
<!-- ================== END PAGE LEVEL JS ================== -->
